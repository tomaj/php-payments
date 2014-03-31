<?php

namespace Tomaj\Epayment;

class SporoPay extends AbstractPayment
{
	public function request()
	{
		$pr = new \SporoPayPaymentRequest();
		
		$pr->pu_predcislo = SLSP_SPOROPAY_PU_PREDCISLO;
		$pr->pu_cislo = SLSP_SPOROPAY_PU_CISLO;
		$pr->suma = $this->amount; // suma (v €)
		$pr->vs = $this->variableSymbol; // variabilný symbol platby
		$pr->url = $this->returnUrl;
		//$pr->mail_notif_att = 3;
		//$pr->email_adr = 'platby@App.sk';

		// ??? bez tychto dvoch parametrov to nejde
		$pr->param = urldecode('abc=defgh');
		$pr->ss = str_pad($this->specificSymbol, 10, 0, STR_PAD_LEFT);
		$pr->SetRedirectUrlBase(SLSP_SPOROPAY_REDIRECTURLBASE);
		
		if ($pr->Validate()) {
			$pr->SignMessage(SLSP_SPOROPAY_SHAREDSECRET);
			$paymentRequestUrl = $pr->GetRedirectUrl();
			// header("Location: " . $paymentRequestUrl);

			// pre pripad ze nas to nepresmeruje dame userovi moznost kliknut si priamo na link
			return $paymentRequestUrl;
		} else {
			return FALSE;
		}
	}
	
	public function response()
	{
		$response = new \SporoPayPaymentHttpResponse();
		if ($response->Validate() && $response->VerifySignature(SLSP_SPOROPAY_SHAREDSECRET)) {
			$result = $response->GetPaymentResponse();
			return $result;
		} else {
			return FALSE;
		}
	}
}
