<?php

namespace Ahmed\Jobs\Controller\Cookie;


use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;


class Testgetcookie implements HttpGetActionInterface
{

    public function __construct(
        protected CookieManagerInterface $cookieManager,
        protected RawFactory $resultRawFactory
    ) {}

    public function execute(): ResultInterface
    {
        $cookieValue = $this->cookieManager->getCookie(Testaddcookie::JOB_COOKIE_NAME);

        $result = $this->resultRawFactory->create();
        $result->setContents($cookieValue);

        return $result;
    }
}
