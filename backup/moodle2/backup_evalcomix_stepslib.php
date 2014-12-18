<?php

/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
  
class backup_evalcomix_block_structure_step extends backup_block_structure_step {

    protected function define_structure() {
		
		// Define each element separated
		$evalcomix = new backup_nested_element('evalcomix', array('id'), array('viewmode'));
		$evalcomix_environment = new backup_nested_element('environment', null, array('courseid', 'moodlename'));
		$evalcomix_tools = new backup_nested_element('tools');
		$evalcomix_tool = new backup_nested_element('tool', array('id'), array('title', 'type', 'timecreated', 'timemodified', 'idtool', 'code'));
		$evalcomix_tasks = new backup_nested_element('tasks');
		$evalcomix_task = new backup_nested_element('task', array('id'), array('instanceid', 'maxgrade', 'weighing', 'timemodified', 'visible'));
		//$evalcomix_task = new backup_nested_element('task', array('instanceid'), array('id', 'maxgrade', 'weighing', 'timemodified'));
		$evalcomix_assessments = new backup_nested_element('assessments');
		//$evalcomix_assessment = new backup_nested_element('assessment', array('id'), array('assessorid', 'studentid', 'grade', 'timemodified', 'code'));
		$evalcomix_assessment = new backup_nested_element('assessment', array('id'), array('assessorid', 'studentid', 'grade', 'timemodified'));
		$evalcomix_modes = new backup_nested_element('modes');
		$evalcomix_mode = new backup_nested_element('mode', array('id'), array('toolid', 'modality', 'weighing'));
		$evalcomix_modes_time = new backup_nested_element('mode_time', array('id'), array('timeavailable', 'timedue'));
		$evalcomix_modes_extra = new backup_nested_element('mode_extra', array('id'), array('anonymous', 'visible', 'whoassesses'));
		$evalcomix_grades = new backup_nested_element('grades');
		$evalcomix_grade = new backup_nested_element('grade', array('id'), array('userid', 'cmid', 'finalgrade', 'courseid'));
		$evalcomix_allowedusers = new backup_nested_element('allowedusers');
		$evalcomix_alloweduser = new backup_nested_element('alloweduser', array('id'), array('cmid', 'assessorid', 'studentid'));
		// $invented = new backup_nested_element('invented', null, array('one', 'two', 'three')        );
		
		// Build the tree
		$evalcomix->add_child($evalcomix_tools);
		$evalcomix->add_child($evalcomix_environment);
		$evalcomix->add_child($evalcomix_tasks);
		$evalcomix->add_child($evalcomix_grades);
		$evalcomix_grades->add_child($evalcomix_grade);
		$evalcomix->add_child($evalcomix_allowedusers);
		$evalcomix_allowedusers->add_child($evalcomix_alloweduser);
		$evalcomix_tools->add_child($evalcomix_tool);
		$evalcomix_tasks->add_child($evalcomix_task);
		$evalcomix_task->add_child($evalcomix_assessments);
		$evalcomix_task->add_child($evalcomix_modes);
		$evalcomix_assessments->add_child($evalcomix_assessment);	
		$evalcomix_modes->add_child($evalcomix_mode);
		$evalcomix_mode->add_child($evalcomix_modes_time);
		$evalcomix_mode->add_child($evalcomix_modes_extra);
		//$inventeds->add_child($invented);
		
		// Define sources
		global $DB, $COURSE, $CFG;
		$courseid = $this->get_courseid(); 
		$cms = $DB->get_records('course_modules', array('course' => $courseid));
		$items = array();
		foreach($cms as $cm){
			$items[] = $cm->id;
		}
		$in_params = array();
		if(!empty($items)){
			list($in_sql, $in_params) = $DB->get_in_or_equal($items);
			foreach ($in_params as $key => $value) {
                $in_params[$key] = backup_helper::is_sqlparam($value);
			}	
		}
		
		if($block = $DB->get_record('block_evalcomix', array('courseid' => $courseid))){
			$evalcomix->set_source_table('block_evalcomix', array('id' => backup_helper::is_sqlparam($block->id)));
		}
		
		
		include_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
		$evalcomix_environment->set_source_array(array((object)array('courseid' => $COURSE->id, 'moodlename' => MOODLE_NAME)));
		
		try{
			$array_xml_tool = array();
			$xml = webservice_evalcomix_client::get_ws_xml_tools2(array('courseid' => $courseid));
			foreach($xml as $toolxml){
				$id = (string)$toolxml['id'];
				foreach($toolxml as $txml){
					$array_xml_tool[$id] = $txml->asXML();
				}
			}
			if($tools = $DB->get_records('block_evalcomix_tools', array('evxid' => $block->id))){
				
				$array = array();
				foreach($tools as $tool){
					$time = time();
					$idtool = $tool->idtool;
					if(isset($array_xml_tool[$idtool])){
						$array[] = (object)array('id' => $tool->id, 'title' => $tool->title, 'type' => $tool->type, 'timecreated' => $time, 'timemodified' => $time, 'idtool' => $idtool, 'code' => $array_xml_tool[$idtool]);
					}
				}
				$evalcomix_tool->set_source_array($array);
			}
		}
		catch(Exception $e){
		
		}
		
		/*$invented->set_source_array(array((object)array('one' => 1, 'two' => 2, 'three' => 3),
            (object)array('one' => 11, 'two' => 22, 'three' => 33))); // 2 object array*/
		
		//$evalcomix_tool->set_source_table('block_evalcomix_tools', array('evxid' => backup::VAR_PARENTID));

		
		if(!empty($in_params)){
			$evalcomix_task->set_source_sql("
                SELECT *
                  FROM {block_evalcomix_tasks}
                 WHERE instanceid $in_sql", $in_params);
				 
			$evalcomix_grade->set_source_sql("
                SELECT *
                  FROM {block_evalcomix_grades}
                 WHERE cmid $in_sql", $in_params);
				 
			$evalcomix_alloweduser->set_source_sql("
                SELECT *
                  FROM {block_evalcomix_allowedusers}
                 WHERE cmid $in_sql", $in_params);
		}
		
		$evalcomix_assessment->set_source_table('block_evalcomix_assessments', array('taskid' => backup::VAR_PARENTID));
		$evalcomix_mode->set_source_table('block_evalcomix_modes', array('taskid' => backup::VAR_PARENTID));
		$evalcomix_modes_time->set_source_table('block_evalcomix_modes_time', array('modeid' => backup::VAR_PARENTID));
		$evalcomix_modes_extra->set_source_table('block_evalcomix_modes_extra', array('modeid' => backup::VAR_PARENTID));
	
		// Define annotations
		$evalcomix_task->annotate_ids('course_modules', 'instanceid');
		//$evalcomix->set_source_table('block_evalcomix', array('id' => backup::VAR_BLOCKID));
		//$evalcomix->set_source_array(array((object)array('id' => $this->task->get_blockid())));
		
		//$evalcomix_tool->set_source_table('evalcomix_tools', array('evxid' => '../id'));

		
		return $this->prepare_block_structure($evalcomix);		
		//EvalCOMIX assessment-------------------------------------------------------	
		/*	$tasks = evalcomix_tasks::get_tasks_by_courseid($courseid);
			if(!empty($tasks)){ 
				$array_xml_assessment = array();
				$hash_assessment = array();
				$params['courseid'] = $courseid;
				$params['lms'] = MOODLE_NAME;
				$array_course_assessments = array();
				$coursecontext = context_course::instance($courseid);
			
				$k = 0;
				foreach($tasks as $task){ 
					$module = evalcomix_tasks::get_type_task($task->instanceid);
					if($assessments = $DB->get_records('block_evalcomix_assessments', array('taskid' => $task->id))){
						foreach($assessments as $assessment){
							$params['module'][$k] = $module;
							$params['activity'][$k] = $task->instanceid;
							$params['student'][$k] = $assessment->studentid;
							$params['assessor'][$k] = $assessment->assessorid;
							if($assessment->studentid == $assessment->assessorid){
								$params['mode'][$k] = 'self';
							}
							elseif(has_capability('moodle/grade:viewhidden',$coursecontext, $assessment->assessorid)){
								$params['mode'][$k] = 'teacher';
							}
							else{
								$params['mode'][$k] = 'peer';
							}
							$str = $courseid . '_' . $params['module'][$k] . '_' . $params['activity'][$k] . '_' . $params['student'][$k] . '_' . $params['assessor'][$k] . '_' . $params['mode'][$k] . '_' . MOODLE_NAME;
							$id = $assessment->id;
							$hash_assessment[$id] = md5($str);
							$array_course_assessments[] = $assessment;
							
							++$k;
						}
					}
				}
				$xml = webservice_evalcomix_client::get_ws_xml_tools($params);
				foreach($xml as $assessmentxml){
					$id = (string)$assessmentxml['id'];
					foreach($assessmentxml as $txml){
						$array_xml_assessment[$id] = $txml->asXML();
					}
				}
				
				$array_result = array();
				if(!empty($array_course_assessments)){
					foreach($array_course_assessments as $assessment){
						$time = time();
						$id = $assessment->id;
						$assid = $hash_assessment[$id];
						$array_xml = '';
						if(isset($array_xml_assessment[$assid])){
							$array_xml = $array_xml_assessment[$assid];
						}
						echo "task = " . $assessment->taskid . ": " . $assessment->assessorid . '--' . $assessment->studentid . "<br>";
						$array_result[] = (object)array('id' => $assessment->id, 'taskid' => $assessment->taskid, 'assessorid' => $assessment->assessorid, 'studentid' => $assessment->studentid, 'timemodified' => $time, 'grade' => $assessment->grade, 'code' => $array_xml);	
					}
					$evalcomix_assessment->set_source_array($array_result);
				}				
			}*/
    }
}
