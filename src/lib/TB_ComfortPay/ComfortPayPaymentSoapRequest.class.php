<?php

class ComfortPayPaymentSoapRequest  {

    private $client;

    public function __construct(){
        $options = array(
            'local_cert' => TB_COMFORTPAY_LOCAL_CERT_PATH,
            'passphrase' => trim(file_get_contents(TB_COMFORTPAY_PASSPHRASE_PATH)),
            'connection_timeout' => 5,
            'cache_wsdl' => WSDL_CACHE_NONE
        );

        $this->client = new SoapClient(__DIR__ . '/../../cardpay.wsdl', $options);
    }

    public function doCardTransaction($transactionId, $referedCardId, $amount, $currency, $vs, $ss){
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

    public function checkCard($card_id){
        if(empty($card_id))
        {
            return false;
        }

        $response = $this->client->checkCard($card_id);

        if($response == 0 || $response == 3)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getCertificate(){
        throw new \Exception('Not yet implemented');
    }

    public function registerCard(){
        throw new \Exception('Not yet implemented');
    }

    public function getListOfExpire(){
        throw new \Exception('Not yet implemented');
    }

    public function getListOfExpirePerId(){
        throw new \Exception('Not yet implemented');
    }

    public function getIdFromCardNum(){
        throw new \Exception('Not yet implemented');
    }

    public function unRegisterCard(){
        throw new \Exception('Not yet implemented');
    }
}