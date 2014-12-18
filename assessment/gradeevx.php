<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>
 */
 
		include_once($CFG->dirroot."/blocks/evalcomix/configeval.php");
		include_once($CFG->dirroot."/blocks/evalcomix/classes/evalcomix_tasks.php");
		include_once($CFG->dirroot."/blocks/evalcomix/classes/evalcomix_assessments.php");
		include_once($CFG->dirroot."/blocks/evalcomix/classes/evalcomix_modes.php");
		global $DB;
		
		
		if(isset($grade->grade_item)){
			$block = $DB->get_record('modules', array('name' => $grade->grade_item->itemmodule));
			
			$cm = $DB->get_record('course_modules', array('course' => $courseid, 'module' => $block->id, 'instance' => $grade->grade_item->iteminstance));
			//preparing variables	    
			
			$module = $grade->grade_item->itemmodule;
			$instance = $cm->id;
			$maxgrade = $grade->grade_item->grademax;
			$student = $userid;	
			$grade_old = null;
			
			if($task = evalcomix_tasks::fetch(array('instanceid' => $cm->id))){			
				$assessments = evalcomix_assessments::fetch_all(array('taskid' => $task->id, 'studentid' => $userid));
				$teacherassessments = array();
				$peerassessments = array();
				$gradeself = null;
				$maxgradeteacher = null;
				$maxgradeself = null;
				$maxgradepeer = null;
				$weighingteacher = null;
				$weighingself = null;
				$weighingpeer = null;
				if($assessments){
					foreach($assessments as $assessment){
//						if(has_capability('block/evalcomix:edit',$context, $assessment->assessorid)){
						if(has_capability('moodle/grade:viewhidden',$context, $assessment->assessorid)){
							array_push($teacherassessments, $assessment->grade);
						}
						elseif($assessment->assessorid == $user->id){
							$gradeself = $assessment->grade;
							$maxgradeself = $task->maxgrade;
							if ($modality = evalcomix_modes::fetch(array('taskid' => $task->id, 'modality' => 'self'))) {
								$weighingself = $modality->weighing;
							}
						}
						else{
							array_push($peerassessments, $assessment->grade);
						}
					}
					if(!empty($teacherassessments)){
						$gradeteacher = array_sum($teacherassessments) / count($teacherassessments);
						$maxgradeteacher = $task->maxgrade;
						if ($modality = evalcomix_modes::fetch(array('taskid' => $task->id, 'modality' => 'teacher'))) {
							$weighingteacher = $modality->weighing;
						}
					}
					if(!empty($peerassessments)){
						$gradepeer = array_sum($peerassessments) / count($peerassessments);
						$maxgradepeer = $task->maxgrade;
						if ($modality = evalcomix_modes::fetch(array('taskid' => $task->id, 'modality' => 'peer'))) {
							$weighingpeer = $modality->weighing;
						}
					}
				}
				
				$grteacher = '';
				$grself = '';
				$grpeer = '';
				$evalcomixable = false;		//echo $instance . '-' . $module . '-'. $courseid . '<br>';
				if (!$grade_item = grade_item::fetch(array('iteminstance'=>$grade->grade_item->iteminstance, 'itemmodule'=>$module, 'courseid' => $courseid, 'itemnumber'=>'0'))) {
						error('Can not find grade_item 1');
				}
				$multfactor = $grade_item->multfactor;
				$plusfactor = $grade_item->plusfactor;
				
		//if($module == 'forum') echo "estudiante: $student; instance:$instance; gradeteacher:". $evalcomixgrade[$student][$module][$instance]['gradeteacher']."<br>";
				switch($module){
					default:{ 
						if(isset($gradeteacher) && is_numeric($gradeteacher) && $maxgradeteacher && $weighingteacher){ //echo "nota profe:".$gradeteacher."del estudiante ".$student."<br>";
							$grteacher = $gradeteacher * $maxgrade / $maxgradeteacher;		
							$grteacher = $grteacher * $weighingteacher / 100;
							//$grteacher *= $multfactor;
						}
				
						if(isset($gradeself) && is_numeric($gradeself) && $maxgradeself && $weighingself){//echo $maxgrade;echo $grself;
							$grself = $gradeself * $maxgrade / $maxgradeself;
							$grself = $grself * $weighingself / 100;
							//$grself *= $multfactor;
						}

						if(isset($gradepeer) && is_numeric($gradepeer) && $maxgradepeer && $weighingpeer){
							$grpeer = $gradepeer * $maxgrade / $maxgradepeer;
							$grpeer = $grpeer * $weighingpeer / 100;
							//$grpeer *= $multfactor;
						}

						if(is_numeric($grteacher) || is_numeric($grself) || is_numeric($grpeer)){
						//if($grteacher || $grself || $grpeer){
							$evalcomixable = true;
						}

						if($gradeval != '-' && $gradeval != '' && is_numeric($gradeval)){
							if($evalcomixable){//echo "-- teacher: ".$grteacher; echo "-self: ".$grself; echo "-peer: ".$grpeer; echo "-moodle: ".$gradeval;echo "<br>";
								//$gradeval = round(($grteacher + $grself + $grpeer + $gradeval) / 2);
								//$gradeval = ((($grteacher + $grself + $grpeer) * $multfactor) + $gradeval) / 2;
								$grade_old = new grade_grade(array('itemid'=>$grade_item->id, 'userid'=>$student));
								$grade_original = $grade_old->rawgrade;
								$gradeval = ($grteacher + $grself + $grpeer + $grade_original) / 2;
								$gradeval *= $multfactor;
								$gradeval += $plusfactor;
								$grademax = $grade_item->grademax;

								$maxcoef = isset($CFG->gradeoverhundredprocentmax) ? $CFG->gradeoverhundredprocentmax : 10; // 1000% max by default

								if (!empty($CFG->unlimitedgrades)) {
									$grademax = $grademax * $maxcoef;
								} else if ($grade_item->is_category_item() or $grade_item->is_course_item()) {
									$category = $grade_item->load_item_category();
									if ($category->aggregation >= 100) {
										// grade >100% hack
										$grademax = $grademax * $maxcoef;
									}
								}
								if($gradeval > $grademax){
									$gradeval = $grademax;
								}
								if($gradeval < $grade_item->grademin){
									$gradeval = $grade_item->grademin;
								}
							}
						}
						elseif(($gradeval == '-' || $gradeval == '')){
							if($evalcomixable){
								//$gradeval = round($grteacher + $grself + $grpeer);
								$grade_old = new grade_grade(array('itemid'=>$grade_item->id, 'userid'=>$student));
								$gradeval = $grteacher + $grself + $grpeer;
								$gradeval *= $multfactor;
								$gradeval += $plusfactor;
								if($gradeval > 100){
									$gradeval = 100;
								}
							}
						}
					}break;
				}
				
				if($evalcomixable){
					if(!$grade_item->scaleid && $grade->grade_item->itemnumber == 0){
						$overridden = $grade_old->overridden;
						$grade_item->update_final_grade($student, $gradeval, 'evalcomixAdd');
						$grade = new grade_grade(array('itemid'=>$grade_item->id, 'userid'=>$student));
						$grade->overridden = $overridden;
						$grade->update('EvalcomixUpdate');
		//				($grade);echo "<br><br>";
					}
				}
			}
		}

