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

require_once('../../../config.php');
global $CFG;
require_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');

$courseid      = required_param('id', PARAM_INT);
$mode = optional_param('mode', '', PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('nocourseid');
}

global $OUTPUT;

$PAGE->set_url(new moodle_url('/blocks/evalcomix/graphics/index.php', array('id' => $courseid)));
$PAGE->set_pagelayout('incourse');
// Print the header.
$PAGE->navbar->add('evalcomix', new moodle_url('../assessment/index.php?id='.$courseid));

$buttons = null;

require_login($course);

$context = context_course::instance($course->id);

print_grade_page_head($course->id, 'report', 'grader', null, false, $buttons, false);

echo '
    <center>
        <div><img src="'. $CFG->wwwroot . EVXLOGOROOT .'" width="230" alt="EvalCOMIX"/></div><br>
        <div><input type="button" style="color:#333333" value="'.
        get_string('assesssection', 'block_evalcomix').'" onclick="location.href=\''.
        $CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'\'"/></div><br>
    </center>

    <div>
        <ul style="margin:5px">
            <li class="pestania" id="pstPT"><a href="?mode=1"><a class="enlacetitulografica"
            href="?mode=1&id='.$courseid.'">Gráfica Tarea</a></li>
            <li class="pestania" id="pstPA"><a class="enlacetitulografica"
            href="?mode=2&id='.$courseid.'">Gráfica Alumnado</a></li>
            <li class="pestania" id="pstPAtr" style="display:none;"><a class="enlacetitulografica"
            href="?mode=3">Gráfica Perfil Atributos</a></li>
            <li class="pestania" id="pstPAleat" style="display:none;"><a class="enlacetitulografica"
            href="?mode=33">Modo Aleatorio</a></li>
        </ul>
    </div>

    <div style="float: left; width: 100%; padding-top: 15px; border: solid 1px #C0C0C0">
';

$graphic = 'graphic';
require_once('graphic_google.php');

$idcurso = $courseid;
switch ($mode){
    case '1': {
        $graphic::draw_perfil_tarea($idcurso);
    } break;

    case '2': {
        $graphic::draw_perfil_alumnado($idcurso);
    } break;

    case '3': {
        $graphic::draw_perfil_atributos($idcurso);
    } break;

    default:
        $minvalor = 0;
        $maxvalor = 100;
        $arraydata = array(
            array("profesor", 75 , 80, 65),
            array("autoevaluacion", 55 , 25, 76),
            array("entre iguales", 67 , 65, 43)
        );

        $blnincluirlimites = true;
        $blnincluirdispersion = true;

        $titulo = get_string('titlegraficbegin', 'block_evalcomix');
        $valorlimites = 2;

        $graphic::draw_perfil_tarea_sola($titulo,
                                         $minvalor,
                                         $maxvalor,
                                         $arraydata,
                                         $blnincluirlimites,
                                         $blnincluirdispersion,
                                         $valorlimites);
}
echo '

    </div>';

echo $OUTPUT->footer();
