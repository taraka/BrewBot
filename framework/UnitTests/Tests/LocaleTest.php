<?php

/**
 * 
 * Test locale class
 * @package LSF
 * @subpackage Tests
 * @author martin
 */
class LocaleTest extends LSF_UnitTests_Base
{	
	public function testConstruct()
	{
		$locale = new LSF_Locale();
		$this->assertEquals("en-gb", $locale->getLocale());
	}
	
	public function testSetLocaleWithStrings()
	{
		$locale = new LSF_Locale();
		$this->assertEquals("meow", $locale->setLocale("meow"));
		$this->assertEquals("meow", $locale->getLocale());
		
		$this->assertEquals("woof", $locale->setLocale("WOOF"));
		$this->assertEquals("woof", $locale->getLocale());
	}
	
	/**
     * @dataProvider invalidDataTypeProvider
     */
	public function testSetLocaleWithInvalidDataTypes($value)
	{
		$locale = new LSF_Locale();
		$this->assertEquals("valid", $locale->setLocale("valid"));
		$this->assertFalse($locale->setLocale($value));
		$this->assertEquals("valid", $locale->getLocale());
	}
	
	public function invalidDataTypeProvider()
	{
		return array(
			array(123),
			array(array()),
			array(new StdClass()),
			array(true),
			array(99.99)
		);
	}
	
	public function testLocalePersistsAcrossInstances()
	{
		$locale = new LSF_Locale();
		$this->assertEquals("quack", $locale->setLocale("quack"));
		$this->assertEquals("quack", $locale->getLocale());
		
		$locale = new LSF_Locale();
		$this->assertEquals("quack", $locale->getLocale());
	}
	
	public function testLanguageFileExists()
	{		
		$locale = new LSF_Locale();
		
		$this->assertTrue($locale->languageFileExists("de"));
		$this->assertFalse($locale->languageFileExists("so"));
	}
	
	public function testLoadLanguageFileWithInvalidPath()
	{		
		$path = "none_existing.xml";
		
		$locale = new LSF_Locale();
		
		$this->assertFalse($locale->loadLanguageFile($path));
	}
	
	/**
     * @dataProvider invalidDataTypeProvider
     */
	public function testLoadLanguageFileWithInvalidDataTypes($value)
	{
		$locale = new LSF_Locale();
		$locale->loadLanguageFile($value);
		
		$this->assertEmpty($locale->getLocaleStrings());
	}
	
	public function testLoadLanguageFileWithValidXML()
	{
		$path = "global.xml";
		
		$locale = new LSF_Locale();
		$this->assertEquals("de", $locale->setLocale("de"));
		$this->assertEquals("de", $locale->getLocale());
		
		$this->assertTrue($locale->loadLanguageFile($path));
		
		$this->assertArrayHasKey("headerImage", $locale->getLocaleStrings());
		$this->assertArrayHasKey("youBeenRecommended", $locale->getLocaleStrings());
		$this->assertArrayHasKey("recommendedBy", $locale->getLocaleStrings());
		$this->assertArrayHasKey("whoThought", $locale->getLocaleStrings());
		$this->assertArrayHasKey("clickHere", $locale->getLocaleStrings());
		
		$localeStrings = $locale->getLocaleStrings();
		$this->assertEquals("header_de.png", $localeStrings["headerImage"]);
		$this->assertEquals("Sie wurden von einem Freund weiterempfohlen.", $localeStrings["youBeenRecommended"]);
		$this->assertEquals("Wir haben Ihre Mailadresse von", $localeStrings["recommendedBy"]);
		$this->assertEquals("erhalten, mit dem Hinweis, dass Sie an Milwaukee Elektrowerkzeugen und Zubehor interessiert sind.", $localeStrings["whoThought"]);
		$this->assertEquals("klicken Sie hier", $localeStrings["clickHere"]);
	}
	
	/**
     * @expectedException LSF_Exception_FileIO
     */
	public function testLoadLanguageFileWithInvalidXML()
	{
		$path = "invalid.xml";
		
		$locale = new LSF_Locale();
		$this->assertEquals("de", $locale->setLocale("de"));
		$this->assertEquals("de", $locale->getLocale());
		
		$locale->loadLanguageFile($path);
	}
}
