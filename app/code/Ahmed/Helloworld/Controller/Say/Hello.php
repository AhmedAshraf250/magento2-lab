<?php

namespace Ahmed\Helloworld\Controller\Say;


use Magento\Framework\Controller\ResultFactory;


class Hello extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $this->_view->loadLayout();

        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $this->_view->getLayout();
        $layout->initMessages();
        $this->_view->renderLayout();
    }
}
