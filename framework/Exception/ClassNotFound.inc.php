<?php

/**
 *
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id: Database.inc.php 4 2009-09-04 13:31:58Z sam $
 */

class LSF_Exception_ClassNotFound extends Exception
{
	public function __construct($message=null, $code=null)
	{
		parent::__construct($message, $code);
	}
}
