<?php

namespace Tomaj\Epayment;

class ComfortPayHmac extends AbstractPayment
{
    public function request()
    {
        $pr = new \ComfortPayHmacPaymentRequest();
        $pr->PT		= 'CardPay';
        $pr->MID	=  TB_COMFORTPAY_MID;
        $pr->AMT	=  $this->amount;
        $pr->CURR	=  978;
        $pr->VS		=  $this->variableSymbol;
        $pr->RURL	=  $this->returnUrl;
        $pr->IPC	=  $_SERVER['REMOTE_ADDR'];
        $pr->NAME	=  $this->variableSymbol;
        $pr->LANG	=  'SK';
        $pr->TPAY	=  'Y';
        $pr->TEM	=  TB_COMFORTPAY_TEM;
        $pr->REM	=  TB_COMFORTPAY_REM;
        $pr->AREDIR	=  '1';

        $pr->SetRedirectUrlBase(TB_COMFORTPAY_REDIRECTURLBASE);
        if ($pr->Validate()) {
            $pr->SignMessage(TB_COMFORTPAY_SHAREDSECRET);
            $paymentRequestUrl = $pr->GetRedirectUrl();
            //	header("Location: " . $paymentRequestUrl);
            // pre pripad ze nas to nepresmeruje dame userovi moznost kliknut si priamo na link
            return $paymentRequestUrl;
        } else {
            return FALSE;
        }
    }

    public function response()
    {
        $pres = new \ComfortPayHmacPaymentHttpResponse();

        if ($pres->Validate() && $pres->VerifySignature(TB_COMFORTPAY_SHAREDSECRET)) {
            $result = $pres->GetPaymentResponse();
            return $result;
        } else {
            return FALSE;
        }
    }
}
