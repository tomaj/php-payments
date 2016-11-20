<?php

class ComfortPayHmacPaymentRequest extends CardPayHmacPaymentRequest
{
    public function __construct()
    {
        parent::__construct();
        $this->optionalFields = array_merge($this->optionalFields, array('CID', 'TPAY', 'TEM', 'TSMS'));
    }

    protected function getSignatureBase()
    {
        $sb = "{$this->MID}{$this->AMT}{$this->CURR}{$this->VS}{$this->RURL}{$this->IPC}{$this->NAME}{$this->REM}{$this->TPAY}{$this->CID}{$this->TIMESTAMP}";
        return $sb;
    }

    public function GetRedirectUrl()
    {
        $url = parent::GetRedirectUrl();
        $url .= "&CID=" . urlencode($this->CID);
        $url .= "&TPAY=" . urlencode($this->TPAY);

        if (!isempty($this->TEM))
        {
            $url .= "&TEM=" . urlencode($this->TEM);
        }

        if (!isempty($this->TSMS))
        {
            $url .= "&TSMS={$this->TSMS}";
        }

        return $url;
    }
}