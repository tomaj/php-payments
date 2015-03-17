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

class ComfortPayPaymentHttpResponse extends CardPayAesPaymentHttpResponse implements IEPaymentHttpPaymentResponse
{
    protected $isVerified = null;

    public function __construct($fields = null)
    {
        parent::__construct($fields);

        $this->readOnlyFields = array('SS', 'VS', 'AC', 'RES', 'SIGN', 'TRES', 'CID');

        if ($fields == null)
        {
            $fields = $_GET;
        }

        $this->fields['TRES'] = isset($fields['TRES']) ? $fields['TRES'] : null;
        $this->fields['CID'] = isset($fields['CID']) ? $fields['CID'] : null;
    }

    protected function getSignatureBase()
    {
        return "{$this->VS}{$this->TRES}{$this->AC}{$this->CID}";
    }

    public function teleplatbaResult()
    {
        // Successful ComfortPay registration
        if ($this->TRES == "OK")
        {
            return IEPaymentHttpPaymentResponse::RESPONSE_SUCCESS;
        }
        // ComfortPay registration failed
        else
        {
            return IEPaymentHttpPaymentResponse::RESPONSE_FAIL;
        }
    }

    public function VerifySignature($password)
    {

        if ($this->SIGN == $this->computeSign($password))
        {
            $this->isVerified = true;
            return true;
        }
        return false;
    }

    public function Validate()
    {
        if (!$this->checkRequiredFields())
        {
            return false;
        }

        if ($this->validateData())
        {
            $this->isValid = true;
            return true;
        }
        else
        {
            return false;
        }
    }
}