<?php

namespace Ahmed\Jobs\Controller\Adminhtml\Department;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Ahmed\Jobs\Model\Department;

class Delete extends Action implements HttpPostActionInterface
{
    protected Department $_model;
    const ADMIN_RESOURCE = 'Ahmed_Jobs::department_delete';

    public function __construct(Context $context, Department $model)
    {
        parent::__construct($context);
        $this->_model = $model;
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_model->load($id);
                if (!$model->getId()) {
                    $this->messageManager->addError(__('Department does not exist'));
                    return $resultRedirect->setPath('*/*/');
                }

                $model->delete();
                $this->messageManager->addSuccess(__('Department deleted'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('Department does not exist'));
        return $resultRedirect->setPath('*/*/');
    }
}
