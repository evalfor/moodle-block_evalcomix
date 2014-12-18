<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

class restore_evalcomix_block_structure_step extends restore_structure_step {

    protected function define_structure() {

        $paths = array();

        $paths[] = new restore_path_element('evalcomix', '/block/evalcomix');
		$paths[] = new restore_path_element('evalcomix_tool', '/block/evalcomix/tools/tool');
		/*$paths[] = new restore_path_element('evalcomix_task', '/block/evalcomix/tasks/task');
		$paths[] = new restore_path_element('evalcomix_mode', '/block/evalcomix/tasks/modes/mode');
		$paths[] = new restore_path_element('evalcomix_mode_time', '/block/evalcomix/tasks/modes/mode/mode_time');
		$paths[] = new restore_path_element('evalcomix_mode_extra', '/block/evalcomix/tasks/modes/mode/mode_extra');
		//$paths[] = new restore_path_element('feed', '/block/rss_client/feeds/feed');
		*/
        return $paths;
    }

    public function process_evalcomix($data) {
        global $DB, $CFG;
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix.php');
		
        $data = (object)$data;
		$oldid = $data->id;
		
		$data->courseid = $this->get_courseid();
		if(!evalcomix::fetch(array('courseid' => $data->courseid))){
			$newitemid = $DB->insert_record('block_evalcomix', $data);
			$this->set_mapping('evalcomix', $oldid, $newitemid);
		}
		else{
			$this->set_mapping('evalcomix', $oldid, $oldid);
		}
    }
	
	public function process_evalcomix_tool($data){
		global $DB, $CFG;
		
		$data = (object)$data;
		$oldid = $data->id;
		if($data->type != 'tmp'){
			$data->evxid = $this->get_new_parentid('evalcomix');
			$data->timemodified = $this->apply_date_offset($data->timemodified);
			//$data->timemodified = $this->apply_date_offset(-1);
			$data->timecreated = $this->apply_date_offset($data->timecreated);
			
			include_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
			$newidtool = false;
			if($newidtool = webservice_evalcomix_client::duplicate_tool($data->idtool)){
				//$data->idtool = $this->apply_date_offset($newidtool);		
				$data->idtool = (string)$newidtool;
				
			}
			else{
				if(isset($data->code)){
					$xml = $data->code;
					if($xmlobject = simplexml_load_string($xml)){
						try{
							$newidtool = webservice_evalcomix_client::post_ws_xml_tools(array('toolxml' => $data->code));
							$data->idtool = (string)$newidtool;
						}
						catch(Exception $e){
							echo "EvalCOMIX no configured correctly";
						}
					}
					else{
						echo "No tool id ". $data->idtool;
					}
				}
				else{
					echo "No tool id ". $data->idtool;
				}
			}
			if($newidtool){
				$newitemid = $DB->insert_record('block_evalcomix_tools', $data);
				$this->set_mapping('evalcomix_tool', $oldid, $newitemid);
			}
		}
	}
	/*
	public function process_evalcomix_grade($data){
		global $DB, $CFG;
		
		$data = (object)$data;
		$oldid = $data->id;
		$cm_mapping = $this->get_mapping('course_module', $data->cmid);
		$data->cmid = $cm_mapping->newitemid;
		$data->courseid = $data->courseid;
		$data->userid = $this->get_mapping('user', $data->userid);
		
		$newitemid = $DB->insert_record('block_evalcomix_grades', $data);
		$this->set_mapping('evalcomix_grade', $oldid, $newitemid);
		
	}
	
	/*public function process_evalcomix_task($data){
		global $DB;

		$data = (object)$data;
		$oldid = $data->id;

		$data->instanceid = $this->get_mappingid('course_modules', $data->instanceid);
		$data->timemodified = $this->apply_date_offset($data->timemodified);

		$newitemid = $DB->insert_record('block_evalcomix_tasks', $data);
		$this->set_mapping('evalcomix_task', $oldid, $newitemid);
	}
	
	public function process_evalcomix_mode($data){
		global $DB;
		
		$data = (object)$data;
		$oldid = $data->id;

		$data->taskid = $this->get_new_parentid('evalcomix_task');
		$data->toolid = $this->get_mappingid('evalcomix_tools', $data->toolid);
			
		$newitemid = $DB->insert_record('block_evalcomix_modes', $data);
		$this->set_mapping('evalcomix_mode', $oldid, $newitemid);
	}
	
	public function process_evalcomix_mode_time($data){
		global $DB;
		
		$data = (object)$data;
		$oldid = $data->id;

		$data->modeid = $this->get_new_parentid('evalcomix_mode');
		$data->timeavailable = $this->apply_date_offset($data->timeavailable);
		$data->timedue = $this->apply_date_offset($data->timedue);
			
		$newitemid = $DB->insert_record('block_evalcomix_modes_time', $data);
		$this->set_mapping('evalcomix_mode_time', $oldid, $newitemid);
	}
	
	public function process_evalcomix_mode_extra($data){
		global $DB;
		
		$data = (object)$data;
		$oldid = $data->id;

		$data->modeid = $this->get_new_parentid('evalcomix_mode');
			
		$newitemid = $DB->insert_record('block_evalcomix_modes_extra', $data);
		$this->set_mapping('evalcomix_mode_extra', $oldid, $newitemid);
	}
	
	protected function after_execute() {
		global $CFG;
		include_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');

		$fullpath = $this->task->get_taskbasepath();
        // We MUST have one fullpath here, else, error
        if (empty($fullpath)) {
            throw new restore_step_exception('restore_structure_step_undefined_fullpath');
        }

        // Append the filename to the fullpath
        $fullpath = rtrim($fullpath, '/') . '/' . $this->filename;

        // And it MUST exist
        if (!file_exists($fullpath)) { // Shouldn't happen ever, but...
            throw new restore_step_exception('missing_moodle_backup_xml_file', $fullpath);
        }
		$xml = simplexml_load_file($fullpath);
		$newcourseid = $this->get_courseid();
		
		$context = get_context_instance(CONTEXT_COURSE, $newcourseid);
		$filename = $CFG->dataroot . '/temp/backup/' . $newcourseid . '-' . $context->id;
		$file = fopen ($filename, 'w+');
		fputs  ($file, $xml->asXML());
		fclose ($file);
		
		$oldcourseid = (string)$xml->evalcomix->environment->courseid;
		$newlms = MOODLE_NAME;
		$oldlms = (string)$xml->evalcomix->environment->moodlename;
		
		//$result = webservice_evalcomix_client::duplicate_course($oldcourseid, $newcourseid, $oldlms, $newlms);
    }*/
	public function after_restore(){
		global $DB, $COURSE, $CFG;
		include_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes_time.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes_extra.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_assessments.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_grades.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_allowedusers.php');
		
		$settings = $this->task->get_info()->root_settings;
	
		//$this->oldcontextid; busco en tabla mdl_context->instanceid es el courseid; con el courseid obtengo todos los cms antiguos y nuevos y voy copiando uno a uno
		//$this->basepath   = $CFG->dataroot . '/temp/backup/' . $controller->get_tempdir();
		//$blockid = $this->get_old_blockid();
		//$xml = simplexml_load_file($this->get_basepath() . '/course/blocks/evalcomix_'. $blockid .'/evalcomix.xml');
		$fullpath = $this->task->get_taskbasepath();
        // We MUST have one fullpath here, else, error
        if (empty($fullpath)) {
            throw new restore_step_exception('restore_structure_step_undefined_fullpath');
        }

        // Append the filename to the fullpath
        $fullpath = rtrim($fullpath, '/') . '/' . $this->filename;

        // And it MUST exist
        if (!file_exists($fullpath)) { // Shouldn't happen ever, but...
            throw new restore_step_exception('missing_moodle_backup_xml_file', $fullpath);
        }
		$xml = simplexml_load_file($fullpath);
		
		//echo $this->task->get_taskbasepath() . '/course/blocks/evalcomix_'. $blockid .'/evalcomix.xml';
		$evxid_old = (int)$xml->evalcomix['id'];
		$viewmode_old = (string)$xml->evalcomix->viewmode;

		if(isset($xml->evalcomix->environment->courseid)){
			$courseid_old = $xml->evalcomix->environment->courseid;
		}
		if(isset($xml->evalcomix->environment->moodlename)){
			$moodlename_old = $xml->evalcomix->environment->moodlename;
		}
		include_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
		//include_once($CFG->dirroot . '/backup/lib.php');
		
		//$courseid_new = $this->plan->get_courseid();
		$courseid_new = $this->get_courseid();
		$moodlename_new = MOODLE_NAME;
		$block_evalcomix = $DB->get_record('block_evalcomix', array('courseid' => $courseid_new));
		//$coursecontext = get_context_instance(CONTEXT_COURSE, $courseid_new);
		$coursecontext = context_course::instance($courseid_new);
		
		$tasksid = '';
		if(isset($xml->evalcomix->tasks[0])){
			$assessmentids = array();
			foreach($xml->evalcomix->tasks[0] as $task){
				$task_id_old = (int)$task['id'];
				$task_instanceid_old = (int)$task->instanceid;
				$task_maxgrade_old = (string)$task->maxgrade;
				$task_weighing_old = (string)$task->weighing;
				$cm = $DB->get_record('block_evalcomix', array('courseid' => $courseid_new));
				$cm_mapping = $this->get_mapping('course_module', $task_instanceid_old);
				$newcmid = $cm_mapping->newitemid;
				$visibletask = '1';
				if(isset($task->visible)){
					$visibletask = (string)$task->visible;
				}
				
				if(!$task_fetch = evalcomix_tasks::fetch(array('instanceid' => $newcmid))){
					$task_object = new evalcomix_tasks('', $newcmid, $task_maxgrade_old, $task_weighing_old, '', $visibletask);
					$newtaskid = $task_object->insert();
					$tasksid .= $task_instanceid_old . '-' . $newcmid . ',';
					foreach($task->modes[0] as $mode){
						$mode_id_old = (int)$mode['id'];
						$mode_toolid_old = (string)$mode->toolid;
						$mode_modality_old = (string)$mode->modality;
						$mode_weighing_old = (string)$mode->weighing;
						
						$tool_mapping = $this->get_mapping('evalcomix_tool', $mode_toolid_old);
						if(!empty($tool_mapping) && $newtoolid = $tool_mapping->newitemid){

							if(!$mode_object = evalcomix_modes::fetch(array('taskid' => $newtaskid, 'toolid' => $newtoolid, 'modality' => $mode_modality_old))){
								$mode_object = new evalcomix_modes('', $newtaskid, $newtoolid, $mode_modality_old, $mode_weighing_old);
								$newmodeid = $mode_object->insert();
						
								
								if(isset($mode->mode_time['id'])){
									$mode_time_id_old = (string)$mode->mode_time['id'];
									$mode_time_timeavailable_old = (string)$mode->mode_time->timeavailable;
									$mode_time_timedue_old = (string)$mode->mode_time->timedue;
									if(!evalcomix_modes_time::fetch(array('modeid' => $newmodeid))){
										$mode_time_object = new evalcomix_modes_time('', $newmodeid, $mode_time_timeavailable_old, $mode_time_timedue_old);
										$mode_time_object->insert();
									}
								}
								
								if(isset($mode->mode_extra)){
									$mode_extra_id_old = (string)$mode->mode_extra['id'];
									$mode_extra_timeavailable_old = (string)$mode->mode_extra->anonymous;
									$mode_extra_visible = $mode->mode_extra->visible;
									$mode_extra_whoassesses = $mode->mode_extra->whoassesses;
									if(!$mode_extra_object = evalcomix_modes_extra::fetch(array('modeid' => $newmodeid))){
										$mode_extra_object = new evalcomix_modes_extra('', $newmodeid, $mode_extra_timeavailable_old, $mode_extra_visible, $mode_extra_whoassesses);
										$mode_extra_object->insert();
									}
								}
							}
						}
					}
				}
				
				if($settings['users'] == 1){
					foreach($task->assessments[0] as $assessment){
						$assessment_id_old = (string)$assessment['id'];
						$assessment_assessorid_old = (string)$assessment->assessorid;
						$assessment_studentid_old = (string)$assessment->studentid;
						$assessment_grade_old = (string)$assessment->grade;
						$assessor_user = $this->get_mapping('user', $assessment_assessorid_old);
						$student_user = $this->get_mapping('user', $assessment_studentid_old);
						if(!isset($assessor_user->newitemid) || !isset($student_user->newitemid)){
							continue;
						}
						if(!$assessment_object = evalcomix_assessments::fetch(array('taskid' => $newtaskid, 'assessorid' => $assessor_user->newitemid, 'studentid' => $student_user->newitemid))){
							$assessment_object = new evalcomix_assessments('', $newtaskid, $assessor_user->newitemid, $student_user->newitemid, $assessment_grade_old);
							$assessment_object->insert();
						}
						//$modulename = get_module_type ($courseid_new, $newcmid);
						$modulename = evalcomix_tasks::get_type_task($newcmid);
						$mode = '';
						if($student_user->newitemid == $assessor_user->newitemid){
							$mode = 'self';
						}
						//elseif(has_capability('block/evalcomix:edit',$coursecontext, $assessor_user->newitemid)){
						elseif(has_capability('moodle/grade:viewhidden',$coursecontext, $assessor_user->newitemid)){
							$mode = 'teacher';
						}
						else{
							$mode = 'peer';
						}
						//$courseid_old, $modulename, $task_instanceid_old, $assessment_studentid_old, $assessment_assessorid_old, $mode, $moodlename_old
						$str = $courseid_old . '_' . $modulename . '_' . $task_instanceid_old . '_' . $assessment_studentid_old . '_' . $assessment_assessorid_old . '_' . $mode . '_' . $moodlename_old;
						$assessmentid_old = md5($str);
						
						$str = $courseid_new . '_' . $modulename . '_' . $newcmid . '_' . $student_user->newitemid . '_' . $assessor_user->newitemid . '_' . $mode . '_' . $moodlename_new; 
						//echo $str . ' -- ';
						$assessmentid_new = md5($str);
						$object = new stdClass();
						$object->oldid = $assessmentid_old;
						$object->newid = $assessmentid_new;
						$assessmentids[] = $object;
					}
				}
			}
		}
		
		if($tasksid != ''){
			$tasksid = substr($tasksid, 0, -1);
		}
		
		if($settings['users'] == 1){
			if(isset($xml->evalcomix->grades[0])){
				foreach($xml->evalcomix->grades[0] as $grade){
					$cm_mapping = $this->get_mapping('course_module', $grade->cmid);
					$newcmid = $cm_mapping->newitemid;
					$student = $this->get_mapping('user', (int)$grade->userid);
					if(!isset($student->newitemid)){
						continue;
					}
					$params = array('finalgrade' =>(float)$grade->finalgrade, 'courseid' => $courseid_new, 'cmid' => $newcmid, 'userid' => $student->newitemid);
					
					if(!$grade_object = evalcomix_grades::fetch(array('courseid' => $courseid_new, 'cmid' => $newcmid, 'userid' => $student->newitemid))){
						$grade_object = new evalcomix_grades($params);
						$newgradeid = $grade_object->insert();
					}
				}
			}
			
			if(isset($xml->evalcomix->allowedusers[0])){
				foreach($xml->evalcomix->allowedusers[0] as $users){
					$cm_mapping = $this->get_mapping('course_module', $users->cmid);
					$newcmid = $cm_mapping->newitemid;
					$assessor = $this->get_mapping('user', (int)$users->assessorid);
					$student = $this->get_mapping('user', (int)$users->studentid);
					if(!isset($student->newitemid) || !isset($assessor->newitemid)){
						continue;
					}
					$params = array('assessorid' =>(int)$assessor->newitemid, 'studentid' => $student->newitemid, 'cmid' => $newcmid);
					
					if(!$allowedusers_object = evalcomix_allowedusers::fetch($params)){
						$allowedusers_object = new evalcomix_allowedusers($params);
						$newid = $allowedusers_object->insert();
					}
				}
			}
		}
		
		if(isset($xml->evalcomix->tools[0])){
			$hashtools = array();		
			foreach($xml->evalcomix->tools[0] as $tool){
				if((string)$tool->type == 'tmp'){
					continue;
				}
				$idtoolold = (string)$tool->idtool;
				$tool_mapping = $this->get_mapping('evalcomix_tool', (string)$tool['id']);
				$toolnew = evalcomix_tool::fetch(array('id' => $tool_mapping->newitemid));
				$idtoolnew = $toolnew->idtool;
				$object = new stdClass();
				$object->oldid = $idtoolold;
				$object->newid = $idtoolnew;
				$hashtools[] = $object;
			}
		}
		
		if(isset($hashtools) && isset($assessmentids)){
			$result = webservice_evalcomix_client::duplicate_course($assessmentids, $hashtools);
		}
		//$newtools = webservice_evalcomix_client::get_ws_list_tool($courseid_new, $moodlename_new);
		
	}
}
