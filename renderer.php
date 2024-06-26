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
 * Defines the renderer for the block_evalcomix
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

require_once('../../../config.php');
require_login();

class block_evalcomix_renderer extends plugin_renderer_base {

    public $validformats = array('xls');

    public function grade_details ($grades) {
        global $CFG;

        $output = '
         <table class="generaltable text-center">
                <thead>
                    <tr class="bg-primary text-white">
                        <th>'. get_string('modality', 'block_evalcomix').'</th>
                        <th>'. get_string('grade', 'block_evalcomix').'</th>
                        <th>'. get_string('weighingfinalgrade', 'block_evalcomix').'</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>'. get_string('teachermodality', 'block_evalcomix').'</td>
                        <td>
        ';

        if (!empty($grades->teacher->grades)) {
            foreach ($grades->teacher->grades as $tgrade) {
                $output .= '<span title="'.$tgrade->assessorname.'">'
                . round($tgrade->grade, 2) .'/'. round($grades->teacher->maxgrade, 2) .' </span>';
                $output .= '<input type="image" src="../images/lupa.png"
onClick="window.open(\''.$tgrade->assessmenturl.'\', \'popup\', \'scrollbars,resizable,width=780,height=500\');
return false;" title="'.get_string('view', 'block_evalcomix').'" alt="'.get_string('view', 'block_evalcomix').'"
width="15"/>';
            }
        } else {
            $output .= '<i>' . get_string('nograde', 'block_evalcomix') . '</i>';
        }
        $output .= '
                        </td>
                        <td>
        ';

        if (!empty($grades->teacher->weighing)) {
            $output .= $grades->teacher->weighing . '%';
        }

        $output .= '
                        </td>
                    </tr>
                    <tr>
                        <td>'. get_string('selfmodality', 'block_evalcomix').'</td>
                        <td>
        ';

        if (!empty($grades->self->grades)) {
            foreach ($grades->self->grades as $tgrade) {
                $output .= '<span class="'.$tgrade->color.'">'.round($tgrade->grade, 2) .' / '. round($grades->self->maxgrade, 2) .'
                <input type="image" src="../images/lupa.png" onClick="window.open(\''.
                $tgrade->assessmenturl.'\', \'popup\', \'scrollbars,resizable,width=780,height=500\'); return false;" title="'.
                get_string('view', 'block_evalcomix').'" alt="'.get_string('view', 'block_evalcomix').'" width="15"/>';

                if (!empty($tgrade->deleteurl)) {
                    $output .= '<input type="image" src="'.
                    $CFG->wwwroot.'/blocks/evalcomix/images/delete.png"
                    title="'. get_string('delete', 'block_evalcomix').'" alt="'.
                    get_string('delete', 'block_evalcomix').'" width="16"
                    onclick="if (confirm(\''.get_string('confirmdeleteassessment', 'block_evalcomix').'\'))location.href=\''.
                    $tgrade->deleteurl.'\';
                    window.opener.change_recarga();">';
                }
            }
        } else {
            $output .= '<i>' . get_string('nograde', 'block_evalcomix') . '</i>';
        }

        $output .= '
                        </td>
                        <td>
        ';
        if (isset($grades->self->weighing)) {
            $output .= '<span>'.$grades->self->weighing . '%</span>';
        }
        $output .= '
                        </td>
                    </tr>
                    <tr>
                        <td>'. get_string('peermodality', 'block_evalcomix').'</td>
                        <td>
        ';

        if (!empty($grades->peer->grades)) {
            if (!empty($grades->peer->extra)) {
                $output .= $grades->peer->extra . '<br>';
            }
            foreach ($grades->peer->grades as $tgrade) {
                $output .= ' <span title="'.$tgrade->assessorname.'"
class="'.$tgrade->color.'">'. round($tgrade->grade, 2) .'/'. round($grades->peer->maxgrade, 2) .'</span>';
                $output .= '<input type="image" src="../images/lupa.png"
onClick="window.open(\''.$tgrade->assessmenturl.'\', \'popup\', \'scrollbars,resizable,width=780,height=500\');
return false;" title="'.get_string('view', 'block_evalcomix').'" alt="'.get_string('view', 'block_evalcomix').'"
width="15"/>';
                if (!empty($tgrade->deleteurl)) {
                    $output .= '<input type="image" width:16px" src="'.
                    $CFG->wwwroot.'/blocks/evalcomix/images/delete.png" title="'.
                    get_string('delete', 'block_evalcomix').'" alt="'. get_string('delete', 'block_evalcomix').'"
                    width="16"
                    onclick="if (confirm(\''.get_string('confirmdeleteassessment', 'block_evalcomix').'\'))
                    location.href=\''.$tgrade->deleteurl.'\';
                    window.opener.change_recarga();">';
                }
            }
        } else {
            $output .= '<i>' . get_string('nograde', 'block_evalcomix') . '</i>';
        }

        $output .= '
                        </td>
                        <td>
        ';

        if (isset($grades->peer->weighing)) {
            $output .= '<span>'.$grades->peer->weighing . '%</span>';
        }

        $output .= '
                        </td>
                    </tr>
                </tbody>
            </table>
            ';

        if (!empty($grades->legend)) {
            $output .= $grades->legend;
        }

        if (isset($grades->finalgrade) && $grades->finalgrade > -1) {
            $output .= '<div class="text-right font-weight-bold">'.
                $this->output->help_icon($grades->helpicon, 'block_evalcomix') .
                get_string('evalcomixgrade', 'block_evalcomix') .': '.
                format_float($grades->finalgrade, 2) .' / '. round($grades->maxgrade, 2) .'</div>';
        }
        return $output;
    }

    public static function display_main_menu($courseid, $option = 'assessment') {
        global $CFG;
        require_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
        $output = '';

        $active1 = '';
        $active2 = '';
        $active3 = '';
        $active4 = '';

        switch ($option) {
            case 'design':
                $active1 = 'active';
            break;
            case 'competency':
                $active3 = 'active';
            break;
            case 'report':
                $active4 = 'active';
            break;
            default:
                $active2 = 'active';
        }

        $output .= '
        <div class="mb-5 border-bottom">
            <ul class="nav nav-tabs">
        ';

        $context = context_course::instance($courseid);
        if (has_capability('moodle/grade:viewhidden', $context)) {
            $output .= '
                <li class="nav-item">
                    <a class="nav-link py-0 '.$active3.'" href="'.$CFG->wwwroot.'/blocks/evalcomix/competency/index.php?id='.
                    $courseid.'">'. get_string('compandout', 'block_evalcomix').'</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-0 '.$active1.'" href="'.$CFG->wwwroot.'/blocks/evalcomix/tool/index.php?id='.
                    $courseid.'">'. get_string('instruments', 'block_evalcomix').'</a>
                </li>
            ';
        }

        $output .= '
                <li class="nav-item">
                    <a class="nav-link py-0 '.$active2.'" href="'.$CFG->wwwroot.'/blocks/evalcomix/assessment/index.php?id='.
                    $courseid.'">'. get_string('evaluation', 'block_evalcomix').'</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-0 '.$active4.'" href="'.$CFG->wwwroot.'/blocks/evalcomix/competency/report.php?id='.
                    $courseid.'">'. get_string('compreport', 'block_evalcomix').'</a>
                </li>
            </ul>
        </div>

        <center>
         <!--  <div><img src="'. $CFG->wwwroot . BLOCK_EVALCOMIX_EVXLOGOROOT .'" width="230" alt="EvalCOMIX"/></div><br>-->
        </center>
        ';

        return $output;
    }

    public function display_assessmentsection_menu($courseid) {
        global $CFG;
        $output = '';

        $output = '
        <div class="mb-1">
            <ul class="nav nav-pills justify-content-center">
                <li class="nav-item">
                    <button type="button" class="mr-1" onclick="location.href=\''. $CFG->wwwroot .
                    '/blocks/evalcomix/graphics/index.php?mode=1&id='.$courseid .'\'">'.
                    get_string('graphics', 'block_evalcomix').'</button>
                </li>
        ';
        $context = context_course::instance($courseid);
        if (has_capability('moodle/block:edit', $context)) {
            $output .= '
                <li class="nav-item">
                    <button type="button" class="mr-1" onclick="location.href=\''.$CFG->wwwroot .
                    '/blocks/evalcomix/assessment/configuration.php?id='.$courseid.'\'">'.
                    get_string('settings', 'block_evalcomix').'</button>
                </li>
            ';
        }

        if (has_capability('moodle/site:viewreports', $context)) {
            $output .= '
                <li class="nav-item">
                    <button type="button" onclick="location.href=\''.$CFG->wwwroot .
                    '/blocks/evalcomix/assessment/index.php?id='.$courseid.'&e=1\'" data-toggle="tooltip" data-placement="right"
                    title="'.get_string('evaluationexporthelp', 'block_evalcomix').'">'.
                    get_string('export', 'block_evalcomix'). ' <i class="icon fa fa-question-circle text-info fa-fw mr-0"
                    ></i></button>
                </li>
            ';
        }
        $output .= '
            </ul>
        </div>
        ';

        return $output;
    }
}
