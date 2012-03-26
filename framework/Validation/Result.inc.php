<?php

/**
 *
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Validation_Result
{
	private $_errors = array();

	public function __construct() {}
	
	/**
	 * Register an error with this object
	 * @param string $key
	 * @param mixed $error
	 * @return bool 
	 */
	public function addError($key, $error)
	{
		if(is_string($key) || is_int($key)) {
			$this->_errors[$key] = $error;
			return true;
		}
		return false;
	}
	
	/**
	 * Returns an array of all the errors indexed by the keys
	 * @return array $errors
	 */
	public function getErrors()
	{
		return $this->_errors;
	}
	
	/**
	 * Return the current number of errors
	 * 
	 * @return int $count
	 */
	public function getNumberOfErrors()
	{
		return count($this->_errors);
	}
	
	/**
	 * Returns true if there were no errors false otherwise
	 * 
	 * @return bool $success
	 */
	public function success()
	{
		return $this->getNumberOfErrors() == 0;
	}
	
	public function __destruct() {}
}
