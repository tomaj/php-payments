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

abstract class EPayment3DesSignedMessage extends EPaymentMessage {
    public function computeSign($sharedSecret) {
        $signature = null;
        if (!$this->isValid)
            throw new Exception(__METHOD__.": Message was not validated.");

        try {
            $bytesHash = sha1($this->GetSignatureBase(), true);
            while (strlen($bytesHash) < 24)
                $bytesHash .= chr(0xFF);

            $ssBytes = base64_decode($sharedSecret);
            $key = $ssBytes . substr($ssBytes, 0, 8);

            $iv = chr(0x00);
            $iv .= $iv; // 2
            $iv .= $iv; // 4
            $iv .= $iv; // 8

            $signatureBytes = mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $bytesHash, MCRYPT_MODE_CBC, $iv);
            $signature = base64_encode($signatureBytes);
        } catch (Exception $e) {
            return false;
        }
        return $signature;
    }
}