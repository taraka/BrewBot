<?php

/**
 *
 *
 * @package
 * $Id$
 */

class Controller_Group extends Controller_TwitterAuth
{
	/**
	 * Default index action
	 *
	 * @return void
	 */
	protected function indexAction()
	{
		
	}
	
	/**
	 * Create group action
	 *
	 * @return void
	 */
	protected function createAction()
	{
		$form = new Form_Group();
		$form->setElementValue('members', $this->getUser()->getUsername());
		$this->view->form = $form->render();
		
		if ($form->formValidated())
		{
			$this->processForm($form, new Model_Group());
			$this->redirect();
		}
	}
	
	/**
	 * Edit group action
	 *
	 * @return void
	 */
	protected function editAction()
	{
		$group = new Model_Group();
		
		if ($group->load($this->getRequest()->getParam(0)))
		{
			$form = new Form_Group();
			$form->setElementValue('name', $group->getName());
			
			$groupUser = $group->getUserList();
			$membersValue = "";

			foreach ($groupUser->getIterator() as $link)
			{
				$membersValue .= $link->getUser()->getUsername() . "\n";
			}
			
			$form->setElementValue('members', $membersValue);
			$this->view->form = $form->render();
			
			if ($form->formValidated())
			{
				$this->processForm($form, $group);
				$this->redirect();
			}
		}
		else {
			$this->view->invalid = true;
		}
	}
	
	/**
	 * Process the form
	 * 
	 */
	protected function processForm(Form_Group $form, Model_Group $group)
	{
		$group->setName($form->getElementValue('name'));
		if ($group->save()->success())
		{
			$group->deleteMembers();
			
			$matches = preg_split('/[\n\r]+/', $form->getElementValue('members'));
			
			foreach ($matches as $username)
			{
				if ($username = trim($username))
				{
					$user = new Model_User();
					
					if (!$user->loadByUsername($username))
					{
						$user->setUsername($username);
						$user->save();
					}
				}
				
				$groupUser = new Model_Group_User();
				$groupUser->setGroupId($group->getId());
				$groupUser->setUserId($user->getId());
				$groupUser->save();
			}
		}
	}
}
