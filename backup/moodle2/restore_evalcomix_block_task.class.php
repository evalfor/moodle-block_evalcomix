<?php

/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
 
require_once($CFG->dirroot . '/blocks/evalcomix/backup/moodle2/restore_evalcomix_stepslib.php'); // We have structure steps

class restore_evalcomix_block_task extends restore_block_task {

    protected function define_my_settings() {
    }

    protected function define_my_steps() {
        // rss_client has one structure step
        $this->add_step(new restore_evalcomix_block_structure_step('evalcomix_structure', 'evalcomix.xml'));
    }

    public function get_fileareas() {
        return array(); // No associated fileareas
    }

    public function get_configdata_encoded_attributes() {
        return array(); // No special handling of configdata
    }

    static public function define_decode_contents() {
	/*$contents = array();
	$contents[] = new restore_decode_content('evalcomix', array('intro'), 'evalcomix');
	return $contents();*/
	return array();
    }

    static public function define_decode_rules() {
	/*$rules = array();
	
	$rules[] = new restore_decode_rule();
	
	return $rules;*/
	  return array();
    }
	
	/*
	public function after_restore(){
		global $DB, $COURSE, $CFG;print_r($this);exit;
		include_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes_time.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes_extra.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_assessments.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
		
		//$this->oldcontextid; busco en tabla mdl_context->instanceid es el courseid; con el courseid obtengo todos los cms antiguos y nuevos y voy copiando uno a uno
		//$this->basepath   = $CFG->dataroot . '/temp/backup/' . $controller->get_tempdir();
		$blockid = $this->get_old_blockid();
		$xml = simplexml_load_file($this->get_basepath() . '/course/blocks/evalcomix_'. $blockid .'/evalcomix.xml');
		
		$evxid_old = (int)$xml->evalcomix['id'];
		$viewmode_old = (string)$xml->evalcomix->viewmode;

		$courseid_old = $xml->evalcomix->environment->courseid;
		$moodlename_old = $xml->evalcomix->environment->moodlename;
		
		include_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
		$courseid_new = $this->plan->get_courseid();
		$moodlename_new = MOODLE_NAME;
		$block_evalcomix = $DB->get_record('block_evalcomix', array('courseid' => $courseid_new));
		
		$tasksid = '';
		foreach($xml->evalcomix->tasks[0] as $task){
			$task_id_old = (int)$task['id'];
			$task_instanceid_old = (int)$task->instanceid;
			$task_maxgrade_old = (string)$task->maxgrade;
			$task_weighing_old = (string)$task->weighing;
			$cm = $DB->get_record('block_evalcomix', array('courseid' => $courseid_new));
			$cm_mapping = restore_structure_step::get_mapping('course_module', $task_instanceid_old);
			$newcmid = $cm_mapping->newitemid;
			
			$task_object = new evalcomix_tasks('', $newcmid, $task_maxgrade_old, $task_weighing_old);
			$newtaskid = $task_object->insert();
			$tasksid .= $task_instanceid_old . '-' . $newcmid . ',';
			foreach($task->modes[0] as $mode){
				$mode_id_old = (int)$mode['id'];
				$mode_toolid_old = (string)$mode->toolid;
				$mode_modality_old = (string)$mode->modality;
				$mode_weighing_old = (string)$mode->weighing;
				
				$tool_mapping = restore_structure_step::get_mapping('evalcomix_tool', $mode_toolid_old);
				$newtoolid = $tool_mapping->newitemid;
			
				$mode_object = new evalcomix_modes('', $newtaskid, $newtoolid, $mode_modality_old, $mode_weighing_old);
				$newmodeid = $mode_object->insert();
			
			
				if(isset($mode->mode_time['id'])){
					$mode_time_id_old = (string)$mode->mode_time['id'];
					$mode_time_timeavailable_old = (string)$mode->mode_time->timeavailable;
					$mode_time_timedue_old = (string)$mode->mode_time->timedue;
					$mode_time_object = new evalcomix_modes_time('', $newmodeid, $mode_time_timeavailable_old, $mode_time_timedue_old);
					$mode_time_object->insert();
				}
				
				if(isset($mode->mode_extra)){
					$mode_extra_id_old = (string)$mode->mode_extra['id'];
					$mode_extra_timeavailable_old = (string)$mode->mode_extra->anonymous;
					$mode_extra_object = new evalcomix_modes_extra('', $newmodeid, $mode_extra_timeavailable_old);
					$mode_extra_object->insert();
				}
				
			}
			
			foreach($task->assessments[0] as $assessment){
				$assessment_id_old = (string)$assessment['id'];
				$assessment_assessorid_old = (string)$assessment->assessorid;
				$assessment_studentid_old = (string)$assessment->studentid;
				$assessment_grade_old = (string)$assessment->grade;
				$assessor_user = restore_structure_step::get_mapping('user', $assessment_assessorid_old);
				$student_user = restore_structure_step::get_mapping('user', $assessment_studentid_old);
				$assessment_object = new evalcomix_assessments('', $newtaskid, $assessor_user->newitemid, $student_user->newitemid, $assessment_grade_old);
				$assessment_object->insert();
			}
		}
		if($tasksid != ''){
			$tasksid = substr($tasksid, 0, -1);
		}
		$result = webservice_evalcomix_client::duplicate_course($courseid_old, $courseid_new, $moodlename_old, $moodlename_new, $tasksid);
		//$newtools = webservice_evalcomix_client::get_ws_list_tool($courseid_new, $moodlename_new);
		
	}*/
}

