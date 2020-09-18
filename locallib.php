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

function block_evalcomix_get_user_submission($assignment, $userid) {
    global $DB, $USER;

    if (!$userid) {
        $userid = $USER->id;
    }

    // If the userid is not null then use userid.
    $submission = $DB->get_record('assign_submission', array('assignment' => $assignment->get_instance()->id,
        'userid' => $userid));

    if ($submission) {
        return $submission;
    }

    return false;
}


function assignsubmission_file_pluginfile($course, $cm, context $context, $filearea, $args, $forcedownload) {
    global $USER, $DB;

    if ($context->contextlevel != CONTEXT_MODULE) {
        return false;
    }

    $itemid = (int)array_shift($args);
    $record = $DB->get_record('assign_submission', array('id' => $itemid), 'userid, assignment', MUST_EXIST);

    if (!$assign = $DB->get_record('assign', array('id' => $cm->instance))) {
        return false;
    }

    if ($assign->id != $record->assignment) {
        return false;
    }

    $relativepath = implode('/', $args);

    $fullpath = "/{$context->id}/assignsubmission_file/submission_files/$itemid/$relativepath";
    return $fullpath;
}

function htmllize_tree(assign_files $tree, $dir) {
    global $CFG;

    if (empty($dir['files'])) {
        return '';
    }

    $result = array();
    foreach ($dir['files'] as $file) {
        $filename = $file->get_filename();

        $arg = explode('pluginfile.php/', ltrim($file->fileurl));
        $aux = explode('/', $arg[1]);
        $aux2 = explode ('?', $aux[4]);
        $args = array($aux[3], $filename);
        $result[] = $args;
    }

    return $result;
}

function block_evalcomix_get_user_submission_old($assignment, $userid) {
    global $DB, $USER;

    if (!$userid) {
        $userid = $USER->id;
    }
    // If the userid is not null then use userid.
    $submission = $DB->get_record('assignment_submissions', array('assignment' => $assignment->assignment->id,
        'userid' => $userid));

    if ($submission) {
        return $submission;
    }

    return false;
}
