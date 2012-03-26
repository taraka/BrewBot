<?php

/**
 * Add a fixed route
 * 
 * @package LSF
 * @author tom
 */
class LSF_Router_Route_Static extends LSF_Router_Route_Default 
{
	private 
		$_requestExpression,
		$_locale,
		$_controller,
		$_action,
		$_params;
		
	public function __construct($requestExpression, $locale=null, $controller=null, $action=null, $params=null)
	{
		$this->_requestExpression = $requestExpression;
		$this->_locale = $locale;
		$this->_controller = $controller;
		$this->_action = $action;
		$this->_params = $params;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Router/Route/LSF_Router_Route_Default#routeAvaliable()
	 */
	public function routeAvaliable(LSF_Request $request)
	{
		return $this->expressionMatch($this->_requestExpression, $request);
	}
	
	/**
	 * Try to get a local string
	 * @param LSF_Request $request
	 */
	protected function processLocale(LSF_Request $request)
	{
		if ($this->_locale) {
			$request->setLocale($this->_locale);
		}
		else  {
			parent::processLocale($request);
		}
	}
	
	/**
	 * Try to get a controller name
	 * @param LSF_Request $request
	 */
	protected function processController(LSF_Request $request)
	{
		if ($this->_controller) {
			$request->setController($this->_controller);
		}
	}
	
	/**
	 * Try to get an action name
	 * @param LSF_Request $request
	 */
	protected function processAction(LSF_Request $request)
	{
		if ($this->_action) {
			$request->setAction($this->_action);
		}
	}

	/**
	 * Try set params
	 * @param LSF_Request $request
	 */
	protected function processParams(LSF_Request $request)
	{
		if (is_array($this->_params)) {
			$request->setParams($this->_params);
		}
		else {
			return parent::processParams($request);
		}
	}
}