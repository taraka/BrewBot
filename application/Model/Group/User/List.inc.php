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
	 * @param int $groupId
	 * @param bool include optout
	 */
	public function load($groupId, $includeOptOut=false)
	{
		$joins = $where = "";
		
		if (!$includeOptOut)
		{
			$joins = "INNER JOIN Users u ON u.id = gu.user_id";
			$where = "AND (u.optOut != 1 OR u.optOut IS NULL)";
		}
		
		$result = $this->getDataSource()->prepareAndExecute("
			SELECT gu.* FROM GroupUsers gu 
			$joins
			WHERE gu.group_id = ?
			$where
			ORDER BY gu.ordinal
		", array($groupId));
		
		while ($row = $result->fetch())
		{
			$groupUser = new Model_Group_User();
			if ($groupUser->inject($row)) {
				$this->addItem($groupUser);
			} 
		}
	}
}