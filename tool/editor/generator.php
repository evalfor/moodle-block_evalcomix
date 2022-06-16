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
$type = optional_param('type', '', PARAM_ALPHA);
$identifier = optional_param('identifier', '', PARAM_ALPHANUM);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
require_course_login($course);
$context = context_course::instance($courseid);
require_capability('moodle/grade:viewhidden', $context);
require_once('controller.php');

$data = (array)data_submitted();
$data = clean_param_array($data, PARAM_RAW);
$data['courseid'] = $courseid;

if ($type == 'importar') {
    $PAGE->set_url(new moodle_url('/blocks/evalcomix/tool/editor/generator.php', array('courseid' => $courseid, 'type' => $type)));
    $PAGE->set_pagelayout('popup');
    $PAGE->set_context($context);
    $PAGE->set_title(get_string('pluginname', 'block_evalcomix'));
    $PAGE->set_heading(get_string('pluginname', 'block_evalcomix'));
    $PAGE->set_pagelayout('popup');
    $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/evalcomix/style/main.css'));
    echo $OUTPUT->header();
    $tool->display_dialog();
} else {
    $tool->display_header($data);
    $tool->display_body($data);
    $save = optional_param('save', 0, PARAM_INT);

    if ($save == 1) {
        echo "<script>alert('" . get_string('savedsaccessfully', 'block_evalcomix') . "');</script>";
    }
    $tool->display_footer();
}

$toolobj = serialize($tool);
$SESSION->tool = $toolobj;
$SESSION->secuencia = $secuencia;
