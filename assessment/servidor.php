<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza S�nchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hern�ndez <juanantonio.caballero@uca.es>
 */
 
	require_once('../../../config.php');	
	
	
	if (function_exists('clean_param_array')) {
		$datapost = clean_param_array($_POST, PARAM_ALPHANUM);
		$dataget = clean_param_array($_GET, PARAM_ALPHANUM);
	}
	elseif(function_exists('clean_param')){
		$datapost = clean_param($_POST, PARAM_ALPHANUM);
		$dataget = clean_param($_GET, PARAM_ALPHANUM);
	}
	else{
		$datapost = $_POST;
		$dataget = $_GET;
	}
	
	if(isset($datapost['stu']) && isset($datapost['cma']) && isset($dataget['id']) && isset($dataget['eva'])){
		require_once('lib.php');	
		
		$courseid = $dataget['id'];
		$assessorid = $dataget['eva'];
		
		$page = $datapost['page'];
		$context = context_course::instance($courseid);
		$report_evalcomix = new grade_report_evalcomix($courseid, null, $context, $page);
		$userid = $datapost['stu'];
		$cmid = $datapost['cma'];
		$report_evalcomix->process_data($datapost);
		//Obtains course�s users
		$users = $report_evalcomix->load_users();
		
		$coursegroups = $report_evalcomix->load_groups();
		$coursegroupings = $report_evalcomix->load_groupings();
		
		$finalgrades = evalcomix_grades::get_grades($courseid);
		//$finalgrades = $report_evalcomix->get_grades();
		//echo $report_evalcomix->create_grade_table();
		
		$showdetails = true;
		$configured = $report_evalcomix->configured_activity($cmid);
		//Only show the user�s grade or all grades if the USER is a teacher or admin
		//if ((has_capability('block/evalcomix:edit',$context, $USER->id) || $userid == $USER->id) && isset($finalgrades[$cmid][$userid])){
		if ((has_capability('moodle/grade:viewhidden',$context, $USER->id) || $userid == $USER->id) && isset($finalgrades[$cmid][$userid])){
			if ($finalgrades[$cmid][$userid] != -1){
				echo format_float($finalgrades[$cmid][$userid], 2);
			}
			else{
				echo '-';
			}
		}
		else { //there is not grade
			if($configured){
				echo '-';
			}
			else{
				echo '<span style="font-style:italic;color:#f54927">'.get_string('noconfigured', 'block_evalcomix').'</span>';
			}
			$showdetails = false;
		}
		
		if ($configured){
			include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
			include_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
			include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_assessments.php');
			include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_allowedusers.php');
			include_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
			include_once($CFG->dirroot . '/blocks/evalcomix/assessment/lib.php');
		
			
			$mode = grade_report_evalcomix::get_type_evaluation($userid, $courseid);
			//Obtains required parameters to create details and evaluate links
			$type_instrument = evalcomix_tasks::get_type_task($cmid);
			$task = evalcomix_tasks::fetch(array('instanceid' => $cmid));
			$tool = get_evalcomix_modality_tool($courseid, $task->id, $mode);
			//$url_instrument = webservice_evalcomix_client::get_ws_assessment_form($tool->idtool, $courseid, $type_instrument, $cmid, $userid, $assessorid, $mode, MOODLE_NAME);
			$url_instrument = 'assessment_form.php?id='.$courseid.'&a='.$cmid.'&t='.$tool->idtool.'&s='.$userid.'&mode=assess';
			$task = evalcomix_tasks::fetch(array('instanceid' => $cmid));
			
			//Evaluate, Delete and Details buttons
			$evaluate = '<input type="image" value="'.get_string('evaluate', 'block_evalcomix').'" title="'.get_string('evaluate', 'block_evalcomix').'" style="width:16px;" src="../images/evaluar.png" onclick="javascript:url(\'' . $url_instrument . '\',\'' . $userid . '\',\'' . $cmid . '\',\'' . $page . '\',\'' . $courseid . '\');"/>';
			if($assessmentgrade = evalcomix_assessments::fetch(array('taskid'=>$task->id, 'assessorid'=>$assessorid, 'studentid'=>$userid))){
				$evaluate = '<input type="image" value="'.get_string('evaluate', 'block_evalcomix').'" title="'.get_string('evaluate', 'block_evalcomix').'" style="width:16px;" src="../images/evaluar2.png" onclick="javascript:url(\'' . $url_instrument . '\',\'' . $userid . '\',\'' . $cmid . '\', \'' . $page . '\',\'' . $courseid . '\');"/>';
			}					
			if ($showdetails) {
				$details = '<input type="image" value="'.get_string('details', 'block_evalcomix').'" style="width:16px" title='.get_string('details', 'block_evalcomix').' src="../images/lupa.png" onclick="javascript:urlDetalles(\''. $CFG->wwwroot. '/blocks/evalcomix/assessment/details.php?cid=' . $context->id . '&itemid=' . $task->id . '&userid=' . $userid . '&popup=1\');"/>';
			}
			else {
				$details = '';
			}
						
			//Show user�s works
			$title = get_string('studentwork1','block_evalcomix').get_string('studentwork2','block_evalcomix'). $cmid;
			echo ' <input type="image" value="'.$title.'" style="background-color:transparent;width:13px" title="'.$title.'" src="../images/task.png" 
						 onclick="javascript:urlDetalles(\''. $CFG->wwwroot. '/blocks/evalcomix/assessment/user_activity.php?id='.$userid.'&course='.$courseid.'&mod='.$cmid.'\');"/>';						
						 
			//If the $USER isn�t a teacher or admin evaluate if it should show Evaluate and Details buttons
			if($mode == 'self' || $mode == 'peer'){
				//Obtains the groupmode of the activity
				//$groupmode = $report_evalcomix->get_groupmode($cmid);
				
				//$gid_loginuser = $report_evalcomix->get_groupid($assessorid);
				//$gid_user = $report_evalcomix->get_groupid($userid);
				$cm = $DB->get_record('course_modules', array('id' => $cmid));
				$groupmode = $cm->groupmode;
				$groupmembersonly = 0;
				if(isset($cm->groupmembersonly)){
					$groupmembersonly = $cm->groupmembersonly;
				}
				$groupingid = $cm->groupingid;
				$same_grouping = false;
				$same_group = $report_evalcomix->same_group($assessorid, $userid);
				
				if($groupingid != 0){
					$same_grouping = $report_evalcomix->same_grouping_by_users($assessorid, $userid, $cm);
				}
				$gid_loginuser = $report_evalcomix->get_groupids($assessorid);
				$gid_user = $report_evalcomix->get_groupids($userid);
				
				$condition = false;
				if($evalcomixallowedusers = evalcomix_allowedusers::fetch(array('cmid' => $cmid, 'assessorid' => $assessorid, 'studentid' => $userid))){
					$condition = true;
				}
							
							//echo $this->activities['name'][$i] . ': ' . $groupmode . ' - gid_loginuser: ' . $gid_loginuser . ' - gid_user: ' . $gid_user . '<br/>';
							
							//If (there are not Separated groups) OR 
							//   (there are Separated groups AND 
									//($user is member of the same group that login $USER OR they are in the same grouping in that activity))
				//if(($groupmode != 1) || ($groupmode == 1 && 
					//				($gid_loginuser == $gid_user || $report_evalcomix->same_grouping($gid_loginuser, $gid_user, $cmid)))){
				if((!$groupmembersonly && (
					($same_grouping && (
							($groupmode != 1 
							|| $same_group))
					) 
					|| 
					(!$groupingid && 
						(($groupmode != 1 
							|| $same_group)))
					)
				)
				|| ($groupmembersonly  && (
//					(!$groupingid && $gid_loginuser != -1 && $gid_user != -1)
					(!$groupingid && 
						(($groupmode != 1 
							|| $same_group)))
					||
					($same_grouping && (
						($groupmode != 1 
							|| $same_group)))
					)
				)
				|| $condition == true
				){							
					$mode_time = $report_evalcomix->get_modestime($task->id, $mode);
					if($mode_time != false){
						$now = getdate();
						$now_timestamp = mktime($now["hours"], $now["seconds"], $now["minutes"], $now["mon"], $now["mday"], $now["year"]);
									
						$available = $mode_time->timeavailable;
						$due = $mode_time->timedue;
									
						if($mode == 'self') { //Details always are shown in selfassessment
							echo $details;
						}
						elseif($now_timestamp >= $due && $mode == 'peer' && $showdetails == true) {	
							$url_peer_instrument = webservice_evalcomix_client::get_ws_view_assessment($courseid, $type_instrument, $cmid, $assessorid, $userid, 'peer', MOODLE_NAME);
							echo '<input type="image" value="'.get_string('details', 'block_evalcomix').'" style="width:16px" title='.get_string('details', 'block_evalcomix').' src="../images/lupa.png" onclick="javascript:urlDetalles(\''. $url_peer_instrument .'\');"/>';
						}
						//Show the buttons if they must be availables
						if($now_timestamp >= $available && $now_timestamp < $due){
							echo $evaluate;
						}
					}								
				}								
			}
			else{ //if $mode == 'teacher'
				echo $details.$evaluate;
			}							
		}
		
		
	}