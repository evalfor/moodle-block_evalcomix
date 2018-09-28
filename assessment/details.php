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
require_once('../configeval.php');
require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');

$contextid  = required_param('cid', PARAM_INT);
$itemid     = required_param('itemid', PARAM_INT);
$userid     = required_param('userid', PARAM_INT);
$popup      = optional_param('popup', 0, PARAM_INT);
$assid      = optional_param('assid', 0, PARAM_INT);

list($context, $course) = get_context_info_array($contextid);
require_login($course->id);

$url = new moodle_url('/blocks/evalcomix/assessment/details.php', array('contextid' => $contextid, 'itemid' => $itemid));
$PAGE->set_url($url);
$PAGE->set_context($context);

if ($popup) {
    $PAGE->set_pagelayout('popup');
}
$PAGE->set_title(get_string('ratingsforitem', 'block_evalcomix'));
echo $OUTPUT->header();

require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes_time.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes_extra.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/webservice_evalcomix_client.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_grades.php');
require_once($CFG->dirroot .'/blocks/evalcomix/javascript/popup.php');
global $DB, $USER;

/*GETTING DATAS----------------------------------------------------------------------------------------*/
require_capability('block/evalcomix:view', $context, $userid);
$user = $DB->get_record('user', array('id' => $userid), '*');
if (!$task = evalcomix_tasks::fetch(array('id' => $itemid))) {
    print_error('Wrong parameters');
}

if ($modeei = evalcomix_modes::fetch(array('taskid' => $task->id, 'modality' => 'peer'))) {
    $modeeitime = evalcomix_modes_time::fetch(array('modeid' => $modeei->id));
    $modesextra = evalcomix_modes_extra::fetch(array('modeid' => $modeei->id));
} else {
    $modeeitime = '';
}
$cm = $DB->get_record('course_modules', array('id' => $task->instanceid), '*');
$module = $DB->get_record('modules', array('id' => $cm->module), '*');
$maxgrade = $task->maxgrade;
$dataactivity = $DB->get_record($module->name, array('id' => $cm->instance));


// If a teacher has done click on Delete button.
if ($assid && has_capability('moodle/block:edit', $context)) {
    $assessdelete = evalcomix_assessments::fetch(array('id' => $assid));
    if ($assessdelete) {
        $stringmode = 'peer';
        if ($assessdelete->assessorid == $assessdelete->studentid) {
            $stringmode = 'self';
        }
        $response = webservice_evalcomix_client::delete_ws_assessment($course->id, $module->name,
            $task->instanceid, $user->id, $assessdelete->assessorid, $stringmode, MOODLE_NAME);
        $assessdelete->delete();

        $params = array('cmid' => $task->instanceid, 'userid' => $user->id, 'courseid' => $course->id);
        $finalgrade = evalcomix_grades::get_finalgrade_user_task($params);
        if ($finalgrade !== null) {
            if ($gradeobject = evalcomix_grades::fetch($params)) {
                $gradeobject->finalgrade = $finalgrade;
                $gradeobject->update();
            }
        } else {
            if ($gradeobject = evalcomix_grades::fetch($params)) {
                $gradeobject->delete();
            }
        }
    }
}

$assessments = evalcomix_assessments::fetch_all(array('studentid' => $user->id, 'taskid' => $itemid));

$selfassessment = null;
$teacherassessments = array();
$peerassessments = array();
$totalpeergrade = 0;
$numpeergrades = 0;
$totalteachergrade = 0;
$numteachergrades = 0;

// Get Weighings.
$modality = evalcomix_modes::fetch(array('taskid' => $itemid, 'modality' => 'teacher'));
if ($modality != null) {
    $weighingteacher = $modality->weighing;
} else {
    $weighingteacher = null;
}
$modality = evalcomix_modes::fetch(array('taskid' => $itemid, 'modality' => 'self'));
if ($modality != null) {
    $weighingself = $modality->weighing;
} else {
    $weighingself = null;
}
$modality = evalcomix_modes::fetch(array('taskid' => $itemid, 'modality' => 'peer'));
if ($modality != null) {
    $weighingpeer = $modality->weighing;
} else {
    $weighingpeer = null;
}

if ($assessments) {
    foreach ($assessments as $assessment) {
        if (has_capability('moodle/grade:viewhidden', $context, $assessment->assessorid)) {
            array_push($teacherassessments, $assessment);
        } else if ($assessment->assessorid == $user->id) {
            $selfassessment = $assessment;
        } else {
            array_push($peerassessments, $assessment);
        }
    }
}


echo '
            <h1>'. get_string('ratingsforitem', 'block_evalcomix') .': '. $dataactivity->name .'</h1>
            <h2>' . $user->firstname . ' ' . $user->lastname . '</h2>
            <table style="border:1px solid #e3e3e3; width:100%; text-align:center">
                <tr style="color:#00f; height:29px;background-image:url(\'../images/th.png\');">
                    <th>'. get_string('modality', 'block_evalcomix').'</th>
                    <th>'. get_string('grade', 'block_evalcomix').'</th>
                    <th>'. get_string('weighingfinalgrade', 'block_evalcomix').'</th>
                </tr>';

/*-------------------------------------------------------------------------------------------
 TEACHER_ASSESSMENT-------------------------------------------------------------------------
 -------------------------------------------------------------------------------------------*/
echo '
                <tr style="border:1px solid #e3e3e3">
                    <td>'. get_string('teachermodality', 'block_evalcomix').'</td>
                    <td>';


if (!empty($teacherassessments) && $tool = get_evalcomix_modality_tool($course->id, $task->id, 'teacher')) {
    foreach ($teacherassessments as $teachergrade) {
        $urlteacher = 'assessment_form.php?id='.$course->id.'&a='.$task->instanceid.'&t='.$tool->idtool.
            '&s='.$user->id.'&as='.$teachergrade->assessorid.'&mode=view';
        $totalteachergrade += $teachergrade->grade;
        $numteachergrades++;

        $userassessor = $DB->get_record('user', array('id' => $teachergrade->assessorid));
        $name = get_string('gradeof', 'block_evalcomix') . $userassessor->firstname . ' ' . $userassessor->lastname;

        $assessorview = $CFG->wwwroot . '/user/view.php?id='.$teachergrade->assessorid .'&course='.$course->id;
        echo '<a href="'.$assessorview.'" target="popup" title="'.$name.'"
onClick="window.open(this.href, this.target, \'scrollbars,resizable,width=780,height=500\');
return false;">'. round($teachergrade->grade, 2) .'/'. round($maxgrade, 2) .' </a>';
        echo '<input type="image" style="border:0; width:15px" src="../images/lupa.png"
onClick="window.open(\''.$urlteacher.'\', \'popup\', \'scrollbars,resizable,width=780,height=500\');
return false;" title="'.get_string('view', 'block_evalcomix').'" alt="'.get_string('view', 'block_evalcomix').'"
width="15"/>
                <!-- <input type="image" style="border:0; width:15px"
src=http://lince.uca.es/evalfor/moodle21A/blocks/evalcomix/images/delete.png
title="Eliminar" alt="Eliminar" width="15"/>-->
            ';
    }
} else {
    echo '<i>' . get_string('nograde', 'block_evalcomix') . '</i>';
}

if ($weighingteacher != null) {
    echo'
                    <td>'. $weighingteacher .'%</td>
                </tr>';
} else {
    echo'
                    <td></td>
                </tr>';
}

/*-------------------------------------------------------------------------------------------
SELF_ASSESSMENT-------------------------------------------------------------------------
-------------------------------------------------------------------------------------------*/
echo '
            <tr style="border:1px solid #e3e3e3">
                    <td>'. get_string('selfmodality', 'block_evalcomix').'</td>
                    <td>';
if ($selfassessment != null && $tool = get_evalcomix_modality_tool($course->id, $task->id, 'self')) {
    $urlself = 'assessment_form.php?id='.$course->id.'&a='.$task->instanceid.'&t='.$tool->idtool.
    '&s='.$user->id.'&as='.$selfassessment->assessorid.'&mode=view';
    echo
            round($selfassessment->grade, 2) .' / '. round($maxgrade, 2) .'
            <input type="image" style="border:0; width:15px" src="../images/lupa.png" onClick="window.open(\''.
    $urlself.'\', \'popup\', \'scrollbars,resizable,width=780,height=500\'); return false;" title="'.
    get_string('view', 'block_evalcomix').'" alt="'.get_string('view', 'block_evalcomix').'" width="15"/>';
        // Teachers can delete grades.

    if (has_capability('moodle/block:edit', $context)) {
        echo '<input type="image" style="border:0; width:16px" src="'.
        $CFG->wwwroot.'/blocks/evalcomix/images/delete.png"
title="'. get_string('delete', 'block_evalcomix').'" alt="'. get_string('delete', 'block_evalcomix').'"
width="16" value="'.$user->id.'"
onclick="if (confirm(\'¿Está seguro que desea eliminar el instrumento?\'))location.href=\'details.php?cid='.
        $contextid.'&itemid='.$itemid.'&userid='.$userid.'&popup=1&assid='.$selfassessment->id.'\';
window.opener.change_recarga();">';
    }
} else {
    echo    '<i>' . get_string('nograde', 'block_evalcomix') . '</i>';
}

if ($weighingself != null) {
    echo'
                    </td>
                    <td>'. $weighingself .'%</td>
                </tr>';
} else {
    echo'
                    </td>
                    <td></td>
                </tr>';
}

/*-------------------------------------------------------------------------------------------
PEER_ASSESSMENT----------------------------------------------------------------------------
-------------------------------------------------------------------------------------------*/
echo '
                <tr style="border:1px solid #e3e3e3">
                    <td>'. get_string('peermodality', 'block_evalcomix').'</td>
                    <td>';
$now = time();
if (!empty($peerassessments) && $tool = get_evalcomix_modality_tool($course->id, $task->id, 'peer')) {
    $now = time();
    if (has_capability('moodle/grade:viewhidden', $context) || ($modeeitime &&
        $now > $modeeitime->timedue) || $modesextra->visible == 1) {
        /* Checks if it is an anonymus assessment */
        if ($modesextra != null && $modesextra->anonymous == 1 && has_capability('moodle/grade:viewhidden', $context) == false) {
            $anonymous = true;
        } else {
            $anonymous = false;
        }

        if ($modeeitime == null || $now <= $modeeitime->timedue) { // It is in assessment time yet.
            echo  '<i>' . $OUTPUT->help_icon('timeopen', 'block_evalcomix') .
            get_string('timeopen', 'block_evalcomix') . '</i><br/>';
        }

        foreach ($peerassessments as $peergrade) {
            $purlpeer = '&a='.$task->instanceid.'&t='.$tool->idtool.'&s='.$user->id.'&as='.$peergrade->assessorid.'&mode=view';
            $urlpeer = 'assessment_form.php?id='.$course->id.$purlpeer;
            $totalpeergrade += $peergrade->grade;
            $numpeergrades++;
             // Checks if it is an anonymus assessment.
            if ($anonymous) {
                echo '<a href="#">'. round($peergrade->grade, 2) .'/'. round($maxgrade, 2) .' </a>';
            } else {
                $userassessor = $DB->get_record('user', array('id' => $peergrade->assessorid));
                $name = get_string('gradeof', 'block_evalcomix') . $userassessor->firstname . ' ' . $userassessor->lastname;
                $assessorview = $CFG->wwwroot . '/user/view.php?id='.$peergrade->assessorid.'&course='.$course->id;
                echo '<a href="'.$assessorview.'" target="popup" title="'.$name.'"
onClick="window.open(this.href, this.target, \'scrollbars,resizable,width=780,height=500\'); return false;">'.
                round($peergrade->grade, 2) .'/'. round($maxgrade, 2) .' </a>';
            }
            echo '<input type="image" style="border:0; width:15px" src="../images/lupa.png"
onClick="window.open(\''.$urlpeer.'\', \'popup\', \'scrollbars,resizable,width=780,height=500\');return false;"
title="Consultar" alt="Consultar" width="15"/>
                        <!-- <input type="image" style="border:0"
                        src=http://lince.uca.es/evalfor/moodle21A/blocks/evalcomix/images/delete.png
                        title="Eliminar" alt="Eliminar" width="15"/>-->
            ';

            /*Teachers can delete grades*/
            if (has_capability('moodle/block:edit', $context)) {
                echo '<input type="image" style="border:0; width:16px" src="'.
                $CFG->wwwroot.'/blocks/evalcomix/images/delete.png" title="'.
                get_string('delete', 'block_evalcomix').'" alt="'. get_string('delete', 'block_evalcomix').'"
                width="16" value="'.$peergrade->assessorid.'" 
onclick="if (confirm(\'¿Está seguro que desea eliminar el instrumento?\'))
location.href=\'details.php?cid='.$contextid.'&itemid='.$itemid.'&userid='.$userid.'&popup=1&assid='.$peergrade->id.'\';
window.opener.change_recarga();">';
            }
        }
    } else {
        echo    '<i>' . get_string('timeopen', 'block_evalcomix') . '</i>';
    }
} else {
    echo    '<i>' . get_string('nograde', 'block_evalcomix') . '</i>';
}


if ($weighingpeer != null) {
    echo '
                    </td>
                    <td>'. $weighingpeer .'%</td>
                </tr>
            </table>
            <br>';
} else {
    echo '
                    </td>
                    <td></td>
                </tr>
            </table>
            <br>';
}



/* Evalcomix Final Grade
If the login user is admin or a teacher and EItime is in assess time
*/
if (has_capability('moodle/grade:viewhidden', $context) && !($modeeitime && $now > $modeeitime->timedue)) {
    $numpeergrades = 0;
}           // If there is one evalcomix assessment at least.
$evalcomixgrade = -1;
if ($numteachergrades > 0 || $selfassessment != null || $numpeergrades > 0) {
    $evalcomixgrade = 0;
    if ($numteachergrades > 0) {
        $evalcomixgrade += ($totalteachergrade / $numteachergrades) * ($weighingteacher / $maxgrade);
    }
    if ($selfassessment != null) {
        $evalcomixgrade += $selfassessment->grade * ($weighingself / $maxgrade);
    }
    if ($numpeergrades > 0) {
        $evalcomixgrade += ($totalpeergrade / $numpeergrades) * ($weighingpeer / $maxgrade);
    }
}


// Moodle Final Grade.
$moodlegrade = -1;
$mgradeitem = $DB->get_record('grade_items', array('courseid' => $course->id, 'idnumber' => $cm->id));
if ($mgradeitem != null) {
    $mgrades = $DB->get_records('grade_grades', array('itemid' => $mgradeitem->id, 'userid' => $userid));
    if ($mgrades != null) {
        $numgrades = 0;
        foreach ($mgrades as $mgrade) {
            // Actividades que después de poner una nota se ha quitado la posibilidad de evaluar.
            if ($mgrade->finalgrade != null) {
                $moodlemaxgrade = $mgrade->rawgrademax; // Será el mismo para todas las tuplas de la misma actividad.
                $moodlegrade += $mgrade->finalgrade;
                $numgrades++;
            }
        }
        // Si hemos encontrado alguna tupla que no sea NULL le sumamos 1 a moodlegrade ya que se inicializó a -1.
        if ($numgrades > 0) {
            $moodlegrade++;
            $moodlegrade = round($moodlegrade / $numgrades, 2);
        }
    }
}

// Final Grade.
if ($evalcomixgrade == -1 && $moodlegrade == -1) { // No moodle or evalcomix grades.
    $finalgrade = -1;
} else if ($evalcomixgrade == -1 && $moodlegrade != -1) { // Only moodle grades.
    $finalgrade = $moodlegrade;
    $finalmaxgrade = $moodlemaxgrade;
} else if ($evalcomixgrade != -1 && $moodlegrade == -1) { // Only evalcomix grades.
    // If only exists peerassessment and it is in open assessment time.
    if (empty($teacherassessments) && $selfassessment == null && $modeeitime && $now <= $modeeitime->timedue) {
        $finalgrade = -1;
        $evalcomixgrade = -1;
    } else {
        $finalgrade = $evalcomixgrade;
        $finalmaxgrade = $maxgrade;
    }
} else { /* moodle grades and evalcomix grades
        // Se pondera la nota de evalcomix en función a la nota máxima de moodle antes de calcular la nota final*/
    $evxgrade = ($evalcomixgrade * $moodlemaxgrade) / $maxgrade;
    $finalgrade = $evxgrade * ($task->weighing / 100) + $moodlegrade * ((100 - $task->weighing) / 100);
    $finalmaxgrade = $moodlemaxgrade;
}

if ($evalcomixgrade > -1) {
    echo '<div style="text-align:right; font-weight:bold">'. $OUTPUT->help_icon('evalcomixgrade', 'block_evalcomix') .
    get_string('evalcomixgrade', 'block_evalcomix') .': '.
    format_float($evalcomixgrade, 2) .' / '. round($maxgrade, 2) .'</div>';
}

if ($popup) {
    echo $OUTPUT->close_window_button();
}
echo $OUTPUT->footer();