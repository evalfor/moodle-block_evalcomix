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

$courseid = required_param('id', PARAM_INT);
$id = required_param('a', PARAM_INT);
$assessorid = optional_param('as', 0, PARAM_INT);

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

require_course_login($course);

$context = context_course::instance($course->id);
require_capability('moodle/block:edit', $context);
$cm = get_coursemodule_from_id('', $id, 0, false, MUST_EXIST);

require_once($CFG->dirroot .'/blocks/evalcomix/lib.php');

$PAGE->set_url(new moodle_url('/blocks/evalcomix/assessment/users_form.php', array('id' => $courseid, 'a' => $cm->id)));
$PAGE->navbar->add('evalcomix', new moodle_url('../assessment/index.php?id='.$courseid));
$PAGE->set_pagelayout('popup');
$PAGE->set_context($context);

$PAGE->set_title(get_string('ratingsforitem', 'block_evalcomix'));
$PAGE->requires->css('/blocks/evalcomix/style/styles.css');
echo $OUTPUT->header();

$reportevalcomix = new block_evalcomix_grade_report($courseid, null, $context);
$users = $reportevalcomix->load_users(false);
$boldusers = array();
$options = '';

require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_allowedusers.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');

$activity = block_evalcomix_get_activity_data($cm);

if ($allowedusers = $DB->get_records('block_evalcomix_allowedusers', array('cmid' => $id))) {
    foreach ($allowedusers as $alloweduser) {
        $studentid = $alloweduser->studentid;
        $assessorid = $alloweduser->assessorid;
        if ($assessorid > 0) {
            if ($alloweduser->assessorid == $assessorid) {
                $options .= '<option>'.fullname($users[$studentid]).'</option>';
            }
        }
        $boldusers[$assessorid] = true;
    }
}

echo '<h1>'. $activity->name .'</h1>';

echo '
    <script type="text/javascript" src="../ajax.js"></script>
    <script type="text/javascript">window.opener.onunload=function() {close();};</script>
    <form id="assignform" method="post" action="assigning_users_form.php?id='.$courseid.'&a='.$cm->id.'"><div>
        <input type="hidden" name="sesskey" value="'.sesskey().'" />

        <table summary="" class="text-center roleassigntable generaltable generalbox boxaligncenter" cellspacing="0">
            <tr>
            <td id="existingcell">
                <p><label for="removeselect" class="font-weight-bold">'.
                get_string('assess_students', 'block_evalcomix').': </label></p>
                <div id="assessors">
                <select id="assessorid" name="assessorid" class="block_evalcomix_w_20" size="20"
                onclick="document.getElementById(\'submit\').disabled = false;doWork(\'targetstudents\', \'targetstudents.php\',
                \'u=\'+this.value+\'&id='.$courseid.'&a='.$cm->id.'\');">
';

foreach ($users as $user) {
    $selected = '';
    if ($user->id == $assessorid) {
        $selected = 'selected';
    }
    $userid = $user->id;
    $style = '';
    if (isset($boldusers[$userid])) {
        $style = 'class="text-danger font-weight-bold"';
    }
    echo '<option '.$style.' '.$selected.' value="'.$user->id.'">'.fullname($user).'</option>';
}

echo '
                </select>
                </div>
                <div class="text-left mt-1">
                    <label>'.get_string('search', 'block_evalcomix').'</label><input type="text" size="15" id="buscarid"
                     onkeyup="document.getElementById(\'submit\').disabled=true;doWork(\'assessors\', \'search.php\',
                     \'a='.$cm->id.'&id='.$courseid.'&search=\'+this.value)">
                </div>
            </td>
            <td id="buttonscell">

            </td>
            <td id="potentialcell">
               <p><label for="addselect" class="font-weight-bold">'.get_string('studentstoassess', 'block_evalcomix').':</label></p>
                <div id="targetstudents">
                    <select size="20" class="block_evalcomix_w_20">
                    '.$options.'
                    </select>
                </div>
                <div>
                    <input type="submit" id="submit" disabled value="'.get_string('add_delete_student', 'block_evalcomix').'">
                </div>
            </td>
            </tr>
        </table>
        </div></form>

';
?>

<?php
echo $OUTPUT->close_window_button();
echo $OUTPUT->footer();
