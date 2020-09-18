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
 * A page to configurate activity
 *
 * @package    block_evalcomix
 * @author Daniel Cabeza Sánchez <daniel.cabeza@uca.es>,
           Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>,
           Claudia Ortega Gómez <claudia.ortega@uca.es>
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../config.php');
$courseid      = required_param('id', PARAM_INT);        // Course id.
if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('nocourseid');
}
require_course_login($course);

$id = required_param('a', PARAM_INT);
$cm = get_coursemodule_from_id('', $id, 0, false, MUST_EXIST);

$context = context_course::instance($course->id);
require_capability('moodle/block:edit', $context);

require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');

$tools = block_evalcomix_tool::get_tools($courseid);
$activity = block_evalcomix_get_activity_data($cm);
$datas = block_evalcomix_get_evalcomix_activity_data($courseid, $cm);

$toolep = 0;
if (isset($datas['toolEP'])) {
    $toolep = $datas['toolEP'];
}
$weighingep = 70;
if (isset($datas['weighingEP'])) {
    $weighingep = $datas['weighingEP'];
}
$disabledep = '';

$toolae = 0;
if (isset($datas['toolAE'])) {
    $toolae = $datas['toolAE'];
}
$weighingae = 10;
if (isset($datas['weighingAE'])) {
    $weighingae = $datas['weighingAE'];
}
$disabledae = '';
$availableae = time();
if (isset($datas['availableAE'])) {
    $availableae = $datas['availableAE'];
}
$timedueae = time() + 7 * 24 * 3600;
if (isset($datas['timedueAE'])) {
    $timedueae = $datas['timedueAE'];
}

$toolei = 0;
if (isset($datas['toolEI'])) {
    $toolei = $datas['toolEI'];
}
$weighingei = 20;
if (isset($datas['weighingEI'])) {
    $weighingei = $datas['weighingEI'];
}
$disabledei = '';
$anonymousei = 0;
if (isset($datas['anonymousEI'])) {
    $anonymousei = $datas['anonymousEI'];
}

$alwaysvisibleei = 0;
if (isset($datas['alwaysvisibleEI'])) {
    $alwaysvisibleei = $datas['alwaysvisibleEI'];
}

$whoassessesei = 0;
if (isset($datas['whoassessesEI'])) {
    $whoassessesei = $datas['whoassessesEI'];
}

$availableei = time();
if (isset($datas['availableEI'])) {
    $availableei = $datas['availableEI'];
}
$timedueei = time() + 7 * 24 * 3600;
if (isset($datas['timedueEI'])) {
    $timedueei = $datas['timedueEI'];
}


if (!isset($toolep) || !$toolep) {
    $disabledep = 'disabled';
}
if (!isset($toolae) || !$toolae) {
    $disabledae = 'disabled';
}
if (!isset($toolei) || !$toolei) {
    $disabledei = 'disabled';
}


global $OUTPUT;

$PAGE->set_url(new moodle_url('/blocks/evalcomix/assessment/activity_edit_form.php', array('id' => $courseid)));
$PAGE->set_context($context);
$PAGE->set_pagetype('course');
$PAGE->set_title(get_string('pluginname', 'block_evalcomix'));
$PAGE->set_heading(get_string('pluginname', 'block_evalcomix'));
$PAGE->navbar->add(get_string('courses'), $CFG->wwwroot .'/course');
$PAGE->navbar->add($course->shortname, $CFG->wwwroot .'/course/view.php?id=' . $courseid);
$PAGE->navbar->add('evalcomix', new moodle_url('../assessment/index.php?id='.$courseid));
$PAGE->set_pagelayout('report');
$PAGE->requires->css('/blocks/evalcomix/style/styles.css');

echo $OUTPUT->header();
echo '
        <center>
            <div><img src="'. $CFG->wwwroot . BLOCK_EVALCOMIX_EVXLOGOROOT .'" width="230" alt="EvalCOMIX"/></div>
            <div><input type="button" value="'.get_string('designsection', 'block_evalcomix').'"
			onclick="location.href=\''. $CFG->wwwroot .'/blocks/evalcomix/tool/index.php?id='.$courseid .'\'"/></div>
        </center>
';


echo '
        <script>
            function disabling(element, slavetag){
                if(element.value != 0)
                    document.getElementById(slavetag).disabled = false;
                else
                    document.getElementById(slavetag).disabled = true;
            }
            function disabling_group(element, slavetag){
                if(element.value != 0){
                    document.getElementById("day_" + slavetag).disabled = false;
                    document.getElementById("month_" + slavetag).disabled = false;
                    document.getElementById("year_" + slavetag).disabled = false;
                    document.getElementById("hour_" + slavetag).disabled = false;
                    document.getElementById("minute_" + slavetag).disabled = false;
                }
                else{
                    document.getElementById("day_" + slavetag).disabled = true;
                    document.getElementById("month_" + slavetag).disabled = true;
                    document.getElementById("year_" + slavetag).disabled = true;
                    document.getElementById("hour_" + slavetag).disabled = true;
                    document.getElementById("minute_" + slavetag).disabled = true;
                }
            }

            function check_weighing(element)
            {
                pon_EP = null;
                pon_AE = null;
                pon_EI = null;
                element_EP = document.getElementById("pon_EP");
                element_AE = document.getElementById("pon_AE");
                element_EI = document.getElementById("pon_EI");
                sum = 0;
                existanymode = false;

                if(document.getElementById("pon_EP").disabled == false){
                    pon_EP = element_EP.value;
                    sum += parseInt(pon_EP);
                    existanymode = true;
                }
                if(document.getElementById("pon_AE").disabled == false){
                    pon_AE = element_AE.value;
                    sum += parseInt(pon_AE);
                    existanymode = true;
                }
                if(document.getElementById("pon_EI").disabled == false){
                    pon_EI = element_EI.value;
                    sum += parseInt(pon_EI);
                    existanymode = true;
                }
                //alert("pon_EP:"+pon_EP+" pon_AE:"+pon_AE+" pon_EI:"+pon_EI);
                //alert(sum);
                element_EI.style.color = "";
                element_AE.style.color = "";
                element_EP.style.color = "";
                element_EI.style.border = "";
                element_AE.style.border = "";
                element_EP.style.border = "";
                document.getElementById("error_weighing_greater").style.visibility = "hidden";
                document.getElementById("error_weighing_less").style.visibility = "hidden";
                document.getElementById("error_weighing_less").style.height = 0;
                document.getElementById("error_weighing_greater").style.height = "0";
                if(sum < 100 && existanymode == true){
                    if(element){
                        element.style.color =  "#ff0000";
                        element.style.border = "1px solid #ff0000";
                    }
                    document.getElementById("error_weighing_less").style.visibility = "visible";
                    document.getElementById("error_weighing_less").style.height = "2em";
                    return false;
                }
                else if(sum > 100){
                    if(element){
                        element.style.color =  "#ff0000";
                        element.style.border = "1px solid #ff0000";
                    }
                    document.getElementById("error_weighing_greater").style.visibility = "visible";
                    document.getElementById("error_weighing_less").style.height = "2em";
                    return false;
                }
                return true;
            }

        </script>
        <div>
            <h1 class="text-center">'. $activity->name .'</h1>
            <form method="post" action="index.php?id='.$courseid.'">
                <input type="hidden" id="cmid" name="cmid" value='.$cm->id.'>
                <input type="hidden" id="maxgrade" name="maxgrade" value="100">
                <input type="hidden" id="sesskey" name="sesskey" value="'.sesskey().'">
                <fieldset class="clearfix border border-secondary" id="Instrument">
                <legend class="ftoggler font-weight-bold">'.
                get_string('selinstrument', 'block_evalcomix'). $OUTPUT->help_icon('selinstrument', 'block_evalcomix').'</legend>
                    <div class="p-3">
                        <table class="w-100">';
if (empty($tools)) {
    echo '
                        <tr>
                            <td colspan=2 class="text-center text-primary">
                                '. get_string('alertnotools', 'block_evalcomix').'
                                <br><a class="text-primary font-weight-bold" href="'.$CFG->wwwroot.
                                '/blocks/evalcomix/tool/index.php?id='.
                                $courseid.'">'.
                                 get_string('designsection', 'block_evalcomix').'</a>";
                                </td>
                            </tr>';
}
echo '

<!---------------------------------------------------------------------------------------------------->
<!--TEACHER-ASSESSMENT-------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------->

                            <tr>
                                <td class="text-right">' . get_string('teachermodality', 'block_evalcomix').
                                $OUTPUT->help_icon('teachermodality', 'block_evalcomix') . '</td>
                                <td>
                                    <select class="form-control" name="toolEP" id="id_toolEP"
                                    onchange="javascript:disabling(this, \'pon_EP\');">
                                        <option value="0">' . get_string('nuloption', 'block_evalcomix') . '</option>
    ';

foreach ($tools as $idtool => $titletool) {
    if ($titletool == '-000_1') {
        continue;
    }
    $checkedeptool = '';
    if ($toolep && $idtool == $toolep->id) {
        $checkedeptool = 'selected = "selected"';
    }
    echo '
                                        <option value="'. $idtool .'" '. $checkedeptool .'>'. $titletool .'</option>
    ';
}

echo '
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-right">' . get_string('pon_EP', 'block_evalcomix').
                                $OUTPUT->help_icon('pon_EP', 'block_evalcomix') . '</td>
                                <td>
';
$extraep = $disabledep . ' onchange="javascript:check_weighing(this);"';
echo block_evalcomix_add_select_range('pon_EP', 0, 100, $weighingep, $extraep);

echo '

                                </td>
                            </tr>

                            <tr><td/><td/></tr>
<!---------------------------------------------------------------------------------------------------->
<!--SELF-ASSESSMENT----------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------->
                            <tr>
                                <td class="text-right">' . get_string('selfmodality', 'block_evalcomix').
                                 $OUTPUT->help_icon('selfmodality', 'block_evalcomix') . '</td>
                                <td><hr>
                                    <select class="form-control" name="toolAE" id="id_toolAE"
                                    onchange="javascript:disabling(this, \'pon_AE\');
									disabling_group(this, \'available_AE\');disabling_group(this, \'timedue_AE\');">
                                        <option value="0">' . get_string('nuloption', 'block_evalcomix') . '</option>
    ';

foreach ($tools as $idtool => $titletool) {
    if ($titletool == '-000_1') {
        continue;
    }
    $checkedaetool = '';
    if ($toolae && $idtool == $toolae->id) {
        $checkedaetool = 'selected = "selected"';
    }
    echo '
                                        <option value="'. $idtool .'" '.$checkedaetool.'>'. $titletool .'</option>
    ';
}

echo '
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-right">' . get_string('pon_AE', 'block_evalcomix').
                                $OUTPUT->help_icon('pon_AE', 'block_evalcomix') . '</td>
                                <td>
';

$extraae = $disabledae . ' onchange="javascript:check_weighing(this);"';
echo block_evalcomix_add_select_range('pon_AE', 0, 100, $weighingae, $extraae);

echo '

                                </td>
                            </tr>

                            <tr>
                                <td class="text-right">' . get_string('availabledate_AE', 'block_evalcomix').
                                $OUTPUT->help_icon('availabledate_AE', 'block_evalcomix') . '</td>
                                <td>
';

echo block_evalcomix_add_date_time_selector('available_AE', $availableae, $disabledae) . '<br>';

echo '
                                </td>
                            </tr>

                            <tr>
                                <td class="text-right">' . get_string('timedue_AE', 'block_evalcomix').
                                $OUTPUT->help_icon('timedue_AE', 'block_evalcomix') . '</td>
                                <td>
';

echo block_evalcomix_add_date_time_selector('timedue_AE', $timedueae, $disabledae) . '<br>';

echo '
                                </td>
                            </tr>

                            <tr><td/><td/></tr>

<!---------------------------------------------------------------------------------------------------->
<!--PEER-ASSESSMENT----------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------->

                            <tr>
                                <td class="text-right">' . get_string('peermodality', 'block_evalcomix').
                                $OUTPUT->help_icon('peermodality', 'block_evalcomix') . '</td>
                                <td><hr>
                                    <select class="form-control" name="toolEI" id="toolEI"
                                    onchange="javascript:disabling(this, \'pon_EI\');
                                    disabling(this,\'anonymousEI\');disabling_group(this, \'available_EI\');
                                    disabling_group(this,\'timedue_EI\');disabling(this, \'alwaysvisibleEI\');
                                    disabling(this, \'anystudent_EI\');disabling(this, \'groups_EI\');
                                    disabling(this, \'specificstudents_EI\');">
                                        <option value="0">' . get_string('nuloption', 'block_evalcomix') . '</option>
    ';

foreach ($tools as $idtool => $titletool) {
    if ($titletool == '-000_1') {
        continue;
    }
    $checkedeitool = '';
    if ($toolei && $idtool == $toolei->id) {
        $checkedeitool = 'selected = "selected"';
    }
    echo '
                                        <option value="'. $idtool .'" '.$checkedeitool.'>'. $titletool .'</option>
    ';
}

echo '
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-right">' . get_string('pon_EI', 'block_evalcomix').
                                $OUTPUT->help_icon('pon_EI', 'block_evalcomix') . '</td>
                                <td>
';

$extraei = $disabledei . ' onchange="javascript:check_weighing(this);"';
echo block_evalcomix_add_select_range('pon_EI', 0, 100, $weighingei, $extraei);

echo '

                                </td>
                            </tr>

                            <tr>
                                <td class="text-right">' . get_string('anonymous_EI', 'block_evalcomix').
                                $OUTPUT->help_icon('anonymous_EI', 'block_evalcomix') . '</td>
                                <td>
';
$checked = '';
if ($anonymousei == 1) {
    $checked = 'checked';
}

echo '
                                    <input type="checkbox" ' . $checked . ' '. $disabledei.'
                                    id="anonymousEI" name="anonymousEI">
                                </td>
                            </tr>


                            <tr>
                                <td class="text-right">' . get_string('availabledate_EI', 'block_evalcomix').
                                $OUTPUT->help_icon('availabledate_EI', 'block_evalcomix') . '</td>
                                <td>
';

echo block_evalcomix_add_date_time_selector('available_EI', $availableei, $disabledei) . '<br>';

echo '
                                </td>
                            </tr>

                            <tr>
                                <td class="text-right">' . get_string('timedue_EI', 'block_evalcomix').
                                $OUTPUT->help_icon('timedue_EI', 'block_evalcomix') . '</td>
                                <td>
';

echo block_evalcomix_add_date_time_selector('timedue_EI', $timedueei, $disabledei) . '<br>';

echo '
                                </td>
                            </tr>

                            <tr>
                                <td class="text-right">' . get_string('alwaysvisible_EI', 'block_evalcomix').
                                $OUTPUT->help_icon('alwaysvisible_EI', 'block_evalcomix') . '</td>
                                <td>
';
$checked = '';
if ($alwaysvisibleei == 1) {
    $checked = 'checked';
}
echo '
                                    <input type="checkbox" ' . $checked . ' '. $disabledei . '
                                    id="alwaysvisibleEI" name="alwaysvisibleEI">
                                </td>
                            </tr>

                            <tr>
                                <td class="text-right align-top">'
                               .get_string('whoassesses_EI', 'block_evalcomix') .
                               $OUTPUT->help_icon('whoassesses_EI', 'block_evalcomix') . '</td>
                                <td>
';

$checked0 = '';
$checked1 = '';
$checked2 = '';
$disabled2 = 'disabled';
switch ($whoassessesei) {
    case '0': $checked0 = 'checked';
    break;
    case '1': $checked1 = 'checked';
    break;
    case '2': $checked2 = 'checked';$disabled2 = '';
    break;
}
echo '
                        <div><input type="radio" '.$checked0.' id="anystudent_EI" '. $disabledei .'
                        name="whoassessesEI" value="0"
                        onclick="document.getElementById(\'assignstudents\').disabled = true;"> <label for="anystudent_EI">'.
                        get_string('anystudent_EI', 'block_evalcomix').'</label>
                        </div>
                        <div><input type="radio" '.$checked1.' id="groups_EI" '. $disabledei .'
                        name="whoassessesEI" value="1"
                        onclick="document.getElementById(\'assignstudents\').disabled = true;"> <label for="groups_EI">'.
                        get_string('groups_EI', 'block_evalcomix').'</label></div>
                        <div><input type="radio" '.$checked2.' id="specificstudents_EI" '. $disabledei .'
                        name="whoassessesEI" value="2"
                        onclick="document.getElementById(\'assignstudents\').disabled = false;"> <label for="specificstudents_EI">'.
                        get_string('specificstudents_EI', 'block_evalcomix').'</label></div>
                        <div><input type="button" '.$disabled2.' id="assignstudents"
                        onclick="window.open(\'users_form.php?id='.$courseid.'&a='.$cm->id.'\', \'popup\',
                        \'scrollbars,resizable,width=780,height=500\'); return false;"
                        value="' . get_string('assignstudents_EI', 'block_evalcomix') .'"></div>
                         </td>
                        </tr>

                        </table>
                    </div>
                    <div id="error_weighing" class="text-center text-danger">
                        <div id="error_weighing_less" class="border border-danger block_evalcomix_invisible">
                        El porcentaje total es MENOR que 100%. La suma de porcentajes debe ser 100%</div>
                        <div id="error_weighing_greater" class="border border-danger block_evalcomix_invisible">
                        El porcentaje total es MAYOR que 100%. La suma de porcentajes debe ser 100%</div>
                    </div>
                    <br>
                    <div class="text-center">
                        <input type="submit" id="save" name="save" value="'. get_string('save', 'block_evalcomix') .'"
                        onclick="return check_weighing();">
                        <input type="submit" id="cancel" name="cancel" value="'. get_string('cancel', 'block_evalcomix') .'">
                    </div>
                </fieldset>
            </form>
        </div>
';

echo $OUTPUT->footer();
