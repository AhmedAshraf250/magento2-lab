<?php

namespace Ahmed\Jobs\Controller\Adminhtml\Department;

use Ahmed\Jobs\Model\DepartmentFactory;
use Ahmed\Jobs\Model\ResourceModel\Department as DepartmentResource;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Ahmed_Jobs::department_save';

    /**
     * @var DepartmentFactory
     */
    private DepartmentFactory $departmentFactory;

    /**
     * @var DepartmentResource
     */
    private DepartmentResource $departmentResource;

    /**
     * @param Action\Context $context
     * @param DepartmentFactory $departmentFactory
     * @param DepartmentResource $departmentResource
     */
    public function __construct(
        Action\Context $context,
        DepartmentFactory $departmentFactory,
        DepartmentResource $departmentResource
    ) {
        $this->departmentFactory = $departmentFactory;
        $this->departmentResource = $departmentResource;
        parent::__construct($context);
    }

    /**
     * Save department.
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        /** @var \Magento\Framework\App\Request\Http $request */
        $request = $this->getRequest();
        $data = $request->getPostValue();
        /* [REQUEST POST DATA (form data)]
            entity_id:
            name:ahmed
            description:ahmed Description
            form_key:M0dmnYuZdUkx8gDm
        */

        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }

        $data = isset($data['data']) && is_array($data['data']) ? $data['data'] : $data;
        unset($data['form_key'], $data['key']);
        if (empty($data['entity_id'])) {
            unset($data['entity_id']);
        }

        $id = (int) ($request->getParam('id') ?: ($data['entity_id'] ?? 0));
        $department = $this->departmentFactory->create();

        if ($id) {
            $this->departmentResource->load($department, $id);
            if (!$department->getId()) {
                $this->messageManager->addErrorMessage(__('This department no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        }

        $department->addData($data);

        $this->_eventManager->dispatch(
            'jobs_department_prepare_save',
            ['department' => $department, 'request' => $this->getRequest()]
        );

        try {
            $this->departmentResource->save($department);
            $this->messageManager->addSuccessMessage(__('Department saved'));
            $this->_getSession()->setFormData(false);

            if ($request->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $department->getId(), '_current' => true]);
            }

            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\RuntimeException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('Something went wrong while saving the department')
            );
        }

        $this->_getSession()->setFormData($data);
        return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
    }
}
