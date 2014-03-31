<?php

namespace Tomaj\Epayment;

class Eplatba extends AbstractPayment
{
	public function request()
	{
		$pr = new \EPlatbaPaymentRequest();

		$pr->MID = VUB_EPLATBA2_HMAC_MID;
		$pr->AMT = $this->amount;	// suma (v €)
		$pr->VS = $this->variableSymbol;	// variabilný symbol platby
		$pr->SS = $this->specificSymbol;	// specificky symbol platby
		$pr->CS = "0308";
		$pr->RURL = $this->returnUrl;
		$pr->SetRedirectUrlBase(VUB_EPLATBA2_HMAC_REDIRECTURLBASE);

		if ($pr->Validate()) {
			$pr->SignMessage(VUB_EPLATBA2_HMAC_SHAREDSECRET);

			// kedze vub je ina, posleme url a fieldy ktore maju ist do postu spolu s hodnotami
			$ret['url'] = $pr->GetUrlBase();
			$ret['postFields'] = $pr->GetPaymentRequestFields();

			return $ret;
		} else {
			return FALSE;
		}
	}

	public function response()
	{
		$response = new \EPlatbaPaymentHttpResponse();
		if ($response->Validate() && $response->VerifySignature(VUB_EPLATBA2_HMAC_SHAREDSECRET)) {
			$result = $response->GetPaymentResponse();
			return $result;
		} else {
			return FALSE;
		}
	}
}
