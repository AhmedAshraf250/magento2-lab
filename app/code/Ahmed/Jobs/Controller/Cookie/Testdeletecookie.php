<?php

namespace Ahmed\Jobs\Controller\Cookie;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

class Testdeletecookie implements HttpGetActionInterface
{
    public function __construct(
        protected CookieManagerInterface $cookieManager,
        protected RawFactory $resultRawFactory,
        protected CookieMetadataFactory $cookieMetadataFactory
    ) {}

    public function execute(): ResultInterface
    {
        $this->cookieManager->deleteCookie(
            Testaddcookie::JOB_COOKIE_NAME,
            // $this->cookieMetadataFactory->createCookieMetadata()
            //     ->setPath('YOUR PATH')
            //     ->setDomain('YOUR DOMAIN')
        );

        $result = $this->resultRawFactory->create();
        $result->setContents('Cookie deleted');

        return $result;
    }
}
