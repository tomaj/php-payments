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
require_once dirname(dirname(__FILE__)).'/EPaymentHmacSignedMessage.class.php';

class EPlatbaPaymentRequest extends EPaymentHmacSignedMessage implements IEPaymentHttpPostPaymentRequest {
    const EPlatba_EPayment_URL_Base = "https://ib.vub.sk/e-platbyeuro.aspx";
    private $urlBase = self::EPlatba_EPayment_URL_Base;
    
    public function SetRedirectUrlBase($url) {
      $this->urlBase = $url;
    }
    
    public function SetUrlBase($url) {
      $this->urlBase = $url;
    }

    public function __construct() {
        $this->requiredFields = array('MID', 'AMT', 'VS', 'CS', 'RURL');
        $this->optionalFields = array('SS', 'DESC', 'REM', 'RSMS');
    }

    public function validateData() {
        if (!is_string($this->AMT))
            $this->AMT = sprintf("%01.2F", $this->AMT);

        //echo "MID:";
        if (isempty($this->MID)) return false;
        if (strlen($this->MID) > 20) return false;

        //echo "AMT:";
        if (strlen((string)($this->AMT)) > 13) return false;
        if (strpos(',', (string)($this->AMT)) !== false) return false;

        //echo "VS:";
        if (isempty($this->VS)) return false;
        if (strlen($this->VS) > 10) return false;
        if (!preg_match('/^[0-9]+$/', $this->VS)) return false;

        //echo "CS:";
        if (isempty($this->CS)) return false;
        if (strlen($this->CS) > 4) return false;
        if (!preg_match('/^[0-9]+$/', $this->CS)) return false;

        //echo "RURL:";
        if (isempty($this->RURL)) return false;
        if (!preg_match("~^https?://.+$~", $this->RURL)) return false;

        if (!isempty($this->SS)) {
            //echo "SS:";
            if (strlen($this->SS) > 10) return false;
            if (!preg_match('/^[0-9]+$/', $this->SS)) return false;
        }

        return true;
    }

    protected function getSignatureBase() {
        return $this->MID.$this->AMT.$this->VS.$this->SS.$this->CS.$this->RURL;
    }

    public function SignMessage($password) {
        $this->fields['SIGN'] = $this->computeSign($password);
    }

    public function GetPaymentRequestFields() {
      $res = array(
        'MID' => $this->MID,
        'AMT' => $this->AMT,
        'VS'  => $this->VS,
        'CS'  => $this->CS,
        'RURL'=> $this->RURL,
        'SIGN'=> $this->SIGN
      );
      if (!isempty($this->SS))    $res['SS']   =  $this->SS;
      if (!isempty($this->DESC))  $res['DESC'] =  $this->DESC;
      if (!isempty($this->REM))   $res['REM']  =  $this->REM;
      if (!isempty($this->RSMS))  $res['RSMS'] =  $this->RSMS;
      return $res;
    }
    
    public function GetUrlBase() {
      return $this->urlBase;
    }
}