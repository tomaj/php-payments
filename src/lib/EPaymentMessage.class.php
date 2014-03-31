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
require_once dirname(__FILE__).'/EPayment.interfaces.php';

abstract class EPaymentMessage {
    protected $fields = array();
    protected $readOnlyFields = array();
    protected $requiredFields = array();
    protected $optionalFields = array();
    protected $isValid = false;

    public function __GET($name) {
        if (!isset($this->fields[$name]))
            return null;
        return $this->fields[$name];
    }

    public function __SET($name, $value) {
        if (in_array($name, $this->readOnlyFields))
            throw new Exception("Trying to change a read only field '$name'.");

        if (!in_array($name, $this->requiredFields) && !in_array($name, $this->optionalFields))
            throw new Exception("Trying to set unknown field '$name'.");

        $this->fields[$name] = $value;
        $this->isValid = false;
    }

    protected function checkRequiredFields() {
        foreach ($this->requiredFields as $requiredField) {
            if (!isset($this->fields[$requiredField]))
                return false;
        }
        return true;
    }

    public function Validate() {
        if (!$this->checkRequiredFields())
            return false;
        if ($this->validateData()) {
            $this->isValid = true;
            return true;
        } else {
            return false;
        }
    }

    public abstract function computeSign($sharedSecret);
    protected abstract function validateData();
    protected abstract function getSignatureBase();
}