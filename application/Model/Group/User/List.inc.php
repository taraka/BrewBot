<?php

class Model_Group_User_List extends LSF_Model_List
{
	public function __construct()
	{
		parent::__construct('GroupUsers');
	}
	
	/**
	 * Load a list of users that are in agroup
	 * 
	 * @param int $userId
	 * @param bool include optout
	 */
	public function load($groupId, $includeOptOut = false)
	{
		$result = $this->find(array('group_id' => (int)$groupId));
		
		while ($row = $result->fetch())
		{
			$groupUser = new Model_Group_User();
			if ($groupUser->inject($row)) {
				$this->addItem($groupUser);
			} 
		}
	}
}