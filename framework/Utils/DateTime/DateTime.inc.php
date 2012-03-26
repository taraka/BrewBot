<?php 

/**
 * Crude implementation of a DateTime object for PHP < 5.2
 * 
 * @author tom
 * @package LSF
 */
if (!class_exists('DateTime', false))
{
	class DateTime
	{
		private
			$_time;
			
		public function __construct($time=null)
		{
			if (isset($time)) {
				$this->_time = strtotime($time);
			}
			else {
				$this->_time = time();
			}
		}
		
		/**
		 * Sets the date of this object
		 * @param int $year
		 * @param int $month
		 * @param int $day
		 */
		public function setDate($year, $month, $day)
		{
			$this->_time = mktime(0, 0, 0, $month, $day, $year);
		}
		
		/**
		 * Returns a formatted date
		 * 
		 * @param string $format
		 * @return string
		 */
		public function format($format)
		{
			return date($format, $this->_time);
		}
		
		/**
		 * Sets the current timestamp for this object
		 * 
		 * @param int $timestamp
		 * @return void
		 */
		public function setTimestamp($timestamp)
		{
			$this->_time = (int)$timestamp;
		}
		
		/**
		 * Returns the timestamp for this object
		 * 
		 * @return int
		 */
		public function getTimestamp()
		{
			return $this->_time;
		}
	}
}