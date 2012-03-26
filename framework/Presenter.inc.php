<?php

/**
 *
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id$
 */

abstract class LSF_Presenter
{
	protected
		$view,
		$locale,
		$controllerName = 'Controller_Default',
		$action = 'index';
	
	public function __construct($controllerName=false)
	{
		if ($controllerName) {
			$this->setControllerName($controllerName);
		}
		
		$this->locale = new LSF_Locale();
		$this->locale->loadLanguageFile('global.xml');
		
		if ($template = $this->getTemplateName()) {
			$this->locale->loadLanguageFile($template . '.xml');
		}
	}
	
	/**
	 * Set the view object
	 *
	 * @param LSF_View $view
	 * @return void
	 */
	public function setView(LSF_View_Base $view)
	{
		$this->view = $view;
	}
	
	/**
	 * outputs the complete output for this object
	 *
	 * @return string
	 */
	public final function display()
	{
		echo $this->fetch();
	}
	
	/**
	 * Returns the complete output for this object
	 *
	 * @return string
	 */
	abstract public function fetch();
	
	/**
	 * Returns the locale strings for this object in the current language
	 *
	 * @return array
	 */
	public function getLocaleStrings()
	{
		return $this->locale->getLocaleStrings();
	}
	
	/**
	 * Return the action name
	 *
	 * @return string
	 */
	public function getActionName()
	{
		return $this->action;
	}
	
	/**
	 * Set the action name
	 *
	 * @param string $action
	 * @return void
	 */
	public function setActionName($action)
	{
		$this->action = $action;
	}
	
	/**
	 * Set the controller name
	 *
	 * @param string $controllerName
	 * @return void
	 */
	public function setControllerName($controllerName)
	{
		$this->controllerName = $controllerName;
	}
	
	/**
	 * This method can be overridden to add additional info to the path
	 *
	 * @see LSF_Controller::getTemplateName()
	 * @return string
	 */
	protected function getTemplateName()
	{
		$classParts = explode('_', $this->controllerName);
		
		$templateName = '';
		
		foreach ($classParts as $i => $part) {
			$templateName .= $part .= '/';
		}
		
		return substr($templateName, 0, -1);
	}
}
