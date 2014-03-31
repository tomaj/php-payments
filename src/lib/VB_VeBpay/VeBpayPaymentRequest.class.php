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
require_once dirname(dirname(__FILE__)).'/EPaymentDesSignedMessage.class.php';

class VeBpayPaymentRequest extends EPaymentDesSignedMessage implements IEPaymentHttpRedirectPaymentRequest {
    const VeBpay_EPayment_URL_Base = "https://ibs.luba.sk/vebpay/";
    private $redirectUrlBase = self::VeBpay_EPayment_URL_Base;

    public function SetRedirectUrlBase($url) {
        $this->redirectUrlBase = $url;
    }

    public function __construct() {
        $this->readOnlyFields = array('SIGN');
        $this->requiredFields = array('MID', 'AMT', 'VS', 'CS', 'RURL');
        $this->optionalFields = array('DESC');
    }

    protected function getSignatureBase() {
        $sb = "{$this->MID}{$this->AMT}{$this->VS}{$this->CS}{$this->RURL}";
        return $sb;
    }

    protected function validateData() {
        try {
            if (!is_string($this->AMT))
                $this->AMT = sprintf("%01.2F", $this->AMT);

            if (isempty($this->MID)) throw new Exception('Merchant ID is empty');
            if (!preg_match('/^[0-9]+(\\.[0-9]+)?$/', $this->AMT)) throw new Exception('Amount is in wrong format');
            if (strlen($this->VS) > 10) throw new Exception('Variable Symbol is in wrong format');
            if (!preg_match('/^[0-9]+$/', $this->VS)) throw new Exception('Variable Symbol is in wrong format');
            if (strlen($this->CS) > 10) throw new Exception('Constant Symbol is in wrong format');
            if (!preg_match('/^[0-9]+$/', $this->CS)) throw new Exception('Constant Symbol is in wrong format');
            if (isempty($this->RURL)) throw new Exception('Return URL is in wrong format');
            $urlRestrictedChars = array('&', '?', ';', '=', '+', '%');
            foreach ($urlRestrictedChars as $char)
                if (false !== strpos($this->RURL, $char)) throw new Exception('Return URL contains restricted character: "'.$char.'"');

            if (!isempty($this->DESC))
                if (strlen($this->DESC) > 35) throw new Exception('Description is too long');

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

        $url .= "?MID={$this->MID}";
        $url .= "&AMT={$this->AMT}";
        $url .= "&VS={$this->VS}";
        $url .= "&CS={$this->CS}";
        $url .= "&RURL=".urlencode($this->RURL);

        if (!isempty($this->DESC))
            $url .= "&DESC=".urlencode($this->DESC);

        $url .= "&SIGN={$this->SIGN}";

        return $url;
    }
}