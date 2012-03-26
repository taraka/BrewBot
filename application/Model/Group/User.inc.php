<?php

class Model_Group_User extends LSF_DB_ActiveRecord_Model
{
	public function __construct()
	{
		parent::__construct('GroupUsers');
	}
	
	/**
	 * Sets the user Id
	 * 
	 * @param int userId
	 * @return void
	 */
	public function setUserId($userId)
	{
		$this->user_id = $userId;
	}
	
	/**
	 * Returns the userId
	 * 
	 * @return string
	 */
	public function getUserId()
	{
		return $this->user_id;
	}
	
	/**
	 * Sets the group Id
	 * 
	 * @param int groupId
	 * @return void
	 */
	public function setGroupId($groupId)
	{
		$this->group_id = $groupId;
	}
	
	/**
	 * Returns the groupId
	 * 
	 * @return string
	 */
	public function getGroupId()
	{
		return $this->group_id;
	}
	
	/**
	 * Returns the group object for this link
	 * 
	 * @return Model_Group
	 */
	public function getGroup()
	{
		$group = new Model_Group();
		$group->load($this->getGroupId());
		return $group;
	}

	/**
	 * Returns the user object for this link
	 * 
	 * @return Model_Group
	 */
	public function getUser()
	{
		$user = new Model_User();
		$user->load($this->getUserId());
		return $user;
	}
}