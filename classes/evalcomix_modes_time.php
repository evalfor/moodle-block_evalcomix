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
            // Añadido comprobación.
            $modesobject = $DB->get_record('block_evalcomix_modes', array('id' => $modeid), '*', MUST_EXIST);
            $this->modeid = $modesobject->id;
            // Fin añadido, si no funciona, comentar lo anterior y descomentar siguiente línea.
            $this->timeavailable = intval($timeavailable);
            $this->timedue = intval($timedue);
        }
    }
}
