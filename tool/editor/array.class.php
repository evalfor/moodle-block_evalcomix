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

/*
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, <info@ansaner.net>
 */

class block_evalcomix_array_util {
    /**
     * This function adds $elem to @array before $keyelement.
     * @param array $array
     * @param string $keyelement key of the element from which to insert the element $elem in $array
     * @param object $element new element to add
     * @param $index key of the new element.
     * @return $array with the new element
     */
    public static function array_add_left($array, $keyelement, $element, $index) {
        $arrayaux = array();
        $keyleft = null;
        $flag = false;
        $i = 0;
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if ($i == 0 && $key == $keyelement) {
                    $result[$index] = $element;
                    return $result + $array;
                } else if ($key == $keyelement) {
                    break;
                }
                $keyleft = $key;
                ++$i;
            }

            foreach ($array as $key => $value) {
                $arrayaux[$key] = $value;
                if ($key == $keyleft) {
                    $flag = true;
                }
                if ($flag == true) {
                    $arrayaux[$index] = $element;
                    $flag = false;
                }
            }
        }
        return $arrayaux;
    }

    public static function array_add_rigth($array, $keyelement, $element, $index) {
        $arrayaux = array();
        $flag = false;
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $arrayaux[$key] = $value;
                if ($key == $keyelement) {
                    $flag = true;
                }
                if ($flag == true) {
                    $newkey = $key + 1;
                    if (isset($index)) {
                        $newkey = $index;
                    }
                    $arrayaux[$newkey] = $element;
                    $flag = false;
                }
            }
        }
        return $arrayaux;
    }

    public static function get_previous_item($array, $element) {
        $i = 0;
        $arrayaux[$i] = 'null';
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                ++$i;
                $arrayaux[$i] = $key;
                if ($key == $element) {
                    $result = $arrayaux[$i - 1];
                    if ($result === 'null') {
                        return false;
                    } else {
                        return $result;
                    }
                }
            }
        }
        return false;
    }

    public static function get_next_item($array, $key) {
        if (is_array($array) && isset($array[$key])) {
            $flag = false;
            foreach ($array as $currentkey => $value) {
                if ($flag == true) {
                    return $currentkey;
                }
                if ($currentkey == $key) {
                    $flag = true;
                }
            }
        }
        return false;
    }
}
