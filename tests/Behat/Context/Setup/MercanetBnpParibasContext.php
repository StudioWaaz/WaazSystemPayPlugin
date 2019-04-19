<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace Tests\Waaz\SystemPayPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Waaz\SystemPayPlugin\Legacy\SystemPay;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Repository\PaymentMethodRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
final class SystemPayContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var PaymentMethodRepositoryInterface
     */
    private $paymentMethodRepository;

    /**
     * @var ExampleFactoryInterface
     */
    private $paymentMethodExampleFactory;

    /**
     * @var FactoryInterface
     */
    private $paymentMethodTranslationFactory;

    /**
     * @var ObjectManager
     */
    private $paymentMethodManager;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param PaymentMethodRepositoryInterface $paymentMethodRepository
     * @param ExampleFactoryInterface $paymentMethodExampleFactory
     * @param FactoryInterface $paymentMethodTranslationFactory
     * @param ObjectManager $paymentMethodManager
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        ExampleFactoryInterface $paymentMethodExampleFactory,
        FactoryInterface $paymentMethodTranslationFactory,
        ObjectManager $paymentMethodManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->paymentMethodExampleFactory = $paymentMethodExampleFactory;
        $this->paymentMethodTranslationFactory = $paymentMethodTranslationFactory;
        $this->paymentMethodManager = $paymentMethodManager;
    }

    /**
     * @Given the store has a payment method :paymentMethodName with a code :paymentMethodCode and System Pay Checkout gateway
     */
    public function theStoreHasAPaymentMethodWithACodeAndSystemPayCheckoutGateway(
        $paymentMethodName,
        $paymentMethodCode
    ) {
        $paymentMethod = $this->createPaymentMethod($paymentMethodName, $paymentMethodCode, 'System Pay');
        $paymentMethod->getGatewayConfig()->setConfig([
            'environment' => 'TEST',
            'merchant_id' => 'TEST',
            'key_version' => 'TEST',
            'secret_key' => 'TEST',
            'payum.http_client' => '@waaz.system_pay.bridge.system_pay_bridge',
        ]);

        $this->paymentMethodManager->flush();
    }

    /**
     * @param string $name
     * @param string $code
     * @param string $description
     * @param bool $addForCurrentChannel
     * @param int|null $position
     *
     * @return PaymentMethodInterface
     */
    private function createPaymentMethod(
        $name,
        $code,
        $description = '',
        $addForCurrentChannel = true,
        $position = null
    ) {

        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $this->paymentMethodExampleFactory->create([
            'name' => ucfirst($name),
            'code' => $code,
            'description' => $description,
            'gatewayName' => 'system_pay',
            'gatewayFactory' => 'system_pay',
            'enabled' => true,
            'channels' => ($addForCurrentChannel && $this->sharedStorage->has('channel')) ? [$this->sharedStorage->get('channel')] : [],
        ]);

        if (null !== $position) {
            $paymentMethod->setPosition($position);
        }

        $this->sharedStorage->set('payment_method', $paymentMethod);
        $this->paymentMethodRepository->add($paymentMethod);

        return $paymentMethod;
    }
}
