<?php

namespace Ahmed\Jobs\Controller\Adminhtml\Department;

class Index extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Ahmed_Jobs::department';

    const PAGE_TITLE = 'Departments';

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_pageFactory->create();
        $resultPage->setActiveMenu('Ahmed_Jobs::department');
        $resultPage->addBreadcrumb(__(static::PAGE_TITLE), __(static::PAGE_TITLE));
        $resultPage->getConfig()->getTitle()->prepend(__(static::PAGE_TITLE));

        return $resultPage;


        // /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        // $resultPage = $this->_pageFactory->create();
        // $resultPage->setActiveMenu('Ahmed_Jobs::department');
        // $resultPage->addBreadcrumb(__('Jobs'), __('Jobs'));
        // $resultPage->addBreadcrumb(__('Manage Departments'), __('Manage Departments'));
        // $resultPage->getConfig()->getTitle()->prepend(__('Department'));

        // return $resultPage;
    }

    /**
     * Is the user allowed to view the page.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(static::ADMIN_RESOURCE);
    }
}
