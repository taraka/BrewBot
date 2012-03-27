<?php

/**
 * Base class for presentabe objects IE forms and emails.
 *
 * @package LSF
 * @author Label Media Ltd
 * $Id: Form.inc.php 55 2010-04-06 12:43:43Z tom $
 */

abstract class LSF_Presentable
{
	private
		$_presenterName = false,
		$_actionName,
		$_presenter,
		$_request;
		
	protected
		$view;
	
	public function __construct()
	{
		$this->_presenterName = LSF_Config::get('default_presenter');
		$this->_request = new LSF_Request();
		$this->loadView();
	}

	/**
	 * Sets up the view for the current presenter
	 *
	 * @return void
	 */
	protected function loadView()
	{
		$this->view = LSF_Config::get('view_mode') == 'basic' ? new LSF_View_Basic() : new LSF_View_Node();
	}
	
	/**
	 * Loads the presenter object
	 *
	 * @return void
	 */
	private function loadPresenter()
	{
		$this->_presenter = LSF_Presenter_Factory::get($this->getPresenterName(), get_class($this));
		
		if ($this->_actionName) {
			$this->_presenter->setActionName($this->_actionName);
		}
	}
	
	/**
	 * Returns presented output as a string
	 *
	 * @return string $output
	 */
	public function fetch()
	{
		$this->loadPresenter();
		
		if ($this->_presenter)
		{
			$this->_presenter->setView($this->view);
			return $this->_presenter->fetch();
		}
	}
	
	/**
	 * Alias of fetch
	 * @see fetch()
	 */
	public function __toString()
	{
		try {
			return $this->fetch();
		}
		catch(Exception $e)
		{
			trigger_error ($e->getMessage(), E_USER_ERROR); 
			return '';
		}
	}
	
	/**
	 * Sets the current presenter name
	 * 
	 * @param string $presenterName
	 */
	public function setPresenterName($presenterName)
	{
		$this->_presenterName = $presenterName;
	}
	
	/**
	 * Returns the current presenter name
	 * 
	 * @return void
	 */
	public function getPresenterName()
	{
		return $this->_presenterName;
	}
	
	/**
	 * sets the current action name for the presenter
	 * 
	 * @return void
	 */
	protected function setActionName($action)
	{
		$this->_actionName = $action;
	}
	
	/**
	 * Returns the current request object 
	 * @return LSF_Request
	 */
	protected function getRequest()
	{
		return $this->_request;
	}
	
	/**
	 * Return the presenter
	 *
	 * @return object
	 */
	protected function getPresenter()
	{
		return $this->_presenter;
	}
	
	/**
	 * Returns the current view object
	 * 
	 * @return LSF_View_Node
	 */
	public function getView()
	{
		return $this->view;
	}
}
