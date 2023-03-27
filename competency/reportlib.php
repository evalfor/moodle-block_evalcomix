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

define('BLOCK_EVALCOMIX_COMPETENCY', 0);
define('BLOCK_EVALCOMIX_OUTCOME', 1);
define('BLOCK_EVALCOMIX_DR_REQUEST', 200);
define('BLOCK_EVALCOMIX_DR_PENDING', 5000);

function block_evalcomix_get_development_datas($courseid, $groupid = 0, $studentid = 0, $grades = true) {
    global $DB;
    $result = new stdClass();
    $result->competency = new stdClass();
    $result->competency->xdatas = array();
    $result->competency->ydatas = array();
    $result->competency->title = array();
    $result->outcome = new stdClass();
    $result->outcome->xdatas = array();
    $result->outcome->ydatas = array();
    $result->outcome->title = array();

    if ($competencies = $DB->get_records('block_evalcomix_competencies', array('courseid' => $courseid))) {
        if ($grades) {
            $datas = block_evalcomix_get_competency_grade($courseid, array_keys($competencies), $groupid, $studentid);
        }
        foreach ($competencies as $competency) {
            $compid = $competency->id;
            $idnumber = $competency->idnumber;
            if ($competency->outcome) {
                $result->outcome->ydatas[$idnumber] = $idnumber;
                $result->outcome->xdatas[$idnumber] = 0;
                $result->outcome->gradebytask[$idnumber] = array();
                if ($grades) {
                    if (isset($datas[$compid]) && isset($datas[$compid]->grade)) {
                        $result->outcome->xdatas[$idnumber] = $datas[$compid]->grade;
                    }
                    if (isset($datas[$compid]) && isset($datas[$compid]->gradebytask)) {
                        $result->outcome->gradebytask[$idnumber] = $datas[$compid]->gradebytask;
                    }
                }
            } else {
                $result->competency->ydatas[$idnumber] = $idnumber;
                $result->competency->xdatas[$idnumber] = 0;
                $result->competency->gradebytask[$idnumber] = array();
                if ($grades) {
                    if (isset($datas[$compid]) && isset($datas[$compid]->grade)) {
                        $result->competency->xdatas[$idnumber] = $datas[$compid]->grade;
                    }
                    if (isset($datas[$compid]) && isset($datas[$compid]->gradebytask)) {
                        $result->competency->gradebytask[$idnumber] = $datas[$compid]->gradebytask;
                    }
                }
            }
        }
    }

    return $result;
}

function block_evalcomix_get_competency_grade($courseid, $competencyid = array(), $groupid = 0, $studentid = 0) {
    global $CFG, $DB;
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
    $result = array();
    $gradebytask = array();

    $datas = block_evalcomix_get_skill_development_data(array('courseid' => $courseid,
        'competencyid' => $competencyid, 'groupid' => $groupid, 'studentid' => $studentid));

    foreach ($datas as $compid => $item1) {
        foreach ($item1 as $subdimensionid => $item2) {
            foreach ($item2 as $studentid => $item3) {
                foreach ($item3 as $cmid => $item4) {
                    foreach ($item4 as $grade) {
                        $gradebytask[$compid][$cmid][] = (int)$grade;
                    }
                }
            }
        }
        $result[$compid] = new stdClass();
        $result[$compid]->grade = 0;
        $result[$compid]->gradebytask = array();
    }

    if (!empty($gradebytask)) {
        foreach ($gradebytask as $compid => $item1) {
            $rawgrades = array();
            foreach ($item1 as $cmid => $grades) {
                if (!empty($grades)) {
                    $countgrades = count($grades);
                    $taskgrade = 0;
                    foreach ($grades as $grade) {
                        $taskgrade += $grade;
                    }
                    $rawgrades[$cmid] = round(($taskgrade / $countgrades), 2);
                }
            }
            $count = count($rawgrades);
            if ($count > 0) {
                $result[$compid]->grade = round((array_sum($rawgrades) / $count), 2);
                foreach ($rawgrades as $cmid => $value) {
                    $result[$compid]->gradebytask[$cmid] = $value;
                }
            }
        }
    }

    return $result;
}

function block_evalcomix_get_skill_development_data($params = array()) {
    global $CFG, $DB;
    $courseid = $params['courseid'];
    $competencyid = $params['competencyid'];
    $studentid = (isset($params['studentid'])) ? (int)$params['studentid'] : 0;
    $groupid = (isset($params['groupid'])) ? (int)$params['groupid'] : 0;
    $datas = array();
    $result = array();
    $students = array();

    $sql = '
        SELECT bes.*
        FROM {block_evalcomix_subdimension} bes
        WHERE bes.courseid = :courseid
            AND competencyid IN ('.implode(',', $competencyid).')
            AND bes.toolid IN (
                SELECT bem.toolid
                FROM {block_evalcomix_modes} bem
                )
    ';

    $studentbyassessment = array();
    if ($student = $DB->get_record('user', array('id' => $studentid, 'deleted' => 0))) {
        $students[$studentid] = $student;
    } else {
        $students = block_evalcomix_get_members_course($courseid, $groupid);
    }
    $xml = '';
    $assbytool = array();
    $toolhash = array();
    if (!empty($students) && $subdimensions = $DB->get_records_sql($sql, array('courseid' => $courseid))) {
        foreach ($subdimensions as $subdimension) {
            $subdimensionid = $subdimension->subdimensionid;
            $subdimensionhash[$subdimensionid][] = $subdimension->id;
            $subtoolid = $subdimension->toolid;
            $toolhash[$subdimensionid] = $subtoolid;
            if (!isset($assbytool[$subtoolid])) {
                $assbytool[$subtoolid] = block_evalcomix_get_student_assessments_by_tool(array('courseid' => $courseid,
                    'toolid' => $subdimension->toolid, 'students' => $students));
            }
            if (!empty($assbytool[$subtoolid])) {
                $allassessments = array();

                foreach ($assbytool[$subtoolid] as $assessmentid => $value) {
                    $allassessments[$assessmentid] = $assessmentid;
                }
                if (!empty($allassessments)) {
                    $sql = "
                    SELECT *
                    FROM {block_evalcomix_dr_grade}
                    WHERE idsubdimension = '".$subdimensionid."' AND idassessment IN ('" .
                        implode("','", $allassessments) . "')";
                    $result[$subdimensionid] = $DB->get_records_sql($sql, array());
                }
            }
        }

        foreach ($result as $subdimensionid => $subdimensiongrades) {
            foreach ($subdimensiongrades as $assessment) {
                $assessmentid = $assessment->idassessment;
                $grade = $assessment->grade;
                if ($toolhash[$subdimensionid]) {
                    $toolid = $toolhash[$subdimensionid];
                    if (isset($assbytool[$toolid][$assessmentid])) {
                        $studentid = $assbytool[$toolid][$assessmentid]->studentid;
                        $cmid = $assbytool[$toolid][$assessmentid]->cmid;
                        foreach ($subdimensionhash[$subdimensionid] as $subid) {
                            $compid = $subdimensions[$subid]->competencyid;
                            $datas[$compid][$subdimensionid][$studentid][$cmid][$assessmentid] = $grade;
                        }
                    }
                }
            }
        }
    }

    return $datas;
}

/**
 * Get datas from ws and save them in db
 */
function block_evalcomix_get_skill_development_data_ws($params = array()) {
    global $CFG, $DB;
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');

    $courses = get_courses();
    $pindex = 0;
    foreach ($courses as $course) {
        echo "
        Processing course: $course->fullname PINDEX:$pindex
        ";
        echo '-------------------------------------
';
        $courseid = $course->id;
        if ($course->id == 1) {
            echo "It is not processed

";
            continue;
        }
        if ($course->visible == 0) {
            echo "It is not processed because it is not visible

";
            continue;
        }

        $contextcourse = context_course::instance($courseid);
        if (!$DB->get_record('block_instances', array('parentcontextid' => $contextcourse->id, 'blockname' => 'evalcomix'))) {
            echo "It is not processed because the course does not have the EvalCOMIX block installed

";
            continue;
        }

        if (!$students = block_evalcomix_get_members_course($courseid)) {
            echo "It is not processed because it does not have students

";
            continue;
        }

        if (!$competencies = $DB->get_records('block_evalcomix_competencies', array('courseid' => $courseid))) {
            echo "It is not processed because there are no competencies or outcomes

";
            continue;
        }

        $cm = $DB->get_records('course_modules', array('course' => $courseid, 'deletioninprogress' => '0'));
        foreach ($cm as $value) {
            $cmids[] = $value->id;
        }
        $sqltask = "
        SELECT *
        FROM {block_evalcomix_tasks}
        WHERE instanceid IN (" . implode(',', $cmids) . ")";
        if (!$tasks = $DB->get_records_sql($sqltask)) {
            echo "It is not processed because there are no activities configured

";
            continue;
        }
        $taskids = array_keys($tasks);

        $sqlmodes = "
        SELECT m.*, mt.timeavailable, mt.timedue
        FROM {block_evalcomix_modes} m LEFT JOIN {block_evalcomix_modes_time} mt
        ON m.id = mt.modeid
        WHERE m.taskid IN (" . implode(',', $taskids) . ")";

        echo "Managing pending requests

";
        $now = time();
        if ($modes = $DB->get_records_sql($sqlmodes)) {
            $activities = array();
            foreach ($modes as $mode) {
                $taskid = $mode->taskid;
                if (!isset($activities[$taskid])) {
                    $activities[$taskid] = new stdClass();
                    $activities[$taskid]->open = false;
                    $activities[$taskid]->toolid = array();
                }
                $toolid = $mode->toolid;
                $activities[$taskid]->toolid[$toolid] = $toolid;
                if (!empty($mode->timedue)) {
                    if ($now < $mode->timedue) {
                        $activities[$taskid]->open = true;
                    }
                }
            }

            foreach ($activities as $taskid => $activity) {
                if ($pindex > BLOCK_EVALCOMIX_DR_PENDING) {
                    break;
                }
                $cmid = $tasks[$taskid]->instanceid;
                $pending = $DB->get_records('block_evalcomix_dr_pending', array('cmid' => $cmid));
                $grades = $DB->get_records('block_evalcomix_dr_grade', array('cmid' => $cmid));
                $assbytool = array();
                $pendingdatas = array();
                if ($activity->open === false) {
                    if (!$pending && !$grades) {
                        $compids = array_keys($competencies);
                        $sql = '
                                SELECT bes.*
                                FROM {block_evalcomix_subdimension} bes
                                WHERE bes.courseid = :courseid
                                    AND bes.competencyid IN ('.implode(',', $compids).')
                                    AND bes.toolid IN ('.implode(',', $activity->toolid).')
                        ';

                        if ($subdimensions = $DB->get_records_sql($sql, array('courseid' => $courseid))) {
                            $tools = array();
                            foreach ($subdimensions as $subdimension) {
                                echo "Processing the subdimension: $subdimension->subdimensionid  of the tool $subdimension->toolid
";
                                $subtoolid = $subdimension->toolid;
                                $subdimensionid = $subdimension->subdimensionid;
                                if (!isset($pendingdatas[$subdimensionid])) {
                                    if (!isset($assbytool[$subtoolid])) {
                                        echo "Calculando assessments del instrumento $subtoolid en cmid $cmid
";
                                        $assbytool[$subtoolid] = block_evalcomix_get_student_assessments_by_tool(
                                            array('courseid' => $courseid, 'toolid' => $subtoolid, 'students' => $students,
                                            'cmid' => $cmid));
                                    }

                                    if (!empty($assbytool[$subtoolid]) && !isset($pendingdatas[$subdimensionid])) {
                                        $allassessments = array();

                                        foreach ($assbytool[$subtoolid] as $assessmentid => $value) {
                                            $allassessments[$assessmentid] = $value;
                                        }
                                        if (!empty($allassessments)) {
                                            $pendingdatas[$subdimensionid] = $allassessments;
                                        }
                                    }
                                }
                            }

                            if (!empty($pendingdatas)) {
                                $i = 0;
                                foreach ($pendingdatas as $subdimensionid => $assessments) {
                                    foreach ($assessments as $assessmentid => $assessment) {
                                        $params = array('courseid' => $courseid,
                                            'cmid' => $assessment->cmid, 'idsubdimension' => $subdimensionid,
                                            'idassessment' => $assessmentid, 'modeid' => $assessment->modeid);
                                        if (!$DB->get_record('block_evalcomix_dr_pending', $params)) {
                                            $DB->insert_record('block_evalcomix_dr_pending', $params);
                                            $i++;
                                            $pindex++;
                                            unset($pendingdatas[$subdimensionid][$assessmentid]);
                                        }
                                    }
                                }
                                echo "$i rows inserted in PENDING
";
                            }
                        }
                    }
                } else {
                    if ($pending) {
                        $DB->delete_records('block_evalcomix_dr_pending', array('cmid' => $cmid));
                    }
                    if ($grades) {
                        $DB->delete_records('block_evalcomix_dr_grade', array('cmid' => $cmid));
                    }
                }
            }
        }
    }

    echo "
Managing grades
";

    $sqlgrade = "SELECT *
    FROM {block_evalcomix_dr_pending}
    LIMIT " . BLOCK_EVALCOMIX_DR_REQUEST;
    if ($requests = $DB->get_records_sql($sqlgrade)) {
        $pendingdatas = array();
        foreach ($requests as $request) {
            $idsubdimension = $request->idsubdimension;
            $idassessment = $request->idassessment;
            if (!isset($pendingdatas[$idsubdimension][$idassessment])) {
                $cmidobj = new stdClass();
                $cmidobj->cmid = $request->cmid;
                $cmidobj->modeid = $request->modeid;
                $pendingdatas[$idsubdimension][$idassessment] = $cmidobj;
            }
        }
        if ($datas = block_evalcomix_webservice_client::get_grade_subdimension($pendingdatas)) {
            $i = 0;
            $j = 0;
            foreach ($datas as $data) {
                if (!empty($data['cmid']) && !empty($data['idsubdimension'])
                        && !empty($data['idassessment']) && !empty($data['modeid'])) {
                    if (!$grade = $DB->get_record('block_evalcomix_dr_grade', array('idassessment' => $data['idassessment'],
                            'idsubdimension' => $data['idsubdimension']))) {
                        $DB->insert_record('block_evalcomix_dr_grade', $data);
                        $i++;
                    } else {
                        if ((int)$grade->grade !== (int)$data['grade']) {
                            $grade->grade = $data['grade'];
                            $DB->update_record('block_evalcomix_dr_grade', $grade);
                            $j++;
                        }
                    }
                    $DB->delete_records('block_evalcomix_dr_pending', array('idassessment' => $data['idassessment'],
                        'idsubdimension' => $data['idsubdimension']));
                }
            }
            echo "$i rows inserted and $j rows updated

";
        }
    }
}

function block_evalcomix_get_student_assessments_by_tool($params = array()) {
    global $CFG, $DB;
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');
    $result = array();

    $courseid = (isset($params['courseid'])) ? $params['courseid'] : 0;
    $toolid = (isset($params['toolid'])) ? $params['toolid'] : 0;
    $students = (isset($params['students'])) ? $params['students'] : array();
    $cmid = (isset($params['cmid'])) ? $params['cmid'] : 0;
    $modality = (isset($params['modality'])) ? $params['modality'] : '';

    $cmids = array();
    if (!empty($cmid)) {
        $cmids[] = $cmid;
    } else {
        $cm = $DB->get_records('course_modules', array('course' => $courseid, 'deletioninprogress' => '0'));
        foreach ($cm as $value) {
            $cmids[] = $value->id;
        }
    }

    $sqltask = "
        SELECT *
        FROM {block_evalcomix_tasks}
        WHERE instanceid IN (" . implode(',', $cmids) . ")";
    if ($tasks = $DB->get_records_sql($sqltask)) {
        $studentids = array_keys($students);
        $tasksids = array_keys($tasks);

        $sql = '
        SELECT s.*
        FROM {block_evalcomix_assessments} s
        WHERE s.studentid IN ('.implode(',', $studentids).') AND s.taskid IN ('.implode(',', $tasksids).')
        ORDER BY s.id ASC
        ';

        if ($assessments = $DB->get_records_sql($sql)) {
            $sqlmode = '
            SELECT *
            FROM {block_evalcomix_modes}
            WHERE toolid = :toolid AND taskid IN ('.implode(',', $tasksids).')';
            $modeparams = array('toolid' => $toolid);
            if (!empty($modality)) {
                $modeparams['modality'] = $modality;
            }
            if ($modes = $DB->get_records_sql($sqlmode, $modeparams)) {
                $modestr = array();
                foreach ($modes as $mode) {
                    $modality = $mode->modality;
                    $modesstr[$modality] = $mode->id;
                }
                $coursecontext = context_course::instance($courseid);
                foreach ($assessments as $assessment) {
                    $studentid = $assessment->studentid;
                    $assessorid = $assessment->assessorid;
                    $modestr = 'peer';
                    if ($studentid == $assessorid) {
                        $modestr = 'self';
                    } else if (has_capability('moodle/grade:viewhidden', $coursecontext, $assessorid)) {
                        $modestr = 'teacher';
                    }

                    if (!isset($modesstr[$modestr])) {
                        continue;
                    }

                    $taskid = $assessment->taskid;
                    if (isset($tasks[$taskid])) {
                        $instanceid = $tasks[$taskid]->instanceid;
                        $assessmentid = block_evalcomix_update_assessmentid($assessment);
                        $result[$assessmentid] = new stdClass();
                        $result[$assessmentid]->studentid = $studentid;
                        $result[$assessmentid]->cmid = $instanceid;
                        $result[$assessmentid]->modeid = $modesstr[$modestr];
                    }
                }
            }
        }
    }

    return $result;
}

function block_evalcomix_get_remaining_download_time($courseid) {
    global $DB;

    $sql = "SELECT *
    FROM {block_evalcomix_dr_pending} p
    ORDER BY p.id ASC";
    if (!$pending = $DB->get_records_sql($sql)) {
        return 0;
    }

    $begin = false;
    $rowstofinish = 0;
    $i = 0;
    foreach ($pending as $row) {
        $i++;
        if ($row->courseid == $courseid) {
            $rowstofinish = $i;
            if ($begin === false) {
                $begin = true;
            }
        }
    }

    if (!$begin) {
        return 0;
    }
    $task = \core\task\manager::get_scheduled_task('\block_evalcomix\task\get_skill_develpment_data');

    $disabled = $task->get_disabled();
    if ($disabled == true) {
        return 'disabled';
    }

    $lastruntime = $task->get_last_run_time();
    $nextruntime = $task->get_next_run_time();
    $now = time();
    if ($now > ($nextruntime + 10)) {
        return 'indeterminate';
    }
    if (is_numeric($lastruntime) && is_numeric($nextruntime)) {
        $timenextruntime = $nextruntime - $now;
        $iterations = ceil($rowstofinish / BLOCK_EVALCOMIX_DR_REQUEST);
        $frequency = $nextruntime - $lastruntime;
        $time = $frequency * $iterations + $timenextruntime;

        return format_time($time);
    }

    return 'indeterminate';
}

function block_evalcomix_insert_teacher_pending($params = array()) {
    global $DB;
    $result = false;

    $task = isset($params['task']) ? $params['task'] : null;
    $assessmentid = isset($params['assessmentid']) ? $params['assessmentid'] : 0;
    $mode = isset($params['mode']) ? $params['mode'] : '';
    $cmid = isset($params['cmid']) ? $params['cmid'] : 0;
    $courseid = isset($params['courseid']) ? $params['courseid'] : 0;
    if ($mode == 'teacher') {
        $sqlmodes = "
            SELECT m.*, mt.timeavailable, mt.timedue
            FROM {block_evalcomix_modes} m LEFT JOIN {block_evalcomix_modes_time} mt
            ON m.id = mt.modeid
            WHERE m.taskid = :taskid";
        if ($modes = $DB->get_records_sql($sqlmodes, array('taskid' => $task->id))) {
            $open = false;
            $teachermode = false;
            $activities = array();
            $now = time();
            $modeid = 0;
            $aeorei = true;
            foreach ($modes as $mode) {
                if (!empty($mode->timedue)) {
                    if ($now < $mode->timedue) {
                        $open = true;
                    }
                }
                if ($mode->modality == 'teacher') {
                    $teachermode = true;
                    $modeid = $mode->id;
                } else {
                    $aeorei = false;
                }
            }
            if (!$open && $teachermode) {
                $pending = $DB->get_records('block_evalcomix_dr_pending', array('cmid' => $cmid));
                $grades = $DB->get_records('block_evalcomix_dr_grade', array('cmid' => $cmid));
                if ($pending || $grades || $aeorei) {
                    if ($assessment = $DB->get_record('block_evalcomix_assessments', array('id' => $assessmentid))) {
                        $idass = $assessment->idassessment;
                        if ($subdimensions = $DB->get_records('block_evalcomix_subdimension', array('toolid' => $mode->toolid))) {
                            $idsubs = array();
                            foreach ($subdimensions as $subdimension) {
                                $idsub = $subdimension->subdimensionid;
                                $idsubs[$idsub] = $idsub;
                            }
                            if ($pending = $DB->get_records('block_evalcomix_dr_pending', array('cmid' => $cmid,
                                    'idassessment' => $idass))) {
                                $DB->delete_records('block_evalcomix_dr_pending', array('idassessment' => $idass));
                            }

                            foreach ($idsubs as $subid) {
                                if (!$DB->get_record('block_evalcomix_dr_pending', array('idsubdimension' => $subid,
                                        'idassessment' => $idass))) {
                                    if ($DB->insert_record('block_evalcomix_dr_pending', array('courseid' => $courseid,
                                        'cmid' => $cmid, 'idsubdimension' => $subid,
                                        'idassessment' => $idass, 'modeid' => $modeid))) {
                                        $result = true;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $result;
}
