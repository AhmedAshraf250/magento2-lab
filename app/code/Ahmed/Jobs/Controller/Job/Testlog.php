<?php

namespace Ahmed\Jobs\Controller\Job;

use Ahmed\Jobs\Logger\Logger as JobsLogger;
use JsonException;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Result\PageFactory;

class Testlog extends Action implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $_pageFactory;
    protected $_logger;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        JobsLogger $logger
    ) {
        $this->_pageFactory = $pageFactory;
        $this->_logger = $logger;
        parent::__construct($context);
    }
    /**
     * View page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // var_dump(get_class($this->_logger));

        $this->_logger->debug('My debug log');
        $this->_logger->info('My info log');
        $this->_logger->notice('My notice log');
        $this->_logger->warning('My warning log');
        $this->_logger->error('My error log');
        $this->_logger->critical('My critical log');
        $this->_logger->alert('My alert log');
        $this->_logger->emergency('My emergency log');

        return $this->_pageFactory->create();

        // $this->_view->loadLayout();
        // $this->_view->getLayout()->initMessages();
        // $this->_view->renderLayout();
    }
}
