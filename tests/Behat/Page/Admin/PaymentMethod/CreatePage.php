<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace Tests\Waaz\SystemPayPlugin\Behat\Page\Admin\PaymentMethod;

use Behat\Mink\Element\NodeElement;
use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    /**
     * {@inheritdoc}
     */
    public function setSystemPayPluginGatewaySecretKey($secretKey)
    {
        $this->getDocument()->fillField('Secure key', $secretKey);
    }

    /**
     * {@inheritdoc}
     */
    public function setSystemPayPluginGatewayMerchantId($merchantId)
    {
        $this->getDocument()->fillField('Merchant ID', $merchantId);
    }

    /**
     * {@inheritdoc}
     */
    public function setSystemPayPluginGatewayEnvironment($environment)
    {
        $this->getDocument()->selectFieldOption('Environment', $environment);
    }

    /**
     * {@inheritdoc}
     */
    public function findValidationMessage($message)
    {
        $elements = $this->getDocument()->findAll('css', '.sylius-validation-error');

        /** @var NodeElement $element */
        foreach ($elements as $element) {
            if ($element->getText() === $message) {
                return true;
            }
        }

        return false;
    }
}
