<?php
/**
 * Unit tests for blocks/calamardo/classes/evalcomix_tool.php, fetch_all
 *
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 * @package block-evalcomix
 */

require_once(dirname(__FILE__) . '/../classes/evalcomix_tool.php');
require_once(dirname(__FILE__) . '/../../../config.php');

class evalcomix_tool_fetchall_test extends UnitTestCase {

	/**
	* Fetch all objects
	*/
	public function test_fetchall_fail()
	{
		$params = array('title'=>'holamundo');
		$result = evalcomix_tool::fetch_all($params);
		$this->assertFalse($result);
	}	
	
	/**
	* Fetch all objects. Return an array with the required params
	*/
	public function test_fetchall_ok()
	{
		$params = array('title'=>'hola');
		$result = evalcomix_tool::fetch_all($params);
		$this->assertIsA($result,'array');
	}	
}