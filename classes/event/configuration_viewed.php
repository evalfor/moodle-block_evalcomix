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
 * The block fundae viewed event.
 *
 * @package   block_evalcomix
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Daniel Cabeza <info@ansaner.com>
 */

namespace block_evalcomix\event;

/**
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Daniel Cabeza <info@ansaner.com>
 */
class configuration_viewed extends \core\event\base {

    /**
     * Init method.
     *
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('configurationviewed', 'block_evalcomix');
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return "The user with id '$this->userid' viewed the configuration page of evalcomix block of the course
            with id '$this->courseid'";
    }

    /**
     * Returns relevant URL.
     *
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/blocks/evalcomix/index.php');
    }
}
