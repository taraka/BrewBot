<?php

/**
 * Base class for view data store.
 *
 * Views should provide data storage and an interface between the controller and presenters.
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_View_Node extends LSF_View_Base
{
	private
		$_name = '',
		$_parent,
		$_children = array(),
		$_attributes,
		$_value;
		
	private
		$_position,
		$_keyMap;
		
	public function __construct($parent=null, $name=null)
	{
		$this->_parent = $parent;
		$this->_name = $name;
		$this->_attributes = new LSF_View_Node_Attributes();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#current()
	 */
	public function current()
	{
		return $this->_children[$this->key()];
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#key()
	 */
	public function key()
	{
		$this->buildKeyMap();
		return $this->_keyMap[$this->_position];
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#valid()
	 */
	public function valid()
	{
		$this->buildKeyMap();
		return isset($this->_keyMap[$this->_position]);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#next()
	 */
	public function next()
	{
		$this->_position++;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#rewind()
	 */
	public function rewind()
	{
		$this->_position = 0;
	}
	
	/**
	 * Sets the array keys of the children to $_keyMap for quick access
	 */
	private function buildKeyMap()
	{
		$this->_keyMap = array_keys($this->_children);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess#offsetExists()
	 */
	public function offsetExists($offset)
	{
		return isset($this->_children[$offset]) && count($this->_children[$offset]);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess#offsetGet()
	 */
	public function offsetGet($offset)
	{
		if (isset($this->_children[$offset])) {
			return $this->_children[$offset];
		}
		else
		{
			$this->_children[$offset] = new LSF_View_Node_List($this, $offset);
			return $this->_children[$offset];
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess#offsetSet()
	 */
	public function offsetSet($offset, $value)
	{
		$node = $this->offsetGet($offset);
		$node->setValue($value);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess#offsetUnset()
	 */
	public function offsetUnset($offset)
	{
		unset($this->_children[$offset]);
	}
	
	/**
	 * Sets a value ti this view
	 * @param mixed $value
	 * @throws LSF_Exception_View
	 */
	public function setValue($value)
	{
		if (is_array($value))
		{
			foreach ($value as $key => $valueData)
			{
				if (is_numeric($key))
				{
					if ($this->_parent && (isset($this->_value) || count($this->_children)))
					{
						$node = $this->_parent->addNode($key);
						$node->setValue($valueData);
					}
					else
					{
						$this->_parent->forceList();
						$this->setValue($valueData);
					}
				}
				else {
					$this->offsetSet($key, $valueData);
				}
			}
		}
		else
		{
			if (count($this->_children)) {
				throw new LSF_Exception_View('Value can not be set to a LSF_View_Node which has child nodes (Call removeChildren)');
			}
		
			/*
			 * Added for php 5.1 support
			 */
			if (is_object($value) && method_exists($value, '__toString')) {
				$value = $value->__toString();
			}
			
			$this->_value = $value;
		}
	}
	
	/**
	 * Returns the value of this view
	 * @return $value
	 */
	public function getValue()
	{
		return $this->_value;
	}
	
	/**
	 * Get values as an object
	 * @param string $offset
	 * @return LSF_View_Node_List
	 */
	public function __get($offset)
	{
		return $this->offsetGet($offset);
	}
	
	/**
	 * Set value to an object
	 * @param string $offset
	 * @param mixed $value
	 */
	public function __set($offset, $value)
	{
		return $this->offsetSet($offset, $value);
	}

	/**
	 * Checks if an offset exists
	 * @param string $offset
	 * @return bool
	 */
	public function __isset($offset)
	{
		return $this->offsetExists($offset);
	}
	
	/**
	 * Retreive the parent object
	 */
	public function parent()
	{
		if ($this->_parent) {
			return $this->_parent;
		}
	}
	
	/**
	 * Get the attributes object
	 * @return LSF_View_Node_Attributes $attributes
	 */
	public function attributes()
	{
		return $this->_attributes;
	}
	
	/**
	 * Get the name
	 * @return string $name
	 */
	public function getName()
	{
		return $this->_name;
	}
	
	/**
	 * Gets the string value of this object
	 * @return string $value
	 */
	public function __toString()
	{
		return (string)$this->getValue();
	}
	
	public function __destruct() {}
}
