<?php

/**
 * This Class is a utility class for writing log entries
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id: String.inc.php 4 2009-09-04 13:31:58Z sam $
 */

class LSF_Utils_File_Log_Writer
{
	private $_writer;
	
	public function __construct($path=null)
	{
		if (!$path) {
			$path = LSF_Application::getApplicationPath() . '/../logs/debug.log';
		}
		
		if (is_string($path))
		{
			try {
				$this->_writer = new LSF_Utils_File_Writer($path);
			}
			catch (LSF_Exception_FileIO $e) {
				throw new LSF_Exception_FileIO('Unable to open log file:' . $path);
			}
		}
	}
	
	/**
	 * Write information to the log file
	 * @param string $message
	 */
	public function info($message)
	{
		$this->write($message, 'INFO');
	}
	
	/**
	 * Write a warning to the log file
	 * @param string $message
	 */
	public function warn($message)
	{
		$this->write($message, 'WARN');
	}
	
	/**
	 * Write an error to the log file
	 * @param string $message
	 */
	public function error($message)
	{
		$this->write($message, 'ERROR');
	}
	
	/**
	 * Write a line to the log
	 * @param string|array $message
	 * @param string $level
	 */
	private function write($message, $level)
	{
		if (is_array($message) || is_object($message)) {
			$message = print_r($message, true);
		}
		
		$log = '[' . date('D M d H:i:s Y') . '] [' . $level . '] [Request: ' . $_SERVER['REQUEST_URI'] . '] ' . $message . "\n";
		
		$this->_writer->write($log);
	}
}
