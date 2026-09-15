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
 * Library of functions for EvalCOMIX-FLOASS outside of the core api
 *
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes.php');

/**
 * This function gets user submission
 *
 * @param stdclass $assignment
 * @param int $userid
 * @return stdclass|false
 */
function block_evalcomix_get_user_submission($assignment, $userid) {
    global $DB, $USER;

    if (!$userid) {
        $userid = $USER->id;
    }

    // If the userid is not null then use userid.
    $submission = $DB->get_record('assign_submission', ['assignment' => $assignment->get_instance()->id,
        'userid' => $userid]);

    if ($submission) {
        return $submission;
    }

    return false;
}

/**
 * This function generate file path
 *
 * @param stdclass $course
 * @param stdclass $cm
 * @param stdclass $context
 * @param stdclass $filearea
 * @param array $args
 * @param bool $forcedownload
 * @return string|false
 */
function block_evalcomix_assignsubmission_file_pluginfile($course, $cm, context $context, $filearea, $args, $forcedownload) {
    global $USER, $DB;

    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    $itemid = (int)array_shift($args);
    $record = $DB->get_record('assign_submission', ['id' => $itemid], 'userid, assignment', MUST_EXIST);

    if (!$assign = $DB->get_record('assign', ['id' => $cm->instance])) {
        return false;
    }

    if ($assign->id != $record->assignment) {
        return false;
    }

    $relativepath = implode('/', $args);

    return "/{$context->id}/assignsubmission_file/submission_files/$itemid/$relativepath";
}

/**
 * This function generate file args
 *
 * @param stdclass $tree
 * @param array $dir
 * @return string|array
 */
function htmllize_tree(assign_files $tree, $dir) {
    global $CFG;

    if (empty($dir['files'])) {
        return '';
    }

    $result = [];
    foreach ($dir['files'] as $file) {
        $filename = $file->get_filename();
        $itemid = $file->get_itemid();
        $args = [$itemid, $filename];
        $result[] = $args;
    }

    return $result;
}

/**
 * This function gets course members
 *
 * @param int $courseid
 * @param int $groupid
 * @param int $page
 * @return array Users
 */
function block_evalcomix_get_members_course($courseid, $groupid = 0, $page = '0') {
    global $DB;

    $members = [];
    if ($course = $DB->get_record('course', ['id' => $courseid])) {
        $contextcourse = context_course::instance($courseid);
        if ($groupid !== 'nogroup' && is_numeric($groupid)) {
            $members = get_enrolled_users(
                $contextcourse,
                'moodle/course:isincompletionreports',
                $groupid,
                'u.*',
                'u.lastname ASC, u.firstname ASC'
            );
        }
    }

    return $members;
}

/**
 * This function gets assessment ID
 *
 * @param array $params
 *      int courseid
 *      string module
 *      int cmid
 *      int studentid
 *      int assessorid
 *      string mode
 * @return string Assessment ID
 */
function block_evalcomix_get_assessmentid($params = []) {
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

/**
 * This function gets assessment ID if assessment is saved
 *
 * @param stdclass $assessment
 * @return string Assessment ID
 */
function block_evalcomix_get_existing_assessmentid($assessment) {
    global $CFG, $DB;
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');
    $result = 0;

    $assessorid = $assessment->assessorid;
    $studentid = $assessment->studentid;
    $lms = BLOCK_EVALCOMIX_MOODLE_NAME;
    if ($task = $DB->get_record('block_evalcomix_tasks', ['id' => $assessment->taskid])) {
        $cmid = $task->instanceid;
        if ($cm = $DB->get_record('course_modules', ['id' => $cmid])) {
            $courseid = $cm->course;
            $module = block_evalcomix_tasks::get_type_task($cmid);
            $mode = block_evalcomix_grade_report::get_type_evaluation($studentid, $courseid, $assessorid);
            $result = block_evalcomix_get_assessmentid(['courseid' => $courseid, 'module' => $module,
                'cmid' => $cmid, 'studentid' => $studentid, 'assessorid' => $assessorid, 'mode' => $mode]);
        }
    }
    return $result;
}

/**
 * This function update assessment ID
 *
 * @param stdclass $assessment
 * @return string Assessment ID
 */
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

/**
 * This function set assessments modified.
 *
 * @param array $toolist
 * @param array $tasks
 * @param int $courseid
 * @return bool
 */
function block_evalcomix_update_assessment_modified($toollist, $tasks, $courseid) {
    global $CFG, $DB;
    $newgrades = block_evalcomix_webservice_client::get_assessments_modified(['tools' => $toollist]);
    if (empty($newgrades)) {
        return false;
    }
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_assessments.php');
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_grades.php');
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');
    $toolids = [];
    foreach ($tasks as $task) {
        $assessments = $DB->get_records('block_evalcomix_assessments', ['taskid' => $task->id]);
        foreach ($assessments as $assessment) {
            $activity = $task->instanceid;
            $module = block_evalcomix_tasks::get_type_task($activity);
            $mode = block_evalcomix_grade_report::get_type_evaluation(
                $assessment->studentid,
                $courseid,
                $assessment->assessorid
            );
            $str = $courseid . '_' . $module . '_' . $activity . '_' . $assessment->studentid .
            '_' . $assessment->assessorid . '_' . $mode . '_' . BLOCK_EVALCOMIX_MOODLE_NAME;
            $assessmentid = md5($str);
            if (!isset($newgrades[$assessmentid])) {
                continue;
            }
            if (isset($newgrades[$assessmentid]->toolid)) {
                $toolids[] = $newgrades[$assessmentid]->toolid;
            }
        }
    }
    block_evalcomix_webservice_client::set_assessments_modified(['toolids' => $toolids]);
    return true;
}

/**
 * This function update finalgrade.
 *
 * @param array $newgrades
 * @param stdclass $assessment
 * @param int $courseid
 * @param stdclass $task
 */
function block_evalcomix_update_assessment_modified_helper($newgrades, $assessment, $courseid, $task) {
    global $CFG, $DB;
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/finalgrades.php');
    if (is_numeric($newgrades[$assessmentid]->grade)) {
        $grade = $newgrades[$assessmentid]->grade;
        $DB->update_record('block_evalcomix_assessments', ['id' => $assessment->id,
        'taskid' => $assessment->taskid, 'assessorid' => $assessment->assessorid,
        'studentid' => $assessment->studentid, 'grade' => $grade, 'timemodified' => time()]);
    }
    if (
        $evalcomixgrade = $DB->get_record('block_evalcomix_grades', ['courseid' => $courseid,
        'cmid' => $task->instanceid, 'userid' => $assessment->studentid])
    ) {
        $params = ['cmid' => $task->instanceid, 'userid' => $assessment->studentid,
        'courseid' => $courseid];
        $finalgrade = block_evalcomix_finalgrade::get_finalgrade_user_task($params);
        if ($finalgrade !== null && (int)$finalgrade > -1) {
            $DB->update_record('block_evalcomix_grades', ['id' => $evalcomixgrade->id,
                'userid' => $evalcomixgrade->userid, 'cmid' => $evalcomixgrade->cmid, 'finalgrade' => $finalgrade,
                'courseid' => $evalcomixgrade->courseid]);
        }
    }
}
