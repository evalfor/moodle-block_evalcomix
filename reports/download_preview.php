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

require_once('../../../config.php');

$courseid      = required_param('id', PARAM_INT);
$mode          = required_param('mode', PARAM_ALPHA);

global $OUTPUT, $DB, $CFG;

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('nocourseid');
}

$PAGE->set_url(new moodle_url('/blocks/evalcomix/reports/index.php', array('id' => $courseid)));

require_login($course);
$context = get_context_instance(CONTEXT_COURSE, $courseid);

$PAGE->set_pagelayout('incourse');

// Print the header.
$PAGE->navbar->add('evalcomix', new moodle_url('../assessment/index.php?id='.$courseid));

$strplural = 'reports';
$PAGE->navbar->add($strplural);
$PAGE->set_title($strplural);

$PAGE->set_heading($course->fullname);

if (function_exists('clean_param_array')) {
    $data = clean_param_array($_POST, PARAM_ALPHANUM);
} else if (function_exists('clean_param')) {
    $data = clean_param($_POST, PARAM_ALPHANUM);
} else {
    $data = $_POST;
}

if (isset($data['download'])) {
    $courseid = required_param('courseid', PARAM_INT);
    $studentids = required_param('student_ids', PARAM_RAW);
    $studentnames = required_param('student_names', PARAM_RAW);
    $task = required_param('task', PARAM_INT);
    $mode = required_param('mode', PARAM_ALPHA);
    $assessorid = optional_param('assessor_id', 0, PARAM_INT);

    require_once($CFG->dirroot . '/blocks/evalcomix/reports/xls/export_xls.php');

    $report = new export_xls(array('courseid' => $courseid, 'mode' => $mode));

    $params['courseid'] = $courseid;
    $params['student_ids'] = $studentids;
    $params['student_names'] = $studentnames;
    $params['task'] = $task;
    $params['mode'] = $mode;
    if ($assessorid) {
        $params['assessor_id'] = $assessorid;
    }

    $report->send_export($params);
    exit;
}

if (!empty($formdata)) {
    echo $OUTPUT->header();
    require_once($CFG->dirroot . '/blocks/evalcomix/reports/export.php');
    $params['courseid'] = $courseid;
    $params['mode'] = $mode;
    $params['format'] = $formdata['format'];

    $export = new export($params);
    $export->preprocess_data($formdata);
    $export->print_continue();
    $export->display_preview();
    echo $OUTPUT->footer();
} else {
    header("Location:index.php?id=$courseid&mode=$mode");
}

