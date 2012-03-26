<?php

/**
 * Form element email validator
 *
 * @package LSF
 * @subpackage Base
 * @version $Id$
 * @copyright 2011
 */

class LSF_Form_Element_Validator_Email implements LSF_Form_Element_Validator
{
	private
		$_errorMessage = 'This must be a valid email address';
	
	public function __construct($errorMessage=null)
	{
		if (isset($errorMessage) && is_string($errorMessage)) {
			$this->_errorMessage = $errorMessage;
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see LSF_Form_Element_Validator::validate()
	 */
	public function validate($value)
	{
		$validator = new LSF_Utils_Validator_String();
		return empty($value) ? true : $validator->validateEmail($value);
		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see LSF_Form_Element_Validator::getErrorMessage()
	 */
	public function getErrorMessage()
	{
		return $this->_errorMessage;
	}
}
