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

$PAGE->set_url(new moodle_url('/blocks/evalcomix/reports/index.php', array('id' => $courseid, 'mode' => $mode)));


////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// Comprobaciones de tipo de acceso /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////

require_login($course);
$context = context_course::instance($courseid);


$PAGE->set_pagelayout('incourse');


// Print the header
$PAGE->navbar->add('evalcomix', new moodle_url('../assessment/index.php?id='.$courseid));

$strplural = 'reports';
$PAGE->navbar->add($strplural);
$PAGE->set_title($strplural);

$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();
//-----------------------------------------------------------------------------------------------

$output = $PAGE->get_renderer('block_evalcomix');
$params['action'] = 'index.php?id='.$courseid.'&mode='.$mode;
$params['courseid'] = $courseid;
$params['context'] = $context;

switch ($mode){
	case 'selftask':
		echo $output->view_form_selftask($params);
	break;
	case 'teachertask':
		echo $output->view_form_teachertask($params);
	break;
	default:{
		global $PAGE;
		
		$output = $PAGE->get_renderer('block_evalcomix');
		echo $output->logoheader();
		echo '<center><input type="button" style="color:#333333" value="'.get_string('assesssection', 'block_evalcomix').'" onclick="location.href=\''. $CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'"></center><br>';
	}				
}


//-----------------------------------------------------------------------------------------------
echo $OUTPUT->footer();
