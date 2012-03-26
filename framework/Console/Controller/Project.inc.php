<?php

/**
 *
 *
 * @package LSF Label Simple Framework
 * $Id: Default.inc.php 6 2009-08-26 11:11:40Z sam $
 */

class LSF_Console_Controller_Project extends LSF_Console_Controller_Base
{
	public function __construct()
	{
		parent::__construct();
	}
	
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
		$this->response->appendLine('  project', array('white'));
		$this->response->appendLine('    lsf project create ', array('cyan'));
	}
	
	/**
	 * Action to create a new LSF project
	 */
	protected function createAction()
	{
		$suppliedPath = isset(LSF_Dispatch_Console::$arguments[0]) ? LSF_Dispatch_Console::$arguments[0] : false;
		$path = realpath($suppliedPath);
		
		if ($suppliedPath && is_dir($path))
		{
			$this->response->appendLine('Creating project: ' . $path);
			$this->response->appendLine('');
			
			$this->response->appendLine('Creating Public Folders');
			mkdir($path . '/public', 0775);
			mkdir($path . '/public/css', 0775);
			mkdir($path . '/public/images', 0775);
			mkdir($path . '/public/js', 0775);
			
			$this->response->appendLine('Creating Logs Folder');
			mkdir($path . '/logs', 0775);
	
			$this->response->appendLine('Creating Application Folders');
			mkdir($path . '/application', 0775);
			mkdir($path . '/application/Config', 0775);
			mkdir($path . '/application/Controller', 0775);
			mkdir($path . '/application/Component', 0775);
			mkdir($path . '/application/Views', 0775);
			mkdir($path . '/application/Email', 0775);
			mkdir($path . '/application/Form', 0775);
			mkdir($path . '/application/Model', 0775);
			mkdir($path . '/application/Views/Smarty', 0775);
			mkdir($path . '/application/Views/Smarty/Templates', 0775);
			mkdir($path . '/application/Views/Smarty/Templates/Controller', 0775);
			mkdir($path . '/application/Views/Smarty/Templates/Controller/Default', 0775);
			mkdir($path . '/application/Views/Smarty/Templates/Component', 0775);
			mkdir($path . '/application/Views/Smarty/Templates/Form', 0775);
			mkdir($path . '/application/Views/Smarty/Templates/Email', 0775);
			mkdir($path . '/application/Views/Smarty/Templates_c', 0775);
			
			$this->response->appendLine('Creating framework links');
			symlink(LSF_Application::getFrameworkPath(), $path . '/framework');
			symlink($path . '/framework/index.php', $path . '/public/index.php');
			symlink($path . '/framework/.htaccess', $path . '/public/.htaccess');
			
			$this->response->appendLine('Adding Default Files');
			
			$this->copy($path . '/framework/applicationDefaults/Default.inc.php', $path . '/application/Controller/Default.inc.php');
			$this->copy($path . '/framework/applicationDefaults/index.tpl', $path . '/application/Views/Smarty/Templates/Controller/Default/index.tpl');
			$this->copy($path . '/framework/applicationDefaults/form/elementsLoop.tpl', $path . '/application/Views/Smarty/Templates/Form/elementsLoop.tpl');
			$this->copy($path . '/framework/applicationDefaults/header.tpl', $path . '/application/Views/Smarty/Templates/header.tpl');
			$this->copy($path . '/framework/applicationDefaults/footer.tpl', $path . '/application/Views/Smarty/Templates/footer.tpl');
			$this->copy($path . '/framework/applicationDefaults/default.css', $path . '/public/css/default.css');
			$this->copy($path . '/framework/applicationDefaults/application.ini', $path . '/application/Config/application.ini');
			$this->copy($path . '/framework/applicationDefaults/.gitignore', $path . '/.gitignore');
		}
		else
		{
			$this->response->appendLine('');
			$this->response->appendLine('Valid path required', array('hiWhite', 'bgRed'));
			$this->response->appendLine('');
		}
	}
	
	/**
	 * Copies files if the target does not exist
	 * 
	 * @param string $source
	 * @param string $target
	 */
	private function copy($source, $target)
	{
		if (!file_exists($target)) {
			return copy($source, $target);
		}
	}
}
