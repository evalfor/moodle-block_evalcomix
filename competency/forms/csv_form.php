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
 * Upload a file CVS file with competencies and ourcomes information.
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <info@ansaner.net>
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot . '/user/editlib.php');

/**
 * Upload a file CVS file with competencies and ourcomes information.
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <info@ansaner.net>
 */
class block_evalcomix_uploadcompetence_form1 extends moodleform {
    /**
     * definition
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('header', 'settingsheader', get_string('upload'));

        $url = new moodle_url('example.csv');
        $link = html_writer::link($url, 'example.csv');
        $mform->addElement('static', 'examplecsv', get_string('examplecsv', 'tool_uploaduser'), $link);
        $mform->addHelpButton('examplecsv', 'examplecsv', 'tool_uploaduser');

        $mform->addElement('filepicker', 'competencyfile', get_string('file'));
        $mform->addRule('competencyfile', null, 'required');

        $choices = csv_import_reader::get_delimiter_list();
        $mform->addElement('select', 'delimiter_name', get_string('csvdelimiter', 'tool_uploaduser'), $choices);
        $mform->setDefault('delimiter_name', 'semicolon');

        $choices = ['UTF-8' => 'UTF-8'];
        ;
        $mform->addElement('select', 'encoding', get_string('encoding', 'tool_uploaduser'), $choices);
        $mform->setDefault('encoding', 'UTF-8');

        $choices = ['10' => 10, '20' => 20, '100' => 100, '1000' => 1000, '100000' => 100000];
        $mform->addElement('select', 'previewrows', get_string('rowpreviewnum', 'tool_uploaduser'), $choices);
        $mform->setType('previewrows', PARAM_INT);

        $objs = [];
        $objs[] =& $mform->createElement('submit', 'submitbutton', get_string('upfile', 'block_evalcomix'));
        $objs[] =& $mform->createElement('cancel', 'cancel', get_string('cancel'));
        $grp =& $mform->addElement(
            'group',
            'buttonsgrp',
            '',
            $objs,
            [' ', '<br />'],
            false
        );
    }

    /**
     * Returns list of elements and their default values, to be used in CLI
     *
     * @return array
     */
    public function get_form_for_cli() {
        $elements = array_filter($this->_form->_elements, function ($element) {
            return !in_array($element->getName(), ['buttonar', 'userfile', 'previewrows']);
        });
        return [$elements, $this->_form->_defaultValues];
    }
}
