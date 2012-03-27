#!/usr/bin/php

<?php
require_once "System/Daemon.php";
require_once "BrewBot.php";

System_Daemon::setOption("appName", "brewbot");
System_Daemon::setOption("appDescription", "Brewbot sends brew notifications to participants");
System_Daemon::setOption("authorEmail", "tom@codebolt.co.uk");
System_Daemon::setOption("authorName", "Tom Rawcliffe");

$runmode = array(
    'no-daemon' => false,
    'help' => false,
    'write-initd' => false,
);

foreach ($GLOBALS['argv'] as $k=>$arg) 
{
	if (substr($arg, 0, 2) == '--' && isset($runmode[substr($arg, 2)])) {
		$runmode[substr($arg, 2)] = true;
	}
}

if ($runmode['help']) {
	echo "no help available\n";
	exit;
}

if (!$runmode['no-daemon'] && !$runmode['write-initd']) {
	System_Daemon::start();
}

if ($runmode['write-initd'])
{
	if (($initd_location = System_Daemon::writeAutoRun()) === false) {
		System_Daemon::info("unable to write init.d script");
	}
	else {
		System_Daemon::info('sucessfully written startup script: ' . $initd_location . "\n");
    }
}
else
{
	$app = new \BrewBot\Application();
	$app->run();
}

System_Daemon::stop();