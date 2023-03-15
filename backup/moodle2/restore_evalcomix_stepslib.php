<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
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
        $paths[] = new restore_path_element('evalcomix_comptype',
        '/block/evalcomix/competencysection/competencytypes/competencytype');
        $paths[] = new restore_path_element('evalcomix_competency',
        '/block/evalcomix/competencysection/competencies/competency');
        $paths[] = new restore_path_element('evalcomix_subdimension',
        '/block/evalcomix/competencysection/subdimensions/subdimension');

        return $paths;
    }

    public function process_evalcomix($data) {
        global $DB, $CFG;
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix.php');

        $data = (object)$data;
        $oldid = $data->id;

        $data->courseid = $this->get_courseid();
        if (!$DB->get_record('block_evalcomix', array('courseid' => $data->courseid))) {
            $newitemid = $DB->insert_record('block_evalcomix', $data);
            $this->set_mapping('evalcomix', $oldid, $newitemid);
        } else {
            $this->set_mapping('evalcomix', $oldid, $oldid);
        }
    }

    public function process_evalcomix_tool($data) {
        global $DB, $CFG;

        $data = (object)$data;
        $oldid = $data->id;
        if ($data->type != 'tmp') {
            $data->evxid = $this->get_new_parentid('evalcomix');
            $data->timemodified = $this->apply_date_offset($data->timemodified);
            $data->timecreated = $this->apply_date_offset($data->timecreated);

            require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
            $newidtool = false;
            if ($newidtool = block_evalcomix_webservice_client::duplicate_tool($data->idtool)) {
                $data->idtool = (string)$newidtool;
            } else {
                if (isset($data->code)) {
                    $xml = $data->code;
                    if ($xmlobject = simplexml_load_string($xml)) {
                        try {
                            $newidtool = block_evalcomix_webservice_client::post_ws_xml_tools(array('toolxml' => $data->code));
                            $data->idtool = (string)$newidtool;
                        } catch (Exception $e) {
                            echo "EvalCOMIX no configured correctly";
                        }
                    } else {
                        echo "No tool id ". $data->idtool;
                    }
                } else {
                    echo "No tool id ". $data->idtool;
                }
            }
            if ($newidtool) {
                $newitemid = $DB->insert_record('block_evalcomix_tools', $data);
                $this->set_mapping('evalcomix_tool', $oldid, $newitemid);
            }
        }
    }

    public function process_evalcomix_comptype($data) {
        global $DB, $CFG;

        $data = (object)$data;
        $oldid = $data->id;

        $data->courseid = $this->get_courseid();
        if (!$DB->get_record('block_evalcomix_comptype', array('courseid' => $data->courseid, 'shortname' => $data->shortname))) {
            $newitemid = $DB->insert_record('block_evalcomix_comptype', $data);
            $this->set_mapping('evalcomix_comptype', $oldid, $newitemid);
        } else {
            $this->set_mapping('evalcomix_comptype', $oldid, $oldid);
        }
    }

    public function process_evalcomix_competency($data) {
        global $DB, $CFG;

        $data = (object)$data;
        $oldid = $data->id;

        $data->courseid = $this->get_courseid();
        if (!$DB->get_record('block_evalcomix_competencies', array('courseid' => $data->courseid,
                'idnumber' => $data->idnumber, 'outcome' => $data->outcome))) {
            if (isset($data->typeid)) {
                $oldtypeid = $data->typeid;
                $newtypeid = $this->get_mapping('evalcomix_comptype', $oldtypeid);
                $data->typeid = $newtypeid->newitemid;
            }
            $newitemid = $DB->insert_record('block_evalcomix_competencies', $data);
            $this->set_mapping('evalcomix_competency', $oldid, $newitemid);
        } else {
            $this->set_mapping('evalcomix_competency', $oldid, $oldid);
        }
    }

    public function process_evalcomix_subdimension($data) {
        global $DB, $CFG;

        $data = (object)$data;
        $oldid = $data->id;
        $data->courseid = $this->get_courseid();
        if (!empty($data->toolid) && !empty($data->subdimensionid)) {
            $oldtoolid = $data->toolid;
            $evalcomixtool = $this->get_mapping('evalcomix_tool', $oldtoolid);
            $newtoolid = $evalcomixtool->newitemid;
            $data->toolid = $newtoolid;
            $newitemid = 0;

            if ($oldsubdimensions = $this->get_subdimensionids($oldtoolid)) {
                if ($newsubdimensions = $this->get_subdimensionids($newtoolid)) {
                    if (count($oldsubdimensions) == count($newsubdimensions)) {
                        foreach ($oldsubdimensions as $id => $subdimensionid) {
                            if (!$newitemid && (string)$subdimensionid === (string)$data->subdimensionid) {
                                if (!empty($newsubdimensions)) {
                                    $data->subdimensionid = $newsubdimensions[$id];
                                    $competency = $this->get_mapping('evalcomix_competency', $data->competencyid);
                                    $data->competencyid = $competency->newitemid;
                                    $newitemid = $DB->insert_record('block_evalcomix_subdimension', $data);
                                    $this->set_mapping('evalcomix_subdimension', $oldid, $newitemid);
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function get_subdimensionids($toolid) {
        global $CFG, $DB;
        $result = array();
        $tool = $DB->get_record('block_evalcomix_tools', array('id' => $toolid));
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
        if ($xmlstring = block_evalcomix_webservice_client::get_tool($tool->idtool)) {
            $xml = simplexml_load_string($xmlstring);
            $typeevx3 = dom_import_simplexml($xml)->tagName;
            $type = '';
            if ($typeevx3 == 'mt:MixTool' || $typeevx3 == 'MixTool') {
                foreach ($xml as $valor) {
                    $ids = $this->get_subdimensionids_from_xml($valor);
                    $result = array_merge($result, $ids);
                }
            } else {
                $result = $this->get_subdimensionids_from_xml($xml);
            }
        }
        return $result;
    }

    private function get_subdimensionids_from_xml($xml) {
        $result = array();
        $tagname = dom_import_simplexml($xml)->tagName;
        $typetool = '';
        if ($tagname[2] == ':') {
            $typeevx3 = explode(':', $tagname);
            $typetool = $typeevx3[1];
        } else {
            $typetool = $tagname;
        }
        if ($typetool == 'SemanticDifferential') {
            $result[] = (string)$xml['id'];
        } else {
            foreach ($xml->Dimension as $dimen) {
                foreach ($dimen->Subdimension as $subdimen) {
                    $result[] = (string)$subdimen['id'];
                }
            }
        }

        return $result;
    }

    public function after_restore() {
        global $DB, $COURSE, $CFG;
        require_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes_time.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes_extra.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_assessments.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_grades.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_allowedusers.php');

        $settings = $this->task->get_info()->root_settings;

        /* This->oldcontextid; busco en tabla mdl_context->instanceid es el courseid; con el courseid obtengo todos los
           cms antiguos y nuevos y voy copiando uno a uno*/
        $fullpath = $this->task->get_taskbasepath();
        // We MUST have one fullpath here, else, error.
        if (empty($fullpath)) {
            throw new restore_step_exception('restore_structure_step_undefined_fullpath');
        }

        // Append the filename to the fullpath.
        $fullpath = rtrim($fullpath, '/') . '/' . $this->filename;

        // And it MUST exist.
        if (!file_exists($fullpath)) { // Shouldn't happen ever, but...
            throw new restore_step_exception('missing_moodle_backup_xml_file', $fullpath);
        }
        $xml = simplexml_load_file($fullpath);

        $evxidold = (int)$xml->evalcomix['id'];
        $viewmodeold = (string)$xml->evalcomix->viewmode;

        if (isset($xml->evalcomix->environment->courseid)) {
            $courseidold = $xml->evalcomix->environment->courseid;
        }
        if (isset($xml->evalcomix->environment->moodlename)) {
            $moodlenameold = $xml->evalcomix->environment->moodlename;
        }
        require_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');

        $courseidnew = $this->get_courseid();
        $moodlenamenew = BLOCK_EVALCOMIX_MOODLE_NAME;
        $blockevalcomix = $DB->get_record('block_evalcomix', array('courseid' => $courseidnew));
        $coursecontext = context_course::instance($courseidnew);

        $tasksid = '';
        $assessmentids = array();
        if (isset($xml->evalcomix->tasks[0])) {
            foreach ($xml->evalcomix->tasks[0] as $task) {
                $taskidold = (int)$task['id'];
                $taskinstanceidold = (int)$task->instanceid;
                $taskmaxgradeold = (string)$task->maxgrade;
                $taskweighingold = (string)$task->weighing;
                $grademethodold = (int)$task->grademethod;
                $workteamsold = (int)$task->workteams;
                $cm = $DB->get_record('block_evalcomix', array('courseid' => $courseidnew));
                $cmmapping = $this->get_mapping('course_module', $taskinstanceidold);
                $newcmid = $cmmapping->newitemid;
                $visibletask = '1';
                if (isset($task->visible)) {
                    $visibletask = (string)$task->visible;
                }

                if (!$taskfetch = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $newcmid))) {
                    $taskobject = new block_evalcomix_tasks('', $newcmid, $taskmaxgradeold, $taskweighingold, '',
                        $visibletask, $grademethodold, $workteamsold);
                    $newtaskid = $taskobject->insert();
                    $tasksid .= $taskinstanceidold . '-' . $newcmid . ',';
                    foreach ($task->modes[0] as $mode) {
                        $modeidold = (int)$mode['id'];
                        $modetoolidold = (string)$mode->toolid;
                        $modemodalityold = (string)$mode->modality;
                        $modeweighingold = (string)$mode->weighing;

                        $toolmapping = $this->get_mapping('evalcomix_tool', $modetoolidold);
                        if (!empty($toolmapping) && $newtoolid = $toolmapping->newitemid) {
                            if (!$modeobject = $DB->get_record('block_evalcomix_modes', array('taskid' => $newtaskid,
                                'toolid' => $newtoolid, 'modality' => $modemodalityold))) {
                                $modeobject = new block_evalcomix_modes('', $newtaskid, $newtoolid, $modemodalityold,
                                    $modeweighingold);
                                $newmodeid = $modeobject->insert();

                                if (isset($mode->mode_time['id'])) {
                                    $modetimeidold = (string)$mode->mode_time['id'];
                                    $modetimetimeavailableold = (string)$mode->mode_time->timeavailable;
                                    $modetimetimedueold = (string)$mode->mode_time->timedue;
                                    if (!$DB->get_record('block_evalcomix_modes_time', array('modeid' => $newmodeid))) {
                                        $modetimeobject = new block_evalcomix_modes_time('', $newmodeid,
                                            $modetimetimeavailableold, $modetimetimedueold);
                                        $modetimeobject->insert();
                                    }
                                }

                                if (isset($mode->mode_extra)) {
                                    $modeextraidold = (string)$mode->mode_extra['id'];
                                    $modeextratimeavailableold = (string)$mode->mode_extra->anonymous;
                                    $modeextravisible = $mode->mode_extra->visible;
                                    $modeextrawhoassesses = $mode->mode_extra->whoassesses;
                                    if (!$modeextraobject = $DB->get_record('block_evalcomix_modes_extra',
                                            array('modeid' => $newmodeid))) {
                                        $modeextraobject = new block_evalcomix_modes_extra('', $newmodeid,
                                            $modeextratimeavailableold, $modeextravisible, $modeextrawhoassesses);
                                        $modeextraobject->insert();
                                    }
                                }
                            }
                        }
                    }
                }

                if ($settings['users'] == 1) {
                    foreach ($task->assessments[0] as $assessment) {
                        $assessmentidold = (string)$assessment['id'];
                        $assessmentassessoridold = (int)$assessment->assessorid;
                        $assessmentstudentidold = (int)$assessment->studentid;
                        $assessmentgradeold = (string)$assessment->grade;
                        $assessoruser = $this->get_mapping('user', $assessmentassessoridold);
                        $studentuser = $this->get_mapping('user', $assessmentstudentidold);

                        if (!isset($assessoruser->newitemid) || !isset($studentuser->newitemid)) {
                            continue;
                        }

                        $modulename = block_evalcomix_tasks::get_type_task($newcmid);
                        $mode = '';
                        if ($studentuser->newitemid == $assessoruser->newitemid) {
                            $mode = 'self';
                        } else if (has_capability('moodle/grade:viewhidden', $coursecontext, $assessoruser->newitemid)) {
                            $mode = 'teacher';
                        } else {
                            $mode = 'peer';
                        }

                        $assessmentidold = block_evalcomix_get_assessmentid(array('courseid' => $courseidold,
                            'module' => $modulename,
                            'cmid' => $taskinstanceidold, 'studentid' => $assessmentstudentidold,
                            'assessorid' => $assessmentassessoridold, 'mode' => $mode, 'lms' => $moodlenameold));

                        $assessmentidnew = block_evalcomix_get_assessmentid(array('courseid' => $courseidnew,
                            'module' => $modulename,
                            'cmid' => $newcmid, 'studentid' => $studentuser->newitemid,
                            'assessorid' => $assessoruser->newitemid, 'mode' => $mode, 'lms' => $moodlenamenew));
                        $object = new stdClass();
                        $object->oldid = $assessmentidold;
                        $object->newid = $assessmentidnew;
                        $assessmentids[] = $object;
                        if (!$assessmentobject = $DB->get_record('block_evalcomix_assessments', array('taskid' => $newtaskid,
                            'assessorid' => $assessoruser->newitemid, 'studentid' => $studentuser->newitemid))) {
                            $DB->insert_record('block_evalcomix_assessments', array('taskid' => $newtaskid,
                                'assessorid' => $assessoruser->newitemid, 'studentid' => $studentuser->newitemid,
                                'grade' => $assessmentgradeold, 'timemodified' => time(), 'idassessment' => $assessmentidnew));
                        }
                    }
                    if (isset($task->coordinators[0])) {
                        foreach ($task->coordinators[0] as $coordinator) {
                            if (!empty($coordinator->groupid)) {
                                $groupid = $this->get_mappingid('group', (int)$coordinator->groupid);
                                if ($groupid) {
                                    if ($coordinator->userid > 0) {
                                        $coordinatorid = $this->get_mappingid('user', (int)$coordinator->userid);
                                        $DB->insert_record('block_evalcomix_coordinators', array('taskid' => $newtaskid,
                                        'groupid' => $groupid, 'userid' => $coordinatorid));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($tasksid != '') {
            $tasksid = substr($tasksid, 0, -1);
        }

        if ($settings['users'] == 1) {
            if (isset($xml->evalcomix->grades[0])) {
                foreach ($xml->evalcomix->grades[0] as $grade) {
                    $cmmapping = $this->get_mapping('course_module', $grade->cmid);
                    $newcmid = $cmmapping->newitemid;
                    $student = $this->get_mapping('user', (int)$grade->userid);
                    if (!isset($student->newitemid)) {
                        continue;
                    }
                    $params = array('finalgrade' => (float)$grade->finalgrade, 'courseid' => $courseidnew,
                        'cmid' => $newcmid, 'userid' => $student->newitemid);

                    if (!$gradeobject = $DB->get_record('block_evalcomix_grades',
                        array('courseid' => $courseidnew, 'cmid' => $newcmid, 'userid' => $student->newitemid))) {
                        $gradeobject = new block_evalcomix_grades($params);
                        $newgradeid = $gradeobject->insert();
                    }
                }
            }

            if (isset($xml->evalcomix->allowedusers[0])) {
                foreach ($xml->evalcomix->allowedusers[0] as $users) {
                    $cmmapping = $this->get_mapping('course_module', $users->cmid);
                    $newcmid = $cmmapping->newitemid;
                    $assessor = $this->get_mapping('user', (int)$users->assessorid);
                    $student = $this->get_mapping('user', (int)$users->studentid);
                    if (!isset($student->newitemid) || !isset($assessor->newitemid)) {
                        continue;
                    }
                    $params = array('assessorid' => (int)$assessor->newitemid, 'studentid' => $student->newitemid,
                        'cmid' => $newcmid);

                    if (!$allowedusersobject = $DB->get_record('block_evalcomix_allowedusers', $params)) {
                        $allowedusersobject = new block_evalcomix_allowedusers($params);
                        $newid = $allowedusersobject->insert();
                    }
                }
            }
        }

        if (isset($xml->evalcomix->tools[0])) {
            $hashtools = array();
            foreach ($xml->evalcomix->tools[0] as $tool) {
                if ((string)$tool->type == 'tmp') {
                    continue;
                }
                $idtoolold = (string)$tool->idtool;
                $toolmapping = $this->get_mapping('evalcomix_tool', (string)$tool['id']);
                $toolnew = block_evalcomix_tool::fetch(array('id' => $toolmapping->newitemid));
                $idtoolnew = $toolnew->idtool;
                $object = new stdClass();
                $object->oldid = $idtoolold;
                $object->newid = $idtoolnew;
                $hashtools[] = $object;
            }
        }

        if (isset($hashtools) && isset($assessmentids)) {
            $result = block_evalcomix_webservice_client::duplicate_course($assessmentids, $hashtools);
        }
    }
}
