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

require('../../../config.php');
$courseid = required_param('course', PARAM_INT);
require_course_login($courseid);

if (file_exists($CFG->dirroot.'/report/outline/locallib.php')) {
    require_once($CFG->dirroot.'/report/outline/locallib.php');
}
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/blocks/evalcomix/locallib.php');

$userid   = required_param('id', PARAM_INT);
$modid     = required_param('mod', PARAM_INT);

$user = $DB->get_record('user', array('id' => $userid, 'deleted' => 0), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$cm = $DB->get_record('course_modules', array('id' => $modid), '*', MUST_EXIST);
$url = new moodle_url('/blocks/evalcomix/assessment/user_activity.php', array('userid' => $userid, 'courseid' => $courseid));
$contextcourse = context_course::instance($course->id);
$PAGE->set_url($url);
$PAGE->set_context($contextcourse);
$PAGE->set_pagelayout('popup');

$mode = block_evalcomix_grade_report::get_type_evaluation($user->id, $course->id);
if ($mode == 'teacher' || $mode == 'self' || $mode == 'peer') {
    // Catch the mods.
    $modinfo = get_fast_modinfo($course->id);
    $mods = $modinfo->get_cms();
    // Identify the concrete mod by $modid.
    $mod = $mods[$modid];
    $gradereport = new block_evalcomix_grade_report($course->id, null, $contextcourse);
    $canseeactivity = $gradereport->student_can_assess($user, $cm);

    if ($mode == 'teacher' || $canseeactivity) {
        $title = fullname($user) .get_string('studentwork2', 'block_evalcomix'). $mod->name;
        $PAGE->set_title($title);

        echo $OUTPUT->header();

        echo '<h1>'. $title.'</h1>';

        $instance = $DB->get_record("$mod->modname", array("id" => $mod->instance));
        $libfile = "$CFG->dirroot/mod/$mod->modname/lib.php";

        if (file_exists($libfile)) {
            require_once($libfile);

            $usercomplete = $mod->modname."_user_complete";
            if (function_exists($usercomplete)) {
                $context = context_module::instance($mod->id);

                $image = $OUTPUT->pix_icon('icon', $mod->modfullname, 'mod_'.$mod->modname, array('class' => 'icon'));
                echo "<h3>$image $mod->modfullname: ".
                     "<a href=\"$CFG->wwwroot/mod/$mod->modname/view.php?id=$mod->id\">".
                     format_string($instance->name, true)."</a></h3>";

                ob_start();

                if ($mod->modname == 'assign' and $USER->id != $user->id and !has_capability('moodle/grade:viewhidden', $context)) {
                    require_once($CFG->dirroot . '/mod/assign/locallib.php');
                    $context = context_module::instance($mod->id);
                    $assignment = new assign($context, $mod, $course);
                    $submission = block_evalcomix_get_user_submission($assignment, $user->id);
                    $sid = null;
                    if ($submission) {
                        $sid = $submission->id;
                    }

                    $teamsubmission = null;
                    if ($assignment->get_instance()->teamsubmission) {
                        $teamsubmission = $assignment->get_group_submission($user->id, 0, false);
                        if (isset($teamsubmission->id)) {
                            $sid = $teamsubmission->id;
                        }
                    }

                    if (isset($sid)) {
                        if ($onlinetext = $DB->get_record('assignsubmission_onlinetext',
                                array('assignment' => $assignment->get_instance()->id, 'submission' => $sid))) {
                            echo '<br><b>'.get_string('enabled', 'assignsubmission_onlinetext').':</b> '. $onlinetext->onlinetext;
                            echo '<br>';
                        }
                        $tree = new assign_files($context, $sid, 'submission_files', 'assignsubmission_file');
                        $args = htmllize_tree($tree, $tree->dir);
                        if (isset($args) && !empty($args)) {
                            echo '<ul>';
                            foreach ($args as $arg) {
                                $relativepath = block_evalcomix_assignsubmission_file_pluginfile($course, $tree->cm,
                                    $tree->context, 'submission_files', $arg, 1);
                                $fullpath = 'pluginfile.php' . $relativepath . '?forcedownload=1';
                                echo '<li><div><a href="'. $fullpath . '">'. $arg[1] .'</a></div></li>';
                            }
                            echo '</ul>';
                        }
                    }
                } else if ($mod->modname == 'assignment') {
                    require_once($CFG->dirroot . '/mod/assignment/locallib.php');
                    $context = context_module::instance($mod->id);
                    $assignment = new assignment_base($mod->id);
                    $type = $assignment->assignment->assignmenttype;

                    $submission = block_evalcomix_get_user_submission_old($assignment, $user->id);
                    if (isset($submission->id)) {
                        $fs = get_file_storage();

                        if ($files = $fs->get_area_files($context->id, 'mod_assignment', 'submission',
                            $submission->id, "timemodified", false)) {
                            $countfiles = count($files)." ".get_string("uploadedfiles", "assignment");
                            $output = '';
                            foreach ($files as $file) {
                                $countfiles .= "; ".$file->get_filename();
                                $filename = $file->get_filename();
                                $mimetype = $file->get_mimetype();
                                $path = file_encode_url('pluginfile.php', '/'.$context->id.'/mod_assignment/submission/'.
                                    $submission->id.'/'.$filename);
                                $output .= '<a href="'.$path.'" >'.$OUTPUT->pix_icon(file_file_icon($file),
                                    get_mimetype_description($file),
                                'moodle', array('class' => 'icon')).s($filename).'</a><br>';
                            }
                            echo $output;
                        }
                    }
                    $type = $assignment->assignment->assignmenttype;
                    if ($type == 'online') {
                        require_once("$CFG->dirroot/mod/assignment/type/online/assignment.class.php");
                        $assignmentonline = new assignment_online($modid);
                        echo $assignmentonline->get_submission($userid)->data1;
                    }
                } else if ($mod->modname == 'workshop') {
                    $context = context_module::instance($mod->id);
                    require_once($CFG->dirroot . '/mod/workshop/locallib.php');
                    $workshop   = new workshop($instance, $mod, $course);
                    $submission = $workshop->get_submission_by_author($user->id);

                    if (is_object($submission)) {
                        $content = format_text($submission->content, $submission->contentformat, array('overflowdiv' => true));
                        $content = file_rewrite_pluginfile_urls($content, 'pluginfile.php', $context->id,
                                                                'mod_workshop', 'submission_content', $submission->id);

                        $fs     = get_file_storage();
                        $ctx    = $context;
                        $files  = $fs->get_area_files($ctx->id, 'mod_workshop', 'submission_attachment', $submission->id);
                        echo '<ul>';
                        foreach ($files as $file) {
                            if ($file->is_directory()) {
                                continue;
                            }

                            $filepath   = $file->get_filepath();
                            $filename   = $file->get_filename();
                            $fileurl    = file_encode_url($CFG->wwwroot . '/blocks/evalcomix/assessment/pluginfile.php',
                                            '/' . $ctx->id . '/mod_workshop/submission_attachment/' . $submission->id .
                                            $filepath . $filename, false);

                            $type       = $file->get_mimetype();

                            $linkhtml = html_writer::link($fileurl, $image) . substr($filepath, 1) .
                                html_writer::link($fileurl, $filename);

                            echo '<li>' . $linkhtml . '</li>';
                            $linktxt    = "$filename [$fileurl]";
                        }
                        echo '</ul>';
                    }
                } else {
                    echo "<ul>";
                    $usercomplete($course, $user, $mod, $instance);

                    echo "</ul>";
                }
                $output = ob_get_contents();
                ob_end_clean();

                if (str_replace(' ', '', $output) != '<ul></ul>') {
                    echo $output;
                }
            }
        }

        echo "</table>";

        echo '</div>';  // Content.
        echo '</div>';  // Section.
    } else {
        print_error('You cannot see this content');
    }
} else {
    print_error('You cannot see this content');
}
echo $OUTPUT->close_window_button();
echo $OUTPUT->footer();
