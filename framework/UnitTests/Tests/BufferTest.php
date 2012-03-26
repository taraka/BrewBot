<?php

/**
 * 
 * Test buffer classes
 * @package LSF
 * @subpackage Tests
 * @author tom
 */
class BufferTest extends LSF_UnitTests_Base
{	
	public function testBuffer()
	{
		$buffer = new LSF_Utils_Buffer();
		$this->assertEquals($buffer->length(), 0);
		
		$buffer->write('Test');
		$this->assertEquals($buffer->length(), 4);
		$this->assertEquals($buffer->fetch(), 'Test');
		
		$buffer->write('Test');
		$this->assertEquals($buffer->length(), 8);
		$this->assertEquals($buffer->fetch(), 'TestTest');
		
		ob_start();
		$buffer->flush();
		$output = ob_get_clean();
		
		$this->assertEquals($output, 'TestTest');
		$this->assertEquals($buffer->length(), 0);
		
		$buffer->write('Test');
		$this->assertEquals($buffer->length(), 4);
		
		$buffer->clear();
		$this->assertEquals($buffer->length(), 0);
		
		$buffer->write('Test');
		$this->assertEquals($buffer->length(), 4);
		$this->assertEquals($buffer->fetch(), 'Test');
		
		$buffer = new LSF_Utils_Buffer();
		$this->assertEquals($buffer->length(), 0);
	}
}
