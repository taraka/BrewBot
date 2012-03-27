<?php

/**
 * Representation of a time stamp
 * 
 * @author tom
 */
class Model_Timeslot
{
	private
		$_timeslot;
		
	public function __construct($hours=null, $minutes=null)
	{
		$this->setTime($hours, $minutes);
	}
	
	/**
	 * Sets this object based on a time
	 * 
	 * @param int $hours
	 * @param int $minutes
	 * @return void
	 */
	public function setTime()
	{
		$hours = isset($hours) 		? $hours 	: date('G');
		$minutes = isset($minutes) 	? $minutes 	: date('i');
		
		$this->_timeslot = $this->parseTimeslot($hours, $minutes);
	}
	
	/**
	 * Takes a time and returns the closest timeslot
	 * 
	 * @param int $hours
	 * @param int $minutes
	 */
	public function parseTimeslot($hours, $minutes)
	{
		return round($hours * 4 + $minutes / 15);
	}

	/**
	 * Sets the timeslot value for this object
	 * 
	 * @param int $timeslot
	 * @return void
	 */
	public function setTimeslot($timeslot)
	{
		$this->_timeslot = $timeslot;
	}
	
	/**
	 * Returns the timeslot value for this object
	 * 
	 * @return int
	 */
	public function getTimeslot()
	{
		return (int)$this->_timeslot;
	}
	
	/**
	 * Returns the hour number for the timeslot
	 * 
	 * @return int
	 */
	public function getHour()
	{
		return floor($this->_timeslot / 4);
	}
	
	/**
	 * Returns the hour number for the timeslot
	 * 
	 * @return int
	 */
	public function getMinute()
	{
		return ($this->_timeslot - floor($this->_timeslot / 4) * 4 ) * 15;
	}
	
	/**
	 * Return a string representation of this timeslot
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return $this->getHour() . ':' . str_pad($this->getMinute(), 2, '0');
	}
}