<?php

/**
 * Abstract datasource object defines interface
 *
 * @package LSF
 * @author Tom
 * $Id$
 */
abstract class LSF_DB_DataSource
{	
	protected function __construct() {}
	
	/**
	 * Starts a database transaction
	 *
	 * @throws LSF_Exception_TransactionCountReached
	 */
	abstract public function beginTransaction();
	
	/**
	 * Commit the current transaction
	 *
	 * @return void
	 */
	abstract public function commit();
	
	/**
	 * Roll back the current transaction.
	 *
	 * @return void
	 */
	abstract public function rollback();
	
	/**
	 * Test the connection for errors
	 */
	abstract public function isError();
	
	/**
	 * Prepares and executes a query using the given string and params
	 *
	 * @param string $queryString
	 * @param mixed $queryParams
	 * @return result object
	 */
	public function prepareAndExecute($queryString, $queryParams=null)
	{
		if (!$queryParams) {
			$queryParams = null;
		}
		
		return $this->prepare($queryString)->execute($queryParams);
	}
	
	public static function Factory($dsn)
	{
		if (LSF_Config::get('db_provider') == 'mdb2') {
			return new LSF_DB_DataSource_MDB2($dsn);
		}
		else {
			return new LSF_DB_DataSource_PDO($dsn);
		}
	}
}
