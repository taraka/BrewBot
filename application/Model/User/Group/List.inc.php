<?php

class Model_User_Group_List extends LSF_Model_List
{
	public function __construct()
	{
		parent::__construct('GroupUsers');
	}
	
	/**
	 * Load a list of groups that a user is in.
	 * 
	 * @param int $userId
	 */
	public function load($userId)
	{
		$result = $this->find(array('user_id' => (int)$userId));
		
		while ($row = $result->fetch())
		{
			$groupUser = new Model_Group_User();
			if ($groupUser->inject($row)) {
				$this->addItem($groupUser);
			} 
		}
	}
}