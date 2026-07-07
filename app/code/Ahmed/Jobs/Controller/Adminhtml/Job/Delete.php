<?php

namespace Ahmed\Jobs\Controller\Adminhtml\Job;

use Magento\Backend\App\Action;

class Delete extends Action
{
    const ADMIN_RESOURCE = 'Ahmed_Jobs::job_delete';
    protected $_model;

    /**
     * @param Action\Context $context
     * @param \Ahmed\Jobs\Model\Job $model
     */
    public function __construct(
        Action\Context $context,
        \Ahmed\Jobs\Model\Job $model
    ) {
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
                $model = $this->_model;
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('Job deleted'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('Job does not exist'));
        return $resultRedirect->setPath('*/*/');
    }
}
