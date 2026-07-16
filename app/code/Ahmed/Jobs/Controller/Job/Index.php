<?php

namespace Ahmed\Jobs\Controller\Job;

class Index extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    public function execute()
    {
        // return phpinfo();
        $this->_view->loadLayout();
        // $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}
