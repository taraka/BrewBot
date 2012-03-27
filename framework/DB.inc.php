<?php

/**
 * Data connection class
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

abstract class LSF_DB
{
	private static
		$_dbString,
		$_dataSource=null;
	
	private function __construct() {}
	
	/**
	 * Returns the system datasource and sets it up if required
	 *
	 * @return LSF_DB_DataSource
	 */
	public static function DataSource()
	{
		if (self::$_dataSource === null)
		{
			if (!self::$_dbString) {
				throw new LSF_Exception_Database('Database connection string not supplied');
			}
			
			$db = LSF_DB_DataSource::Factory(self::$_dbString . '?new_link=true');
			
			if ($db->isError()) {
				throw new LSF_Exception_Database('Failed to connect to database');
			}
			
			/**
			 * Run test query to check for connection
			 */
			$query = $db->query('SELECT 1+1');
			
			self::$_dataSource =& $db;
		}
		
		return self::$_dataSource;
	}
	
	/**
	 * Sets the connection string
	 *
	 * @param string $dbString
	 * @return void
	 */
	public static function setDbString($dbString)
	{
		self::$_dbString = $dbString;
	}
	
	/**
	 * remove reference to the connected database
	 * 
	 * @return void
	 */
	public static function destroyDbConnection()
	{
		self::$_dataSource = null;
	}
	
	private function __destruct() {}
}
