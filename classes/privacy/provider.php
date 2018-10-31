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

namespace block_evalcomix\privacy;
defined('MOODLE_INTERNAL') || die();

use core_privacy\local\metadata\collection;
use \core_privacy\local\request\contextlist;
use core_privacy\local\request\transform;
use core_privacy\local\request\writer;
use \core_privacy\local\request\approved_contextlist;

class provider implements \core_privacy\local\metadata\provider, \core_privacy\local\request\plugin\provider {

    public static function get_metadata(collection $collection) : collection {

        $collection->add_database_table(
            'block_evalcomix_allowedusers',
             [
                'assessorid' => 'privacy:metadata:block_evalcomix_allowedusers:assessorid',
                'studentid' => 'privacy:metadata:block_evalcomix_allowedusers:studentid',
             ],
            'privacy:metadata:block_evalcomix_allowedusers'
        );

        $collection->add_database_table(
            'block_evalcomix_assessments',
             [
                'assessorid' => 'privacy:metadata:block_evalcomix_assessments:assessorid',
                'studentid' => 'privacy:metadata:block_evalcomix_assessments:studentid',
                'grade' => 'privacy:metadata:block_evalcomix_assessments:grade',
                'timemodified' => 'privacy:metadata:block_evalcomix_assessments:timemodified',
             ],
            'privacy:metadata:block_evalcomix_assessments'
        );

        $collection->add_database_table(
            'block_evalcomix_grades',
             [
                'userid' => 'privacy:metadata:block_evalcomix_grades:userid',
                'finalgrade' => 'privacy:metadata:block_evalcomix_grades:finalgrade',
             ],
            'privacy:metadata:block_evalcomix_grades'
        );
        return $collection;
    }


    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param   int           $userid       The user to search.
     * @return  contextlist   $contextlist  The list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid) : contextlist {
        $sql = "SELECT ctx.id
                FROM {block_evalcomix_tasks} bet
                JOIN {block_evalcomix_grades} beg
                    ON bet.instanceid = beg.cmid
                JOIN {block_evalcomix_assessments} bea
                    ON bet.id = bea.taskid
                JOIN {block_evalcomix_allowedusers} beau
                    ON bet.instanceid = beau.cmid
                JOIN {user} u
                    ON u.id = beg.userid
                JOIN {context} ctx
                    ON ctx.instanceid = u.id
                        AND ctx.contextlevel = :contextlevel
                WHERE u.id = :userid AND ((bea.studentid = u.id AND beau.studentid = u.id)
                    OR (bea.assessorid = u.id AND beau.assessorid = u.id)) ";

        $params = ['userid' => $userid, 'contextlevel' => CONTEXT_USER];

        $contextlist = new contextlist();
        $contextlist->add_from_sql($sql, $params);
        return $contextlist;
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (!$contextlist->count()) {
            return;
        }

        $user = $contextlist->get_user();

        self::export_block_evalcomix_allowedusers($context, $user);
        self::export_block_evalcomix_assessments($context, $user);
        self::export_block_evalcomix_grades($context, $user);
    }

    /**
     * Export one entry in the database activity module (one record in {data_records} table)
     *
     * @param \context $context
     * @param \stdClass $user
     * @param \stdClass $recordobj
     */
    protected static function export_block_evalcomix_allowedusers($context, $user) {
        global $DB;
        $sql = "SELECT beau.*
                FROM {block_evalcomix_tasks} bet
                JOIN {block_evalcomix_grades} beg
                    ON bet.instanceid = beg.cmid
                JOIN {block_evalcomix_assessments} bea
                    ON bet.id = bea.taskid
                JOIN {block_evalcomix_allowedusers} beau
                    ON bet.instanceid = beau.cmid
                JOIN {user} u
                    ON u.id = beg.userid
                JOIN {context} ctx
                    ON ctx.instanceid = u.id
                        AND ctx.contextlevel = :contextlevel
                WHERE ctx.id = :contextid AND u.id = :userid AND ((bea.studentid = u.id AND beau.studentid = u.id)
                    OR (bea.assessorid = u.id AND beau.assessorid = u.id)) ";

        $params = ['userid' => $user->id, 'contextid' => $context->id, 'contextlevel' => CONTEXT_USER];
        if (!$recordobj = $DB->get_records_sql($sql, $params)) {
            return;
        }

        $recordobj = new stdclass;
        foreach ($record as $rec) {
            $recordobj->assessorid = $rec->assessorid;
            $recordobj->studentid = $rec->studentid;
        }

        $data = [
            'assessorid' => transform::user($recordobj->assessorid),
            'studentid' => transform::user($user->id),
        ];
        // Data about the record.
        writer::with_context($context)->export_data([$recordobj->id], (object)$data);
    }

    /**
     * Export one entry in the database activity module (one record in {data_records} table)
     *
     * @param \context $context
     * @param \stdClass $user
     * @param \stdClass $recordobj
     */
    protected static function export_block_evalcomix_assessments($context, $user, $recordobj) {
        global $DB;
        $sql = "SELECT bea.*
                FROM {block_evalcomix_tasks} bet
                JOIN {block_evalcomix_grades} beg
                    ON bet.instanceid = beg.cmid
                JOIN {block_evalcomix_assessments} bea
                    ON bet.id = bea.taskid
                JOIN {block_evalcomix_allowedusers} beau
                    ON bet.instanceid = beau.cmid
                JOIN {user} u
                    ON u.id = beg.userid
                JOIN {context} ctx
                    ON ctx.instanceid = u.id
                        AND ctx.contextlevel = :contextlevel
                WHERE ctx.id = :contextid AND u.id = :userid AND ((bea.studentid = u.id AND beau.studentid = u.id)
                    OR (bea.assessorid = u.id AND beau.assessorid = u.id)) ";

        $params = ['userid' => $user->id, 'contextid' => $context->id, 'contextlevel' => CONTEXT_USER];
        if (!$recordobj = $DB->get_records_sql($sql, $params)) {
            return;
        }

        $recordobj = new stdclass;
        foreach ($record as $rec) {
            $recordobj->assessorid = $rec->assessorid;
            $recordobj->studentid = $rec->studentid;
            $recordobj->grade = $rec->grade;
            $recordobj->timemodified = $rec->timemodified;
        }

        $data = [
            'assessorid' => transform::user($recordobj->assessorid),
            'studentid' => transform::user($user->id),
            'grade' => $recordobj->grade,
            'timemodified' => transform::datetime($recordobj->timemodified),
        ];
        // Data about the record.
        writer::with_context($context)->export_data([$recordobj->id], (object)$data);
    }

    protected static function export_block_evalcomix_grades($context, $user, $recordobj) {
        global $DB;
        $sql = "SELECT beg.*
                FROM {block_evalcomix_tasks} bet
                JOIN {block_evalcomix_grades} beg
                    ON bet.instanceid = beg.cmid
                JOIN {block_evalcomix_assessments} bea
                    ON bet.id = bea.taskid
                JOIN {block_evalcomix_allowedusers} beau
                    ON bet.instanceid = beau.cmid
                JOIN {user} u
                    ON u.id = beg.userid
                JOIN {context} ctx
                    ON ctx.instanceid = u.id
                        AND ctx.contextlevel = :contextlevel
                WHERE ctx.id = :contextid AND u.id = :userid AND ((bea.studentid = u.id AND beau.studentid = u.id)
                    OR (bea.assessorid = u.id AND beau.assessorid = u.id)) ";

        $params = ['userid' => $user->id, 'contextid' => $context->id, 'contextlevel' => CONTEXT_USER];
        if (!$record = $DB->get_records_sql($sql, $params)) {
            return;
        }
        $recordobj = new stdclass;
        foreach ($record as $rec) {
            $recordobj->grade = $rec->grade;
            $recordobj->userid = $rec->userid;
        }

        $data = [
            'userid' => transform::user($user->id),
            'finalgrade' => $recordobj->grade,
        ];
        // Data about the record.
        writer::with_context($context)->export_data([$recordobj->id], (object)$data);
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param \context $context The specific context to delete data for.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;
        if ($context instanceof \context_user) {
            $DB->delete_records('block_evalcomix_allowedusers', array('studentid' => $context->instanceid));
            $DB->delete_records('block_evalcomix_allowedusers', array('assessorid' => $context->instanceid));

            $DB->delete_records('block_evalcomix_assessments', array('studentid' => $context->instanceid));
            $DB->delete_records('block_evalcomix_assessments', array('assessorid' => $context->instanceid));

            $DB->delete_records('block_evalcomix_grades', array('userid' => $context->instanceid));
        }
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;
        $userid = $contextlist->get_user()->id;
        $DB->delete_records('block_evalcomix_allowedusers', array('studentid' => $userid));
        $DB->delete_records('block_evalcomix_allowedusers', array('assessorid' => $userid));

        $DB->delete_records('block_evalcomix_assessments', array('studentid' => $userid));
        $DB->delete_records('block_evalcomix_assessments', array('assessorid' => $userid));

        $DB->delete_records('block_evalcomix_grades', array('userid' => $userid));
    }
}