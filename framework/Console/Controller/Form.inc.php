<?php

/**
 *
 *
 * @package LSF Label Simple Framework
 * $Id$
 */

class LSF_Console_Controller_Form extends LSF_Console_Controller_Base
{
	/**
	 * Default action
	 */
	protected function indexAction()
	{
		$this->usageAction();
		$this->response->appendLine('');
		$this->response->appendLine('Valid action required for form ', array('hiWhite', 'bgRed'));
		$this->response->appendLine('');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Console/Controller/LSF_Console_Controller_Project#usageAction()
	 */
	protected function helpAction()
	{
		$this->usageAction();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Console/LSF_Console_Controller#usageAction()
	 */
	public function usageAction()
	{
		$this->response->appendLine('  form', array('white'));
		$this->response->appendContent('    lsf form create ', array('cyan'));
		$this->response->appendContent('path formName', array('white'));
		$this->response->appendLine();
	}
	
	/**
	 * Action to create a new LSF project
	 */
	protected function createAction()
	{
		$suppliedPath = isset(LSF_Dispatch_Console::$arguments[0]) ? LSF_Dispatch_Console::$arguments[0] : false;
		$formName = isset(LSF_Dispatch_Console::$arguments[1]) ? LSF_Dispatch_Console::$arguments[1] : false;
		$path = realpath($suppliedPath);
		
		$formNameParts = explode('/', $formName);
		
		foreach ($formNameParts as &$part)
		{
			$part = ucfirst($part);
		}
		
		$formName = implode('/', $formNameParts);
		
		if ($formName && $suppliedPath && is_dir($path) && is_dir($path . '/application/Controller') && is_dir($path . '/application/Views/Smarty/Templates/Controller'))
		{
			if (!file_exists($path . '/application/Views/Smarty/Templates/Form/elementsLoop.tpl')) {
				copy(LSF_Application::getFrameworkPath() . '/applicationDefaults/form/elementsLoop.tpl', $path . '/application/Views/Smarty/Templates/Form/elementsLoop.tpl');
			}
			
			$templatePath = $path . '/application/Views/Smarty/Templates/Form/' . ucfirst($formName) . '/index.tpl';
			$formPath = $path . '/application/Form/' . ucfirst($formName) . '.inc.php';
			
			if (!file_exists($templatePath) && !file_exists($formPath))
			{
				$dirPath = dirname($templatePath);
				if (!is_dir($dirPath)) {
					mkdir ($dirPath, 0777, true);
				}
				$templateContent = file_get_contents(LSF_Application::getFrameworkPath() . '/applicationDefaults/form/form.tpl');
				file_put_contents($templatePath, $templateContent);
				
				$dirPath = dirname($formPath);
				if (!is_dir($dirPath)) {
					mkdir ($dirPath, 0777, true);
				}
				$formContent = file_get_contents(LSF_Application::getFrameworkPath() . '/applicationDefaults/form/Form.inc.php');
				$formContent = str_replace('{$formName}', str_replace('/', '_', ucfirst($formName)), $formContent);
				file_put_contents($formPath, $formContent);
				
				$this->response->appendLine('');
				$this->response->appendLine('Created form ' . $formName);
				$this->response->appendLine('');
			}
			else
			{
				$this->response->appendLine('');
				$this->response->appendLine('File already exists', array('hiWhite', 'bgRed'));
				$this->response->appendLine('');
			}
		}
		else
		{
			$this->response->appendLine('');
			$this->response->appendLine('Valid project dir required', array('hiWhite', 'bgRed'));
			$this->response->appendLine('');
		}
	}
}
