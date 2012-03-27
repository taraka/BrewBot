<?php

/**
 * This class is the View for the Xml Presenter and provides data storage and retreval for the presenter.
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id: Smarty.inc.php 57 2010-04-08 11:34:17Z tom $
 */

class LSF_Presenter_View_Xml
{
	private
		$_document, 
		$_element;  
		
	
	public function __construct()
	{
		$this->_document = new DOMDocument();
		$this->_document->formatOutput = true;
	}
	
	/**
	 * Takes a view object and converts in in to an xml string
	 * @param LSF_View_Node $view
	 * @return string $xml
	 */
	public function parse(LSF_View_Node $view)
	{
		$root = $this->_document->createElement('response');
		$this->parse_r($view, $root);
		$this->_document->appendChild($root);
		
		if (extension_loaded('xhprof') && LSF_Config::get('enable_profiling') && LSF_Config::get('xhprof_lib_dir'))
		{
			$profilerNamespace = LSF_Config::get('application_name') ? LSF_Config::get('application_name') : 'LSF';  // namespace for your application
			$xhprofData = xhprof_disable();
			$xhprofRuns = new XHProfRuns_Default();
			$runId = $xhprofRuns->save_run($xhprofData, $profilerNamespace);
			
			if (LSF_Config::get('xhprof_ui_url'))
			{
				$profilerUrl = LSF_Config::get('xhprof_ui_url') . '/index.php?run=' . $runId . '&source=' . $profilerNamespace;
				$root->setAttribute('profileUrl', htmlspecialchars($profilerUrl, ENT_COMPAT, 'UTF-8'));
				LSF_Application::disableProfiling();
			}
		}
		
		return $this->_document->saveXml();
	}
	
	/**
	 * Builds DomElement based on the passed in View object
	 * @param LSF_View_Node $view
	 * @param DomElement $root
	 */
	private function parse_r(LSF_View_Node $view, $root)
	{
		foreach ($view as $key => $list)
		{
			foreach ($list as $child)
			{
				$element = $this->_document->createElement($key);
				$root->appendChild($element);	
				
				if ($child->getValue())
				{
					foreach ($child->attributes() as $name => $attribute)
					{
						$element->setAttribute($name, htmlspecialchars($attribute, ENT_COMPAT, 'UTF-8'));
					}
					
					$element->nodeValue = htmlspecialchars($child->getValue(), ENT_COMPAT, 'UTF-8');
				}
				else
				{
					$this->parse_r($child, $element);
				}
			}
			
		}
		
		foreach ($view->attributes() as $name => $attribute)
		{
			$root->setAttribute($name, htmlspecialchars($attribute, ENT_COMPAT, 'UTF-8'));
		}
	}
}
