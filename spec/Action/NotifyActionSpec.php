<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace spec\Waaz\SystemPayPlugin\Action;

use Waaz\SystemPayPlugin\Action\NotifyAction;
use Waaz\SystemPayPlugin\Bridge\SystemPayBridgeInterface;
use Payum\Core\Request\Notify;
use PhpSpec\ObjectBehavior;
use SM\Factory\FactoryInterface;
use SM\StateMachine\StateMachineInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Payment\PaymentTransitions;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
final class NotifyActionSpec extends ObjectBehavior
{
    function let(
        SystemPayBridgeInterface $systemPayBridge,
        FactoryInterface $stateMachineFactory
    ) {
        $this->beConstructedWith($stateMachineFactory);
        $this->setApi($systemPayBridge);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NotifyAction::class);
    }

    function it_execute(
        Notify $request,
        \ArrayObject $arrayObject,
        SystemPayBridgeInterface $systemPayBridge,
        PaymentInterface $payment,
        FactoryInterface $stateMachineFactory,
        StateMachineInterface $stateMachine
    ) {
        $request->getModel()->willReturn($arrayObject);
        $request->getFirstModel()->willReturn($payment);
        $systemPayBridge->isPostMethod()->willReturn(true);
        $systemPayBridge->paymentVerification()->willReturn(true);
        $stateMachineFactory->get($payment, PaymentTransitions::GRAPH)->willReturn($stateMachine);

        $stateMachine->apply(PaymentTransitions::TRANSITION_COMPLETE)->shouldBeCalled();

        $this->execute($request);
    }
}
