#!/usr/bin/php

<?php
require_once "System/Daemon.php";
require_once "BrewBot.php";

System_Daemon::setOption("appName", "brewbot");
System_Daemon::setOption("appDescription", "Brewbot sends brew notifications to partisipants");
System_Daemon::setOption("authorEmail", "tom@codebolt.co.uk");
System_Daemon::setOption("authorName", "Tom Rawcliffe");

System_Daemon::start();

//System_Daemon::writeAutoRun();

$app = new \BrewBot\Application();
$app->run();

System_Daemon::stop();