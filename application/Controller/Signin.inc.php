<?php

require (LSF_Application::getApplicationPath() . '/../Ext/twitteroauth/twitteroauth.php');

/**
 *
 *
 * @package
 * $Id$
 */

class Controller_Signin extends LSF_Controller
{
	/**
	 * Default index action
	 *
	 * @return void
	 */
	protected function indexAction()
	{
		
	}
	
	/**
	 * Perform the oauth signin
	 * 
	 * @return void
	 */
	protected function doAction()
	{
		LSF_Session::GetSession()->startSession();
		
		$oauth = new TwitterOAuth(LSF_Config::get('twitter_consumer_key'), LSF_Config::get('twitter_consumer_secret'));
		
		$requestToken = $oauth->getRequestToken('http://' . $this->getRequest()->gethostname() . '/signin/complete/');
		
		LSF_Session::GetSession()->oauth_token = $requestToken['oauth_token'];
		LSF_Session::GetSession()->oauth_token_secret = $requestToken['oauth_token_secret'];
		
		if ($oauth->http_code == 200) {
			$this->redirectUrl($oauth->getAuthorizeURL(LSF_Session::GetSession()->oauth_token));
		}
		else {
			$this->view->failed = true;
		}
	}
	
	/**
	 * Finish the auth
	 * 
	 * @return void
	 */
	protected function completeAction()
	{
		if ($this->getRequest()->getGetVar('oauth_verifier') && $this->getRequest()->getGetVar('oauth_token') && LSF_Session::GetSession()->oauth_token_secret)
		{
			$oauth = new TwitterOAuth(LSF_Config::get('twitter_consumer_key'), LSF_Config::get('twitter_consumer_secret'),
				LSF_Session::GetSession()->oauth_token, LSF_Session::GetSession()->oauth_token_secret);
				
			$accessToken = $oauth->getAccessToken($this->getRequest()->getGetVar('oauth_verifier'));
			unset(LSF_Session::GetSession()->oauth_token);
			unset(LSF_Session::GetSession()->oauth_token_secret);
			
			LSF_Session::GetSession()->access_token = $accessToken;
			
			if ($accessToken) {
				$this->redirect();
			}
		}
		
		$this->redirect('signin', 'fail');
	}
	
	/**
	 * Twitter authentication fail action
	 * 
	 * @return void
	 */
	protected function failAction()
	{
		
	}
}
