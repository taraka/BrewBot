<?php

/**
 * Basic form element
 *
 * @package LSF
 * @subpackage Base
 * @version $Id$
 * @copyright 2011
 */

class LSF_Form_Element
{
	private
		$_element	= 'input',
		$_type 		= 'text',
		$_name,
		$_label,
		$_hint,
		$_placeholder,
		$_value		= false,
		$_required 	= false,
		$_hidden 	= false,
		$_options	= array(),
		$_error,
		$_validators = array();
	
	public function __construct() {}
	
	/**
	 * Set the HTML element (input, textarea, etc)
	 *
	 * @param string $element
	 * @return LSF_Base_Form_Element
	 */
	public function setElement($element)
	{
		if (is_string($element)) {
			$this->_element = $element;
		}
		
		return $this;
	}
	
	/**
	 * Returns the HTML element
	 *
	 * @return string
	 */
	public function getElement()
	{
		return $this->_element;
	}
	
	/**
	 * Set the element type
	 *
	 * @param string $type
	 * @return LSF_Base_Form_Element
	 */
	public function setType($type)
	{
		if (is_string($type)) {
			$this->_type = $type;
		}
		
		return $this;
	}
	
	/**
	 * Returns the element type
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->_type;
	}
	
	/**
	 * Set the element name
	 *
	 * @param string $name
	 * @return LSF_Base_Form_Element
	 */
	public function setName($name)
	{
		if (is_string($name)) {
			$this->_name = $name;
		}
		
		return $this;
	}
	
	/**
	 * Returns the element name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}
	
	/**
	 * Set the element label
	 *
	 * @param string $label
	 * @return LSF_Base_Form_Element
	 */
	public function setLabel($label)
	{
		if (is_string($label)) {
			$this->_label = $label;
		}
		
		return $this;
	}
	
	/**
	 * Returns the element label
	 *
	 * @return string
	 */
	public function getLabel()
	{
		return $this->_label;
	}
	
	/**
	 * Set the hint (helper) text
	 *
	 * @param string $hint
	 * @return LSF_Base_Form_Element
	 */
	public function setHint($hint)
	{
		if (is_string($hint)) {
			$this->_hint = $hint;
		}
		
		return $this;
	}
	
	/**
	 * Returns the hint (helper) text
	 *
	 * @return string
	 */
	public function getHint()
	{
		return $this->_hint;
	}
	
	/**
	 * Set the placeholder text
	 *
	 * @param string $placeholder
	 * @return LSF_Base_Form_Element
	 */
	public function setPlaceholder($placeholder)
	{
		if (is_string($placeholder)) {
			$this->_placeholder = $placeholder;
		}
		
		return $this;
	}
	
	/**
	 * Returns the placeholder text
	 *
	 * @return string
	 */
	public function getPlaceholder()
	{
		return $this->_placeholder;
	}
	
	/**
	 * Set the element value
	 *
	 * @param mixed $value
	 * @return LSF_Base_Form_Element
	 */
	public function setValue($value)
	{
		$this->_value = $value;
		
		return $this;
	}
	
	/**
	 * Returns the element value
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->_value;
	}
	
	/**
	 * Set whether or not this element is required
	 *
	 * @param bool $required
	 * @return LSF_Base_Form_Element
	 */
	public function setRequired($required=true)
	{
		if (is_bool($required)) {
			$this->_required = $required;
		}
		
		return $this;
	}
	
	/**
	 * Returns whether or not this element is required
	 *
	 * @return bool
	 */
	public function isRequired()
	{
		return (bool)$this->_required;
	}
	
	/**
	 * Set whether or not this element should be hidden
	 *
	 * @param bool $hidden
	 * @return LSF_Base_Form_Element
	 */
	public function setHidden($hidden=true)
	{
		if (is_bool($hidden)) {
			$this->_hidden = $hidden;
		}
		
		return $this;
	}
	
	/**
	 * Returns whether or not this element is hidden
	 *
	 * @return bool
	 */
	public function isHidden()
	{
		return (bool)$this->_hidden;
	}
	
	/**
	 * Set options (for select inputs, etc)
	 *
	 * @param array $options
	 * @return LSF_Base_Form_Element
	 */
	public function setOptions(array $options)
	{
		$this->_options = $options;
		return $this;
	}
	
	/**
	 * Returns an array of options
	 *
	 * @return array
	 */
	public function getOptions()
	{
		return $this->_options;
	}
	
	/**
	 * Set an error string
	 *
	 * @param string $error
	 * @return LSF_Base_Form_Element
	 */
	public function setError($error)
	{
		if (is_string($error)) {
			$this->_error = $error;
		}
		
		return $this;
	}
	
	/**
	 * Returns an error message
	 *
	 * @return string
	 */
	public function getError()
	{
		return $this->_error;
	}
	
	/**
	 * Add a new validator to this element
	 * 
	 * @param LSF_Form_Element_Validator $validator
	 * @return $this
	 */
	public function addValidator(LSF_Form_Element_Validator $validator)
	{
		array_push($this->_validators, $validator);
		return $this;
	}
	
	/**
	 * Returns an array of LSF_Form_Element_Validator elements
	 * 
	 * @return array
	 */
	public function getValidators()
	{
		return $this->_validators;
	}
	
	public function __destruct() {}
}
