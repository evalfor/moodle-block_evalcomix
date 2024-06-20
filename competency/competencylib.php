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

class block_evalcomix_competencies {
    public static function get_competencies($courseid, $search = '', $outcome = 0) {
        global $DB;

        $typenames = array();
        $comptypesql = '
        SELECT *
        FROM {block_evalcomix_comptype}
        WHERE courseid = ?
        ';

        if ($types = $DB->get_records_sql($comptypesql, array('courseid' => $courseid))) {
            foreach ($types as $type) {
                $typeid = $type->id;
                $typenames[$typeid] = $type->shortname;
            }
        }

        $compsql = '
            SELECT *
            FROM {block_evalcomix_competencies}
            WHERE courseid = :courseid AND outcome = :outcome
        ';
        $words = array();
        if (!empty($search)) {
            $words = explode(' ', $search);
            foreach ($words as $word) {
                $lowerword = strtolower($word);
                $compsql .= ' AND (LOWER(shortname) LIKE \'%'. $lowerword .'%\' OR LOWER(idnumber) LIKE \'%'. $lowerword .
                '%\' OR LOWER(description) LIKE \'%'. $lowerword . '%\') ';
            }
        }
        if ($datas = $DB->get_records_sql($compsql, array('courseid' => $courseid, 'outcome' => $outcome))) {
            foreach ($datas as $data) {
                $data->typename = '';
                if (!empty($data->typeid)) {
                    $datatypeid = $data->typeid;
                    if (isset($typenames[$datatypeid])) {
                        $data->typename = $typenames[$datatypeid];
                    }
                }
            }
        } else if (empty($search)) {
            if ($datas = $DB->get_records('block_evalcomix_competencies', array('courseid' => $courseid, 'outcome' => 0))) {
                foreach ($datas as $data) {
                    $data->typename = '';
                    if (!empty($data->typeid)) {
                        $datatypeid = $data->typeid;
                        if (isset($typenames[$datatypeid])) {
                            $data->typename = $typenames[$datatypeid];
                        }
                    }
                }
            }
        }
        return $datas;
    }

    public static function get_competencytypes($courseid, $search = '') {
        global $DB;

        $sql = '
            SELECT *
            FROM {block_evalcomix_comptype}
            WHERE courseid = :courseid
        ';
        $words = array();
        if (!empty($search)) {
            $words = explode(' ', $search);
            foreach ($words as $word) {
                $lowerword = strtolower($word);
                $sql .= ' AND (LOWER(shortname) LIKE \'%'. $lowerword .'%\' OR LOWER(description) LIKE \'%'. $lowerword . '%\') ';
            }
        }
        $datas = $DB->get_records_sql($sql, array('courseid' => $courseid));

        return $datas;
    }
}
