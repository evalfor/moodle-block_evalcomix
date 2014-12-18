<?php
require_once('../../../config.php');

$courseid 	   = required_param('id', PARAM_INT);        // course id
$cmid 		   = required_param('tid', PARAM_INT);  
$modality 	   = required_param('mode', PARAM_ALPHA);
$assessorid      = optional_param('a', 0, PARAM_INT);	

global $CFG;
require_once($CFG->dirroot . '/blocks/evalcomix/assessment/lib.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes.php');
	
$context = get_context_instance(CONTEXT_COURSE, $courseid);	
$report_evalcomix = new grade_report_evalcomix($courseid, null, $context);
$users = $report_evalcomix->load_users();

$assessed_users = array();
						
if($task = evalcomix_tasks::fetch(array('instanceid' => $cmid))){
	if($mode = evalcomix_modes::fetch(array('taskid' => $task->id, 'modality' => $modality))){
		foreach($users as $user){
			if(!$assessorid){
				$assessorid = $user->id;
			}
			//It obtains assignments for each task and user
			$params2 = array('taskid' => $task->id, 'studentid' => $user->id, 'assessorid' => $assessorid);
			$assessments = evalcomix_assessments::fetch_all($params2);
			if($assessments){
				$assessed_users[] = $user;
			}
		}
	}
}

if(empty($assessed_users)){
	$output = html_writer::start_tag('div', array('style' => 'font-style:italic'));
	$output .= get_string('nostudentselfassessed', 'block_evalcomix');
	$output .= html_writer::end_tag('div');
}
else{
	$num_users = 0;
	$output = html_writer::start_tag('table');
	foreach($assessed_users as $user){
		$output .= html_writer::start_tag('tr');
		$output .= html_writer::start_tag('td', array('style' => 'margin:0;padding:0'));
		$output .= html_writer::empty_tag('input', array('type' => 'checkbox', 'checked' => 'checked', 'name' => 'user_'. $num_users, 'value' => $user->id));
		$output .= html_writer::end_tag('td');
		$output .= html_writer::start_tag('td', array('style' => 'margin:0;padding:0'));
		$output .= html_writer::start_tag('label', array('for' => 'user_'. $num_users));
		$output .= $user->lastname .', '.$user->firstname;
		$output .= html_writer::end_tag('label');
		$output .= html_writer::end_tag('td');
		$output .= html_writer::end_tag('tr');
		$output .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'username_'. $num_users, 'value' => $user->firstname));
		$output .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'usersurname_'. $num_users, 'value' => $user->lastname));
		++$num_users;
	}

	$output .= html_writer::end_tag('table');
	$output .= html_writer::empty_tag('input', array('type' => 'button', 'value' => get_string('selectallany', 'block_evalcomix'), 'onclick' => 'select_all_in()'));
	$output .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'nu', 'value' => $num_users));
}
echo $output;
