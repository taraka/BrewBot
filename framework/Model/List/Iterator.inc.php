<?php

class LSF_Model_List_Iterator implements Iterator
{
	private
		$_items;
	
	public function __construct(array $items)
	{
		$this->_items = $items;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#rewind()
	 */
	public function rewind()
	{
		reset($this->_items);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#current()
	 */
	public function current()
	{
		return current($this->_items);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#key()
	 */
	public function key()
	{
		return key($this->_items);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#next()
	 */
	public function next()
	{
		return next($this->_items);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Iterator#valid()
	 */
	public function valid()
	{
		return $this->current() !== false;
	}
}