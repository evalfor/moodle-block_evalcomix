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
$courseid      = required_param('id', PARAM_INT);        // Course id.
require_course_login($courseid);

require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');
require_once($CFG->dirroot .'/blocks/evalcomix/configeval.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/grade_report.php');

$toolid = required_param('t', PARAM_ALPHANUM);
$perspective = required_param('mode', PARAM_ALPHA);
// It indicates what will be showed: ['1'] only the template tool or ['0'] tool filled with assessment.
$viewtemplate = optional_param('vt', '0', PARAM_INT);

$context = context_course::instance($courseid);

$url = new moodle_url('/blocks/evalcomix/assessment/assessment_form.php',
array('courseid' => $courseid, 't' => $toolid));
$PAGE->set_url($url);
$PAGE->set_pagelayout('popup');

if (!$tool = $DB->get_record('block_evalcomix_tools', array('idtool' => $toolid))) {
    print_error('EvalCOMIX: No tool enabled');
}

if ($perspective != 'assess' && $perspective != 'view') {
    print_error('EvalCOMIX: the mode param is wrong');
}

$lang = current_language();

// To show tool used in a assessment.
if ($viewtemplate == '0') {
    $cmid = required_param('a', PARAM_INT);
    $studentid = required_param('s', PARAM_INT);
    require_capability('block/evalcomix:assessed', $context, $studentid);

    $lms = BLOCK_EVALCOMIX_MOODLE_NAME;

    $module = block_evalcomix_tasks::get_type_task($cmid);

    $user = $DB->get_record('user', array('id' => $studentid));
    if ($user) {
        $modinfo = get_fast_modinfo($courseid);
        $mods = $modinfo->get_cms();
        $mod = $mods[$cmid];
        $title = fullname($user) .get_string('studentwork2', 'block_evalcomix'). $mod->name;
    }

    $urlinstrument = '';
    if ($perspective == 'assess') {
        $mode = block_evalcomix_grade_report::get_type_evaluation($studentid, $courseid);
        if ($task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cmid))) {
            if (!$modefetch = $DB->get_record('block_evalcomix_modes', array('taskid' => $task->id, 'modality' => $mode))) {
                print_error('EvalCOMIX: No permissions');
            }
        } else {
            print_error('EvalCOMIX: The activity is not configured with EvalCOMIX');
        }
        $assessor = $USER->id;
        $urlinstrument = block_evalcomix_webservice_client::get_ws_assessment_form($toolid, $lang.'_utf8',
        $courseid, $module, $cmid, $studentid, $assessor, $mode, $lms, 'assess', $title);
    } else if ($perspective == 'view') {
        $assessorid = required_param('as', PARAM_INT);

        if ($assessorid == $studentid) {
            $mode = 'self';
        } else {
            if (has_capability('moodle/grade:viewhidden', $context, $assessorid)) {
                $mode = 'teacher';
            } else if (has_capability('block/evalcomix:assessed', $context, $assessorid)) {
                $mode = 'peer';
            } else {
                print_error('EvalCOMIX: Wrong User');
            }
        }

        $urlinstrument = block_evalcomix_webservice_client::get_ws_viewtool($toolid, $lang.'_utf8', $courseid,
        $module, $cmid, $studentid, $assessorid, $mode, $lms, $title);
    }
} else if ($viewtemplate == '1') {
    require_capability('moodle/grade:viewhidden', $context, $USER->id);
    $urlinstrument = block_evalcomix_webservice_client::get_ws_viewtool($toolid, $lang.'_utf8');
}

$vars = explode('?', $urlinstrument);
require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

$curl = new block_evalcomix_curl();
$response = $curl->post($vars[0], $vars[1]);
if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
    echo $response;
} else {
    print_error('EvalCOMIX cannot get datas');
}

if ($viewtemplate == 0) {

    echo "<script>

    window.opener.onunload=function() {
        doWork('evalcomixtablegrade', 'servidor.php?id=".$courseid."&eva=".$USER->id."',
        'courseid=".$courseid."&page=&stu=".$studentid."&cma=".$cmid."');
        setTimeout(close, 1000);

    };

    /*window.opener.onbeforeunload() {
        doWork('evalcomixtablegrade', 'servidor.php?id=".$courseid."&eva=".$USER->id."',
        'courseid=".$courseid."&page=&stu=".$studentid."&cma=".$cmid."');
        close();
    };*/

    /*function testParent() {
        if (window.opener != null && !window.opener.closed) {
            setTimeout(\"testParent()\",1);
        }
        else {
            alert('Parent closed/does not exist.');
            doWork('evalcomixtablegrade', 'servidor.php?id=".$courseid."&eva=".$USER->id."',
            'courseid=".$courseid."&page=&stu=".$studentid."&cma=".$cmid."');
            window.close();
        }
}
    testParent()¨*/
</script>";
}
