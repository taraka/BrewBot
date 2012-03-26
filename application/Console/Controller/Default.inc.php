<?php

/**
 *
 *
 * @package 
 * $Id$
 */

class Console_Controller_Default extends LSF_Console_Controller
{	
	protected function indexAction()
	{
		$this->response->appendLine('Welcome to your Applications CLI');
		$this->response->appendLine('');
	}
	
	public function usageAction()
	{
		
	}
}
