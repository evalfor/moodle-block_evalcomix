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

require_once('../../../config.php');

$courseid      = required_param('id', PARAM_INT);
$option        = optional_param('o', 'competency', PARAM_ALPHA);
$sort          = optional_param('sort', '', PARAM_ALPHA);
$dir           = optional_param('dir', 'ASC', PARAM_ALPHA);
$search        = optional_param('search', '', PARAM_RAW);

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
require_course_login($course);
$context = context_course::instance($course->id);
require_capability('moodle/grade:viewhidden', $context);

$datas = null;

switch ($option) {
    case 'competency': {
        require_once($CFG->dirroot . '/blocks/evalcomix/competency/competencylib.php');
        $datas = block_evalcomix_competencies::get_competencies($courseid, $search);
    }break;
    case 'type': {
        $datas = $DB->get_records('block_evalcomix_comptype', array('courseid' => $courseid));
    }break;
    case 'outcome': {
        $datas = $DB->get_records('block_evalcomix_competencies', array('courseid' => $courseid, 'outcome' => 1));
    }break;
}

require_once($CFG->dirroot . '/blocks/evalcomix/competency/renderer.php');
$renderer = $PAGE->get_renderer('block_evalcomix', 'competency');
echo $renderer->display_main_page($courseid, $datas, $option, $sort, $dir, $search);
