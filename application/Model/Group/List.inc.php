<?php

class Model_Group_List extends LSF_Model_List
{
	public function __construct()
	{
		parent::__construct('Groups');
	}
	
	/**
	 * Load all the groups
	 * 
	 * @return void
	 */
	public function load()
	{
		$result = $this->find();
		
		while ($row = $result->fetch())
		{
			$group = new Model_Group();
			if ($group->inject($row)) {
				$this->addItem($group);
			} 
		}
	}
	
	/**
	 * Load groups for a timeslot
	 * 
	 * @return void
	 */
	public function loadGroupsWithTimeslot(Model_Timeslot $timeslot)
	{
		$result = $this->getDataSource()->prepareAndExecute("
			SELECT g.* FROM Groups g
			INNER JOIN GroupTimeslots gt ON
				g.id = gt.group_id
			WHERE timeslot = ?
		", array($timeslot->getTimeslot()));
		
		while ($row = $result->fetch())
		{
			$group = new Model_Group();
			if ($group->inject($row)) {
				$this->addItem($group);
			} 
		}
	}
}