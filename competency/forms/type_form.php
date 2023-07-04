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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/datalib.php');

class type_form extends moodleform {
    public function definition() {
        global $CFG;

        $shortname =& $this->_customdata['shortname'];
        $description =& $this->_customdata['description'];
        $itemid =& $this->_customdata['itemid'];

        $mform =& $this->_form;
        $mform->addElement('header', 'header', get_string('comptype', 'block_evalcomix'));

        $mform->addElement('hidden', 'itemid', $itemid);
        $mform->setType('itemid', PARAM_INT);
        $mform->addElement('hidden', 'option', 'type');
        $mform->setType('option', PARAM_TEXT);
        $mform->addElement('text', 'shortname', get_string('compshortname', 'block_evalcomix'), 'maxlength="20" size="30"');
        $mform->setType('shortname', PARAM_TEXT);
        $mform->setDefault('shortname', $shortname);
        $mform->addRule('shortname', null, 'required', '', 'client');

        $mform->addElement('textarea', 'description', get_string('compdescription', 'block_evalcomix'), 'rows="10" cols="50"');
        $mform->setDefault('description', $description);
        $mform->setType('description', PARAM_TEXT);

        $objs = array();
        $objs[] =& $mform->createElement('submit', 'send', get_string('save'));
        $objs[] =& $mform->createElement('cancel');
        $grp =& $mform->addElement('group', 'buttonsgrp', '', $objs,
               array(' ', '<br />'), false);
    }

    // Custom validation.
    public function validation($data, $files) {
        global $DB, $COURSE;
        $error = array('shortname' => get_string('duplicatevalue', 'block_evalcomix'));
        if (empty($data['itemid']) && $item = $DB->get_record('block_evalcomix_comptype',
                array('courseid' => $COURSE->id, 'shortname' => $data['shortname']))) {
            return $error;
        } else if (!empty($data['itemid'])) {
            $sql = 'SELECT *
                    FROM {block_evalcomix_comptype} ct
                    WHERE ct.id != :itemid AND ct.courseid = :courseid AND ct.shortname = :shortname';
            if ($DB->get_records_sql($sql, array('itemid' => $data['itemid'], 'courseid' => $COURSE->id,
                    'shortname' => $data['shortname']))) {
                return $error;
            }
        }
        return array();
    }
}
