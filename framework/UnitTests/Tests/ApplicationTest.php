<?php

/**
 * 
 * Test application class
 * @package LSF
 * @subpackage Tests
 * @author tom
 */
class ApplicationTest extends LSF_UnitTests_Base
{	
	public function testBaseUrl()
	{
		$this->assertEquals(LSF_Application::getBaseUrl(), 'http://');
	}
	
	public function testClassLoading()
	{
		$this->assertTrue(class_exists('LSF_Bootstrap'));
	}
	
	/**
	 * @expectedException LSF_Exception_ClassNotFound
	 */
	public function testClassLoadFail()
	{
		class_exists('LSF_ApplicationFAIL');
	}
	
	public function testApplicationCreate()
	{
		$testApp = new LSF_Application();
	}
	
	public function testBootstrapping()
	{
		$testApp = new LSF_Application();
		$testApp->bootstrap();
		
		$bootstrap = LSF_Application::getBootstrap();
		$this->assertTrue($bootstrap instanceof LSF_BootstrapAbstract);
	}
	
	public function testExecutionTime()
	{
		$this->assertTrue(is_numeric(LSF_Application::getExecutionStartTime()));
	}
	
	public function testRunApp()
	{
		$testApp = new LSF_Application();
		$testApp->bootstrap();
		$this->assertFalse($testApp->run());
	}
}
