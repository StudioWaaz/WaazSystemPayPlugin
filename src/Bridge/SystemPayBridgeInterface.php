<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace Waaz\SystemPayPlugin\Bridge;

use Waaz\SystemPayPlugin\Legacy\SimplePay;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
interface SystemPayBridgeInterface
{
    /**
     * @param string $secretKey
     *
     * @return SimplePay
     */
    public function createSystemPay($secretKey);

    /**
     * @return bool
     */
    public function paymentVerification();

    /**
     * @return bool
     */
    public function isPostMethod();

    /**
     * @return string
     */
    public function getSecretKey();

    /**
     * @param string $secretKey
     */
    public function setSecretKey($secretKey);

    /**
     * @return string
     */
    public function getMerchantId();

    /**
     * @param string $merchantId
     */
    public function setMerchantId($merchantId);

    /**
     * @return string
     */
    public function getEnvironment();

    /**
     * @param string $environment
     */
    public function setEnvironment($environment);
}
