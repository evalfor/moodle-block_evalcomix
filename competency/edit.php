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

$courseid = required_param('id', PARAM_INT);
$option = optional_param('o', 'competency', PARAM_ALPHA);
$itemid = optional_param('iid', 0, PARAM_INT);
$delete = optional_param('del', 0, PARAM_INT);

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
require_course_login($course);
$context = context_course::instance($course->id);
require_capability('moodle/block:edit', $context);

$url = new moodle_url('/blocks/evalcomix/competency/edit.php', array('id' => $courseid, 'o' => $option));
$redirect = new moodle_url('/blocks/evalcomix/competency/index.php', array('id' => $courseid, 'o' => $option));
$PAGE->set_url($url);
$PAGE->set_pagelayout('incourse');
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'block_evalcomix'));
$PAGE->set_heading(get_string('pluginname', 'block_evalcomix'));
$PAGE->navbar->add('evalcomix', new moodle_url('../assessment/index.php?id='.$courseid));
$PAGE->navbar->add(get_string('handlerofco', 'block_evalcomix'), $redirect);
$PAGE->navbar->add(get_string('edit'));
$PAGE->set_pagelayout('report');

if ($option != 'competency' && $option != 'outcome' && $option != 'type') {
    $option = 'competency';
}

require_once($CFG->dirroot . '/blocks/evalcomix/competency/forms/'.$option.'_form.php');

$class = $option . '_form';
$params = array();
$params['itemid'] = $itemid;
if (!empty($itemid)) {
    if ($delete === 1) {
        if ($option == 'competency' || $option == 'outcome') {
            if ($DB->get_record('block_evalcomix_competencies', array('id' => $itemid))) {
                $DB->delete_records('block_evalcomix_competencies', array('id' => $itemid));
            }
        } else if ($option == 'type') {
            if ($t = $DB->get_record('block_evalcomix_comptype', array('id' => $itemid))) {
                if ($competencies = $DB->get_records('block_evalcomix_competencies', array('typeid' => $t->id))) {
                    foreach ($competencies as $competency) {
                        $competency->typeid = null;
                        $DB->update_record('block_evalcomix_competencies', $competency);
                    }
                }
                $DB->delete_records('block_evalcomix_comptype', array('id' => $itemid));
            }
        }
        redirect($redirect);
    }

    $datas = null;
    switch ($option) {
        case 'competency': {
            if ($datas = $DB->get_record('block_evalcomix_competencies', array('id' => $itemid, 'outcome' => 0))) {
                $params['code'] = $datas->idnumber;
                $params['type'] = $datas->typeid;
            }
        }break;
        case 'type': {
            $datas = $DB->get_record('block_evalcomix_comptype', array('id' => $itemid));
        }break;
        case 'outcome': {
            if ($datas = $DB->get_record('block_evalcomix_competencies', array('id' => $itemid, 'outcome' => 1))) {
                $params['code'] = $datas->idnumber;
            }
        }break;
    }
    $params['shortname'] = $datas->shortname;
    $params['description'] = (isset($datas->description)) ? $datas->description : '';
}
if ($option == 'competency') {
    $types = array();
    $comptypes = $DB->get_records('block_evalcomix_comptype', array('courseid' => $courseid));
    foreach ($comptypes as $comptype) {
        $comptypeid = $comptype->id;
        $types[$comptypeid] = $comptype->shortname;
    }
    $params['types'] = $types;
}
$form = new $class($url, $params);
if ($form->is_cancelled()) {
    redirect($redirect);
}
if ($data = $form->get_data()) {
    $paramsdb = array('shortname' => $data->shortname, 'description' => $data->description, 'courseid' => $courseid);
    switch($data->option) {
        case 'type': {
            $table = 'block_evalcomix_comptype';
        }break;
        case 'outcome': {
            $paramsdb['idnumber'] = $data->code;
            $paramsdb['outcome'] = 1;
            $table = 'block_evalcomix_competencies';
        }break;
        case 'competency': {
            $paramsdb['idnumber'] = $data->code;
            $paramsdb['outcome'] = 0;
            $paramsdb['typeid'] = (isset($data->type)) ? $data->type : null;
            $table = 'block_evalcomix_competencies';
        }
    }
    if (empty($data->itemid)) {
        if ($option != 'type') {
            $paramsdb['timecreated'] = time();
        }
        $DB->insert_record($table, $paramsdb);
    } else if (!empty($data->itemid)) {
        $paramsdb['id'] = $data->itemid;
        if ($option != 'type') {
            $paramsdb['timemodified'] = time();
        }
        $DB->update_record($table, $paramsdb);
    }
    redirect($redirect);
}
echo $OUTPUT->header();
echo $form->display();
echo $OUTPUT->footer();
