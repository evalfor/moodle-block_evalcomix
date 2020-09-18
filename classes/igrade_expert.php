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
 *             Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Interface of class grade_expert
 */
interface block_evalcomix_igrade_expert{
    /**
     * It gets grades of $users in $courseid of $platform
     * @param array $platform Moodle instance
     * @param array $courseid of Moodle
     * @param array $users of Moodle
     * @return array grades
     */
    public function get_grades($courseid, $users, $platform);
}