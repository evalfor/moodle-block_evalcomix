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

require_once('../../../config.php');

$courseid = required_param('id', PARAM_INT);
$studentid = optional_param('u', 0, PARAM_INT);
$groupid = optional_param('g', 0, PARAM_INT);
$export = optional_param('e', 0, PARAM_INT);

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
require_course_login($course);
$context = context_course::instance($course->id);

if (!has_capability('moodle/grade:viewhidden', $context)) {
    $studentid = $USER->id;
}
$grades = (!empty($studentid)) ? true : false;

if ($export && has_capability('moodle/grade:viewhidden', $context)) {
    require_once($CFG->dirroot . '/blocks/evalcomix/util.php');
    $params = array('course' => $course);
    if (!empty($groupid)) {
        $params['groupid'] = $groupid;
    } else if (!empty($studentid)) {
        $params['studentid'] = $studentid;
    }
    block_evalcomix_export_development_report($params);
    exit;
}

$PAGE->set_url(new moodle_url('/blocks/evalcomix/competency/report.php', array('id' => $courseid)));
$PAGE->set_pagelayout('incourse');
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'block_evalcomix'));
$PAGE->set_heading(get_string('pluginname', 'block_evalcomix'));
$PAGE->navbar->add(get_string('pluginname', 'block_evalcomix'), new moodle_url('../assessment/index.php?id='.$courseid));
$PAGE->navbar->add(get_string('compreport', 'block_evalcomix'));
$PAGE->set_pagelayout('report');
$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/blocks/evalcomix/ajax.js'));
$PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/evalcomix/style/development-report.css'));

require_once($CFG->dirroot . '/blocks/evalcomix/competency/renderer.php');
$renderer = $PAGE->get_renderer('block_evalcomix', 'competency');

require_once($CFG->dirroot . '/blocks/evalcomix/competency/reportlib.php');
require_once($CFG->dirroot . '/blocks/evalcomix/locallib.php');
$competencydatas = array();
$outcomedatas = array();
if (!empty($studentid)) {
    $datas = block_evalcomix_get_development_datas($courseid, $groupid, $studentid);
    $competencydatas = $datas->competency;
    $outcomedatas = $datas->outcome;
}
$students = block_evalcomix_get_members_course($courseid, $groupid);
$groups = $DB->get_records('groups', array('courseid' => $courseid));
$timeleft = block_evalcomix_get_remaining_download_time($courseid);

echo $OUTPUT->header();

\core\notification::info(get_string('inforeporttime', 'block_evalcomix', $timeleft));
if ($timeleft === 'disabled') {
    \core\notification::error(get_string('reporttimeleftdisabled', 'block_evalcomix', $timeleft));
} else if (!empty($timeleft)) {
    \core\notification::info(get_string('reporttimeleft', 'block_evalcomix', $timeleft));
}
echo html_writer::start_tag('div', array('id' => 'change'));
echo $renderer->display_report_page($courseid, $competencydatas, $outcomedatas, $groups, $groupid, $students, $studentid);
echo html_writer::end_tag('div');

echo $OUTPUT->footer();
echo '
<script>
ajax("'.$CFG->wwwroot . '/blocks/evalcomix/competency/loadcron.php?id='.$courseid.'");
</script>
';
