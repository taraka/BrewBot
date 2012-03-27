<?php

/**
 * Abstract bootstrap should be extended for custom bootstrapping
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id: Bootstrap.inc.php 55 2010-04-06 12:43:43Z tom $
 */

abstract class LSF_BootstrapAbstract
{
	/**
	 * Execute the bootstrap functions
	 */
	abstract public function run();
	
	/**
	 * Add route the request tot eh correct controller
	 * @param LSF_Router $router
	 */
	abstract public function addRoutes(LSF_Router $router);
}
