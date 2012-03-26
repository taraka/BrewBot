<?php

/**
 * FTP client class
 *
 * @package LSF
 * @version $Id$
 * @copyright 2011
 */
class LSF_Utils_FTP_Client
{
	private
		$_stream;
		
	public function __construct()
	{
		
	}
	
	/**
	 * Connect and login to an ftp server
	 * 
	 * @param string $host
	 * @param string $user
	 * @param string $password
	 * @param int $port
	 * @param int $timeout
	 * @throws LSF_Exception_FTP
	 * 
	 * @return void
	 */
	public function connect($host, $user, $password, $port=21, $timeout=90)
	{
		$this->_stream = ftp_connect(LSF_Config::get('ftp_host'));
		
		if (!$this->_stream) {
			throw new LSF_Exception_FTP('Unable to connect server');
		}
		
		if (!ftp_login($this->_stream, $user, $password)) {
			throw new LSF_Exception_FTP('Login failed');
		}
	}

	/**
	 * Disconnect from the ftp server
	 */
	public function disconnect()
	{
		if ($this->_stream) {
			ftp_close($this->_stream);
		}
	}
	
	/**
	 * Changes the current directory
	 * 
	 * @param string $dir
	 * @return bool
	 */
	public function cd($dir)
	{
		$return = ftp_chdir($this->_stream, $dir);
		if (!$return) {
			throw new LSF_Exception_FTP('Unable to change dir');
		}
		return $return;
	}
	
	/**
	 * Returns the current directory
	 * 
	 * @return string
	 */
	public function pwd($dir)
	{
		return ftp_pwd($this->_stream);
	}
	
	/**
	 * Gets an array of file names in the current dir
	 * 
	 * @param string $directory
	 * @return array
	 */
	public function ls($directory=".")
	{
		return ftp_nlist($this->_stream, $directory);
	}
	
	/**
	 * Upload a file to the server
	 * 
	 * @param string $remoteFileName
	 * @param string $localFileName
	 * @param int $mode
	 */
	public function upload($remoteFileName, $localFileName, $mode=FTP_BINARY)
	{
		$return = false;
		
		if (file_exists($localFileName))
		{
			$file = fopen($localFileName, 'r');
			$return = ftp_fput($this->_stream, $remoteFileName, $file, $mode);
			fclose($file);
		}
		
		return $return;
	}
	
	/**
	 * Gets a filesize
	 * 
	 * @param string $fileName
	 * @return int
	 */
	public function size($fileName)
	{
		return ftp_size($this->_stream, $fileName);
	}
	
	/**
	 * Close the steam if left open
	 */
	public function __destruct()
	{
		$this->disconnect();
	}
}