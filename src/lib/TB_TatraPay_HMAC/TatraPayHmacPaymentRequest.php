<?php
/*
	Copyright 2009 MONOGRAM Technologies

	This file is part of MONOGRAM EBanking libraries

	MONOGRAM EBanking libraries is free software: you can redistribute it and/or modify
	it under the terms of the GNU Lesser General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	MONOGRAM EBanking libraries is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU Lesser General Public License for more details.

	You should have received a copy of the GNU Lesser General Public License
	along with MONOGRAM EBanking libraries.  If not, see <http://www.gnu.org/licenses/>.
*/
require_once dirname(dirname(__FILE__)).'/EPaymentAes256SignedMessage.class.php';

class TatraPayHmacPaymentRequest extends EPaymentHmacSignedMessage implements IEPaymentHttpRedirectPaymentRequest {
    const TatraPay_EPayment_URL_Base = "https://moja.tatrabanka.sk/cgi-bin/e-commerce/start/e-commerce.jsp";
    private $redirectUrlBase = self::TatraPay_EPayment_URL_Base;

    public function SetRedirectUrlBase($url) {
        $this->redirectUrlBase = $url;
    }

    public function __construct() {
        $this->readOnlyFields = array('SIGN');
        $this->requiredFields = array('MID', 'AMT', 'CURR', 'VS', 'CS', 'RURL');
        $this->optionalFields = array('PT', 'SS', 'DESC', 'RSMS', 'REM', 'AREDIR', 'LANG');

        $this->PT = 'TatraPay';
    }

    protected function getSignatureBase() {
        $sb = "{$this->MID}{$this->AMT}{$this->CURR}{$this->VS}{$this->SS}{$this->CS}{$this->RURL}";
        return $sb;
    }

    protected function validateData() {
        try {
            if (!is_string($this->AMT))
                $this->AMT = sprintf("%01.2F", $this->AMT);

            if (!preg_match('/^[0-9a-z]{3,4}$/', $this->MID)) throw new Exception('Merchant ID is in wrong format');
            if (!preg_match('/^[0-9]+(\\.[0-9]+)?$/', $this->AMT)) throw new Exception('Amount is in wrong format');
            if (strlen($this->VS) > 10) throw new Exception('Variable Symbol is in wrong format');
            if (!preg_match('/^[0-9]+$/', $this->VS)) throw new Exception('Variable Symbol is in wrong format');
            if (strlen($this->CS) > 4) throw new Exception('Constant Symbol is in wrong format');
            if (!preg_match('/^[0-9]+$/', $this->CS)) throw new Exception('Constant Symbol is in wrong format');
            if (isempty($this->RURL)) throw new Exception('Return URL is in wrong format');
            $urlRestrictedChars = array('&', '?', ';', '=', '+', '%');
            foreach ($urlRestrictedChars as $char)
                if (false !== strpos($this->RURL, $char)) throw new Exception('Return URL contains restricted character: "'.$char.'"');

            // nepovinne
            if (!isempty($this->SS)) {
                if (strlen($this->SS) > 10) throw new Exception('Specific Symbol is in wrong format');
                if (!preg_match('/^[0-9]+$/', $this->SS)) throw new Exception('Specific Symbol is in wrong format');
            }
            if (!isempty($this->PT))
                if ($this->PT != 'TatraPay') throw new Exception('Payment Type parameter must be "TatraPay"');
            if (!isempty($this->RSMS))
                if (!preg_match('/^(0|\\+421)9[0-9]{2}( ?[0-9]{3}){2}$/', $this->RSMS)) throw new Exception('Return SMS in wrong format.');
            if (!isempty($this->REM))
                if (!preg_match('/^[0-9a-z_]+(\.[0-9a-z_]+)*@([12]?[0-9]{0,2}(\.[12]?[0-9]{0,2}){3}|([a-z][0-9a-z\-]*\.)+[a-z]{2,6})$/', $this->REM)) throw new Exception('Return e-mail address in wrong format');
            if (!isempty($this->DESC))
                if (strlen($this->DESC) > 20) throw new Exception('Description is too long');
            if (!isempty($this->LANG)) {
                $validLanguages = array('SK', 'EN', 'DE', 'RU');
                if (!in_array($this->LANG, $validLanguages)) throw new Exception('Unknown language, known languages are: '.implode(',', $validLanguages));
            }
            return true;

        } catch (Exception $e) {
            if (defined('DEBUG') && DEBUG) {
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
        if (!isempty($this->PT)) {
            $url .= "?PT={$this->PT}";
            $url .= "&MID={$this->MID}";
        } else {
            $url .= "?MID={$this->MID}";
        }
        $url .= "&AMT={$this->AMT}";
        $url .= "&CURR={$this->CURR}";
        $url .= "&VS={$this->VS}";
        if (!isempty($this->SS))
            $url .= "&SS={$this->SS}";
        $url .= "&CS={$this->CS}";
        $url .= "&RURL=".urlencode($this->RURL);
        $url .= "&SIGN={$this->SIGN}";

        if (!isempty($this->RSMS))
            $url .= "&RSMS=".urlencode($this->RSMS);
        if (!isempty($this->REM))
            $url .= "&REM=".urlencode($this->REM);
        if (!isempty($this->DESC))
            $url .= "&DESC=".urlencode($this->DESC);
        if (!isempty($this->AREDIR))
            $url .= "&AREDIR={$this->AREDIR}";
        if (!isempty($this->LANG))
            $url .= "&LANG={$this->LANT}";

        return $url;
    }
}