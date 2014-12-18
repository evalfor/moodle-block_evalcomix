<?php
/**
 * Unit tests for blocks/calamardo/classes/evalcomix_tool.php, insert
 *
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 * @package block-evalcomix
 */
require_once(dirname(__FILE__) . '/../classes/evalcomix_tool.php');
require_once(dirname(__FILE__) . '/../../../config.php');


class evalcomix_tool_insert_test extends UnitTestCase {

	/**
	* Non-existing object insert in a DB 
	*/
	public function test_insert_ok() {
		$objectnew = new evalcomix_tool('', '1', 'titulo de la escala2', 'scale'); 
		$id = $objectnew->insert();
		$this->assertEqual($id,$objectnew->id);
		unset($objectnew);
	}


	/**
	* $evxid not exists in 'block_evalcomix' DB 
	*/
	public function test_insert_fail_1()
	{
	$this->expectException('dml_missing_record_exception');
	$objectnew = new evalcomix_tool('', '3', 'titulo', 'scale');
	$id = $objectnew->insert();
	unset($objectnew);
	}
	
	/**
	* Existing object insert in a DB 
	*/
	
	public function test_insert_fail_2() {
		$objectnew = new evalcomix_tool('120', '1', 'titulo de la escala2', 'scale'); 
		$id = $objectnew->insert();
		$this->assertFalse($id);
		unset($objectnew);
	}
	
	
}