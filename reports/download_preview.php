<?php 
require_once('../../../config.php');

$courseid 	   = required_param('id', PARAM_INT);        // course id
$mode		   = required_param('mode', PARAM_ALPHA);

global $OUTPUT, $DB, $CFG;

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
	print_error('nocourseid');
}

////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// Declaración de variables /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////

$PAGE->set_url(new moodle_url('/blocks/evalcomix/reports/index.php', array('id' => $courseid)));


////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// Comprobaciones de tipo de acceso /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////

require_login($course);
$context = get_context_instance(CONTEXT_COURSE, $courseid);


$PAGE->set_pagelayout('incourse');


// Print the header
$PAGE->navbar->add('evalcomix', new moodle_url('../assessment/index.php?id='.$courseid));

$strplural = 'reports';
$PAGE->navbar->add($strplural);
$PAGE->set_title($strplural);

$PAGE->set_heading($course->fullname);

if (function_exists('clean_param_array')) {
	$data = clean_param_array($_POST, PARAM_ALPHANUM);
}
elseif(function_exists('clean_param')){
	$data = clean_param($_POST, PARAM_ALPHANUM);
}
else{
	$data = $_POST;
}

if(isset($data['download'])){
	$courseid = required_param('courseid', PARAM_INT);
	$student_ids = required_param('student_ids', PARAM_RAW);
	$student_names = required_param('student_names', PARAM_RAW);
	$task = required_param('task', PARAM_INT);
	$mode = required_param('mode', PARAM_ALPHA);
	$assessorid = optional_param('assessor_id', 0, PARAM_INT);
	
	include_once($CFG->dirroot . '/blocks/evalcomix/reports/xls/export_xls.php');
	
	$report = new export_xls(array('courseid' => $courseid, 'mode' => $mode));
	
	$params['courseid'] = $courseid;
	$params['student_ids'] = $student_ids;
	$params['student_names'] = $student_names;
	$params['task'] = $task;
	$params['mode'] = $mode;
	if($assessorid){
		$params['assessor_id'] = $assessorid;
	}
	
	$report->send_export($params);
	exit;
}


if(!empty($formdata)){
	echo $OUTPUT->header();
	include_once($CFG->dirroot . '/blocks/evalcomix/reports/export.php');
	$params['courseid'] = $courseid;
	$params['mode'] = $mode;
	$params['format'] = $formdata['format'];
	
	$export = new export($params);
	$export->preprocess_data($formdata);
	$export->print_continue();
	$export->display_preview();
	echo $OUTPUT->footer();
}
else{
	header("Location:index.php?id=$courseid&mode=$mode");	
}

