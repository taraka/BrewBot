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
		$groupList = $this->getUser()->getGroupList();
		
		$this->view->groupCount = count($groupList); 
	}
}
