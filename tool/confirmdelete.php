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
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

require_once('../../../config.php');

$courseid = required_param('id', PARAM_INT);
$tid = required_param('tool', PARAM_INT);

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
require_course_login($course);
$context = context_course::instance($course->id);
require_capability('moodle/grade:viewhidden', $context);

$count = 0;
if ($tooldelete = $DB->get_record('block_evalcomix_tools', array('id' => $tid))) {
    $sql = "SELECT a.*
        FROM {block_evalcomix_assessments} a, {block_evalcomix_modes} m
        WHERE a.modeid = m.id AND m.toolid = :toolid";

    if ($toolassessments = $DB->get_records_sql($sql, array('toolid' => $tid))) {
        $count = count($toolassessments);
    }
} else {
    redirect(new moodle_url($CFG->wwwroot . '/blocks/evalcomix/tool/index.php', array('id' => $courseid)));
}

$PAGE->set_url(new moodle_url('/blocks/evalcomix/tool/confirmdelete.php', array('id' => $courseid, 'tool' => $tid)));
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'block_evalcomix'));
$PAGE->set_heading(get_string('pluginname', 'block_evalcomix'));
$PAGE->navbar->add('evalcomix', new moodle_url('../assessment/index.php?id='.$courseid));
$PAGE->set_pagelayout('popup');

echo $OUTPUT->header();

echo  '<h3 class="mb-5">'.get_string('instruments', 'block_evalcomix').'</h3>';
echo get_string('lastconfirmdeletetool', 'block_evalcomix', $count);
echo '<br><br><div>
<ul><li>'. get_string('title', 'block_evalcomix') .': '. $tooldelete->title . '</li>
<li>'. get_string('type', 'block_evalcomix') .': '.get_string($tooldelete->type, 'block_evalcomix').'</li></ul>
</div>';
echo '<div>
<center>
<button class="btn btn-primary" type="button" onclick="location.href=\''. $CFG->wwwroot .
'/blocks/evalcomix/tool/index.php?id='.$courseid.'\';">'. get_string('cancel') .'</button>
<button class="btn btn-secondary" type="button" onclick="location.href=\''. $CFG->wwwroot .
'/blocks/evalcomix/tool/index.php?id='.$courseid.'&tool='.$tid.'&confirmdelete=1\';">'. get_string('delete') .'</button>
</center></div>';
echo $OUTPUT->footer();
