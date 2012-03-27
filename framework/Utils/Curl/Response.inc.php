<?php

/**
 * Curl response class
 *
 * @package LSF
 * @version $Id$
 * @copyright 2010
 */

class LSF_Utils_Curl_Response
{
	private
		$_status,
		$_headers = array(),
		$_result;
	
	public function __construct($statusCode, $headers, $body)
	{
		$this->setStatus($statusCode);
		$this->setHeaders($headers);
		$this->setResult($body);
	}
	
	/**
	 * Set the internal status code
	 *
	 * @param int $status
	 * @return void
	 */
	private function setStatus($status)
	{
		if (is_numeric($status)) {
			$this->_status = $status;
		}
	}
	
	/**
	 * Set the response headers
	 *
	 * @param array $headers
	 * @return void
	 */
	private function setHeaders($headers)
	{
		if (is_array($headers)) {
			$this->_headers = $headers;
		}
	}
	
	/**
	 * Sets the result message body
	 *
	 * @param string $result
	 * @return void
	 */
	private function setResult($result)
	{
		if (is_string($result)) {
			$this->_result = $result;
		}
	}
	
	/**
	 * Returns the HTTP status response code
	 *
	 * @return int
	 */
	public function getStatus()
	{
		return $this->_status;
	}
	
	/**
	 * Returns an array of response headers
	 *
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->_headers;
	}
	
	/**
	 * Returns a particular header if it exists
	 *
	 * @param string $header
	 * @return string or bool false
	 */
	public function getHeader($header)
	{
		return is_string($header) && isset($this->_headers[$header]) ? $this->_headers[$header] : false;
	}
	
	/**
	 * Returns the response body
	 *
	 * @return string
	 */
	public function getResult()
	{
		return $this->_result;
	}
}
