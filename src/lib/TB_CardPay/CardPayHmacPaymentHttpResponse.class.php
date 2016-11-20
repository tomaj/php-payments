<?php

require_once dirname(dirname(__FILE__)) . '/EPaymentAesSignedMessage.class.php';

class CardPayHmacPaymentHttpResponse extends EPaymentHmacSignedMessage implements IEPaymentHttpPaymentResponse {
    public function __construct($fields = null)
    {
        $this->readOnlyFields = array('SS', 'VS', 'AC', 'RES', 'HMAC', 'AMT', 'CURR', 'TRES', 'RC', 'CID', 'TID', 'TIMESTAMP');

        if ($fields == null)
        {
            $fields = $_GET;
        }

        $this->fields['VS'] = isset($fields['VS']) ? $fields['VS'] : null;
        $this->fields['AC'] = isset($fields['AC']) ? $fields['AC'] : null;
        $this->fields['RES'] = isset($fields['RES']) ? $fields['RES'] : null;
        $this->fields['HMAC'] = isset($fields['HMAC']) ? $fields['HMAC'] : null;
        $this->fields['AMT'] = isset($fields['AMT']) ? $fields['AMT'] : null;
        $this->fields['CURR'] = isset($fields['CURR']) ? $fields['CURR'] : null;
        $this->fields['VS'] = isset($fields['VS']) ? $fields['VS'] : null;
        $this->fields['TRES'] = isset($fields['TRES']) ? $fields['TRES'] : null;
        $this->fields['RC'] = isset($fields['RC']) ? $fields['RC'] : null;

        $this->fields['CID'] = isset($fields['CID']) ? $fields['CID'] : null;
        $this->fields['TID'] = isset($fields['TID']) ? $fields['TID'] : null;
        $this->fields['TIMESTAMP'] = isset($fields['TIMESTAMP']) ? $fields['TIMESTAMP'] : null;
    }

    protected function validateData()
    {
        if ($this->VS == ""){
            return false;
        }
        if (!($this->RES == "FAIL" || $this->RES == "OK" || $this->RES == "TOUT"))
        {
            return false;
        }

        return true;
    }

    protected function getSignatureBase()
    {
        return "{$this->AMT}{$this->CURR}{$this->VS}{$this->RES}{$this->AC}{$this->RC}{$this->TID}{$this->TIMESTAMP}";
    }

    protected $isVerified = false;

    public function VerifySignature($password)
    {
//        dump($this);
//        dump($this->HMAC);
//        dump($this->getSignatureBase());
//        dump($password);
//        dump($this->computeSign($password));

        if ($this->HMAC == $this->computeSign($password))
        {
            $this->isVerified = true;
            return true;
        }
        return false;
    }

    public function GetPaymentResponse()
    {
        if (!$this->isVerified)
            throw new Exception(__METHOD__ . ": Message was not verified yet.");

        if ($this->RES == "FAIL")
            return IEPaymentHttpPaymentResponse::RESPONSE_FAIL;
        else if ($this->RES == "OK")
            return IEPaymentHttpPaymentResponse::RESPONSE_SUCCESS;
        else
            return null;
    }

    public function GetVS()
    {
        return $this->VS;
    }

}