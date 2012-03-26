<?php

/**
 * ActiveRecord model handles loading and writing to an individual data row
 *
 * @package LSF
 * @subpackage Data
 * @version $Id$
 * @copyright 2008-2011
 */

abstract class LSF_DB_ActiveRecord_Model extends LSF_DB_ActiveRecord_Base
{
	private
		$_primaryKey,
		$_primaryKeyIsAutoInc,
		$_columns = array(),
		$_id = 0,
		$_loaded = false,
		$_validation;
		
	private static
		$_tableSchema = array();
	
	/**
	 * Create a new record model
	 *
	 * @param string $table
	 */
	public function __construct($table)
	{
		parent::__construct($table);
		
		$tableName 		= $this->getTableName();
		
		if (!isset(self::$_tableSchema[$tableName]))
		{
			self::$_tableSchema[$tableName] = array(
				'columns' 				=> array(),
				'primaryKeys'			=> array(),
				'primaryKeyIsAutoInc'	=> false,
			);
			
			$result = $this->getDataSource()->query('SHOW COLUMNS FROM `' . $tableName . '`');
			
			while ($row = $result->fetchRow())
			{
				self::$_tableSchema[$tableName]['columns'][(string)$row['Field']] = null;
				
				if ($row['Key'] == 'PRI')
				{
					self::$_tableSchema[$tableName]['primaryKeys'][] = (string)$row['Field'];
					self::$_tableSchema[$tableName]['primaryKeyIsAutoInc'] = ($row['Extra'] == 'auto_increment');
				}
			}
			
			if (!count(self::$_tableSchema[$tableName]['columns'])) {
				throw new LSF_Exception_Database('No Columns found for table');
			}
		}
		
		$this->setTableColumns(self::$_tableSchema[$tableName]['columns']);
		$this->setPrimaryKey(self::$_tableSchema[$tableName]['primaryKeys']);
		$this->_primaryKeyIsAutoInc = self::$_tableSchema[$tableName]['primaryKeyIsAutoInc'];
	}
	
	/**
	 * Set the table columns
	 *
	 * @param array $columns
	 * @return void
	 */
	private function setTableColumns(array $columns)
	{
		$this->_columns = $columns;
	}
	
	/**
	 * Set the primary key(s)
	 *
	 * @param mixed $key
	 * @return void
	 */
	protected function setPrimaryKey($key)
	{
		if (!empty($key) && (is_string($key) || is_array($key)))
		{
			if (is_array($key) && count($key) === 1) {
				$key = $key[0];
			}
			
			$this->_primaryKey = $key;
		}
	}
	
	/**
	 * Load a row based on primary keys
	 *
	 * @return bool
	 */
	public function load()
	{
		$params = func_get_args();
		
		if (empty($params) || (count($params) === 1 && empty($params[0])))
		{
			/**
			 * If there's no params provided, but existing values set then we can try to reload based on those
			 */
			$params = array();
			
			if (is_string($this->_primaryKey) && ($id = $this->getId())) {
				$params[] = $id;
			}
			elseif (is_array($this->_primaryKey))
			{
				/**
				 * Grab the values for all fields which are designated as primary keys
				 */
				foreach ($this->_primaryKey as $key) {
					$params[] = $this->_columns[$key];
				}
			}
		}
		
		if (!empty($params))
		{
			$dataSource 	= $this->getDataSource();
			$result = $id 	= null;
			$whereClause	= '';
			
			if (is_string($this->_primaryKey) && count($params) === 1)
			{
				/**
				 * Load based on a single primary key
				 */
				$params = $id = $params[0];
				
				if (!empty($params)) {
					$whereClause = 'WHERE ' . $dataSource->escape($this->_primaryKey) . '=?';
				}
			}
			elseif (is_array($this->_primaryKey) && count($this->_primaryKey) == count($params))
			{
				/**
				 * We've got a few primary keys to select by
				 */
				$params = array_values($params);
				
				foreach ($this->_primaryKey as $key) {
					$whereClause .= $dataSource->escape($key) . '=' . '? AND ';
				}
				
				if (!empty($whereClause)) {
					$whereClause = 'WHERE ' . substr($whereClause, 0, -5);
				}
			}
			
			$queryString = 'SELECT * FROM `' . $this->getTableName() . '` ' . $whereClause;
			$result 	 = $dataSource->prepareAndExecute($queryString, $params);
			
			if ($result && $row = $result->fetchRow())
			{
				$this->setId($id);
				return $this->inject($row);
			}
		}
		
		return false;
	}
	
	/**
	 * Set the contents of this model based on a loaded row from the DB
	 *
	 * @param array $row
	 * @return bool
	 */
	public function inject(array $row)
	{
		$id = $this->getId();
		
		foreach ($row as $key => $value)
		{
			if (!$id && $key == $this->_primaryKey) {
				$id = $this->setId($value);
			}
			
			if (array_key_exists($key, $this->_columns)) {
				$this->_columns[$key] = $value;
			}
		}
		
		$this->_afterLoad();
		return $this->_loaded = true;
	}
	
	/**
	 * Implement this method in derived classes to run operations after a successful load
	 *
	 * @return void
	 */
	protected function _afterLoad() {}
	
	/**
	 * Set the primary key ID
	 *
	 * @param int $id
	 * @return int
	 */
	protected function setId($id)
	{
		if (is_numeric($id))
		{
			if (is_string($this->_primaryKey) && !$this->_primaryKeyIsAutoInc) {
				$this->_columns[$this->_primaryKey] = (int)$id;
			}
			
			return $this->_id = (int)$id;
		}
		
		return null;
	}
	
	/**
	 * Return the record's ID
	 *
	 * @return int
	 */
	public function getId()
	{
		if (is_string($this->_primaryKey))
		{
			if ($this->_primaryKeyIsAutoInc) {
				return (int)$this->_id;
			}
			else {
				return (int)$this->_columns[$this->_primaryKey];
			}

		}
	}
	
	/**
	 * Resets the primary key so a call to save() will insert a new
	 * Only works for tables with a single primary key (auto int) as multiples need to be set manually anyway
	 *
	 * @return void
	 */
	public function resetId()
	{
		$this->setId(0);
	}
	
	/**
	 * Save this record
	 *
	 * @param LSF_Validation_Result $result
	 * @return LSF_Validation_Result
	 */
	public function save(LSF_Validation_Result $result=null)
	{
		$this->_beforeSave();
		
		if (!$result) {
			$result = $this->getValidationObject();
		}
		
		if ($result->success())
		{
			if ($this->recordExists()) {
				$this->update();
			}
			else {
				$this->insert();
			}
			
			$this->_loaded = true;
			$this->_afterSave();
		}
		
		$this->_validation = null; // Reset this so a new validation object is created next save
		return $result;
	}
	
	/**
	 * Returns a validation object for use when saving
	 *
	 * @return LSF_Validation_Result
	 */
	protected final function getValidationObject()
	{
		if (!$this->_validation) {
			$this->_validation = new LSF_Validation_Result();
		}
		
		return $this->_validation;
	}
	
	/**
	 * Implement this method in derived classes to run operations prior to saving
	 *
	 * @return void
	 */
	protected function _beforeSave() {}
	
	/**
	 * Implement this method in derived classes to run operations after saving
	 *
	 * @return void
	 */
	protected function _afterSave() {}
	
	/**
	 * Implement this method in derived classes to run operations after deletion
	 *
	 * @return void
	 */
	protected function _afterDelete() {}
	
	/**
	 * Insert a new record
	 *
	 * @return int new ID
	 */
	protected function insert()
	{
		$dataSource = $this->getDataSource();
		$fields = $placeholders = '';
		
		if (is_string($this->_primaryKey) && $this->_primaryKeyIsAutoInc) {
			unset($this->_columns[$this->_primaryKey]);
		}
		
		foreach ($this->_columns as $key => $value)
		{
			$fields 		.= '`' . $dataSource->escape($key) . '`, ';
			$placeholders 	.= '?, ';
		}
		
		$dataSource->prepareAndExecute('
			INSERT INTO `' . $this->getTableName() . '` (' . substr($fields, 0, -2) . ')
			VALUES (' . substr($placeholders, 0, -2) . ')', array_values($this->_columns));
		
		if ($this->_primaryKeyIsAutoInc) {
			$this->setId($dataSource->lastInsertID());
		}
		
		return $this->getId();
	}
	
	/**
	 * Update an existing row
	 *
	 * @return void
	 */
	protected function update()
	{
		$dataSource = $this->getDataSource();
		
		$set = $whereClause = '';
		$params = array();
		
		$primaryKeys = array();
		
		if (is_array($this->_primaryKey))
		{
			foreach ($this->_primaryKey as $key) {
				$primaryKeys[$key] = $this->_columns[$key];
			}
		}
		else {
			$primaryKeys[$this->_primaryKey] = $this->getId();
		}
		
		foreach ($this->_columns as $field => $value)
		{
			if (!isset($primaryKeys[$field]))
			{
				$set .= '`' . $dataSource->escape($field) . '`=?, ';
				$params[] = $value;
			}
		}
		
		if (empty($params)) {
			// All fields are primary keys so there is nothing to update
			return null;
		}
		
		foreach ($primaryKeys as $key => $value)
		{
			$whereClause .= '`' . $dataSource->escape($key) . '`=? AND ';
			$params[] = $value;
		}
		
		$dataSource->prepareAndExecute('
			UPDATE `' . $this->getTableName() . '` SET ' . substr($set, 0, -2) . '
			WHERE ' . substr($whereClause, 0, -5), $params);
	}

	/**
	 * Delete an existing row
	 *
	 * @return void
	 */
	public function delete()
	{
		$dataSource = $this->getDataSource();
		
		$whereClause = '';
		$params = array();
		
		if (is_string($this->_primaryKey))
		{
			$whereClause = '`' . $this->_primaryKey . '`=?';
			$params = $this->getId();
		}
		else
		{
			foreach ($this->_primaryKey as $key)
			{
				$whereClause .= '`' . $dataSource->escape($key) . '`=? AND ';
				$params[] = $this->_columns[$key];
			}
			
			$whereClause = substr($whereClause, 0, -5);
		}
		
		if (!empty($whereClause) && !empty($params))
		{
			$dataSource->prepareAndExecute('
				DELETE FROM ' . $this->getTableName() . '
				WHERE ' . $whereClause, $params);
		}
		
		$this->_afterDelete();
	}
	
	/**
	 * Return a column value if available, null if not
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return isset($this->_columns[$key]) ? $this->_columns[$key] : null;
	}
	
	/**
	 * Return true if a column exists and is not null
	 *
	 * @param string $key
	 * @return bool
	 */
	public function __isset($key)
	{
		return isset($this->_columns[$key]);
	}

	/**
	 * Set a value
	 *
	 * @param string $key
	 * @param mixed $value
	 * @throws LSF_Exception_Database
	 * @return bool
	 */
	public function __set($key, $value)
	{
		if (!array_key_exists($key, $this->_columns)) {
			throw new LSF_Exception_Database('Trying to set value for column (' . $key . ') which doesn\'t exits.');
		}
		
		$this->_columns[$key] = $value;
		return true;
	}
	
	/**
	 * Returns whether or not a record exists based on the current primary key values
	 *
	 * @return bool
	 */
	private function recordExists()
	{
		$object = clone($this);
		return (bool)$object->load();
	}
	
	/**
	 * Returns whether or not a record is loaded
	 *
	 * @return bool
	 */
	public function isLoaded()
	{
		return (bool)$this->_loaded;
	}
}
