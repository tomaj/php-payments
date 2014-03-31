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
require_once dirname(dirname(__FILE__)).'/EPayment3DesSignedMessage.class.php';

class SporoPayPaymentHttpResponse extends EPayment3DesSignedMessage implements IEPaymentHttpPaymentResponse {
    public function __construct($fields = null) {
        $this->readOnlyFields = array(	'u_predcislo', 'u_cislo', 'u_kbanky', 'pu_predcislo', 'pu_cislo',
            'pu_kbanky', 'suma', 'mena', 'vs', 'ss', 'url', 'param', 'result', 'real', 'SIGN2');

        if ($fields == null) {
            $fields = $_GET;
        }

        $this->fields['u_predcislo']  = isset($fields['u_predcislo'])   ? $fields['u_predcislo'] : null;
        $this->fields['u_cislo']      = isset($fields['u_cislo'])       ? $fields['u_cislo'] : null;
        $this->fields['u_kbanky']     = isset($fields['u_kbanky'])      ? $fields['u_kbanky'] : null;
        $this->fields['pu_predcislo'] = isset($fields['pu_predcislo'])  ? $fields['pu_predcislo'] : null;
        $this->fields['pu_cislo']     = isset($fields['pu_cislo'])      ? $fields['pu_cislo'] : null;
        $this->fields['pu_kbanky']    = isset($fields['pu_kbanky'])     ? $fields['pu_kbanky'] : null;
        $this->fields['suma']         = isset($fields['suma'])          ? $fields['suma'] : null;
        $this->fields['mena']         = isset($fields['mena'])          ? $fields['mena'] : null;
        $this->fields['vs']           = isset($fields['vs'])            ? $fields['vs'] : null;
        $this->fields['ss']           = isset($fields['ss'])            ? $fields['ss'] : null;
        $this->fields['url']          = isset($fields['url'])           ? $fields['url'] : null;
        $this->fields['param']        = isset($fields['param'])         ? $fields['param'] : null;
        $this->fields['result']       = isset($fields['result'])        ? $fields['result'] : null;
        $this->fields['real']         = isset($fields['real'])          ? $fields['real'] : null;
        $this->fields['SIGN2']        = isset($fields['SIGN2'])         ? $fields['SIGN2'] : null;
    }

    protected function validateData() {
        if (!preg_match('/^[0-9]*$/', $this->u_predcislo)) return false;
        if (!preg_match('/^[0-9]+$/', $this->u_cislo)) return false;
        if (!preg_match('/^[0-9]+$/', $this->u_kbanky)) return false;

        if (!preg_match('/^[0-9]*$/', $this->pu_predcislo)) return false;
        if (!preg_match('/^[0-9]+$/', $this->pu_cislo)) return false;
        if ($this->pu_kbanky != '0900') return false;

        if (!preg_match('/^([0-9]+|[0-9]*\\.[0-9]{0,2})$/', $this->suma)) return false;
        if ($this->mena != 'EUR') return false;
        if (!preg_match('/^[0-9]{10}$/', $this->vs)) return false;
        if (!preg_match('/^[0-9]{10}$/', $this->ss)) return false;
        if (preg_match('/[\\;\\?\\&]/', $this->url)) return false;
        $results = array('OK', 'NOK');
        if (!in_array($this->result, $results)) return false;
        if (!in_array($this->real, $results)) return false;
        return true;
    }

    protected function getSignatureBase() {
        return "{$this->u_predcislo};{$this->u_cislo};{$this->u_kbanky};{$this->pu_predcislo};{$this->pu_cislo};{$this->pu_kbanky};{$this->suma};{$this->mena};{$this->vs};{$this->ss};{$this->url};{$this->param};{$this->result};{$this->real}";
    }

    protected $isVerified = false;
    public function VerifySignature($password) {
        if ($this->SIGN2 == $this->computeSign($password)) {
            $this->isVerified = true;
            return true;
        }
        return false;
    }

    public function GetPaymentResponse() {
        if (!$this->isVerified)
            throw new Exception(__METHOD__.": Message was not verified yet.");

        if ($this->result == 'OK' && $this->real == 'OK')
            return IEPaymentHttpPaymentResponse::RESPONSE_SUCCESS;
        if ($this->result == 'OK' && $this->real != 'OK')
            return IEPaymentHttpPaymentResponse::RESPONSE_TIMEOUT;
        if ($this->result != 'OK')
            return IEPaymentHttpPaymentResponse::RESPONSE_FAIL;

        return null;
    }
}