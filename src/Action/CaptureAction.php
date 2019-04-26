<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace Waaz\SystemPayPlugin\Action;

use Waaz\SystemPayPlugin\Legacy\SimplePayment;
use Waaz\SystemPayPlugin\Bridge\SystemPayBridgeInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Payum\Core\Security\TokenInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Webmozart\Assert\Assert;
use Payum\Core\Payum;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
final class CaptureAction implements ActionInterface, ApiAwareInterface
{
    use GatewayAwareTrait;

    /**
     * @var Payum
     */
    private $payum;

    /**
     * @var SystemPayBridgeInterface
     */
    private $systemPayBridge;

    /**
     * @var UrlGeneratorInterface
     */
     private $router;

    /**
     * @param Payum $payum
     */
    public function __construct(Payum $payum, UrlGeneratorInterface $router)
    {
        $this->payum = $payum;
        $this->router = $router;
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
     *
     * @param Capture $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        /** @var PaymentInterface $payment */
        $payment = $request->getFirstModel();
        Assert::isInstanceOf($payment, PaymentInterface::class);

        /** @var TokenInterface $token */
        $token = $request->getToken();

        $transactionReference = isset($model['transactionReference']) ? $model['transactionReference'] : null;

        if ($transactionReference !== null) {

            if ($this->systemPayBridge->isPostMethod()) {

                $model['status'] = $this->systemPayBridge->paymentVerification() ?
                    PaymentInterface::STATE_COMPLETED : PaymentInterface::STATE_CANCELLED;

                $request->setModel($model);

                return;
            }

            if ($model['status'] === PaymentInterface::STATE_COMPLETED) {

                return;
            }
        }

        $notifyToken = $this->createNotifyToken($token->getGatewayName(), $token->getDetails());

        $secretKey = $this->systemPayBridge->getSecretKey();

        $systemPay = $this->systemPayBridge->createSystemPay($secretKey);

        $environment = $this->systemPayBridge->getEnvironment();
        $merchantId = $this->systemPayBridge->getMerchantId();

        $automaticResponseUrl = $notifyToken->getTargetUrl();
        $currencyCode = $payment->getCurrencyCode();

        $targetUrl = $this->router->generate('sylius_shop_order_thank_you', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $cancelUrl = $this->router->generate('sylius_shop_order_show', ['tokenValue' => $payment->getOrder()->getTokenValue()], UrlGeneratorInterface::ABSOLUTE_URL);

        //$targetUrl = $request->getToken()->getTargetUrl();
        $amount = $payment->getAmount();

        $transactionReference = $payment->getOrder()->getId();

        $model['transactionReference'] = $transactionReference;

        $simplePayment = new SimplePayment(
            $systemPay,
            $merchantId,
            $environment,
            $amount,
            $targetUrl,
            $currencyCode,
            $transactionReference,
            $automaticResponseUrl,
            $cancelUrl
        );

        $request->setModel($model);
        $simplePayment->execute();
    }

    /**
     * @param string $gatewayName
     * @param object $model
     *
     * @return TokenInterface
     */
    private function createNotifyToken($gatewayName, $model)
    {
        return $this->payum->getTokenFactory()->createNotifyToken(
            $gatewayName,
            $model
        );
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess;
    }
}
