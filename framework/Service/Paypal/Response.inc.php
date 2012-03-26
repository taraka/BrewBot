<?php

/**
 * Paypal response class
 *
 * @package LSF
 * @version $Id$
 * @copyright 2011
 */
class LSF_Service_Paypal_Response
{		
	private
		$_response,
		$_responseArray=array();
	
	public function __construct(LSF_Utils_Curl_Response $response)
	{
		$this->_response = $response;
		$this->parseResponse();
	}
	
	/**
	 * Get the Curl Response object
	 * 
	 * @return LSF_Utils_Curl_Response
	 */
	public function getHttpResponse()
	{
		return $this->_response;
	}
	
	/**
	 * Get the NVP response formatted as an array 
	 * 
	 * @return array
	 */
	public function getResponseArray()
	{
		return $this->_responseArray;
	}
	
	/**
	 * populates the $_responseArray
	 * 
	 * @return void
	 */
	private function parseResponse()
	{
		parse_str($this->getHttpResponse()->getResult(), $this->_responseArray);
	}
	
	/**
	 * Gets the email from the response
	 * 
	 * @return string
	 */
	public function getEmail()
	{
		return isset($this->_responseArray['EMAIL']) ? $this->_responseArray['EMAIL'] : null;
	}
	
	/**
	 * Gets the first name from the response
	 * 
	 * @return string
	 */
	public function getFirstName()
	{
		return isset($this->_responseArray['FIRSTNAME']) ? $this->_responseArray['FIRSTNAME'] : null;
	}
	
	/**
	 * Gets the last name from the response
	 * 
	 * @return string
	 */
	public function getLastName()
	{
		return isset($this->_responseArray['LASTNAME']) ? $this->_responseArray['LASTNAME'] : null;
	}
	
	/**
	 * Gets the amount from the response
	 * 
	 * @return string
	 */
	public function getAmount()
	{
		return isset($this->_responseArray['AMT']) ? $this->_responseArray['AMT'] : null;
	}
	
	/**
	 * Gets the token from the response
	 * 
	 * @return string
	 */
	public function getToken()
	{
		return isset($this->_responseArray['TOKEN']) ? $this->_responseArray['TOKEN'] : null;
	}
	
	/**
	 * Gets the acknowledgement from the response
	 * 
	 * @return string
	 */
	public function getAcknowledgement()
	{
		return isset($this->_responseArray['ACK']) ? $this->_responseArray['ACK'] : null;
	}
	
	/**
	 * Gets the error code from the response
	 * 
	 * @return int
	 */
	public function getErrorCode()
	{
		return isset($this->_responseArray['L_ERRORCODE0']) ? $this->_responseArray['L_ERRORCODE0'] : null;
	}
	
	/**
	 * Gets the error message from the response
	 * 
	 * @return string
	 */
	public function getErrorMessage()
	{
		return isset($this->_responseArray['L_SHORTMESSAGE0']) ? $this->_responseArray['L_SHORTMESSAGE0'] : null;
	}
	
	/**
	 * Gets the error details from the response
	 * 
	 * @return string
	 */
	public function getErrorDetails()
	{
		return isset($this->_responseArray['L_LONGMESSAGE0']) ? $this->_responseArray['L_LONGMESSAGE0'] : null;
	}
	
	/**
	 * Returns true if the payal reports a success
	 * 
	 * @return bool
	 */
	public function success()
	{
		return strtolower($this->getAcknowledgement()) == 'success';
	}
}