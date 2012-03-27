<?php

/**
 *
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Cache
{
	private static $_engines = array (
		'LSF_Cache_Engine_APC',
		'LSF_Cache_Engine_Memcache',
	);
	
	private $_cacheEngine;
		
	public function __construct()
	{
		$this->setupEngine();
	}
	
	/**
	 * Check which cache engines are available and create them
	 *
	 * @return void
	 */
	private function setupEngine()
	{
		if (!LSF_Config::get('disable_cache'))
		{
			foreach (self::$_engines as $engineName)
			{
				$engine = new $engineName();
				
				if ($engine->isAvailable()) {
					$this->_cacheEngine = $engine;
				}
			}
		}
	}
	
	/**
	 * Passes cache method calls off to the current engine
	 *
	 * @param string $function
	 * @param mixed $args
	 * @return mixed
	 */
	public function __call($function, $args)
	{
		if (!$this->_cacheEngine) {
			return null;
		}
		
		return call_user_func_array(array($this->_cacheEngine, $function), $args);
	}
}
