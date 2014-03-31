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
require_once dirname(dirname(__FILE__)).'/EPaymentDesSignedMessage.class.php';

class UniPlatbaPaymentRequest extends EPaymentDesSignedMessage implements IEPaymentHttpRedirectPaymentRequest {
    const UniPlatba_EPayment_URL_Base = "https://sk.unicreditbanking.net/disp?restart=true&link=login.tplogin.system_login";
    private $redirectUrlBase = self::UniPlatba_EPayment_URL_Base;

    public function SetRedirectUrlBase($url) {
        $this->redirectUrlBase = $url;
    }

    public function __construct() {
        $this->readOnlyFields = array('SIGN');
        $this->requiredFields = array('MID', 'LNG', 'AMT', 'VS', 'CS');
        $this->optionalFields = array('SS', 'DESC');
    }

    protected function getSignatureBase() {
        $sb = "{$this->MID}{$this->LNG}{$this->AMT}{$this->VS}{$this->CS}{$this->SS}{$this->DESC}";
        return $sb;
    }

    protected function validateData() {
        try {
            if (!is_string($this->AMT))
                $this->AMT = sprintf("%01.2F", $this->AMT);

            if (!preg_match('/^[0-9]{1,13}\\.[0-9]{2}$/', $this->AMT)) throw new Exception('Amount must be a decimal number with 2 digits after period delimiter.');
            if (!preg_match('/^[0-9]{1,10}$/', $this->MID)) throw new Exception('Merchant ID is in wrong format');
            $validLanguages = array('SK', 'EN');
            if (!in_array($this->LNG, $validLanguages)) throw new Exception('Unknown language, known languages are: '.implode(',', $validLanguages));
            if (!preg_match('/^[0-9]{1,13}(\\.[0-9]{1,2})?$/', $this->AMT)) throw new Exception('Amount is in wrong format');
            if (strlen($this->VS) > 10) throw new Exception('Variable Symbol is in wrong format');
            if (!preg_match('/^[0-9]+$/', $this->VS)) throw new Exception('Variable Symbol is in wrong format');
            if (strlen($this->CS) != 4) throw new Exception('Constant Symbol must be 4 digits long');
            if (!preg_match('/^[0-9]+$/', $this->CS)) throw new Exception('Constant Symbol is in wrong format');

            // nepovinne
            if (!isempty($this->SS)) {
                if (strlen($this->SS) > 10) throw new Exception('Specific Symbol is in wrong format');
                if (!preg_match('/^[0-9]+$/', $this->SS)) throw new Exception('Specific Symbol is in wrong format');
            }
            if (!isempty($this->DESC)) {
                if (strlen($this->DESC) > 35) throw new Exception('Description is too long');
                if (strpos($this->DESC, ' ') !== false) throw new Exception('Description contains whitespace characters');
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

        if (strpos($url, '?') !== false) {
            $url .= '&';
        } else {
            $url .= '?';
        }

        $url .= "MID={$this->MID}";
        $url .= "&LNG={$this->LNG}";
        $url .= "&AMT={$this->AMT}";
        $url .= "&VS={$this->VS}";
        $url .= "&CS={$this->CS}";

        if (!isempty($this->SS))
            $url .= "&SS=".urlencode($this->SS);
        if (!isempty($this->DESC))
            $url .= "&DESC=".urlencode($this->DESC);

        $url .= "&SIGN={$this->SIGN}";
        return $url;
    }
}