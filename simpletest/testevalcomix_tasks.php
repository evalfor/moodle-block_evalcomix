<?php
/**
 * Unit tests for blocks/calamardo/classes/evalcomix_tasks.php
 *
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 * @package block-evalcomix
 */

/*if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); //  It must be included from a Moodle page
}*/

require_once(dirname(__FILE__) . '/../classes/evalcomix_tasks.php');
require_once(dirname(__FILE__) . '/../../../config.php');
require_once(dirname(__FILE__) . '/testblockevalcomix.php');


//require_once($CFG->dirroot.'/grade/edit/tree/lib.php');//This require: global $CFG;

class evalcomix_tasks_test extends blockevalcomix_test {

	function test_evalcomix_tasks() {
	
	}
	/**
	* Object construction with wrong required param 'instanceid'. It should throw a 'moodle_exception' exception
	*/
	public function test_constructor_fail_1() {
		$this->expectException('moodle_exception');
		$objectnew = new evalcomix_tasks('', 'AAA');
		unset($objectnew);
	}	
	
	/**
	* Object construction with required param 'instanceid'. It should throw a 'moodle_exception' exception
	*/
	public function test_constructor_ok() {
		$objectnew = new evalcomix_tasks('', '24');
		$this->assertNotNull($objectnew);
		unset($objectnew);
	}	
	
	/**
	* Object construction with all the required params. The object instance must be create
	*/
	public function test_construction_ok_2() {
		$objectnew = new evalcomix_tasks();
		$this->assertNotNull($objectnew);
		unset($objectnew);
	}

	/**
	* Fetch all objects with a required not existing param
	*/
	public function test_fetchall_fail() {
		$params = array('weighing'=>'13');
		$result = evalcomix_tasks::fetch_all($params);
		$this->assertFalse($result);
		unset($params, $result);
	}	
	
	/**
	* Fetch all objects. Return an array with the required params
	*/
	public function test_fetchall_ok() {
		$params = array('weighing'=>'50');
		$result = evalcomix_tasks::fetch_all($params);
		$this->assertIsA($result,'array');
		unset($params, $result);
	}	

	/**
	* Fetch an object with no unique params. Return false
	*/
	public function test_fetch_fail() {
		$this->expectException('moodle_exception');
		$params = array('weighing'=>'50');
		$result = evalcomix_tasks::fetch($params);
		//$this->assertFalse($result);
	}	
	
	/**
	* Fetch all objects. Return the object with the required params
	*/
	public function test_fetch_ok() {
		$params = array('instanceid'=>'26');
		$result = evalcomix_tasks::fetch($params);
		$this->assertIsA($result,'evalcomix_tasks');
		unset($params, $result);
	}	
	
	/**
	* An object instance doesn't exist
	*/
	public function test_exist_fail() {
		$objectnew = new evalcomix_tasks('4');
		$id = $objectnew->exist();
		$this->assertFalse($id);
		unset($objectnew, $id);
	}
	
	/**
	* Exists
	*/
	public function test_exist_ok() {
		$objectnew = new evalcomix_tasks('11','40','100.00000','50');
		$id = $objectnew->exist();
		$this->assertTrue($id);
		unset($objectnew, $id);
	}
		
	/**
	* Update existing object in the DB  ¿TIENE SENTIDO ESTE TEST?
	*/
	public function test_update() {
		$objectnew = new evalcomix_tasks('1','25','100.00000','50');
		$id = $objectnew->update();
		$this->assertTrue($id);
		unset($objectnew, $id);
	}
	
	/**
	* Update a recently insert object in the DB
	*/
	public function test_update_ok() {
		$objectnew = new evalcomix_tasks('', '17', '100', 'BB');
		$objectnew->insert();
		$id = $objectnew->update();
		$this->assertTrue($id);
		$objectnew->delete();
		unset($objectnew,$id);
	}	

	/**
	* Update one param
	*/
	public function test_one_param_update_ok() {
		$objectnew = new evalcomix_tasks('','17','100','BBB');
		$objectnew->insert();
		$params = array('maxgrade'=>'80');
		evalcomix_tasks::set_properties($objectnew, $params);
		$id = $objectnew->update();
		$this->assertTrue($id);
		$objectnew->delete();
		unset($objectnew, $id);
	}

	/**
	* Update more than one param
	*/
	public function test_more_than_one_param_update_ok() {
		$objectnew = new evalcomix_tasks('','20','100','AAA');
		$objectnew->insert();
		$params = array('maxgrade'=>'80','weighing'=>'70');
		evalcomix_tasks::set_properties($objectnew, $params);
		$id = $objectnew->update();
		$this->assertTrue($id);
		$objectnew->delete();
		unset($objectnew, $id);
	}
	
	/**
	* Update object in the DB. Return false because the object is not in the DB
	*/
	public function test_update_fail() {
		$objectnew = new evalcomix_tasks('', '22','100','LL');
		$id = $objectnew->update();
		$this->assertFalse($id);
		unset($objectnew);
	}
	
	/**
	* Obtains every id tasks for each course and returns them
	*/
	public function test_gettasksbycourseid_ok() {
		$result = evalcomix_tasks::get_tasks_by_courseid('22');
		$this->assertIsA($result,'array');
		unset($result);
	}

	/**
	* Get tasks from a not existing course
	*/
	public function test_gettasksbycourseid_fail() {
		$result = evalcomix_tasks::get_tasks_by_courseid('30');
		$zero = sizeof($result);
		$this->assertEqual($zero,'0');
		unset($result, $zero);
	}

	/**
	* Get tasks objects from a existing course
	*/		
	/*public function test_getcoursetasks_ok() {
		$result = evalcomix_tasks::get_course_tasks('22');
		$this->assertIsA($result,'array');
		unset($result);
	}*/

	/**
	* Get tasks from a not existing course
	*/
	/*public function test_getcoursetasks_fail() {
		$result = evalcomix_tasks::get_course_tasks('30');
		$zero = sizeof($result);
		$this->assertEqual($zero,'0');
		unset($result, $zero);
	}*/	
	
	/**
	* Get tasks id and activity names from a existing course
	*/		
	public function test_getmoodlecoursetasks_ok() {
		$result = evalcomix_tasks::get_moodle_course_tasks('22');
		$this->assertIsA($result,'array');
		unset($result);
	}

	/**
	* Fail to get tasks id and activity names from a not existing course
	*/
	public function test_getmoodlecoursetasks_fail() {
		$result = evalcomix_tasks::get_moodle_course_tasks('30');
		$zero = sizeof($result);
		$this->assertEqual($zero,'0');
		unset($result, $zero);
	}	
	
	/**
	* Get type task from an instanceid
	*/		
	public function test_gettypetask_ok() {
		$result = evalcomix_tasks::get_type_task('26');
		$this->assertIsA($result,'string');
		unset($result);
	}

	/**
	* Fail to get type task from a not existing instanceid
	*/
	public function test_gettypetask_fail() {
		$result = evalcomix_tasks::get_type_task('58');
		$this->assertNull($result);
		unset($result);
	}	
		
}
