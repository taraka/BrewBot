<?php

/**
 *
 *
 * @package
 * $Id$
 */

class Form_{$formName} extends LSF_Form
{
	/**
	 * Add form fields
	 *
	 * @return void
	 */
	protected function initForm()
	{
		/**
		 * Example:
		
		$element = new LSF_Form_Element();
		$this->addElement($element->setName('example')
			->setLabel('Example')
		);
		
		*/
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
