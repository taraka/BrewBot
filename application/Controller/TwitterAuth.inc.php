<?php

require (LSF_Application::getApplicationPath() . '/../Ext/twitteroauth/twitteroauth.php');

/**
 *
 *
 * @package BrewBot
 * $Id: Default.inc.php 6 2009-08-26 11:11:40Z sam $
 */

abstract class Controller_TwitterAuth extends LSF_Controller
{
	private
		$_twitter,
		$_user;
	
	public function start()
	{
		try {
			$this->getTwitter(); 
			
			return parent::start();
		}
		catch(Exception_TwitterAuth $e) {
			$this->authFailed();
		}
	}
	
	/**
	 * Get Twitter object
	 * 
	 * @throws Exception_TwitterAuth
	 * @return TwitterOAuth
	 */
	protected function getTwitter()
	{
		if (!isset(LSF_Session::GetSession()->access_token)) {
			throw new Exception_TwitterAuth('No twitter credentials');
		}
		
		if (!$this->_twitter) {
			$this->_twitter = new TwitterOAuth(LSF_Config::get('twitter_consumer_key'), LSF_Config::get('twitter_consumer_secret'),
				LSF_Session::GetSession()->access_token['oauth_token'], LSF_Session::GetSession()->access_token['oauth_token_secret']);
		}
		
		return $this->_twitter;
	}
	
	/**
	 * Gets the twitter username
	 * 
	 * @return string
	 */
	private function getTwitterUsername()
	{
		return isset(LSF_Session::GetSession()->access_token['screen_name']) ? LSF_Session::GetSession()->access_token['screen_name'] : null;
	}
	
	/**
	 * Returns a loaded user object for the authenticated user
	 * 
	 * @return Model_User
	 */
	protected function getUser()
	{
		if (!$this->_user)
		{
			$this->_user = new Model_User();
			
			if (!$this->_user->loadByUsername($this->getTwitterUsername()))
			{
				$this->_user->setUsername($this->getTwitterUsername());
				$this->_user->save();
			}
		}
		
		return $this->_user;
	}
	
	/**
	 * Failed auth action
	 * 
	 * @return void;
	 */
	private function authFailed()
	{
		$this->redirect('signin');
	}
}
