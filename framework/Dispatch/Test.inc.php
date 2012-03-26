<?php

/**
 *
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id: Controller.inc.php 38 2010-02-01 16:52:35Z tom $
 */

class LSF_Dispatch_Test implements LSF_IDispatch
{
	/**
	 * Returns false and lets the test proceed
	 *
	 * @return bool
	 */
	public function run()
	{
		return false;
	}
}
