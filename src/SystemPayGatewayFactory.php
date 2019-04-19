<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace Waaz\SystemPayPlugin;

use Waaz\SystemPayPlugin\Action\ConvertPaymentAction;
use Waaz\SystemPayPlugin\Bridge\SystemPayBridgeInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
final class SystemPayGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'system_pay',
            'payum.factory_title' => 'SystemPay - Banque Populaire',

            'payum.action.convert' => new ConvertPaymentAction(),

            'payum.http_client' => '@waaz.system_pay.bridge.system_pay_bridge',
        ]);

        if (false == $config['payum.api']) {
            $config['payum.default_options'] = [
                'environment' => '',
                'secure_key' => '',
                'merchant_id' => ''
            ];

            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = ['secret_key', 'environment', 'merchant_id'];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                /** @var SystemPayBridgeInterface $systemPayBridge */
                $systemPayBridge = $config['payum.http_client'];

                $systemPayBridge->setSecretKey($config['secret_key']);
                $systemPayBridge->setMerchantId($config['merchant_id']);
                $systemPayBridge->setEnvironment($config['environment']);

                return $systemPayBridge;
            };
        }
    }
}
