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

class CardPayPaymentHttpResponse extends EPaymentDesSignedMessage implements IEPaymentHttpPaymentResponse {
	public function __construct($fields = null) {
		$this->readOnlyFields = array('SS', 'VS', 'AC','RES', 'SIGN');

                    if ($fields == null) {
                        $fields = $_GET;
                    }

		$this->fields['VS'] = isset($fields['VS']) ? $fields['VS'] : null;
		$this->fields['AC'] = isset($fields['AC']) ? $fields['AC'] : null;
		$this->fields['RES'] = isset($fields['RES']) ? $fields['RES'] : null;
		$this->fields['SIGN'] = isset($fields['SIGN']) ? $fields['SIGN'] : null;
	}
	
	protected function validateData() {
		if (isempty($this->VS)) return false;
		if (!($this->RES == "FAIL" || $this->RES == "OK" || $this->RES == "TOUT")) return false;
		
		return true;
	}

	protected function getSignatureBase() {
		return "{$this->VS}{$this->RES}{$this->AC}";
	}

protected $isVerified = false;
	public function VerifySignature($password) {
		if ($this->SIGN == $this->computeSign($password)) {
			$this->isVerified = true;
			return true;
		}
		return false;
	}

	public function GetPaymentResponse() {
		if (!$this->isVerified)
			throw new Exception(__METHOD__.": Message was not verified yet.");

		if ($this->RES == "FAIL")
			return IEPaymentHttpPaymentResponse::RESPONSE_FAIL;
		else if ($this->RES == "OK")
			return IEPaymentHttpPaymentResponse::RESPONSE_SUCCESS;
		else
			return null;
	}
}