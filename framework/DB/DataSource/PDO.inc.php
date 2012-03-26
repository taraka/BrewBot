<?php
/**
 * PDO Datasource object
 *
 * @package LSF
 * @author Tom
 * $Id$
 */
class LSF_DB_DataSource_PDO extends LSF_DB_DataSource
{
	private
		$_db;
		
	protected function __construct($dsn)
	{
		$dsn = parse_url($dsn);
		
		$settings = 'mysql:host=' . $dsn['host'];
		
		if (!empty($dsn['port'])) {
			$settings .= ';port=' . $dsn['port'];
		}
		
		if (!empty($dsn['path'])) {
			$settings .= ';dbname=' . substr($dsn['path'], 1);
		}
		
		try {
			$db = new PDO($settings, $dsn['user'], $dsn['pass'], array(
				PDO::MYSQL_ATTR_INIT_COMMAND		=> "SET NAMES 'UTF8'",
				PDO::MYSQL_ATTR_USE_BUFFERED_QUERY 	=> true,
				PDO::ATTR_ERRMODE					=> PDO::ERRMODE_EXCEPTION,
			));
		}
		catch (PDOException $e) {
			throw new LSF_Exception_Database($e->getMessage());
		}
		
		$this->_db = $db;
		
		parent::__construct();
	}
	
	/**
	 * Starts a database transaction
	 *
	 * @throws LSF_Exception_TransactionCountReached
	 */
	public function beginTransaction()
	{
		return $this->_db->beginTransaction();
	}
	
	/**
	 * Commit the current transaction
	 *
	 * @return void
	 */
	public function commit()
	{
		return $this->_db->commit();
	}
	
	/**
	 * Roll back the current transaction.
	 *
	 * @return void
	 */
	public function rollback()
	{
		return $this->_db->rollBack();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see DB/LSF_DB_DataSource#isError()
	 */
	public function isError()
	{
		return ($this->_db->errorCode() != '0000') && ($this->_db->errorCode() != '');
	}
	
	/**
	 * Escape a string for use in a query
	 */
	public function escape($value)
	{
		return substr($this->_db->quote($value), 1, -1);
	}
	
	/**
	 * Defers calls to the PDO object
	 *
	 * @param string $func
	 * @param array $args
	 * @throws LSF_Exception_Database
	 */
	public function __call($func, $args)
	{
		$result = null;
		try {
			if (!is_null($this->_db)) {
				$result = call_user_func_array(array($this->_db, $func), $args);
			}
			else {
				throw new LSF_Exception_Database('No useable database found');
			}
		}
		catch (PDOException $e) {
			throw new LSF_Exception_Database($e->getMessage());
		}
		
		if ($result instanceof PDOStatement) {
			$result = new LSF_DB_Statement_PDO($result, $this->_db);
		}
		
		return $result;
	}
}
