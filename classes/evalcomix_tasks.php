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
 * @author     Daniel Cabeza S�nchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hern�ndez <juanantonio.caballero@uca.es>
 */

defined('MOODLE_INTERNAL') || die();
require_once('evalcomix_object.php');

/**
 * Definitions of EvalCOMIX tool object class
 *
 * @package    block-evalcomix
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 */
class block_evalcomix_tasks extends block_evalcomix_object {
    public $table = 'block_evalcomix_tasks';

    /**
     * Array of required table fields, must start with 'id'.
     * @var array $requiredfields
     */
    public $requiredfields = array('id', 'instanceid', 'maxgrade', 'weighing', 'timemodified', 'visible', 'grademethod',
                            'workteams');

    /**
     * Array of optional table fields, must start with 'id'.
     * @var array $requiredfields
     */
    public $optionalfields = array();

    /**
     * course_module ID associated
     * @var int $instanceid
     */
    public $instanceid;

    /**
     * Maximum grade for this task
     * @var int $maxgrade
     */
    public $maxgrade;

    /**
     * Weighing for this task
     * @var int $weighing
     */
    public $weighing;

    /**
     * The last time this evalcomix_task was modified.
     * @var int $timemodified
     */
    public $timemodified;

    /**
     * It indicates the visibility into assessment table
     * @var int $visible
     */
    public $visible;

    public $grademethod;

    public $workteams;

    /**
     * Constructor
     *
     * @param int $id ID
     * @param int $instanceid foreign key of table 'course_modules'
     * @param float $maxgrade
     * @param int $weighing of EvalCOMIX grade respect Moodle grade
     * @param int $timemodified
     */
    public function __construct($id = '', $instanceid = '0', $maxgrade = '100', $weighing = '50',
        $timemodified = '0', $visible = '1', $grademethod = '1', $workteams = '0') {
        if ($instanceid != '0') {
            global $DB;
            $this->id = intval($id);
            $cm = $DB->get_record('course_modules', array('id' => $instanceid), '*', MUST_EXIST);
            $this->instanceid = $cm->id;
            $this->maxgrade = intval($maxgrade);
            $this->weighing = intval($weighing);
            $this->timemodified = $timemodified;
            $this->visible = intval($visible);
            $this->grademethod = intval($grademethod);
            $this->workteams = intval($workteams);
        }
    }

    /**
     * Obtains every id tasks for each course and returns them
     * @param int $courseid
     * @return array $ids with id tasks
     */
    public static function get_tasks_by_courseid($courseid) {
        global $DB;
        $tasks = array();
        $task = null;
        $i = 0;

        $cm = $DB->get_records('course_modules', array('course' => $courseid));
        foreach ($cm as $value) {
            $params = array('instanceid' => $value->id);
            $task = $DB->get_record('block_evalcomix_tasks', $params);
            if ($task) {
                $cmid = $value->id;
                $tasks[$cmid] = $task;
            }
        }
        return $tasks;
    }

    /**
     * @param int $courseid
     * @return mixed array of moodle activities configurated by evalcomix.
     * Array key is evalcomix_tasks ID and Array value is activity name
     */
    public static function get_moodle_course_tasks($courseid) {
        global $DB;
        $evalcomixtasks = self::get_tasks_by_courseid($courseid);
        $result = array();
        foreach ($evalcomixtasks as $task) {
            $cm = $DB->get_record('course_modules', array('id' => $task->instanceid));
            if ($cm) {
                $module = self::get_type_task($cm->id);
                $taskmoodle = $DB->get_record($module, array('id' => $cm->instance));
                $cmid = $cm->id;
                $result[$cmid] = array('id' => $task->id, 'nombre' => $taskmoodle->name);
            }
        }
        return $result;
    }

    /**
     * @param int $instanceid
     * @return string with the task's type.
     */
    public static function get_type_task($instanceid) {
        global $DB;
        $cm = $DB->get_record('course_modules', array('id' => $instanceid));
        $module = $DB->get_record('modules', array('id' => $cm->module));
        return $module->name;
    }
}
