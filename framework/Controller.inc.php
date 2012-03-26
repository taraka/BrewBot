<?php

/**
 * Base class for all controllers to extend
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

abstract class LSF_Controller
{
	private
		$_presenterName = false,
		$_request;
	
	protected
		$view;
	
	public function __construct()
	{
		$this->_presenterName = LSF_Config::get('default_presenter');
	}
		
	/**
	 * Calculates the current action and starts the controller
	 *
	 * @return bool
	 */
	public function start()
	{
		$method = $this->getActionMethodName();
		
		$this->setupView();
		
		if (method_exists($this, $method))
		{
			try {
				$this->_beforeAction();
				$result = $this->$method();
				$this->_afterAction($result);
			}
			catch(LSF_Exception_ResourceNotFound $e) {
				throw new LSF_Exception_ControllerNotFound($e->getMessage());
			}
		}
		else {
			throw new LSF_Exception_ControllerNotFound('Action Not Found: ' . $this->getRequest()->getAction());
		}
		
		return true;
	}
	
	/**
	 * Setup the view object
	 *
	 * @return void
	 */
	protected function setupView()
	{
		if (empty($this->view)) {
			$this->view = LSF_Config::get('view_mode') == 'basic' ? new LSF_View_Basic() : new LSF_View_Node();
		}
	}
	
	/**
	 * Returns the name of this controller
	 *
	 * @return string
	 */
	protected function getControllerName()
	{
		return strtolower(str_replace('Controller_', '', get_class($this)));
	}
	
	/**
	 * Returns the request object
	 *
	 * @return LSF_Request
	 */
	protected function getRequest()
	{
		return $this->_request;
	}
	
	/**
	 * Set the name of the presenter for use by this controller
	 *
	 * @param string $name
	 * @return void
	 */
	protected function setPresenterName($name)
	{
		$this->_presenterName = $name;
	}
	
	/**
	 * Returns the name of this controller's presenter
	 *
	 * @return string
	 */
	public function getPresenterName()
	{
		return $this->_presenterName;
	}
	
	/**
	 * Returns the view object
	 *
	 * @return object
	 */
	public function getView()
	{
		return $this->view;
	}
	
	/**
	 * Set the array of request parts in this controller
	 *
	 * @param array $requestParts
	 * @return void
	 */
	public function setRequest(LSF_Request $request)
	{
		$this->_request = $request;

	}
	
	/**
	 * Returns an array of inputs from get, post, etc
	 *
	 * @param string $method
	 * @return array
	 * @deprecated
	 */
	public function getInputs($method=null)
	{
		switch ($method)
		{
			case 'get':
				return $this->getRequest()->getGetVars();
			
			case 'post':
				return $this->getRequest()->getPostVars();
			
			default:
				return array_merge($this->getRequest()->getGetVars(), $this->getRequest()->getPostVars());
		}
	}
	
	/**
	 * Redirects the user's browser to a given controller and action, then exits.
	 *
	 * @param string $controller
	 * @param string $action
	 * @param array $params
	 * @param array $query
	 * @return void
	 */
	protected function redirect($controller=null, $action=null, $params=array(), $query=null)
	{
		$location = '/';
		
		if (!is_array($params))
		{
			$params = array($params);
		}
		
		if (LSF_Locale::getLocale() && LSF_Locale::getLocale() != LSF_Locale::DEFAULT_LOCALE) {
			$location .= LSF_Locale::getLocale() . '/';
		}
		
		if (is_string($controller))
		{
			$location .= $controller . '/';
			
			if (is_string($action)) {
				$location .= $action . '/';
			}
			if (is_array($params))
			{
				foreach ($params as $param)
				{
					$location .= $param . '/';
				}
			}
			
			$location .= $query;
		}
		
		header('Location: ' . $location);
		exit;
	}
	
	/**
	 * Redirect to a specified url
	 * @param string $location
	 */
	protected function redirectUrl($location)
	{
		header('Location: ' . $location);
		exit;
	}
	
	/**
	 * Returns the name of the method that will be called
	 * 
	 * @return string
	 */
	protected function getActionMethodName()
	{
		return $this->getRequest()->getAction() . 'Action';
	}
	
	/**
	 * Blank method can be overriden by application controllers and is called before the action method.
	 */
	protected function _beforeAction() {}
	
	/**
	 * Blank method can be overriden by application controllers and is called after the action method.
	 */
	protected function _afterAction() {}
	
	public function __destruct() {}
}
