#!/usr/bin/php
<?php


/**
 * We need to change the cwd to be the directory that contains this file, for this reason this file cannot be a symbolic link.  
 */
define('SCRIPT_CWD', getcwd());
chdir(realpath(dirname(__FILE__) . '/..'));

include ('scripts/cli.php');