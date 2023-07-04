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
require_login();
$PAGE->requires->css('/blocks/evalcomix/style/styles.css');

$datapost = array();
$datapost['stu'] = optional_param('stu', null, PARAM_ALPHANUM);
$datapost['cma'] = optional_param('cma', null, PARAM_ALPHANUM);
$datapost['page'] = optional_param('page', 0, PARAM_INT);

$dataget = array();
$dataget['id'] = optional_param('id', null, PARAM_ALPHANUM);
$dataget['eva'] = optional_param('eva', null, PARAM_ALPHANUM);

if (isset($datapost['stu']) && isset($datapost['cma']) && isset($dataget['id']) && isset($dataget['eva'])) {
    require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_grades.php');

    $courseid = $dataget['id'];
    $assessorid = $dataget['eva'];

    $page = $datapost['page'];
    $context = context_course::instance($courseid);
    $reportevalcomix = new block_evalcomix_grade_report($courseid, null, $context, $page);
    $userid = $datapost['stu'];
    $cmid = $datapost['cma'];
    try {
        $reportevalcomix->process_data($datapost);
    } catch (Exception $e) {
        // Processed on a previous call.
        echo '';
    }
    $event = \block_evalcomix\event\student_assessed::create(array('objectid' => $cmid,
        'courseid' => $courseid, 'context' => $context, 'userid' => $assessorid, 'relateduserid' => $userid));
    $event->trigger();

    // Obtains course's users.
    $users = $reportevalcomix->load_users();

    $coursegroups = $reportevalcomix->coursegroups;
    $coursegroupings = $reportevalcomix->load_groupings();

    $finalgrades = block_evalcomix_grades::get_grades($courseid);

    $showdetails = true;
    $configured = $reportevalcomix->configured_activity($cmid);
    // Only show the grade of users or all grades if the USER is a teacher or admin.
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
            echo '<span class="text-danger font-italic">'.get_string('noconfigured', 'block_evalcomix').'</span>';
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

        $mode = block_evalcomix_grade_report::get_type_evaluation($userid, $courseid);
        // Obtains required parameters to create details and evaluate links.
        $typeinstrument = block_evalcomix_tasks::get_type_task($cmid);
        $task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cmid));
        $tool = block_evalcomix_get_modality_tool($courseid, $task->id, $mode);

        $urlinstrument = 'assessment_form.php?id='.$courseid.'&a='.$cmid.'&t='.$tool->idtool.'&s='.$userid.'&mode=assess';
        $task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cmid));

        $membersgroup = array();
        if ($task->workteams == 1) {
            if (!empty($coursegroups)) {
                foreach ($coursegroups as $groupid => $mgroup) {
                    if (in_array($userid, $mgroup)) {
                        $membersgroup = $mgroup;
                    }
                }
            }
        }

        // Evaluate, Delete and Details buttons.
        $evaluate = '<input type="image" value="'.get_string('evaluate', 'block_evalcomix').'"
            title="'.get_string('evaluate', 'block_evalcomix').'"
            class="block_evalcomix_w_16" src="../images/evaluar.png" onclick="javascript:url(\'' . $urlinstrument . '\',\''.
        $userid . '\',\'' . $cmid . '\',\'' .
        $page . '\',\'' . $courseid . '\', \'\', [' . implode(',', $membersgroup) . ']);"/>';
        if ($assessmentgrade = $DB->get_record('block_evalcomix_assessments', array('taskid' => $task->id,
            'assessorid' => $assessorid, 'studentid' => $userid))) {

            $evaluate = '<input type="image" value="'.get_string('evaluate', 'block_evalcomix').'"
                title="'.get_string('evaluate', 'block_evalcomix').'"
                class="block_evalcomix_w_16" src="../images/evaluar2.png" onclick="javascript:url(\'' .
                $urlinstrument . '\',\'' . $userid . '\',\'' .
                $cmid . '\', \'' . $page . '\',\'' . $courseid . '\', \'\', [' . implode(',', $membersgroup) . ']);"/>';
        }
        if ($showdetails) {
            $details = '<input type="image" value="'.get_string('details', 'block_evalcomix').'"
            class="block_evalcomix_w_16" title='.
            get_string('details', 'block_evalcomix').' src="../images/lupa.png"
            onclick="javascript:urlDetalles(\''. $CFG->wwwroot.
            '/blocks/evalcomix/assessment/details.php?cid=' . $context->id . '&itemid=' .
            $task->id . '&userid=' . $userid . '&popup=1\');"/>';
        } else {
            $details = '';
        }

        // Show user's works.
        $title = get_string('studentwork1', 'block_evalcomix').get_string('studentwork2', 'block_evalcomix'). $cmid;
        echo ' <input type="image" value="'.$title.'"
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
            if ($evalcomixallowedusers = $DB->get_record('block_evalcomix_allowedusers', array('cmid' => $cmid,
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
                        $urlpeerinstrument = block_evalcomix_webservice_client::get_ws_view_assessment($courseid,
                        $typeinstrument, $cmid, $assessorid, $userid, 'peer', BLOCK_EVALCOMIX_MOODLE_NAME);
                        echo '<input type="image" value="'.get_string('details', 'block_evalcomix').
                        '" class="block_evalcomix_w_16" title='.
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
