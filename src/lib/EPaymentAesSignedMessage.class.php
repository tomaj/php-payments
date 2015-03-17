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
require_once dirname(__FILE__) . '/EPaymentMessage.class.php';

abstract class EPaymentAesSignedMessage extends EPaymentMessage {
    public function computeSign($sharedSecret) {
        if (!$this->isValid)
            throw new Exception(__METHOD__.": Message was not validated.");

        try {
            $bytesHash = sha1($this->GetSignatureBase(), true);
            $sharedSecret = pack('H*', $sharedSecret);

            // uprava pre PHP < 5.0
            if (strlen($bytesHash) != 20) {
                $bytes = "";
                for ($i = 0; $i < strlen($bytesHash); $i+=2)
                    $bytes .= chr(hexdec(substr($str, $i, 2)));
                $bytesHash = $bytes;
            }

            $cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, "", MCRYPT_MODE_ECB, "");

            $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($cipher), MCRYPT_RAND);
            mcrypt_generic_init($cipher, $sharedSecret, $iv);

            $text = $this->pad(substr($bytesHash, 0, 16), mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB));
            $bytesSign = mcrypt_generic($cipher, $text);

            mcrypt_generic_deinit($cipher);
            mcrypt_module_close($cipher);
            $sign = substr(strtoupper(bin2hex($bytesSign)), 0, 32);
        } catch (Exception $e) {
            return false;
        }
        return $sign;
    }

    private function pad($str, $blocksize)
    {
        $pad = $blocksize - (strlen($str) % $blocksize);
        return $str = $str . str_repeat(chr($pad), $pad);
    }
}