<?php

namespace Waaz\SystemPayPlugin\Legacy;

/**
 * @author Ibes Mongabure <developpement@studiowaaz.com>
 */
class SystemPay
{
    /**
     * @var string
     */
    private $paymentUrl = 'https://systempay.cyberpluspaiement.com/vads-payment/';

    /**
     * @var array
     */
    private $mandatoryFields = array(
        'action_mode' => null,
        'ctx_mode' => null,
        'page_action' => null,
        'payment_config' => null,
        'site_id' => null,
        'version' => 'V2',
        'redirect_success_message' => null,
        'redirect_error_message' => null,
        'url_return' => null,
    );

    /**
     * @var string
     */
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
        $this->mandatoryFields['trans_date'] = gmdate('YmdHis');
    }

    /**
     * @param $fields
     * remove "vads_" prefix and form an array that will looks like :
     * trans_id => x
     * cust_email => xxxxxx@xx.xx
     * @return $this
     */
    public function setFields($fields)
    {
        foreach ($fields as $field => $value)
            if (empty($this->mandatoryFields[$field]) || $field == 'payment_config')
                $this->mandatoryFields[$field] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        $this->mandatoryFields['signature'] = $this->getSignature();
        return $this->mandatoryFields;
    }

    /**
     * @return string
     */
    public function getPaymentUrl()
    {
        return $this->paymentUrl;
    }

    /**
     * @param array $fields
     * @return array
     */
    private function setPrefixToFields(array $fields)
    {
        $newTab = array();
        foreach ($fields as $field => $value)
            $newTab[sprintf('vads_%s', $field)] = $value;
        return $newTab;
    }

    /**
     * @param null $fields
     * @return string
     */
    private function getSignature($fields = null)
    {
        if (!$fields)
            $fields = $this->mandatoryFields = $this->setPrefixToFields($this->mandatoryFields);
        ksort($fields);
        $contenu_signature = "";
        foreach ($fields as $field => $value)
                $contenu_signature .= $value."+";
        $contenu_signature .= $this->key;
        $signature = base64_encode(hash_hmac('sha256', utf8_encode($contenu_signature), $this->key, true));
        return $signature;
    }

    public function executeRequest()
    {
        $return = "<html><body><form name=\"redirectForm\" method=\"POST\" action=\"" . $this->paymentUrl . "\">";
          foreach ($this->getResponse() as $key => $value) {
            $return .= "<input type=\"hidden\" name=\"$key\" value=\"" .$value. "\">";
          }


        $return .=
            "<noscript><input type=\"submit\" name=\"Go\" value=\"Click to continue\"/></noscript> </form>" .
            "<script type=\"text/javascript\"> document.redirectForm.submit(); </script>" .
            "</body></html>";

        return $return;
    }
}
