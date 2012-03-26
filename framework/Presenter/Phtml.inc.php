<?php

/**
 * Phtml Presenter allows for very fast presentation as the view object does not require parsing.
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Presenter_Phtml extends LSF_Presenter
{
	private $_smarty;
	
	public function __construct($controllerName=null)
	{
		parent::__construct($controllerName);
	}
	
	/**
	 * Returns the complete HTML output for this object
	 *
	 * @return string
	 */
	public function fetch()
	{
		ob_start();
		$templateName = $this->getTemplateName() . '/' . $this->getActionName() . '.phtml';
		$templatePath = LSF_Application::getApplicationPath() . '/Views/Phtml/';
		
		$view = $this->view;
		
		if (file_exists($templatePath . $templateName)) {
			$oldCwd = getcwd();
			chdir($templatePath);
			include($templateName);
			chdir($oldCwd);
		}
		elseif (($errorController = LSF_Controller_Factory::getError('404')) && method_exists($errorController, '__toString')) {
			echo (string)$errorController;
		}
		else {
			throw new LSF_Exception_TemplateNotFound('Invalid action');
		}
		
		return ob_get_clean();
	}
}
