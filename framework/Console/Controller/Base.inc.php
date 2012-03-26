<?php

/**
 *
 *
 * @package LSF Label Simple Framework
 * $Id$
 */

abstract class LSF_Console_Controller_Base extends LSF_Console_Controller
{
	/**
	 * (non-PHPdoc)
	 * @see Console/LSF_Console_Controller#start()
	 */
	public function start()
	{
		$this->response->appendLine(
				'LSF Console Utilities');
		$this->response->appendLine('Copyright (C) 2009-2010 Label Media Ltd.');
		$this->response->appendLine('<http://www.labelmedia.co.uk/>');
		$this->response->appendLine('');
		
		$this->response->flushContent();
		
		return parent::start();
	}
}
