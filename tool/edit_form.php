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

$courseid = required_param('id', PARAM_INT);
$toolid = required_param('t', PARAM_ALPHANUM);
$context = context_course::instance($courseid);
require_course_login($courseid);

require_capability('moodle/block:edit', $context, $USER->id);

require_once($CFG->dirroot .'/blocks/evalcomix/configeval.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');

$url = new moodle_url('/blocks/evalcomix/tool/edit_form.php', array('courseid' => $courseid, 't' => $toolid));
$PAGE->set_url($url);
$PAGE->set_pagelayout('popup');

if (!$tool = $DB->get_record('block_evalcomix_tools', array('idtool' => $toolid))) {
    print_error('EvalCOMIX: No tool enabled');
}

$lang = current_language();
$url = block_evalcomix_webservice_client::get_ws_createtool($toolid, $courseid, $lang.'_utf8', 'open');

$vars = explode('?', $url->serverurl);
require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

$curl = new block_evalcomix_curl();
$response = $curl->post($vars[0], $vars[1]);
if ($response && $curl->getHttpCode() >= 200 && $curl->getHttpCode() < 400) {
    echo $response;
} else {
    print_error('EvalCOMIX cannot get datas');
}
