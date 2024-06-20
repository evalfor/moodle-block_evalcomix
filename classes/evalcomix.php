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
 * Definitions of EvalCOMIX object class
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

class block_evalcomix_class extends block_evalcomix_object {
    public $table = 'block_evalcomix';

     /**
      * Array of required table fields, must start with 'id'.
      * @var array $requiredfields
      */
    public $requiredfields = array('id', 'courseid', 'viewmode', 'sendgradebook');

    /**
     * Array of optional table fields, must start with 'id'.
     * @var array $requiredfields
     */
    public $optionalfields = array();

    /**
     * Course ID associated
     * @var int $evxid
     */
    public $courseid;

    /**
     * View Mode
     * @var string $viewmode
     */
    public $viewmode;

    /**
     * 1 or 0 if grades are sended to grade book
     * @var smallint $sendgradebook
     */
    public $sendgradebook;

    /**
     * Constructor
     *
     * @param int $id ID
     * @param int $courseid foreign key of table 'course'
     * @param string $viewmode view mode. It can be: 'evalcomix' or 'evalmoodle'
     */
    public function __construct($id = '', $courseid = '0', $viewmode = 'evalcomix', $sendgradebook = '0') {
        if ($courseid != 0) {
            global $DB;
            $this->id = intval($id);
            $this->courseid = intval($courseid);
            $this->viewmode = $viewmode;
            $this->sendgradebook = $sendgradebook;
            $course = $DB->get_record('course', array('id' => $this->courseid), '*', MUST_EXIST);
            // Adding to control viewmode.
            if ($this->viewmode != 'evalcomix' && $this->viewmode != 'evalmoodle') {
                throw new \moodle_exception('The view mode is wrong');
            }
        }
    }

    /**
     * Finds and returns a evalcomix instance based on params.
     * @static
     *
     * @param array $params associative arrays varname=>value
     * @return object grade_item instance or false if none found.
     */
    public static function fetch($params) {
        return block_evalcomix_object::fetch_helper('block_evalcomix', 'block_evalcomix_class', $params);
    }
}
