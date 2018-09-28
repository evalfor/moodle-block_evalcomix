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

/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

class evalcomix_allowedusers extends evalcomix_object{
    public $table = 'block_evalcomix_allowedusers';

     /**
      * Array of required table fields, must start with 'id'.
      * @var array $requiredfields
      */
    public $requiredfields = array('id', 'cmid', 'assessorid', 'studentid');

    /**
     * Array of required table fields, must start with 'id'.
     * @var array $requiredfields
     */
    public $optionalfields = array();

    /**
     * @var int $cmid
     */
    public $cmid;

     /**
      * If the assessor will be assessorid or not.
      * @var int $assessorid
      */
    public $assessorid;

    /**
     * If the modality will be studentid before its limit date.
     * @var int $assessorid
     */
    public $studentid;


    /**
     * Constructor
     *
     * @param int $params[id] ID
     * @param int $params[cmid] foreign key of table 'block_evalcomix_modes'
     * @param int $params[assessorid] foreign key of table 'users'
     * @param int $params[studentid] foreign key of table 'users'
     */
    public function __construct($params = array()) {
        if (!empty($params) && isset($params['cmid']) && $params['cmid'] != '0') {
            global $DB;
            if (isset($params['id'])) {
                $this->id = intval($params['id']);
            }
            if (isset($params['assessorid'])) {
                $this->assessorid = intval($params['assessorid']);
            }
            if (isset($params['studentid'])) {
                $this->studentid = intval($params['studentid']);
            }
            if (isset($params['cmid'])) {
                $this->cmid = intval($params['cmid']);
            }
        }
    }

    /**
     * Finds and returns a evalcomix_allowedusers instance based on params.
     * @static
     *
     * @param array $params associative arrays varname=>value
     * @return object grade_item instance or false if none found.
     */
    public static function fetch($params) {
        return evalcomix_object::fetch_helper('block_evalcomix_allowedusers', 'evalcomix_allowedusers', $params);
    }

    /**
     * Finds and returns all evalcomix_tool instances.
     * @static abstract
     *
     * @return array array of evalcomix_tool instances or false if none found.
     */
    public static function fetch_all($params) {
        return self::fetch_all_helper('block_evalcomix_allowedusers', 'evalcomix_allowedusers', $params);
    }

    /**
     * @return bool|int if exist return ID else return 0
     */
    public function exist() {
        global $DB;
        if (!$data = $DB->get_record($this->table, array('cmid' => $this->cmid))) {
            return 0;
        }
        $this->id = $data->id;
        return $data->id;
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