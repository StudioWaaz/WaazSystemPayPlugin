<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace Tests\Waaz\SystemPayPlugin\Behat\Mocker;

use Waaz\SystemPayPlugin\Legacy\SystemPay;
use Waaz\SystemPayPlugin\Bridge\SystemPayBridgeInterface;
use Sylius\Behat\Service\Mocker\Mocker;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
final class SystemPayMocker
{
    /**
     * @var Mocker
     */
    private $mocker;

    /**
     * @param Mocker $mocker
     */
    public function __construct(Mocker $mocker)
    {
        $this->mocker = $mocker;
    }

    /**
     * @param callable $action
     */
    public function completedPayment(callable $action)
    {
        $openSystemPayWrapper = $this->mocker
            ->mockService('waaz.system_pay.bridge.system_pay_bridge', SystemPayBridgeInterface::class);

        $openSystemPayWrapper
            ->shouldReceive('createSystemPay')
            ->andReturn(new SystemPay('test'));

        $openSystemPayWrapper
            ->shouldReceive('paymentVerification')
            ->andReturn(true);

        $openSystemPayWrapper
            ->shouldReceive('isPostMethod')
            ->andReturn(true);

        $openSystemPayWrapper
            ->shouldReceive('setSecretKey', 'setEnvironment', 'setMerchantId', 'setKeyVersion')
        ;

        $openSystemPayWrapper
            ->shouldReceive('getSecretKey')
            ->andReturn('test')
        ;

        $openSystemPayWrapper
            ->shouldReceive('getMerchantId')
            ->andReturn('test')
        ;

        $openSystemPayWrapper
            ->shouldReceive('getEnvironment')
            ->andReturn('TEST')
        ;

        $action();

        $this->mocker->unmockAll();
    }

    /**
     * @param callable $action
     */
    public function canceledPayment(callable $action)
    {
        $openSystemPayWrapper = $this->mocker
            ->mockService('waaz.system_pay.bridge.system_pay_bridge', SystemPayBridgeInterface::class);

        $openSystemPayWrapper
            ->shouldReceive('createSystemPay')
            ->andReturn(new SystemPay('test'));

        $openSystemPayWrapper
            ->shouldReceive('paymentVerification')
            ->andReturn(false);

        $openSystemPayWrapper
            ->shouldReceive('isPostMethod')
            ->andReturn(true);

        $openSystemPayWrapper
            ->shouldReceive('setSecretKey', 'setEnvironment', 'setMerchantId', 'setKeyVersion')
        ;

        $openSystemPayWrapper
            ->shouldReceive('getSecretKey')
            ->andReturn('test')
        ;

        $openSystemPayWrapper
            ->shouldReceive('getMerchantId')
            ->andReturn('test')
        ;

        $openSystemPayWrapper
            ->shouldReceive('getEnvironment')
            ->andReturn('TEST')
        ;

        $action();

        $this->mocker->unmockAll();
    }
}
