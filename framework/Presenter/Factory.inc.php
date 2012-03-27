<?php

/**
 *
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id: Factory.inc.php 55 2010-04-06 12:43:43Z tom $
 */

abstract class LSF_Presenter_Factory
{
	/**
	 * Factory Method to create a new presenter object
	 * 
	 * @param string $presenterName
	 * @param string $controllerName
	 */
	public static function get($presenterName, $controllerName=null)
	{
		if (!$presenterName) {
			$presenterName = 'Smarty';
		}
		
		$presenter = 'LSF_Presenter_' . ucfirst(strtolower($presenterName));
		
		if (class_exists($presenter)) {
			return new $presenter($controllerName);
		}
	}
}
