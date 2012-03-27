<?php

/**
 * This class represents the current web request
 *
 * @package LSF
 * @author tom
 */

class LSF_Request
{
	private
		$_locale = 'en-gb',
		$_controller = 'Default',
		$_action = 'index',
		$_params = array(),
		$_server,
		
		$_requestPath;
	private static
		$_getVars;
		
	
	public function __construct()
	{
		$this->_server = $_SERVER;
		$this->setUpGetVars();
		$this->setUpRequestPath();
	}
	
	/**
	 * Sets the locale for the request
	 *
	 * @param string $locale
	 * @return string
	 */
	public function setLocale($locale)
	{
		return $this->_locale = $locale;
	}
	
	/**
	 * Sets the controller for the request
	 *
	 * @param string $controller
	 * @return string
	 */
	public function setController($controller)
	{
		return $this->_controller = $controller;
	}
	
	/**
	 * Sets the action for the request
	 *
	 * @param string $action
	 * @return string
	 */
	public function setAction($action)
	{
		return $this->_action = $action;
	}
	
	/**
	 * Set the request params
	 *
	 * @param array $params
	 * @return array
	 */
	public function setParams($params)
	{
		return $this->_params = $params;
	}
	
	/**
	 * Adds a parameter to the request
	 *
	 * @param string $value
	 * @param string $name
	 * @return void
	 */
	public function addParam($value, $name=null)
	{
		if ($name) {
			$this->params[$name] = $value;
		}
		else {
			$this->params[] = $value;
		}
	}
	
	/**
	 * Return the locale string
	 *
	 * @return string $locale
	 */
	public function getLocale()
	{
		return $this->_locale;
	}
	
	/**
	 * Return the controller string
	 *
	 * @return string $controller
	 */
	public function getController()
	{
		return $this->_controller;
	}
	
	/**
	 * Return the action string
	 *
	 * @return string $action
	 */
	public function getAction()
	{
		return $this->_action;
	}
	
	/**
	 * Return the request params
	 *
	 * @return array $params
	 */
	public function getParams()
	{
		return $this->_params;
	}
	
	/**
	 * Return the remote IP address
	 */
	public function getIpAddress()
	{
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ipString=$_SERVER['HTTP_X_FORWARDED_FOR'];
		    $addr = explode(",",$ipString); 
		    return trim($addr[sizeof($addr)-1]);
		}
		return !empty($this->_server['REMOTE_ADDR']) ? $this->_server['REMOTE_ADDR'] : null;
	}
	
	/**
	 * Initiates the get vars
	 */
	private function setUpGetVars()
	{
		if (!isset(self::$_getVars))
		{
			self::$_getVars = array();
			   
			$urlParts = parse_url(!empty($this->_server['REQUEST_URI']) ? $this->_server['REQUEST_URI'] : null);
			
			if (isset($urlParts['query'])) {
				parse_str($urlParts['query'], self::$_getVars);
			}
		}
	}
	
	/**
	 * Returns and array of get vars
	 *
	 * @return array $getVars
	 */
	public function getGetVars()
	{
		return self::$_getVars;
	}
	
	/**
	 * Fetch a getvariable by index
	 * @param unknown_type $index
	 */
	public function getGetVar($index)
	{
		$inputs = $this->getGetVars();
		return !empty($inputs[$index]) ? $inputs[$index] : null;
	}
	
	/**
	 * Sets the value of an item in the get array
	 * @param string $var
	 * @param mixed $value
	 */
	public function setGetVar($var, $value)
	{
		return self::$_getVars[$var] = $value;
	}
	
	/**
	 * Sets the get array
	 * @param array $value
	 */
	public function setGetVars(array $value)
	{
		return self::$_getVars = $value;
	}
	
	/**
	 * Returns and array of post vars
	 *
	 * @return array $postVars
	 */
	public function getPostVars()
	{
		return $_POST;
	}

	/**
	 * Returns a single post var or false
	 * 
	 * @param string $index
	 * @return mixed
	 */
	public function getPostVar($index)
	{
		return !empty($_POST[$index]) ? $_POST[$index] : null;
	}
	
	/**
	 * Gets the current http request method, returns 'get' on fail
	 * 
	 * @return string
	 */
	public function getRequestMethod()
	{
		return !empty($this->_server['REQUEST_METHOD']) ? strtolower($this->_server['REQUEST_METHOD']) : 'get';
	}
	
	/**
	 * Private function to set up the request path
	 */
	private function setUpRequestPath()
	{
		$parts = parse_url(!empty($this->_server['REQUEST_URI']) ? $this->_server['REQUEST_URI'] : null);
		$this->_requestPath = !empty($parts['path']) ? $parts['path'] : null;
	}
	
	/**
	 * Returns the current request path
	 * 
	 * @return string
	 */
	public function getRequestPath()
	{
		return $this->_requestPath;
	}
	
	/**
	 * Sets the current request path
	 * 
	 * @param string $path
	 * @return string
	 */
	public function setRequestPath($path)
	{
		return $this->_requestPath = $path;
	}
	
	/**
	 * Returns a single url request parameter
	 * 
	 * @param string $index
	 * @return string or false
	 */
	public function getParam($index)
	{
		return !empty($this->_params[$index]) ? $this->_params[$index] : null;
	}
	
	/**
	 * Set a request parameter
	 * 
	 * @param string $index
	 * @param mixed $value
	 * @return value
	 */
	public function setParam($index, $value)
	{
		return $this->_params[$index] = $value;
	}
	
	/**
	 * Gets the content of the $_FILES array
	 * 
	 * @return array 
	 */
	public function getFiles()
	{
		return $_FILES;
	}
	
	/**
	 * Returns the content of the http referer
	 * @return string  
	 */
	public function getReferer()
	{
		return $this->getHeader('referer');
	}
	
	/**
	 * Returns the content of the server name header
	 * @return string  
	 */
	public function getHostname()
	{
		return !empty($this->_server['SERVER_NAME']) ? $this->_server['SERVER_NAME'] : null;
	}
	
	/**
	 * Returns a http header that was sent as part of the request
	 * for example to get the HTTP_USER_AGENT index should be passed as user-agent or user_agent
	 * 
	 * @param string $index
	 * @return string
	 */
	public function getHeader($index)
	{
		$index = strtoupper($index);
		$index = 'HTTP_' . $index;
		$index = str_replace('-', '_', $index);
		
		return !empty($this->_server[$index]) ? $this->_server[$index] : null;
	}
	
	/**
	 * Clears the static get cache
	 * 
	 * @return null
	 */
	public static function clearGetCache()
	{
		self::$_getVars = null;
	}
	
	public function __destruct() {}
}
