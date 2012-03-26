<?php

/**
 * 
 * Test config class
 * @package LSF
 * @subpackage Tests
 * @author tom
 */
class ConfigTest extends LSF_UnitTests_Base
{	
	public function setup()
	{
		$config = <<<ConfigEOF
setting1 = "I am a string"

setting2 = 2

[dev]

setting2 = devconfig

setting3 = 4

[other]

fake = wibble

ConfigEOF;
		file_put_contents(LSF_Application::getApplicationPath() . '/Config/application.ini', $config);
	}
	
	public function testDefault()
	{
		LSF_Config::setup();
		
		$this->assertEquals(count(LSF_Config::getAll()), 2);
		$this->assertEquals(LSF_Config::get('setting1'), "I am a string");
		$this->assertEquals(LSF_Config::get('setting2'), 2);
		$this->assertFalse(LSF_Config::get('setting3'));
		$this->assertFalse(LSF_Config::get('fake'));
	}
	
	public function testEnviroment()
	{
		file_put_contents(LSF_Application::getApplicationPath() . '/Config/env', 'dev');
		LSF_Application::setEnvironment('dev');
		
		LSF_Config::setup();
		
		$this->assertEquals(count(LSF_Config::getAll()), 3);
		$this->assertEquals(LSF_Config::get('setting1'), "I am a string");
		$this->assertEquals(LSF_Config::get('setting2'), 'devconfig');
		$this->assertEquals(LSF_Config::get('setting3'), 4);
		$this->assertFalse(LSF_Config::get('fake'));
	}
	
	public static function tearDownAfterClass()
	{
		if (file_exists(LSF_Application::getApplicationPath() . '/Config/env')) {
			unlink(LSF_Application::getApplicationPath() . '/Config/env');
		}
	}
}
