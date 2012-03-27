<?php

class Model_BrewBot
{
	private
		$_observer,
		$_twitter;
		
	public function __construct(Interface_Observer $observer=null)
	{
		$this->_observer = $observer;
	}
	
	/**
	 * Its time for the brewbot to do its thing!
	 * 
	 * @return void
	 */
	public function timeForABrew()
	{
		$groupList = new Model_Group_List();
		$groupList->load();
		
		foreach ($groupList->getIterator() as $group)
		{
			$user = $group->getRandomUser();
			
			$tweet = sprintf($this->getTweetText(), $user->getUsername(), date('h:i'), $group->getName());
			
			$this->notify('Tweeting: "' . $tweet . '"');
			
			$this->getTwitter()->post('statuses/update', array(
				'status'	=> $tweet
			));
		}
	}
	
	/**
	 * Get Twitter object
	 * 
	 * @return TwitterOAuth
	 */
	private function getTwitter()
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
		return '@%1$s It\'s your turn to make the %2$s brew for the %3$s team.';
	}
	
	/**
	 * Notify the observer
	 * 
	 * @param unknown_type $text
	 * @return void
	 */
	public function notify($text)
	{
		if (is_object($this->_observer)) {
			$this->_observer->update($text);
		}
	}
}