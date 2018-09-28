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
// Comprobar si existe el evalcomixAdd para el usuario e item correspondiente.
global $DB;
if ($rst = $DB->get_records('grade_grades_history',
    array('userid' => $userid, 'itemid' => $grade->grade_item->id, 'source' => 'evalcomixAdd'))) {
    $sql2 = "SELECT id, finalgrade FROM " . $CFG->prefix . "grade_grades_history
                WHERE userid=$userid AND itemid=".$grade->grade_item->id." AND source like 'mod/%'
                ORDER BY id DESC";
    $gradeevalcomix2 = $DB->get_records_sql($sql2);
    if ($gradeevalcomix2) {// Si existe reestablecemos el valor.
        foreach ($gradeevalcomix2 as $greval) {
            $gradeval = $greval->finalgrade;
            break;
        }
    } else {
        // Si existe, comprobar si existe una tupla con source=evalcomixDelete para ese usuario e item.
        $sql = "SELECT id, finalgrade
            FROM " . $CFG->prefix . "grade_grades_history
            WHERE id IN
                    (SELECT MAX(id)
                    FROM
                        (SELECT id, finalgrade
                        FROM " . $CFG->prefix . "grade_grades_history
                        WHERE action = 2 AND source='evalcomixDelete'
                              AND userid=$userid AND itemid=".$grade->grade_item->id.") AS t)";

        $gradeevalcomix = $DB->get_records_sql($sql);
        if ($gradeevalcomix) {
            // Si existe reestablecemos el valor.
            foreach ($gradeevalcomix as $greval) {
                $gradeval = $greval->finalgrade;
            }
        } else {// Si no existe, obtener el valor anterior a EvalcomixAdd.
            $sql = "SELECT source, finalgrade FROM " . $CFG->prefix . "grade_grades_history
                WHERE userid=$userid AND itemid=".$grade->grade_item->id."
                ORDER BY id ASC";
            $gradeevalcomix = $DB->get_records_sql($sql);
            if ($gradeevalcomix) {// Si existe reestablecemos el valor.
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

// Comprobamos que el item actual no es un "resultado".
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
