<?php

/**
 * 
 * Test request class
 * @package LSF
 * @subpackage Tests
 * @author martin
 */
class LSF_Router_Route_DefaultTest extends LSF_UnitTests_Base
{		
	public function testRouteAvailable()
	{
		$_SERVER['REQUEST_URI'] = 'http://www.example.com/test/something/param/';		
		$request = new LSF_Request();		
		$defaultRouter = new LSF_Router_Route_Default();
		
		$this->assertTrue($defaultRouter->routeAvaliable($request));
	}
	
	public function testController()
	{
		$_SERVER['REQUEST_URI'] = 'http://www.example.com/Test/something/param/';		
		$request = new LSF_Request();		
		$defaultRouter = new LSF_Router_Route_Default();
		$defaultRouter->route($request);
		
		$this->assertEquals('Test', $request->getController());
		
		$_SERVER['REQUEST_URI'] = 'http://www.example.com/KITTY/something/param/';
		$request = new LSF_Request();
		$defaultRouter->route($request);
		
		$this->assertEquals('Kitty', $request->getController());
		
		$_SERVER['REQUEST_URI'] = 'http://www.example.com/puppy/something/param/';
		$request = new LSF_Request();
		$defaultRouter->route($request);
		
		$this->assertEquals('Puppy', $request->getController());
	}
	
	public function testAction()
	{
		$_SERVER['REQUEST_URI'] = 'http://www.example.com/test/Something/param/';		
		$request = new LSF_Request();
		$defaultRouter = new LSF_Router_Route_Default();
		$defaultRouter->route($request);
		
		$this->assertEquals('Something', $request->getAction());
		
		$_SERVER['REQUEST_URI'] = 'http://www.example.com/test/SOMETHING/param/';
		$request = new LSF_Request();
		$defaultRouter->route($request);
		
		$this->assertEquals('SOMETHING', $request->getAction());
	}
	
	public function testParam()
	{
		$_SERVER['REQUEST_URI'] = 'http://www.example.com/test/something/param/';		
		$request = new LSF_Request();
		$defaultRouter = new LSF_Router_Route_Default();
		$defaultRouter->route($request);
		
		$this->assertEquals('param', $request->getParam(0));
		
		$_SERVER['REQUEST_URI'] = 'http://www.example.com/test/something/PARAM/';
		$request = new LSF_Request();
		$defaultRouter->route($request);
		
		$this->assertEquals('PARAM', $request->getParam(0));
	}
	
	public function testLocale()
	{
		$_SERVER['REQUEST_URI'] = 'http://www.example.com/test/something/param/';		
		$request = new LSF_Request();
		$defaultRouter = new LSF_Router_Route_Default();
		$defaultRouter->route($request);
		
		$this->assertEquals('en-gb', $request->getLocale());
		
		$_SERVER['REQUEST_URI'] = 'http://www.example.com/DE/test/something/PARAM/';
		$request = new LSF_Request();
		$defaultRouter->route($request);
		
		$this->assertEquals('de', $request->getLocale());
		
		$_SERVER['REQUEST_URI'] = 'http://www.example.com/de/test/something/param/';
		$request = new LSF_Request();
		$defaultRouter->route($request);
		
		$this->assertEquals('de', $request->getLocale());
	}
}
