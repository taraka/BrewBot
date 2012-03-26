<?php

class Model_User extends LSF_DB_ActiveRecord_Model
{
	public function __construct()
	{
		parent::__construct('Users');
	}
	
	/**
	 * Loads the user by there username
	 * 
	 * @param string $username
	 * @return bool
	 */
	public function loadByUsername($username)
	{
		$result = $this->find(array('username' => $username), array('limit' => 1));
		
		if ($row = $result->fetch()) {
			return $this->inject($row);
		}
		
		return false;
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
	
	/**
	 * Returns the group list for the user
	 * 
	 * @return Model_User_Group_List
	 */
	public function getGroupList()
	{
		$groupList = new Model_User_Group_List();
		$groupList->load($this->getId());
		
		return $groupList;
	}
}