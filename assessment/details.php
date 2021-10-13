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
 * A page to display a list of ratings for a given item
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>,
               Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>,
               Claudia Ortega Gómez <claudia.ortega@uca.es>
 */

require_once('../../../config.php');

$contextid  = required_param('cid', PARAM_INT);
list($context, $course) = get_context_info_array($contextid);
require_course_login($course);

$itemid     = required_param('itemid', PARAM_INT);
$userid     = required_param('userid', PARAM_INT);
$popup      = optional_param('popup', 0, PARAM_INT);
$assid      = optional_param('assid', 0, PARAM_INT);

require_capability('block/evalcomix:view', $context, $userid);

require_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');

$url = new moodle_url('/blocks/evalcomix/assessment/details.php', array('contextid' => $contextid, 'itemid' => $itemid));
$PAGE->set_url($url);
$PAGE->set_context($context);
if ($popup) {
    $PAGE->set_pagelayout('popup');
}
$PAGE->set_title(get_string('ratingsforitem', 'block_evalcomix'));
$PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/evalcomix/style/details.css'));

require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes_time.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes_extra.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/webservice_evalcomix_client.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_grades.php');
require_once($CFG->dirroot .'/blocks/evalcomix/javascript/popup.php');
global $DB, $USER;

$renderer = $PAGE->get_renderer('block_evalcomix');

// Getting datas.
$user = $DB->get_record('user', array('id' => $userid), '*', MUST_EXIST);
if (!$task = $DB->get_record('block_evalcomix_tasks', array('id' => $itemid))) {
    print_error('Wrong parameters');
}

if ($modeei = $DB->get_record('block_evalcomix_modes', array('taskid' => $task->id, 'modality' => 'peer'))) {
    $modeeitime = $DB->get_record('block_evalcomix_modes_time', array('modeid' => $modeei->id));
    $modesextra = $DB->get_record('block_evalcomix_modes_extra', array('modeid' => $modeei->id));
} else {
    $modeeitime = '';
}

$cm = $DB->get_record('course_modules', array('id' => $task->instanceid), '*');
$module = $DB->get_record('modules', array('id' => $cm->module), '*');
$maxgrade = $task->maxgrade;
$dataactivity = $DB->get_record($module->name, array('id' => $cm->instance));
$grademethod = $task->grademethod;

// If a teacher has done click on Delete button.
if ($assid && has_capability('moodle/block:edit', $context)) {
    $assessdelete = $DB->get_record('block_evalcomix_assessments', array('id' => $assid));
    if ($assessdelete) {
        $stringmode = 'peer';
        if ($assessdelete->assessorid == $assessdelete->studentid) {
            $stringmode = 'self';
        }
        $response = block_evalcomix_webservice_client::delete_ws_assessment($course->id, $module->name,
            $task->instanceid, $user->id, $assessdelete->assessorid, $stringmode, BLOCK_EVALCOMIX_MOODLE_NAME);
        $DB->delete_records('block_evalcomix_assessments', array('id' => $assessdelete->id));

        $params = array('cmid' => $task->instanceid, 'userid' => $user->id, 'courseid' => $course->id);
        $finalgrade = block_evalcomix_grades::get_finalgrade_user_task($params);
        if ($finalgrade !== null) {
            if ($gradeobject = $DB->get_record('block_evalcomix_grades', $params)) {
                $params['id'] = $gradeobject->id;
                $params['finalgrade'] = $finalgrade;
                $DB->update_record('block_evalcomix_grades', $params);
            }
        } else {
            if ($gradeobject = $DB->get_record('block_evalcomix_grades', $params)) {
                $DB->delete_records('block_evalcomix_grades', array('id' => $gradeobject->id));
            }
        }
    }
}

$assessments = $DB->get_records('block_evalcomix_assessments', array('studentid' => $user->id, 'taskid' => $itemid));

$selfassessment = null;
$teacherassessments = array();
$peerassessments = array();
$peergrades = array();
$totalteachergrade = 0;
$numteachergrades = 0;
$withteachergrade = false;
$mainsetofgrades = array();
$gradeobject = new stdClass();
$gradeobject->teacher = new stdClass();
$gradeobject->teacher->grades = array();
$gradeobject->teacher->weighing = null;
$gradeobject->teacher->maxgrade = 100;
$gradeobject->self = new stdClass();
$gradeobject->self->grades = array();
$gradeobject->self->weighing = null;
$gradeobject->self->maxgrade = 100;
$gradeobject->peer = new stdClass();
$gradeobject->peer->extra = '';
$gradeobject->peer->grades = array();
$gradeobject->peer->weighing = null;
$gradeobject->peer->maxgrade = 100;
$gradeobject->helpicon = 'evalcomixgrade';

// Get Weighings.
$weighingteacher = '';
$modality = $DB->get_record('block_evalcomix_modes', array('taskid' => $itemid, 'modality' => 'teacher'));
if ($modality != null) {
    $gradeobject->teacher->weighing = $modality->weighing;
    $weighingteacher  = $modality->weighing;
    $withteachergrade = true;
}

$weighingself = '';
$modality = $DB->get_record('block_evalcomix_modes', array('taskid' => $itemid, 'modality' => 'self'));
if ($modality != null) {
    $gradeobject->self->weighing = $modality->weighing;
    $weighingself = $modality->weighing;
}

$now = time();
$inperiod = false;
$weighingpeer = '';
$modality = $DB->get_record('block_evalcomix_modes', array('taskid' => $itemid, 'modality' => 'peer'));
if ($modality != null) {
    $gradeobject->peer->weighing = $modality->weighing;
    $weighingpeer = $modality->weighing;
    if ($modeeitime && $now >= $modeeitime->timeavailable && $now <= $modeeitime->timedue) {
        $inperiod = true;
    }
}

if ($assessments) {
    foreach ($assessments as $assessment) {
        if (has_capability('moodle/grade:viewhidden', $context, $assessment->assessorid)) {
            array_push($teacherassessments, $assessment);
        } else if ($assessment->assessorid == $user->id) {
            $selfassessment = $assessment;
        } else {
            array_push($peerassessments, $assessment);
            $peergrades[] = $assessment->grade;
        }
    }
}

/*-------------------------------------------------------------------------------------------
 TEACHER_ASSESSMENT--------------------------------------------------------------------------
 -------------------------------------------------------------------------------------------*/
if (!empty($teacherassessments) && $tool = block_evalcomix_get_modality_tool($course->id, $task->id, 'teacher')) {
    foreach ($teacherassessments as $teachergrade) {
        unset($tgrade);
        $tgrade = new stdClass();
        $userassessor = $DB->get_record('user', array('id' => $teachergrade->assessorid));
        $name = get_string('gradeof', 'block_evalcomix') . $userassessor->firstname . ' ' . $userassessor->lastname;

        $tgrade->assessmenturl = $CFG->wwwroot . '/blocks/evalcomix/assessment/assessment_form.php?id='.
            $course->id.'&a='.$task->instanceid.'&t='.$tool->idtool.
            '&s='.$user->id.'&as='.$teachergrade->assessorid.'&mode=view';

        $tgrade->assessorname = $name;
        $tgrade->assessorurl = $CFG->wwwroot . '/user/view.php?id='.$teachergrade->assessorid .'&course='.$course->id;
        $tgrade->grade = $teachergrade->grade;
        $tgrade->color = 'text-dark';

        $gradeobject->teacher->grades[] = $tgrade;

        $mainsetofgrades[] = $teachergrade->grade;

        $totalteachergrade += $teachergrade->grade;
        $numteachergrades++;
    }
}

/*-------------------------------------------------------------------------------------------
PEER_ASSESSMENT------------------------------------------------------------------------------
-------------------------------------------------------------------------------------------*/
$now = time();
if (!empty($peerassessments) && $tool = block_evalcomix_get_modality_tool($course->id, $task->id, 'peer')) {
    $now = time();
    if (has_capability('moodle/grade:viewhidden', $context) || ($modeeitime &&
        $now > $modeeitime->timedue) || $modesextra->visible == 1) {
        // Checks if it is an anonymus assessment.
        if ($modesextra != null && $modesextra->anonymous == 1 && has_capability('moodle/grade:viewhidden', $context) == false) {
            $anonymous = true;
        } else {
            $anonymous = false;
        }

        if ($modeeitime == null || $now <= $modeeitime->timedue) { // It is in assessment time yet.
            $gradeobject->peer->extra = '<i>' . $OUTPUT->help_icon('timeopen', 'block_evalcomix') .
            get_string('timeopen', 'block_evalcomix') . '</i>';
        }

        $normalpeervalues = false;
        foreach ($peerassessments as $peergrade) {
            unset($tgrade);
            $tgrade = new stdClass();
            $tgrade->assessmenturl = $CFG->wwwroot . '/blocks/evalcomix/assessment/assessment_form.php?id='.
                $course->id.'&a='.$task->instanceid.'&t='.$tool->idtool.'&s='.$user->id.'&as='.
                $peergrade->assessorid.'&mode=view';
            $tgrade->assessorname = '';
            $tgrade->assessorurl = '';
            $tgrade->grade = $peergrade->grade;
            $tgrade->color = '';
            $tgrade->deleteurl = '';

            if ($grademethod == BLOCK_EVALCOMIX_GRADE_METHOD_WA_SMART && !$withteachergrade) {
                if (block_evalcomix_grades::is_extreme_grade($peergrade->grade, $peergrades)) {
                    $tgrade->color = BLOCK_EVALCOMIX_GRADE_METHOD_COLOR_EI_EXTREME  . ' font-weight-bold';
                } else if (block_evalcomix_grades::is_mild_grade($peergrade->grade, $peergrades)) {
                    $tgrade->color = BLOCK_EVALCOMIX_GRADE_METHOD_COLOR_EI_MILD . ' font-weight-bold';
                    $mainsetofgrades[] = $peergrade->grade;
                } else {
                    $mainsetofgrades[] = $peergrade->grade;
                }
            } else if ($grademethod == BLOCK_EVALCOMIX_GRADE_METHOD_WA_SMART && $withteachergrade) {
                if (block_evalcomix_grades::is_upper_grade($peergrade->grade, $mainsetofgrades)) {
                    $tgrade->color = BLOCK_EVALCOMIX_GRADE_METHOD_COLOR_UPPER . ' font-weight-bold';
                } else if (block_evalcomix_grades::is_lower_grade($peergrade->grade, $mainsetofgrades)) {
                    $tgrade->color = BLOCK_EVALCOMIX_GRADE_METHOD_COLOR_LOWER . ' font-weight-bold';
                } else {
                    $normalpeervalues = true;
                }
            }

            if (!$anonymous) {
                $userassessor = $DB->get_record('user', array('id' => $peergrade->assessorid));
                $name = get_string('gradeof', 'block_evalcomix') . $userassessor->firstname . ' ' . $userassessor->lastname;

                $tgrade->assessorname = get_string('gradeof', 'block_evalcomix') .
                    $userassessor->firstname . ' ' . $userassessor->lastname;
                $tgrade->assessorurl = $CFG->wwwroot . '/user/view.php?id='.$peergrade->assessorid.'&course='.$course->id;
            }

            // Teachers can delete grades.
            if (has_capability('moodle/block:edit', $context)) {
                $tgrade->deleteurl = $CFG->wwwroot . '/blocks/evalcomix/assessment/details.php?cid='.
                    $contextid.'&itemid='.$itemid.'&userid='.$userid.'&popup=1&assid='.$peergrade->id;
            }
            $gradeobject->peer->grades[] = $tgrade;
        }
        if ($grademethod == BLOCK_EVALCOMIX_GRADE_METHOD_WA_SMART
                && $normalpeervalues === false && $withteachergrade && !$inperiod) {
            $gradeobject->teacher->weighing += $gradeobject->peer->weighing;
            $gradeobject->peer->weighing = 0;

        }
    }
}

/*-------------------------------------------------------------------------------------------
SELF_ASSESSMENT------------------------------------------------------------------------------
-------------------------------------------------------------------------------------------*/

if ($selfassessment != null && $tool = block_evalcomix_get_modality_tool($course->id, $task->id, 'self')) {
    $urlself = 'assessment_form.php?id='.$course->id.'&a='.$task->instanceid.'&t='.$tool->idtool.
    '&s='.$user->id.'&as='.$selfassessment->assessorid.'&mode=view';

    unset($tgrade);
    $tgrade = new stdClass();
    $tgrade->color = 'text-dark';
    if ($grademethod == BLOCK_EVALCOMIX_GRADE_METHOD_WA_SMART) {
        if (block_evalcomix_grades::is_upper_grade($selfassessment->grade, $mainsetofgrades)) {
            if (!empty($gradeobject->teacher->grades)) {
                $tgrade->color = BLOCK_EVALCOMIX_GRADE_METHOD_COLOR_UPPER . ' font-weight-bold';
                $gradeobject->teacher->weighing += $gradeobject->self->weighing;
                $gradeobject->self->weighing = 0;
            } else if (!empty($gradeobject->peer->grades) && count($peergrades) > 4) {
                $tgrade->color = BLOCK_EVALCOMIX_GRADE_METHOD_COLOR_UPPER . ' font-weight-bold';
                $gradeobject->peer->weighing += $gradeobject->self->weighing;
                $gradeobject->self->weighing = 0;
            }
        } else if (block_evalcomix_grades::is_lower_grade($selfassessment->grade, $mainsetofgrades)) {
            if (!empty($gradeobject->teacher->grades)) {
                $tgrade->color = BLOCK_EVALCOMIX_GRADE_METHOD_COLOR_LOWER . ' font-weight-bold';
                $gradeobject->teacher->weighing += $gradeobject->self->weighing;
                $gradeobject->self->weighing = 0;
            } else if (!empty($gradeobject->peer->grades)  && count($peergrades) > 4) {
                $tgrade->color = BLOCK_EVALCOMIX_GRADE_METHOD_COLOR_LOWER . ' font-weight-bold';
                $gradeobject->peer->weighing += $gradeobject->self->weighing;
                $gradeobject->self->weighing = 0;
            }
        }
    }
    $tgrade->assessmenturl = $CFG->wwwroot . '/blocks/evalcomix/assessment/assessment_form.php?id='.
        $course->id.'&a='.$task->instanceid.'&t='.$tool->idtool.
        '&s='.$user->id.'&as='.$selfassessment->assessorid.'&mode=view';

    $tgrade->assessorname = '';
    $tgrade->assessorurl = '';
    $tgrade->grade = $selfassessment->grade;
    $tgrade->deleteurl = '';

    if (has_capability('moodle/block:edit', $context)) {
        $tgrade->deleteurl = $CFG->wwwroot . '/blocks/evalcomix/assessment/details.php?cid='.
            $contextid.'&itemid='.$itemid.'&userid='.$userid.'&popup=1&assid='.$selfassessment->id;
    }
    $gradeobject->self->grades[] = $tgrade;
} else {
    $selfassessment = null;
}

$evalcomixgrade = block_evalcomix_grades::calculate_finalgrades($context, $task, $userid);

if ($evalcomixgrade > -1) {
    $gradeobject->finalgrade = $evalcomixgrade;
    $gradeobject->maxgrade = 100;
}

if ($grademethod == BLOCK_EVALCOMIX_GRADE_METHOD_WA_SMART) {
    $gradeobject->helpicon = 'evalcomixgradesmart';
    $gradeobject->legend = '
    <div class="row border p-1 mb-1" style="font-size:0.8em">
        <div class="col-sm-3"><span class="'.BLOCK_EVALCOMIX_GRADE_METHOD_BGCOLOR_EI_MILD.
        ' p-1 pr-2"></span><span class="pl-1">'.get_string('mildoutlier', 'block_evalcomix').'</span></div>
        <div class="col-sm-3"><span class="'.BLOCK_EVALCOMIX_GRADE_METHOD_BGCOLOR_EI_EXTREME.
        ' p-1 pr-2"></span><span class="pl-1">'.get_string('extremeoutlier', 'block_evalcomix').'</span></div>
        <div class="col-sm-3"><span class="'.BLOCK_EVALCOMIX_GRADE_METHOD_BGCOLOR_UPPER.
        ' p-1 pr-2"></span><span class="pl-1">'.get_string('overvaluation', 'block_evalcomix').'</span></div>
        <div class="col-sm-3"><span class="'.BLOCK_EVALCOMIX_GRADE_METHOD_BGCOLOR_LOWER.
        ' p-1 pr-2"></span><span class="pl-1">'.get_string('undervaluation', 'block_evalcomix').'</span></div>
    </div>
    ';
}

echo $OUTPUT->header();

echo '
    <h1>'. get_string('ratingsforitem', 'block_evalcomix') .': '. $dataactivity->name .'</h1>
    <h2>' . $user->firstname . ' ' . $user->lastname . '</h2>
';

echo $renderer->grade_details($gradeobject);
if ($popup) {
    echo $OUTPUT->close_window_button();
}
echo $OUTPUT->footer();
