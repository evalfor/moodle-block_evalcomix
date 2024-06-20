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

defined('MOODLE_INTERNAL') || die();

require_once('evalcomix_object.php');
require_once('webservice_evalcomix_client.php');

/**
 * Definitions of EvalCOMIX tool object class
 *
 * @package    block-evalcomix
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 */
class block_evalcomix_tool extends block_evalcomix_object {
    public $table = 'block_evalcomix_tools';

    /**
     * Array of required table fields, must start with 'id'.
     * @var array $requiredfields
     */
    public $requiredfields = array('id', 'evxid', 'title', 'type', 'idtool', 'timecreated', 'timemodified');

    /**
     * Array of optional table fields, must start with 'id'.
     * @var array $requiredfields
     */
    public $optionalfields = array();

    /**
     * evalcomix ID associated
     * @var int $evxid
     */
    public $evxid;

    /**
     * Tool title
     * @var string $title
     */
    public $title;

    /**
     * Tool type
     * @var string $type
     */
    public $type;

    /**
     * ID of Web tool
     * @var string $idtool
     */
    public $idtool;

    /**
     * The first time this evalcomix_tool was created.
     * @var int $timecreated
     */
    public $timecreated;

    /**
     * The last time this evalcomix_tool was modified.
     * @var int $timemodified
     */
    public $timemodified;


    /**
     * Constructor
     *
     * @param int $id ID
     * @param int $evxid foreign key of table 'block_evalcomix'
     * @param string $title Tool Title
     * @param string $type Tool type. Can be: scale, list, listscale, rubric, differential, mixed
     */
    public function __construct($id = '', $evxid = '0', $title = '', $type = '', $idtool = '0',
        $timecreated = '0', $timemodified = '0') {
        if ($evxid != 0) {
            global $DB;
            $this->id = intval($id);
            $this->evxid = intval($evxid);
            $this->title = addslashes($title);
            $this->type = addslashes($type);
            if ($idtool != '0') {
                $this->idtool = $idtool;
            } else {
                $this->idtool = block_evalcomix_webservice_client::generate_token();
            }
            $this->timecreated = 0;
            $this->timemodified = 0;
            $course = $DB->get_record('block_evalcomix', array('id' => $evxid), '*', MUST_EXIST);
            if ($this->type != 'scale' && $this->type != 'list' && $this->type != 'listscale' && $this->type != 'rubric'
                && $this->type != 'mixed' && $this->type != 'differential' && $this->type != 'argumentset'
                && $this->type != 'tmp') {
                throw new \moodle_exception('The type assessment tool is wrong');
            }
        }
    }

    /**
     * Finds and returns all evalcomix_tool instances.
     * @static abstract
     *
     * @return array array of evalcomix_tool instances or false if none found.
     */
    public static function fetch_all($params) {
        return block_evalcomix_object::fetch_all_helper('block_evalcomix_tools', 'block_evalcomix_tool', $params);
    }

    /**
     * Finds and returns a evalcomix_tool instance based on params.
     * @static
     *
     * @param array $params associative arrays varname=>value
     * @return object grade_item instance or false if none found.
     */
    public static function fetch($params) {
        return block_evalcomix_object::fetch_helper('block_evalcomix_tools', 'block_evalcomix_tool', $params);
    }

    /**
     * @param int $courseid
     * @return array of tools. Key of array is 'id' and value of array is 'title' tool
     */
    public static function get_tools($courseid) {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tool.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix.php');
        $result = array();

        $params = array('courseid' => $courseid);
        if (!$environment = $DB->get_record('block_evalcomix', $params)) {
            return $result;
        }

        $evxid = $environment->id;
        $params = array('evxid' => $evxid);
        $tools = $DB->get_records('block_evalcomix_tools', $params);
        if (!is_array($tools)) {
            $tools = array();
        }
        foreach ($tools as $key => $value) {
            if ($value->type != 'tmp') {
                $result[$value->id] = $value->title;
            }
        }
        return $result;
    }

    public static function delete_tool($id) {
        global $CFG, $DB;
        $result = false;
        if ($DB->get_record('block_evalcomix_tools', array('id' => $id))) {
            $DB->delete_records('block_evalcomix_subdimension', array('toolid' => $id));
            if ($modes = $DB->get_records('block_evalcomix_modes', array('toolid' => $id))) {
                require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes.php');
                foreach ($modes as $mode) {
                    block_evalcomix_modes::delete_mode($mode->id);
                }
            }
            $result = $DB->delete_records('block_evalcomix_tools', array('id' => $id));
        }
        return $result;
    }
}
