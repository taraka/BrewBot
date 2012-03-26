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
	public function getUserList()
	{
		$groupList = new Model_Group_User_List();
		$groupList->load($this->getId());
		
		return $groupList;
	}
}