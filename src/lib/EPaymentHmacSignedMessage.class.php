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

abstract class EPaymentHmacSignedMessage extends EPaymentMessage {
		protected function getRawSharedSecret($sharedSecret) {
                if (strlen($sharedSecret) == 64) {
                    return pack('A*', $sharedSecret);
                } else if (strlen($sharedSecret) == 128) {
                    return pack('H*', $sharedSecret);
				} else {
                    throw new Exception(__METHOD__.": Invalid shared secret format.");
				}
		}
		
    public function computeSign($sharedSecret) {
        if (!$this->isValid)
            throw new Exception(__METHOD__.": Message was not validated.");
        try {
            $signatureBase = $this->GetSignatureBase();
            $rawSharedSecret = $this->getRawSharedSecret($sharedSecret);

            $signature = hash_hmac('sha256', $signatureBase, $rawSharedSecret);
        } catch (Exception $e) {
            return false;
        }
        return $signature;
    }
}