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

define('BLOCK_EVALCOMIX_COMPETENCY', 0);
define('BLOCK_EVALCOMIX_OUTCOME', 1);

function block_evalcomix_get_development_datas($courseid, $outcome = 0, $groupid = 0, $studentid = 0, $grades = true) {
    global $DB;
    $result = new stdClass();
    $result->xdatas = array();
    $result->ydatas = array();
    $result->title = array();
    $activities = array();

    if ($competencies = $DB->get_records('block_evalcomix_competencies', array('courseid' => $courseid,
            'outcome' => $outcome))) {
        if ($grades) {
            $datas = block_evalcomix_get_competency_grade($courseid, array_keys($competencies), $groupid, $studentid);
        }
        foreach ($competencies as $competency) {
            $compid = $competency->id;
            $idnumber = $competency->idnumber;
            $result->ydatas[$idnumber] = $idnumber;
            $result->xdatas[$idnumber] = 0;
            $result->gradebytask[$idnumber] = array();
            if ($grades) {
                if (isset($datas[$compid]) && isset($datas[$compid]->grade)) {
                    $result->xdatas[$idnumber] = $datas[$compid]->grade;
                }
                if (isset($datas[$compid]) && isset($datas[$compid]->gradebytask)) {
                    $result->gradebytask[$idnumber] = $datas[$compid]->gradebytask;
                }
            }
        }
    }

    return $result;
}

function block_evalcomix_get_competency_grade($courseid, $competencyid = array(), $groupid = 0, $studentid = 0) {
    global $CFG, $DB;
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
    $result = array();
    $gradebytask = array();

    $datas = block_evalcomix_webservice_client::get_grade_subdimension(array('courseid' => $courseid,
        'competencyid' => $competencyid, 'groupid' => $groupid, 'studentid' => $studentid));

    foreach ($datas as $compid => $item1) {
        foreach ($item1 as $subdimensionid => $item2) {
            foreach ($item2 as $studentid => $item3) {
                foreach ($item3 as $cmid => $item4) {
                    foreach ($item4 as $grade) {
                        $gradebytask[$compid][$cmid][] = (int)$grade;
                    }
                }
            }
        }
        $result[$compid] = new stdClass();
        $result[$compid]->grade = 0;
        $result[$compid]->gradebytask = array();
    }

    if (!empty($gradebytask)) {
        foreach ($gradebytask as $compid => $item1) {
            $rawgrades = array();
            foreach ($item1 as $cmid => $grades) {
                if (!empty($grades)) {
                    $countgrades = count($grades);
                    $taskgrade = 0;
                    foreach ($grades as $grade) {
                        $taskgrade += $grade;
                    }
                    $rawgrades[$cmid] = round(($taskgrade / $countgrades), 2);
                }
            }
            $count = count($rawgrades);
            if ($count > 0) {
                $result[$compid]->grade = round((array_sum($rawgrades) / $count), 2);
                foreach ($rawgrades as $cmid => $value) {
                    $result[$compid]->gradebytask[$cmid] = $value;
                }
            }
        }
    }

    return $result;
}
