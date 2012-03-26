<?php

/**
 * Boot strap for the application executes before the application runs
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Bootstrap extends LSF_BootstrapAbstract
{
	/**
	 * (non-PHPdoc)
	 * @see LSF_BootstrapAbstract#run()
	 */
	public function run()
	{
		$this->setupDb();
	}
	
	/**
	 * Creates the data connection if the db_connection_string is avaliable from the config
	 *
	 * @return void
	 */
	public function setupDb()
	{
		if (LSF_Config::get('db_connection_string')) {
			LSF_DB::setDbString(LSF_Config::get('db_connection_string'));
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see LSF_BootstrapAbstract#addRoutes()
	 */
	public function addRoutes(LSF_Router $router)
	{
		 $router->addRoute(new LSF_Router_Route_Default());
	}
}
