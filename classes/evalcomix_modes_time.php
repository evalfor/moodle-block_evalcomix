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
 * @author     Daniel Cabeza S�nchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hern�ndez <juanantonio.caballero@uca.es>
 */
class block_evalcomix_modes_time extends block_evalcomix_modes {
    public $table = 'block_evalcomix_modes_time';

    /**
     * Array of required table fields, must start with 'id'.
     * @var array $requiredfields
     */
    public $requiredfields = array('id', 'modeid', 'timeavailable', 'timedue');

    /**
     * Array of optional table fields.
     * @var array $optionalfields
     */
    public $optionalfields = array();

    /**
     * Mode ID associated
     * @var int $modeid
     */
    public $modeid;

    /**
     * The time this task is available.
     * @var int $timeavailable
     */
    public $timeavailable;

    /**
     * The time this task is not available.
     * @var int $timedue
     */
    public $timedue;

    /**
     * Constructor
     *
     * @param int $id ID
     * @param int $modeid foreign key of table 'block_evalcomix_modes'
     */
    public function __construct($id = '', $modeid = '0', $timeavailable = '0', $timedue = '0') {
        if ($modeid != '0') {
            global $DB;
            $this->id = intval($id);
            // A�adido comprobaci�n.
            $modesobject = $DB->get_record('block_evalcomix_modes', array('id' => $modeid), '*', MUST_EXIST);
            $this->modeid = $modesobject->id;
            // Fin a�adido, si no funciona, comentar lo anterior y descomentar siguiente l�nea.
            $this->timeavailable = intval($timeavailable);
            $this->timedue = intval($timedue);
        }
    }

    /**
     * Finds and returns a evalcomix_modes_time instance based on params.
     * @static
     *
     * @param array $params associative arrays varname=>value
     * @return object grade_item instance or false if none found.
     */
    public static function fetch($params) {
        return block_evalcomix_object::fetch_helper('block_evalcomix_modes_time', 'block_evalcomix_modes_time', $params);
    }

    /**
     * Finds and returns all evalcomix_tool instances.
     * @static abstract
     *
     * @return array array of evalcomix_tool instances or false if none found.
     */
    public static function fetch_all($params) {
        return block_evalcomix_object::fetch_all_helper('block_evalcomix_modes_time', 'block_evalcomix_modes_time', $params);
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
     * @return bool|int if exist return ID else return 0
     */
    public function exist() {
        global $DB;
        if (!$data = $DB->get_record($this->table, array('modeid' => $this->modeid))) {
            return 0;
        }
        $this->id = $data->id;
        return $data->id;
    }
}
