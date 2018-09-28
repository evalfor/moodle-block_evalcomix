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
require_login();

$courseid      = required_param('id', PARAM_INT);
$cmid          = required_param('tid', PARAM_INT);
$modality      = required_param('mode', PARAM_ALPHA);
$assessorid      = optional_param('a', 0, PARAM_INT);

global $CFG;
require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes.php');

$context = get_context_instance(CONTEXT_COURSE, $courseid);
$reportevalcomix = new grade_report_evalcomix($courseid, null, $context);
$users = $reportevalcomix->load_users();

$assessedusers = array();

if ($task = evalcomix_tasks::fetch(array('instanceid' => $cmid))) {
    if ($mode = evalcomix_modes::fetch(array('taskid' => $task->id, 'modality' => $modality))) {
        foreach ($users as $user) {
            if (!$assessorid) {
                $assessorid = $user->id;
            }
            // It obtains assignments for each task and user.
            $params2 = array('taskid' => $task->id, 'studentid' => $user->id, 'assessorid' => $assessorid);
            $assessments = evalcomix_assessments::fetch_all($params2);
            if ($assessments) {
                $assessedusers[] = $user;
            }
        }
    }
}

if (empty($assessedusers)) {
    $output = html_writer::start_tag('div', array('style' => 'font-style:italic'));
    $output .= get_string('nostudentselfassessed', 'block_evalcomix');
    $output .= html_writer::end_tag('div');
} else {
    $numusers = 0;
    $output = html_writer::start_tag('table');
    foreach ($assessedusers as $user) {
        $output .= html_writer::start_tag('tr');
        $output .= html_writer::start_tag('td', array('style' => 'margin:0;padding:0'));
        $output .= html_writer::empty_tag('input', array('type' => 'checkbox', 'checked' => 'checked',
            'name' => 'user_'. $numusers, 'value' => $user->id));
        $output .= html_writer::end_tag('td');
        $output .= html_writer::start_tag('td', array('style' => 'margin:0;padding:0'));
        $output .= html_writer::start_tag('label', array('for' => 'user_'. $numusers));
        $output .= $user->lastname .', '.$user->firstname;
        $output .= html_writer::end_tag('label');
        $output .= html_writer::end_tag('td');
        $output .= html_writer::end_tag('tr');
        $output .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'username_'.
            $numusers, 'value' => $user->firstname));
        $output .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'usersurname_'.
            $numusers, 'value' => $user->lastname));
        ++$numusers;
    }

    $output .= html_writer::end_tag('table');
    $output .= html_writer::empty_tag('input', array('type' => 'button',
        'value' => get_string('selectallany', 'block_evalcomix'), 'onclick' => 'select_all_in()'));
    $output .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'nu', 'value' => $numusers));
}
echo $output;
