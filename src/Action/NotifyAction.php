<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace Waaz\SystemPayPlugin\Action;

use Waaz\SystemPayPlugin\Bridge\SystemPayBridgeInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\GatewayAwareTrait;
use Sylius\Component\Core\Model\PaymentInterface;
use Payum\Core\Request\Notify;
use Sylius\Component\Payment\PaymentTransitions;
use Webmozart\Assert\Assert;
use SM\Factory\FactoryInterface;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
final class NotifyAction implements ActionInterface, ApiAwareInterface
{
    use GatewayAwareTrait;

    /**
     * @var SystemPayBridgeInterface
     */
    private $systemPayBridge;

    /**
     * @var FactoryInterface
     */
    private $stateMachineFactory;

    /**
     * @param FactoryInterface $stateMachineFactory
     */
    public function __construct(FactoryInterface $stateMachineFactory)
    {
        $this->stateMachineFactory = $stateMachineFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request Notify */
        RequestNotSupportedException::assertSupports($this, $request);

        if ($this->systemPayBridge->paymentVerification()) {

            /** @var PaymentInterface $payment */
            $payment = $request->getFirstModel();

            Assert::isInstanceOf($payment, PaymentInterface::class);

            $this->stateMachineFactory->get($payment, PaymentTransitions::GRAPH)->apply(PaymentTransitions::TRANSITION_COMPLETE);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setApi($systemPayBridge)
    {
        if (!$systemPayBridge instanceof SystemPayBridgeInterface) {
            throw new UnsupportedApiException('Not supported.');
        }

        $this->systemPayBridge = $systemPayBridge;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Notify &&
            $request->getModel() instanceof \ArrayObject
        ;
    }
}