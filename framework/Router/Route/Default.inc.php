<?php

/**
 * Default url routing
 * 
 * @package LSF
 * @author tom
 */
class LSF_Router_Route_Default extends LSF_Router_Route 
{

	/**
	 * (non-PHPdoc)
	 * @see framework/Router/LSF_Router_Route#routeAvaliable()
	 */
	public function routeAvaliable(LSF_Request $request)
	{
		return true;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see framework/Router/LSF_Router_Route#route()
	 */
	public function route(LSF_Request $request)
	{
		$this->requestParts = $this->getRequestParts($request);
		
		$this->processLocale($request);
		$this->processController($request);
		$this->processAction($request);
		$this->processParams($request);
	}
	/**
	 * Try to get a local string
	 * @param LSF_Request $request
	 */
	protected function processLocale(LSF_Request $request)
	{
		if (!empty($this->requestParts[0]))
		{
			$locale = strtolower($this->requestParts[0]);
			
			if (LSF_Locale::languageFileExists($locale)) {
				$request->setLocale($locale);
				unset($this->requestParts[0]);
				$this->requestParts = array_values($this->requestParts);
			}
		}
	}
	
	/**
	 * Try to get a controller name
	 * @param LSF_Request $request
	 */
	protected function processController(LSF_Request $request)
	{
		$i = count($this->requestParts);
		do
		{
			$i--;
			
			$controller = '';
			
			foreach (range(0, $i) as $j)
			{
				$controllerPart = isset($this->requestParts[$j]) ? $this->requestParts[$j] : false;
				$controllerPart = '_' . ucfirst(strtolower($controllerPart));
				$controller .= str_replace('-', '', $controllerPart);
			}
			
			$controller = substr($controller, 1);
			
			if ($i == 0) {
				break;
			}
			
		} while ((!LSF_Application::classFileExists('Controller_' . $controller)) || ($controllerPart == '_'));
		
		$controller = str_replace('.', '', $controller);
		
		if ($controller) {
			$request->setController($controller);
		}

		foreach (range(0, $i) as $j) {
			array_shift($this->requestParts);
		}
		
	}
	
	/**
	 * Try to get an action name
	 * @param LSF_Request $request
	 */
	protected function processAction(LSF_Request $request)
	{
		if (!empty($this->requestParts[0])) {
			$request->setAction(array_shift($this->requestParts));
		}
	}
}