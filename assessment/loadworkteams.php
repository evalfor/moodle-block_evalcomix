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
 * @package       block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author       Daniel Cabeza Sánchez <info@ansaner.net>
 */

require_once('../../../config.php');
$courseid = required_param('id', PARAM_INT);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
require_course_login($course);
$studentid = required_param('stu', PARAM_INT);
$newassessedid = required_param('newstu', PARAM_INT);
$cmid = required_param('cma', PARAM_INT);
$student = $DB->get_record('user', array('id' => $studentid), '*', MUST_EXIST);
$newassessed = $DB->get_record('user', array('id' => $newassessedid), '*', MUST_EXIST);
$cm = $DB->get_record('course_modules', array('id' => $cmid), '*', MUST_EXIST);

require_once($CFG->dirroot . '/blocks/evalcomix/locallib.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_grades.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_assessments.php');

$context = context_course::instance($courseid);
$reportevalcomix = new block_evalcomix_grade_report($courseid, null, $context);
try {
    $reportevalcomix->process_data(array('stu' => $studentid, 'cma' => $cmid));
} catch (Exception $e) {
    // Processed on a previous call.
    echo '';
}

$lms = BLOCK_EVALCOMIX_MOODLE_NAME;
$module = block_evalcomix_tasks::get_type_task($cmid);

$assessorid = $USER->id;
$mode = block_evalcomix_grade_report::get_type_evaluation($studentid, $courseid);
$event = \block_evalcomix\event\student_assessed::create(array('objectid' => $cmid,
        'courseid' => $courseid, 'context' => $context, 'userid' => $assessorid, 'relateduserid' => $studentid));
$event->trigger();
$memberid = $newassessedid;
if ($task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cmid))) {
    $taskid = $task->id;
    $configured = $reportevalcomix->configured_activity($cmid);
    $mode = block_evalcomix_grade_report::get_type_evaluation($studentid, $courseid);
    $tools = array();
    if ($modeobject = $DB->get_record('block_evalcomix_modes', array('taskid' => $taskid, 'modality' => $mode))) {
        if ($tool = $DB->get_record('block_evalcomix_tools', array('id' => $modeobject->toolid))) {
            $object = new stdClass();
            $object->oldid = $tool->idtool;
            $object->newid = $tool->idtool;
            $tools[] = $object;
        }
    }
    if ($assessment = $DB->get_record('block_evalcomix_assessments', array('taskid' => $taskid, 'assessorid' => $assessorid,
            'studentid' => $studentid))) {
        $assessmentid = block_evalcomix_get_assessmentid(array('courseid' => $courseid, 'module' => $module, 'cmid' => $cmid,
        'studentid' => $studentid, 'assessorid' => $assessorid, 'mode' => $mode, 'lms' => $lms));

        if ($mode == 'self') {
            $assessorid = $memberid;
        }

        $newassessmentid = block_evalcomix_get_assessmentid(array('courseid' => $courseid, 'module' => $module,
        'cmid' => $cmid, 'studentid' => $memberid, 'assessorid' => $assessorid, 'mode' => $mode, 'lms' => $lms));
        $object = new stdClass();
        $object->oldid = $assessmentid;
        $object->newid = $newassessmentid;
        $assessments = array($object);

        $now = time();
        if ($duplicateassessment = $DB->get_record('block_evalcomix_assessments', array('taskid' => $taskid,
                'assessorid' => $assessorid, 'studentid' => $memberid))) {
            if ($assessment->grade != $duplicateassessment->grade) {
                if ($DB->update_record('block_evalcomix_assessments', array('id' => $duplicateassessment->id,
                        'taskid' => $duplicateassessment->taskid, 'assessorid' => $assessorid,
                        'studentid' => $duplicateassessment->studentid,
                        'grade' => $assessment->grade, 'timemodified' => $now))) {
                    block_evalcomix_webservice_client::delete_ws_assessment($duplicateassessment);
                    block_evalcomix_webservice_client::duplicate_course($assessments, $tools);
                    $params = array('cmid' => $task->instanceid, 'userid' => $memberid, 'courseid' => $courseid);
                    $finalgrade = block_evalcomix_grades::get_finalgrade_user_task($params);
                    if ($finalgrade !== null) {
                        if ($grade = $DB->get_record('block_evalcomix_grades', array('userid' => $memberid, 'cmid' => $cmid,
                                'courseid' => $courseid))) {
                            $DB->update_record('block_evalcomix_grades', array('id' => $grade->id, 'userid' => $grade->userid,
                                'cmid' => $grade->cmid, 'finalgrade' => $finalgrade, 'courseid' => $grade->courseid));
                        } else {
                            $DB->insert_record('block_evalcomix_grades', array('userid' => $memberid,
                                'cmid' => $cmid, 'finalgrade' => $finalgrade, 'courseid' => $courseid));
                        }
                    }
                    if ($mode == 'teacher') {
                        require_once($CFG->dirroot . '/blocks/evalcomix/competency/reportlib.php');
                        block_evalcomix_insert_teacher_pending(array('task' => $task, 'assessmentid' => $duplicateassessment->id,
                            'mode' => $mode, 'cmid' => $cmid, 'courseid' => $courseid));
                    }
                    $event = \block_evalcomix\event\student_assessed::create(array('objectid' => $cmid,
                    'courseid' => $courseid, 'context' => $context, 'userid' => $assessorid, 'relateduserid' => $studentid));
                    $event->trigger();
                }
            }
        } else {
            $idassessment = block_evalcomix_get_assessmentid(array('courseid' => $courseid, 'module' => $module, 'cmid' => $cmid,
                'studentid' => $memberid, 'assessorid' => $assessorid, 'mode' => $mode, 'lms' => $lms));
            if ($newassessmentid = $DB->insert_record('block_evalcomix_assessments', array('taskid' => $taskid,
                    'assessorid' => $assessorid, 'studentid' => $memberid, 'grade' => $assessment->grade,
                    'timemodified' => $now, 'idassessment' => $idassessment, 'modeid' => $modeobject->id))) {
                block_evalcomix_webservice_client::duplicate_course($assessments, $tools);
                $params = array('cmid' => $task->instanceid, 'userid' => $memberid, 'courseid' => $courseid);
                $finalgrade = block_evalcomix_grades::get_finalgrade_user_task($params);
                if ($finalgrade !== null) {
                    if ($grade = $DB->get_record('block_evalcomix_grades', array('userid' => $memberid, 'cmid' => $cmid,
                            'courseid' => $courseid))) {
                        $DB->update_record('block_evalcomix_grades', array('id' => $grade->id, 'userid' => $memberid,
                        'cmid' => $cmid, 'finalgrade' => $finalgrade, 'courseid' => $grade->courseid));
                    } else {
                        $DB->insert_record('block_evalcomix_grades', array('userid' => $memberid, 'cmid' => $cmid,
                        'finalgrade' => $finalgrade, 'courseid' => $courseid));
                    }
                    $event = \block_evalcomix\event\student_assessed::create(array('objectid' => $cmid,
                    'courseid' => $courseid, 'context' => $context, 'userid' => $assessorid, 'relateduserid' => $studentid));
                    $event->trigger();
                }
                require_once($CFG->dirroot . '/blocks/evalcomix/competency/reportlib.php');
                block_evalcomix_insert_teacher_pending(array('task' => $task, 'assessmentid' => $newassessmentid, 'mode' => $mode,
                    'cmid' => $cmid, 'courseid' => $courseid));
            }
        }
    } else {
        if ($mode == 'self') {
            $assessorid = $memberid;
        }
        if ($duplicateassessment = $DB->get_record('block_evalcomix_assessments', array('taskid' => $taskid,
                'assessorid' => $assessorid, 'studentid' => $memberid))) {
            block_evalcomix_assessments::delete_assessment(array('where' => array('id' => $duplicateassessment->id),
                    'courseid' => $courseid, 'cmid' => $task->instanceid));
        }
    }
    $showdetails = true;
    $params = array('cmid' => $task->instanceid, 'userid' => $memberid, 'courseid' => $courseid);
    $finalgrade = block_evalcomix_grades::get_finalgrade_user_task($params);
    // Only show the grade of users or all grades if the USER is a teacher or admin.
    if ((has_capability('moodle/grade:viewhidden', $context, $USER->id)
        || $memberid == $USER->id) && isset($finalgrade)) {
        if ($finalgrade != -1) {
            echo format_float($finalgrade, 2);
        } else {
            echo '-';
        }
    } else { // There is not grade.
        if ($configured) {
            echo '-';
        } else {
            echo '<span class="text-danger font-italic">'.get_string('noconfigured', 'block_evalcomix').'</span>';
        }
        $showdetails = false;
    }

    if ($configured) {
        $details = '';
        if ($showdetails) {
            $details = '<input type="image" value="'.get_string('details', 'block_evalcomix').'"
            class="block_evalcomix_w_16" title='.
            get_string('details', 'block_evalcomix').' src="../images/lupa.png"
            onclick="javascript:urlDetalles(\''. $CFG->wwwroot.
            '/blocks/evalcomix/assessment/details.php?cid=' . $context->id . '&itemid=' .
            $task->id . '&userid=' . $memberid . '&popup=1\');"/>';
        }

        if ($mode == 'teacher') {
            echo $details;
        }
    }
}
