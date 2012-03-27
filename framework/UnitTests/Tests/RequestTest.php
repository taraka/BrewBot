<?php

/**
 * 
 * Test request class
 * @package LSF
 * @subpackage Tests
 * @author tom
 */
class RequestTest extends LSF_UnitTests_Base
{	
	
	public function routeProvider()
	{
		return array(
			array ('test', 'test', 'test'),
		);
	}
	
	public function pathProvider()
	{
		return array(
			array ('test', 'test'),
			array ('', ''),
			array ('////', '////'),
		);
	}
	
	public function methodProvider()
	{
		return array(
			array('get'),
			array('post'),
			array('put'),
			array('delete'),
			array('custom'),
		);
	}
	
	public function setup()
	{
		$_POST = array(
			'var1'	=> 1,
			'var2'	=> 'wibble',
		);
		
		$_SERVER['REQUEST_URI'] = 'http://www.example.com/test/something/1/';
		$_SERVER['HTTP_TEST'] = 'a test header';
	}
	
	public function testPost()
	{
		$request = new LSF_Request();
		
		$this->assertEquals(count($request->getPostVars()), 2);
		$this->assertNull($request->getPostVar('var3'));
		$this->assertTrue(is_numeric($request->getPostVar('var1')));
	}
	
	/**
	 * @dataProvider pathProvider
	 */
	public function testPath($path, $expect)
	{
		$request = new LSF_Request();
		
		$this->assertEquals($request->getRequestPath(), '/test/something/1/');
		
		$this->assertEquals($request->setRequestPath($path), $path);
		$this->assertEquals($request->getRequestPath(), $path);
		
		
	}
	
	/**
	 * @dataProvider routeProvider
	 */
	public function testFakeRoute($locale, $controller, $action)
	{
		$request = new LSF_Request();
		
		$this->assertEquals($request->setLocale($locale), $locale);	
		$this->assertEquals($request->setController($controller), $controller);	
		$this->assertEquals($request->setAction($action), $action);	
		
		$this->assertEquals($request->getLocale(), $locale);	
		$this->assertEquals($request->getController(), $controller);	
		$this->assertEquals($request->getAction(), $action);	
	}
	
	public function testHeaders()
	{
		$request = new LSF_Request();
		
		$this->assertEquals($request->getHeader('test'), 'a test header');
		$this->assertEquals($request->getHeader('Test'), 'a test header');
		$this->assertEquals($request->getHeader('TEST'), 'a test header');
		$this->assertNull($request->getHeader('test other'));
	}
	
	/**
	 * @dataProvider methodProvider
	 */
	public function testMethod($method)
	{
		$request = new LSF_Request();
		
		$this->assertEquals($request->getRequestMethod(), 'get');
		
		$_SERVER['REQUEST_METHOD'] = $method;
		
		$request = new LSF_Request();
		
		$this->assertEquals($request->getRequestMethod(), $method);
	}
	
	public function testParams()
	{
		$request = new LSF_Request();
		
		$request->setParams(array('bob' => 'moo'));
		
		$this->assertEquals($request->getParam('bob'), 'moo');
		
		$request->setParam(0, 'first');
		$request->setParam(1, 'second');
		
		$request->setParam('test', 'named');
		$request->setParam('testOther', 'hello');
		
		$this->assertEquals($request->getParam(0), 'first');
		$this->assertEquals($request->getParam(1), 'second');
		$this->assertEquals($request->getParam('test'), 'named');
		$this->assertEquals($request->getParam('testOther'), 'hello');
		
		$this->assertEquals(count($request->getParams()), 5);
	}
	
	public function testGet()
	{
		$_SERVER['REQUEST_URI'] = 'http://www.example.com/test/?first=hello&array[]=1&array[]=2&other=bob';
		LSF_Request::clearGetCache();
		$request = new LSF_Request();

		$this->assertEquals($request->getGetVar('first'), 'hello');
		$this->assertEquals($request->getGetVar('array'), array('1', '2'));
		$this->assertEquals($request->getGetVar('other'), 'bob');
		$this->assertEquals(count($request->getGetVar('array')), 2);
		
		$this->assertEquals(count($request->getGetVars()), 3);
	}
	
	public function testGetIpAddress()
	{
		$_SERVER['REMOTE_ADDR'] = '192.168.3.55';
		
		$request = new LSF_Request();

		$this->assertEquals('192.168.3.55', $request->getIpAddress());
		
		$_SERVER['HTTP_X_FORWARDED_FOR'] = '192.168.2.55';
		
		$request = new LSF_Request();
		$this->assertEquals('192.168.2.55', $request->getIpAddress());
		
		$_SERVER['HTTP_X_FORWARDED_FOR'] = '192.168.2.55,192.168.1.55';
		
		$request = new LSF_Request();
		$this->assertEquals('192.168.1.55', $request->getIpAddress());
	}
}
