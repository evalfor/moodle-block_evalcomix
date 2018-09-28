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

function get_datas_profile_attribute($idcurso, $idtarea, $idmodality, $idstudents) {
    global $CFG;
    require_once($CFG->dirroot .'/blocks/evalcomix/configeval.php');
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/webservice_evalcomix_client.php');
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');
    $task = evalcomix_tasks::fetch(array('id' => $idtarea));
    $module = evalcomix_tasks::get_type_task($task->instanceid);
    $modality = null;
    switch ($idmodality) {
        case 1: $modality = 'teacher';
        break;
        case 2: $modality = 'self';
        break;
        case 3: $modality = 'peer';
        break;
    }

    $xml = webservice_evalcomix_client::get_ws_tool_assessed($idcurso, $module, $task->instanceid,
        $idstudents, $modality, MOODLE_NAME);
    $attributesgrade = evalcomix_tool::get_attributes_grade($xml);
    return $attributesgrade;
}
