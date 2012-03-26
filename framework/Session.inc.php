<?php

/**
 * Singleton class for handling session variables
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Session
{
	private static
		$_session;
		
	private 
		$_started = false;
	
	private function __construct()
	{
		if (isset($_COOKIE['_lsf'])) {
			$this->startSession();
		}
	}
	
	/**
	 * Returns an instance of the current session object
	 *
	 * @return LSF_Session object
	 */
	public static function GetSession($sessionId=false)
	{
		if ($sessionId) {
			session_id($sessionId);
		}
		
		if (!self::$_session) {
			self::$_session = new LSF_Session();
		}
		
		if ($sessionId) {
			self::$_session->startSession();
		}
		
		return self::$_session;
	}
	
	/**
	 * Start the session
	 */
	public function startSession()
	{
		if (!$this->_started)
		{
			session_name('_lsf');
			session_start();
			$this->_started = true;
		}
	}
	
	/**
	 * Clears all session vars
	 *
	 * @return void
	 */
	public function reset()
	{
		if ($this->_started)
		{
			foreach ($_SESSION as $key => $value) {
				$this->__unset($key);
			}
		}
	}
	
	/**
	 * Destroys the current session Vars
	 */
	public function destroySession()
	{
		if ($this->_started) {
			session_destroy();
		}
		$this->_started = false;
		if (!headers_sent()) {
			setcookie('_lsf', false, time() - 3600);
		}
	}
	
	/**
	 * Returns a session value
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
	}
	
	/**
	 * Set a session value
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function __set($name, $value)
	{
		$this->startSession();
		$_SESSION[$name] = $value;
	}
	
	/**
	 * Clear a session value
	 *
	 * @param string $name
	 * @return void
	 */
	public function __unset($name)
	{
		$this->startSession();
		unset($_SESSION[$name]);
	}
	
	/**
	 * Checks existence of session var
	 *
	 * @param string $name
	 * @return void
	 */
	public function __isset($name)
	{
		return isset($_SESSION[$name]);
	}
	
	public function __destruct()
	{
		if (empty($_SESSION) && $this->_started) {
			$this->destroySession();
		}
	}
}
