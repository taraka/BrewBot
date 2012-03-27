<?php

/**
 * This class contains string validation functions.
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id: String.inc.php 4 2009-09-04 13:31:58Z sam $
 */

class LSF_Utils_Validator_String
{
	public function __construct() {}
	
	/**
	 * Validates email addresses using a regex
	 *
	 * @param string $email
	 * @return bool
	 */
	public function validateEmail($email)
	{
		return (bool)preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9])+[a-zA-Z0-9-]*(\.[a-zA-Z0-9]+[a-zA-Z0-9-]*)+$/", $email);
	}
	
	/**
	 *
	 * @param string $number phone number
	 * @return bool
	 */
	public function validatePhoneNumber($number)
	{
		return is_numeric(str_replace(array(' ', '+', '(', ')', '-'), '', $number));
	}
	
	/**
	 * Validates a string is under a wordcount
	 * @param string $string
	 * @param int $wc
	 * @return bool
	 */
	public function validateWordCount($string, $wc)
	{
		return str_word_count($string) <= $wc;
	}
	
	public function __destruct() {}
}
