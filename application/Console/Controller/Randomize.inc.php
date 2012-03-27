<?php

/**
 *
 *
 * @package 
 * $Id$
 */

class Console_Controller_Randomize extends LSF_Console_Controller
{	
	protected function indexAction()
	{
		$this->response->appendLine('Mixing it up a little');
		$this->response->appendLine('');
		
		$groups = new Model_Group_List();
		$groups->load();
		
		foreach ($groups->getIterator() as $group)
		{
			$this->response->appendLine("\tRantomizing: " . $group->getName());
			$this->response->flushContent();
			$group->randomize();
		}
	}
	
	public function usageAction()
	{
		
	}
}
