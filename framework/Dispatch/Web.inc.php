<?php

/**
 *
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id: Bootstrap.inc.php 55 2010-04-06 12:43:43Z tom $
 */

class LSF_Dispatch_Web implements LSF_IDispatch
{
	private
		$_request,
		$_router,
		$_controller;
	
	public function __construct()
	{
		$this->_request = new LSF_Request();
		$this->_router = new LSF_Router();
	}
	
	/**
	 * Start the web interface for the application
	 */
	public function run()
	{
		/**
		 * Route the request to the correct controller
		 */
		$this->_router->route($this->_request);
		
		LSF_Locale::setLocale($this->_request->getLocale());
		$this->_controller = LSF_Controller_Factory::get($this->_request);
		
		try {
			$this->_controller->start();
		}
		catch (LSF_Exception_ControllerNotFound $e)
		{
			/**
			 * Error 404 occurred see if there is a user defined 404 handeler 
			 */
			try {
				$this->_controller = LSF_Controller_Factory::getError('404', $this->_request);
				$this->_controller->start();
			}
			catch (LSF_Exception_ControllerNotFound $e1) {
				throw($e);
			}
		}
		catch (LSF_Exception_Permission $e)
		{
			/**
			 * Permission error occurred see if there is a user defined 403 handeler 
			 */
			try {
				$this->_controller = LSF_Controller_Factory::getError('403', $this->_request);
				$this->_controller->start();
			}
			catch (LSF_Exception_ControllerNotFound $e1) {
				throw($e);
			}
		}

		/**
		 * Start the presenter
		 */
		$presenter = LSF_Presenter_Factory::get($this->_controller->getPresenterName(), get_class($this->_controller));
		
		if ($presenter)
		{
			$presenter->setActionName($this->_request->getAction());
			$presenter->setView($this->_controller->getView());
			
			try
			{
				if (LSF_Config::get('minify_output'))
				{
					$output = $presenter->fetch();
					$output = preg_replace('/\s\s+/', ' ', $output);
					$output = preg_replace("/<!--.*-->/Uis", "", $output);
					echo $output;
				}
				else {
					$presenter->display();
				}
				
				if (LSF_Config::get('show_debug')) {
					$debug = new LSF_Debug();
					$debug->flush(); 
				}
			}
			catch (LSF_Exception_ControllerNotFound $e) {
				throw new LSF_Exception_ControllerNotFound('Unable to find template file or 404 controller');
			}
		}
		else {
			echo 'Invalid Presenter';
		}
		
	}
	
	/**
	 * Returns the Router class for this request
	 * 
	 * @return LSF_Router
	 */
	public function getRouter()
	{
		return $this->_router;
	}
	
	/**
	 * Get the request object for the request
	 * 
	 * @return LSF_Request
	 */
	public function getRequest()
	{
		return $this->_request;
	}
}
