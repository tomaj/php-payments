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

interface IEPaymentSignedPaymentRequest {
    public function SignMessage($sharedSecret);
}
	
interface IEPaymentHttpRedirectPaymentRequest extends IEPaymentSignedPaymentRequest {
    public function SetRedirectUrlBase($url);
    public function GetRedirectUrl();
}

interface IEPaymentHttpPaymentResponse {
    public function VerifySignature($password);

    public function GetPaymentResponse();

    const RESPONSE_SUCCESS = 1;
    const RESPONSE_FAIL    = 2;
    const RESPONSE_TIMEOUT = 3;
}

interface IEPaymentHttpPostPaymentRequest extends IEPaymentSignedPaymentRequest {
    public function SetUrlBase($url);
    public function GetPaymentRequestFields();
    public function GetUrlBase();
}

function isempty($var) {
    return empty($var);
}