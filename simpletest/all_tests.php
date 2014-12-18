<?php
/**
 * Unit tests for blocks/calamardo/classes/evalcomix_tool.php
 *
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 * @package block-evalcomix
 */

/*if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); //  It must be included from a Moodle page
}*/

require_once(dirname(__FILE__) . '/../../../../simpletest/autorun.php');
//require_once('/home/evalfor/simpletest/autorun.php');

class AllTests extends TestSuite {
    function AllTests() {
        $this->TestSuite('All tests for evalcomix_tool ' . SimpleTest::getVersion());
         $this->addFile(dirname(__FILE__) . '/testevalcomix_tool.php');
         $this->addFile(dirname(__FILE__) . '/testevalcomix.php');
         $this->addFile(dirname(__FILE__) . '/testevalcomix_assessments.php');
		 $this->addFile(dirname(__FILE__) . '/testevalcomix_modes.php');
		 $this->addFile(dirname(__FILE__) . '/testevalcomix_modes_extra.php');
		 $this->addFile(dirname(__FILE__) . '/testevalcomix_modes_time.php');
		 $this->addFile(dirname(__FILE__) . '/testevalcomix_tasks.php');
		 //$this->addFile(dirname(__FILE__) . '/testwebservice_evalcomix_client.php');
		 $this->addFile(dirname(__FILE__) . '/testcalculator_average.php');
    }
}
