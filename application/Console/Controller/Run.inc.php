<?php

/**
 *
 *
 * @package 
 * $Id$
 */

class Console_Controller_Run extends LSF_Console_Controller implements Interface_Observer
{	
	protected function indexAction()
	{
		$this->response->appendLine('Triggering brews');
		$this->response->appendLine('');
		
		$brewBot = new Model_BrewBot($this);
		
		$timeslot = new Model_Timeslot();
		
		$brewBot->timeForABrew($timeslot);
	}
	
	public function usageAction()
	{
		
	}
	
	/**
	 * Recive notifications from the bot
	 * 
	 * @param string $text
	 */
	public function update($text)
	{
		$this->response->appendLine($text);
		$this->response->flushContent();
	}
}
