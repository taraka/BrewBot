<?php

/**
 *
 *
 * @package LSF Label Simple Framework
 * $Id: Default.inc.php 6 2009-08-26 11:11:40Z sam $
 */

class LSF_Console_Controller_Default extends LSF_Console_Controller_Base
{
	/**
	 * Default request for command line
	 */
	protected function indexAction()
	{
		$this->usageAction();
		$this->response->appendLine('');
		$this->response->appendLine('Invalid request', array('hiWhite', 'bgRed'));
		$this->response->appendLine('');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Console/LSF_Console_Controller#usageAction()
	 */
	public function usageAction()
	{
		$controller = new LSF_Console_Controller_Project();
		$controller->usageAction();
		
		$controller = new LSF_Console_Controller_Controller();
		$controller->usageAction();
		
		$controller = new LSF_Console_Controller_Form();
		$controller->usageAction();
		
		$controller = new LSF_Console_Controller_Cli();
		$controller->usageAction();
	}
}
