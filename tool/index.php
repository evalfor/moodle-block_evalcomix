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
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

require_once('../../../config.php');

$courseid = required_param('id', PARAM_INT);
$tid = optional_param('tool', 0, PARAM_INT);
$sorttool = optional_param('sorttool', '', PARAM_TEXT);        // Course idsortitemid=lastname.
$edit = optional_param('edit', '', PARAM_ALPHANUM);        // Tool id to be uploaded.

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

require_course_login($course);

$context = context_course::instance($course->id);
require_capability('moodle/grade:viewhidden', $context);

require_once('../configeval.php');
require_once('../lib.php');
require_once($CFG->dirroot . '/lib/accesslib.php');

global $OUTPUT, $USER;

$PAGE->set_url(new moodle_url('/blocks/evalcomix/tool/index.php', array('id' => $courseid)));
$PAGE->set_pagelayout('incourse');
// Print the header.
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'block_evalcomix'));
$PAGE->set_heading(get_string('pluginname', 'block_evalcomix'));
$PAGE->navbar->add('evalcomix', new moodle_url('../assessment/index.php?id='.$courseid));
$PAGE->set_pagelayout('report');

require_once($CFG->dirroot .'/blocks/evalcomix/javascript/popup.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/webservice_evalcomix_client.php');
require_once($CFG->dirroot . '/blocks/evalcomix/renderer.php');

if ($tid) {
    $tooldelete = $DB->get_record('block_evalcomix_tools', array('id' => $tid));
    if ($tooldelete) {
        $response = block_evalcomix_webservice_client::get_ws_deletetool($tooldelete->idtool);
        if ($DB->delete_records('block_evalcomix_tools', array('id' => $tooldelete->id))) {
            $DB->delete_records('block_evalcomix_subdimension', array('toolid' => $tid));
            $event = \block_evalcomix\event\tool_deleted::create(array('objectid' => $tid,
                'courseid' => $course->id, 'context' => $context, 'relateduserid' => $USER->id));
            $event->trigger();
            redirect($CFG->wwwroot . '/blocks/evalcomix/tool/index.php?id='.$courseid, get_string('tooldeleted', 'block_evalcomix'),
                null, \core\output\notification::NOTIFY_SUCCESS);
        }
    }
}

if (isset($edit) && $edit != '' && $edit != 'undefined') {
    $tool = $DB->get_record('block_evalcomix_tools', array('idtool' => $edit));
    $response = block_evalcomix_webservice_client::get_ws_list_tool($course->id, $tool->idtool);
    if ($response != false) {
        $DB->update_record('block_evalcomix_tools', array('id' => $tool->id, 'evxid' => $tool->evxid, 'title' => $response->title,
            'type' => $response->type, 'idtool' => $tool->idtool, 'timemodified' => time()));
    }
}

if (!$environment = $DB->get_record('block_evalcomix', array('courseid' => $course->id))) {
    $DB->insert_record('block_evalcomix', array('courseid' => $courseid, 'viewmode' => 'evalcomix', 'sendgradebook' => '0'));
}

$tools = $DB->get_records('block_evalcomix_tools', array('evxid' => $environment->id));
$toollist = array();
if ($tools) {
    foreach ($tools as $tool) {
        if ($tool->type == 'tmp') {
            $response = block_evalcomix_webservice_client::get_ws_list_tool($course->id, $tool->idtool);
            if ($response != false) {
                if ($DB->update_record('block_evalcomix_tools', array('id' => $tool->id, 'evxid' => $tool->evxid,
                    'title' => $response->title, 'type' => $response->type, 'idtool' => $tool->idtool,
                    'timemodified' => time()))) {
                    if ($toolupdated = $DB->get_record('block_evalcomix_tools', array('id' => $tool->id))) {
                        array_push($toollist, $toolupdated);
                    }
                }
            } else {
                $result = $DB->delete_records('block_evalcomix_tools', array('id' => $tool->id));
            }
        } else {
            array_push($toollist, $tool);
        }
    }
}

if ($sorttool == 'title') {
    usort($toollist, 'block_evalcomix_cmp_type_tool');
    usort($toollist, 'block_evalcomix_cmp_title_tool');
} else if ($sorttool == 'type') {
    usort($toollist, 'block_evalcomix_cmp_title_tool');
    usort($toollist, 'block_evalcomix_cmp_type_tool');
}

$lang = current_language();
$urlcreate = block_evalcomix_webservice_client::get_ws_createtool(null, $lms = BLOCK_EVALCOMIX_MOODLE_NAME,
    $course->id, $lang.'_utf8');

$counttool = count($toollist);

$event = \block_evalcomix\event\tool_manager_viewed::create(array('courseid' => $course->id, 'context' => $context,
    'relateduserid' => $USER->id));
$event->trigger();

echo $OUTPUT->header();
$buttons = null;

if (ob_get_level() == 0) {
    ob_start();
}

echo block_evalcomix_renderer::display_main_menu($courseid, 'design');
echo  '<h3 class="mb-5">'.get_string('instruments', 'block_evalcomix').'</h3>';
echo "
    <noscript>
        <div class='text-danger'>".get_string('alertjavascript', 'block_evalcomix')."</div>
    </noscript>\n";

if (has_capability('moodle/block:edit', $context, $USER->id)) {  // If the login user is an editing teacher.
    $editing = true;
} else {
    $editing = false;
}

echo '
    <script type="text/javascript">
        function urledit(u, nombre, edit) {
            win2 = window.open(u, nombre, "menubar=0,location=0,scrollbars,resizable,width=900,height=500");
            checkChildedit(edit);
        }
        function checkChildedit(edit) {
            if (win2.closed) {
             window.location.replace("'.$CFG->wwwroot .'/blocks/evalcomix/tool/index.php?id='.$courseid.'&edit=" + edit);

                /*window.location.href = "'.$CFG->wwwroot .'/blocks/evalcomix/tool/index.php?id='.$courseid.'&edit=" + edit;*/
        }
        else setTimeout("checkChildedit(\'"+edit+"\')",1);
        }
    </script>
';

echo '
    <center>
        <div class="font-weight-bold mb-1">
            <h5> '. $OUTPUT->help_icon('whatis', 'block_evalcomix') .
            get_string('counttool', 'block_evalcomix') .':  '. $counttool .'</h5>
        </div>
        <div>
            <table class="generaltable w-75 text-left bg-white" border="1">
                <thead>
                <tr class="text-primary font-weight-bold text-center">
                    <td><a href="index.php?id='.$courseid.'
                    &sorttool=title">'. get_string('title', 'block_evalcomix') .'</a></td>
                    <td><a href="index.php?id='.$courseid.'
                    &sorttool=type">'. get_string('type', 'block_evalcomix') .'</a></td>
                    <td>';

if ($editing) { // If the login user is an editing teacher.
    echo '
       <input type="button" value="'. get_string('newtool', 'block_evalcomix') .'"
       onclick="urledit(\''. $urlcreate .'\', \'wincreate\');">';
}

echo '
                    </td>
                </tr>
                </thead>
                <tbody>
';

foreach ($toollist as $tool) {
    $urlview = '../assessment/assessment_form.php?id='.$course->id.'&t='.$tool->idtool.'&mode=view&vt=1';
    $urlopen = block_evalcomix_webservice_client::get_ws_createtool($tool->idtool, $lms = BLOCK_EVALCOMIX_MOODLE_NAME,
    $course->id, $lang.'_utf8', 'open');
    echo '
                <tr>
                    <td>'. $tool->title.'</td>
                    <td class="text-center">'.
                    get_string($tool->type, 'block_evalcomix') .'</td>
                    <td class="text-center">
                    <input type="image" src="'.
                    $CFG->wwwroot.'/blocks/evalcomix/images/lupa.png" title="'.
                    get_string('view', 'block_evalcomix').'" alt="'. get_string('view', 'block_evalcomix').'" width="20"
                    onclick="url(\''. $urlview .'\', \'win1\')">';

    if ($editing) {
        echo ' <input type="image" src="'.
        $CFG->wwwroot.'/blocks/evalcomix/images/edit.png" title="'.
        get_string('open', 'block_evalcomix') .'" alt="'. get_string('open', 'block_evalcomix') .'" width="20"
        onclick="urledit(\''. $urlopen .'\', \'win_open\', \''.$tool->idtool.'\');">
                        <input type="image"src="'.
                        $CFG->wwwroot.'/blocks/evalcomix/images/delete.png" title="'.
                        get_string('delete', 'block_evalcomix').'" alt="'.
                        get_string('delete', 'block_evalcomix').'" width="20"
                        value="'.$tool->id.'" onclick="if (confirm(\''.get_string('confirmdeletetool', 'block_evalcomix').'\'))
                            location.href=\'index.php?id='.$courseid.'&tool='.$tool->id.'\';">';
    }
    echo '              </td>
                </tr>
    ';
}

echo '
            </tbody>
            </table>
        </div>
    </center>
';
ob_flush();
flush();

$newgrades = block_evalcomix_webservice_client::get_assessments_modified(array('tools' => $toollist));
if (!empty($newgrades)) {
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_grades.php');
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');
    $tasks = block_evalcomix_tasks::get_tasks_by_courseid($courseid);
    $toolids = array();
    foreach ($tasks as $task) {
        if ($assessments = $DB->get_records('block_evalcomix_assessments', array('taskid' => $task->id))) {
            foreach ($assessments as $assessment) {
                $activity = $task->instanceid;
                $module = block_evalcomix_tasks::get_type_task($activity);
                $mode = block_evalcomix_grade_report::get_type_evaluation($assessment->studentid,
                    $courseid, $assessment->assessorid);
                $str = $courseid . '_' . $module . '_' . $activity . '_' . $assessment->studentid .
                '_' . $assessment->assessorid . '_' . $mode . '_' . BLOCK_EVALCOMIX_MOODLE_NAME;
                $assessmentid = md5($str);
                if (isset($newgrades[$assessmentid])) {
                    if (isset($newgrades[$assessmentid]->toolid)) {
                        $toolids[] = $newgrades[$assessmentid]->toolid;
                    }

                    if (isset($newgrades[$assessmentid]->grade)) {
                        if (is_numeric($newgrades[$assessmentid]->grade)) {
                            $grade = $newgrades[$assessmentid]->grade;
                            $DB->update_record('block_evalcomix_assessments', array('id' => $assessment->id,
                            'taskid' => $assessment->taskid, 'assessorid' => $assessment->assessorid,
                            'studentid' => $assessment->studentid, 'grade' => $grade, 'timemodified' => time()));
                        }
                    }
                    if ($evalcomixgrade = $DB->get_record('block_evalcomix_grades', array('courseid' => $courseid,
                        'cmid' => $task->instanceid, 'userid' => $assessment->studentid))) {
                        $params = array('cmid' => $task->instanceid, 'userid' => $assessment->studentid,
                        'courseid' => $courseid);
                        $finalgrade = block_evalcomix_grades::get_finalgrade_user_task($params);
                        if ($finalgrade !== null && (int)$finalgrade > -1) {
                            $DB->update_record('block_evalcomix_grades', array('id' => $evalcomixgrade->id,
                                'userid' => $evalcomixgrade->userid, 'cmid' => $evalcomixgrade->cmid, 'finalgrade' => $finalgrade,
                                'courseid' => $evalcomixgrade->courseid));
                        }
                    }
                }
            }
        }
    }
    block_evalcomix_webservice_client::set_assessments_modified(array('toolids' => $toolids));
}

echo $OUTPUT->footer();
