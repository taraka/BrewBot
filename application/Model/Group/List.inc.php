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
}