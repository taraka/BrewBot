<?php

/**
 * Wrapper class to defer calls to methods on MDB2_Statement_Common objects and detect errors
 * 
 * @package LSF
 * @author tom
 * $ID$
 */
class LSF_DB_Statement_MDB2 extends LSF_DB_Statement
{
	private
		$_statement;
		
	public function __construct(MDB2_Statement_Common $statement)
	{
		$this->_statement = $statement;
	}

	/**
	 * 
	 * Defer method calls and examine result for pear errors
	 * @param string $func
	 * @param array $args
	 * @throws LSF_Exception_Database
	 */
	public function __call($func, $args)
	{
		$result = null;
		if (!is_null($this->_statement)) {
			$result = call_user_func_array(array($this->_statement, $func), $args);
		}
		
		if (PEAR::isError($result)) {
			throw new LSF_Exception_Database($result->getUserInfo());
		}
		
		return $result;
	}
}