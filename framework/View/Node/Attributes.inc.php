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

class LSF_View_Node_Attributes implements ArrayAccess, Iterator
{
	private
		$_attributes = array(),
		$_position,
		$_keyMap;
		
		
	public function __construct() {}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#current()
	 */
	public function current()
	{
		return $this->_attributes[$this->key()];
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
		$this->_keyMap = array_keys($this->_attributes);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess#offsetExists()
	 */
	public function offsetExists($offset)
	{
		return isset($this->_attributes[$offset]);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess#offsetGet()
	 */
	public function offsetGet($offset)
	{
		return $this->_attributes[$offset];
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess#offsetSet()
	 */
	public function offsetSet($offset, $value)
	{
		$this->_attributes[$offset] = (string)$value;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess#offsetUnset()
	 */
	public function offsetUnset($offset)
	{
		unset($this->_attributes[$offset]);
	}
	
	/**
	 * Get the value of an attribute
	 * @param string $offset
	 * @return string $value
	 */
	public function __get($offset)
	{
		return $this->offsetGet($offset);
	}
	
	/**
	 * Get the value of an attribute
	 * @param string $offset
	 * @param string $value
	 */
	public function __set($offset, $value)
	{
		return $this->offsetSet($offset, $value);
	}
	
	/**
	 * Check if an object exists
	 * @param string $offset
	 * @return bool $exists
	 */
	public function __isset($offset)
	{
		return $this->offsetExists($offset);
	}
	
	public function __destruct() {}
}
