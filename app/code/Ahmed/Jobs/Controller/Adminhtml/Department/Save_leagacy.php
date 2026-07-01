<?php

namespace Ahmed\Jobs\Controller\Adminhtml\Department;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;

class Save extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Ahmed_Jobs::department_save';

    const PAGE_TITLE = 'Manage Departments';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;
    private \Ahmed\Jobs\Model\Department $_model;
    /**
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Ahmed\Jobs\Model\Department $model
    ) {
        $this->_model = $model;
        return parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Framework\View\Result\Page
     */

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            /** @var \Ahmed\Jobs\Model\Department $model */
            $model = $this->_model;

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);

            $this->_eventManager->dispatch(
                'jobs_department_prepare_save',
                ['department' => $model, 'request' => $this->getRequest()]
            );

            try {
                $model->save();
                $this->messageManager->addSuccess(__('Department saved'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the department'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('id')]);
        }
    }
}
