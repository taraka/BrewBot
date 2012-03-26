<?php

/**
 * ActiveRecord base sets up the data source and table, provides a facility to find rows
 *
 * @package LSF
 * @subpackage DB
 * @version $Id$
 * @copyright 2008-2011
 */

abstract class LSF_DB_ActiveRecord_Base
{
	private
		$_tableName;
	
	/**
	 * Create a new active record base
	 * 
	 * @param string $tableName
	 * @throws LSF_Exception_Database
	 */
	public function __construct($tableName)
	{
		if (!$tableName || !is_string($tableName)) {
			throw new LSF_Exception_Database('Table name not defined');
		}
		
		$this->_tableName = $this->getDataSource()->escape((string)$tableName);
	}
	
	/**
	 * Finds and returns database rows based on the provided criteria
	 *
	 * @param array $conditions
	 * @return array
	 */
	protected final function find($conditions=array(), $options=array())
	{
		$sql = 'SELECT * FROM `' . $this->_tableName . '`';
		$params = array();
		
		if (is_array($conditions) && !empty($conditions))
		{
			$sql .= ' WHERE `';
			
			foreach ($conditions as $key => $value)
			{
				if (is_string($key) && (is_string($value) || is_numeric($value) || is_bool($value) || is_null($value)))
				{
					$sql .= $this->getDataSource()->escape($key) . '`=? AND `';
					$params[] = $value;
				}
			}
			
			$sql = strlen($sql) > 6 ? substr($sql, 0, -6) : '';
		}
		
		if (is_array($options) && !empty($options))
		{
			$orderBy = $order = $limit = false;
			
			foreach ($options as $key => $value)
			{
				if (is_string($value) || is_numeric($value))
				{
					switch ($key)
					{
						case 'orderBy':
							if (!$orderBy) {
								$sql = $orderBy = $sql . ' ORDER BY ' . $this->getDataSource()->escape($value);
							}
							break;
							
						case 'order':
							if (!$order) {
								$sql = $order = $sql . (strtolower($value) == 'desc' ? ' DESC' : ' ASC');
							}
							break;
							
						case 'limit':
							if (!$limit && (is_string($value) || is_numeric($value))) {
								$sql = $limit = $sql . ' LIMIT ' . $value;
							}
							break;
					}
				}
			}
		}
		
		return $this->getDataSource()->prepareAndExecute($sql, $params);
	}
		
	/**
	 * Returns a data source object
	 *
	 * @return LSF_DB_DataSource
	 */
	protected function getDataSource()
	{
		return LSF_DB::DataSource();
	}
	
	/**
	 * Returns the current table name
	 *
	 * @return string
	 */
	protected function getTableName()
	{
		return $this->_tableName;
	}
	
	public function __destruct() {}
}
