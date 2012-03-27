<?php

/**
 * Paypal client class
 *
 * @package LSF
 * @version $Id$
 * @copyright 2011
 */
class LSF_Service_Paypal_Client
{		
	private
		$_token,
		$_payerId,
		$_endpoint,
		$_username,
		$_password,
		$_signature,
		$_returnUrl,
		$_cancelUrl,
		$_apiVersion="72.0",
		$_description;
	
	public function __construct($token=null)
	{
		$this->_token = $token;
		
		$this->_endpoint   = LSF_Config::get('paypal_endpoint');
		$this->_username   = LSF_Config::get('paypal_username');
		$this->_password   = LSF_Config::get('paypal_password');
		$this->_signature  = LSF_Config::get('paypal_signature');
		$this->_returnUrl  = LSF_Config::get('paypal_return_url');
		$this->_cancelUrl  = LSF_Config::get('paypal_cancel_url');
	}

	/**
	 * Calls the setExpressCheckout method of paypal
	 * 
	 * @param float $amount
	 * @param bool $noShipping
	 * @return LSF_Service_Paypal_Response
	 */
	public function setExpressCheckout($amount, $noShipping=false)
	{
		$curl = new LSF_Utils_Curl_Client();
		
		$curl->setUri($this->_endpoint);
		
		$postData = array(
			'USER'							=> $this->_username,
			'PWD'							=> $this->_password,
			'SIGNATURE'						=> $this->_signature,
			'VERSION'						=> $this->_apiVersion,
			'PAYMENTREQUEST_0_CURRENCYCODE'	=> 'GBP',
			'PAYMENTREQUEST_0_AMT'			=> $amount,
			'PAYMENTREQUEST_0_DESC'			=> $this->_description,
			'NOSHIPPING'					=> (int)$noShipping,
			'ReturnURL'						=> $this->_returnUrl,
			'CancelURL'						=> $this->_cancelUrl,
			'METHOD'						=> 'SetExpressCheckout',
		);
		
		$curl->setPostData($postData);
		
		$response = new LSF_Service_Paypal_Response($curl->makeRequest('post'));
		
		if ($response->success()) {
			$this->_token = $response->getToken();
		}
		
		return $response;
	}
	
	/**
	 * Calls the GetExpressCheckoutDetails method of paypal
	 * 
	 * @return LSF_Service_Paypal_Response
	 */
	public function GetExpressCheckoutDetails()
	{
		$curl = new LSF_Utils_Curl_Client();
		
		$curl->setUri($this->_endpoint);
		
		$postData = array(
			'USER'			=> $this->_username,
			'PWD'			=> $this->_password,
			'SIGNATURE'		=> $this->_signature,
			'VERSION'		=> $this->_apiVersion,
			'TOKEN'			=> $this->_token,
			'METHOD'		=> 'GetExpressCheckoutDetails',
		);
		
		$curl->setPostData($postData);
		
		$response = new LSF_Service_Paypal_Response($curl->makeRequest('post'));
		
		if ($response->success()) {
			$this->_token = $response->getToken();
		}
		
		return $response;
	}
	
	/**
	 * Calls the doExpressCheckout method of paypal
	 * 
	 * @param float $amount
	 * @return LSF_Service_Paypal_Response
	 */
	public function doExpressCheckout($amount)
	{
		$curl = new LSF_Utils_Curl_Client();
		
		$curl->setUri($this->_endpoint);
		
		$postData = array(
			'USER'							=> $this->_username,
			'PWD'							=> $this->_password,
			'SIGNATURE'						=> $this->_signature,
			'VERSION'						=> $this->_apiVersion,
			'PAYMENTREQUEST_0_CURRENCYCODE'	=> 'GBP',
			'PAYMENTREQUEST_0_AMT'			=> $amount,
			'PAYMENTREQUEST_0_PAYMENTACTION'=> 'Sale',
			'PAYMENTREQUEST_0_DESC'			=> $this->_description,
			'TOKEN'							=> $this->_token,
			'PAYERID'						=> $this->_payerId,
			'METHOD'						=> 'DoExpressCheckoutPayment',
		);
		
		$curl->setPostData($postData);
		
		return  new LSF_Service_Paypal_Response($curl->makeRequest('post'));;
	}
	
	/**
	 * Gets the current payment token
	 * 
	 * @return string
	 */
	public function getToken()
	{
		return $this->_token;
	}
	
	/**
	 * Sets the current payerId
	 * 
	 * @param string $payerId
	 * @return void
	 */
	public function setPayerId($payerId)
	{
		$this->_payerId = (string)$payerId;
	}
	
	/**
	 * Gets the current payerId
	 * 
	 * @return string
	 */
	public function getPayerId()
	{
		return $this->_payerId;
	}
	
	/**
	 * Sets the current payment description
	 * 
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description)
	{
		$this->_description = (string)$description;
	}
}