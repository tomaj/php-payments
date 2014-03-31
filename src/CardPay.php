<?php

namespace Tomaj\Epayment;

class CardPay extends AbstractPayment
{
	public function request()
	{
		$pr = new \CardPayPaymentRequest();
		$pr->MID = TB_CARDPAY_MID;
		$pr->AMT= $this->amount; // suma (v €)
		$pr->VS = $this->variableSymbol; // variabilný symbol platby
		$pr->CS = "0308";
		$pr->CURR = "978";		// kod eura
		$pr->RURL = $this->returnUrl;
		$pr->IPC = $_SERVER['REMOTE_ADDR'];

		// toto mozno nebude moct byt stale rovnake, neviem, na DK tam nastavujeme username ale nemyslim si ze by to malo pri platbe nejaky vyznam
		$pr->NAME = 'App.tv';
		// umozni automaticke presmerovanie usera z banky po 9 sekundach
		$pr->AREDIR = 1;
		// banka posle mail Appmu
		//$pr->REM = 'platby@App.sk';
		$pr->SetRedirectUrlBase(TB_CARDPAY_REDIRECTURLBASE);

		if ($pr->Validate()) {
			$pr->SignMessage(TB_CARDPAY_SHAREDSECRET);
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
		$pres = new \CardPayPaymentHttpResponse();
		if ($pres->Validate() && $pres->VerifySignature(TB_CARDPAY_SHAREDSECRET)) {
			$result = $pres->GetPaymentResponse();
			return $result;
		} else {
			return FALSE;
		}
	}
}
