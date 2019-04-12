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
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>,
               Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

require_once('../../../config.php');
require_login();
require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_allowedusers.php');

$id = required_param('a', PARAM_INT);
$courseid = required_param('id', PARAM_INT);
$assessorid = required_param('u', PARAM_INT);

$context = context_course::instance($courseid);
$reportevalcomix = new block_evalcomix_grade_report($courseid, null, $context);
$users = $reportevalcomix->load_users(false);

echo '<select style="width:20em" size="20">';

if ($allowedusers = evalcomix_allowedusers::fetch_all(array('cmid' => $id, 'assessorid' => $assessorid))) {
    foreach ($allowedusers as $alloweduser) {
        $userid = $alloweduser->studentid;
        echo '<option>'.fullname($users[$userid]).'</option>';
    }
}

echo '</select>';