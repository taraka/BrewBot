<?php

/**
 *
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Dispatch_AppConsole extends LSF_Dispatch_Console
{
	public function run()
	{
		$controller = !empty(self::$arguments[0]) ? self::$arguments[0] : false;
		array_shift(self::$arguments);
		$console = LSF_Controller_Factory::getConsoleAppController($controller);
		$console->start();
	}
}
