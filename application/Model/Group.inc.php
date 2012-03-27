<?php

class Model_Group extends LSF_DB_ActiveRecord_Model
{
	public function __construct()
	{
		parent::__construct('Groups');
	}
	
	/**
	 * Sets the name
	 * 
	 * @param string $name
	 * @return void
	 */
	public function setName($name)
	{
		$this->name = $name;
	}
	
	/**
	 * Returns the name
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Sets the lst user Id
	 * 
	 * @param int userId
	 * @return void
	 */
	public function setLastUserId($userId)
	{
		$this->last_user_id = $userId;
	}
	
	/**
	 * Returns the last userId
	 * 
	 * @return int
	 */
	public function getLastUserId()
	{
		return $this->last_user_id;
	}
	
	/**
	 * Deletes all user links to this group
	 * 
	 * @return void
	 */
	public function deleteMembers()
	{
		$this->getDataSource()->prepareAndExecute("DELETE FROM GroupUsers WHERE group_id = ?", array($this->getId()));
	}
		
	/**
	 * Returns the  group users list for the group
	 * 
	 * @return Model_User_Group_List
	 */
	public function getUserList($includeOptOut=false)
	{
		$userList = new Model_Group_User_List();
		$userList->load($this->getId(), $includeOptOut);
		
		return $userList;
	}
	
	/**
	 * Returns a random available user for the group
	 * @return Model_User
	 * 
	 */
	public function getRandomUser()
	{
		$userList = $this->getUserList();
		
		foreach ($userList->getIterator() as $key => $userGroup)
		{
			if ($userGroup->getUser()->isOptedOut()) {
				unset($userList[$key]);
			}
		}
		
		return $userList->random()->getUser(); 
	}
	
	/**
	 * Mix up the order of users in this group
	 * 
	 * @return void
	 */
	public function randomize()
	{
		$userList = $this->getUserList(true);
		$userList->shuffle();
		
		$counter = 0;
		foreach ($userList->getIterator() as $userGroup)
		{
			$userGroup->setOrdinal($counter++);
			$userGroup->save();
		}
	}
	
	/**
	 * Returns a random available user for the group
	 * 
	 * @return Model_User
	 */
	public function getNextUser()
	{
		$userList = $this->getUserList();
		$foundLast = false;
		
		foreach ($userList->getIterator() as $key => $userGroup)
		{
			if ($foundLast) {
				return $userGroup->getUser();
			}
			
			if ($userGroup->getUserId() == $this->getLastUserId()) {
				$foundLast = true;
			}
		}
		
		return $userList->first()->getUser(); 
	}
}