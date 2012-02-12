<?php
namespace BrewBot;

require_once "System/Daemon.php";
use \System_Daemon;

class Application
{
	private
		$_partisipants,
		$_times,
		$_lastBrew;
		
	
	public function __construct()
	{
		
	}
	
	/**
	 * Run the app
	 * 
	 * @return int
	 */
	public function run()
	{
		while (!System_Daemon::isDying())
		{
			System_Daemon::info("Write info");
			System_Daemon::iterate(2);
		}
	}
}