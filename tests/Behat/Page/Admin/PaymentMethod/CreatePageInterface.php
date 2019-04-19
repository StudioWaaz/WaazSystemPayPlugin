<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace Tests\Waaz\SystemPayPlugin\Behat\Page\Admin\PaymentMethod;

use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
interface CreatePageInterface extends BaseCreatePageInterface
{
    /**
     * @param string $secretKey
     */
    public function setSystemPayPluginGatewaySecretKey($secretKey);

    /**
     * @param string $merchantId
     */
    public function setSystemPayPluginGatewayMerchantId($merchantId);

    /**
     * @param string $environment
     */
    public function setSystemPayPluginGatewayEnvironment($environment);

    /**
     * @param string $message
     *
     * @return bool
     */
    public function findValidationMessage($message);
}
