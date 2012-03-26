<?php

/**
 * Form element validator interface
 *
 * @package LSF
 * @subpackage Base
 * @version $Id$
 * @copyright 2011
 */

interface LSF_Form_Element_Validator
{
	/**
	 * Validates the form element
	 * 
	 * @param mixed $value
	 */
	public function validate($value);
	
	/**
	 * Get the error message
	 */
	public function getErrorMessage();
}
