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
 * util
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <info@ansaner.net>
 */

/**
 * block_evalcomix_export_assessment
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <info@ansaner.net>
 */
class block_evalcomix_export_assessment {
    /**
     * Export report
     */
    public static function export($course) {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/blocks/evalcomix/locallib.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_assessments.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_grades.php');

        $context = context_course::instance($course->id);

        $now = time();

        // Get datas.
        $reportevalcomix = new block_evalcomix_grade_report($course->id, null, $context);
        $reportevalcomix->get_headers();
        $activities = $reportevalcomix->get_activities();
        $students = block_evalcomix_get_members_course($course->id);
        $studentids = array_keys($students);
        $hashtasks = block_evalcomix_tasks::get_tasks_by_courseid($course->id);
        $taskids = [];
        foreach ($hashtasks as $task) {
            $taskid = $task->id;
            $taskids[$taskid] = $taskid;
        }
        $modes = block_evalcomix_modes::get_modes_by_courseid($course->id);
        $hashmodes = [];
        foreach ($modes as $mode) {
            $cmid = $mode->cmid;
            $modality = $mode->modality;
            $hashmodes[$cmid][$modality] = $mode;
        }
        $assessments = block_evalcomix_assessments::get_assessments_by_tasks($taskids, $studentids);
        $hashassessments = [];
        foreach ($assessments as $assessment) {
            $studentid = $assessment->assessorid;
            $tid = $assessment->taskid;
            $assessmentid = $assessment->id;
            $hashassessments[$studentid][$tid][$assessmentid] = $assessment;
        }
        $commented = [];
        if ($assessments) {
            $commented = block_evalcomix_webservice_client::get_commented_assessments($course->id, $assessments);
        }

        $rows = self::build_rows(
            $course,
            $students,
            $activities,
            $hashtasks,
            $hashmodes,
            $hashassessments,
            $commented
        );

        return generate_excel_download($course->fullname . "-EvalCOMIX-assessment-report", $rows);
    }

    /**
     * build_rows
     */
    public static function build_rows($course, $students, $activities, $hashtasks, $hashmodes, $hashassessments, $commented) {
        $header = self::build_header($course, $activities, $hashmodes);
        $body = self::build_body(
            $students,
            $activities,
            $hashtasks,
            $hashmodes,
            $hashassessments,
            $commented
        );
        return array_merge($header, $body);
    }

    /**
     * build_header
     */
    public static function build_header($course, $activities, $hashmodes) {
        $header = [
            [strtoupper(get_string('assessmentreport', 'block_evalcomix'))],
            [''],
            [get_string('course') . ':', $course->fullname],
            [''],
            [''],
            [get_string('scale') . ':'],
            ['-', get_string('noconfigured', 'block_evalcomix')],
            [0, get_string('unrealized', 'block_evalcomix')],
            [1, get_string('doneoutofrange', 'block_evalcomix')],
            [2, get_string('doneoutofrangecomments', 'block_evalcomix')],
            [3, get_string('donewithinrange', 'block_evalcomix')],
            [4, get_string('donewithinrangecomments', 'block_evalcomix')],
            [''],
            [''],
        ];

        $headertotalself = [];
        $headertotalpeer = [];
        $rows = [];
        $rows[0][0] = get_string('lastname');
        $rows[0][1] = get_string('name');
        $rows[1][0] = '';

        if ($activities) {
            $index = 2;
            $ae = 1;
            $ei = 1;
            foreach ($activities['name'] as $key => $activityname) {
                $cmid = $activities['id'][$key];
                if (isset($hashmodes[$cmid])) {
                    $rows[0][$index] = $activityname . '[[-double-]]';
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
        $rows[0][$index] = get_string('assessmentreporttotalAE', 'block_evalcomix') . ' (' . implode('+', $headertotalself) .
            ')*(10/' . count($headertotalself) * 4 . ')';
        $rows[1][$index] = '';
        $selfindex = $index;
        $index++;
        $rows[0][$index] = get_string('assessmentreporttotalEI', 'block_evalcomix') . ' (' . implode('+', $headertotalpeer) .
            ')*(10/' . count($headertotalpeer) * 4 . ')';
        ;
        $rows[1][$index] = '';

        return array_merge($header, $rows);
    }

    /**
     * build_body
     */
    public static function build_body($students, $activities, $hashtasks, $hashmodes, $hashassessments, $commented) {
        if (!$students || !$activities) {
            return [];
        }

        $rows = [];
        $setofgrades = [];
        foreach ($students as $student) {
            $rows[] = self::build_student_row(
                $student,
                $activities,
                $hashtasks,
                $hashmodes,
                $hashassessments,
                $commented,
                $setofgrades
            );
        }

        return $rows;
    }

    /**
     * build_student_row
     */
    public static function build_student_row(
        $student,
        $activities,
        $hashtasks,
        $hashmodes,
        $hashassessments,
        $commented,
        &$setofgrades
    ) {
        $studentid = $student->id;
        $row = [];
        $row[] = $student->lastname;
        $row[] = $student->firstname;
        ;
        $totalself = [];
        $totalpeer = [];
        foreach ($activities['id'] as $cmid) {
            if (!isset($hashmodes[$cmid])) {
                continue;
            }

            [$gradeself, $gradepeer] = self::calculate_activity_grades(
                $studentid,
                $cmid,
                $hashtasks,
                $hashmodes,
                $hashassessments,
                $commented,
                $setofgrades
            );

            if (is_numeric($gradepeer)) {
                $totalpeer[] = $gradepeer;
            }
            if (is_numeric($gradeself)) {
                $totalself[] = $gradeself;
            }

            $row[] = $gradeself;
            $row[] = $gradepeer;
        }
        $counttotalself = count($totalself);
        if ($counttotalself > 0) {
            $row[] = round((array_sum($totalself) * 10) / (4 * ($counttotalself)), 2);
        }
        $counttotalpeer = count($totalpeer);
        if ($counttotalpeer > 0) {
            $row[] = round(array_sum($totalpeer) * 10 / (4 * ($counttotalpeer)), 2);
        }
        return $row;
    }

    /**
     * calculate_activity_grades
     */
    public static function calculate_activity_grades(
        $studentid,
        $cmid,
        $hashtasks,
        $hashmodes,
        $hashassessments,
        $commented,
        &$setofgrades
    ) {
        $gradeself = (isset($hashmodes[$cmid]['self'])) ? 0 : '-';
        $gradepeer = (isset($hashmodes[$cmid]['peer'])) ? 0 : '-';

        $gradepeeraverage = [];
        if (!isset($hashassessments[$studentid]) || !isset($hashtasks[$cmid])) {
            return [$gradeself, $gradepeer];
        }
        $task = $hashtasks[$cmid];
        $taskid = $task->id;
        if (!isset($hashassessments[$studentid][$taskid])) {
            return [$gradeself, $gradepeer];
        }

        foreach ($hashassessments[$studentid][$taskid] as $assessmentid => $assessment) {
            $grade = self::calculate_assessment_grade(
                $assessmentid,
                $assessment,
                $task,
                $commented,
                $setofgrades
            );

            if ($assessment->assessorid == $assessment->studentid) {
                $gradeself = $grade;
            } else {
                $gradepeeraverage[] = $grade;
            }
        }

        if (!empty($gradepeeraverage)) {
            $gradepeer = array_sum($gradepeeraverage) / count($gradepeeraverage);
            $gradepeer = round($gradepeer, 1);
        }

        return [$gradeself, $gradepeer];
    }

    /**
     * calculate_assessment_grade
     */
    public static function calculate_assessment_grade($assessmentid, $assessment, $task, $commented, &$setofgrades) {
        if (!isset($setofgrades[$assessmentid])) {
            $setofgrades[$assessmentid] = block_evalcomix_grades::get_main_set_of_grades(
                $task->id,
                $assessment
            );
        }

        $outofrange = block_evalcomix_grades::is_out_of_range(
            $task,
            $setofgrades[$assessmentid],
            $assessment
        );

        $basegrade = $outofrange ? 1 : 3;
        if (isset($commented[$assessmentid]) && $commented[$assessmentid] == 1) {
            $basegrade++;
        }

        return $basegrade;
    }
}

/**
 * block_evalcomix_export_competence
 */
function block_evalcomix_export_competence($courseid) {
    global $CFG, $DB;
    require_once($CFG->libdir . '/csvlib.class.php');

    $datas = $DB->get_records('block_evalcomix_competencies', ['courseid' => $courseid]);
    $types = $DB->get_records('block_evalcomix_comptype', ['courseid' => $courseid]);
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
    $csv->add_data(["\xEF\xBB\xBF"]);
    $csv->add_data(['idnumber', 'shortname', 'description', 'outcome', 'timecreated', 'timemodified',
        'typename', 'typedescription']);
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

/**
 * block_evalcomix_export_development_report
 */
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
    $ctitle = (isset($competencydatas->gradebytask)) ? $competencydatas->gradebytask : [];
    $otitle = (isset($outcomedatas->gradebytask)) ? $outcomedatas->gradebytask : [];
    $activities = block_evalcomix_tasks::get_moodle_course_tasks($courseid);

    $title = strtoupper(get_string('compreport', 'block_evalcomix'));
    $header = [
            [$title],
            [''],
            [get_string('course') . ':', $course->fullname],
    ];

    if ($group = $DB->get_record('groups', ['id' => $groupid])) {
        $header[][] = $group->name;
    }
    if ($student = $DB->get_record('user', ['id' => $studentid])) {
        $header[] = [fullname($student)];
    }

    $rows = [];

    $rows[0] = [''];
    $rows[1] = [get_string('outcomes', 'block_evalcomix')];
    $rows[2] = [''];
    $rows[3] = [get_string('name')];
    foreach ($activities as $activity) {
        $rows[3][] = $activity['nombre'];
    }
    $rows[3][] = 'Total';
    $i = 4;
    foreach ($outcomexdatas as $label => $grade) {
        $row = [];
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

    $rows[$i] = [''];
    $i++;
    $rows[$i] = [''];
    $i++;
    $rows[$i] = [get_string('competencies', 'block_evalcomix')];
    $i++;
    $rows[$i] = [''];
    $i++;
    $rows[$i] = [get_string('name')];
    foreach ($activities as $activity) {
        $rows[$i][] = $activity['nombre'];
    }
    $rows[$i][] = 'Total';
    foreach ($competencyxdatas as $label => $grade) {
        $row = [];
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

/**
 * block_evalcomix_get_file_columns
 */
function block_evalcomix_get_file_columns($cir) {
    $columns = $cir->get_columns();
    $stdfields = ['idnumber', 'shortname', 'description', 'outcome', 'timecreated', 'timemodified', 'typename',
    'typedescription'];
    $requiredfields = ['idnumber', 'shortname', 'outcome'];
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
    $processed = [];
    foreach ($columns as $key => $unused) {
        $field = $columns[$key];
        $field = trim($field);
        $lcfield = core_text::strtolower($field);
        if (in_array($field, $stdfields) || in_array($lcfield, $stdfields)) {
            $newfield = $lcfield;
        } else {
            $cir->close();
            $cir->cleanup();
            throw new \moodle_exception('invalidfieldname', 'error', null, $field);
        }
        if (in_array($newfield, $processed)) {
            $cir->close();
            $cir->cleanup();
            throw new \moodle_exception('duplicatefieldname', 'error', null, $newfield);
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

/**
 * Generates generic Excel file for download.
 */
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
