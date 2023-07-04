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
 * Bulk user registration script from a comma separated file
 *
 * @package    tool
 * @subpackage uploaduser
 * @copyright  2004 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/csvlib.class.php');
require_once($CFG->dirroot . '/blocks/evalcomix/competency/forms/csv_form.php');
require_once($CFG->dirroot . '/blocks/evalcomix/util.php');
require_once($CFG->dirroot . '/blocks/evalcomix/competency/preview.php');

$courseid = required_param('id', PARAM_INT);
$iid = optional_param('iid', '', PARAM_INT);
$previewrows = optional_param('previewrows', 10, PARAM_INT);
$continue = optional_param('continue', 0, PARAM_INT);

core_php_time_limit::raise(60 * 60); // 1 hour should be enough.
raise_memory_limit(MEMORY_HUGE);

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
require_course_login($course);
$context = context_course::instance($course->id);
require_capability('moodle/block:edit', $context);

$url = new moodle_url('/blocks/evalcomix/competency/import.php', array('id' => $courseid));
$PAGE->set_url($url);
$PAGE->set_pagelayout('incourse');
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'block_evalcomix'));
$PAGE->set_heading(get_string('pluginname', 'block_evalcomix'));
$PAGE->navbar->add('evalcomix', new moodle_url('../assessment/index.php?id='.$courseid));
$PAGE->navbar->add(get_string('handlerofco', 'block_evalcomix'));
$PAGE->set_pagelayout('report');
$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url($CFG->wwwroot . '/blocks/evalcomix/ajax.js'));

require_once($CFG->dirroot . '/blocks/evalcomix/renderer.php');
$returnurl = new moodle_url('/blocks/evalcomix/competency/index.php', array('id' => $courseid));

if ($continue) {
    $competencies = 0;
    $outcomes = 0;
    $types = 0;
    $ignored = 0;
    $errors = 0;
    foreach ($SESSION->uploadcompetencies as $item) {
        if (isset($item['status']) && $item['status'] !== '') {
            $ignored++;
            continue;
        }
        if (isset($item['idnumber']) && isset($item['shortname']) && isset($item['outcome'])) {
            $idnumber = $item['idnumber'];
            $shortname = $item['shortname'];
            $outcome = (int)$item['outcome'];
            $description = isset($item['description']) ? $item['description'] : null;
            $now = time();
            if ($outcome === 0) {
                $typename = !empty($item['typename']) ? $item['typename'] : null;
                $typedescription = !empty($item['typedescription']) ? $item['typedescription'] : '';
                if (!$DB->get_record('block_evalcomix_competencies', array('courseid' => $courseid, 'idnumber' => $idnumber,
                        'outcome' => $outcome))) {
                    $typeid = null;
                    if (!empty($typename)) {
                        if (!$type = $DB->get_record('block_evalcomix_comptype', array('courseid' => $courseid,
                            'shortname' => $typename))) {
                            if ($typeid = $DB->insert_record('block_evalcomix_comptype', array('courseid' => $courseid,
                                'shortname' => $typename, 'description' => $typedescription))) {
                                $types++;
                            } else {
                                $errors++;
                            }
                        } else {
                            $typeid = $type->id;
                        }
                    }
                    if ($DB->insert_record('block_evalcomix_competencies', array('courseid' => $courseid, 'idnumber' => $idnumber,
                        'outcome' => $outcome, 'shortname' => $shortname, 'description' => $description, 'timecreated' => $now,
                        'typeid' => $typeid))) {
                        $competencies++;
                    } else {
                        $errors++;
                    }
                }
            } else if ($outcome === 1) {
                if (!$DB->get_record('block_evalcomix_competencies', array('courseid' => $courseid, 'idnumber' => $idnumber,
                        'outcome' => $outcome))) {
                    if ($DB->insert_record('block_evalcomix_competencies', array('courseid' => $courseid, 'idnumber' => $idnumber,
                        'outcome' => $outcome, 'shortname' => $shortname, 'description' => $description, 'timecreated' => $now))) {
                        $outcomes++;
                    } else {
                        $errors++;
                    }
                }
            } else {
                $ignored++;
            }
        } else {
            $ignored++;
        }
    }
    require_once($CFG->dirroot . '/blocks/evalcomix/competency/renderer.php');
    $renderer = $PAGE->get_renderer('block_evalcomix', 'competency');

    echo $OUTPUT->header();
    echo block_evalcomix_renderer::display_main_menu($courseid, 'competency');

    echo $renderer->display_import_result($competencies, $outcomes, $types, $ignored, $errors, $returnurl);

    echo $OUTPUT->footer();
    die;
}

if (empty($iid)) {
    $params = array();
    $mform1 = new block_evalcomix_uploadcompetence_form1($url, $params);

    if ($mform1->is_cancelled()) {
        redirect($CFG->wwwroot . '/blocks/evalcomix/competency/index.php?id='.$courseid);
    } else if ($formdata = $mform1->get_data()) {
        $iid = csv_import_reader::get_new_iid('uploadcompetence');
        $cir = new csv_import_reader($iid, 'uploadcompetence');

        $content = $mform1->get_file_content('competencyfile');

        $readcount = $cir->load_csv_content($content, $formdata->encoding, $formdata->delimiter_name);
        $csvloaderror = $cir->get_error();
        unset($content);

        if (!is_null($csvloaderror)) {
            print_error('csvloaderror', '', $returnurl, $csvloaderror);
        }
    } else {
        echo $OUTPUT->header();
        echo block_evalcomix_renderer::display_main_menu($courseid, 'competency');
        echo $OUTPUT->heading_with_help(get_string('uploadcompetencies', 'block_evalcomix'), 'uploadcompetencies',
        'block_evalcomix');

        $mform1->display();
        echo $OUTPUT->footer();
        die;
    }
} else {
    $cir = new csv_import_reader($iid, 'uploaduser');
}

// Test if columns ok.
$filecolumns = block_evalcomix_get_file_columns($cir);

// Print the header.
echo $OUTPUT->header();

echo block_evalcomix_renderer::display_main_menu($courseid, 'competency');
echo $OUTPUT->heading(get_string('uploadcompetenciespreview', 'block_evalcomix'));

// Preview table data.
$table = new preview($cir, $filecolumns, $previewrows);

echo html_writer::tag('div', html_writer::table($table), ['class' => 'flexible-wrap']);

if ($table->get_no_error()) {
    $SESSION->uploadcompetencies = $table->alldatas;
    echo '<div class="text-center"><button type="button" onclick="location.href=\''.$url.'&op=import\'">'.get_string('back').
    '</button> <button type="button" onclick="location.href=\''.$url.'&continue=1\'">'.get_string('continue').
    '</button></div>';
} else {
    echo '<div class="text-center"><button type="button" onclick="location.href=\''.$returnurl.'\'">'.get_string('back').
    '</button></div>';
}
echo $OUTPUT->footer();
die;
