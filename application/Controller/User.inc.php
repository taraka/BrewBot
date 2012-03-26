<?php

/**
 *
 *
 * @package
 * $Id$
 */

class Controller_User extends Controller_TwitterAuth
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
	 * Set the optout flag for the current user
	 *
	 * @return void
	 */
	protected function optoutAction()
	{
		$this->getUser()->setOptOut();
		$this->getUser()->save();
		$this->redirect();
	}
	
	/**
	 *  Clear the optout flag for the current user
	 *
	 * @return void
	 */
	protected function optinAction()
	{
		$this->getUser()->setOptOut(false);
		$this->getUser()->save();
		$this->redirect();
	}
}
