<?php

/**
 * LSF buffered debug output class 
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Debug extends LSF_Utils_Buffer_Static
{
	private static 
		$_instantWrite = false;
	
	/**
	 * enables instant write output if flushed imediatly been added to the buffer
	 * @param bool $value
	 */
	public function setInstantWrite($value)
	{
		self::$_instantWrite = $value;
	}
	
	/**
	 * add output to the buffer
	 * @param string $data
	 */
	public function write($data)
	{
		parent::write($data);
		
		if (self::$_instantWrite)
		{
			$this->flush();
		}
	}
}
