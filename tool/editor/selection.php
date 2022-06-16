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
 * @author     Daniel Cabeza Sánchez <info@ansaner.net>
 */

require_once('../../../../config.php');
$courseid = required_param('courseid', PARAM_INT);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
require_course_login($course);
$context = context_course::instance($courseid);
$type = required_param('type', PARAM_ALPHA);
$id = required_param('identifier', PARAM_ALPHANUM);

require_capability('moodle/grade:viewhidden', $context);
unset($SESSION->tool);

if ($type == 'new') {
    $SESSION->id = $id;
    unset($SESSION->open);
    $PAGE->set_url(new moodle_url('/blocks/evalcomix/tool/editor/selection.php', array('id' => $id, 'type' => $type,
        'courseid' => $courseid)));
    $PAGE->set_pagelayout('popup');
    // Print the header.
    $PAGE->set_context($context);
    $PAGE->set_title(get_string('pluginname', 'block_evalcomix'));
    $PAGE->set_heading(get_string('pluginname', 'block_evalcomix'));
    $PAGE->set_pagelayout('popup');
    $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/evalcomix/style/main.css'));
    echo $OUTPUT->header();
    echo '
<div id="bgmenu">
    <div>'. get_string('windowselection', 'block_evalcomix') . '</div>
    <div class="text-center bg-white"><img src="'.$CFG->wwwroot.'/blocks/evalcomix/images/evalcomix.jpg"
    alt="EvalCOMIX"></div>
    <form action="generator.php" method="post">
        <input type="hidden" name="courseid" value="'.$courseid.'">
        <input type="hidden" name="identifier" value="'.$id.'">
        <div id="menu">
            <div class="mb-3 ml-1">'.get_string('selecttool', 'block_evalcomix').'</div>
                <ul class="list-group m-1">
                    <li class="list-group-item pt-0 pb-0">
                        <input type="radio" name="type" id="escala" checked value="escala"/> <label
                        class="w-75 h-100" for="escala">'.
                            get_string('ratescale', 'block_evalcomix').'</label>
                    </li>
                    <li class="list-group-item pt-0 pb-0">
                        <input type="radio" name="type" id="listaescala" value="listaescala"/> <label class="w-75 h-100"
                        for="listaescala">'. get_string('listrate', 'block_evalcomix').'</label>
                    </li>
                    <li class="list-group-item pt-0 pb-0">
                        <input type="radio" name="type" id="lista" value="lista"/> <label class="w-75 h-100" for="lista">'.
                            get_string('checklist', 'block_evalcomix').'</label>
                    </li>
                    <li class="list-group-item pt-0 pb-0">
                        <input type="radio" name="type" id="rubrica" value="rubrica"/> <label class="w-75 h-100" for="rubrica">'.
                            get_string('rubric', 'block_evalcomix').'</label>
                    </li>
                    <li class="list-group-item pt-0 pb-0">
                        <input type="radio" name="type" id="diferencial" value="diferencial"/> <label class="w-75 h-100"
                        for="diferencial">'. get_string('differentail', 'block_evalcomix').'</label>
                    </li>
                    <li class="list-group-item pt-0 pb-0">
                        <input type="radio" name="type" id="mixta" value="mixta"/> <label class="w-75 h-100" for="mixta">'
                            .get_string('mix', 'block_evalcomix').'</label>
                    </li>
                    <li class="list-group-item pt-0 pb-0">
                        <input type="radio" name="type" id="argumentario" value="argumentario"/> <label class="w-75 h-100"
                        for="argumentario">'. get_string('argument', 'block_evalcomix').'</label>
                    </li>
                    <li class="list-group-item pt-0 pb-0">
                        <input type="radio" name="type" id="importar" value="importar"/> <label class="w-75 h-100" for="importar">'.
                            get_string('import', 'block_evalcomix').'</label>
                    </li>
                </ul>
            </div>
            <center><input type="button" name="submit" id="submit" value="'.get_string('accept', 'block_evalcomix').
                '" onclick=\'javascript:var valores=document.getElementsByName("type");
                for(var i=0; i<valores.length; i++){
                    if(valores[i].checked){tipo=valores[i].id;location.replace("generator.php?identifier='.
                    $id.'&courseid='.$courseid.'&type="+tipo)}}\'/>
        </form>
    </div>
';
} else if ($type == 'open') {
    $SESSION->id = $id;
    $SESSION->open = 1;

    require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
    if ($toolxml = block_evalcomix_webservice_client::get_tool($id)) {
        $xml = simplexml_load_string($toolxml);
        require('inicio.php');
        $tool->import($xml, $id);
        $tool->display_header(array('courseid' => $courseid));
        $tool->display_body(array('courseid' => $courseid));
        $tool->display_footer();
        $toolobj = serialize($tool);
        $SESSION->tool = $toolobj;
    } else {
        die('This Tool is not enabled');
    }
}
