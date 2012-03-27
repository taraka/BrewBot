<?php

/**
 * 
 * Test model validator class
 * @package LSF
 * @subpackage Tests
 * @author martin
 */
class LSF_Validation_ResultTest extends LSF_UnitTests_Base
{	
	public function testConstruct()
	{
		$validator = new LSF_Validation_Result();
		
		$this->assertEmpty($validator->getErrors());
		$this->assertEquals(0, $validator->getNumberOfErrors());
		$this->assertTrue($validator->success());
	}
	
	public function testAddErrorWithStringKey()
	{
		$validator = new LSF_Validation_Result();
		
		$this->assertTrue($validator->addError("error", "generic error"));		
		
		$this->assertArrayHasKey("error", $validator->getErrors());
		$this->assertEquals(1, $validator->getNumberOfErrors());
		$this->assertFalse($validator->success());
		
		$this->assertTrue($validator->addError("fail", "something failed, probably the whale"));	
			
		$this->assertArrayHasKey("fail", $validator->getErrors());
		$this->assertEquals(2, $validator->getNumberOfErrors());
		$this->assertFalse($validator->success());
		
		$errors = $validator->getErrors();
		
		$this->assertEquals($errors["error"], "generic error");
		$this->assertEquals($errors["fail"], "something failed, probably the whale");
	}
	
	public function testAddErrorWithIntKey()
	{
		$validator = new LSF_Validation_Result();
		
		$this->assertTrue($validator->addError(123, "generic error"));	
			
		$this->assertArrayHasKey(123, $validator->getErrors());
		$this->assertEquals(1, $validator->getNumberOfErrors());
		$this->assertFalse($validator->success());
		
		$this->assertTrue($validator->addError(99, "something failed, probably the whale"));
		
		$this->assertArrayHasKey(99, $validator->getErrors());
		$this->assertEquals(2, $validator->getNumberOfErrors());
		$this->assertFalse($validator->success());
		
		$errors = $validator->getErrors();
		
		$this->assertEquals($errors[123], "generic error");
		$this->assertEquals($errors[99], "something failed, probably the whale");
	}
	
	public function testAddErrorWithObjectKey()
	{
		$validator = new LSF_Validation_Result();
		
		$this->assertFalse($validator->addError(new StdClass(), "generic error"));	
			
		$this->assertEmpty($validator->getErrors());
		$this->assertEquals(0, $validator->getNumberOfErrors());
		$this->assertTrue($validator->success());
	}
	
	public function testAddErrorWithFloatKey()
	{
		$validator = new LSF_Validation_Result();
		
		$this->assertFalse($validator->addError(59.99, "generic error"));	
			
		$this->assertEmpty($validator->getErrors());
		$this->assertEquals(0, $validator->getNumberOfErrors());
		$this->assertTrue($validator->success());
	}
	
	public function testAddErrorWithArrayKey()
	{
		$validator = new LSF_Validation_Result();
		
		$this->assertFalse($validator->addError(array(), "generic error"));	
			
		$this->assertEmpty($validator->getErrors());
		$this->assertEquals(0, $validator->getNumberOfErrors());
		$this->assertTrue($validator->success());
	}
}
