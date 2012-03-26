<?php

/**
 * This class is a utility class for writing to a file stream
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id: Writer.inc.php 4 2009-09-04 13:31:58Z sam $
 */

class LSF_Utils_File_Writer
{
	private $_file;
	
	public function __construct($path)
	{
		if (is_string($path))
		{
			if (!$this->_file = fopen($path, 'a+')) 
			{
				throw new LSF_Exception_FileIO('Unable to open file:' . $path);
			}
		}
	}
	
	public function write($line)
	{
		fwrite($this->_file, $line);
	}
	
	public function __destruct()
	{
		if ($this->_file)
		{
			fclose($this->_file);
		}
	}
}
