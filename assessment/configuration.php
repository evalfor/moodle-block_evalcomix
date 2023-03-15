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
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>,
               Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

require_once('../../../config.php');

$courseid = required_param('id', PARAM_INT);        // Course id.
require_course_login($courseid);

$page = optional_param('page', 0, PARAM_INT);   // Active page.
$hide = optional_param('hide', 0, PARAM_INT);
$show = optional_param('show', 0, PARAM_INT);

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$context = context_course::instance($courseid);
require_capability('moodle/block:edit', $context);

require_once($CFG->dirroot . '/grade/report/grader/lib.php');
require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/webservice_evalcomix_client.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');

if (!empty($hide)) {
    if ($taskhide = $DB->get_record('block_evalcomix_tasks', array('id' => $hide))) {
        $cm = $DB->get_record('course_modules', array('id' => $taskhide->instanceid, 'course' => $courseid), '*', MUST_EXIST);
        $paramtask = array('id' => $taskhide->id, 'instanceid' => $taskhide->instanceid, 'maxgrade' => $taskhide->maxgrade,
            'weighing' => $taskhide->weighing, 'timemodified' => time(), 'visible' => 0);
        $DB->update_record('block_evalcomix_tasks', $paramtask);
    } else {
        print_error('Parameter "Hide" is wrong');
    }
} else if (!empty($show)) {
    if ($taskshow = $DB->get_record('block_evalcomix_tasks', array('id' => $show))) {
        $cm = $DB->get_record('course_modules', array('id' => $taskshow->instanceid, 'course' => $courseid), '*', MUST_EXIST);
        $paramtask = array('id' => $taskshow->id, 'instanceid' => $taskshow->instanceid, 'maxgrade' => $taskshow->maxgrade,
            'weighing' => $taskshow->weighing, 'timemodified' => time(), 'visible' => 1);
        $DB->update_record('block_evalcomix_tasks', $paramtask);
    } else {
        print_error('Parameter "Show" is wrong');
    }
}

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/blocks/evalcomix/assessment/index.php', array('id' => $courseid)));
$buttons = false;
$PAGE->set_pagelayout('incourse');
$strplural = 'evalcomix';
$PAGE->navbar->add(get_string('courses'), new moodle_url('/course/index.php'));
$PAGE->navbar->add($course->shortname, new moodle_url("/course/view.php", array('id' => $courseid)));
$PAGE->navbar->add($strplural, new moodle_url('../assessment/index.php', array('id' => $courseid)));
$PAGE->navbar->add(get_string('configuration'));
$PAGE->set_title($strplural);
$PAGE->set_heading($course->fullname);

$event = \block_evalcomix\event\configuration_viewed::create(array('courseid' => $course->id, 'context' => $context,
    'relateduserid' => $USER->id));
$event->trigger();

echo $OUTPUT->header();

echo '
<center>
<div><img src="'. $CFG->wwwroot . BLOCK_EVALCOMIX_EVXLOGOROOT .'" width="230" alt="EvalCOMIX"/></div><br>
<div><input type="button" value="'. get_string('back', 'block_evalcomix').'"
onclick="location.href=\''. $CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'\'"/>
</div><br>
</center>
';

$reportevalcomix = new block_evalcomix_grade_report($courseid, null, $context, $page);
$levels = $reportevalcomix->gtree->get_levels();


echo '
    <center>
        <div>'.get_string('settings_description', 'block_evalcomix').'</div><br>
        <table class="gradestable flexible boxaligncenter generaltable">
            <tr><th>'.get_string('activities', 'block_evalcomix').'</th><th>'.get_string('edition', 'block_evalcomix').'</th></tr>
    ';

$activities = array();
$tasks = array();

foreach ($levels as $row) {
    foreach ($row as $element) {
        if (isset($element['object']->itemnumber) &&
            $element['object']->itemnumber == 0 && $element['object']->itemtype != 'manual') {
            if ($element['type'] == 'item') {
                if ($cm = get_coursemodule_from_instance($element['object']->itemmodule,
                    $element['object']->iteminstance, $courseid)) {
                    $cmid = $cm->id;

                    if (!$task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cmid))) {
                        $pbet = array('instanceid' => $cmid, 'maxgrade' => 100, 'weighing' => 50, 'timemodified' => time(),
                            'visible' => '1');
                        $DB->insert_record('block_evalcomix_tasks', $pbet);
                    }
                    $taskid = $task->id;
                    $tasks[$taskid] = $task;

                    $name = $element['object']->get_name();
                    $dots = '';
                    if (strlen($name) > 50) {
                        $dots = '... ';
                    }

                    $taskname = substr($element['object']->get_name(), 0, 50) . $dots;

                    $activities[$taskid] = $taskname;
                    $icontask[$taskid] = $reportevalcomix->gtree->get_element_icon($element, false);
                }
            }
        }
    }
}

foreach ($tasks as $task) {
    $taskid = $task->id;
    if ($task->visible == '1') {
        $url = new moodle_url('../assessment/configuration.php?id='.$courseid, array('hide' => $taskid));
        $icon = $OUTPUT->action_icon($url, new pix_icon('t/hide', get_string('hide')));
    } else {
        $url = new moodle_url('../assessment/configuration.php?id='.$courseid, array('show' => $taskid));
        $icon = $OUTPUT->action_icon($url, new pix_icon('t/show', get_string('show')));
    }

    echo '<tr><td>'.$icontask[$taskid] . $activities[$taskid].'</td><td>
    <div id="icon" class="text-center">'.$icon.'</div></td></tr>';
}


echo '
        </table>
    </center>
    ';

echo $OUTPUT->footer();
