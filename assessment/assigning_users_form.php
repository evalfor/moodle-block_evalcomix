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
$courseid      = required_param('id', PARAM_INT);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
require_course_login($course);

$id = required_param('a', PARAM_INT);
$assessorid = required_param('assessorid', PARAM_INT);
$add = optional_param('add', 0, PARAM_INT);
$remove = optional_param('remove', 0, PARAM_INT);
$potentialuser = optional_param_array('pu', array(), PARAM_RAW);
$allowed = optional_param_array('au', array(), PARAM_RAW);

require_sesskey();

$context = context_course::instance($course->id);
require_capability('moodle/block:edit', $context);

$cm = get_coursemodule_from_id('', $id, 0, false, MUST_EXIST);

global $OUTPUT, $CFG;
require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_allowedusers.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');

$PAGE->set_url(new moodle_url('/blocks/evalcomix/assessment/users_form.php', array('id' => $courseid, 'a' => $cm->id)));
$PAGE->set_pagelayout('popup');
$PAGE->set_context($context);
$PAGE->set_title('EvalCOMIX');
$PAGE->requires->css('/blocks/evalcomix/style/styles.css');
echo $OUTPUT->header();

$reportevalcomix = new block_evalcomix_grade_report($courseid, null, $context);
$users = $reportevalcomix->load_users(false);

$activity = block_evalcomix_get_activity_data($cm);
echo '<h3>'.$activity->name .'</h3>';
echo '<h4 class="text-primary">'.fullname($users[$assessorid]).'</h4>';

if (isset($add) && isset($potentialuser) && is_array($potentialuser)) {
    foreach ($potentialuser as $puser) {
        $params = array('cmid' => $cm->id, 'assessorid' => $assessorid, 'studentid' => $puser);
        if (!$alloweduser = $DB->get_record('block_evalcomix_allowedusers', $params)) {
            $DB->insert_record('block_evalcomix_allowedusers', $params);
        }
    }
}

if (isset($remove) && isset($allowed) && is_array($allowed)) {
    foreach ($allowed as $auser) {
        $params = array('cmid' => $cm->id, 'assessorid' => $assessorid, 'studentid' => $auser);
        if ($alloweduser = $DB->get_record('block_evalcomix_allowedusers', $params)) {
            $DB->delete_records('block_evalcomix_allowedusers', array('id' => $alloweduser->id));
        }
    }
}

echo '
    <script type="text/javascript" src="../ajax.js"></script>
    <script type="text/javascript">window.opener.onunload=function(){close();};</script>
    <form id="assignform" method="post" action="assigning_users_form.php?id='.$courseid.'&a='.$cm->id.'
    &assessorid='.$assessorid.'"><div>
        <input type="hidden" name="sesskey" value="'.sesskey().'" />

        <div class="text-center">
            <div><input type="button" value="'.get_string('back', 'block_evalcomix').'"
            onclick="location.href = \'users_form.php?id='.$courseid.'&a='.$cm->id.'&as='.$assessorid.'\'"></div><br>
                <table summary="" class="roleassigntable generaltable generalbox boxaligncenter" cellspacing="0">
                    <tr>
                        <td id="existingcell">
                            <p><label for="removeselect" class="font-weight-bold">'.
                            get_string('studentstoassess', 'block_evalcomix').': </label></p>
                            <select multiple name="au[]" class="block_evalcomix_w_20" size="20"
                            onchange="document.getElementById(\'remove\').disabled=false">';

$allowedusershash = array();
$allowedusershash[$assessorid] = true;
if ($allowedusers = $DB->get_records('block_evalcomix_allowedusers', array('cmid' => $id, 'assessorid' => $assessorid))) {
    foreach ($allowedusers as $alloweduser) {
        $userid = $alloweduser->studentid;
        $allowedusershash[$userid] = true;
        echo '<option value="'.$userid.'">'.fullname($users[$userid]).'</option>';
    }
}

echo '
                            </select>
                        </td>
                        <td id="buttonscell">
                            <div id="addcontrols">
                                <input name="add" disabled id="add" type="submit" value="'.
                                $OUTPUT->larrow().'&nbsp;'.get_string('add') .'" title="'.print_string('add').'" /><br />
                            </div>

                            <div id="removecontrols">
                                <input name="remove" disabled id="remove" type="submit" value="'.
                                get_string('remove').'&nbsp;'.$OUTPUT->rarrow() .'" title="'.print_string('remove').'" />
                            </div>
                        </td>
                        <td id="potentialcell">
                            <p><label for="addselect" class="font-weight-bold">'.
                            get_string('potentialstudents', 'block_evalcomix').':</label></p>
                            <div id="potential">
                                <select multiple name="pu[]" class="block_evalcomix_w_20" size="20"
                                onchange="document.getElementById(\'add\').disabled=false">';

foreach ($users as $user) {
    $userid = $user->id;
    if (!isset($allowedusershash[$userid])) {
        echo '<option value="'.$user->id.'">'.fullname($user).'</option>';
    }
}

echo '
                                </select>
                            </div>
                            <div class="text-left mt-1">
                                <label>'.get_string('search', 'block_evalcomix').'</label><input type="text"
                                size="15" id="buscarid" onkeyup="document.getElementById(\'add\').disabled=true;
                                doWork(\'potential\', \'search.php\', \'as='.$assessorid.'&t=potential&a='.$cm->id.
                                '&id='.$courseid.'&search=\'+this.value)">
                            </div>
                        </td>
                        </tr>
                    </table>
                </div>
            </div></form>
';

echo $OUTPUT->close_window_button();
echo $OUTPUT->footer();
