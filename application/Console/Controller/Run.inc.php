<?php

require (LSF_Application::getApplicationPath() . '/../Ext/twitteroauth/twitteroauth.php');

/**
 *
 *
 * @package 
 * $Id$
 */

class Console_Controller_Run extends LSF_Console_Controller
{	
	private
		$_twitter;
		
	protected function indexAction()
	{
		$this->response->appendLine('Triggering brews');
		$this->response->appendLine('');
		
		$groupList = new Model_Group_List();
		$groupList->load();
		
		foreach ($groupList->getIterator() as $group)
		{
			$user = $group->getRandomUser();
			
			$tweet = sprintf($this->getTweetText(), $user->getUsername(), date('h:i'), $group->getName());
			
			$this->response->appendLine('Tweeting: "' . $tweet . '"');
			$this->response->flushContent();
			
			$this->getTwitter()->post('statuses/update', array(
				'status'	=> $tweet
			));
		}
	}
	
	public function usageAction()
	{
		
	}
	
	/**
	 * Get Twitter object
	 * 
	 * @return TwitterOAuth
	 */
	protected function getTwitter()
	{
		if (!$this->_twitter) {
			$this->_twitter = new TwitterOAuth(LSF_Config::get('twitter_consumer_key'), LSF_Config::get('twitter_consumer_secret'),
				LSF_Config::get('brewbot_key'), LSF_Config::get('brewbot_secret'));
		}
		
		return $this->_twitter;
	}
	
	/**
	 * Get the text for the tweet
	 * 
	 * @return string
	 */
	private function getTweetText()
	{
		return '@%1$s It\'s your turn to make the %2$s brew the %3$s team.';
	}
}
