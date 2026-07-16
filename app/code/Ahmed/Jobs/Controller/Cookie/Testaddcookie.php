<?php

namespace Ahmed\Jobs\Controller\Cookie;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;

class Testaddcookie implements HttpGetActionInterface
{
    public const JOB_COOKIE_NAME = 'jobs';
    public const JOB_COOKIE_DURATION = 86400; // lifetime in seconds

    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var CookieMetadataFactory
     */
    protected $cookieMetadataFactory;

    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    public function __construct(
        Context $context,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        RawFactory $resultRawFactory
    ) {
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->resultRawFactory = $resultRawFactory;
    }

    public function execute(): ResultInterface
    {
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            // ->setPath('YOUR PATH')
            // ->setDomain('YOUR DOMAIN')
            ->setDuration(self::JOB_COOKIE_DURATION);

        $this->cookieManager->setPublicCookie(
            self::JOB_COOKIE_NAME,
            'MY COOKIE VALUE',
            $metadata
        );

        $result = $this->resultRawFactory->create();
        $result->setContents('COOKIE OK');

        return $result;
    }
}
