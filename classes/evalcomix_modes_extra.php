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
class block_evalcomix_modes_extra extends block_evalcomix_modes {
    public $table = 'block_evalcomix_modes_extra';

    /**
     * Array of required table fields, must start with 'id'.
     * @var array $requiredfields
     */
    public $requiredfields = array('id', 'modeid', 'anonymous', 'visible', 'whoassesses');

    /**
     * Array of required table fields, must start with 'id'.
     * @var array $requiredfields
     */
    public $optionalfields = array();

    /**
     * Mode ID associated
     * @var int $modeid
     */
    public $modeid;

    /**
     * If the assessor will be anonymous or not.
     * @var int $anonymous
     */
    public $anonymous;

    /**
     * If the modality will be visible before its limit date.
     * @var int $anonymous
     */
    public $visible;

    /**
     * It indicate who assesses who: '0' Any student, '1' Groups, '2' Specific students
     * @var int $whoassesses
     */
    public $whoassesses;

    /**
     * Constructor
     *
     * @param int $id ID
     * @param int $modeid foreign key of table 'block_evalcomix_modes'
     * @param bool $anonymous indicates if EI will be anonymous or not
     */
    public function __construct($id = '', $modeid = '0', $anonymous = '0', $visible = '0', $whoassesses = '0') {
        if ($modeid != '0') {
            global $DB;
            $this->id = intval($id);
            $this->anonymous = intval($anonymous);
            $this->visible = intval($visible);
            $modesobject = $DB->get_record('block_evalcomix_modes', array('id' => $modeid), '*', MUST_EXIST);
            $this->modeid = $modesobject->id;
            $this->whoassesses = intval($whoassesses);
        }
    }
}
