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
}