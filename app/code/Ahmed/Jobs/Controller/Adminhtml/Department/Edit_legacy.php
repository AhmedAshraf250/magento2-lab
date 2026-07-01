<?php

namespace Ahmed\Jobs\Controller\Adminhtml\Department;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use \Ahmed\Jobs\Model\Department;

class Edit_legacy extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Ahmed_Jobs::department_save';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    protected Department $_model;

    private PageFactory $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param Department $model
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        Department $model
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_model = $model;
        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage
            ->setActiveMenu('Ahmed_Jobs::department')
            ->addBreadcrumb(__('Department'), __('Department'))
            ->addBreadcrumb(__('Manage Departments'), __('Manage Departments'));
        return $resultPage;
    }

    /**
     * Edit or create department page.
     *
     * @return Page
     */
    public function execute()
    {
        $idParam = $this->getRequest()->getParam('id');
        $id = (int) $idParam;
        $model = $this->_model;

        if ($idParam !== null && $id <= 0) {
            $this->messageManager->addErrorMessage(__('Invalid department ID.'));
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath('*/*/');
        }

        // If you have got an id, it's edition
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This department not exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }
        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('jobs_department', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Department') : __('New Department'),
            $id ? __('Edit Department') : __('New Department')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Departments'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getName() : __('New Department'));

        return $resultPage;
    }
}
