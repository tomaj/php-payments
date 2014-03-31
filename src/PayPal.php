<?php

namespace Tomaj\Epayment;

class PayPal extends AbstractPayment
{	
	const RESPONSE_SUCCESS = 1;
	const RESPONSE_FAIL    = 2;
	const RESPONSE_TIMEOUT = 3;
	
	protected $config;
		
	protected $service;
	
	protected $base;
	
	protected $token;
	
	protected $payer;
	
	protected $productName;
	
	public function __construct($amount = NULL, $variableSymbol = NULL, $returnUrl = NULL)
	{
		require dirname(__FILE__) . '/../../Monogram/PayPal/autoload.php';
		$this->init();
		$this->amount = $amount;
		$this->variableSymbol = $variableSymbol;
		$this->returnUrl = $returnUrl;
	}
	
	public function setProductName($productName) {
		$this->productName = $productName;
	}
	
	public function setToken($token) {
		$this->token = $token;
	}
	
	public function setPayer($payer) {
		$this->payer = $payer;
	}
		
	public function init() {
		$this->config = \Nette\Environment::getConfig('paypal');
		$configArray = array(
			'mode' => $this->config->mode,
			'acct1.UserName' => $this->config->userName,
			'acct1.Password' => $this->config->password,
			'acct1.Signature' => $this->config->signature,
			'http.ConnectionTimeOut' => $this->config->connectionTimeOut,
			'http.Retry' => $this->config->retry,
			'log.LogEnabled' => $this->config->logEnabled,
			'log.FileName' => $this->config->fileName,
			'log.LogLevel' => $this->config->logLevel,
		);
		
		$this->service = new \PayPalAPIInterfaceServiceService($configArray);
		if($this->config->mode == 'sandbox') {
			$this->base = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';
		}
		else {
			$this->base = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';
		}
	}
	
	/*protected function getPayKey() {
		// create the receivers
		$receivers = array();
		
		$AppReceiver = new Receiver();
		$AppReceiver->email = $this->config->account;
		$AppReceiver->amount = $this->amount;
		$AppReceiver->paymentType = 'SERVICE';
		$receivers[] = $AppReceiver;
		
		$receiverList = new ReceiverList($receivers);
		
		$payRequest = new PayRequest(new RequestEnvelope("en_US"), 'PAY', 'http://order.radiomed.mikoczy.monogram.sk/case/pay-now?cancel=1', 'EUR', $receiverList, 'http://order.radiomed.mikoczy.monogram.sk/case/pay-now?ok=1');
		
		$payRequest->ipnNotificationUrl = 'http://order.radiomed.mikoczy.monogram.sk/paypal/ipn';
		$payRequest->trackingId = time();
		$payRequest->memo = 'predplatne';
		
		//_ap-payment&paykey=
		
		try {
			$response = $this->service->Pay($payRequest);
			//var_dump($response);
			return $response->payKey;
		} catch(Exception $ex) {
			require_once 'Common/Error.php';
			exit;
		}
	}
	
	protected function setOptions($payKey) {
		$setPaymentOptionsRequest = new SetPaymentOptionsRequest(new RequestEnvelope("en_US"));
		$setPaymentOptionsRequest->payKey = $payKey;
		
		$receiverOptions = new ReceiverOptions();
		$setPaymentOptionsRequest->receiverOptions[] = $receiverOptions;
		$receiverOptions->invoiceData = new InvoiceData();
		
		$receiverId = new ReceiverIdentifier();
		$receiverId->email = $this->config->account;
		$receiverOptions->receiver = $receiverId;
		
		
		
		try {
			$response = $this->service->SetPaymentOptions($setPaymentOptionsRequest);
		} catch(Exception $ex) {
			require_once 'Common/Error.php';
			exit;
		}
	}
	
	public function request() {
		$payKey = $this->getPayKey();
		$this->setOptions($payKey);
		
		$redirectUrl = 'https://www.sandbox.paypal.com/webscr&cmd=_ap-payment&paykey=' . $payKey;
		return $redirectUrl;
	}*/
	
	public function request() {
		try {
			$orderTotal = new \BasicAmountType();
			$orderTotal->currencyID = 'EUR';
			$orderTotal->value = $this->amount;
			
			$taxTotal = new \BasicAmountType();
			$taxTotal->currencyID = 'EUR';
			$taxTotal->value = '0.0';
			
			$itemDetails = new \PaymentDetailsItemType();
			$itemDetails->Name = 'Predplatné - ' . $this->productName;
			
			$itemDetails->Amount = $this->amount;
			$itemDetails->Quantity = '1';
			$itemDetails->ItemCategory =  'Digital';
			
			$PaymentDetails= new \PaymentDetailsType();
			$PaymentDetails->PaymentDetailsItem[0] = $itemDetails;
			
			$PaymentDetails->OrderTotal = $orderTotal;
			$PaymentDetails->PaymentAction = 'Sale';
			$PaymentDetails->ItemTotal = $orderTotal;
			$PaymentDetails->TaxTotal = $taxTotal;
			
			$setECReqDetails = new \SetExpressCheckoutRequestDetailsType();
			$setECReqDetails->PaymentDetails[0] = $PaymentDetails;
			$setECReqDetails->CancelURL = $this->returnUrl . '?vs=' . $this->variableSymbol . '&paypal_success=0';
			$setECReqDetails->ReturnURL = $this->returnUrl . '?vs=' . $this->variableSymbol . '&paypal_success=1';
			$setECReqDetails->ReqConfirmShipping = 0;
			$setECReqDetails->NoShipping = 1;
			
			$setECReqType = new \SetExpressCheckoutRequestType();
			$setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;
			
			$setECReq = new \SetExpressCheckoutReq();
			$setECReq->SetExpressCheckoutRequest = $setECReqType;
			
			$PayPal_service = $this->service;
			$setECResponse = $PayPal_service->SetExpressCheckout($setECReq);
			
			if($setECResponse->Ack == 'Success')
			{
				$token = $setECResponse->Token;
				return $this->base . $token;
			}
			else {
				$err = '';
				foreach($setECResponse->Errors as $error) {
					$err  .= $error->ErrorCode . ': ' . $error->ShortMessage . ' - ' . $error->LongMessage;
				}
				throw new \Exception('PayPal error: ' . $err);
			}
		}
		catch(\PPConnectionException $e) {
			$detailMessage = "Error connecting to " . $e->getUrl();
			$ex = new \Exception('PayPal error: ' . get_class($e) . ' - ' . $e->getMessage() . ' - ' . $detailMessage);
			\Nette\Diagnostics\Debugger::log($ex, \Nette\Diagnostics\Debugger::ERROR);
			return FALSE;
		}
		catch(\PPMissingCredentialException $e) {
			$detailMessage = $e->errorMessage();
			$ex = new \Exception('PayPal error: ' . get_class($e) . ' - ' . $e->getMessage() . ' - ' . $detailMessage);
			\Nette\Diagnostics\Debugger::log($ex, \Nette\Diagnostics\Debugger::ERROR);
			return FALSE;
		}
		catch(\PPInvalidCredentialException $e) {
			$detailMessage = $e->errorMessage();
			$ex = new \Exception('PayPal error: ' . get_class($e) . ' - ' . $e->getMessage() . ' - ' . $detailMessage);
			\Nette\Diagnostics\Debugger::log($ex, \Nette\Diagnostics\Debugger::ERROR);
			return FALSE;
		}
		catch(\PPConfigurationException $e) {
			$detailMessage = "Invalid configuration. Please check your configuration file";
			$ex = new \Exception('PayPal error: ' . get_class($e) . ' - ' . $e->getMessage() . ' - ' . $detailMessage);
			\Nette\Diagnostics\Debugger::log($ex, \Nette\Diagnostics\Debugger::ERROR);
			return FALSE;
		}
		catch(\Exception $e) {
			\Nette\Diagnostics\Debugger::log($e, \Nette\Diagnostics\Debugger::ERROR);
			return FALSE;
		}
	}
	
	public function response() {
		try {
			$getExpressCheckoutDetailsRequest = new \GetExpressCheckoutDetailsRequestType($this->token);
			$getExpressCheckoutReq = new \GetExpressCheckoutDetailsReq();
			$getExpressCheckoutReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;
			
			$getECResponse = $this->service->GetExpressCheckoutDetails($getExpressCheckoutReq);
			if($getECResponse->Ack != 'Success') {
				$err = '';
				foreach($getECResponse->Errors as $error) {
					$err  .= $error->ErrorCode . ': ' . $error->ShortMessage . ' - ' . $error->LongMessage;
				}
				throw new \Exception('PayPal error: ' . $err);
			}
			
			$orderTotal = new \BasicAmountType();
			$orderTotal->currencyID = $getECResponse->GetExpressCheckoutDetailsResponseDetails->PaymentDetails[0]->OrderTotal->currencyID;
			$orderTotal->value = $getECResponse->GetExpressCheckoutDetailsResponseDetails->PaymentDetails[0]->OrderTotal->value;
			
			$itemDetails = new \PaymentDetailsItemType();
			$itemDetails->Name = 'Predplatné - ' . $this->productName;
			$itemDetails->Amount = $orderTotal;
			$itemDetails->Quantity = '1';
			
			$itemDetails->ItemCategory =  'Digital';
			
			$PaymentDetails= new \PaymentDetailsType();
			$PaymentDetails->PaymentDetailsItem[0] = $itemDetails;
			
			$PaymentDetails->OrderTotal = $orderTotal;
			$PaymentDetails->PaymentAction = 'Sale';
			
			$PaymentDetails->ItemTotal = $orderTotal;
			
			$DoECRequestDetails = new \DoExpressCheckoutPaymentRequestDetailsType();
			$DoECRequestDetails->PayerID = $this->payer;
			$DoECRequestDetails->Token = $this->token;
			$DoECRequestDetails->PaymentDetails[0] = $PaymentDetails;
			
			$DoECRequest = new \DoExpressCheckoutPaymentRequestType();
			$DoECRequest->DoExpressCheckoutPaymentRequestDetails = $DoECRequestDetails;
			
			$DoECReq = new \DoExpressCheckoutPaymentReq();
			$DoECReq->DoExpressCheckoutPaymentRequest = $DoECRequest;
			
			$DoECResponse = $this->service->DoExpressCheckoutPayment($DoECReq);
			if($DoECResponse->Ack == 'Success')
			{
				return self::RESPONSE_SUCCESS;
			}
			else
			{
				$err = '';
				foreach($DoECResponse->Errors as $error) {
					$err  .= $error->ErrorCode . ': ' . $error->ShortMessage . ' - ' . $error->LongMessage;
				}
				throw new \Exception('PayPal error: ' . $err);
			}
		}
		catch(\PPConnectionException $e) {
			$detailMessage = "Error connecting to " . $e->getUrl();
			$ex = new \Exception('PayPal error: ' . get_class($e) . ' - ' . $e->getMessage() . ' - ' . $detailMessage);
			\Nette\Diagnostics\Debugger::log($ex, \Nette\Diagnostics\Debugger::ERROR);
			return self::RESPONSE_FAIL;
		}
		catch(\PPMissingCredentialException $e) {
			$detailMessage = $e->errorMessage();
			$ex = new \Exception('PayPal error: ' . get_class($e) . ' - ' . $e->getMessage() . ' - ' . $detailMessage);
			\Nette\Diagnostics\Debugger::log($ex, \Nette\Diagnostics\Debugger::ERROR);
			return self::RESPONSE_FAIL;
		}
		catch(\PPInvalidCredentialException $e) {
			$detailMessage = $e->errorMessage();
			$ex = new \Exception('PayPal error: ' . get_class($e) . ' - ' . $e->getMessage() . ' - ' . $detailMessage);
			\Nette\Diagnostics\Debugger::log($ex, \Nette\Diagnostics\Debugger::ERROR);
			return self::RESPONSE_FAIL;
		}
		catch(\PPConfigurationException $e) {
			$detailMessage = "Invalid configuration. Please check your configuration file";
			$ex = new \Exception('PayPal error: ' . get_class($e) . ' - ' . $e->getMessage() . ' - ' . $detailMessage);
			\Nette\Diagnostics\Debugger::log($ex, \Nette\Diagnostics\Debugger::ERROR);
			return self::RESPONSE_FAIL;
		}
		catch(\Exception $e) {
			\Nette\Diagnostics\Debugger::log($e, \Nette\Diagnostics\Debugger::ERROR);
			return self::RESPONSE_FAIL;
		}
	}
}
