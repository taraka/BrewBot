<?php

/**
 * Smarty3 presenter
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Presenter_Smarty3 extends LSF_Presenter
{
	private $_smarty;
	
	public function __construct($controllerName=null)
	{
		parent::__construct($controllerName);
		$this->setupSmarty();
	}
	
	/**
	 * Returns the complete HTML output for this object
	 *
	 * @return string
	 */
	public function fetch()
	{
		$viewProcessor = new LSF_Presenter_View_Smarty();
		$viewProcessor->setView($this->view);
		
		foreach ($viewProcessor->getProperties() as $name => $value) {
			$this->_smarty->assign($name, $value);
		}
		
		$this->_smarty->assign('localeCode', LSF_Locale::getLocale());
		$this->_smarty->assign('localeStrings', $this->locale->getLocaleStrings());
		
		$templateName = $this->getTemplateName() . '/' . $this->getActionName() . '.tpl';
		
		if (file_exists(array_shift($this->_smarty->getTemplateDir()) . $templateName)) {
			return $this->_smarty->fetch($templateName);
		}
		elseif (($errorController = LSF_Controller_Factory::getError('404')) && method_exists($errorController, '__toString')) {
			return (string)$errorController;
		}
		else {
			throw new LSF_Exception_TemplateNotFound('Invalid action');
		}
	}
	
	/**
	 * Sets up the internal Smarty object
	 *
	 * @return void
	 */
	private function setupSmarty()
	{
		if (!$this->_smarty)
		{
			try {
				include_once(LSF_Application::getFrameworkPath() . '/Externals/Smarty3/Smarty.class.php');
				
				$templatePath = LSF_Application::getApplicationPath() . '/Views';
				
				$this->_smarty = new Smarty();
			}
			catch (LSF_Exception_ClassNotFound $e) {}
			
			$this->_smarty->error_reporting = E_ALL & ~E_NOTICE;
			
			$this->_smarty->setTemplateDir($templatePath . '/Smarty/Templates/');
			$this->_smarty->setCompileDir($templatePath . '/Smarty/Templates_c/');
		}
	}
}
