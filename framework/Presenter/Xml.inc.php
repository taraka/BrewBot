<?php

/**
 * XML Presenter
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id: Presenter.inc.php 55 2010-04-06 12:43:43Z tom $
 */

class LSF_Presenter_Xml extends LSF_Presenter
{
	public function __construct()
	{
		header('Content-Type: text/xml; charset=utf-8');
		
		parent::__construct();
	}
	
	/**
	 * Returns the complete XML output for this object
	 * 
	 * @return string
	 */
	public function fetch()
	{
		$viewProcessor = new LSF_Presenter_View_Xml();
		return $viewProcessor->parse($this->view);
	}
}
