<?php

namespace Ahmed\Jobs\Controller\Adminhtml\Department;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Ahmed_Jobs::department_save';

    public function __construct(
        Action\Context $context,
        private PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Render the department UI component form.
     *
     * @return Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage
            ->setActiveMenu('Ahmed_Jobs::department')
            ->addBreadcrumb(__('Department'), __('Department'))
            ->addBreadcrumb(__('Manage Departments'), __('Manage Departments'));

        $isEdit = (bool) $this->getRequest()->getParam('id');
        $resultPage->addBreadcrumb(
            $isEdit ? __('Edit Department') : __('New Department'),
            $isEdit ? __('Edit Department') : __('New Department')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Departments'));
        $resultPage->getConfig()->getTitle()->prepend($isEdit ? __('Edit Department') : __('New Department'));

        return $resultPage;
    }
}
