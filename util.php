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
 * @author     Daniel Cabeza Sánchez <info@ansaner.net>
 */

class block_evalcomix_export_assessment {
    public static function export($course) {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/blocks/evalcomix/locallib.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_grades.php');
        $context = context_course::instance($course->id);

        $students = block_evalcomix_get_members_course($course->id);
        $reportevalcomix = new block_evalcomix_grade_report($course->id, null, $context);
        $reportevalcomix->get_headers();
        $activities = $reportevalcomix->get_activities();
        $hashtasks = array();
        $taskids = array();
        $hashmodes = array();
        $hashassessments = array();
        $setofgrades = array();
        $commented = array();
        $now = time();
        $headertotalself = array();
        $headertotalpeer = array();

        $tasksql = '
        SELECT t.*
        FROM {block_evalcomix_tasks} t
        WHERE t.instanceid IN ('.implode(',', $activities['id']).')';
        if ($tasks = $DB->get_records_sql($tasksql, array())) {
            foreach ($tasks as $task) {
                $cmid = $task->instanceid;
                $taskid = $task->id;
                $hashtasks[$cmid] = $task;
            }
            $taskids = array_keys($tasks);

            $modesql = 'SELECT m.*, t.instanceid as cmid
            FROM {block_evalcomix_modes} m, {block_evalcomix_tasks} t
            WHERE m.taskid = t.id AND (m.modality = \'peer\' OR m.modality = \'self\')
            AND t.instanceid IN ('.implode(',', $activities['id']).')';
            if ($modes = $DB->get_records_sql($modesql, array())) {
                foreach ($modes as $mode) {
                    $cmid = $mode->cmid;
                    $modality = $mode->modality;
                    $hashmodes[$cmid][$modality] = $mode;
                }
            }

            $assessmentsql = '
            SELECT a.*
            FROM {block_evalcomix_assessments} a
            WHERE a.taskid IN ('.implode(',', $taskids).')';
            if ($students) {
                $studentids = array_keys($students);
                $assessmentsql .= ' AND a.assessorid IN ('.implode(',', $studentids).')';
            }
            if ($assessments = $DB->get_records_sql($assessmentsql, array())) {
                foreach ($assessments as $assessment) {
                    $studentid = $assessment->assessorid;
                    $assessmentid = $assessment->id;
                    $tid = $assessment->taskid;
                    $hashassessments[$studentid][$tid][$assessmentid] = $assessment;
                }
                $commented = block_evalcomix_webservice_client::get_commented_assessments($course->id, $assessments);
            }
        }

        $title = strtoupper(get_string('assessmentreport', 'block_evalcomix'));
        $header = array(
            array($title),
            array(''),
            array(get_string('course').':', $course->fullname),
            array(''),
            array(''),
            array(get_string('scale').':'),
            array('-', get_string('noconfigured', 'block_evalcomix')),
            array(0, get_string('unrealized', 'block_evalcomix')),
            array(1, get_string('doneoutofrange', 'block_evalcomix')),
            array(2, get_string('doneoutofrangecomments', 'block_evalcomix')),
            array(3, get_string('donewithinrange', 'block_evalcomix')),
            array(4, get_string('donewithinrangecomments', 'block_evalcomix')),
            array(''),
            array(''),
        );

        $rows = array();
        $rows[0][0] = get_string('name');
        $rows[1][0] = '';
        if ($activities) {
            $index = 1;
            $ae = 1;
            $ei = 1;
            foreach ($activities['name'] as $key => $activityname) {
                $cmid = $activities['id'][$key];
                if (isset($hashmodes[$cmid])) {
                    $rows[0][$index] = $activityname.'[[-double-]]';
                    $rows[1][$index] = get_string('AE', 'block_evalcomix');
                    $index++;
                    $rows[0][$index] = '';
                    $rows[1][$index] = get_string('EI', 'block_evalcomix');
                    $index++;
                    if (isset($hashmodes[$cmid]['self'])) {
                        $headertotalself[] = get_string('AE', 'block_evalcomix') . $ae;
                        ++$ae;
                    }
                    if (isset($hashmodes[$cmid]['peer'])) {
                        $headertotalpeer[] = get_string('EI', 'block_evalcomix') . $ei;
                        ++$ei;
                    }
                }
            }
        }
        $rows[0][$index] = get_string('assessmentreporttotalAE', 'block_evalcomix') . ' ('.implode('+', $headertotalself). ')*(10/'.
            count($headertotalself) * 4 .')';
        $rows[1][$index] = '';
        $selfindex = $index;
        $index++;
        $rows[0][$index] = get_string('assessmentreporttotalEI', 'block_evalcomix') . ' ('.implode('+', $headertotalpeer). ')*(10/'.
            count($headertotalpeer) * 4 .')';;
        $rows[1][$index] = '';
        $peerindex = $index;

        if ($students && $activities) {
            foreach ($students as $student) {
                $studentid = $student->id;
                $studentname = fullname($student);
                $row = array();
                $row[] = $studentname;
                $totalself = array();
                $totalpeer = array();
                foreach ($activities['id'] as $cmid) {
                    if (isset($hashmodes[$cmid])) {
                        $gradeself = (isset($hashmodes[$cmid]['self'])) ? 0 : '-';
                        $gradepeeraverage = array();
                        if (isset($hashassessments[$studentid])) {
                            if (isset($hashtasks[$cmid])) {
                                $task = $hashtasks[$cmid];
                                $taskid = $task->id;
                                if (isset($hashassessments[$studentid][$taskid])) {
                                    foreach ($hashassessments[$studentid][$taskid] as $assessmentid => $assessment) {
                                        if (!isset($setofgrades[$assessmentid])) {
                                            $setofgrades[$assessmentid] = block_evalcomix_grades::get_main_set_of_grades($taskid,
                                            $assessment);
                                        }
                                        $grade = $assessment->grade;
                                        $outofrange = false;
                                        if ($hashtasks[$cmid]->grademethod == BLOCK_EVALCOMIX_GRADE_METHOD_WA_SMART) {
                                            if (!empty($setofgrades[$assessmentid]->mainsetofgrades)) {
                                                $mainsetofgrades = $setofgrades[$assessmentid]->mainsetofgrades;
                                                $mode = $setofgrades[$assessmentid]->mode;

                                                if ($mode == 'peer') {
                                                    $countmainsetofgrades = count($mainsetofgrades);
                                                    if ($assessment->assessorid == $assessment->studentid
                                                            && $countmainsetofgrades > BLOCK_EVALCOMIX_GRADE_METHOD_MIN_PEERS
                                                            && (block_evalcomix_grades::is_upper_grade($grade,
                                                            $mainsetofgrades, $task->threshold)
                                                            || block_evalcomix_grades::is_lower_grade($grade, $mainsetofgrades,
                                                            $task->threshold))) {
                                                        $outofrange = true;
                                                    } else if ($assessment->assessorid != $assessment->studentid
                                                            && block_evalcomix_grades::is_extreme_grade($grade, $mainsetofgrades)) {
                                                        $outofrange = true;
                                                    }
                                                } else {
                                                    if (block_evalcomix_grades::is_upper_grade($grade,
                                                            $mainsetofgrades, $task->threshold)
                                                            || block_evalcomix_grades::is_lower_grade($grade, $mainsetofgrades,
                                                            $task->threshold)) {
                                                        $outofrange = true;
                                                    }
                                                }
                                            }
                                        }

                                        if (!$outofrange) {
                                            if ($assessment->assessorid == $assessment->studentid) {
                                                $gradeself = (isset($commented[$assessmentid])
                                                    && $commented[$assessmentid] == 1) ? 4 : 3;
                                            } else {
                                                $gradepeeraverage[] = (isset($commented[$assessmentid])
                                                    && $commented[$assessmentid] == 1) ? 4 : 3;
                                            }
                                        } else {
                                            if ($assessment->assessorid == $assessment->studentid) {
                                                $gradeself = (isset($commented[$assessmentid])
                                                    && $commented[$assessmentid] == 1) ? 2 : 1;
                                            } else {
                                                $gradepeeraverage[] = (isset($commented[$assessmentid])
                                                    && $commented[$assessmentid] == 1) ? 2 : 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $gradepeer = (isset($hashmodes[$cmid]['peer'])) ? 0 : '-';
                        if (!empty($gradepeeraverage)) {
                            $gradepeer = array_sum($gradepeeraverage) / count($gradepeeraverage);
                            $gradepeer = round($gradepeer, 1);
                        }
                        if (is_numeric($gradepeer)) {
                            $totalpeer[] = $gradepeer;
                        }
                        if (is_numeric($gradeself)) {
                            $totalself[] = $gradeself;
                        }

                        $row[] = $gradeself;
                        $row[] = $gradepeer;
                    }
                }
                $counttotalself = count($totalself);
                if ($counttotalself > 0) {
                    $row[] = round((array_sum($totalself) * 10) / (4 * ($counttotalself)), 2);
                }
                $counttotalpeer = count($totalpeer);
                if ($counttotalpeer > 0) {
                    $row[] = round(array_sum($totalpeer) * 10 / (4 * ($counttotalpeer)), 2);
                }
                $rows[] = $row;
            }
        }
        $rows = array_merge($header, $rows);
        return generate_excel_download($course->fullname . "-EvalCOMIX-assessment-report", $rows);
    }


}

function block_evalcomix_export_competence($courseid) {
    global $CFG, $DB;
    require_once($CFG->libdir . '/csvlib.class.php');

    $datas = $DB->get_records('block_evalcomix_competencies', array('courseid' => $courseid));
    $types = $DB->get_records('block_evalcomix_comptype', array('courseid' => $courseid));
    foreach ($datas as $data) {
        $data->typename = '';
        $data->typedes = '';
        if (!empty($data->typeid)) {
            $typeid = $data->typeid;
            $data->typename = $types[$typeid]->shortname;
            $data->typedes = $types[$typeid]->description;
        }
    }

    $csv = new csv_export_writer('semicolon');
    $csv->set_filename('moodle-evalcomix-competencies');
    $csv->add_data(array("\xEF\xBB\xBF"));
    $csv->add_data(array('idnumber', 'shortname', 'description', 'outcome', 'timecreated', 'timemodified',
        'typename', 'typedescription'));
    foreach ($datas as $data) {
        $row = (array)$data;
        unset($row['id']);
        unset($row['courseid']);
        unset($row['typeid']);
        $row['description'] = core_text::trim_utf8_bom($row['description']);
        $csv->add_data($row);
    }

    $csv->download_file();
}

function block_evalcomix_export_development_report($params) {
    global $CFG, $DB;
    $course = (isset($params['course'])) ? $params['course'] : null;
    $groupid = (isset($params['groupid'])) ? $params['groupid'] : null;
    $studentid = (isset($params['studentid'])) ? $params['studentid'] : null;
    $courseid = $course->id;
    require_once($CFG->dirroot . '/blocks/evalcomix/competency/reportlib.php');
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
    require_once($CFG->dirroot . '/blocks/evalcomix/competency/reportlib.php');
    $datas = block_evalcomix_get_development_datas($courseid, $groupid, $studentid);
    $competencydatas = $datas->competency;
    $outcomedatas = $datas->outcome;
    $competencyxdatas = array_reverse($competencydatas->xdatas);
    $outcomexdatas = array_reverse($outcomedatas->xdatas);
    $ctitle = (isset($competencydatas->gradebytask)) ? $competencydatas->gradebytask : array();
    $otitle = (isset($outcomedatas->gradebytask)) ? $outcomedatas->gradebytask : array();
    $activities = block_evalcomix_tasks::get_moodle_course_tasks($courseid);

    $title = strtoupper(get_string('compreport', 'block_evalcomix'));
    $header = array(
            array($title),
            array(''),
            array(get_string('course').':', $course->fullname),
    );

    if ($group = $DB->get_record('groups', array('id' => $groupid))) {
        $header[][] = $group->name;
    }
    if ($student = $DB->get_record('user', array('id' => $studentid))) {
        $header[] = array(fullname($student));
    }

    $rows = array();

    $rows[0] = array('');
    $rows[1] = array(get_string('outcomes', 'block_evalcomix'));
    $rows[2] = array('');
    $rows[3] = array(get_string('name'));
    foreach ($activities as $activity) {
        $rows[3][] = $activity['nombre'];
    }
    $rows[3][] = 'Total';
    $i = 4;
    foreach ($outcomexdatas as $label => $grade) {
        $row = array();
        $row[] = $label;
        foreach ($activities as $cmid => $activity) {
            if (isset($otitle[$label][$cmid])) {
                $row[] = $otitle[$label][$cmid];
            } else {
                $row[] = '';
            }
        }
        $row[] = $grade;
        $rows[$i] = $row;
        $i++;
    }

    $rows[$i] = array('');
    $i++;
    $rows[$i] = array('');
    $i++;
    $rows[$i] = array(get_string('competencies', 'block_evalcomix'));
    $i++;
    $rows[$i] = array('');
    $i++;
    $rows[$i] = array(get_string('name'));
    foreach ($activities as $activity) {
        $rows[$i][] = $activity['nombre'];
    }
    $rows[$i][] = 'Total';
    foreach ($competencyxdatas as $label => $grade) {
        $row = array();
        $row[] = $label;
        foreach ($activities as $cmid => $activity) {
            if (isset($ctitle[$label][$cmid])) {
                $row[] = $ctitle[$label][$cmid];
            } else {
                $row[] = '';
            }
        }
        $row[] = $grade;
        $rows[] = $row;
    }
    $rows = array_merge($header, $rows);
    return generate_excel_download($course->fullname . "-EvalCOMIX-assessment-report", $rows);
}

function block_evalcomix_get_file_columns($cir) {
    $columns = $cir->get_columns();
    $stdfields = array('idnumber', 'shortname', 'description', 'outcome', 'timecreated', 'timemodified', 'typename',
    'typedescription');
    $requiredfields = array('idnumber', 'shortname', 'outcome');
    if (empty($columns)) {
        $cir->close();
        $cir->cleanup();
        throw new \moodle_exception('cannotreadtmpfile', 'error');
    }
    if (count($columns) < 3) {
        $cir->close();
        $cir->cleanup();
        throw new \moodle_exception('csvfewcolumns', 'error');
    }

    // Test columns.
    $processed = array();
    foreach ($columns as $key => $unused) {
        $field = $columns[$key];
        $field = trim($field);
        $lcfield = core_text::strtolower($field);
        if (in_array($field, $stdfields) || in_array($lcfield, $stdfields)) {
            $newfield = $lcfield;
        } else {
            $cir->close();
            $cir->cleanup();
            throw new \moodle_exception('invalidfieldname', 'error', $returnurl, $field);
        }
        if (in_array($newfield, $processed)) {
            $cir->close();
            $cir->cleanup();
            throw new \moodle_exception('duplicatefieldname', 'error', $returnurl, $newfield);
        }
        $processed[$key] = $newfield;
    }

    foreach ($requiredfields as $field) {
        if (!in_array($field, $processed)) {
            throw new \moodle_exception('A required field is missing', 'error');
        }
    }

    return $processed;
}

// Generates generic Excel file for download.
function generate_excel_download($downloadname, $rows) {
    global $CFG;

    require_once($CFG->libdir . '/excellib.class.php');

    $workbook = new MoodleExcelWorkbook(clean_filename($downloadname));

    $myxls = $workbook->add_worksheet(get_string('pluginname', 'block_evalcomix'));

    $rowcount = 0;
    foreach ($rows as $row) {
        foreach ($row as $index => $content) {
            $pos = strpos($content, '[[-double-]]');
            $format = null;
            if ($pos !== false) {
                $content = str_replace('[[-double-]]', '', $content);
                $myxls->merge_cells($rowcount, $index, $rowcount, ($index + 1));
                $format = $workbook->add_format();
                $format->set_align('center');
            }
            $myxls->write($rowcount, $index, $content, $format);
        }
        $rowcount++;
    }

    $workbook->close();

    return $workbook;
}
