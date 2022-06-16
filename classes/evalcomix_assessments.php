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
     * Updates this object in the Database, based on its object variables. ID must be set.
     * @param string $source from where was the object updated (mod/forum, manual, etc.)
     * @return boolean success
     */
    public function update() {
        global $DB;

        if (empty($this->id)) {
            debugging('Can not update assessment object, no id!');
            return false;
        }
        $this->timemodified = time();

        $data = $this->get_record_data();

        $DB->update_record($this->table, $data);

        $this->notify_changed(false);
        return true;
    }

    /**
     * Calculate evalcomix final grade.
     * @static abstract
     * @param array $users
     * @param int $courseid
     * @return array $finalgrades with two dimensions [$taskinstance][$userid] that contains the finalgrades.
     */
    public static function get_final_grade($courseid, $users) {
        global $CFG, $COURSE, $DB;
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix.php');

        $coursecontext = context_course::instance($courseid);

        $finalgrades = array();

        $tasks = block_evalcomix_tasks::get_tasks_by_courseid($courseid);
        $now = time();

        foreach ($tasks as $task) {
            $teacherweight = 0;
            $selfweight = 0;
            $peerweight = 0;

            $params = array('taskid' => $task->id);
            $modes = $DB->get_records('block_evalcomix_modes', $params);
            if ($modes) {
                $inperiod = false;
                // Obtains activity´s weights.
                foreach ($modes as $mode) {
                    switch ($mode->modality) {
                        case 'teacher': $teacherweight = $mode->weighing;
                        break;
                        case 'self': $selfweight = $mode->weighing;
                        break;
                        case 'peer':{
                            $peerweight = $mode->weighing;
                            $modeeitime = $DB->get_record('block_evalcomix_modes_time', array('modeid' => $mode->id));
                            if ($modeeitime && $now >= $modeeitime->timeavailable && $now <= $modeeitime->timedue) {
                                $inperiod = true;
                            }
                        }
                        break;
                        default:
                    }
                }

                foreach ($users as $user) {
                    // It obtains assignments for each task and user.
                    $params2 = array('taskid' => $task->id, 'studentid' => $user->id);
                    $assessments = $DB->get_records('block_evalcomix_assessments', $params2);
                    if ($assessments) {
                        $selfgrade = -1;
                        $teachergrade = 0;
                        $numteachers = 0;
                        $peergrade = 0;
                        $numpeers = 0;
                        $grade = 0;
                        foreach ($assessments as $assessment) {
                            // If it is a self assessment.
                            if ($assessment->studentid == $assessment->assessorid && $selfweight) {
                                $selfgrade = $assessment->grade;
                            } else if (has_capability('moodle/grade:viewhidden', $coursecontext,
                                $assessment->assessorid) && $teacherweight) {
                                // If it is a teacher assessment.
                                $teachergrade += $assessment->grade;
                                $numteachers++;
                            } else { // If it is a peer assessment.
                                // Only gets grades when the assessment period in the task is finished.
                                if (!$inperiod) {
                                    $peergrade += $assessment->grade;
                                    $numpeers++;
                                }
                            }
                        }

                        // Calculates peergrade.
                        if ($numpeers > 0) {
                            $peergrade = round($peergrade / $numpeers, 2);
                        }
                        // Calculates teachergrade.
                        if ($numteachers > 0) {
                            $teachergrade = round($teachergrade / $numteachers, 2);
                        }
                        // Calcultes the total grade.
                        if ($numteachers > 0 || $numpeers > 0 || $selfgrade != -1) {
                            if ($selfgrade == -1) {
                                $selfgrade = 0;
                            }
                            $totalgrade = $selfgrade * ($selfweight / 100) + $teachergrade * ($teacherweight / 100)
                                + $peergrade * ($peerweight / 100);
                            // Add grade to array final grades.
                            $finalgrades[$task->instanceid][$user->id] = $totalgrade;
                        } else {
                            // There is peer assessments but assessment period hasn't finished.
                            $finalgrades[$task->instanceid][$user->id] = -1;
                        }
                    }
                }
            }
        }

        return $finalgrades;
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
     * Finds and returns one evalcomix_tool instances.
     * @static abstract
     * @param array $params
     * @return an object instance or false if not found
     */
    public static function fetch($params) {
        return block_evalcomix_object::fetch_helper('block_evalcomix_assessments', 'block_evalcomix_assessments', $params);
    }

    /**
     * Called immediately after the object data has been inserted, updated, or
     * deleted in the database. Default does nothing, can be overridden to
     * hook in special behaviour.
     *
     * @param bool $deleted
     */
    public function notify_changed($deleted) {
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

    public static function get_assessments_by_modality_helper() {

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

    /**
     * @param int $taskid
     * @param int $userid
     * @return object with assessment of student $userid in $taskid
     */
    public static function get_grade_by_user_task($taskid, $userid) {
        $assessments = self::fetch(array('studentid' => $userid, 'taskid' => $taskid));
        return $assessments;
    }
}
