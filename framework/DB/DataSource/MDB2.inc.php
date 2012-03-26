<?php

include_once 'MDB2.php';

/**
 * MDB2 Datasource object
 *
 * @package LSF
 * @author Tom
 * $Id$
 */
class LSF_DB_DataSource_MDB2 extends LSF_DB_DataSource
{
	private
		$_db;
		
	protected function __construct($dsn)
	{
		$this->_db =& MDB2::factory($dsn);
		$this->_db->setFetchMode(MDB2_FETCHMODE_ASSOC);
		parent::__construct();
	}
	
	/**
	 * Starts a database transaction
	 *
	 * @throws LSF_Exception_TransactionCountReached
	 */
	public function beginTransaction()
	{
		if ($this->_db->supports('transactions') && !$this->_db->in_transaction) {
			return $this->_db->beginTransaction();
		}
		else {
			throw new LSF_Exception_TransactionCountReached();
		}
	}
	
	/**
	 * Commit the current transaction
	 *
	 * @return void
	 */
	public function commit()
	{
		if ($this->_db->supports('transactions') && $this->_db->in_transaction) {
			$this->_db->commit();
		}
	}
	
	/**
	 * Roll back the current transaction.
	 *
	 * @return void
	 */
	public function rollback()
	{
		if ($this->_db->supports('transactions') && $this->_db->in_transaction) {
			return $this->_db->rollback();
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see DB/LSF_DB_DataSource#isError()
	 */
	public function isError()
	{
		return PEAR::isError($this->_db);
	}
	
	/**
	 * Defers calls to the MDB2 object
	 *
	 * @param string $func
	 * @param array $args
	 * @throws LSF_Exception_Database
	 */
	public function __call($func, $args)
	{
		$result = null;
		
		if (!is_null($this->_db)) {
			$result = call_user_func_array(array($this->_db, $func), $args);
		}
		else {
			throw new LSF_Exception_Database('No useable database found');
		}
		
		if (PEAR::isError($result)) {
			throw new LSF_Exception_Database("
Message: {$result->getMessage()}
UserInfo: \n{$result->getUserInfo()}");
		}
		
		if ($result instanceof MDB2_Statement_Common) {
			$result = new LSF_DB_Statement_MDB2($result);
		}
		
		return $result;
	}
}
