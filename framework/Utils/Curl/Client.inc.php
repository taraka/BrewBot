<?php

/**
 * HTTP client class for cURL
 *
 * @package LSF
 * @version $Id$
 * @copyright 2011
 */

class LSF_Utils_Curl_Client
{
	private
		$_uri,
		$_headers = array(),
		$_postData,
		$_rawData;
	
	public function __construct() {}
	
	/**
	 * Set a URI to request
	 *
	 * @param string $uri
	 * @return void
	 */
	public function setUri($uri)
	{
		if (is_string($uri)) {
			$this->_uri = $uri;
		}
	}
	
	/**
	 * Set request headers,
	 * either numerically indexed as key:value or as array(key => value)
	 *
	 * @param array $headers
	 * @return void
	 */
	public function setHeaders($headers)
	{
		$this->_headers = array();
		
		if (is_array($headers))
		{
			foreach ($headers as $key => $value)
			{
				if (!is_numeric($key)) {
					$this->_headers[] = $key . ':' . $value;
				}
				else {
					$this->_headers[] = $value;
				}
			}
		}
	}
	
	/**
	 * Set an array of post data to be sent in the request
	 * 
	 * @param array $inData
	 * @return void
	 */
	public function setPostData(array $inData)
	{
		$this->_postData = $inData;
	}
	
	/**
	 * Sets data to be sent in request body
	 *
	 * @param string $data
	 * @return void
	 */
	public function setRawData($data)
	{
		$this->_rawData = $data;
	}
	
	/**
	 * Send the HTTP request
	 *
	 * @param string $method the HTTP method to use (GET, POST, PUT, DELETE)
	 * @return LSF_Http_Response object
	 */
	public function makeRequest($method)
	{
		$method = strtoupper($method);
		$curl 	= curl_init();
		
		if ($method == 'HEAD')
		{
			curl_setopt($curl, CURLOPT_HEADER, true);
			curl_setopt($curl, CURLOPT_NOBODY, true);
		}
		
		curl_setopt($curl, CURLOPT_URL, $this->_uri);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $this->_headers);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_MAXREDIRS, 4);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		
		if ($this->_postData)
		{
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->_postData));
		}
		elseif ($this->_rawData)
		{
			$fileData = tmpfile();
			fwrite($fileData, $this->_rawData);
			fseek($fileData, 0);
			
			curl_setopt($curl, CURLOPT_PUT, true);
			curl_setopt($curl, CURLOPT_INFILE, $fileData);
			curl_setopt($curl, CURLOPT_INFILESIZE, strlen($this->_rawData));
		}
		
		$response	= curl_exec($curl);
		$info		= curl_getinfo($curl);
		
		curl_close($curl);
		
		if (isset($fileData)) {
			fclose($fileData);
		}
		
		$headerCount = 2;
		
		if (substr($response, 0, 21) == 'HTTP/1.1 100 Continue') {
			$headerCount = 3;
		}
		
		$headers		= explode("\r\n\r\n", $response, $headerCount);
		$responseCode	= false;
		$responseBody	= array_pop($headers);
		$headerArray 	= array();
		
		foreach ($headers as $line)
		{
			if (!preg_match('@^HTTP/[0-9]\.[0-9] ([0-9]{3})@', $line)) {
				list($header, $value) = explode(': ', $line, 2);
				$headerArray[$header] = $value;
			}
		}
		
		return new LSF_Utils_Curl_Response($info['http_code'], $headerArray, trim($responseBody));
	}
}
