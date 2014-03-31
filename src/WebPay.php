<?php

namespace Tomaj\Epayment;

class WebPay extends AbstractPayment
{
	public function request()
	{
		$pr = new \VeBpayPaymentRequest();

		$pr->MID = VB_VEBPAY_MID;
		$pr->AMT = $this->amount; // suma (v €)
		$pr->VS = $this->variableSymbol; // variabilný symbol platby
		$pr->CS = "0308";
		$pr->RURL = $this->returnUrl;
		$pr->SetRedirectUrlBase(VB_VEBPAY_REDIRECTURLBASE);

		if ($pr->Validate()) {
			$pr->SignMessage(VB_VEBPAY_SHAREDSECRET);
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
		$response = new \VeBpayPaymentHttpResponse();
		if ($response->Validate() && $response->VerifySignature(VB_VEBPAY_SHAREDSECRET)) {
			$result = $response->GetPaymentResponse();
			return $result;
		} else {
			return FALSE;
		}
	}
}
