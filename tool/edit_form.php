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
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>
 */

require_once('../../../config.php');
require_once($CFG->dirroot .'/blocks/evalcomix/configeval.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');

$courseid      = required_param('id', PARAM_INT);
$toolid = required_param('t', PARAM_ALPHANUM);

require_login($courseid);

$context = context_course::instance($course->id);

$url = new moodle_url('/blocks/evalcomix/tool/edit_form.php', array('courseid' => $courseid, 't' => $toolid));
$PAGE->set_url($url);
$PAGE->set_pagelayout('popup');

if (!$tool = evalcomix_tool::fetch(array('idtool' => $toolid))) {
    print_error('EvalCOMIX: No tool enabled');
}

global $USER, $DB;
require_capability('moodle/block:edit', $context, $USER->id);

$lang = current_language();
$lms = MOODLE_NAME;
$url = webservice_evalcomix_client::get_ws_createtool($toolid, $lms, $courseid, $lang.'_utf8', 'open');

$vars = explode('?', $url);
require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

$curl = new Curly();
$response = $curl->post($vars[0], $vars[1]);
if ($response && $curl->getHttpCode() >= 200 && $curl->getHttpCode() < 400) {
    echo $response;
} else {
    print_error('EvalCOMIX cannot get datas');
}
