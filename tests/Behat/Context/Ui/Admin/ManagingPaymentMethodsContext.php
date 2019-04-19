<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace Tests\Waaz\SystemPayPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Tests\Waaz\SystemPayPlugin\Behat\Page\Admin\PaymentMethod\CreatePageInterface;
use Webmozart\Assert\Assert;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
final class ManagingPaymentMethodsContext implements Context
{
    /**
     * @var CreatePageInterface
     */
    private $createPage;

    /**
     * @param CreatePageInterface $createPage
     */
    public function __construct(CreatePageInterface $createPage)
    {
        $this->createPage = $createPage;
    }

    /**
     * @Given I want to create a new payment method with Mercanet BNP Paribas gateway factory
     */
    public function iWantToCreateANewPaymentMethodWithSystemPayGatewayFactory()
    {
        $this->createPage->open(['factory' => 'system_pay']);
    }

    /**
     * @When I configure it with test Mercanet BNP Paribas credentials
     */
    public function iConfigureItWithTestSystemPayCredentials()
    {
        $this->createPage->setSystemPayPluginGatewaySecretKey('test');
        $this->createPage->setSystemPayPluginGatewayMerchantId('test');
        $this->createPage->setSystemPayPluginGatewayEnvironment('Test');
    }

    /**
     * @Then I should be notified that the secure key is invalid
     */
    public function iShouldBeNotifiedThatTheSecureKeyIsInvalid()
    {
        Assert::true($this->createPage->findValidationMessage('Please enter the Security Code.'));
    }

    /**
     * @Then I should be notified that the merchant ID is invalid
     */
    public function iShouldBeNotifiedThatTheMerchantIdIsInvalid()
    {
        Assert::true($this->createPage->findValidationMessage('Please enter the Merchant ID.'));
    }
}
