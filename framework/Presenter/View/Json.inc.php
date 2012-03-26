<?php

/**
 * This class is the View for the Xml Presenter and provides data storage and retreval for the presenter.
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

class LSF_Presenter_View_Json
{
	/**
	 * Parse the view object in to a json string
	 * 
	 * @param LSF_View_Base $view
	 * @return string
	 */
	public function parse(LSF_View_Base $view)
	{
		$parser = new LSF_Presenter_View_Smarty();
		$parser->setView($view);
		return json_encode($parser->getProperties());
	}
}
