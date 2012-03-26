<?php

/**
 *
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

abstract class LSF_Controller_Factory
{
	/**
	 * Creates a controller
	 *
	 * @param array $requestParts
	 */
	public static function get(LSF_Request $request)
	{
		try {
			$className = 'Controller_' . $request->getController();
			$newClassName = preg_replace('/[^\w]/', '', $className);
			
			if ($className != $newClassName) {
				throw new LSF_Exception_ClassNotFound($className);
			}
			
			$reflection = new ReflectionClass($className);
			if ($reflection->isAbstract()) {
				throw new LSF_Exception_ClassNotFound();
			}

			$controller = new $className();
		}
		catch (LSF_Exception_ClassNotFound $e)
		{
			try {
				$controller = self::getError(404, $request);
			}
			catch (LSF_Exception_ControllerNotFound $e1) {
				throw new LSF_Exception_ControllerNotFound($request->getController());
			}
		}

		$controller->setRequest($request);
		
		return $controller;
	}
	
	/**
	 * Returns an error controller
	 *
	 * @param string $error
	 */
	public static function getError($error=404, $request=null)
	{
		$request = $request ? $request : new LSF_Request();
		
		try {
			$className = 'Controller_Error_' . $error;
			$controller = new $className();
			$request->setAction('index');
			if (method_exists($controller, 'setRequest')) {
				$controller->setRequest($request);
			}
			else {
				throw new LSF_Exception_ControllerNotFound($request->getController());
			}
		}
		catch (LSF_Exception_ClassNotFound $e)
		{
			throw new LSF_Exception_ControllerNotFound($request->getController());
		}
		
		return $controller;
	}
	
	/**
	 * Returns a console controller
	 *
	 * @param string $controller
	 */
	public static function getConsoleController($controller)
	{
		$controller = empty($controller) ? 'Default' : ucfirst(strtolower($controller));
		
		$className = 'LSF_Console_Controller_' . $controller;
		
		try {
			$controller = new $className(true);
		}
		catch (LSF_Exception_ClassNotFound $e) {
			$controller = new LSF_Console_Controller_Default(true);
		}
		
		return $controller;
	}
	
	/**
	 * Returns a applications console controller
	 *
	 * @param string $controller
	 */
	public static function getConsoleAppController($controller)
	{
		$controller = empty($controller) ? 'Default' : ucfirst(strtolower($controller));
		
		$className = 'Console_Controller_' . $controller;
		
		try {
			$controller = new $className(true);
		}
		catch (LSF_Exception_ClassNotFound $e) {
			$controller = new Console_Controller_Default(true);
		}
		
		return $controller;
	}
}
