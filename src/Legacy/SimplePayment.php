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
     * @var string
     */
    private $merchantId;

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
     * @param SystemPay $systemPay
     * @param $merchantId
     * @param $environment
     * @param $amount
     * @param $targetUrl
     * @param $currency
     * @param $transactionReference
     * @param $automaticResponseUrl
     */
    public function __construct(
        SystemPay $systemPay,
        $merchantId,
        $environment,
        $amount,
        $targetUrl,
        $currency,
        $transactionReference,
        $automaticResponseUrl
    )
    {
        $this->automaticResponseUrl = $automaticResponseUrl;
        $this->transactionReference = $transactionReference;
        $this->systemPay = $systemPay;
        $this->environment = $environment;
        $this->merchantId = $merchantId;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->targetUrl = $targetUrl;
    }

    public function execute()
    {
        $this->systemPay->setFields([
          'site_id' => $this->merchantId,
          'ctx_mode' => $this->environment,
          'amount' => $this->amount,
          'currency' => CurrencyNumber::getByCode($this->currency),
          'trans_id' => sprintf('%06d', $this->transactionReference),
          'url_return' => $this->targetUrl,
          //'url_check' => $this->automaticResponseUrl,
          'action_mode' => 'INTERACTIVE',
          'page_action'=> 'PAYMENT',
          'payment_config' => 'SINGLE'
        ]);


        // doit générer du html qui redirige vers la banque
        $response = $this->systemPay->executeRequest();

        throw new HttpResponse($response);
    }
}
