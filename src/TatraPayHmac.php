<?php

namespace Tomaj\Epayment;

class TatraPayHmac extends AbstractPayment
{
    public function request()
    {
        $pr = new \TatraPayHmacPaymentRequest();

        $pr->MID = TB_TATRAPAY_MID;
        $pr->AMT = $this->amount; // suma (v €)
        $pr->VS = $this->variableSymbol; // variabilný symbol platby
        $pr->SS = $this->specificSymbol;	// specificky symbol platby
        $pr->CS = "0308";
        $pr->CURR = "978";		// kod eura
        $pr->RURL = $this->returnUrl;
        // umozni automaticke presmerovanie usera z banky po 9 sekundach
        $pr->AREDIR = 1;
        // banka posle mail Appmu
        //$pr->REM = 'platby@App.sk';
        $pr->SetRedirectUrlBase(TB_TATRAPAY_REDIRECTURLBASE);

        if ($pr->Validate()) {
            $pr->SignMessage(TB_TATRAPAY_SHAREDSECRET);
            $paymentRequestUrl = $pr->GetRedirectUrl();
            //header("Location: " . $paymentRequestUrl);
            // pre pripad ze nas to nepresmeruje dame userovi moznost kliknut si priamo na link
            return $paymentRequestUrl;
        } else {
            return FALSE;
        }
    }

    public function response()
    {
        $response = new \TatraPayHmacPaymentHttpResponse();

        $response->MID = TB_TATRAPAY_MID;

        if ($response->Validate() && $response->VerifySignature(TB_TATRAPAY_SHAREDSECRET)) {
            $result = $response->GetPaymentResponse();
            return $result;
        } else {
            return FALSE;
        }
    }
}
