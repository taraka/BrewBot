<?php

/**
 *
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Cache_Engine_APC implements LSF_Cache_IEngine
{
	private
		$_cacheKey;

	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#set()
	 */
	public function set($key, $var, $ttl=43200)
	{
		$key = $this->makeKey($key);
		return apc_store($key, $var, $ttl);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#add()
	 */
	public function add($key, $var, $ttl=43200)
	{
		$key = $this->makeKey($key);
		return apc_add($key, $var, $ttl);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#increment()
	 */
	public function increment($key, $increment=1)
	{
		$key = $this->makeKey($key);
		$value = $this->get($key);
		
		if (is_numeric($value) && is_numeric($increment)) {
			return $this->set($key, $value+$increment);
		}
		
		return null;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#get()
	 */
	public function get($key)
	{
		$key = $this->makeKey($key);
		return apc_fetch($key);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#exists()
	 */
	public function exists($key)
	{
		$key = $this->makeKey($key);
		return (bool)apc_fetch($key);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#getStats()
	 */
	public function getStats()
	{
		$info = apc_sma_info();
		
		return array(
			'Engine'				=> 'APC',
			'Total memory' 			=> round(($info['seg_size'] / 1024) / 1024),
			'Available memory'		=> round(($info['avail_mem'] / 1024) / 1024)
		);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#clear()
	 */
	public function clear($key)
	{
		$key = $this->makeKey($key);
		return apc_delete($key);
	}

	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#clearAll()
	 */
	public function clearAll()
	{
		return apc_clear_cache('user');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#clearFullCache()
	 */
	public function clearFullCache()
	{
		$return = $this->clearAll();
		return apc_clear_cache('user') && $return;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#isAvailable()
	 */
	public function isAvailable()
	{
		return function_exists('apc_add');
	}
	
	/**
	 * Makes a key unique to this site.
	 * @param string $key
	 * 
	 * @return string $key
	 */
	private function makeKey($key)
	{
		if (!$this->_cacheKey)
		{
			$cacheKey = LSF_Config::get('cache_key');
			$this->_cacheKey = $cacheKey ? $cacheKey : md5_file(LSF_Application::getApplicationPath() . '/Config/application.ini');
		}
		
		return $this->_cacheKey . '-' . $key;
	}
}
