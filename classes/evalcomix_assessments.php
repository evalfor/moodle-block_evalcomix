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

defined('MOODLE_INTERNAL') || die();
require_once('evalcomix_object.php');
require_once('evalcomix_modes.php');

/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

class block_evalcomix_assessments extends block_evalcomix_object {
    public $table = 'block_evalcomix_assessments';

    /**
     * Array of required table fields, must start with 'id'.
     * @var array $requiredfields
     */
    public $requiredfields = array('id', 'taskid', 'assessorid', 'studentid', 'grade', 'timemodified');

    /**
     * Array of optional table fields.
     * @var array $requiredfields
     */
    public $optionalfields = array();

    /**
     * course_module ID associated
     * @var int $instanceid
     */
    public $taskid;

    /**
     * Assessor ID associated
     * @var int $assessorid
     */
    public $assessorid;

    /**
     * Student ID associated
     * @var int $studentid
     */
    public $studentid;

    /**
     * Grade
     * @var int $grade
     */
    public $grade;

    /**
     * The last time this evalcomix_assessment was modified.
     * @var int $timemodified
     */
    public $timemodified;

    /**
     * Constructor
     *
     * @param int $id ID
     * @param int $instanceid //foreign key of table 'block_evalcomix_tasks'
     * @param int $assessorid //foreign key of table 'user'
     * @param int $studentid //foreign key of table 'user'
     * @param float $grade Grade
     * @param int $timemodified
     */
    public function __construct($id = '', $taskid = '0', $assessorid = '0', $studentid = '0', $grade = '0', $timemodified = '0') {
        global $DB;
        $this->id = intval($id);
        $this->grade = $grade;
        $this->timemodified = 0;
        // Por si queremos crear una instancia vacía (para usar evalcomix_object::fetch_all_helper es necesario).
        if (is_numeric($taskid) && !is_float($taskid) && (int)$taskid > 0) {
            $task = $DB->get_record('block_evalcomix_tasks', array('id' => $taskid), '*', MUST_EXIST);
            $this->taskid = $task->id;
        } else {
            $this->taskid = 0;
        }

        // Por si queremos crear una instancia vacía (para usar evalcomix_object::fetch_all_helper es necesario).
        if (is_numeric($assessorid) && !is_float($assessorid) && $assessorid > '0') {
            $assessor = $DB->get_record('user', array('id' => $assessorid), '*', MUST_EXIST);
            $this->assessorid = $assessor->id;
        } else {
            $this->assessorid = 0;
        }

        // Por si queremos crear una instancia vacía (para usar evalcomix_object::fetch_all_helper es necesario).
        if (is_numeric($studentid) && !is_float($studentid) && $studentid > '0') {
            $student = $DB->get_record('user', array('id' => $studentid), '*', MUST_EXIST);
            $this->studentid = $student->id;
        } else {
            $this->studentid = 0;
        }
    }

    /**
     * Finds and returns all evalcomix_assessments instances.
     * @static abstract
     * @param array $params
     * @return array array of evalcomix_assessments instances or false if none found.
     */
    public static function fetch_all($params) {
        return block_evalcomix_object::fetch_all_helper('block_evalcomix_assessments', 'block_evalcomix_assessments', $params);
    }

    /**
     * @param int $taskid
     * @param int $userid
     * @return object with evalcomix_assessment objects by modality and their weighings
     */
    public static function get_assessments_by_modality($taskid, $userid) {
        global $CFG, $DB;
        require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');
        require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
        $assessments = $DB->get_records('block_evalcomix_assessments', array('studentid' => $userid, 'taskid' => $taskid));
        if ($assessments) {
            if (!$task = $DB->get_record('block_evalcomix_tasks', array('id' => $taskid))) {
                return false;
            }
            $cm = $DB->get_record('course_modules', array('id' => $task->instanceid));

            $context = context_course::instance($cm->course);
            $selfassessment = null;
            $teacherassessment = array();
            $peerassessments = array();
            $weighingteacher = null;
            $weighingself = null;
            $weighingpeer = null;

            foreach ($assessments as $assessment) {
                if (has_capability('moodle/grade:viewhidden', $context, $assessment->assessorid)) {
                    if ($modality = $DB->get_record('block_evalcomix_modes', array('taskid' => $taskid, 'modality' => 'teacher'))) {
                        $weighingteacher = $modality->weighing;
                        array_push($teacherassessment, $assessment);
                    }
                } else if ($assessment->assessorid == $userid) {
                    if ($modality = $DB->get_record('block_evalcomix_modes', array('taskid' => $taskid, 'modality' => 'self'))) {
                        $weighingself = $modality->weighing;
                        $selfassessment = $assessment;
                    }
                } else {
                    if ($modality = $DB->get_record('block_evalcomix_modes', array('taskid' => $taskid, 'modality' => 'peer'))) {
                        $weighingpeer = $modality->weighing;
                        array_push($peerassessments, $assessment);
                    }
                }
            }

            $result = new stdClass();
            $result->teacherassessments = $teacherassessment;
            $result->weighingteacher = $weighingteacher;
            $result->selfassessment = $selfassessment;
            $result->weighingself = $weighingself;
            $result->peerassessments = $peerassessments;
            $result->weighingpeer = $weighingpeer;

            return $result;
        }
        return false;
    }

    /**
     * @param array $assessments array of evalcomix_assessments objects
     * @return double|false result of current implementation of icalculator interface of false if $assessment is empty
     */
    public static function calculate_gradearray($assessments) {
        if (!empty($assessments)) {
            $grades = array();
            foreach ($assessments as $assessment) {
                array_push($grades, $assessment->grade);
            }
            $result = block_evalcomix_calculator_average::calculate_one_array($grades);
            return $result;
        }
        return false;
    }

    /**
     * @param array $assessments array of evalcomix_assessments objects
     * @return array|false result of current implementation of icalculator interface of false if $assessment is empty
     */
    public static function calculate_grades($assessments) {
        if (!empty($assessments)) {
            $grades = array();
            foreach ($assessments as $assessment) {
                array_push($grades, $assessment->grade);
            }
            return $grades;
        }
        return false;
    }

    /**
     * @param int $taskid
     * @return array of IDs of student assessed in $taskid
     */
    public static function get_students_assessed($taskid) {
        $assessments = self::fetch_all(array('taskid' => $taskid));
        $students = array();
        if ($assessments) {
            foreach ($assessments as $assessment) {
                array_push($students, $assessment->studentid);
            }
            $result = array_unique($students);
            return $result;
        }
        return false;
    }

    public static function delete_assessment_by_modeid($modeid) {
        global $CFG, $DB;
        $result = true;
        if ($DB->get_records('block_evalcomix_modes', array('id' => $modeid))) {
            if ($assessments = $DB->get_records('block_evalcomix_assessments', array('modeid' => $modeid))) {
                $cms = array();
                foreach ($assessments as $key => $assessment) {
                    $taskid = $assessment->taskid;
                    if (empty($cms[$taskid])) {
                        if ($task = $DB->get_record('block_evalcomix_tasks', array('id' => $taskid))) {
                            if ($cm = $DB->get_record('course_modules', array('id' => $task->instanceid))) {
                                $cms[$taskid] = $cm;
                            }
                        }
                    }
                }
                $result = self::delete_assessment(array('where' => array('modeid' => $modeid), 'cmid' => $cms[$taskid]->id,
                    'courseid' => $cms[$taskid]->course));
            }
        }
        return $result;
    }

    public static function delete_assessment($params) {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_grades.php');
        $result = false;
        $where = (isset($params['where'])) ? $params['where'] : null;
        if (!empty($where) && is_array($where) && $assessments = $DB->get_records('block_evalcomix_assessments', $where)) {
            if ($result = $DB->delete_records('block_evalcomix_assessments', $where)) {
                $courseid = (isset($params['courseid'])) ? $params['courseid'] : 0;
                $cmid = (isset($params['cmid'])) ? $params['cmid'] : 0;
                foreach ($assessments as $assessment) {
                    $DB->delete_records('block_evalcomix_dr_pending', array('idassessment' => $assessment->idassessment));
                    $DB->delete_records('block_evalcomix_dr_grade', array('idassessment' => $assessment->idassessment));
                    block_evalcomix_webservice_client::delete_ws_assessment($assessment);

                    if (!empty($courseid) && !empty($cmid)) {
                        if ($grade = $DB->get_record('block_evalcomix_grades', array('courseid' => $courseid,
                            'cmid' => $cmid, 'userid' => $assessment->studentid))) {
                            $finalgrade = block_evalcomix_grades::get_finalgrade_user_task(array('userid' => $assessment->studentid,
                                'cmid' => $cmid, 'courseid' => $courseid));
                            if ($finalgrade !== null) {
                                $DB->update_record('block_evalcomix_grades', array('id' => $grade->id, 'userid' => $grade->userid,
                                    'cmid' => $grade->cmid, 'finalgrade' => $finalgrade, 'courseid' => $grade->courseid));
                            } else {
                                $DB->delete_records('block_evalcomix_grades', array('id' => $grade->id));
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }
}
