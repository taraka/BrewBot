<?php

/**
 * LSF Static buffer base class
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Utils_Buffer
{
	protected
		$buffer;
	
	public function __construct()
	{
		$this->buffer = '';
	}
	
	/**
	 * add output to the buffer
	 * @param string $data
	 */
	public function write($data)
	{
		$this->buffer .= $data;
	}
	
	/**
	 * Outputs the content of the buffer and clears its content
	 */
	public function flush()
	{
		echo $this->fetch();
		$this->clear();
	}
	
	/**
	 * return the content of the output buffer
	 * 
	 * @return string buffer 
	 */
	public function fetch()
	{
		return $this->buffer;
	} 
	
	/**
	 * Clear the content of the output buffer
	 */
	public function clear()
	{
		$this->buffer = '';
	}
	
	/**
	 * Gets the length of the buffer
	 * 
	 * @return int
	 */
	public function length()
	{
		return strlen($this->buffer);
	}
}
