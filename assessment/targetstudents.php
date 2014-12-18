<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
 
include_once('../../../config.php');
include_once('lib.php');	
global $CFG;
include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_allowedusers.php');

$id = required_param('a', PARAM_INT);
$courseid = required_param('id', PARAM_INT);        // course id
$assessorid = required_param('u', PARAM_INT);

$context = context_course::instance($courseid);
$report_evalcomix = new grade_report_evalcomix($courseid, null, $context);
$users = $report_evalcomix->load_users(false);

echo '<select style="width:20em" size="20">';

if($allowedusers = evalcomix_allowedusers::fetch_all(array('cmid' => $id, 'assessorid' => $assessorid))){
	foreach($allowedusers as $alloweduser){
		$userid = $alloweduser->studentid;
		echo '<option>'.fullname($users[$userid]).'</option>';
	}
}

echo '</select>';

?>