<?php

/**
 *
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id: Controller.inc.php 38 2010-02-01 16:52:35Z tom $
 */

class LSF_Utils_Console_Response
{
	private
		$_colourOptions = array(
        // blacks
        'black'     => '30m',
        'hiBlack'   => '1;30m',
        'bgBlack'   => '40m',
        // reds
        'red'       => '31m',
        'hiRed'     => '1:31m',
        'bgRed'     => '41m',
        // greens
        'green'     => '32m',
        'hiGreen'   => '1;32m',
        'bgGreen'   => '42m',
        // yellows
        'yellow'    => '33m',
        'hiYellow'  => '1;33m',
        'bgYellow'  => '43m',
        // blues
        'blue'      => '34m',
        'hiBlue'    => '1;34m',
        'bgBlue'    => '44m',
        // magentas
        'magenta'   => '35m',
        'hiMagenta' => '1;35m',
        'bgMagenta' => '45m',
        // cyans
        'cyan'      => '36m',
        'hiCyan'    => '1;36m',
        'bgCyan'    => '46m',
        // whites
        'white'     => '37m',
        'hiWhite'   => '1;37m',
        'bgWhite'   => '47m'
	);
	
	private
		$_content = array(),
		$_currentLine;
	
	public function __construct() {}
	
	/**
	 * Appends a new line to console output
	 *
	 * @param string $content
	 * @param mixed $colours a single colour or array of colours (foreground and background)
	 * @return void
	 */
	public function appendLine($content=false, $colours='green')
	{
		if ($this->_currentLine)
		{
			$line = $this->_currentLine;
			$this->_currentLine = '';
			$this->appendLine($line, $colours);
		}
		
		if ($content !== false)
		{
			$this->appendContent($content, $colours);
			$this->_content[] = $this->_currentLine;
			$this->_currentLine = '';
		}
	}
	
	/**
	 * Appends content to console output without a new line
	 *
	 * @param string $content
	 * @param mixed $colours a single colour or array of colours (foreground and background)
	 * @return void
	 */
	public function appendContent($content, $colours='green')
	{
		if (is_array($content))
		{
			foreach ($content as $line)
			{
				$this->appendLine($line, $colours);
			}
		}
		else
		{
			if ($colours && !is_array($colours))
			{
				$colours = array($colours);
			}
			
			if ($colours)
			{
				$colourContent = '';
				
				foreach ($colours as $colour)
				{
					if (isset($this->_colourOptions[$colour]))
					{
						$colourContent .= "\033[" . $this->_colourOptions[$colour];
					}
				}
				
				$content = $colourContent . $content . "\033[m";
			}

			$this->_currentLine .= $content;
		}
	}
	
	/**
	 * Write a message to std Err
	 * @param string $error
	 */
	public function writeError($error)
	{
		$this->flushContent();
		
		$colours = array('hiWhite', 'bgRed');
		
		$colourContent = '';
		
		foreach ($colours as $colour)
		{
			if (isset($this->_colourOptions[$colour]))
			{
				$colourContent .= "\033[" . $this->_colourOptions[$colour];
			}
		}
		
		$content = $colourContent . $error . "\033[m";
		
		file_put_contents('php://stderr', $content);
	}
	
	/**
	 * Flushes the current console output buffer
	 *
	 * @return void
	 */
	public function flushContent()
	{
		echo $this->getContent();
		$this->clearContent();
	}
	
	/**
	 * Clears the current console output buffer
	 *
	 * @return void
	 */
	public function clearContent()
	{
		$this->_content = array();
	}
	
	/**
	 * Returns an array of console output lines
	 *
	 * @return void
	 */
	public function getContent()
	{
		return implode("\n", $this->_content) . "\n";
	}
	
	public function __destruct() {}
}
