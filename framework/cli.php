<?php 
/**
 * Application entry point
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

include_once('Application.inc.php');

define('APPLICATION_CLI', true);

$app = new LSF_Application();
$app->bootstrap();
$app->run();
