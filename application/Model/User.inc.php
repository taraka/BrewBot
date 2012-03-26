<?php

class Model_User extends LSF_DB_ActiveRecord_Model
{
	public function __construct()
	{
		parent::__construct('Users');
	}
	
	/**
	 * Sets the username
	 * 
	 * @param string username
	 * @return void
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}
	
	/**
	 * Returns the username
	 * 
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}
}