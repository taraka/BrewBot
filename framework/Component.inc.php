<?php

/**
 * Base component class gives a generic container for a presentable conponent
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

abstract class LSF_Component extends LSF_Presentable
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct() {}
}
