<?php

/**
 * 
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

interface LSF_Cache_IEngine
{
/**
	 * Add a value to the cache, overwriting any existing value with the same key
	 * @param string $key
	 * @param mixed $var
	 * @param int $ttl
	 * @return bool
	 */
	public function set($key, $var, $ttl=0);
	
	/**
	 * Add a value to the cache, unless a value with the same key already exists
	 * @param string $key
	 * @param mixed $var
	 * @param int $ttl
	 * @return bool
	 */
	public function add($key, $var, $ttl=0);
	
	/**
	 * Increment the value stored in $key by $increment
	 * @param string $key
	 * @param int $increment
	 */
	public function increment($key, $increment=1);
	
	/**
	 * get the value of the object stored in $key
	 * @param string $key
	 * @return mixed
	 */
	public function get($key);
	
	/**
	 * Checks is a key exists in the cache
	 * @param string $key
	 * @return bool
	 */
	public function exists($key);
	
	/**
	 * Return an array of information about the cache
	 * @return array
	 */
	public function getStats();
	
	/**
	 * Clear the entry at $key
	 * @param $key $key
	 */
	public function clear($key);
	
	/**
	 * Clear the current namespace
	 */
	public function clearAll();
	
	/**
	 * Clear the entire server cache
	 */
	public function clearFullCache();
	
	/**
	 * Is this object avaliable as an engine
	 * 
	 * @return bool
	 */
	public function isAvailable();
}
