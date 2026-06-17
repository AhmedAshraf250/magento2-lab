<?php

namespace Ahmed\Helloworld\Controller\Index;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;

class Index extends \Magento\Framework\App\Action\Action
{

    protected PageFactory $_pageFactory;

    public function __construct(Context $context, PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }
    public function execute()
    {
        // echo 'Execute Action Say_Index OK';
        // die();

        $this->_view->loadLayout();

        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $this->_view->getLayout();
        $layout->initMessages();
        $this->_view->renderLayout();

        // return $this->_pageFactory->create();
    }
}
