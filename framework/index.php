<?php

/**
 * Application entry point
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id: index.php 4 2009-08-26 10:21:37Z sam $
 */

if (file_exists(getCwd(). '/../framework/Application.inc.php'))
{
	chdir('..');
	include_once 'framework/Application.inc.php';
}
else {
	include_once 'Application.inc.php';
}

$app = new LSF_Application();
$app->bootstrap();
$app->run();
