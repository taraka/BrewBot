<?php

class Model_Group_Timeslot_List extends LSF_Model_List
{
	public function __construct()
	{
		parent::__construct('GroupTimeslots');
	}
	
	/**
	 * Load a list of timeslots that are for a agroup
	 * 
	 * @param int $groupId
	 */
	public function load($groupId)
	{
		$result = $this->getDataSource()->prepareAndExecute("
			SELECT gt.* FROM GroupTimeslots gt 
			ORDER BY gt.timeslot
		", array($groupId));
		
		while ($row = $result->fetch())
		{
			$groupTimeslot = new Model_Group_Timeslot();
			if ($groupTimeslot->inject($row)) {
				$this->addItem($groupTimeslot);
			} 
		}
	}
}