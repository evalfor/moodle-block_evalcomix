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
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>
 */

function block_evalcomix_get_user_submission($assignment, $userid) {
    global $DB, $USER;

    if (!$userid) {
        $userid = $USER->id;
    }

    // If the userid is not null then use userid.
    $submission = $DB->get_record('assign_submission', array('assignment' => $assignment->get_instance()->id,
        'userid' => $userid));

    if ($submission) {
        return $submission;
    }

    return false;
}


function block_evalcomix_assignsubmission_file_pluginfile($course, $cm, context $context, $filearea, $args, $forcedownload) {
    global $USER, $DB;

    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    $itemid = (int)array_shift($args);
    $record = $DB->get_record('assign_submission', array('id' => $itemid), 'userid, assignment', MUST_EXIST);

    if (!$assign = $DB->get_record('assign', array('id' => $cm->instance))) {
        return false;
    }

    if ($assign->id != $record->assignment) {
        return false;
    }

    $relativepath = implode('/', $args);

    $fullpath = "/{$context->id}/assignsubmission_file/submission_files/$itemid/$relativepath";
    return $fullpath;
}

function htmllize_tree(assign_files $tree, $dir) {
    global $CFG;

    if (empty($dir['files'])) {
        return '';
    }

    $result = array();
    foreach ($dir['files'] as $file) {
        $filename = $file->get_filename();
        $itemid = $file->get_itemid();
        $args = array($itemid, $filename);
        $result[] = $args;
    }

    return $result;
}

function block_evalcomix_get_user_submission_old($assignment, $userid) {
    global $DB, $USER;

    if (!$userid) {
        $userid = $USER->id;
    }
    // If the userid is not null then use userid.
    $submission = $DB->get_record('assignment_submissions', array('assignment' => $assignment->assignment->id,
        'userid' => $userid));

    if ($submission) {
        return $submission;
    }

    return false;
}

function block_evalcomix_get_members_course($courseid, $groupid = 0, $page = '0', $search = '') {
    global $DB;

    $members = array();
    if ($course = $DB->get_record('course', array('id' => $courseid))) {
        $contextcourse = context_course::instance($courseid);
        $conditions = '';
        if (!empty($search)) {
            $words = explode(' ', $search);
            foreach ($words as $word) {
                $conditions .= ' AND (u.firstname LIKE "%'. $word .'%" OR u.lastname LIKE "%'. $word .'%") ';
            }
            $joins = $where = '';
            $params = array();
            $params['contextid'] = $contextcourse->id;
            $sql = 'SELECT u.*
            FROM {user} u
            '.$joins.'
            WHERE 1 '. $conditions .' AND u.id IN (
                SELECT ra.userid
                FROM {role} r, {role_assignments} ra
                WHERE ra.userid = u.id AND ra.roleid = r.id AND r.shortname="student" AND ra.contextid = :contextid)
                '.$where;
            $members = $DB->get_records_sql($sql, $params);
        } else if ($groupid !== 'nogroup' && is_numeric($groupid)) {
            $members = get_enrolled_users($contextcourse, 'moodle/course:isincompletionreports', $groupid, 'u.*',
                'u.firstname ASC');
        } else if ($groupid === 'nogroup') {
            $joins = $where = '';
            $params = array();
            $params['courseid'] = $courseid;
            $params['contextid'] = $contextcourse->id;
            $sql = 'SELECT u.*
            FROM {user} u
            '.$joins.'
            WHERE u.deleted = 0 AND u.id IN (
                SELECT ra.userid
                FROM {role} r, {role_assignments} ra
                WHERE ra.userid = u.id AND ra.roleid = r.id AND r.shortname="student" AND ra.contextid = :contextid)
            AND u.id NOT IN (
                SELECT gm.userid
                FROM {groups} g, {groups_members} gm
                WHERE g.courseid = :courseid AND g.id = gm.groupid)
            ' . $where;
            $members = $DB->get_records_sql($sql, $params);
        } else if ($groupid === 'all') {
            list($esql, $params) = get_enrolled_sql($contextcourse, 'moodle/course:isincompletionreports');
            $sql = '';

            $members = get_enrolled_users($contextcourse, 'moodle/course:isincompletionreports', 0, 'u.*', 'u.firstname ASC');
        }
    }

    return $members;
}

function block_evalcomix_get_assessmentid($params = array()) {
    global $CFG;
    require_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');

    $courseid = (isset($params['courseid'])) ? $params['courseid'] : 0;
    $module = (isset($params['module'])) ? $params['module'] : 0;
    $cmid = (isset($params['cmid'])) ? $params['cmid'] : 0;
    $studentid = (isset($params['studentid'])) ? $params['studentid'] : 0;
    $assessorid = (isset($params['assessorid'])) ? $params['assessorid'] : 0;
    $mode = (isset($params['mode'])) ? $params['mode'] : 0;
    $lms = BLOCK_EVALCOMIX_MOODLE_NAME;

    return md5($courseid . '_' . $module . '_' . $cmid . '_' . $studentid . '_' . $assessorid . '_' .
                $mode . '_' . $lms);
}

function block_evalcomix_get_existing_assessmentid($assessment) {
    global $CFG, $DB;
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
    $result = 0;

    $assessorid = $assessment->assessorid;
    $studentid = $assessment->studentid;
    $lms = BLOCK_EVALCOMIX_MOODLE_NAME;
    if ($task = $DB->get_record('block_evalcomix_tasks', array('id' => $assessment->taskid))) {
        $cmid = $task->instanceid;
        if ($cm = $DB->get_record('course_modules', array('id' => $cmid))) {
            $courseid = $cm->course;
            $module = block_evalcomix_tasks::get_type_task($cmid);
            $mode = block_evalcomix_grade_report::get_type_evaluation($studentid, $courseid, $assessorid);
            $result = block_evalcomix_get_assessmentid(array('courseid' => $courseid, 'module' => $module,
                'cmid' => $cmid, 'studentid' => $studentid, 'assessorid' => $assessorid, 'mode' => $mode));
        }
    }
    return $result;
}

function block_evalcomix_fill_assessmentid() {
    global $CFG, $DB;
    $result = array();

    if ($assessments = $DB->get_records('block_evalcomix_assessments', array())) {
        foreach ($assessments as $assessment) {
            $assessmentid = $assessment->id;
            $idassessment = block_evalcomix_update_assessmentid($assessment);
            $result[$assessmentid] = $idassessment;
        }
    }

    return $result;
}

function block_evalcomix_update_assessmentid($assessment) {
    global $CFG, $DB;
    $update = false;
    if (isset($assessment->idassessment) && $assessment->idassessment === '0') {
        $assessment->idassessment = block_evalcomix_get_existing_assessmentid($assessment);
        if ($assessment->idassessment !== '0') {
            $update = true;
        }
    }
    if (empty($assessment->modeid)) {
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes.php');
        if ($modeid = block_evalcomix_modes::get_mode($assessment)) {
            $assessment->modeid = $modeid;
            $update = true;
        }
    }
    if ($update) {
        $DB->update_record('block_evalcomix_assessments', $assessment);
    }

    return $assessment->idassessment;
}
