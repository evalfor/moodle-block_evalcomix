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

require_once($CFG->dirroot . '/blocks/evalcomix/backup/moodle2/backup_evalcomix_stepslib.php'); // Because it exists (must).

class backup_evalcomix_block_task extends backup_block_task {

    /**
     * Define (add) particular settings this block can have
     */
    protected function define_my_settings() {
        // No particular settings for this block.
    }

    /**
     * Define (add) particular steps this block can have
     */
    protected function define_my_steps() {
        // Choice only has one structure step.
        $this->add_step(new backup_evalcomix_block_structure_step('evalcomix_structure', 'evalcomix.xml'));
    }

    public function get_fileareas() {
        return array(); // No associated fileareas.
    }

    public function get_configdata_encoded_attributes() {
        return array(); // No special handling of configdata.
    }

    /**
     * Code the transformations to perform in the block in
     * order to get transportable (encoded) links
     */
    static public function encode_content_links($content) {
        return $content;
    }
}
