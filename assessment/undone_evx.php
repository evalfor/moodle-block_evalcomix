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
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>,
               Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/lib/dmllib.php');
// Check if the evalcomixAdd exists for the corresponding user and item.
global $DB;
if ($rst = $DB->get_records('grade_grades_history',
    array('userid' => $userid, 'itemid' => $grade->grade_item->id, 'source' => 'evalcomixAdd'))) {
    $sql2 = "SELECT id, finalgrade FROM {grade_grades_history}
                WHERE userid=? AND itemid=? AND source like 'mod/%'
                ORDER BY id DESC";
    $gradeevalcomix2 = $DB->get_records_sql($sql2, array($userid, $grade->grade_item->id));
    if ($gradeevalcomix2) {// If there is a reestablished value.
        foreach ($gradeevalcomix2 as $greval) {
            $gradeval = $greval->finalgrade;
            break;
        }
    } else {
        // If it exists, check if there is a tuple with source = evalcomixDelete for that user and item.
        $sql = "SELECT id, finalgrade
            FROM {grade_grades_history}
            WHERE id IN
                    (SELECT MAX(id)
                    FROM
                        (SELECT id, finalgrade
                        FROM {grade_grades_history}
                        WHERE action = 2 AND source='evalcomixDelete'
                              AND userid=? AND itemid=?) AS t)";

        $gradeevalcomix = $DB->get_records_sql($sql, array($userid, $grade->grade_item->id));
        if ($gradeevalcomix) {
            // If it exists, we reestablish the value.
            foreach ($gradeevalcomix as $greval) {
                $gradeval = $greval->finalgrade;
            }
        } else {// If it does not exist, get the value before EvalcomixAdd.
            $sql = "SELECT source, finalgrade FROM {grade_grades_history}
                WHERE userid=? AND itemid=?
                ORDER BY id ASC";
            $gradeevalcomix = $DB->get_records_sql($sql, array($userid, $grade->grade_item->id));
            if ($gradeevalcomix) {// If it exists, we reestablish the value.
                foreach ($gradeevalcomix as $greval) {
                    if ($greval->source == 'evalcomixAdd') {
                        $gradeval = null;
                    } else {
                        $gradeval = $greval->finalgrade;
                    }
                    break;
                }// Foreach gradeevalcomix as greval.
            }// If gradeevalcomix.
        }// Else.
    }// Else.
}// If (rst = get_record).

// We check that the current item is not a "result".
if ($grade->grade_item->itemnumber == 0) {
    $modulo = $grade->grade_item->itemmodule;
    $instancia = $grade->grade_item->iteminstance;
    $maxgrade = $grade->grade_item->grademax;
    $student = $userid;
    $courseid = $courseid;
    if (!$gradeitem = grade_item::fetch(array('iteminstance' => $instancia, 'itemmodule' => $modulo, 'courseid' => $courseid,
        'itemnumber' => 0))) {
            error('Can not find grade_item 2');
    }
    $gradeold = new grade_grade(array('itemid' => $gradeitem->id, 'userid' => $student));
    $overridden = $gradeold->overridden;
    $gradeitem->update_final_grade($student, $gradeval, 'evalcomixDelete');
    $grade1 = new grade_grade(array('itemid' => $gradeitem->id, 'userid' => $student));
    $grade1->overridden = $overridden;
    $grade1->update();
}
