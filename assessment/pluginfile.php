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
$relativepath = get_file_argument();
$args = explode('/', ltrim($relativepath, '/'));

if (count($args) < 3) { // Always at least context, component and filearea.
    throw new \moodle_exception('invalidarguments');
}

$contextid = (int)array_shift($args);
$component = clean_param(array_shift($args), PARAM_COMPONENT);
list($context, $course, $cm) = get_context_info_array($contextid);

require_course_login($course);
require_capability('block/evalcomix:view', $context);

$mode = block_evalcomix_grade_report::get_type_evaluation($USER->id, $course->id);
if ($mode == 'teacher' || (($mode == 'self' || $mode == 'peer'))) {
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');
    $gradereport = new block_evalcomix_grade_report($course->id, null, $context);
    $canseeactivity = $gradereport->student_can_assess($USER, $cm);
    if ($canseeactivity) {
        $fs = get_file_storage();
        if ($task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cm->id))) {
            if ($component == 'assignsubmission_file' || $component == 'mod_workshop') {
                $dir = core_component::get_component_directory($component);
                if (!file_exists("$dir/lib.php")) {
                    send_file_not_found();
                }
                if (!$file = $fs->get_file_by_hash(sha1($relativepath))) {
                    send_file_not_found();
                }
                if ($file->is_directory()) {
                    send_file_not_found();
                }
                send_stored_file($file, 0, 0, true);
            } else {
                send_file_not_found();
            }
        }
    } else {
        throw new \moodle_exception('You cannot access this file');
    }
}
