<?php

/**
 * 
 * Test model class
 * @package LSF
 * @subpackage Tests
 * @author tom
 */
class LSF_UnitTests_Class_ModelTest extends LSF_UnitTests_Base
{	
	
	/*public static function setUpBeforeClass()
	{
		LSF_UnitTests_Class_Model::setDao(new LSF_Model_Dao_Test(array(
			'id'		=> array('type' => 'int',		'null' => false),
			'name'		=> array('type' => 'string',	'null' => false),
			'optional'	=> array('type' => 'string', 	'null' => true),
		)));
	}*/
	
	/**
	 * @expectedException LSF_Exception_Model
	 */
	public function testSetupNoTable()
	{
		$this->markTestIncomplete('Test needs updating');
		
		$stub = $this->getMockForAbstractClass('LSF_Model');
	}
	
	/**
	 * @expectedException LSF_Exception_Database
	 */
	public function testCreateBadModel()
	{
		$this->markTestIncomplete('Test needs updating');
		
		$model = new LSF_UnitTests_Class_Model();
		$model->save();
	}
	
	public function testCreateRealModel()
	{
		$this->markTestIncomplete('Test needs updating');
		
		$model = new LSF_UnitTests_Class_Model();
		
		$this->assertEquals($model->setName('bob'), 'bob');
		
		$this->assertTrue(is_numeric($model->save()));
		
		$this->assertEquals($model->getId(), 1);
		
		return $model;
	}
	
	/**
	 * @depends testCreateRealModel
	 */
	public function testSetOptional($model)
	{
		$this->assertEquals($model->setOptional('hello'), 'hello');
		
		$this->assertTrue(is_numeric($model->save()));
		
		$this->assertEquals($model->getId(), 1);
	}
	
	public function testCreateSecond()
	{
		$this->markTestIncomplete('Test needs updating');
		
		$model = new LSF_UnitTests_Class_Model();
		
		$this->assertEquals($model->setName('Fred'), 'Fred');
		
		$this->assertTrue(is_numeric($model->save()));
		
		$this->assertEquals($model->getId(), 2);
		
		return $model->getId();
	}
	
	/**
	 * @depends testCreateSecond
	 */
	public function testLoad($id)
	{
		$this->markTestIncomplete('Test needs updating');
		
		$model = new LSF_UnitTests_Class_Model();
		
		$this->assertFalse($model->load('wibble'));
		$this->assertFalse($model->load(999));
		$this->assertTrue($model->load($id));
		$this->assertTrue($model->isLoaded());
		
		$this->assertEquals($model->getName(), 'Fred');
	}
	
	/**
	 * @depends testCreateSecond
	 */
	public function testClone($id)
	{
		$model = new LSF_UnitTests_Class_Model();
		
		$this->assertFalse($model->load('wibble'));
		$this->assertFalse($model->load(999));
		$this->assertTrue($model->load($id));
		$this->assertTrue($model->isLoaded());
		
		$clone = $model->cloneSelf();
		$this->assertTrue($clone instanceof LSF_UnitTests_Class_Model);
		
		$this->assertEquals($model->getName(), $clone->getName());
		
		$this->assertNotEquals($id, $clone->getId());
	}
	
	public function testInject()
	{
		$this->markTestIncomplete('Test needs updating');
		
		$model = new LSF_UnitTests_Class_Model();
		
		$model->inject(array(
			'id'		=> 11,
			'name'		=> 'bob',
			'optional'	=> null,
		));
		
		$this->assertTrue($model->isLoaded());
		
		$this->assertEquals($model->getName(), 'bob');
		$this->assertEquals($model->getId(), 11);
	}
}
