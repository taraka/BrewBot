<?php

/**
 * Wrapper class to defer calls to methods on MDB2_Statement_Common objects and detect errors
 * @author tom
 * @package LSF
 */
abstract class LSF_DB_Statement
{
	public function __construct() {}
	
	public function __destruct() {}
}