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

class LSF_View_Basic extends LSF_View_Base
{
	private
		$_variables = array();
		
	public function __construct()
	{
		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#current()
	 */
	public function current()
	{
		return current($this->_variables);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#key()
	 */
	public function key()
	{
		return key($this->_variables);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#valid()
	 */
	public function valid()
	{
		return $this->current !== false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#next()
	 */
	public function next()
	{
		return next($this->_variables);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#rewind()
	 */
	public function rewind()
	{
		reset($this->_variables);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess#offsetExists()
	 */
	public function offsetExists($offset)
	{
		return isset($this->_variables[$offset]);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess#offsetGet()
	 */
	public function offsetGet($offset)
	{
		return $this->_variables[$offset];
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess#offsetSet()
	 */
	public function offsetSet($offset, $value)
	{
		$this->_variables[$offset] = $value;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess#offsetUnset()
	 */
	public function offsetUnset($offset)
	{
		unset($this->_variables[$offset]);
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
	 * Get the varaibles for this view object
	 * 
	 * @return array
	 */
	public function getVariables()
	{
		return $this->_variables;
	}
	
	public function __destruct() {}
}
