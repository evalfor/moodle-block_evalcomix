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

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_heading('evalcomix_heading', get_string('adminheader', 'block_evalcomix'),
                       get_string('admindescription', 'block_evalcomix')));

    // Server URL.
    $settings->add(new admin_setting_configtext('block_evalcomix/serverurl', get_string('serverurl', 'block_evalcomix'),
                       get_string('serverurlinfo', 'block_evalcomix'), 'https://localhost/evalcomix'));
    // Token.
    $settings->add(new admin_setting_configtext('block_evalcomix/token', get_string('token', 'block_evalcomix'),
                       get_string('tokeninfo', 'block_evalcomix'), ''));
     // Validation button.
    $html = html_writer::script('', $CFG->wwwroot.'/blocks/evalcomix/validate.js');
    $html .= html_writer::tag('p', get_string('validationinfo', 'block_evalcomix'));
    $html .= html_writer::start_tag('div', array('class' => 'text-center pb-5'));
    $html .= html_writer::start_tag('span', array('id' => 'validatebutton', 'class' => 'yui-button yui-link-button'));
    $html .= html_writer::tag('a', get_string('validationbutton', 'block_evalcomix'),
        array('id' => 'validatebtn',
            'name' => 'validatebtn',
            'href' => 'javascript:validate();'));
    $html .= html_writer::end_tag('span');
    $html .= html_writer::end_tag('div');

    $settings->add(new admin_setting_heading('lamslesson_validation', get_string('validationheader', 'block_evalcomix'),
                       $html));

}
