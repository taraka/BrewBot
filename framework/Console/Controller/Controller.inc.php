<?php

/**
 *
 *
 * @package LSF Label Simple Framework
 * $Id$
 */

class LSF_Console_Controller_Controller extends LSF_Console_Controller_Base
{
	/**
	 * Default action
	 */
	protected function indexAction()
	{
		$this->usageAction();
		$this->response->appendLine('');
		$this->response->appendLine('Valid action required for controller ', array('hiWhite', 'bgRed'));
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
		$this->response->appendLine('  controller', array('white'));
		$this->response->appendContent('    lsf controller create ', array('cyan'));
		$this->response->appendContent('path controllerName', array('white'));
		$this->response->appendLine();
	}
	
	/**
	 * Action to create a new LSF project
	 */
	protected function createAction()
	{
		$suppliedPath = isset(LSF_Dispatch_Console::$arguments[0]) ? LSF_Dispatch_Console::$arguments[0] : false;
		$controllerName = isset(LSF_Dispatch_Console::$arguments[1]) ? LSF_Dispatch_Console::$arguments[1] : false;
		$path = realpath($suppliedPath);
		
		$controllerNameParts = explode('/', $controllerName);
		
		foreach ($controllerNameParts as &$part)
		{
			$part = ucfirst($part);
		}
		
		$controllerName = implode('/', $controllerNameParts);
		
		if ($controllerName && $suppliedPath && is_dir($path) && is_dir($path . '/application/Controller') && is_dir($path . '/application/Views/Smarty/Templates/Controller'))
		{
			
			$templatePath = $path . '/application/Views/Smarty/Templates/Controller/' . ucfirst($controllerName) . '/index.tpl';
			$controllerPath = $path . '/application/Controller/' . ucfirst($controllerName) . '.inc.php';
			
			if (!file_exists($templatePath) && !file_exists($controllerPath))
			{
				$dirPath = dirname($templatePath);
				if (!is_dir($dirPath)) {
					mkdir ($dirPath, 0777, true);
				}
				$templateContent = file_get_contents(LSF_Application::getFrameworkPath() . '/applicationDefaults/controller/default.tpl');
				file_put_contents($templatePath, $templateContent);
				
				$dirPath = dirname($controllerPath);
				if (!is_dir($dirPath)) {
					mkdir ($dirPath, 0777, true);
				}
				$controllerContent = file_get_contents(LSF_Application::getFrameworkPath() . '/applicationDefaults/controller/Controller.inc.php');
				$controllerContent = str_replace('{$controllerName}', str_replace('/', '_', ucfirst($controllerName)), $controllerContent);
				file_put_contents($controllerPath, $controllerContent);
				
				$this->response->appendLine('');
				$this->response->appendLine('Created controller ' . $controllerName);
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
