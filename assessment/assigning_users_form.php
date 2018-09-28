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
require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');

$courseid      = required_param('id', PARAM_INT);
$id = required_param('a', PARAM_INT);
$assessorid = required_param('assessorid', PARAM_INT);
$add = optional_param('add', 0, PARAM_INT);
$remove = optional_param('remove', 0, PARAM_INT);
$potentialuser = optional_param_array('pu', array(), PARAM_RAW);
$allowed = optional_param_array('au', array(), PARAM_RAW);

require_login($courseid);

if ($id) {
    $cm = get_coursemodule_from_id('', $id, 0, false, MUST_EXIST);
    if (!$course = $DB->get_record('course', array('id' => $courseid))) {
        print_error('nocourseid');
    }
}

global $OUTPUT, $CFG;
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_allowedusers.php');

require_login($course);
$context = context_course::instance($course->id);

require_capability('moodle/block:edit', $context);

$PAGE->set_url(new moodle_url('/blocks/evalcomix/assessment/users_form.php', array('id' => $courseid, 'a' => $cm->id)));
$PAGE->set_pagelayout('popup');
$PAGE->set_context($context);

$PAGE->set_title('EvalCOMIX');
echo $OUTPUT->header();

$context = context_course::instance($courseid);

$reportevalcomix = new grade_report_evalcomix($courseid, null, $context);
$users = $reportevalcomix->load_users(false);

$activity = get_activity_data($cm);
echo '<div style="font-weight:bold; font-size:16px">'.$activity->name .'</div>';
echo '<div style="font-weight:bold; font-size:14px;color:#00f">'.fullname($users[$assessorid]).'</div>';

if (isset($add) && isset($potentialuser) && is_array($potentialuser)) {
    foreach ($potentialuser as $puser) {
        $params = array('cmid' => $cm->id, 'assessorid' => $assessorid, 'studentid' => $puser);
        if (!$alloweduser = evalcomix_allowedusers::fetch($params)) {
            $alloweduser = new evalcomix_allowedusers($params);
            $alloweduser->insert();
        }
    }
}

if (isset($remove) && isset($allowed) && is_array($allowed)) {
    foreach ($allowed as $auser) {
        $params = array('cmid' => $cm->id, 'assessorid' => $assessorid, 'studentid' => $auser);
        if ($alloweduser = evalcomix_allowedusers::fetch($params)) {
            $alloweduser->delete();
        }
    }
}

echo '
    <script type="text/javascript" src="../ajax.js"></script>
    <script type="text/javascript">window.opener.onunload=function(){close();};</script>
    <form id="assignform" method="post" action="assigning_users_form.php?id='.$courseid.'&a='.$cm->id.'
    &assessorid='.$assessorid.'"><div>
        <input type="hidden" name="sesskey" value="'.sesskey().'" />

        <div style="text-align:center">
            <div><input type="button" value="'.get_string('back', 'block_evalcomix').'"
            onclick="location.href = \'users_form.php?id='.$courseid.'&a='.$cm->id.'&as='.$assessorid.'\'"></div><br>
                <table summary="" class="roleassigntable generaltable generalbox boxaligncenter" cellspacing="0">
                    <tr>
                        <td id="existingcell">
                            <p><label for="removeselect" style="font-weight:bold">'.
                            get_string('studentstoassess', 'block_evalcomix').': </label></p>
                            <select multiple name="au[]" style="width:20em" size="20"
                            onchange="document.getElementById(\'remove\').disabled=false">';

$allowedusershash = array();
$allowedusershash[$assessorid] = true;
if ($allowedusers = evalcomix_allowedusers::fetch_all(array('cmid' => $id, 'assessorid' => $assessorid))) {
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
                            <div style="margin-top: 9em" id="addcontrols">
                                <input name="add" disabled id="add" type="submit" value="'.
                                $OUTPUT->larrow().'&nbsp;'.get_string('add') .'" title="'.print_string('add').'" /><br />
                            </div>

                            <div id="removecontrols">
                                <input name="remove" disabled id="remove" type="submit" value="'.
                                get_string('remove').'&nbsp;'.$OUTPUT->rarrow() .'" title="'.print_string('remove').'" />
                            </div>
                        </td>
                        <td id="potentialcell">
                            <p><label for="addselect" style="font-weight:bold">'.
                            get_string('potentialstudents', 'block_evalcomix').':</label></p>
                            <div id="potential">
                                <select multiple name="pu[]" style="width:20em" size="20"
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
                            <div style="text-align:left;margin-top:3px">
                                <label>'.get_string('search', 'block_evalcomix').'</label><input type="text"
                                size="15" id="buscarid" onkeyup="document.getElementById(\'add\').disabled=true;
                                doWork(\'potential\', \'search.php\', \'as='.$assessorid.'&t=potential&a='.$cm->id.'
                                &id='.$courseid.'&search=\'+this.value)">
                            </div>
                        </td>
                        </tr>
                    </table>
                </div>
            </div></form>
';

echo $OUTPUT->close_window_button();
echo $OUTPUT->footer();