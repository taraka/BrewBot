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

class LSF_View_Node_List implements Iterator, Countable
{
	private
		$_parent,
		$_name,
		$_nodes = array(),
		$_forceList = false,
		$_position;
		
	public function __construct($parent=null, $name=false)
	{
		$this->_name = $name;
		$this->_parent = $parent;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#current()
	 */
	public function current()
	{
		return current($this->_nodes);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#key()
	 */
	public function key()
	{
		return key($this->_nodes);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#valid()
	 */
	public function valid()
	{
		return current($this->_nodes) !== false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#next()
	 */
	public function next()
	{
		return next($this->_nodes);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#rewind()
	 */
	public function rewind()
	{
		reset($this->_nodes);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Countable#count()
	 */
	public function count()
	{
		return count($this->_nodes);
	}
	
	/**
	 * Adds a new node to this list
	 * 
	 * @return LSF_View_Node
	 */
	public function addNode($index=null)
	{
		$node = new LSF_View_Node($this, $this->_name);
		if ($index === null) {
			$this->_nodes[] = $node;
		}
		else {
			$this->_nodes[$index] = $node;
		}
		
		return $node;
	}
	
	/**
	 * Gets the firsts node in the list
	 * @return LSF_View_Node
	 */
	public function getFirstNode($index=null)
	{
		if (count($this->_nodes) == 0) {
			$this->addNode($index);
		}
		
		foreach ($this->_nodes as $node) {
			return $node;
		}
		
		return false;
	}
	
	/**
	 * Marks that during view this will be a lists
	 */
	public function forceList()
	{
		$this->_forceList = true;
	}
	
	/**
	 * Checks if this should be rendered as a list
	 */
	public function isList()
	{
		return count($this) > 1 || $this->_forceList;
	}
	
	/**
	 * Sets a value to the offset
	 * @param $offset
	 * @param $value
	 */
	public function __set($offset, $value)
	{
		$node = $this->getFirstNode();
		
		if ($node) {
			$node[$offset] = $value;
		}
	}
	
	/**
	 * gets the value of an a offset
	 * @param unknown_type $offset
	 */
	public function __get($offset)
	{
		$node = $this->getFirstNode();
		
		if ($node) {
			return $node->{$offset};
		}
	}
	
	/**
	 * Deferes calls to the child nodes
	 * @param string $func
	 * @param aarray $args
	 */
	public function __call($func, $args)
	{
		$node = $this->getFirstNode();
		
		if ($node) {
			return call_user_func_array(array($node, $func), $args);
		}
	}
	
	/**
	 * Returns string representation of this object
	 */
	public function __toString()
	{
		$node = $this->getFirstNode();
		
		if ($node) {
			return $node->__toString();
		}
	}
	
	/**
	 * Sets the value of the node
	 * @param string $value
	 */
	public function setValue($value)
	{
		if ($value instanceof LSF_View_Node) {
			$this->_nodes = array($value);
		}
		else
		{
			$index = null;
			if (is_array($value) && count($value))
			{
				$valueCopy = $value;
				$index = array_shift(array_keys($valueCopy));
			}
			$this->getFirstNode($index)->setValue($value);	
		}
	}
	
	/**
	 * Returns the parent object
	 * @return $parent
	 */
	public function parent()
	{
		return $this->_parent;
	}
	
	public function __destruct() {}
}
