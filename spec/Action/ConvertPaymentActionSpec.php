<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace spec\Waaz\SystemPayPlugin\Action;

use Waaz\SystemPayPlugin\Action\ConvertPaymentAction;
use PhpSpec\ObjectBehavior;
use Payum\Core\Request\Convert;
use Payum\Core\Model\PaymentInterface;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
final class ConvertPaymentActionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ConvertPaymentAction::class);
    }

    function it_execute(
        Convert $request,
        \ArrayObject $arrayObject,
        PaymentInterface $payment
    )
    {
        $request->setResult([])->willReturn($arrayObject);
        $request->getSource()->willReturn($payment);
        $request->getTo()->willReturn('array');

        $this->execute($request);
    }
}
