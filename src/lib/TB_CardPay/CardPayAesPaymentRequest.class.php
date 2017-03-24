<?php
/*
	Copyright 2009 MONOGRAM Technologies

	This file is part of MONOGRAM EPayment libraries

	MONOGRAM EPayment libraries is free software: you can redistribute it and/or modify
	it under the terms of the GNU Lesser General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	MONOGRAM EPayment libraries is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU Lesser General Public License for more details.

	You should have received a copy of the GNU Lesser General Public License
	along with MONOGRAM EPayment libraries.  If not, see <http://www.gnu.org/licenses/>.
*/
require_once dirname(dirname(__FILE__)) . '/EPaymentAesSignedMessage.class.php';

class CardPayAesPaymentRequest extends EPaymentAesSignedMessage implements IEPaymentHttpRedirectPaymentRequest {
    const CardPay_EPayment_URL_Base = "https://moja.tatrabanka.sk/cgi-bin/e-commerce/start/e-commerce.jsp";
    private $redirectUrlBase = self::CardPay_EPayment_URL_Base;

    public function SetRedirectUrlBase($url) {
        $this->redirectUrlBase = $url;
    }

    public function __construct() {
        $this->readOnlyFields = array('SIGN');
        $this->requiredFields = array('MID', 'AMT', 'CURR', 'VS', 'RURL', 'IPC', 'NAME');
        $this->optionalFields = array('PT', 'CS', 'RSMS', 'REM', 'DESC', 'AREDIR', 'LANG');

        $this->PT = 'CardPay';
    }

    protected function getSignatureBase() {
        $sb = "{$this->MID}{$this->AMT}{$this->CURR}{$this->VS}{$this->RURL}{$this->IPC}{$this->NAME}";
        return $sb;
    }

    protected function validateData()
    {
        try
        {
            if (!is_string($this->AMT))
            {
                $this->AMT = sprintf("%01.2F", $this->AMT);
            }

            if (!preg_match('/^[0-9a-z]{3,4}$/i', $this->MID))
            {
                throw new Exception('Merchant ID is in wrong format');
            }

            if (!preg_match('/^[0-9]+(\.[0-9]+)?$/', $this->AMT))
            {
                throw new Exception('Amount is in wrong format');
            }

            if (!preg_match('/^[0-9]{1,10}$/', $this->VS))
            {
                throw new Exception('Variable Symbol is in wrong format');
            }

            if ($this->RURL == "")
            {
                throw new Exception('Return URL is in wrong format');
            }

            $urlRestrictedChars = array('&', '?', ';', '=', '+', '%');
            foreach ($urlRestrictedChars as $char)
            {
                if (false !== strpos($this->RURL, $char))
                {
                    throw new Exception('Return URL contains restricted character: "'.$char.'"');
                }
            }

            // nepovinne
            if ( $this->PT != 'CardPay')
            {
                throw new Exception('Payment Type parameter must be "CardPay"');
            }

            if (!empty($this->CS) && !preg_match('/^[0-9]{1,4}$/', $this->CS))
            {
                throw new Exception('Constant Symbol is in wrong format');
            }

            if (!empty($this->RSMS) && !preg_match('/^(0|\+421)9[0-9]{2}( ?[0-9]{3}){2}$/', $this->RSMS))
            {
                throw new Exception('Return SMS in wrong format.');
            }

            if (!empty($this->DESC) && strlen($this->DESC) > 20)
            {
                throw new Exception('Description is too long');
            }

            $validLanguages = array('sk', 'en', 'de', 'hu', 'cz', 'es', 'fr', 'it', 'pl');
            if (!empty($this->LANG) && !in_array($this->LANG, $validLanguages))
            {
                throw new Exception('Unknown language, known languages are: '.implode(',', $validLanguages));
            }

            return true;
        }
        catch (Exception $e)
        {
            if (defined('DEBUG') && DEBUG)
            {
                throw $e;
            }

            return false;
        }
    }

    public function SignMessage($password) {
        $this->fields['SIGN'] = $this->computeSign($password);
    }

    public function GetRedirectUrl() {
        $url = $this->redirectUrlBase;
        if ( $this->PT !== "")
        {
            $url .= "?PT={$this->PT}";
            $url .= "&MID={$this->MID}";
        }
        else
        {
            $url .= "?MID={$this->MID}";
        }
        $url .= "&AMT={$this->AMT}";
        $url .= "&CURR={$this->CURR}";
        $url .= "&VS={$this->VS}";
        $url .= "&CS={$this->CS}";
        $url .= "&RURL=".urlencode($this->RURL);
        $url .= "&IPC={$this->IPC}";
        $url .= "&NAME={$this->NAME}";
        $url .= "&SIGN={$this->SIGN}";

        if ($this->RSMS != "")
        {
            $url .= "&RSMS=".urlencode($this->RSMS);
        }
        if ($this->REM  != "")
        {
            $url .= "&REM=".urlencode($this->REM);
        }
        if ($this->DESC  != "")
        {
            $url .= "&DESC=".urlencode($this->DESC);
        }
        if ($this->AREDIR  != "")
        {
            $url .= "&AREDIR={$this->AREDIR}";
        }
        if ($this->LANG  != "")
        {
            $url .= "&LANG={$this->LANG}";
        }

        return $url;
    }
}
