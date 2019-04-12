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
require_login();

$courseid      = required_param('id', PARAM_INT);
$tid       = optional_param('tool', 0, PARAM_INT);
$sorttool      = optional_param('sorttool', '', PARAM_TEXT);        // Course idsortitemid=lastname.
$edit      = optional_param('edit', '', PARAM_ALPHANUM);        // Tool id to be uploaded.

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('nocourseid');
}

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
$PAGE->navbar->add(get_string('courses'), $CFG->wwwroot .'/course');
$PAGE->navbar->add($course->shortname, $CFG->wwwroot .'/course/view.php?id=' . $courseid);
$PAGE->navbar->add('evalcomix', new moodle_url('../assessment/index.php?id='.$courseid));
$PAGE->set_pagelayout('report');

echo $OUTPUT->header();
$buttons = null;

if (ob_get_level() == 0) {
    ob_start();
}

echo '
    <center>
        <div><img src="'. $CFG->wwwroot . EVXLOGOROOT .'" width="230" alt="EvalCOMIX"/></div><br>
        <div><input type="button" style="color:#333333" value="'. get_string('assesssection', 'block_evalcomix').'"
        onclick="location.href=\''. $CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'\'"/></div><br>
    </center>
';
echo "
    <noscript>
        <div style='color: #f00;'>".get_string('alertjavascript', 'block_evalcomix')."</div>
    </noscript>\n";

if (has_capability('moodle/block:edit', $context, $USER->id)) {  // If the login user is an editing teacher.
    $editing = true;
} else {
    $editing = false;
}

echo '
    <script type="text/javascript">
        function urledit(u, nombre, edit) {
            win2 = window.open(u, nombre, "menubar=0,location=0,scrollbars,resizable,width=780,height=500");
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
require_once($CFG->dirroot .'/blocks/evalcomix/javascript/popup.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/webservice_evalcomix_client.php');


if ($tid) {
    $tooldelete = evalcomix_tool::fetch(array('id' => $tid));
    if ($tooldelete) {
        $response = webservice_evalcomix_client::get_ws_deletetool($tooldelete->idtool);
        $tooldelete->delete();
    }
}

if (isset($edit) && $edit != '' && $edit != 'undefined') {
    $tool = evalcomix_tool::fetch(array('idtool' => $edit));
    // LLamada para obtener datos y actualizar. Por lo general.
    $response = webservice_evalcomix_client::get_ws_list_tool($course->id, $tool->idtool);
    if ($response != false) {
        $tool->type = $response->type;
        $tool->title = $response->title;
        $tool->update();
    }
}

if (!$environment = evalcomix::fetch(array('courseid' => $course->id))) {
    $environment = new evalcomix('', $courseid, 'evalcomix');
    $environment->insert();
}

$tools = evalcomix_tool::fetch_all(array('evxid' => $environment->id));
$toollist = array();
if ($tools) {
    foreach ($tools as $tool) {
        if ($tool->type == 'tmp') {
            // Llamada para obtener datos y actualizar. Por lo general.
            $response = webservice_evalcomix_client::get_ws_list_tool($course->id, $tool->idtool);
            if ($response != false) {
                $tool->type = $response->type;
                $tool->title = $response->title;
                $tool->update();
                array_push($toollist, $tool);
            } else {
                $result = $tool->delete();
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
$urlcreate = webservice_evalcomix_client::get_ws_createtool(null, $lms = MOODLE_NAME, $course->id, $lang.'_utf8');

$counttool = count($toollist);

echo '
    <center>
        <div style="font-weight: bold; margin-bottom:0.5em">
            <h5> '. $OUTPUT->help_icon('whatis', 'block_evalcomix') .
            get_string('counttool', 'block_evalcomix') .':  '. $counttool .'</h5>
        </div>
        <div>
            <table style="width:80%;text-align:left;border-color:#146C84;background-color:#fff" border=1>
                <tr style="color:#00f; font-weight: bold; text-align:center">
                    <td style="background-color:inherit"><a href="index.php?id='.$courseid.'
                    &sorttool=title">'. get_string('title', 'block_evalcomix') .'</a></td>
                    <td style="background-color:inherit"><a href="index.php?id='.$courseid.'
                    &sorttool=type">'. get_string('type', 'block_evalcomix') .'</a></td>
                    <td style="padding:0.2em;background-color:inherit;">';

if ($editing) { // If the login user is an editing teacher.
    echo '
       <input type="button" value="'. get_string('newtool', 'block_evalcomix') .'"
       onclick="urledit(\''. $urlcreate .'\', \'wincreate\');">';
}

echo '
                    </td>
                </tr>
';

foreach ($toollist as $tool) {
    $urlview = '../assessment/assessment_form.php?id='.$course->id.'&t='.$tool->idtool.'&mode=view&vt=1';
    $urlopen = webservice_evalcomix_client::get_ws_createtool($tool->idtool, $lms = MOODLE_NAME,
    $course->id, $lang.'_utf8', 'open');
    echo '
                <tr>
                    <td style="border:1px solid #146C84; padding-left:0.6em">'. $tool->title.'</td>
                    <td style="border:1px solid #146C84;padding-left:0.5em;text-align:center;">'.
                    get_string($tool->type, 'block_evalcomix') .'</td>
                    <td style="border:1px solid #146C84;text-align:center;">
                    <input type="image" style="border:0; width:20px" src="'.
                    $CFG->wwwroot.'/blocks/evalcomix/images/lupa.gif" title="'.
                    get_string('view', 'block_evalcomix').'" alt="'. get_string('view', 'block_evalcomix').'" width="20"
                    onclick="url(\''. $urlview .'\', \'win1\')">';

    if ($editing) {
        echo ' <input type="image" style="border:0; width:20px" src="'.
        $CFG->wwwroot.'/blocks/evalcomix/images/edit.png" title="'.
        get_string('open', 'block_evalcomix') .'" alt="'. get_string('open', 'block_evalcomix') .'" width="20"
        onclick="urledit(\''. $urlopen .'\', \'win_open\', \''.$tool->idtool.'\');">
                        <input type="image" style="border:0; width:20px" src="'.
                        $CFG->wwwroot.'/blocks/evalcomix/images/delete.png" title="'.
                        get_string('delete', 'block_evalcomix').'" alt="'.
                        get_string('delete', 'block_evalcomix').'" width="20"
                        value="'.$tool->id.'" onclick="if (confirm(\'¿Está seguro que desea eliminar el instrumento?\'))
                            location.href=\'index.php?id='.$courseid.'&tool='.$tool->id.'\';">';
    }
    echo '              </td>
                </tr>
    ';
}

echo '
            </table>
        </div>
    </center>
';
ob_flush();
flush();

$newgrades = webservice_evalcomix_client::get_assessments_modified(array('tools' => $toollist));
if (!empty($newgrades)) {
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_grades.php');
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');
    $tasks = evalcomix_tasks::get_tasks_by_courseid($courseid);
    $toolids = array();
    foreach ($tasks as $task) {
        if ($assessments = evalcomix_assessments::fetch_all(array('taskid' => $task->id))) {
            foreach ($assessments as $assessment) {
                $activity = $task->instanceid;
                $module = evalcomix_tasks::get_type_task($activity);
                $mode = block_evalcomix_grade_report::get_type_evaluation($assessment->studentid,
                    $courseid, $assessment->assessorid);
                $str = $courseid . '_' . $module . '_' . $activity . '_' . $assessment->studentid .
                '_' . $assessment->assessorid . '_' . $mode . '_' . MOODLE_NAME;
                $assessmentid = md5($str);
                if (isset($newgrades[$assessmentid])) {
                    if (isset($newgrades[$assessmentid]->toolid)) {
                        $toolids[] = $newgrades[$assessmentid]->toolid;
                    }

                    if (isset($newgrades[$assessmentid]->grade)) {
                        $grade = $newgrades[$assessmentid]->grade;
                        $assessment->grade = $grade;
                        $assessment->update();
                    }
                    if ($evalcomixgrade = evalcomix_grades::fetch(array('courseid' => $courseid,
                        'cmid' => $task->instanceid, 'userid' => $assessment->studentid))) {
                        $params = array('cmid' => $task->instanceid, 'userid' => $assessment->studentid,
                        'courseid' => $courseid);
                        $finalgrade = evalcomix_grades::get_finalgrade_user_task($params);
                        if ($finalgrade !== null && (int)$finalgrade > -1) {
                            $evalcomixgrade->finalgrade = $finalgrade;
                            $evalcomixgrade->update();
                        }
                    }
                }
            }
        }
    }
    webservice_evalcomix_client::set_assessments_modified(array('toolids' => $toolids));
}

echo $OUTPUT->footer();