<?php
namespace BrewBot;

require_once "System/Daemon.php";
use \System_Daemon;

class Application
{
	private
		$_partisipants,
		$_times = array('23:22'),
		$_lastBrew,
		$_currentTime,
		$_running = true;
		
	
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
		while (!System_Daemon::isDying() && $this->isRunning())
		{
			$this->setCurrentTimeMark();
			
			if ($this->isTimeForABrew())
			{
				System_Daemon::info('Time for a brew');
				$this->setLastBrewTime();
				$this->notifyPartisipants();
			}
			
			$this->_running = false; 
			System_Daemon::iterate(20);
		}
	}
	
	/**
	 * Sets up the current time variable for this iteration
	 * 
	 * @return void
	 */
	private function setCurrentTimeMark()
	{
		$this->_currentTime = date('H:m');
		System_Daemon::info('Updated current time to: ' . $this->_currentTime);
	}
	
	/**
	 * Returns true if the daemon should tell people its time for a brew
	 * 
	 * @return bool
	 */
	private function isTimeForABrew()
	{
		return false;
	}
	
	/**
	 * Marks the last brew time so we don't sent notifications twice
	 * 
	 * @return void
	 */
	private function setLastBrewTime()
	{
		$this->_lastBrew = $this->_currentTime;
	}
	
	/**
	 * Notify the partisipants that its time for a brew
	 */
	private function notifyPartisipants()
	{
		
	}
	
	/**
	 * Returns true if the daemon is not exiting
	 * 
	 * @return boolean
	 */
	public function isRunning()
	{
		return (bool)$this->_running;
	}
}