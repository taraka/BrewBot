<?php

/**
 * 
 * Test string validator class
 * @package LSF
 * @subpackage Tests
 * @author tom
 */
class LSF_Utils_Validator_StringTest extends LSF_UnitTests_Base
{
	private $validator;
	
	public function emailProvider()
	{
		return array(
			array(true, 'test@example.com'),
			array(true, 'test_1@example.com'),
			array(true, '341@example.com'),
			array(true, 'a@b.c'),
			array(true, '341@12.com'),
			array(true, 'test.1@example.com'),
			array(true, 'tes_t.2@example.com.co.uk'),
			array(false, ''),
			array(false, '_test@example.com'),
			array(false, '-test@example.com'),
			array(false, 'test@-example.com'),
			array(false, 'test@test'),
			array(false, 'test@example.'),
			array(false, 'test@bob'),
			array(false, 'test@example..com'),
			array(false, 'something.com'),
			array(false, 'tom@something.c_om'),
			array(false, 'tom@some_thing.com'),
			array(false, 'ku.oc.aidemlebal@mas..'),
		);
	}
	
	public function phoneNumberProvider()
	{
		return array(
			array(true, '01132458868'),
			array(true, '2113 245 8868'),
			array(false, '2a13 245 8868'),
			array(true, '+44 213 245 8868'),
			array(true, '+44 (0)213 245 8868'),
			array(true, '651651651213 245 8868'),
		);
	}
	
	public function wordCountProvider()
	{
		return array(
			array(true, 'hello', 1),
			array(false, 'hello bob', 1),
			array(true, 'hello bob', 2),
			array(false, "hello\nbob", 1),
			array(true, "hello\nbob hello", 5),
		);
	}
	
	protected function setUp()
	{
		$this->validator = new LSF_Utils_Validator_String();
	}
	
	/**
	 * @dataProvider emailProvider
	 */
	public function testEmailValidation($expected=true, $value)
	{
		$this->assertEquals($expected, $this->validator->validateEmail($value));
	}
	
	/**
	 * @dataProvider phoneNumberProvider
	 */
	public function testPhoneNumberValidation($expected=true, $value)
	{
		$this->assertEquals($expected, $this->validator->validatePhoneNumber($value));
	}
	
	/**
	 * @dataProvider wordCountProvider
	 */
	public function testWordCountValidation($expected, $value, $wc)
	{
		$this->assertEquals($expected, $this->validator->validateWordCount($value, $wc));
	}
}
