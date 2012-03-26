<?php

/**
 *
 *
 * @package LSF Label Simple Framework
 * $Id$
 */

class LSF_Console_Controller_Cli extends LSF_Console_Controller_Base
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
		$this->response->appendLine('  cli', array('white'));
		$this->response->appendContent('    lsf cli create ', array('cyan'));
		$this->response->appendContent('path [binaryName]', array('white'));
		$this->response->appendLine();
	}
	
	/**
	 * Action to create a new LSF project
	 */
	protected function createAction()
	{
		$suppliedPath = isset(LSF_Dispatch_Console::$arguments[0]) ? LSF_Dispatch_Console::$arguments[0] : false;
		$path = realpath($suppliedPath);
		$binaryName = isset(LSF_Dispatch_Console::$arguments[1]) ? LSF_Dispatch_Console::$arguments[1] : basename($path);
				
		if ($binaryName && $suppliedPath && is_dir($path) && is_dir($path . '/application/'))
		{
			$this->response->appendLine('Creating scripts dir');
			
			$dirPath = $path . '/scripts';
			if (!is_dir($dirPath)) {
				mkdir ($dirPath, 0777, true);
			}
		
			$this->response->appendLine('Linking cli file');
			
			$cliPath = $path . '/scripts/cli.php';
			if (!file_exists($cliPath)) {
				symlink(LSF_Application::getFrameworkPath() . '/cli.php', $cliPath);
			}
			
			$this->response->appendLine('Copy executable');
			
			$executableFile = $path . '/scripts/' . $binaryName . '.php';
			if (!file_exists($executableFile)) {
				copy(LSF_Application::getFrameworkPath() . '/applicationDefaults/bin.php', $executableFile);
				chmod($executableFile, 0777);
			}
			
			$this->response->appendLine('Creating controller dir');
			
			$dirPath = $path . '/application/Console/Controller';
			if (!is_dir($dirPath)) {
				mkdir ($dirPath, 0777, true);
			}
			
			$this->response->appendLine('Creating default controller');
			
			$controllerPath = $path . '/application/Console/Controller/Default.inc.php';
			if (!file_exists($controllerPath)) {
				copy(LSF_Application::getFrameworkPath() . '/applicationDefaults/Console.inc.php', $controllerPath);
			}
			
			$this->response->appendLine('Linking executable to /usr/bin will fail if not root');
			
			$executableLink = '/usr/bin/' . $binaryName;
			if (is_dir('/usr/bin') && is_writable('/usr/bin'))
			{
				if (!file_exists($executableLink)) {
					symlink($executableFile, $executableLink);
				}
			}
			else {
				$this->response->appendLine('/usr/bin not found or not writeable', array('hiWhite', 'bgRed'));
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
