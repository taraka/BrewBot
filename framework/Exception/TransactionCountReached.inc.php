<?php

/**
 *
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Exception_TransactionCountReached extends Exception
{
	public function __construct()
	{
		parent::__construct(null, null);
	}
}
