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

class SporoPayPaymentRequest extends EPayment3DesSignedMessage implements IEPaymentHttpRedirectPaymentRequest {
    const SporoPay_EPayment_URL_Base = "https://ib.slsp.sk/epayment/epayment/epayment.xml";
    private $redirectUrlBase = self::SporoPay_EPayment_URL_Base;

    public function SetRedirectUrlBase($url) {
        $this->redirectUrlBase = $url;
    }

    public function __construct() {
        $this->requiredFields = array('pu_predcislo', 'pu_cislo', 'pu_kbanky', 'suma', 'mena', 'vs', 'ss', 'url', 'param');
        $this->optionalFields = array('acc_prefix', 'acc_number', 'mail_notif_att', 'email_adr', 'client_login', 'auth_tool_type');

        $this->pu_kbanky = '0900'; // konstanta v protokole v052006
        $this->mena = 'EUR'; // konstanta v protokole v052006

        $this->readOnlyFields = array('pu_kbanky', 'mena'); // konstanty v protokole v052006
    }

    protected function validateData() {
        if (!is_string($this->suma))
            $this->suma = sprintf("%01.2F", $this->suma);

        if (!preg_match('/^[0-9]*$/', $this->pu_predcislo)) return false;
        if (!preg_match('/^[0-9]+$/', $this->pu_cislo)) return false;
        // kbanky - konstanta
        if (!preg_match('/^([0-9]+|[0-9]*\\.[0-9]{0,2})$/', $this->suma)) return false;
        // mena - konstanta
        if (!preg_match('/^[0-9]{10}$/', $this->vs)) return false;
        if (!preg_match('/^[0-9]{10}$/', $this->ss)) return false;
        if (preg_match('/[\\;\\?\\&]/', $this->url)) return false;
        if (preg_match('/[\\;\\?\\&]/', $this->param)) return false;

        return true;
    }

    protected function getSignatureBase() {
        return "{$this->pu_predcislo};{$this->pu_cislo};{$this->pu_kbanky};{$this->suma};{$this->mena};{$this->vs};{$this->ss};{$this->url};{$this->param}";
    }

    public function SignMessage($password) {
        $this->fields['sign1'] = $this->computeSign($password);
    }

    public function GetRedirectUrl() {
        $url = $this->redirectUrlBase.'?';
        $url .= "pu_predcislo={$this->pu_predcislo}";
        $url .= "&pu_cislo={$this->pu_cislo}";
        $url .= "&pu_kbanky={$this->pu_kbanky}";
        $url .= "&suma={$this->suma}";
        $url .= "&mena={$this->mena}";
        $url .= "&vs={$this->vs}";
        $url .= "&ss={$this->ss}";
        $url .= "&url=".urlencode($this->url);
        $url .= "&param=".urlencode($this->param);

        if (!isempty($this->acc_prefix))
            $url .= "&acc_prefix={$this->acc_prefix}";
        if (!isempty($this->acc_number))
            $url .= "&acc_number={$this->acc_number}";
        if (!isempty($this->mail_notif_att))
            $url .= "&mail_notif_att={$this->mail_notif_att}";
        if (!isempty($this->email_adr))
            $url .= "&email_adr=".urlencode($this->email_adr);
        if (!isempty($this->client_login))
            $url .= "&clien_login={$this->client_login}";
        if (!isempty($this->auth_tool_type))
            $url .= "&auth_tool_type={$this->auth_tool_type}";

        $url .= "&sign1=".urlencode($this->sign1);

        return $url;
    }
}