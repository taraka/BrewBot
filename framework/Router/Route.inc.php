<?php

/**
 * 
 * Abstract base for route classes
 * 
 * @package LSF
 * @author tom
 */
abstract class LSF_Router_Route
{
	protected
		$requestParts = array();
		
	abstract public function routeAvaliable(LSF_Request $request);
	abstract public function route(LSF_Request $request);
	
	public function __construct() {}
	
	protected function getRequestParts(LSF_Request $request)
	{
		$parts = explode('/', $request->getRequestPath());
		if (empty($parts[0]))
		{
			array_shift($parts);
		}
		return $parts;
	}
	
	/**
	 * This Function Adds the remaings request parts as parameter
	 * 
	 * @param LSF_Request $request
	 */
	protected function processParams(LSF_Request $request)
	{
		$request->setParams($this->requestParts);
	}
	
	protected function expressionMatch($requestExpression, $request)
	{
		$url = $request->getRequestPath();
		
	 	$position = strpos($url, $requestExpression);
	 	if ($position === 1 || $position === 0)
	 	{
	 		$newUri = substr($url, $position + strlen($requestExpression));	
	 		$request->setRequestPath($newUri);
	 		return true;
	 	}	
		 return false;
	}
}