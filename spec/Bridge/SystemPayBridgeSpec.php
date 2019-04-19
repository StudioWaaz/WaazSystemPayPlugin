<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace spec\Waaz\SystemPayPlugin\Bridge;

use Waaz\SystemPayPlugin\Bridge\SystemPayBridge;
use Waaz\SystemPayPlugin\Bridge\SystemPayBridgeInterface;
use Waaz\SystemPayPlugin\Legacy\SystemPay;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
final class SystemPayBridgeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(SystemPayBridge::class);
        $this->shouldHaveType(SystemPayBridgeInterface::class);
    }

    function let(RequestStack $requestStack)
    {
        $this->beConstructedWith($requestStack);
    }

    function it_is_post_method(
        RequestStack $requestStack,
        Request $request
    )
    {
        $request->isMethod('POST')->willReturn(true);
        $requestStack->getCurrentRequest()->willReturn($request);

        $this->isPostMethod()->shouldReturn(true);
    }

    function it_is_not_post_method(
        RequestStack $requestStack,
        Request $request
    )
    {
        $request->isMethod('POST')->willReturn(false);
        $requestStack->getCurrentRequest()->willReturn($request);

        $this->isPostMethod()->shouldReturn(false);
    }

    function it_creates_system_pay()
    {
        $this->createSystemPay('key')->shouldBeAnInstanceOf(SystemPay::class);
    }

    function it_payment_verification_has_been_thrown(
        RequestStack $requestStack,
        Request $request
    )
    {
        $request->isMethod('POST')->willReturn(true);
        $requestStack->getCurrentRequest()->willReturn($request);

        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('paymentVerification', ['key'])
        ;
    }
}
