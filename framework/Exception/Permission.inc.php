<?php

/**
 *
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Exception_Permission extends Exception
{
	public function __construct($message=null, $code=null)
	{
		parent::__construct($message, $code);
	}
}
