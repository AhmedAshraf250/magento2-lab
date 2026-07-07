<?php

namespace Ahmed\Jobs\Controller\Job;

use Ahmed\Jobs\Model\Job;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class View extends Action implements HttpGetActionInterface
{
    protected PageFactory $_pageFactory;

    protected Job $_model;

    protected Registry $_registry;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        Job $model,
        Registry $registry
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_model = $model;
        $this->_registry = $registry;

        parent::__construct($context);
    }

    /**
     * View page action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->_model;

        if (empty($id)) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }

        $model->load($id);

        if (!$model->getId()) {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/');
        }

        $this->_registry->register('ahmed_jobs_current_job', $model);

        return $this->_pageFactory->create();
    }
}
