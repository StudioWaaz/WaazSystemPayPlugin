<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace Waaz\SystemPayPlugin\Bridge;

use Waaz\SystemPayPlugin\Legacy\SystemPay;
use Symfony\Component\HttpFoundation\RequestStack;
use Psr\Log\LoggerInterface;
/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
final class SystemPayBridge implements SystemPayBridgeInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var string
     */
    private $merchantId;

    /**
     * @var string
     */
    private $environment;

    private $logger;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack, LoggerInterface $logger)
    {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function createSystemPay($secretKey)
    {
        return new SystemPay($secretKey);
    }

    /**
     * {@inheritDoc}
     */
    public function paymentVerification()
    {
        $this->logger->info('Test autoresponse');
        if ($this->isPostMethod()) {
            $this->logger->info('Post = true');
            $paymentResponse = new SystemPay($this->secretKey);
            $postdata = $this->getPostData();
            $this->logger->info('Postdata '.json_encode($postdata));
            return $paymentResponse->responseHandler($postdata);
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function isPostMethod()
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        return $currentRequest->isMethod('POST');
    }

    /**
     * @return array
     */
     public function getPostData()
     {
       $currentRequest = $this->requestStack->getCurrentRequest();

       return $currentRequest->request->all();
     }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @param string $secretKey
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @param string $merchantId
     */
    public function setMerchantId($merchantId)
    {
        $this->merchantId = $merchantId;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param string $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }
}
