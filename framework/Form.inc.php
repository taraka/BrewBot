<?php

/**
 * Base form class providing functionality for validating and runnign forms
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

abstract class LSF_Form extends LSF_Presentable
{
	private
		$_elements = array(),
		$_formInputs = array(),
		$_method = 'post',
		$_validated = false;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->initForm();
		$this->getInputs();
	}
	
	/**
	 * Adds variables to the form and runs the validation if submitted
	 *
	 * @return $this
	 */
	public function render()
	{
		$errorCount = 0;
		
		if ($this->formSubmitted())
		{
			$result = $this->formValidate();
			
			if (!$result->success())
			{
				foreach ($result->getErrors() as $element => $error)
				{
					$this->_elements[$element]->setError($error);
					
					if ($error) {
						$errorCount++;
					}
				}
			}
			else {
				$this->_validated = true;
			}
		}
		
		$this->view->elements = $this->getElementsArray();
		$this->view->errorCount = $errorCount;
		return $this;
	}
	
	/**
	 * Get an array of the element data
	 *
	 * @return array $elements
	 */
	public function getElements()
	{
		return $this->_elements;
	}
	
	/**
	 * Fetch an element by name
	 * @param LSF_Form_Element
	 */
	public function getElement($name)
	{
		return isset($this->_elements[$name]) ? $this->_elements[$name] : false;
	}
	
	/**
	 * Returns the value of a given element
	 * @param string $name
	 */
	public function getElementValue($name)
	{
		if ($element = $this->getElement($name)) {
			return $element->getValue();
		}
		
		return null;
	}
	
	/**
	 * Sets an element Value
	 * @param string $name
	 * @param string $value
	 */
	public function setElementValue($name, $value)
	{
		if (isset($this->_elements[$name])) {
			$this->_elements[$name]->setValue($value);
		}
	}
	
	/**
	 * Mark an element as required
	 * @param string $name
	 * @param bool $value
	 */
	public function setElementRequired($name, $value)
	{
		if (isset($this->_elements[$name])) {
			$this->_elements[$name]->setRequired($value);
		}
	}
	
	/**
	 * See if an element is required
	 * @param string $name
	 * @return bool $value
	 */
	public function getElementRequired($name)
	{
		if (isset($this->_elements[$name])) {
			return $this->_elements[$name]->getRequired();
		}
		
		return null;
	}
	
	/**
	 * Sets up inputs for the form depending on $this->_method
	 *
	 * @return void
	 */
	private function getInputs()
	{
		switch ($this->_method)
		{
			case 'get':
				$this->_formInputs = $this->getRequest()->getGetVars();
				break;
				
			case 'post':
				$this->_formInputs = $this->getRequest()->getPostVars();
				break;
		}
	}
	
	/**
	 * Validate the form
	 *
	 * @return LSF_Validation_Result
	 */
	protected function formValidate()
	{
		$return = new LSF_Validation_Result();

		foreach ($this->_elements as $element)
		{
			if ($element->isRequired() && !$element->getValue()) {
				$return->addError($element->getName(), 'This field is required');
			}
			
			foreach ($element->getValidators() as $validator)
			{
				if (!$validator->validate($element->getValue())) {
					$return->addError($element->getName(), $validator->getErrorMessage());
				}
			}
		}
		
		return $return;
	}
	
	/**
	 * Checks for form validity
	 *
	 * @return bool
	 */
	public function formValidated()
	{
		return $this->_validated;
	}
	
	/**
	 * Returns true if the form has been submitted and populates the element values from the inputs
	 *
	 * @return bool
	 */
	public function formSubmitted()
	{
		$return = false;
		
		foreach ($this->_elements as $key => $element)
		{
			if ($element->getName() && isset($this->_formInputs[$element->getName()]))
			{
				/**
				 * Parse date inputs from smarty html_select_date
				 */
				if ($element->getType() == 'date')
				{
					if (!empty($this->_formInputs[$element->getName()]['Date_Day']) && !empty($this->_formInputs[$element->getName()]['Date_Month']) && !empty($this->_formInputs[$element->getName()]['Date_Year'])) {
						$element->setValue(mktime(0, 0, 0, $this->_formInputs[$element->getName()]['Date_Month'], $this->_formInputs[$element->getName()]['Date_Day'], $this->_formInputs[$element->getName()]['Date_Year']));
					}
					else {
						$element->setValue(false);
					}
				}
				else {
					$element->setValue($this->_formInputs[$element->getName()]);
				}
				
				
				$return = true;
			}
		}
		
		return $return;
	}
	
	/**
	 * Set the method for form input
	 *
	 * @param string $method ('get' | 'post')
	 * @return void
	 */
	protected function setMethod($method)
	{
		$this->_method = $method == 'get' ? 'get' : 'post';
	}
	
	/**
	 * Add a new element to the form
	 *
	 * @param array $element
	 * @return bool
	 */
	protected function addElement(LSF_Form_Element $element)
	{
		$this->_elements[$element->getName()] = $element;
		return true;
	}
	
	/**
	 * Initialise the form from an xml file
	 *
	 * @param string $path
	 * @throws LSF_Exception_FileIO
	 * @return bool
	 */
	protected function initFormFromXML($path)
	{
		$path = LSF_Application::getApplicationPath() . '/' . $path;
		
		if (!file_exists($path)) {
			throw new LSF_Exception_FileIO("Form XML file couldn't be located: $path");
		}
		
		$xml = new SimpleXMLElement(file_get_contents($path));
		
		foreach ($xml->field as $field)
		{
			if ($field->name)
			{
				$elementType = (string)$field->element;
				$options = array();
				
				if ($elementType == 'select')
				{
					foreach ($field->options->option as $option)
					{
						$options[] = array(
							'value' => (string)$option->attributes()->value,
							'label' => (string)$option
						);
					}
				}
				
				$element = new LSF_Form_Element();
				$this->addElement($element
					->setName((string)$field->name)
					->setElement($elementType)
					->setType((string)$field->type)
					->setOptions($options)
					->setLabel((string)$field->label)
					->setHint((string)$field->hint)
					->setRequired((string)$field->required)
					->setValue((string)$field->value)
					->setHidden((string)$field->hidden)
				);
			}
		}
		
		return true;
	}
	
	/**
	 * Initialize the form
	 *
	 * @return void
	 */
	abstract protected function initForm();
	
	/**
	 * Returns the current template name
	 *
	 * @return string
	 */
	protected function getTemplateName()
	{
		return 'Form/' . parent::getTemplateName();
	}
	
	/**
	 * Return the elements as arrays ready to be added to the view
	 *
	 * @return array
	 */
	public function getElementsArray()
	{
		$return = array();
		
		foreach ($this->_elements as $element)
		{
			$return[$element->getName()] = array(
				'element' 		=> $element->getElement(),
				'type' 			=> $element->getType(),
				'name' 			=> $element->getName(),
				'label'			=> $element->getLabel(),
				'hint'			=> $element->getHint(),
				'placeholder'	=> $element->getPlaceholder(),
				'value'			=> $element->getValue(),
				'required'		=> $element->isRequired(),
				'hidden'		=> $element->isHidden(),
				'options'		=> $element->getOptions(),
				'error'			=> $element->getError(),
			);
		}
		
		return $return;
	}
	
	public function __destruct() {}
}
