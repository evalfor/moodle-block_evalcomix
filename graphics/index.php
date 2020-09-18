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

$mode = optional_param('mode', 1, PARAM_INT);
$courseid      = required_param('id', PARAM_INT);
if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('nocourseid');
}
require_course_login($course);
$context = context_course::instance($course->id);
require_capability('moodle/grade:viewhidden', $context);

$PAGE->set_url(new moodle_url('/blocks/evalcomix/graphics/index.php', array('id' => $courseid)));
$PAGE->set_pagetype('home');
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'block_evalcomix'));
$PAGE->set_heading(get_string('pluginname', 'block_evalcomix'));

// Print the header.
$PAGE->navbar->add('evalcomix', new moodle_url('../assessment/index.php?id='.$courseid));
$PAGE->set_pagelayout('standard');
$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/blocks/evalcomix/ajax.js'));
$PAGE->requires->css(new moodle_url($CFG->wwwroot . '/blocks/evalcomix/style/styles.css'));
require_once($CFG->dirroot . '/blocks/evalcomix/graphics/graphicsrenderer.php');
$renderer = new block_evalcomix_graphic_renderer();

$event = \block_evalcomix\event\graphic_viewed::create(array('courseid' => $course->id, 'context' => $context,
    'relateduserid' => $USER->id));
$event->trigger();

echo $OUTPUT->header();

echo '
    <center>
        <div><img src="'. $CFG->wwwroot . '/blocks/evalcomix/images/logoevalcomix.png" width="230" alt="EvalCOMIX"/></div><br>
        <div><input type="button" value="'.
        get_string('assesssection', 'block_evalcomix').'" onclick="location.href=\''.
        $CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'\'"/></div><br>
    </center>
';

echo $renderer->display_graphics($course->id, $mode);

echo $OUTPUT->footer();
