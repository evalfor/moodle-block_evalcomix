<?php
/**
 * Unit tests for blocks/calamardo/classes/webservice_evalcomix_client.php
 *
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 * @package block-evalcomix
 */

/*if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); //  It must be included from a Moodle page
}*/

require_once(dirname(__FILE__) . '/../classes/webservice_evalcomix_client.php');
require_once(dirname(__FILE__) . '/../../../config.php');

//require_once($CFG->dirroot.'/grade/edit/tree/lib.php');//This require: global $CFG;

class webservice_evalcomix_client_test extends UnitTestCase {

	/**
	* get_ws_createtool
	*/
	public function test_get_ws_createtool_ok() {
	$id = webservice_evalcomix_client::get_ws_createtool('','','4');
	$this->assertNotNull($id);
	$this->assertIsA($id,'string');
	}

	
	/**
	* get_ws_viewtool
	*/
	public function test_get_ws_viewtool_ok() {
	$id = webservice_evalcomix_client::get_ws_viewtool('4');
	$this->assertNotNull($id);
    $this->assertIsA($id,'string');	
	}
	
	
	/**
	* get_ws_deletetool
	*/
	/*public function test_get_ws_deletetool_ok() {
	$id = webservice_evalcomix_client::get_ws_deletetool('12_pla');
	$this->assertNotNull($id);	
    $this->assertIsA($id,'string');		
	}*/
	
	/**
	* get_ws_deletetool
	*/
	/*public function test_get_ws_deletetool_fail() {
	$id = webservice_evalcomix_client::get_ws_deletetool('18');
	$this->assertNotNull($id);	
	 $this->assertIsA($id,'string');	
	}*/
	
	
	/**
	* get_ws_assessment_form //$courseid = 0, $module = 0, $activity = 0, $student = 0, $assessor = 0, $mode = 'teacher', $lms = 0
	*/
	public function test_get_ws_assessment_form() {
	$id = webservice_evalcomix_client::get_ws_assessment_form();
	$this->assertNotNull($id);
    $this->assertIsA($id,'string');	
	}
	
	/**
	* get_ws_view_assessment_form
	*/
	public function test_get_ws_view_assessment() {
	$id = webservice_evalcomix_client::get_ws_view_assessment(4, 'forum', 4, 12, 15, 'teacher', 1);
	$this->assertNotNull($id);	
    $this->assertIsA($id,'string');		
	}
	
	/**
	* put_ws_savetask //$courseid, $module, $activity, $tool, $selftool, $peertool, $teacherweighing, $selfweighing, $peerweighing, $timeavailableAE, $timedueEI, $timedueAE, $lms
	*/
	/*public function test_put_ws_savetask() {
	$id = webservice_evalcomix_client::put_ws_savetask(5,'forum',4,2,3,4);
	$this->assertNotNull($id);		
	}*/
	
	/**
	* get_ws_savetask  //$courseid, $module, $activity, $lms
	*/
	public function test_get_ws_task() {
	$id = webservice_evalcomix_client::get_ws_task(5,'forum',4,1);
	$this->assertNotNull($id);
    $this->assertIsA($id,'SimpleXMLElement');	
	}
	
		
	/**
	* get_ws_list_tool  //$courseid, $lms
	*/
	
	public function test_get_ws_list_tool() {
	$id = webservice_evalcomix_client::get_ws_list_tool(5,1);
	$this->assertNotNull($id);
	$this->assertIsA($id,'array');
	}
	
	/**
	* check_url 
	*/
	public function test_check_url_fail() {
	$id = webservice_evalcomix_client::check_url('http://asfas');
	$this->assertEqual($id,0);		
	}
	
	/**
	* check_url 
	*/
	public function test_check_url() {
	$id = webservice_evalcomix_client::check_url('http://dipeval.uca.es');
	$this->assertNotNull($id);		
	}
	
	
	/**
	* encrypt_params 
	*/
	/*public function test_encrypt_params() {
	$id = webservice_evalcomix_client::encrypt_params($get, $key, $long = 0);
	$this->assertNotNull($id);		
	}*/
		
	
	
	
}