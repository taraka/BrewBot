<?php

/**
 * Class for loading local information
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Locale
{
	const DEFAULT_LOCALE = 'en-gb';
	private static $_locale;
	
	private $_localeStrings = array();
	
	public function __construct()
	{
		if (!self::$_locale) {
			self::setLocale(self::DEFAULT_LOCALE);
		}
	}
	
	/**
	 * Loads a language xml file
	 *
	 * @param string $path
	 * @throws LSF_Exception_FileIO
	 * @return bool
	 */
	public function loadLanguageFile($path)
	{
		if (is_string($path))
		{
			$path = LSF_Application::getApplicationPath() .'/LocaleXML/' . self::$_locale .  '/' . $path;
		
			if (file_exists($path))
			{
				libxml_use_internal_errors(true);
				
				if (!$xmlObject = simplexml_load_file($path))
				{
					libxml_use_internal_errors(false);
					throw new LSF_Exception_FileIO("XML file contained errors: $path");
				}

				foreach ($xmlObject->xpath('//localeStrings/*') as $element) {
					$this->_localeStrings[$element->getName()] = (string)$element;
				}
				
				libxml_use_internal_errors(false);
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Checks to see if a language file is available
	 * 
	 * @param string
	 * @return bool
	 */
	public static function languageFileExists($locale)
	{
		return file_exists(LSF_Application::getApplicationPath() . '/Config/Languages/' . $locale . '.ini');
	}
	
	/**
	 * Returns the text strings for this object in the current locale
	 *
	 * @return array
	 */
	public function getLocaleStrings()
	{
		return $this->_localeStrings;
	}
	
	/**
	 * Returns the current locale code
	 *
	 * @return string
	 */
	public static function getLocale()
	{
		return self::$_locale;
	}
	
	/**
	 * Sets the current locale code
	 *
	 * @param string $locale
	 * @return the new locale or bool false on fail
	 */
	public static function setLocale($locale)
	{
		if (is_string($locale)) {
			return self::$_locale = strtolower($locale);
		}
		
		return false;
	}
}
