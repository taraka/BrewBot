<?php

/**
 * Json presentation logic
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Presenter_Json extends LSF_Presenter
{
	public function __construct()
	{
		header('Content-Type: application/json');
		
		parent::__construct();
	}
	
	/**
	 * Returns the complete XML output for this object
	 * 
	 * @return string
	 */
	public function fetch()
	{
		$viewProcessor = new LSF_Presenter_View_Json();
		return $viewProcessor->parse($this->view);
	}
}
