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

require_once('../../../config.php');
$courseid      = required_param('id', PARAM_INT);        // Course id.
require_course_login($courseid);

$page          = optional_param('page', 0, PARAM_INT);   // Active page.
$perpageurl    = optional_param('perpage', 0, PARAM_INT);
$sortitemid    = optional_param('sortitemid', 0, PARAM_ALPHANUM); // Sort by which grade item.
$grd           = optional_param('grd', 0, PARAM_INT);   // 1 if the system must pass grades to Moodle´s Grades Report.
$cma = optional_param('cma', 0, PARAM_INT);   // Cm id of evaluated activity.
$export        = optional_param('e', '0', PARAM_INT);

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$context = context_course::instance($courseid);

require_once($CFG->dirroot .'/blocks/evalcomix/lib.php');
require_once($CFG->dirroot . '/grade/report/grader/lib.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tool.php');
require_once($CFG->dirroot .'/blocks/evalcomix/classes/webservice_evalcomix_client.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');

if ($export && has_capability('moodle/block:edit', $context, $USER->id)) {
    require_once($CFG->dirroot . '/blocks/evalcomix/util.php');
    block_evalcomix_export_assessment::export($course);
    exit;
}

if (!empty($cma)) {
    $data['cma'] = $cma;
}

$data = array();
$data['cmid'] = optional_param('cmid', 0, PARAM_INT);
$data['stu'] = optional_param('stu', 0, PARAM_INT);   // Evaluated student id.
$data['toolEP'] = optional_param('toolEP', '', PARAM_ALPHANUM);
$data['toolEI'] = optional_param('toolEI', '', PARAM_ALPHANUM);
$data['toolAE'] = optional_param('toolAE', '', PARAM_ALPHANUM);
$data['pon_EP'] = optional_param('pon_EP', '', PARAM_ALPHANUM);
$data['pon_AE'] = optional_param('pon_AE', '', PARAM_ALPHANUM);
$data['pon_EI'] = optional_param('pon_EI', '', PARAM_ALPHANUM);
$data['hour_available_AE'] = optional_param('hour_available_AE', '', PARAM_ALPHANUM);
$data['minute_available_AE'] = optional_param('minute_available_AE', '', PARAM_ALPHANUM);
$data['month_available_AE'] = optional_param('month_available_AE', '', PARAM_ALPHANUM);
$data['day_available_AE'] = optional_param('day_available_AE', '', PARAM_ALPHANUM);
$data['year_available_AE'] = optional_param('year_available_AE', '', PARAM_ALPHANUM);
$data['hour_timedue_AE'] = optional_param('hour_timedue_AE', '', PARAM_ALPHANUM);
$data['minute_timedue_AE'] = optional_param('minute_timedue_AE', '', PARAM_ALPHANUM);
$data['month_timedue_AE'] = optional_param('month_timedue_AE', '', PARAM_ALPHANUM);
$data['day_timedue_AE'] = optional_param('day_timedue_AE', '', PARAM_ALPHANUM);
$data['year_timedue_AE'] = optional_param('year_timedue_AE', '', PARAM_ALPHANUM);
$data['hour_available_EI'] = optional_param('hour_available_EI', '', PARAM_ALPHANUM);
$data['minute_available_EI'] = optional_param('minute_available_EI', '', PARAM_ALPHANUM);
$data['month_available_EI'] = optional_param('month_available_EI', '', PARAM_ALPHANUM);
$data['day_available_EI'] = optional_param('day_available_EI', '', PARAM_ALPHANUM);
$data['year_available_EI'] = optional_param('year_available_EI', '', PARAM_ALPHANUM);
$data['hour_timedue_EI'] = optional_param('hour_timedue_EI', '', PARAM_ALPHANUM);
$data['minute_timedue_EI'] = optional_param('minute_timedue_EI', '', PARAM_ALPHANUM);
$data['month_timedue_EI'] = optional_param('month_timedue_EI', '', PARAM_ALPHANUM);
$data['day_timedue_EI'] = optional_param('day_timedue_EI', '', PARAM_ALPHANUM);
$data['year_timedue_EI'] = optional_param('year_timedue_EI', '', PARAM_ALPHANUM);
$data['save'] = optional_param('save', '', PARAM_ALPHANUM);
$data['maxgrade'] = optional_param('maxgrade', '', PARAM_ALPHANUM);
$data['anonymousEI'] = optional_param('anonymousEI', '', PARAM_ALPHANUM);
$data['alwaysvisibleEI'] = optional_param('alwaysvisibleEI', '', PARAM_ALPHANUM);
$data['whoassessesEI'] = optional_param('whoassessesEI', '', PARAM_ALPHANUM);
$data['grademethod'] = optional_param('grademethod', '', PARAM_INT);
$data['threshold'] = optional_param('threshold', '', PARAM_INT);
$workteams = optional_param('workteams', '', PARAM_ALPHA);
$data['workteams'] = (!empty($workteams) && strtolower($workteams) == 'on') ? 1 : 0;
$groups = groups_get_all_groups($courseid);
$coordinators = array();
foreach ($groups as $group) {
    $groupid = $group->id;
    $coordinator = optional_param('coordinator-'.$groupid, '', PARAM_INT);
    if (!empty($coordinator)) {
        $coordinators[$groupid] = $coordinator;
    }
}
$data['coordinators'] = $coordinators;

// It is verified that the course is not newly restored, in which case it updates the instruments.
$environment = $DB->get_record('block_evalcomix', array('courseid' => $courseid));
// If there are duplicate instruments (timemodified == -1).
if (isset($environment->id) && $webtools = $DB->get_records('block_evalcomix_tools',
        array('evxid' => $environment->id, 'timemodified' => '-1'))) {
    $tools = array();
    if (!empty($webtools) && $environment) {
        block_evalcomix_update_tool_list($environment->id, $webtools);
    }
}

// Initialise the evalcomix report object that produces the table.
$reportevalcomix = new block_evalcomix_grade_report($courseid, null, $context, $page, $sortitemid);
if (!empty($data['cmid'])) {
    require_sesskey();
    $reportevalcomix->process_data($data);
}

$users = array();
$activities = array();
$contentcells = array();

$evalcomix = $DB->get_record('block_evalcomix', array('courseid' => $courseid));
$showmessage = false;

// Prints paging bar at top for large pages.
$studentsperpage = $reportevalcomix->studentsperpage;
$numusers = $reportevalcomix->get_numusers();
require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
$tasks = block_evalcomix_tasks::get_tasks_by_courseid($courseid);

if (has_capability('moodle/block:edit', $context, $USER->id) && ($grd == 1 || $grd == 2 || $grd == 3)) {
    $blockdb = $DB->get_records('modules', array());
    $cmdb = $DB->get_records('course_modules', array('course' => $courseid));
    foreach ($blockdb as $b) {
        $mod = $b->id;
        $blocks[$mod] = $b->name;
    }
    foreach ($cmdb as $cm) {
        $mod = $cm->module;
        $module = $blocks[$mod];
        $instance = $cm->instance;
        $cms[$module][$instance] = $cm->id;
    }

    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_grades.php');
    $finalgrades = block_evalcomix_grades::get_grades($courseid);

    $numpages = (int)($numusers / $studentsperpage);
    if ($numusers % $studentsperpage > 0) {
        $numpages += 1;
    }
    for ($ipage = 0; $ipage < $numpages; ++$ipage) {
        $reportgrader = new grade_report_grader($courseid, null, $context, $ipage, $sortitemid);
        $reportgrader->load_users();
        $reportgrader->load_final_grades();

        foreach ($reportgrader->users as $userid => $user) {
            if ($reportgrader->canviewhidden) {
                $altered = array();
                $unknown = array();
            } else {
                $hidingaffected = grade_grade::get_hiding_affected($reportgrader->grades[$userid],
                    $reportgrader->gtree->get_items());
                $altered = $hidingaffected['altered'];
                $unknown = $hidingaffected['unknown'];
                unset($hidingaffected);
            }

            foreach ($reportgrader->gtree->items as $itemid => $unused) {
                $item =& $reportgrader->gtree->items[$itemid];
                $grade = $reportgrader->grades[$userid][$item->id];

                // Get the decimal points preference for this item.
                $decimalpoints = $item->get_decimals();

                if (in_array($itemid, $unknown)) {
                    $gradeval = null;
                } else if (array_key_exists($itemid, $altered)) {
                    $gradeval = $altered[$itemid];
                } else {
                    $gradeval = $grade->finalgrade;
                }

                if ($grade->grade_item->is_external_item()) {
                    if ($grd == 1 && $evalcomix->sendgradebook == 0) {
                        require($CFG->dirroot. '/blocks/evalcomix/assessment/gradeevx.php');
                        $showmessage = true;
                    }
                    if ($grd == 2 && isset($gradeval) && $evalcomix->sendgradebook == 1) {
                        include($CFG->dirroot . '/blocks/evalcomix/assessment/undone_evx.php');
                        $showmessage = true;
                    }
                    if ($grd == 3) {
                        if (isset($gradeval)) {
                            include($CFG->dirroot . '/blocks/evalcomix/assessment/undone_evx.php');
                        }
                        include($CFG->dirroot. '/blocks/evalcomix/assessment/gradeevx.php');
                        $showmessage = true;
                    }
                }
            }
        }
    }
    if ($grd == 1) {
        $DB->update_record('block_evalcomix', array('id' => $evalcomix->id, 'courseid' => $evalcomix->courseid,
            'viewmode' => $evalcomix->viewmode, 'sendgradebook' => 1));
    } else if ($grd == 2) {
        $DB->update_record('block_evalcomix', array('id' => $evalcomix->id, 'courseid' => $evalcomix->courseid,
            'viewmode' => $evalcomix->viewmode, 'sendgradebook' => 0));
    }
}
if ($grd == 1 && $showmessage == true) {
    redirect($CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'&page='.$page,
        get_string('gradessubmitted', 'block_evalcomix'), null, \core\output\notification::NOTIFY_SUCCESS);
} else if ($grd == 2 && $showmessage == true) {
    redirect($CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'&page='.$page,
        get_string('gradesdeleted', 'block_evalcomix'), null, \core\output\notification::NOTIFY_SUCCESS);
} else if ($grd == 3 && $showmessage == true) {
    redirect($CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'&page='.$page,
    get_string('gradessubmitted', 'block_evalcomix'), null, \core\output\notification::NOTIFY_SUCCESS);
}

if (isset($environment->id) && $toollist = $DB->get_records('block_evalcomix_tools', array('evxid' => $environment->id))) {
    try {
        $newgrades = block_evalcomix_webservice_client::get_assessments_modified(array('tools' => $toollist));
        if (!empty($newgrades)) {
            require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
            require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_grades.php');

            $toolids = array();
            foreach ($tasks as $task) {
                if ($assessments = $DB->get_records('block_evalcomix_assessments', array('taskid' => $task->id))) {
                    foreach ($assessments as $assessment) {
                        $activity = $task->instanceid;
                        $module = block_evalcomix_tasks::get_type_task($activity);
                        $mode = block_evalcomix_grade_report::get_type_evaluation($assessment->studentid, $courseid,
                            $assessment->assessorid);
                        $str = $courseid . '_' . $module . '_' . $activity . '_' . $assessment->studentid .
                            '_' . $assessment->assessorid . '_' . $mode . '_' . BLOCK_EVALCOMIX_MOODLE_NAME;
                        $assessmentid = md5($str);
                        if (isset($newgrades[$assessmentid])) {
                            if (is_numeric($newgrades[$assessmentid]->grade)) {
                                $grade = $newgrades[$assessmentid]->grade;
                                $toolids[] = $newgrades[$assessmentid]->toolid;
                                $assessment->grade = $grade;
                                $DB->update_record('block_evalcomix_assessments', $assessment);
                                if ($evalcomixgrade = $DB->get_record('block_evalcomix_grades', array('courseid' => $courseid,
                                'cmid' => $task->instanceid, 'userid' => $assessment->studentid))) {
                                    $params = array('cmid' => $task->instanceid, 'userid' => $assessment->studentid,
                                    'courseid' => $courseid);
                                    $finalgrade = block_evalcomix_grades::get_finalgrade_user_task($params);
                                    if ($finalgrade !== null) {
                                        $evalcomixgrade->finalgrade = $finalgrade;
                                        $DB->update_record('block_evalcomix_grades', $evalcomixgrade);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            block_evalcomix_webservice_client::set_assessments_modified(array('toolids' => $toolids));
        }
    } catch (Exception $e) {
        \core\notification::error('EvalCOMIX is not configured correctly. Please contact the administrator');
    }
}

$PAGE->set_url(new moodle_url('/blocks/evalcomix/assessment/index.php', array('id' => $courseid)));
$PAGE->set_pagelayout('incourse');
$strplural = 'evalcomix';
$PAGE->set_context($context);
$PAGE->navbar->add($strplural);
$PAGE->set_title($strplural);
$PAGE->set_pagelayout('report');
$PAGE->set_heading(get_string('pluginname', 'block_evalcomix'));
$PAGE->requires->jquery();
$PAGE->requires->css('/blocks/evalcomix/style/styles.css');

$event = \block_evalcomix\event\activity_assessor_viewed::create(array('courseid' => $course->id, 'context' => $context,
    'relateduserid' => $USER->id));
$event->trigger();

echo $OUTPUT->header();

require_once($CFG->dirroot . '/blocks/evalcomix/renderer.php');
echo block_evalcomix_renderer::display_main_menu($courseid, 'assessment');

echo '<h3 class="mb-5">'.get_string('evaluation', 'block_evalcomix').'</h3>';
require_once($CFG->dirroot . '/blocks/evalcomix/renderer.php');
$renderer = $PAGE->get_renderer('block_evalcomix');

// If $USER has editing permits.
if (is_siteadmin($USER) || has_capability('moodle/grade:viewhidden', $context)) {
    echo $renderer->display_assessmentsection_menu($courseid);
    if (has_capability('moodle/block:edit', $context)) {
        echo '
        <center>
        <fieldset class="border border-secondary w-50 mb-4">
        <legend class="text-left">
        <a href='.$CFG->wwwroot.'/grade/report/index.php?id='.$courseid.'>'.
        get_string('gradebook', 'block_evalcomix').'</a></legend>';
        // To show the correct button.
        if (isset($evalcomix->sendgradebook) && $evalcomix->sendgradebook == 0) {
            echo '<div><input type="button" value="'.get_string('sendgrades', 'block_evalcomix').'"
            onclick="if (confirm(\'' . get_string('confirm_add', 'block_evalcomix') . '\'))location.href=\''.
            $CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'&page='.$page.'&grd=1\'"/></div>';
        } else if (isset($evalcomix->sendgradebook) && $evalcomix->sendgradebook == 1) {
            echo '<div><input type="button" value="'.get_string('updategrades', 'block_evalcomix') .
            '" onclick="if (confirm(\'' . get_string('confirm_update', 'block_evalcomix') . '\'))location.href=\''.
            $CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'&page='.$page.'&grd=3\'"/>
            <input type="button" value="'.get_string('deletegrades', 'block_evalcomix') .'"
            onclick="if (confirm(\'' . get_string('confirm_delete', 'block_evalcomix') . '\'))location.href=\''.
            $CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'&page='.$page.'&grd=2\'"/></div>';
        }
        echo '</fieldset>
        </center>';
    }
}

echo '</center>';


if (!empty($studentsperpage) && $studentsperpage >= 20) {
    echo $OUTPUT->paging_bar($numusers, $reportevalcomix->page, $studentsperpage, $reportevalcomix->pbarurl);
}

echo '<div class="bg-white">';
echo $reportevalcomix->create_grade_table();
echo '</div>';

// Prints paging bar at bottom for large pages.
if (!empty($studentsperpage) && $studentsperpage >= 20) {
    echo $OUTPUT->paging_bar($numusers, $reportevalcomix->page, $studentsperpage, $reportevalcomix->pbarurl);
}

echo $OUTPUT->footer();
