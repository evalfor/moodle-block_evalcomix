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
class block_evalcomix_modes extends block_evalcomix_object{
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
                print_error("weighing wrong");
            }
            $this->weighing = $weighing;
            $taskobject = $DB->get_record('block_evalcomix_tasks', array('id' => $taskid), '*', MUST_EXIST);
            $this->taskid = $taskobject->id;
            if (is_numeric($toolid) && !is_float($toolid) && (int)$toolid > 0) {

                $toolobject = $DB->get_record('block_evalcomix_tools', array('id' => $toolid), '*', MUST_EXIST);
                $this->toolid = $toolobject->id;
            }
            if ($this->modality != 'teacher' && $this->modality != 'peer' && $this->modality != 'self') {
                print_error('The assessment modality is wrong');
            }
        }
    }

    /**
     * @return bool|int if exist return ID else return 0
     */
    public function exist() {
        global $DB;
        if (!$data = $DB->get_record($this->table, array('taskid' => $this->taskid, 'modality' => $this->modality))) {
            return 0;
        }
        $this->id = $data->id;
        return $data->id;
    }

    /**
     * Finds and returns all evalcomix_tool instances.
     * @static abstract
     *
     * @return array array of evalcomix_tool instances or false if none found.
     */
    public static function fetch_all($params) {
        return block_evalcomix_object::fetch_all_helper('block_evalcomix_modes', 'block_evalcomix_modes', $params);
    }

    /**
     * Finds and returns a evalcomix_modes instance based on params.
     * @static
     *
     * @param array $params associative arrays varname=>value
     * @return object grade_item instance or false if none found.
     */
    public static function fetch($params) {
        return block_evalcomix_object::fetch_helper('block_evalcomix_modes', 'block_evalcomix_modes', $params);
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
}
