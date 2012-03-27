<?php

/**
 * This class is designed to parse the view object for use with smarty
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Presenter_View_Smarty
{
	private $_view = array();
	
	/**
	 * Assigns the current view object
	 * @param LSF_View_Base $view
	 */
	public function setView(LSF_View_Base $view)
	{
		return $this->_view = $view;
	}
	
	/**
	 * Returns an array of properties built from the current view object
	 *
	 * @return array $properties
	 */
	public function getProperties()
	{
		if ($this->_view instanceof LSF_View_Basic) {
			return $this->_view->getVariables();
		}
		
		$returnArray = array();
		
		$this->parse_r($this->_view, $returnArray);
		
		return $returnArray;
	}
	
	/**
	 * Recursive function to build the properties array
	 *
	 * @param LSF_View_Node $view
	 * @param array $array
	 * @return array
	 */
	private function parse_r(LSF_View_Node $view, &$array)
	{
		foreach ($view->attributes() as $key => $attribute) {
			$array[$key] = $attribute;
		}
		
		foreach ($view as $key => $list)
		{
			$array[$key] = array();
			
			if ($list->isList())
			{
				foreach ($list as $listKey => $child)
				{
					if ($child->getValue()) {
						$array[$key][$listKey] = $child->getValue();
					}
					else
					{
						$array[$key][$listKey] = array();
						$this->parse_r($child, $array[$key][$listKey]);
					}
				}
			}
			else
			{
				if ($list->getValue() !== null) {
					$array[$key] = $list->getValue();
				}
				else {
					 $this->parse_r($list->getFirstNode(), $array[$key]);
				}
			}
		}
	}
}
