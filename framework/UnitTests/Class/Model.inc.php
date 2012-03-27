<?php

/**
 * A basic testable model to enable testign of base model code
 * 
 * @package LSF
 * @subpackage Tests
 * @author tom
 */
class LSF_UnitTests_Class_Model extends LSF_Model
{
	private static
		$_dao;
		
	public function __construct()
	{
		$this->setTableName('test_table');
		parent::__construct(self::$_dao);
	}
	
	public static function setDao(LSF_Model_Dao_Interface $dao)
	{
		self::$_dao = $dao;
	}
}