<?php

/**
 * LSF Static buffer base class
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Utils_Buffer_Static extends LSF_Utils_Buffer
{
	private static 
		$_bufferList = array();
	
	public function __construct()
	{
		parent::__construct();
		
		if (!isset(self::$_bufferList[get_class($this)])) {
			self::$_bufferList[get_class($this)] = '';	
		}
		
		$this->buffer =& self::$_bufferList[get_class($this)];
	}
}
