<?php

namespace Ahmed\Jobs\Controller\Department;

use Ahmed\Jobs\Model\Department;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class View extends Action implements HttpGetActionInterface
{
    protected Department $_model;

    protected PageFactory $_pageFactory;

    protected Registry $_registry;

    public function __construct(
        Context $context,
        Department $model,
        PageFactory $pageFactory,
        Registry $registry
    ) {
        $this->_model = $model;
        $this->_pageFactory = $pageFactory;
        $this->_registry = $registry;

        parent::__construct($context);
    }

    /**
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

        $this->_registry->register('ahmed_jobs_current_department', $model);

        return $this->_pageFactory->create();
    }
}
