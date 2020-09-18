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

defined('MOODLE_INTERNAL') || die();

require_once('icalculator.php');

/**
 * It defined "calculate" method of "icalculator" as average operation
 */
class block_evalcomix_calculator_average implements block_evalcomix_icalculator{
    /**
     * It works out the average between elements of array $grades1 and $grades2
     * $activities and $users to travel the element arrays
     * @param array $grades1 --> moodle grades
     * @param array $grades2 --> evalcomix grades
     * @param array $activities
     * @param array $users it contains users' objects
     * @return array averages
     */
    public function calculate($grades1, $grades2, $activities, $users) {
        global $DB;
        $averages = array();
        // Obtains the weighing to calculate the average foreach activity.
        for ($i = 0; $i < count($activities['id']); $i++) {

            $task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $activities['id'][$i]));

            // Si la actividad esta configurada en evalcomix.
            if ($task) {
                $grade1weighing = (100 - $task->weighing) / 100;
                $grade2weighing = $task->weighing / 100;
            } else { // Si no existe.
                $grade1weighing = 100;
                $grade2weighing = 0;
            }

            foreach ($users as $user) {
                // Calculates average foreach user.

                // Si existen las notas de moodle y evalcomix.
                if (isset($grades1[$activities['id'][$i]][$user->id]) && isset($grades2[$activities['id'][$i]][$user->id])) {
                    $grade1 = $grades1[$activities['id'][$i]][$user->id] * $grade1weighing;
                    $grade2 = $grades2[$activities['id'][$i]][$user->id] * $grade2weighing;
                    $average = $grade1 + $grade2;
                    $averages[$activities['id'][$i]][$user->id] = round($average, 2, PHP_ROUND_HALF_UP);
                } else if (isset($grades1[$activities['id'][$i]][$user->id])
                    && !isset($grades2[$activities['id'][$i]][$user->id])) {
                    // Si existe nota de moodle y no existe nota de evalcomix.
                    $average = $grades1[$activities['id'][$i]][$user->id];
                    $averages[$activities['id'][$i]][$user->id] = round($average, 2, PHP_ROUND_HALF_UP);
                } else if (!isset($grades1[$activities['id'][$i]][$user->id])
                    && isset($grades2[$activities['id'][$i]][$user->id])) {
                    // Si no existe nota de moodle y existe nota de evalcomix.
                    $average = $grades2[$activities['id'][$i]][$user->id];
                    $averages[$activities['id'][$i]][$user->id] = round($average, 2, PHP_ROUND_HALF_UP);
                }
            }
        }
        return $averages;
    }

    /**
     * It works out the average between elements of array $grades
     * @param array $grades
     * @return float average
     */
    public static function calculate_one_array($grades) {
        $average = array_sum($grades) / count($grades);
        return $average;
    }
}