<?php

/**
 *
 *
 * @package BrewBot
 * $Id: Default.inc.php 6 2009-08-26 11:11:40Z sam $
 */

class Controller_Default extends Controller_TwitterAuth
{
	/**
	 * Default index action
	 */
	protected function indexAction()
	{
		var_dump($this->getTwitter()->get('statuses/home_timeline'));
	}
}
