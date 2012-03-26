<?php

/**
 * Test view classes
 * 
 * @package LSF
 * @subpackage Tests
 * @author tom
 */
class LSF_View_NodeTest extends LSF_UnitTests_Base
{	
	public function testBasicView()
	{
		$view = new LSF_View_Node();
		$this->assertFalse(isset($view->bob));
		$view->bob = 'test';
		$this->assertTrue(isset($view->bob));
	}
	
	public function testValues()
	{
		$view = new LSF_View_Node();
		$view->bob = 'test';
		
		$this->assertEquals((string)$view->bob, 'test');
	}
	
	public function testGetValue()
	{
		$view = new LSF_View_Node();
		$view->bob = 'test';
		
		$this->assertEquals($view->bob->getValue(), 'test');
	}
	
	public function testType()
	{
		$view = new LSF_View_Node();
		$view->bob = 'test';
		
		$this->assertTrue(is_object($view->bob));
		$this->assertTrue($view->bob instanceof LSF_View_Node_List);
	}
	
	public function testArrayCount1()
	{
		$view = new LSF_View_Node();
		$view->bob = 'test';
		
		$this->assertEquals(count($view->bob), 1);
		
		foreach ($view->bob as $bob)
		{
			$this->assertEquals((string)$bob, 'test');
		}
	}
	
	public function testArrayCount2()
	{
		$view = new LSF_View_Node();
		$view->bob = 'test';
		
		$this->assertEquals(count($view->bob), 1);
		
		$node = $view->bob->addNode();
		
		$this->assertEquals(count($view->bob), 2);
		
		$this->assertTrue($view->bob->isList());
	}
	
	public function testNodeType()
	{
		$view = new LSF_View_Node();
		$node = $view->bob->addNode();
		$this->assertTrue($node instanceof LSF_View_Node);
	}
	
	public function testArraySet()
	{
		$view = new LSF_View_Node();
		$view->bob = array( 
			'test',
			'test2',
		);

		$this->assertTrue($view->bob->isList());
		
		$this->assertEquals(count($view->bob), 2);
	}
	
	public function testArrayLoop()
	{
		$view = new LSF_View_Node();
		$view->bob = array( 
			'test',
			'test2',
		);
		
		$first = true;
		foreach ($view->bob as $key => $node)
		{
			$this->assertTrue(is_numeric($key));
			
			if ($first) {
				$this->assertEquals($node->getValue(), 'test');
			}
			else {
				$this->assertEquals($node->getValue(), 'test2');
			}
			
			$first = false;
		}
	} 
	
	public function testArraySetAssoc()
	{
		$view = new LSF_View_Node();
		$view->bob = array( 
			'name' => 'test',
			'other' => 'test2',
		);
		
		$this->assertFalse($view->bob->isList());
		$this->assertEquals(count($view->bob), 1);
	}
	
	public function testArraySetAssoc2()
	{
		$view = new LSF_View_Node();
		$view->bob = array( 
			'name' => 'test',
			'other' => 'test2',
		);
		
		$this->assertEquals((string)$view->bob->name, 'test');
		$this->assertEquals((string)$view->bob->other, 'test2');
	}
	
	public function testParent()
	{
		$view = new LSF_View_Node();
		$view->bob = 'test';
		
		$this->assertEquals($view->bob->parent(), $view);
	}
	
	public function testAttributeSet()
	{
		$view = new LSF_View_Node();
		$view->bob = 'test';
		
		$this->assertFalse(isset($view->bob->attributes()->test));
		$view->bob->attributes()->test = 'hello';
		$this->assertTrue(isset($view->bob->attributes()->test));
	}
	
	public function testAttributeGet()
	{
		$view = new LSF_View_Node();
		$view->bob = 'test';

		$view->bob->attributes()->test = 'hello';
		$this->assertEquals($view->bob->attributes()->test, 'hello');
	}
	
	public function testAttributeCount()
	{
		$view = new LSF_View_Node();
		$view->bob = 'test';

		$view->bob->attributes()->test = 'hello';
		$this->assertEquals(count($view->bob->attributes()), 1);
	}
	
	public function testAttributeLoop()
	{
		$view = new LSF_View_Node();
		$view->bob = 'test';

		$view->bob->attributes()->test = 'hello';
		foreach($view->bob->attributes() as $key => $value)
		{
			$this->assertEquals($key, 'test');
			$this->assertEquals($value, 'hello');
		}
	}
}
