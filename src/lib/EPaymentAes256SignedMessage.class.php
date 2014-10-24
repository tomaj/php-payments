<?php
/*
	Copyright 2014 MONOGRAM Technologies

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

abstract class EPaymentAes256SignedMessage extends EPaymentMessage {
	public function computeSign($sharedSecret) {
		if (!$this->isValid)
			throw new Exception(__METHOD__.": Message was not validated.");

		try {
			// ak mame zadany shared secret v hexa tvare tak ho prevedieme na 32 bytovy string
			if (strlen($sharedSecret) == 64) {
				$sharedSecret = pack('H*', $sharedSecret);
			}
			
			$base = $this->GetSignatureBase();
			$bytesHash = sha1($base, TRUE);
			
			// vezmeme prvych 16 bytov
			$bytesHash = substr($bytesHash, 0, 16);
			
			$aes = mcrypt_module_open (MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
			$iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($aes), MCRYPT_RAND);
			mcrypt_generic_init($aes, $sharedSecret, $iv);
			$bytesSign = mcrypt_generic($aes, $bytesHash);
			mcrypt_generic_deinit($aes);
			mcrypt_module_close($aes);
			
			$sign = strtoupper(bin2hex($bytesSign));
		} catch (Exception $e) {
			return FALSE;
		}
		return $sign;
	}
}