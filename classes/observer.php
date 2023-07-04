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
 * Define cron task.
 *
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>
 */

/**
 * Event observer for block_evalcomix.
 */
class block_evalcomix_observer {

    /**
     * Triggered via group_member_removed event.
     *
     * @param \core\event\group_member_removed $event
     */
    public static function group_member_removed(\core\event\group_member_removed $event) {
        global $DB;

        $userid = $event->relateduserid;
        $groupid = $event->objectid;
        if ($coordinators = $DB->get_records('block_evalcomix_coordinators', array('groupid' => $groupid, 'userid' => $userid))) {
            $DB->delete_records('block_evalcomix_coordinators', array('groupid' => $groupid, 'userid' => $userid));
        }
    }

    public static function group_deleted(\core\event\group_deleted $event) {
        global $DB;

        $groupid = $event->objectid;
        if ($coordinators = $DB->get_records('block_evalcomix_coordinators', array('groupid' => $groupid))) {
            $DB->delete_records('block_evalcomix_coordinators', array('groupid' => $groupid));
        }
    }

    public static function student_deleted(\core\event\user_deleted $event) {
        global $CFG;
        require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');
        if ($event->objecttable == 'user') {
            $studentid = $event->objectid;
            block_evalcomix_student_deleted($studentid);
        }
    }
}
