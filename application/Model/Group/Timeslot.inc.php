<?php

class Model_Group_Timeslot extends LSF_DB_ActiveRecord_Model
{
	public function __construct()
	{
		parent::__construct('GroupTimeslots');
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
	 * Sets the timeslot Id
	 * 
	 * @param Model_Timeslot $timeslot
	 * @return void
	 */
	public function setTimeslot(Model_Timeslot $timeslot)
	{
		$this->timeslot = $timeslot->getTimeslot();
	}
	
	/**
	 * Returns the userId
	 * 
	 * @return Model_Timeslot
	 */
	public function getTimeslot()
	{
		$timeslot = new Model_Timeslot();
		$timeslot->setTimeslot((int)$this->timeslot);
		return $timeslot;
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
}