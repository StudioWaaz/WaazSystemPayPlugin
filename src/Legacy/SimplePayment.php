<?php

/**
 * This file was created by the developers from Waaz.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://www.studiowaaz.com and write us
 * an email on developpement@studiowaaz.com.
 */

namespace Waaz\SystemPayPlugin\Legacy;

use Payum\Core\Reply\HttpResponse;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
final class SimplePayment
{
    /**
     * @var SystemPay|object
     */
    private $systemPay;

    /**
     * @var string
     */
    private $environment;

    /**
     * @var bool
     */
    private $useOldSecurity;

    /**
     * @var string
     */
    private $merchantId;

    /**
     * @var string
     */
    private $paymentCards;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $transactionReference;

    /**
     * @var string
     */
    private $automaticResponseUrl;

    /**
     * @var string
     */
    private $targetUrl;

    /**
     * @var string
     */
    private $cancelUrl;

    /**
     * @param SystemPay $systemPay
     * @param $merchantId
     * @param $paymentCards
     * @param $environment
     * @param $useOldSecurity
     * @param $amount
     * @param $targetUrl
     * @param $currency
     * @param $transactionReference
     * @param $automaticResponseUrl
     * @param $cancelUrl
     */
    public function __construct(
        SystemPay $systemPay,
        $merchantId,
        $paymentCards,
        $environment,
        $useOldSecurity,
        $amount,
        $targetUrl,
        $currency,
        $transactionReference,
        $automaticResponseUrl,
        $cancelUrl
    )
    {
        $this->automaticResponseUrl = $automaticResponseUrl;
        $this->transactionReference = $transactionReference;
        $this->systemPay = $systemPay;
        $this->environment = $environment;
        $this->useOldSecurity = $useOldSecurity;
        $this->merchantId = $merchantId;
        $this->paymentCards = $paymentCards;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->targetUrl = $targetUrl;
        $this->cancelUrl = $cancelUrl;
    }

    public function execute()
    {
        $this->systemPay->setUseOldSecurity($this->useOldSecurity);
        $this->systemPay->setFields([
          'site_id' => $this->merchantId,
          'payment_cards' => $this->paymentCards,
          'ctx_mode' => $this->environment,
          'amount' => $this->amount,
          'currency' => CurrencyNumber::getByCode($this->currency),
          'trans_id' => $this->generateUniqueTransId(),
          'url_return' => $this->cancelUrl,
          'url_success' => $this->targetUrl,
          'url_check' => $this->automaticResponseUrl,
          'action_mode' => 'INTERACTIVE',
          'page_action'=> 'PAYMENT',
          'payment_config' => 'SINGLE',
          'order_id' => $this->transactionReference
        ]);


        // doit générer du html qui redirige vers la banque
        $response = $this->systemPay->executeRequest();

        throw new HttpResponse($response);
    }

    private function generateUniqueTransId() {
      $range = range(0, 899999);
      shuffle($range);
      return sprintf('%06d', $range[0]);
    }
}
