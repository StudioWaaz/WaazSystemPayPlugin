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

    /**
     * @var string
     */
    private $paymentCards;

    /**
     * @var bool
     */
    private $useOldSecurity;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
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
        if ($this->isPostMethod()) {
            $paymentResponse = $this->createSystemPay($this->secretKey);
            $postdata = $this->getPostData();
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

    /**
     * {@inheritDoc}
     */
    public function getPaymentCards()
    {
        return $this->paymentCards;
    }

    /**
     * {@inheritDoc}
     */
    public function setPaymentCards($paymentCards)
    {
        $this->paymentCards = $paymentCards;
    }

    /**
     * {@inheritDoc}
     */
    public function useOldSecurity()
    {
        return $this->useOldSecurity;
    }

    /**
     * {@inheritDoc}
     */
    public function setUseOldSecurity($useOldSecurity)
    {
        $this->useOldSecurity = $useOldSecurity;
    }
}
