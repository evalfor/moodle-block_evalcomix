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
 * Defines graphic functions for the block_evalcomix
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>
 */

defined('MOODLE_INTERNAL') || die();

/**
 * This function obtains students evaluated from a group in a activity
 * @param int $groupid
 * @param int $activityid ID of evalcomix_tasks object
 * @return array of user objects
 */
function get_group_members_evaluated($groupid, $activityid) {
    global $CFG, $DB;

    $students = array();
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_assessments.php');
    if ($users = evalcomix_assessments::get_students_assessed($activityid)) {
        if ($groupsmember = $DB->get_records('groups_members', array('groupid' => $groupid))) {
            $membersids = array();
            foreach ($groupsmember as $member) {
                if (in_array($member->userid, $users)) {
                    $membersids[] = $member->userid;
                }
            }
            foreach ($membersids as $student) {
                $user = $DB->get_record('user', array('id' => $student));
                $students[] = $user;
            }
        }
    }
    return $students;
}