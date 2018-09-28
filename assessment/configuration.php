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
require_once($CFG->dirroot . '/grade/report/grader/lib.php');
require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/webservice_evalcomix_client.php');

$courseid      = required_param('id', PARAM_INT);        // Course id.
$page          = optional_param('page', 0, PARAM_INT);   // Active page.
$hide          = optional_param('hide', 0, PARAM_INT);
$show          = optional_param('show', 0, PARAM_INT);
global $OUTPUT, $DB;

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('nocourseid');
}

if (!empty($hide)) {
    if ($taskhide = evalcomix_tasks::fetch(array('id' => $hide))) {
        $cm = $DB->get_record('course_modules', array('id' => $taskhide->instanceid, 'course' => $courseid), '*', MUST_EXIST);
        $taskhide->visible = 0;
        $taskhide->update();
    } else {
        print_error('Parameter "Hide" is wrong');
    }
} else if (!empty($show)) {
    if ($taskshow = evalcomix_tasks::fetch(array('id' => $show))) {
        $cm = $DB->get_record('course_modules', array('id' => $taskshow->instanceid, 'course' => $courseid), '*', MUST_EXIST);
        $taskshow->visible = 1;
        $taskshow->update();
    } else {
        print_error('Parameter "Show" is wrong');
    }
}

$PAGE->set_url(new moodle_url('/blocks/evalcomix/assessment/index.php', array('id' => $courseid)));
$buttons = false;

require_login($course);
$context = context_course::instance($courseid);
require_capability('moodle/block:edit', $context);

$PAGE->set_pagelayout('incourse');

$strplural = 'evalcomix';
$PAGE->navbar->add($strplural, new moodle_url('../assessment/configuration.php?id='.$courseid));
$PAGE->set_title($strplural);
$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();

echo '
<center>
<div><img src="'. $CFG->wwwroot . EVXLOGOROOT .'" width="230" alt="EvalCOMIX"/></div><br>
<div><input type="button" style="color:#333333" value="'. get_string('back', 'block_evalcomix').'"
onclick="location.href=\''. $CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'\'"/>
</div><br>
</center>
';

$reportevalcomix = new grade_report_evalcomix($courseid, null, $context, $page);
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

                    if (!$task = evalcomix_tasks::fetch(array('instanceid' => $cmid))) {
                        $task = new evalcomix_tasks('', $cmid, 100, 50, time(), '1');
                        $task->insert();
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
    <div id="icon" style="text-align:center">'.$icon.'</div></td></tr>';
}


echo '
        </table>
    </center>
    ';

echo $OUTPUT->footer();