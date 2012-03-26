<?php

/**
 * Route the request using the http verb as the action
 * 
 * @package LSF
 * @author tom
 */
class LSF_Router_Route_HttpVerb extends LSF_Router_Route_Default 
{
	
	/**
	 * Sets the action based on the http verb used in teh request
	 */
	public function processAction($request)
	{
		$request->setAction($request->getRequestMethod());
	}
}