  <?php
/**
 * Unit tests for blocks/calamardo/classes/evalcomix_modes_extra.php
 *
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 * @package block-evalcomix
 */

/*if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); //  It must be included from a Moodle page
}*/

require_once(dirname(__FILE__) . '/../classes/evalcomix_modes_extra.php');
require_once(dirname(__FILE__) . '/../../../config.php');

//require_once($CFG->dirroot.'/grade/edit/tree/lib.php');//This require: global $CFG;

class evalcomix_modes_extra_test extends UnitTestCase {

	/**
	* Object construction with no params. The object instance must be create
	*/
	public function test_construction_ok() {
		$objectnew = new evalcomix_modes_extra();
		$this->assertNotNull($objectnew);
		unset($objectnew);
	}
		
	/**
	* Object construction with some params. The object instance must be create
	*/
	public function test_construction_ok_2() {
		$objectnew = new evalcomix_modes_extra('', '14','1');
		$this->assertNotNull($objectnew);
		unset($objectnew);
	}
	
	/**
	* Object construction with some params. The object instance must be create
	*/
	public function test_construction_ok_3() {
		$objectnew = new evalcomix_modes_extra('', '14');
		$this->assertNotNull($objectnew);
		unset($objectnew);
	}
	
	/**
	* Fetch all objects with a required not existing param
	*/
	public function test_fetchall_fail() {
		$params = array('modeid'=>'24');
		$result = evalcomix_modes_extra::fetch_all($params);
		$this->assertFalse($result);
		unset($params, $result);
	}	
	
	/**
	* Fetch all objects. Return an array with the required params
	*/
	public function test_fetchall_ok() {
		$params = array('modeid'=>'6');
		$result = evalcomix_modes_extra::fetch_all($params);
		$this->assertIsA($result,'array');
		unset($params, $result);
	}	

	/**
	* Fetch an object with no unique params. Return false
	*/
	public function test_fetch_fail() {
		$params = array('modeid'=>'2');
		$result = evalcomix_modes_extra::fetch($params);
		$this->assertFalse($result);
		unset($params, $result);
	}	
	
	/**
	* Fetch all objects. Return the object with the required params
	*/
	public function test_fetch_ok() {
		$params = array('modeid'=>'14');
		$result = evalcomix_modes_extra::fetch($params);
		$this->assertIsA($result,'evalcomix_modes');
		unset($params, $result);
	}	
	
	/**
	* An object instance doesn't exist
	*/
	public function test_exist_fail() {
		$objectnew = new evalcomix_modes_extra('11');
		$id = $objectnew->exist();
		$this->assertFalse($id);
		unset($objectnew);
	}
	
	/**
	* Exists
	*/
	public function test_exist_ok() {
		$objectnew = new evalcomix_modes_extra('3','11','0');
		$id = $objectnew->exist();
		$this->assertTrue($id);
		unset($objectnew);
	}
	
}