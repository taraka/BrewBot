<?php

/**
 * Redirection
 * 
 * @package LSF
 * @author tom
 */
class LSF_Router_Route_Redirection extends LSF_Router_Route 
{
	private
		$_xml,
		$_url;
		
	public function __construct($path=null)
	{
		if (!$path) {
			$path = LSF_Application::getApplicationPath() . '/Config/redirection.xml';
		}
		
		if (file_exists($path))
		{
			libxml_use_internal_errors(true);
			$this->_xml = simplexml_load_file($path);
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Router/LSF_Router_Route#routeAvaliable()
	 */
	public function routeAvaliable(LSF_Request $request)
	{
		if ($this->_xml)
		{
			if (is_object($this->_xml) && $this->_xml->redirect)
			{
				foreach ($this->_xml->redirect as $redirect)
				{
					if ($redirect->attributes()->from == $request->getRequestPath())
					{
						$this->_url = trim((string)$redirect);
						
						if ($redirect->attributes()->keepQuery == 'true') {
							$this->_url .= '?' . http_build_query($request->getGetVars());
						}
						
						return !empty($this->_url);
					}
				}
			}
		}
		
		return false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Router/LSF_Router_Route#route()
	 */
	public function route(LSF_Request $request)
	{
		if ($this->_url)
		{			
			header ('HTTP/1.1 301 Moved Permanently');
			header ('Location: ' . $this->_url);
			exit;
		}
	}
}