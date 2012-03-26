<?php

/**
 * 
 * Test request class
 * @package LSF
 * @subpackage Tests
 * @author martin
 */
class LSF_Router_Route_HttpVerbTest extends LSF_UnitTests_Base
{		
	public function testHttpVerb()
	{
		$_SERVER['REQUEST_URI'] = 'http://www.example.com/test/Something/param/';
		$_SERVER['REQUEST_METHOD'] = "GET";	
		$request = new LSF_Request();
		$httpVerbRouter = new LSF_Router_Route_HttpVerb();		
		$httpVerbRouter->processAction($request);
		
		$this->assertEquals('get', $request->getAction());
		
		$_SERVER['REQUEST_METHOD'] = "PUT";
		$request = new LSF_Request();
		$httpVerbRouter->route($request);
		
		$this->assertEquals('put', $request->getAction());
		
		$_SERVER['REQUEST_METHOD'] = "POST";
		$request = new LSF_Request();
		$httpVerbRouter->route($request);
		
		$this->assertEquals('post', $request->getAction());
	}
}
