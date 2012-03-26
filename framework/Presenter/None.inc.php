<?php

/**
 * Blank presenter to stop any output
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Presenter_None extends LSF_Presenter
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Returns an empty string
	 * 
	 * @return string
	 */
	public function fetch()
	{
		return "";
	}
}
