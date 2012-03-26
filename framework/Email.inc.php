<?php

/**
 * Base class for emails to extend
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

abstract class LSF_Email extends LSF_Presentable
{
	private
		$_stringValidator,
		$_toAddress,
		$_subject,
		$_fromAddress,
		$_fromName,
		$_headers = array(),
		$_type = 'text',
		$_boundary,
		$_altBoundary,
		$_attachment = array();
	
	public function __construct()
	{
		parent::__construct();
		
		$this->_stringValidator = new LSF_Utils_Validator_String();
	}
	
	/**
	 * Sets the address to send the email to
	 * @param string $address
	 */
	public function setToAddress($address)
	{
		if ($this->_stringValidator->validateEmail($address)) {
			$this->_toAddress = $address;
		}
	}
	
	/**
	 * Sets the subject of the email
	 * @param string $subject
	 */
	public function setSubject($subject)
	{
		if (is_string($subject)) {
			$this->_subject = $subject;
		}
	}
	
	/**
	 * Sets the from address
	 * @param string $address
	 */
	public function setFromAddress($address)
	{
		if ($this->_stringValidator->validateEmail($address))
		{
			$this->_fromAddress = $address;
		}
	}
	
	/**
	 * Sets the from name
	 * @param string $name
	 */
	public function setFromName($name)
	{
		$this->_fromName = $name;
	}
	
	/**
	 * Sets the cc address
	 * @param string $address
	 * 
	 * @return bool
	 */
	public function setCcAddress($address)
	{
		if ($this->_stringValidator->validateEmail($address))
		{
			$this->addHeader('Cc: ' . $address);
			return true;
		}
		return false;
	}
	
	/**
	 * Sets the bcc address
	 * @param string $address
	 * 
	 * @return bool
	 */
	public function setBccAddress($address)
	{
		if ($this->_stringValidator->validateEmail($address))
		{
			$this->addHeader('Bcc: ' . $address);
			return true;
		}
		
		return false;
	}
	
	/**
	 * Adds a mail header
	 * @param string $header
	 */
	public function addHeader($header)
	{
		if (is_string($header)) {
			$this->_headers[] = trim($header);
		}
	}
	
	/**
	 * Send the Email
	 * 
	 * @return void 
	 */
	public function send()
	{
		$message = $this->fetch();
		
		if ($this->_type == 'html')
		{
			if (!$this->_boundary)
			{
				$this->_boundary = md5(time());
				$this->_altBoundary = md5(time() . "alt");
				
				$boundary_header = chr(34) . $this->_boundary . chr(34);
				$alt_boundary_header = chr(34) . $this->_altBoundary . chr(34);
				
				$this->addHeader('MIME-Version: 1.0');
				
				if(isset($this->_attachment["name"])) {
					$this->addHeader("Content-Type: multipart/mixed; boundary=$boundary_header");
				}
				else {
					$this->addHeader('Content-Type: multipart/alternative; boundary=' . $alt_boundary_header);
				}
			}
			
			$htmlMessage = $message;
			$textMessage = strip_tags($message);	
			$message = "";
			
			if(isset($this->_attachment["name"])) 
			{
				$message = "
--$this->_boundary
Content-Type: multipart/alternative; boundary=$alt_boundary_header
";
			}
			
			$message .= "
--$this->_altBoundary
Content-Type: text/plain; charset=us-utf-8

$textMessage

--$this->_altBoundary
Content-Type: text/html; charset=us-utf-8

$htmlMessage

--$this->_altBoundary--
";
			
			if(isset($this->_attachment["name"]))
			{
				$message .= "
--$this->_boundary
Content-Type: application/octet-stream; name=".$this->_attachment["name"]."
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename=".$this->_attachment["name"]."

".$this->_attachment["file"]."

--$this->_boundary--
";
			}

		}
		
		if ($this->_toAddress && $this->_fromAddress && $this->_subject)
		{
			$from = !empty($this->_fromName) ? ('"' . $this->_fromName . '" <' .  $this->_fromAddress . '>') : $this->_fromAddress; 
			
			$this->addHeader('From: ' . $from);
			$this->addHeader('Reply-To: ' . $this->_fromAddress);
			
			if (!ini_get('safe_mode')) {
				return mail($this->_toAddress, $this->_subject, $message, implode("\n", $this->_headers),  "-f" . $this->_fromAddress);
			}
			else {
				return mail($this->_toAddress, $this->_subject, $message, implode("\n", $this->_headers));
			}
		}
	}
	
	/**
	 * Gets the current Template name
	 * 
	 * @return string $templateName
	 */
	protected function getTemplateName()
	{
		return 'Email/' . parent::getTemplateName();
	}
	
	/**
	 * Set this email to be text, html, or with an attachment (default text)
	 * 
	 * @param string $type test|html
	 */
	public function setType($type)
	{
		$type = strtolower($type);
		if ($type == 'text' || $type == 'html') {
			$this->_type = $type;
		}
	}
	
	/**
	* Adds an attachment to the email
	* @param string $filename, $file
	*/
	public function addAttachment($filename, $file)
	{
		$this->_attachment["name"] = $filename;
		$this->_attachment["file"] = $file;
	}
	
	/**
	 * Default destructor
	 */
	public function __destruct() {}
}
