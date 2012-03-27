<?php

/**
 *
 *
 * @package
 * $Id$
 */

class Form_Group extends LSF_Form
{
	/**
	 * Add form fields
	 *
	 * @return void
	 */
	protected function initForm()
	{
		$element = new LSF_Form_Element();
		$this->addElement($element->setName('name')
			->setLabel('Name')
		);
		
		$element = new LSF_Form_Element();
		$this->addElement($element->setName('members')
			->setElement('textarea')
			->setLabel('Members')
			->setHint('New line seperated list of twitter handels')
		);
		
		$element = new LSF_Form_Element();
		$this->addElement($element->setName('timeslots')
			->setHidden()
		);
	}
	
	/**
	 * Validate the form
	 *
	 * @return LSF_Validation_Result
	 */
	public function formValidate()
	{
		$return = parent::formValidate();
		
		return $return;
	}
}
