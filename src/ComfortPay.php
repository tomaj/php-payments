<?php

namespace Tomaj\Epayment;

class ComfortPay extends AbstractPayment
{
	public function request()
	{
		$pr = new \ComfortPayPaymentRequest();
		$pr->PT		= 'CardPay';
		$pr->MID	=  TB_COMFORTPAY_MID;
		$pr->AMT	=  $this->amount;
		$pr->CURR	=  978;
		$pr->VS		=  $this->variableSymbol;
		$pr->RURL	=  $this->returnUrl;
		$pr->IPC	=  $_SERVER['REMOTE_ADDR'];
		$pr->NAME	=  $this->variableSymbol;
		$pr->LANG	=  'sk';
		$pr->TPAY	=  'Y';
		$pr->TEM	=  'Y';
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
		$pres = new \ComfortPayPaymentHttpResponse();

		if ($pres->Validate() && $pres->VerifySignature(TB_COMFORTPAY_SHAREDSECRET)) {
			$result = $pres->GetPaymentResponse();
			return $result;
		} else {
			return FALSE;
		}
	}
}
