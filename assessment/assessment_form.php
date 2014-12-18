<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
 
require_once('../../../config.php');	
require_once('lib.php');
include_once($CFG->dirroot .'/blocks/evalcomix/configeval.php');
include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');
include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');


$courseid 	   = required_param('id', PARAM_INT);        // course id
$toolid = required_param('t', PARAM_ALPHANUM); //toolid
$perspective = required_param('mode', PARAM_ALPHA);
$viewtemplate = optional_param('vt', '0', PARAM_INT); //It indicates what will be showed: ['1'] only the template tool or ['0'] tool filled with assessment

require_login($courseid);

$context = context_course::instance($courseid);


$url = new moodle_url('/blocks/evalcomix/assessment/assessment_form.php', array('courseid'=>$courseid, 't'=>$toolid));
$PAGE->set_url($url);
$PAGE->set_pagelayout('popup');
//echo $OUTPUT->header();

if(!$tool = evalcomix_tool::fetch(array('idtool' => $toolid))){
	print_error('EvalCOMIX: No tool enabled');
}

if($perspective != 'assess' && $perspective != 'view'){
	print_error('EvalCOMIX: the mode param is wrong');
}

global $USER, $DB;
$lang = current_language();

//To show tool used in a assessment
if($viewtemplate == '0'){
	$cmid = required_param('a', PARAM_INT);
	$studentid = required_param('s', PARAM_INT);
	require_capability('block/evalcomix:assessed', $context, $studentid);

	$lms = MOODLE_NAME;

	$module = evalcomix_tasks::get_type_task($cmid);
	//echo "$courseid - $module - $cmid - $studentid, - $assessor - $mode - $lms ";

	$user = $DB->get_record('user', array('id' => $studentid));
	if ($user) {
		$modinfo = get_fast_modinfo($courseid);
		$mods = $modinfo->get_cms();
		$mod = $mods[$cmid];
		$title = fullname($user) .get_string('studentwork2','block_evalcomix'). $mod->name;
	}

	$url_instrument = '';
	if($perspective == 'assess'){
		$mode = grade_report_evalcomix::get_type_evaluation($studentid, $courseid);
		if($task = evalcomix_tasks::fetch(array('instanceid' => $cmid))){
			if(!$modefetch = evalcomix_modes::fetch(array('taskid' => $task->id, 'modality' => $mode))){
				print_error('EvalCOMIX: No permissions');
			}
		}
		else{
			print_error('EvalCOMIX: The activity is not configured with EvalCOMIX');
		}
		$assessor = $USER->id;
		$url_instrument = webservice_evalcomix_client::get_ws_assessment_form($toolid, $lang.'_utf8', $courseid, $module, $cmid, $studentid, $assessor, $mode, $lms, 'assess', $title);
	}
	elseif($perspective == 'view'){
		$assessorid = required_param('as', PARAM_INT);
		
		if ($assessorid == $studentid){
			$mode = 'self';
		}
		else{
			//if (has_capability('block/evalcomix:edit',$context, $assessorid)){
			if (has_capability('moodle/grade:viewhidden',$context, $assessorid)){
				$mode = 'teacher';
			}
			elseif(has_capability('block/evalcomix:assessed',$context, $assessorid)){
				$mode = 'peer';
			}
			else{
				print_error('EvalCOMIX: Wrong User');
			}
		}

		$url_instrument = webservice_evalcomix_client::get_ws_viewtool($toolid, $lang.'_utf8', $courseid, $module, $cmid, $studentid, $assessorid, $mode, $lms, $title);
	}
}
elseif($viewtemplate == '1'){
	//require_capability('block/evalcomix:edit', $context, $USER->id);
	require_capability('moodle/grade:viewhidden', $context, $USER->id);
	$url_instrument = webservice_evalcomix_client::get_ws_viewtool($toolid, $lang.'_utf8');
}

$vars = explode('?', $url_instrument);
include_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');
		
$curl = new Curly();
$response = $curl->post($vars[0], $vars[1]);
if ($response && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){
	echo $response;
}
else{
	print_error('EvalCOMIX cannot get datas');
}

if($viewtemplate == 0){
	echo "<script>
	
	window.opener.onunload=function(){
		doWork('evalcomixtablegrade', 'servidor.php?id=".$courseid."&eva=".$USER->id."', 'courseid=".$courseid."&page=&stu=".$studentid."&cma=".$cmid."');
		close();
	};
	
	/*function testParent() {
		if (window.opener != null && !window.opener.closed){
			setTimeout(\"testParent()\",1);
		}
		else {
			alert('Parent closed/does not exist.');
			doWork('evalcomixtablegrade', 'servidor.php?id=".$courseid."&eva=".$USER->id."', 'courseid=".$courseid."&page=&stu=".$studentid."&cma=".$cmid."');
			window.close();
		}
}  
	testParent()¨*/
</script>";
}

//$report_evalcomix->process_data($datapost);

/*$o = '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="moodle, evalcomix" />
<link rel="stylesheet" type="text/css" href="../styles/main.css" />
</head>
<body style="margin:0; font-family:arial">
';

$user = $DB->get_record('user', array('id' => $studentid));
if ($user) {
	$modinfo = get_fast_modinfo($courseid);
	$mods = $modinfo->get_cms();
	$mod = $mods[$cmid];
//	$o .= '<center><div style="font-weight:bold; ">'.$mod->name.'</div></center>';
	//$o .= '<div class="userpic">'. $OUTPUT->user_picture($user) .'<a style="margin:1em;text-decoration:none" href="'.$CFG->wwwroot.'/user/view.php?id='.$user->id.'&course='.$courseid.'">'. fullname($user) .'</a></div><br>';
	//$title = $user->username .get_string('studentwork2','block_evalcomix');
	$title = $user->username .get_string('studentwork2','block_evalcomix'). $mod->name;
	
$o .= '<h1>'. $title.'</h1>';

}

$o .= '
<iframe src="'. $url_instrument.'" width="100%" frameborder=0 marginwidth="0" style="height:200em;border:0;margin:0; padding:0">
</iframe>
';
$o .= '
</body>
</html>';
echo $o;
*/
//echo $OUTPUT->footer();