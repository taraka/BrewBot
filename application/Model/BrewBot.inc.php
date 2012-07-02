<?php

require (LSF_Application::getApplicationPath() . '/../Ext/twitteroauth/twitteroauth.php');

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
	 * It's time for the brewbot to do its thing!
	 * 
	 * @return void
	 */
	public function timeForABrew($timeslot)
	{
		if (date('N') <= 5)
		{
			$groupList = new Model_Group_List();
			$groupList->loadGroupsWithTimeslot($timeslot);
			
			foreach ($groupList->getIterator() as $group)
			{
				if ($user = $group->getNextUser())
				{
					$tweet = sprintf($this->getTweetText(), $user->getUsername(), $timeslot, $group->getName());
					
					$this->notify('Tweeting: "' . $tweet . '"');
					
					$this->getTwitter()->post('statuses/update', array(
						'status'	=> $tweet
					));
					
					$group->setLastUserId($user->getId());
					$group->save();
				}
				else {
					$this->notify('Group has no active users"');
				}
			}
		}
		else {
			$this->notify('Sorry but I don\'t work weekends.');
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