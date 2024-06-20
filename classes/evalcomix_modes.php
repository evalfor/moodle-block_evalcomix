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
require_once('evalcomix_tool.php');

/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
class block_evalcomix_modes extends block_evalcomix_object {
    public $table = 'block_evalcomix_modes';

    /**
     * Array of required table fields, must start with 'id'.
     * @var array $requiredfields
     */
    public $requiredfields = array('id', 'taskid', 'toolid', 'modality', 'weighing');

    /**
     * Array of optional table fields, must start with 'id'.
     * @var array $requiredfields
     */
    public $optionalfields = array();

    /**
     * Task ID associated
     * @var int $taskid
     */
    public $taskid;

    /**
     * Tool ID associated
     * @var int $toolid
     */
    public $toolid;

    /**
     * Evaluation modality
     * @var string $modality
     */
    public $modality;

    /**
     * Weighing into final grade
     * @var int $weighing
     */
    public $weighing;

    /**
     * Constructor
     *
     * @param int $id ID
     * @param int $taskid foreign key of table 'block_evalcomix_tasks'
     * @param int $toolid foreign key of table 'block_evalcomix_tools'
     * @param string $modality Evaluation modality. Can be: "teacher" | "peer" | "self"
     * @param int $weighing Task weighing. Should be <= 100
     */
    // This function must be improved in the future.
    public function __construct($id = '', $taskid = '0', $toolid = '0', $modality = 'teacher', $weighing = '0') {
        if (is_numeric($taskid) && !is_float($taskid) && (int)$taskid > 0) {

            global $DB;
            $this->id = intval($id);
            $this->modality = addslashes($modality);
            if ($weighing < 0 || $weighing > 100) {
                throw new \moodle_exception("weighing wrong");
            }
            $this->weighing = $weighing;
            $taskobject = $DB->get_record('block_evalcomix_tasks', array('id' => $taskid), '*', MUST_EXIST);
            $this->taskid = $taskobject->id;
            if (is_numeric($toolid) && !is_float($toolid) && (int)$toolid > 0) {

                $toolobject = $DB->get_record('block_evalcomix_tools', array('id' => $toolid), '*', MUST_EXIST);
                $this->toolid = $toolobject->id;
            }
            if ($this->modality != 'teacher' && $this->modality != 'peer' && $this->modality != 'self') {
                throw new \moodle_exception('The assessment modality is wrong');
            }
        }
    }

    public static function delete_mode($id) {
        global $CFG, $DB;
        $result = false;
        if ($mode = $DB->get_record('block_evalcomix_modes', array('id' => $id))) {
            $taskid = $mode->taskid;
            $weighing = $mode->weighing;
            require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_assessments.php');
            if ($DB->get_records('block_evalcomix_modes_extra', array('modeid' => $id))) {
                $DB->delete_records('block_evalcomix_modes_extra', array('modeid' => $id));
            }
            if ($DB->get_record('block_evalcomix_modes_time', array('modeid' => $id))) {
                $DB->delete_records('block_evalcomix_modes_time', array('modeid' => $id));
            }
            block_evalcomix_assessments::delete_assessment_by_modeid($id);

            $result = $DB->delete_records('block_evalcomix_modes', array('id' => $id));

            // Delete allowedusers.
            if ($mode->modality == 'peer' && $task = $DB->get_record('block_evalcomix_tasks', array('id' => $taskid))) {
                $DB->delete_records('block_evalcomix_allowedusers', array('cmid' => $task->instanceid));
            }

            if ($restofmodes = $DB->get_records('block_evalcomix_modes', array('taskid' => $taskid))) {
                // Recalculate the weight of the rest of modalities.
                if ($weighing > 0) {
                    $count = count($restofmodes);
                    $newweighing = (int)(100 / $count);
                    foreach ($restofmodes as $mode) {
                        $mode->weighing = $newweighing;
                        $DB->update_record('block_evalcomix_modes', $mode);
                    }
                }
            } else {
                $DB->delete_records('block_evalcomix_coordinators', array('taskid' => $taskid));
                $DB->delete_records('block_evalcomix_tasks', array('id' => $taskid));
            }
        }
        return $result;
    }

    public static function get_mode($assessment) {
        global $CFG, $DB;
        $result = 0;
        if ($task = $DB->get_record('block_evalcomix_tasks', array('id' => $assessment->taskid))) {
            if ($cm = $DB->get_record('course_modules', array('id' => $task->instanceid))) {
                require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');
                $modestring = block_evalcomix_grade_report::get_type_evaluation($assessment->studentid, $cm->course,
                    $assessment->assessorid);
                if ($mode = $DB->get_record('block_evalcomix_modes', array('taskid' => $assessment->taskid,
                        'modality' => $modestring))) {
                    $result = $mode->id;
                }
            }
        }
        return $result;
    }
}
