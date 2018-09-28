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
defined('MOODLE_INTERNAL') || die();

require_once('../../../config.php');

$courseid      = required_param('id', PARAM_INT);
$mode          = required_param('mode', PARAM_ALPHA);

global $OUTPUT, $DB, $CFG;

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('nocourseid');
}

$PAGE->set_url(new moodle_url('/blocks/evalcomix/reports/index.php', array('id' => $courseid, 'mode' => $mode)));

require_login($course);
$context = context_course::instance($courseid);

$PAGE->set_pagelayout('incourse');

// Print the header.
$PAGE->navbar->add('evalcomix', new moodle_url('../assessment/index.php?id='.$courseid));

$strplural = 'reports';
$PAGE->navbar->add($strplural);
$PAGE->set_title($strplural);

$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();

$output = $PAGE->get_renderer('block_evalcomix');
$params['action'] = 'index.php?id='.$courseid.'&mode='.$mode;
$params['courseid'] = $courseid;
$params['context'] = $context;

switch ($mode) {
    case 'selftask':
        echo $output->view_form_selftask($params);
    break;
    case 'teachertask':
        echo $output->view_form_teachertask($params);
    break;
    default:
        global $PAGE;
        $output = $PAGE->get_renderer('block_evalcomix');
        echo $output->logoheader();
        echo '<center><input type="button" style="color:#333333" value="'.
        get_string('assesssection', 'block_evalcomix').'" onclick="location.href=\''.
        $CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'"></center><br>';
}

    echo $OUTPUT->footer();