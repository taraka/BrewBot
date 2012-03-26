<?php

/**
 * Loads the configuration from the application.ini file
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

abstract class LSF_Config
{
	private static
		$_settings = array();
	
	/**
	 * Returns a configuration setting
	 *
	 * @param string $setting
	 * @return mixed
	 */
	public static function get($setting)
	{
		return isset(self::$_settings[$setting]) ? self::$_settings[$setting] : false;
	}
	
	/**
	 * Returns an array of all settings
	 * 
	 * @return array $settings
	 */
	public static function getAll()
	{
		return self::$_settings;
	}
	
	/**
	 * Set a configuration setting
	 *
	 * @param string $setting
	 * @param mixed $value
	 * @return void
	 */
	public static function set($setting, $value)
	{
		self::$_settings[$setting] = $value;
	}
	
	/**
	 * Setup initial configuration from application ini file
	 *
	 * @return void
	 */
	public static function setup()
	{
		$configPath = LSF_Application::getApplicationPath() . '/Config';
		$iniPath = $configPath . '/application.ini';
		
		if (file_exists($iniPath))
		{
			$env = LSF_Application::getEnvironment();
			self::$_settings = parse_ini_file($iniPath, true);
			
			if ($env)
			{
				if (isset(self::$_settings[$env]) && is_array(self::$_settings[$env]))
				{
					foreach (self::$_settings[$env] as $var => $value)
					{
						self::$_settings[$var] = $value;
					}
				}
			}
			
			foreach (self::$_settings as $var => $value)
			{
				if (is_array($value)) {
					unset (self::$_settings[$var]);
				}
			}
		}
	}
}
