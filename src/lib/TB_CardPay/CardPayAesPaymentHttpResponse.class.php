<?php

require_once dirname(dirname(__FILE__)) . '/EPaymentAesSignedMessage.class.php';

class CardPayAesPaymentHttpResponse extends EPaymentAesSignedMessage implements IEPaymentHttpPaymentResponse {
    public function __construct($fields = null)
    {
        $this->readOnlyFields = array('SS', 'VS', 'AC', 'RES', 'SIGN');

        if ($fields == null)
        {
            $fields = $_GET;
        }

        $this->fields['VS'] = isset($fields['VS']) ? $fields['VS'] : null;
        $this->fields['AC'] = isset($fields['AC']) ? $fields['AC'] : null;
        $this->fields['RES'] = isset($fields['RES']) ? $fields['RES'] : null;
        $this->fields['SIGN'] = isset($fields['SIGN']) ? $fields['SIGN'] : null;
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
        return "{$this->VS}{$this->RES}{$this->AC}";
    }

    protected $isVerified = false;

    public function VerifySignature($password)
    {
        if ($this->SIGN == $this->computeSign($password))
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