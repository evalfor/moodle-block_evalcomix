<?php
/**
 * Unit tests for blocks/calamardo/classes/evalcomix_modes_time.php
 *
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 * @package block-evalcomix
 */

/*if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); //  It must be included from a Moodle page
}*/

require_once(dirname(__FILE__) . '/../classes/evalcomix_modes_time.php');
require_once(dirname(__FILE__) . '/../../../config.php');

//require_once($CFG->dirroot.'/grade/edit/tree/lib.php');//This require: global $CFG;

class evalcomix_modes_time_test extends UnitTestCase {

	/**
	* Object construction with no params. The object instance must be create
	*/
	public function test_construction_ok() {
		$objectnew = new evalcomix_modes_time();
		$this->assertNotNull($objectnew);
		unset($objectnew);
	}
	
	/**
	* Object construction with some params. The object instance must be create
	*/
	public function test_construction_ok_2() {
		$objectnew = new evalcomix_modes_time('', '1');
		$this->assertNotNull($objectnew);
		unset($objectnew);
	}
	
	
	/**
	* Object construction with more than required param. It should throw a 'moodle_exception'
	*/
	//NOT THROW AN EXCEPTION, WHY?? Because can't control param numbers with php. Deactivated test
	/*public function test_construction_fail_1() {
	   $this->expectException('moodle_exception');
	   $objectnew = new evalcomix_modes_time('', '11', '129', '24','80');
		//$this->assertNotNull($objectnew);
		unset($objectnew);
	}*/

	/**
	* Fetch all objects with a required not existing param
	*/
	public function test_fetchall_fail() {
		$params = array('modeid'=>'1');
		$result = evalcomix_modes_time::fetch_all($params);
		$this->assertFalse($result);
		unset($params, $result);
	}	
	
	/**
	* Fetch all objects. Return an array with the required params
	*/
	public function test_fetchall_ok() {
		$params = array('modeid'=>'14');
		$result = evalcomix_modes_time::fetch_all($params);
		$this->assertIsA($result,'array');
		unset($params, $result);
	}	

	/**
	* Fetch an object with no unique params. Return false
	*/
	public function test_fetch_fail() {
	    //$this->expectException('moodle_exception');
		$params = array('timeavailable'=>'1324987200');
		$result = evalcomix_modes_time::fetch($params);
		$this->assertFalse($result);
		unset($params, $result);
	}	
	
	/**
	* Fetch all objects. Return the object with the required params
	*/
	public function test_fetch_ok() {
		$params = array('modeid'=>'5');
		$result = evalcomix_modes_time::fetch($params);
		$this->assertIsA($result,'evalcomix_modes');
		unset($params, $result);
	}	
	
	/**
	* An object instance doesn't exist
	*/
	public function test_exist_fail() {
		$objectnew = new evalcomix_modes_time('11');
		$id = $objectnew->exist();
		$this->assertFalse($id);
		unset($objectnew, $id);
	}
	
	/**
	* Exists
	*/
	public function test_exist_ok() {
		$objectnew = new evalcomix_modes_time('1','2','1327755000','1329569400');
		$id = $objectnew->exist();
		$this->assertTrue($id);
		unset($objectnew, $id);
	}
	
}