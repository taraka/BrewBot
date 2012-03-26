<?php

/**
 *
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Cache_Engine_Memcache implements LSF_Cache_IEngine
{
	private
		$_cacheKey;

	private static
		$_cacheObject,
		$_connectionAttempted = false;

	public function __construct()
	{
		if (class_exists('Memcache') && !is_object(self::$_cacheObject) && !self::$_connectionAttempted)
		{
			self::$_connectionAttempted = true;
			
			$memcache = new Memcache();
			
			if ($memcache->connect('localhost')) {
				self::$_cacheObject = $memcache;
			}
			else {
				throw new LSF_Exception_Cache('Unable to connect to local memcache daemon');
			}
		}
	}
		
	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#set()
	 */
	public function set($key, $var, $ttl=43200)
	{
		$key = $this->makeKey($key);
		return self::$_cacheObject->set($key, $var, false, $ttl);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#add()
	 */
	public function add($key, $var, $ttl=43200)
	{
		$key = $this->makeKey($key);
		return self::$_cacheObject->add($key, $var, false, $ttl);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#increment()
	 */
	public function increment($key, $increment=1)
	{
		$key = $this->makeKey($key);
		return self::$_cacheObject->increment($key, $increment);;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#get()
	 */
	public function get($key)
	{
		$key = $this->makeKey($key);
		return self::$_cacheObject->get($key);
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
		return array(
			'Engine'				=> 'Memcache',
		);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#clear()
	 */
	public function clear($key)
	{
		$key = $this->makeKey($key);
		if (!empty($key)) {
			return self::$_cacheObject->delete($key);
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#clearAll()
	 */
	public function clearAll()
	{
		$return = $this->clearAll();
		return self::$_cacheObject->flush() && $return;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#clearFullCache()
	 */
	public function clearFullCache()
	{
		return $this->clearAll();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Cache/LSF_Cache_IEngine#isAvailable()
	 */
	public function isAvailable()
	{
		return is_object(self::$_cacheObject);
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
