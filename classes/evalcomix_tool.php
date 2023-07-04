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
                print_error('The type assessment tool is wrong');
            }
        }
    }

    /**
     * Records this object in the Database, sets its id to the returned value, and returns that value.
     * If successful this function also fetches the new object data from database and stores it
     * in object properties.
     * @return int PK ID if successful, false otherwise
     */
    public function insert() {
        global $DB;

        if (!empty($this->id)) {
            debugging("Tool object already exists!");
            return false;
        }

        $this->timecreated = time();

        $data = $this->get_record_data();

        $this->id = $DB->insert_record($this->table, $data);

        // Set all object properties from real db data.
        $this->update_from_db();

        $this->notify_changed(false);
        return $this->id;
    }

    /**
     * Updates this object in the Database, based on its object variables. ID must be set.
     * @param string $source from where was the object updated (mod/forum, manual, etc.)
     * @return boolean success
     */
    public function update() {
        global $DB;

        if (empty($this->id)) {
            debugging('Can not update tool object, no id!');
            return false;
        }
        if ($this->type != 'scale' && $this->type != 'list' && $this->type != 'listscale' && $this->type != 'rubric'
            && $this->type != 'mixed' && $this->type != 'differential' && $this->type != 'argumentset') {
            print_error('The type assessment tool is wrong');
        }

        $this->timemodified = time();

        $tool = $DB->get_record('block_evalcomix_tools', array('id' => $this->id));
        $this->timecreated = $tool->timecreated;

        $data = $this->get_record_data();

        $DB->update_record($this->table, $data);

        $this->notify_changed(false);
        return true;
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
     * Called immediately after the object data has been inserted, updated, or
     * deleted in the database. Default does nothing, can be overridden to
     * hook in special behaviour.
     *
     * @param bool $deleted
     */
    public function notify_changed($deleted) {
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

    /**
     * @param object simplexml object of tool assessed
     * @return array with grade of each attribute of the tool
     */
    public static function get_attributes_grade($xml) {
        if (!is_object($xml)) {
            return false;
        }

        $result = array();
        $type = dom_import_simplexml($xml)->tagName;
        if ($type == 'mt:MixTool') {
            foreach ($xml as $valor) {
                $result .= self::get_attributes_grade_simpletool($valor);
            }
        } else {
            $result = self::get_attributes_grade_simpletool($xml);
        }
        if (empty($result)) {
            return false;
        }

        return $result;
    }

    /**
     * @param object simplexml object of a simple tool assessed
     * @return array with grade of each attribute of a simple tool
     */
    public static function get_attributes_grade_simpletool($xml) {
        if (!is_object($xml)) {
            return false;
        }

        $result = array();
        $type = dom_import_simplexml($xml)->tagName;
        if ($type == 'sd:SemanticDifferential') {
            foreach ($xml->Attribute as $attribute) {
                $key = (string)$attribute['nameN'] . '-' . (string)$attribute['nameP'];
                $value = (string)$attribute;
                array_push($result, array($key => $value));
            }
        } else {
            foreach ($xml->Dimension as $dimension) {
                $scale = array();
                foreach ($dimension->Values[0] as $value) {
                    if ($type == 'ru:Rubric') {
                        foreach ($value->instance as $grade) {
                            array_push($scale, (string)grade);
                        }
                    } else {
                        array_push($scale, (string)$value);
                    }
                }

                $numericscale = self::get_numeric_scale($scale);
                foreach ($dimension->Subdimension as $subdimension) {
                    foreach ($subdimension->Attribute as $attribute) {
                        $key = (string)$attribute['name'];
                        $value = (string)$attribute;
                        $numericvalue = $numericscale[$value];
                        array_push($result, array($key, $numericvalue));
                    }
                }
            }
        }

        if (empty($result)) {
            return false;
        }

        return $result;
    }

    /**
     * Converts $scale in numeric $scale from 0 to 100
     * @param array $scale array of values alphanumeric
     * @return array with numeric scale
     */
    public static function get_numeric_scale($scale) {
        if (!is_array($scale)) {
            return false;
        }
        $isnumeric = true;
        foreach ($scale as $grade) {
            if (!is_numeric($grade)) {
                $isnumeric = false;
            }
        }

        if ($isnumeric) {
            return $scale;
        }

        $result = array();

        // First Value.
        $key = $scale[0];
        $result[$key] = 0;

        // Next Values.
        $size = count($scale);
        $distance = 100 / ($size - 1);
        $accumulator = 0;
        for ($i = 1; $i <= ($size - 1); $i++) {
            $accumulator += $distance;
            $key = $scale[$i];
            $result[$key] = $accumulator;
            if ($i == ($size - 1)) {
                $result[$key] = 100;
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
