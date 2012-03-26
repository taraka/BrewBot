<?php

/**
 *
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id: Controller.inc.php 38 2010-02-01 16:52:35Z tom $
 */

abstract class LSF_Console_Controller
{
	protected
		$controllerOptions = array(),
		$actionOptions = array(),
		$response;
	
	private
		$_action = false;
	
	public function __construct()
	{
		$this->response = new LSF_Utils_Console_Response();
	}
	
	/**
	 * Registers the controller option and and starts the action
	 *
	 * @return void
	 */
	public function start()
	{
		$this->controllerOptions = $this->getNextOptions();
	
		$method = $this->getCurrentAction() . 'Action';
		
		$this->actionOptions = $this->getNextOptions();
		
		if (method_exists($this, $method)) {
			$this->$method();
		}
		elseif(method_exists($this, 'indexAction')) {
			$this->indexAction();
		}
	}
	
	/**
	 * Retreves the next set of options from the LSF_Dispatch_Console::$arguments
	 *
	 * @return void
	 */
	private function getNextOptions()
	{
		$returnArray = array();
		
		foreach (LSF_Dispatch_Console::$arguments as $key => $argument)
		{
			if (!is_numeric($key))
			{
				$returnArray[] = LSF_Dispatch_Console::$arguments[$key];
				unset(LSF_Dispatch_Console::$arguments[$key]);
			}
			else {
				break;
			}
		}
		
		return $returnArray;
	}
	
	/**
	 * Return the current action
	 *
	 * @return string $action
	 */
	protected function getCurrentAction()
	{
		if (!$this->_action)
		{
			$this->_action = !empty(LSF_Dispatch_Console::$arguments[0]) ? LSF_Dispatch_Console::$arguments[0] : false;
			array_shift(LSF_Dispatch_Console::$arguments);
		}
		
		return $this->_action;
	}
	
	/**
	 * Return the current action
	 *
	 * @return string $controller
	 */
	protected function getControllerName()
	{
		return strtolower(str_replace('Console_Controller_', '', get_class($this)));
	}
	
	public function __destruct()
	{
		$this->response->flushContent();
	}
}
