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

class block_evalcomix_grades extends block_evalcomix_object{
    public $table = 'block_evalcomix_grades';

    /**
     * Array of required table fields, must start with 'id'.
     * @var array $requiredfields
     */
    public $requiredfields = array('id', 'userid', 'cmid', 'finalgrade', 'courseid');

    /**
     * Array of optional table fields.
     * @var array $requiredfields
     */
    public $optionalfields = array();

    /**
     * course_module ID
     * @var int $cmid
     */
    public $cmid;

    /**
     * student ID associated
     * @var int $userid
     */
    public $userid;

    /**
     * Finalgrade
     * @var float $finalgrade
     */
    public $finalgrade;

    /**
     * @var int $courseid
     */
    public $courseid;

    /**
     * Constructor
     *
     * @param int $params[id] ID
     * @param int $params[cmid] //foreign key of table 'course_modules'
     * @param int $params[userid] //foreign key of table 'user'
     * @param float $params[finalgrade] Grade
     */
    public function __construct($params = array()) {
        if (isset($params['id'])) {
            $this->id = intval($params['id']);
        }
        if (isset($params['finalgrade'])) {
            $this->finalgrade = floatval($params['finalgrade']);
        }
        if (isset($params['courseid'])) {
            $this->courseid = floatval($params['courseid']);
        }

        // Por si queremos crear una instancia vacía (para usar evalcomix_object::fetch_all_helper es necesario).
        if (isset($params['cmid']) && is_numeric($params['cmid']) && !is_float($params['cmid']) && (int)$params['cmid'] > 0) {
            $this->cmid = $params['cmid'];
        } else {
            $this->cmid = 0;
        }

        // Por si queremos crear una instancia vacía (para usar evalcomix_object::fetch_all_helper es necesario).
        if (isset($params['userid']) && is_numeric($params['userid']) && !is_float($params['userid']) && $params['userid'] > '0') {
            $this->userid = $params['userid'];
        } else {
            $this->userid = 0;
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
     * Finds and returns all block_evalcomix_grades instances.
     * @static abstract
     * @param array $params
     * @return array array of evalcomix_assessments instances or false if none found.
     */
    public static function fetch_all($params) {
        return evalcomix_object::fetch_all_helper('block_evalcomix_grades', 'block_evalcomix_grades', $params);
    }

    /**
     * Finds and returns one evalcomix_grades instances.
     * @static abstract
     * @param array $params
     * @return an object instance or false if not found
     */
    public static function fetch($params) {
        return evalcomix_object::fetch_helper('block_evalcomix_grades', 'block_evalcomix_grades', $params);
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
     * Calculates finalgrade for a student in a activity
     * @param int $params[cmid] course module id
     * @param int $params[userid] student id
     * @return the finalgrade or -1 if there is only peer-assessment but assessment period hasn't finished
     * or -2 if there is not any assessment
     */
    public static function get_finalgrade_user_task($params) {
        if (!isset($params['cmid']) || !isset($params['userid']) || !isset($params['courseid'])) {
            return null;
        }
        $cmid = $params['cmid'];
        $userid = $params['userid'];
        $courseid = $params['courseid'];

        $coursecontext = context_course::instance($courseid);

        $now = time();

        global $CFG, $DB;
        require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
        if (!$task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cmid))) {
            return null;
        }

        $result = null;

        $teacherweight = -1;
        $selfweight = -1;
        $peerweight = -1;

        $paramsmodes = array('taskid' => $task->id);
        $modes = $DB->get_records('block_evalcomix_modes', $paramsmodes);
        if ($modes) {
            // Obtains activity´s weights.
            foreach ($modes as $mode) {
                switch($mode->modality) {
                    case 'teacher': $teacherweight = $mode->weighing;
                    break;
                    case 'self': $selfweight = $mode->weighing;
                    break;
                    case 'peer': $peerweight = $mode->weighing;
                    break;
                    default:
                }
            }

            $params2 = array('taskid' => $task->id, 'studentid' => $userid);
            $assessments = $DB->get_records('block_evalcomix_assessments', $params2);
            $inperiod = false;
            if ($assessments) {
                $selfgrade = -1;
                $teachergrade = 0;
                $numteachers = 0;
                $peergrade = 0;
                $numpeers = 0;
                $grade = 0;
                foreach ($assessments as $assessment) {
                    // If it is a self assessment.
                    if ($assessment->studentid == $assessment->assessorid && $selfweight != -1) {
                        $selfgrade = $assessment->grade;
                    } else if (has_capability('moodle/grade:viewhidden', $coursecontext, $assessment->assessorid)) {
                        // If it is a teacher assessment.

                        if ($teacherweight != -1) {
                            $teachergrade += $assessment->grade;
                            $numteachers++;
                        }
                    } else if ($assessment->studentid != $assessment->assessorid) { // If it is a peer assessment.
                        // Only gets grades when the assessment period in the task is finished.

                        if ($modeei = $DB->get_record('block_evalcomix_modes', array('taskid' => $assessment->taskid,
                            'modality' => 'peer'))) {
                            $modeeitime = $DB->get_record('block_evalcomix_modes_time', array('modeid' => $modeei->id));
                            if ($modeeitime && $now > $modeeitime->timedue) {
                                $peergrade += $assessment->grade;
                                $numpeers++;
                            } else if ($now >= $modeeitime->timeavailable && $now <= $modeeitime->timedue) {
                                $inperiod = true;
                            }
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
                    $result = $selfgrade * ($selfweight / 100) + $teachergrade * ($teacherweight / 100) + $peergrade *
                    ($peerweight / 100);
                } else if ($inperiod == true) {
                    // There is peer assessments but assessment period hasn't finished.
                    $result = -1;
                } else {
                    $result = -2;
                }
                return $result;
            } else {
                return null;
            }
        } else {
            return -3;
        }
    }

    /**
     * Get EvalCOMIX finalgrades for the assessment table
     * @var int courseid
     * @return array with finalgrades by cmid and userid
     */
    public static function get_grades($courseid) {
        global $DB;
        $result = array();
        if ($finalgrades = $DB->get_records('block_evalcomix_grades', array('courseid' => $courseid))) {
            foreach ($finalgrades as $finalgrade) {
                $userid = $finalgrade->userid;
                $cmid = $finalgrade->cmid;
                $result[$cmid][$userid] = $finalgrade->finalgrade;
            }
        }
        return $result;
    }
}
