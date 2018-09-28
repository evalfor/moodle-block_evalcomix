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
 * @author     Daniel Cabeza S�nchez <daniel.cabeza@uca.es>,
               Juan Antonio Caballero Hern�ndez <juanantonio.caballero@uca.es>
 */

require_once('../../../config.php');
require_login();

if (function_exists('clean_param_array')) {
    $datapost = clean_param_array($_POST, PARAM_ALPHANUM);
    $dataget = clean_param_array($_GET, PARAM_ALPHANUM);
} else if (function_exists('clean_param')) {
    $datapost = clean_param($_POST, PARAM_ALPHANUM);
    $dataget = clean_param($_GET, PARAM_ALPHANUM);
} else {
    $datapost = $_POST;
    $dataget = $_GET;
}

if (isset($datapost['stu']) && isset($datapost['cma']) && isset($dataget['id']) && isset($dataget['eva'])) {
    require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');

    $courseid = $dataget['id'];
    $assessorid = $dataget['eva'];

    $page = $datapost['page'];
    $context = context_course::instance($courseid);
    $reportevalcomix = new grade_report_evalcomix($courseid, null, $context, $page);
    $userid = $datapost['stu'];
    $cmid = $datapost['cma'];
    $reportevalcomix->process_data($datapost);
    // Obtains course's users.
    $users = $reportevalcomix->load_users();

    $coursegroups = $reportevalcomix->load_groups();
    $coursegroupings = $reportevalcomix->load_groupings();

    $finalgrades = evalcomix_grades::get_grades($courseid);

    $showdetails = true;
    $configured = $reportevalcomix->configured_activity($cmid);
    // Only show the user�s grade or all grades if the USER is a teacher or admin.
    if ((has_capability('moodle/grade:viewhidden', $context, $USER->id)
        || $userid == $USER->id) && isset($finalgrades[$cmid][$userid])) {
        if ($finalgrades[$cmid][$userid] != -1) {
            echo format_float($finalgrades[$cmid][$userid], 2);
        } else {
            echo '-';
        }
    } else { // There is not grade.
        if ($configured) {
            echo '-';
        } else {
            echo '<span style="font-style:italic;color:#f54927">'.get_string('noconfigured', 'block_evalcomix').'</span>';
        }
        $showdetails = false;
    }

    if ($configured) {
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_assessments.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_allowedusers.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');

        $mode = grade_report_evalcomix::get_type_evaluation($userid, $courseid);
        // Obtains required parameters to create details and evaluate links.
        $typeinstrument = evalcomix_tasks::get_type_task($cmid);
        $task = evalcomix_tasks::fetch(array('instanceid' => $cmid));
        $tool = get_evalcomix_modality_tool($courseid, $task->id, $mode);

        $urlinstrument = 'assessment_form.php?id='.$courseid.'&a='.$cmid.'&t='.$tool->idtool.'&s='.$userid.'&mode=assess';
        $task = evalcomix_tasks::fetch(array('instanceid' => $cmid));

        // Evaluate, Delete and Details buttons.
        $evaluate = '<input type="image" value="'.get_string('evaluate', 'block_evalcomix').'"
            title="'.get_string('evaluate', 'block_evalcomix').'"
            style="width:16px;" src="../images/evaluar.png" onclick="javascript:url(\'' . $urlinstrument . '\',\''.
        $userid . '\',\'' . $cmid . '\',\'' .
        $page . '\',\'' . $courseid . '\');"/>';
        if ($assessmentgrade = evalcomix_assessments::fetch(array('taskid' => $task->id,
            'assessorid' => $assessorid, 'studentid' => $userid))) {

            $evaluate = '<input type="image" value="'.get_string('evaluate', 'block_evalcomix').'"
                title="'.get_string('evaluate', 'block_evalcomix').'"
                style="width:16px;" src="../images/evaluar2.png" onclick="javascript:url(\'' .
                $urlinstrument . '\',\'' . $userid . '\',\'' .
                $cmid . '\', \'' . $page . '\',\'' . $courseid . '\');"/>';
        }
        if ($showdetails) {
            $details = '<input type="image" value="'.get_string('details', 'block_evalcomix').'"
            style="width:16px" title='.
            get_string('details', 'block_evalcomix').' src="../images/lupa.png"
            onclick="javascript:urlDetalles(\''. $CFG->wwwroot.
            '/blocks/evalcomix/assessment/details.php?cid=' . $context->id . '&itemid=' .
            $task->id . '&userid=' . $userid . '&popup=1\');"/>';
        } else {
            $details = '';
        }

        // Show user�s works.
        $title = get_string('studentwork1', 'block_evalcomix').get_string('studentwork2', 'block_evalcomix'). $cmid;
        echo ' <input type="image" value="'.$title.'" style="background-color:transparent;width:13px"
            title="'.$title.'" src="../images/task.png"
            onclick="javascript:urlDetalles(\''. $CFG->wwwroot. '/blocks/evalcomix/assessment/user_activity.php?id='.
            $userid.'&course='.
            $courseid.'&mod='.$cmid.'\');"/>';

        // If the $USER isn�t a teacher or admin evaluate if it should show Evaluate and Details buttons.
        if ($mode == 'self' || $mode == 'peer') {
            // Obtains the groupmode of the activity.
            $cm = $DB->get_record('course_modules', array('id' => $cmid));
            $groupmode = $cm->groupmode;
            $groupmembersonly = 0;
            if (isset($cm->groupmembersonly)) {
                $groupmembersonly = $cm->groupmembersonly;
            }
            $groupingid = $cm->groupingid;
            $samegrouping = false;
            $samegroup = $reportevalcomix->same_group($assessorid, $userid);

            if ($groupingid != 0) {
                $samegrouping = $reportevalcomix->same_grouping_by_users($assessorid, $userid, $cm);
            }
            $gidloginuser = $reportevalcomix->get_groupids($assessorid);
            $giduser = $reportevalcomix->get_groupids($userid);

            $condition = false;
            if ($evalcomixallowedusers = evalcomix_allowedusers::fetch(array('cmid' => $cmid,
                'assessorid' => $assessorid, 'studentid' => $userid))) {
                $condition = true;
            }

            // If (there are not Separated groups) OR
            // (there are Separated groups AND
            // ($user is member of the same group that login $USER OR they are in the same grouping in that activity)).
            if ((!$groupmembersonly && (
                ($samegrouping && (
                        ($groupmode != 1
                        || $samegroup))
                )
                ||
                (!$groupingid &&
                    (($groupmode != 1
                        || $samegroup)))
                )
            )
            || ($groupmembersonly  && (
                (!$groupingid &&
                    (($groupmode != 1
                        || $samegroup)))
                ||
                ($samegrouping && (
                    ($groupmode != 1
                        || $samegroup)))
                )
            )
            || $condition == true
            ) {
                $modetime = $reportevalcomix->get_modestime($task->id, $mode);
                if ($modetime != false) {
                    $now = getdate();
                    $nowtimestamp = mktime($now["hours"], $now["seconds"], $now["minutes"],
                        $now["mon"], $now["mday"], $now["year"]);

                    $available = $modetime->timeavailable;
                    $due = $modetime->timedue;

                    if ($mode == 'self') { // Details always are shown in selfassessment.
                        echo $details;
                    } else if ($nowtimestamp >= $due && $mode == 'peer' && $showdetails == true) {
                        $urlpeerinstrument = webservice_evalcomix_client::get_ws_view_assessment($courseid,
                        $typeinstrument, $cmid, $assessorid, $userid, 'peer', MOODLE_NAME);
                        echo '<input type="image" value="'.get_string('details', 'block_evalcomix').'" style="width:16px" title='.
                        get_string('details', 'block_evalcomix').' src="../images/lupa.png"
                        onclick="javascript:urlDetalles(\''. $urlpeerinstrument .'\');"/>';
                    }
                    // Show the buttons if they must be availables.
                    if ($nowtimestamp >= $available && $nowtimestamp < $due) {
                        echo $evaluate;
                    }
                }
            }
        } else { // Mode == 'teacher'.
            echo $details.$evaluate;
        }
    }
}