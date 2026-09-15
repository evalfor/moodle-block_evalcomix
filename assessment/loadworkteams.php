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
 * Process actions related to work teams
 *
 * @package       block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author       Daniel Cabeza Sánchez <info@ansaner.net>
 */

require_once('../../../config.php');

$courseid = required_param('id', PARAM_INT);
$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
require_course_login($course);

$studentid = required_param('stu', PARAM_INT);

$cmid = required_param('cma', PARAM_INT);
$student = $DB->get_record('user', ['id' => $studentid], '*', MUST_EXIST);
$cm = $DB->get_record('course_modules', ['id' => $cmid], '*', MUST_EXIST);
$teamraw = required_param('team', PARAM_TEXT);
$team = explode(',', $teamraw);

require_once($CFG->dirroot . '/blocks/evalcomix/locallib.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_grades.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_assessments.php');

$context = context_course::instance($courseid);

$reportevalcomix = new block_evalcomix_grade_report($courseid, null, $context);
try {
    $reportevalcomix->process_data(['stu' => $studentid, 'cma' => $cmid]);
} catch (Exception $e) {
    // Processed on a previous call.
    echo '';
}

$transaction = $DB->start_delegated_transaction();
try {
    $lms = BLOCK_EVALCOMIX_MOODLE_NAME;
    $module = block_evalcomix_tasks::get_type_task($cmid);
    $assessorid = $USER->id;
    $mode = block_evalcomix_grade_report::get_type_evaluation($studentid, $courseid);
    $event = \block_evalcomix\event\student_assessed::create(['objectid' => $cmid,
            'courseid' => $courseid, 'context' => $context, 'userid' => $assessorid, 'relateduserid' => $studentid]);
    $event->trigger();

    $task = $DB->get_record('block_evalcomix_tasks', ['instanceid' => $cmid], '*', MUST_EXIST);

    $taskid = $task->id;
    $configured = $reportevalcomix->configured_activity($cmid);
    $now = time();

    $wsduplicate = false;
    $wsdelete = false;
    $assessments = [];
    $deletableassessments = [];
    $tools = [];
    if ($modeobject = $DB->get_record('block_evalcomix_modes', ['taskid' => $taskid, 'modality' => $mode])) {
        if ($tool = $DB->get_record('block_evalcomix_tools', ['id' => $modeobject->toolid])) {
            $object = new stdClass();
            $object->oldid = $tool->idtool;
            $object->newid = $tool->idtool;
            $tools[] = $object;
        }
    }

    // Si se ha guardado una evaluación (nueva o modificada).
    if (
        $assessment = $DB->get_record('block_evalcomix_assessments', ['taskid' => $taskid, 'assessorid' => $assessorid,
            'studentid' => $studentid])
    ) {
        $assessmentid = block_evalcomix_get_assessmentid(['courseid' => $courseid, 'module' => $module, 'cmid' => $cmid,
        'studentid' => $studentid, 'assessorid' => $assessorid, 'mode' => $mode, 'lms' => $lms]);

        foreach ($team as $memberid) {
            if ($mode == 'self') {
                $assessorid = $memberid;
            }
            $newassessmentid = block_evalcomix_get_assessmentid(['courseid' => $courseid, 'module' => $module,
            'cmid' => $cmid, 'studentid' => $memberid, 'assessorid' => $assessorid, 'mode' => $mode, 'lms' => $lms]);
            $object = new stdClass();
            $object->oldid = $assessmentid;
            $object->newid = $newassessmentid;

            if (
                $duplicateassessment = $DB->get_record('block_evalcomix_assessments', ['taskid' => $taskid,
                    'assessorid' => $assessorid, 'studentid' => $memberid])
            ) {
                if ($assessment->grade != $duplicateassessment->grade) {
                    if (
                        $DB->update_record('block_evalcomix_assessments', ['id' => $duplicateassessment->id,
                            'taskid' => $duplicateassessment->taskid, 'assessorid' => $assessorid,
                            'studentid' => $duplicateassessment->studentid,
                            'grade' => $assessment->grade, 'timemodified' => $now])
                    ) {
                        $wsdelete = true;
                        $wsduplicate = true;
                        $deletableassessments[] = $duplicateassessment;
                        $assessments[] = $object;

                        $params = ['cmid' => $task->instanceid, 'userid' => $memberid, 'courseid' => $courseid];
                        $finalgrade = block_evalcomix_grades::get_finalgrade_user_task($params);
                        if ($finalgrade !== null) {
                            if (
                                $grade = $DB->get_record('block_evalcomix_grades', ['userid' => $memberid, 'cmid' => $cmid,
                                    'courseid' => $courseid])
                            ) {
                                $DB->update_record('block_evalcomix_grades', ['id' => $grade->id, 'userid' => $grade->userid,
                                    'cmid' => $grade->cmid, 'finalgrade' => $finalgrade, 'courseid' => $grade->courseid]);
                            } else {
                                $DB->insert_record('block_evalcomix_grades', ['userid' => $memberid,
                                    'cmid' => $cmid, 'finalgrade' => $finalgrade, 'courseid' => $courseid]);
                            }
                        }
                        if ($mode == 'teacher') {
                            require_once($CFG->dirroot . '/blocks/evalcomix/competency/reportlib.php');
                            block_evalcomix_insert_teacher_pending(['task' => $task, 'assessmentid' => $duplicateassessment->id,
                                'mode' => $mode, 'cmid' => $cmid, 'courseid' => $courseid]);
                        }
                        $event = \block_evalcomix\event\student_assessed::create(['objectid' => $cmid,
                        'courseid' => $courseid, 'context' => $context, 'userid' => $assessorid, 'relateduserid' => $studentid]);
                        $event->trigger();
                    }
                }
            } else {
                $idassessment = block_evalcomix_get_assessmentid(['courseid' => $courseid, 'module' => $module, 'cmid' => $cmid,
                    'studentid' => $memberid, 'assessorid' => $assessorid, 'mode' => $mode, 'lms' => $lms]);
                if (
                    $newassessmentid = $DB->insert_record('block_evalcomix_assessments', ['taskid' => $taskid,
                        'assessorid' => $assessorid, 'studentid' => $memberid, 'grade' => $assessment->grade,
                        'timemodified' => $now, 'idassessment' => $idassessment, 'modeid' => $modeobject->id])
                ) {
                    $wsduplicate = true;
                    $assessments[] = $object;
                    $params = ['cmid' => $task->instanceid, 'userid' => $memberid, 'courseid' => $courseid];
                    $finalgrade = block_evalcomix_grades::get_finalgrade_user_task($params);
                    if ($finalgrade !== null) {
                        if (
                            $grade = $DB->get_record('block_evalcomix_grades', ['userid' => $memberid, 'cmid' => $cmid,
                                'courseid' => $courseid])
                        ) {
                            $DB->update_record('block_evalcomix_grades', ['id' => $grade->id, 'userid' => $memberid,
                            'cmid' => $cmid, 'finalgrade' => $finalgrade, 'courseid' => $grade->courseid]);
                        } else {
                            $DB->insert_record('block_evalcomix_grades', ['userid' => $memberid, 'cmid' => $cmid,
                            'finalgrade' => $finalgrade, 'courseid' => $courseid]);
                        }
                        $event = \block_evalcomix\event\student_assessed::create(['objectid' => $cmid,
                        'courseid' => $courseid, 'context' => $context, 'userid' => $assessorid, 'relateduserid' => $studentid]);
                        $event->trigger();
                    }
                    require_once($CFG->dirroot . '/blocks/evalcomix/competency/reportlib.php');
                    block_evalcomix_insert_teacher_pending(['task' => $task, 'assessmentid' => $newassessmentid, 'mode' => $mode,
                        'cmid' => $cmid, 'courseid' => $courseid]);
                }
            }
        }
    } else {
        // Si tras ejecutar el process_data no se encuentra el $assessment significa que se ha borrado la evaluación.
        foreach ($team as $memberid) {
            if ($mode == 'self') {
                $assessorid = $memberid;
            }
            if (
                $duplicateassessment = $DB->get_record('block_evalcomix_assessments', ['taskid' => $taskid,
                    'assessorid' => $assessorid, 'studentid' => $memberid])
            ) {
                $wsdelete = true;
                $deletableassessments[] = $duplicateassessment;
                block_evalcomix_assessments::delete_assessment(['where' => ['id' => $duplicateassessment->id],
                        'courseid' => $courseid, 'cmid' => $task->instanceid, 'ws' => false]);
            }
        }
    }
    $transaction->allow_commit();
} catch (Exception $e) {
    $transaction->rollback($e);
    throw $e;
}

if ($wsdelete) {
    block_evalcomix_webservice_client::delete_ws_assessments($deletableassessments);
}
if ($wsduplicate) {
    block_evalcomix_webservice_client::duplicate_course($assessments, $tools);
}

echo $reportevalcomix->create_grade_table();
