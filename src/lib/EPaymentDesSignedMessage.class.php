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
require_once dirname(__FILE__).'/EPaymentMessage.class.php';

abstract class EPaymentDesSignedMessage extends EPaymentMessage {
    public function computeSign($sharedSecret) {
        if (!$this->isValid)
            throw new Exception(__METHOD__.": Message was not validated.");

        try {
            $bytesHash = sha1($this->GetSignatureBase(), true);

            // uprava pre PHP < 5.0
            if (strlen($bytesHash) != 20) {
                $bytes = "";
                for ($i = 0; $i < strlen($bytesHash); $i+=2)
                    $bytes .= chr(hexdec(substr($str, $i, 2)));
                $bytesHash = $bytes;
            }

            $des = mcrypt_module_open(MCRYPT_DES, "", MCRYPT_MODE_ECB, "");

            $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($des), MCRYPT_RAND);
            mcrypt_generic_init($des, $sharedSecret, $iv);

            $bytesSign = mcrypt_generic($des, substr($bytesHash, 0, 8));

            mcrypt_generic_deinit($des);
            mcrypt_module_close($des);

            $sign = strtoupper(bin2hex($bytesSign));
        } catch (Exception $e) {
            return false;
        }
        return $sign;
    }
}