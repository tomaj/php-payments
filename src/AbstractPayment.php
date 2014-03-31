<?php

namespace Tomaj\Epayment;

class AbstractPayment
{
	/**
	 * amount
	 *
	 * @var	float
	 */
	protected $amount;

	/**
	 * variable symbol
	 * @var	string
	 */
	protected $variableSymbol;

	/**
	 * specific symbol
	 * 
	 * @var	string
	 */
	protected $specificSymbol;
	
	/**
	 * return url
	 * @var	string
	 */
	protected $returnUrl;

	public function __construct($amount = NULL, $variableSymbol = NULL, $returnUrl = NULL)
	{
		$this->amount = $amount;
		$this->variableSymbol = $variableSymbol;
		$this->returnUrl = $returnUrl;
	}

	public function setAmount($amount)
	{
		$this->amount = $amount;
	}

	public function setVariableSymbol($variableSymbol)
	{
		$this->variableSymbol = $variableSymbol;
	}

	public function setSpecificSymbol($specificSymbol)
	{
		$this->specificSymbol = $specificSymbol;
	}
	
	public function setReturnUrl($returnUrl)
	{
		$this->returnUrl = $returnUrl;
	}
}
