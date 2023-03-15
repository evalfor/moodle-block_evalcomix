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

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot."/blocks/evalcomix/configeval.php");
require_once($CFG->dirroot."/blocks/evalcomix/classes/evalcomix_tasks.php");
require_once($CFG->dirroot."/blocks/evalcomix/classes/evalcomix_assessments.php");
require_once($CFG->dirroot."/blocks/evalcomix/classes/evalcomix_modes.php");
global $DB;

if (isset($grade->grade_item)) {
    $module = $grade->grade_item->itemmodule;
    $inst = $grade->grade_item->iteminstance;
    $instance = $cms[$module][$inst];
    $maxgrade = $grade->grade_item->grademax;
    $student = $userid;
    $gradeold = null;

    $evalcomixable = false;
    if (!empty($tasks[$instance]->visible) && isset($finalgrades[$instance][$student])) {
        $finalgrade = $finalgrades[$instance][$student];
        if ($finalgrades[$instance][$student] >= 0) {
            $evalcomixable = true;
        }

        if (!$gradeitem = grade_item::fetch(array('iteminstance' => $grade->grade_item->iteminstance,
            'itemmodule' => $module, 'courseid' => $courseid, 'itemnumber' => '0'))) {
            error('Can not find grade_item 1');
        }
        $multfactor = $gradeitem->multfactor;
        $plusfactor = $gradeitem->plusfactor;

        if ($evalcomixable) {
            $finalgrade = $finalgrade * $maxgrade / 100;

            if ($gradeval != '-' && $gradeval != '' && is_numeric($gradeval)) {
                $gradeold = new grade_grade(array('itemid' => $gradeitem->id, 'userid' => $student));
                $gradeoriginal = $gradeold->rawgrade;
                $gradeval = ($finalgrade + $gradeoriginal) / 2;
                $gradeval *= $multfactor;
                $gradeval += $plusfactor;
                $grademax = $gradeitem->grademax;

                $maxcoef = isset($CFG->gradeoverhundredprocentmax) ? $CFG->gradeoverhundredprocentmax : 10; // 1000% max by default.

                if (!empty($CFG->unlimitedgrades)) {
                    $grademax = $grademax * $maxcoef;
                } else if ($gradeitem->is_category_item() || $gradeitem->is_course_item()) {
                    $category = $gradeitem->load_item_category();
                    if ($category->aggregation >= 100) {
                        // Grade >100% hack.
                        $grademax = $grademax * $maxcoef;
                    }
                }
                if ($gradeval > $grademax) {
                    $gradeval = $grademax;
                }
                if ($gradeval < $gradeitem->grademin) {
                    $gradeval = $gradeitem->grademin;
                }
            } else if (($gradeval == '-' || $gradeval == '')) {
                $gradeold = new grade_grade(array('itemid' => $gradeitem->id, 'userid' => $student));
                $gradeval = $finalgrade;
                $gradeval *= $multfactor;
                $gradeval += $plusfactor;
                if ($gradeval > 100) {
                    $gradeval = 100;
                }
            }

            if (!$gradeitem->scaleid && $grade->grade_item->itemnumber == 0) {
                $overridden = $gradeold->overridden;
                $gradeitem->update_final_grade($student, $gradeval, 'evalcomixAdd');
                $grade = new grade_grade(array('itemid' => $gradeitem->id, 'userid' => $student));
                $grade->overridden = $overridden;
                $grade->update('EvalcomixUpdate');
            }
        }
    }
}
