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
 * Define cron task.
 *
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <info@ansaner.net>
 */
defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
        'eventname'   => '\core\event\group_member_removed',
        'callback'    => 'block_evalcomix_observer::group_member_removed',
    ),
    array(
        'eventname'   => '\core\event\group_deleted',
        'callback'    => 'block_evalcomix_observer::group_deleted',
    ),
);
