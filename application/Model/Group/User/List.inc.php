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
	public function load($groupId, $includeOptOut=false)
	{
		$sql = "";
		
		if (!$includeOptOut) {
			$sql = "INNER JOIN Users u ON u.id = gu.user_id
			WHERE u.optOut != 1 OR u.optOut IS NULL";
		}
		
		$result = $this->getDataSource()->prepareAndExecute("
			SELECT gu.* FROM GroupUsers gu 
			$sql
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