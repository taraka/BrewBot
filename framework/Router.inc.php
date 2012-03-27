<?php
/**
 * 
 * This class controlles routing to controllers
 * 
 * @package LSF
 * @author tom
 */
class LSF_Router
{
	private
		$_routes = array();
	
	public function __construct() {}
	
	/**
	 * Add another route to the routing stack
	 * 
	 * @param LSF_Router_Route $route
	 */
	public function addRoute(LSF_Router_Route $route)
	{
		array_unshift($this->_routes, $route);
	}
	
	/**
	 * Perform the routing action
	 * 
	 * @param LSF_Request $request
	 */
	public function route(LSF_Request $request)
	{
		foreach ($this->_routes as $route)
		{
			if ($route->routeAvaliable($request))
			{
				$route->route($request);
				return true;
			}
		}
		
		return false;
	}
}