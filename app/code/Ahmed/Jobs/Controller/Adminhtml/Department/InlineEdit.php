<?php

namespace Ahmed\Jobs\Controller\Adminhtml\Department;

use Ahmed\Jobs\Model\DepartmentFactory;
use Ahmed\Jobs\Model\ResourceModel\Department as DepartmentResource;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class InlineEdit extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Ahmed_Jobs::department_save';

    /**
     * @var JsonFactory
     */
    private JsonFactory $jsonFactory;

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
     * @param JsonFactory $jsonFactory
     * @param DepartmentFactory $departmentFactory
     * @param DepartmentResource $departmentResource
     */
    public function __construct(
        Action\Context $context,
        JsonFactory $jsonFactory,
        DepartmentFactory $departmentFactory,
        DepartmentResource $departmentResource
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->departmentFactory = $departmentFactory;
        $this->departmentResource = $departmentResource;
        parent::__construct($context);
    }

    /**
     * Save department grid inline edits.
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $messages = [];
        $error = false;
        $items = $this->getRequest()->getParam('items', []);

        if (!$this->getRequest()->getParam('isAjax') || !count($items)) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach ($items as $departmentId => $data) {
            $department = $this->departmentFactory->create();
            $this->departmentResource->load($department, (int) $departmentId);

            if (!$department->getId()) {
                $messages[] = __('Department ID %1 no longer exists.', $departmentId);
                $error = true;
                continue;
            }

            try {
                $department->addData($data);
                $this->departmentResource->save($department);
            } catch (\Exception $e) {
                $messages[] = __('Something went wrong while saving department ID %1.', $departmentId);
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error,
        ]);
    }
}
