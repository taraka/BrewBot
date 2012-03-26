<?php

/**
 * Wrapper class to defer calls to methods on MDB2_Statement_Common objects and detect errors
 * 
 * @package LSF
 * @author tom
 * $ID$
 */
class LSF_DB_Statement_PDO extends LSF_DB_Statement
{
	private
		$_statement,
		$_dataSource,
		$_numRows;
		
	public function __construct(PDOStatement $statement, PDO $pdo)
	{
		$this->_statement = $statement;
		$this->_dataSource = $pdo;
	}

	/**
	 * Returns the number of rows returned from the previous select statement
	 *
	 * @return int
	 */
	public function numRows()
	{
		if ($this->_numRows === null) {
			$this->_numRows = (int)$this->_dataSource->query('SELECT FOUND_ROWS()')->fetchColumn();
		}
		
		return $this->_numRows;
	}
	
	public function free() {}
	
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
		try {
			if (!is_null($this->_statement)) {
				switch ($func)
				{
					case 'execute':
						if (isset($args[0]) && !is_array($args[0])) {
							$args[0] = array($args[0]);
						}
						break;
						
					case 'fetchRow':
						$func = 'fetch';
						$args = array(PDO::FETCH_ASSOC);
						break;
				}
				
				$result = call_user_func_array(array($this->_statement, $func), $args);
			}
		}
		catch (PDOException $e) {
			throw new LSF_Exception_Database($e->getMessage());
		}
		
		return $func == 'fetch' || $func == 'fetchAll' ? $result : $this;
	}
}