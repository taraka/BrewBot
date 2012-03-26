<?php

/**
 * Base class for model lists
 *
 * @package LSF
 * @subpackage Model
 * @version $Id$
 * @copyright 2010-2011
 */

abstract class LSF_Model_List extends LSF_DB_ActiveRecord_Base implements Countable, ArrayAccess
{
	private
		$_items = array();
		
	/**
	 * (non-PHPdoc)
	 * @see Countable::count()
	 */
	public function count()
	{
		return count($this->_items);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess::offsetExists()
	 */
	public function offsetExists($offset)
	{
		return isset($this->_items[$offset]);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess::offsetGet()
	 */
	public function offsetGet($offset)
	{
		return $this->offsetExists($offset) ? $this->_items[$offset] : null;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess::offsetSet()
	 */
	public function offsetSet($offset, $value)
	{
		$this->_items[$offset] = $value;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ArrayAccess::offsetUnset()
	 */
	public function offsetUnset($offset)
	{
		unset($this->_items[$offset]);
	}
	
	/**
	 * Moves the array pointer to the beginning and returns the first item
	 *
	 * @return mixed
	 */
	public function first()
	{
		return reset($this->_items);
	}
	
	/**
	 * Moves the array pointer to the end and returns the last item
	 *
	 * @return mixed
	 */
	public function last()
	{
		return end($this->_items);
	}
	
	/**
	 * Returns a random item from the list
	 *
	 * @return mixed
	 */
	public function random()
	{
		return $this->_items[array_rand($this->_items)];
	}
	
	/**
	 * Reverse the items in this list
	 *
	 * @return void
	 */
	public function reverse()
	{
		array_reverse($this->_items);
	}
	
	/**
	 * Add an item to the internal dataset
	 *
	 * @param mixed $item
	 * @param string $key
	 * @return bool
	 */
	public function addItem($item, $key=null)
	{
		if (isset($item))
		{
			if (isset($key)) {
				$this->_items[$key] = $item;
			}
			else {
				$this->_items[] = $item;
			}
		
			return true;
		}
		
		return false;
	}
	
	/**
	 * Override the item set
	 *
	 * @param array $items
	 * @return void
	 */
	protected function setItems(array $items)
	{
		$this->_items = $items;
	}
	
	/**
	 * Returns the array of items
	 *
	 * @return array
	 */
	protected function getItems()
	{
		return $this->_items;
	}
	
	/**
	 * Empties the item array
	 *
	 * @return void
	 */
	public function emptyItems()
	{
		$this->_items = array();
	}
	
	/**
	 * Gets an iterator for this list
	 * 
	 * @return LSF_Model_List_Iterator
	 */
	public function getIterator()
	{
		return new LSF_Model_List_Iterator($this->_items);
	}
}
