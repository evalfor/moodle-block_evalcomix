<?php
define('CLI_SCRIPT', true);
require_once('../classes/evalcomix_tool.php');
include_once('../../../config.php');


class evalcomix_tool_MockObjectFirstTest extends PHPUnit_Framework_TestCase {
	
	/**
	* Object construction using a Mock Object
	*/

	public function testMockObject2Ok()
	{
		//$this->setExpectedException('moodle_exception');
		$mock2 = $this->getMock('evalcomix_tool',new evalcomix_tool('', '1', 'titulo de la escala2', 'list'));
		$mock2->expects($this->any())
			->method('insert');//'dml_missing_record_exception'
	//	$mock = new evalcomix_tool('', '1', 'titulo de la escala2', '');
		//$mock->insert();
		$id = $mock2->insert();
		$this->AssertEquals($id,$mock2->id);
	}
	
		public function testMockObjectOk()
	{
		$this->setExpectedException('moodle_exception');
		$mock = $this->getMock('evalcomix_tool',new evalcomix_tool('', '1', 'titulo de la escala2', ''));
		$mock->expects($this->any())
			->method('insert')
			->will($this->throwException('moodle_exception'));//'dml_missing_record_exception'
	//	$mock = new evalcomix_tool('', '1', 'titulo de la escala2', '');
		$mock->insert();
	}

	
}
?>
