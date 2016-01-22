<?php

class ComfortPayPaymentSoapRequest  {

    private $client;

    public function __construct()
    {
        $options = array(
            'trace' => true,
            'exception' => true,
            'local_cert' => TB_COMFORTPAY_LOCAL_CERT_PATH,
            'passphrase' => trim(file_get_contents(TB_COMFORTPAY_PASSPHRASE_PATH)),
            'connection_timeout' => 5,
            'keep_alive' => false,
            'soap_version' => SOAP_1_2,
            'cache_wsdl' => WSDL_CACHE_NONE
        );

        $this->client = new SoapClient(__DIR__ . '/../../cardpay.wsdl', $options);
    }

    public function doCardTransaction($transactionId, $referedCardId, $amount, $currency, $vs, $ss)
    {
        $data = array(
            'transactionId' => $transactionId,
            'referedCardId' => $referedCardId,
            'merchantId' => TB_COMFORTPAY_WS_MID,
            'terminalId' => TB_COMFORTPAY_TERMINALID,
            'amount' => $amount,
            'currency' => $currency,
            'vs' => $vs,
            'ss' => $ss
        );

        $param = new SoapParam($data, 'TransactionRequest');
        return $this->client->doCardTransaction($param);
    }

    public function getTransactionStatus($transactionId){
        $this->client->getTransactionStatus($transactionId);
    }

    public function checkCard($cardId)
    {
        if (empty($cardId)) {
            return false;
        }
        $response = $this->client->checkCard($cardId);
        return $response == 0 || $response == 3;
    }

    public function getCertificate(){
        throw new \Exception('Not yet implemented');
    }

    public function registerCard(){
        throw new \Exception('Not yet implemented');
    }

    /**
     * Get month and year when CID expire
     *
     * @param array|string $ids   collection of cids
     * @return array   collection with all fetched cids in format array('id' => ..., 'date' => 'ym')
     */
    public function getListOfExpirePerId($ids)
    {
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        $this->client->__call('getListOfExpirePerId', array('listOfIds' => $ids));
        $xmlResponse = $this->client->__getLastResponse();

        $xml = new SimpleXMLElement($xmlResponse);
        $result = $xml->xpath('//pair');
        return array_map(function($element) {
            return array(
                'id' => (string)$element->idOfCard,
                'date' => (string)$element->expirationDate
            );
        }, $result);
    }

    /**
     * Get expiration list for date
     *
     * @param string $expirationDate  date in ISO 8601 format - date('c')
     * @return array
     */
    public function getListOfExpire($expirationDate)
    {
        $this->client->__call('getListOfExpire', ["expirationDate" => $expirationDate]);
        $xmlResponse = $this->client->__getLastResponse();

        $xml = new SimpleXMLElement($xmlResponse);
        $result = $xml->xpath('//id');
        return array_map(function($element) {
            return (string)$element;
        }, $result);
    }

    public function getIdFromCardNum(){
        throw new \Exception('Not yet implemented');
    }

    public function unRegisterCard(){
        throw new \Exception('Not yet implemented');
    }
}
