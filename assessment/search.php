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
require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_allowedusers.php');

$search = required_param('search', PARAM_RAW);
$courseid      = required_param('id', PARAM_INT);
$id = required_param('a', PARAM_INT);
$type = optional_param('t', 0, PARAM_INT);
$assessorid = optional_param('as', 0, PARAM_INT);
require_login($courseid);

if ($id) {
    $cm = get_coursemodule_from_id('', $id, 0, false, MUST_EXIST);
    if (!$course = $DB->get_record('course', array('id' => $courseid))) {
        print_error('nocourseid');
    }
}

$context = context_course::instance($courseid);

$reportevalcomix = new grade_report_evalcomix($courseid, null, $context);
$users = $reportevalcomix->load_users(false);
$allowedusershash = array();


$output = '<select id="assessorid" name="assessorid" style="width:20em" size="20"
onclick="document.getElementById(\'submit\').disabled =
false;doWork(\'targetstudents\', \'targetstudents.php\', \'u=\'+this.value+\'&id='.$courseid.'&a='.$cm->id.'\');">';
if ($type == 'potential' && $assessorid > 0) {
    $allowedusershash[$assessorid] = true;
    if ($allowedusers = evalcomix_allowedusers::fetch_all(array('cmid' => $id, 'assessorid' => $assessorid))) {
        foreach ($allowedusers as $alloweduser) {
            $userid = $alloweduser->studentid;
            $allowedusershash[$userid] = true;
        }
    }
    $output = '<select multiple name="pu[]" style="width:20em" size="20"
    onchange="document.getElementById(\'add\').disabled=false">';
}
foreach ($users as $user) {
    $needle = trim($search);
    if ($needle != '') {
        $pos1 = strpos($user->firstname, $needle);
        $pos2 = strpos($user->lastname, $needle);
        $userid = $user->id;
        if (!isset($allowedusershash[$userid])) {
            if ($pos1 !== false || $pos2 !== false) {
                $output .= '<option value="'.$userid.'">'.fullname($user).'</option>';
            }
        }
    } else {
        $userid = $user->id;
        if (!isset($allowedusershash[$userid])) {
            $output .= '<option value="'.$user->id.'">'.fullname($user).'</option>';
        }
    }
}
$output .= '</select>';

echo $output;