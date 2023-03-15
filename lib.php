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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/grade/report/lib.php');
require_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/calculator_average.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_expert_db_block.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes_time.php');
require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');

function block_evalcomix_add_select_range($name, $ibegin, $iend, $iselected, $extra) {
    $ibegin = intval($ibegin);
    $iend = intval($iend);
    $iselected = intval($iselected);

    $result = '<select id = "'. $name . '" name = "'. $name . '" '. $extra. '>';
    for ($i = $ibegin; $i <= $iend; $i++) {
        $selected = '';
        if ($i == $iselected) {
            $selected = 'selected="selected"';
        }
        $result .= '
                <option value="'. $i . '" ' . $selected .'>'. $i . '</option>
        ';
    }
    $result .= '
        </select>
    ';
    return $result;
}

function block_evalcomix_add_select($name, $options, $iselected, $extra) {
    $tam = count($options);
    $result = '<select id = "'. $name . '" name = "'. $name . '" '. $extra. '>';
    foreach ($options as $key => $value) {
        $selected = '';
        if ($key == $iselected) {
            $selected = 'selected="selected"';
        }
        $result .= '
                <option value="'. $key . '" ' . $selected .'>'. $value . '</option>
        ';
    }
    $result .= '
        </select>
    ';
    return $result;
}

function block_evalcomix_add_date_time_selector($name, $timestamp, $extra) {
    if (!isset($timestamp) && !is_number($timestamp)) {
        $timestamp = time();
    }
    $day = date('j', $timestamp);
    $month = date('n', $timestamp);
    $year = date('Y', $timestamp);
    $hour = date('G', $timestamp);
    $minute = date('i', $timestamp);
    if ($min = $minute % 5 != 0) {
        $minute -= $min;
    }
    $months = array(1 => '"'.get_string('january', 'block_evalcomix').'"', 2 => '"'.
        get_string('february', 'block_evalcomix').'"',
        3 => '"'.get_string('march', 'block_evalcomix').'"', 4 => '"'.get_string('april', 'block_evalcomix').'"',
        5 => '"'.get_string('may', 'block_evalcomix').'"', 6 => '"'.get_string('june', 'block_evalcomix').'"', 7 => '"'.
        get_string('july', 'block_evalcomix').'"', 8 => '"'.get_string('august', 'block_evalcomix').'"', 9 => '"'.
        get_string('september', 'block_evalcomix').'"', 10 => '"'.get_string('october', 'block_evalcomix').'"', 11 => '"'.
        get_string('november', 'block_evalcomix').'"', 12 => '"'.get_string('december', 'block_evalcomix') . '"');
    $result = block_evalcomix_add_select_range('day_'. $name, 1, 31, $day, $extra);
    $result .= block_evalcomix_add_select('month_'. $name, $months, $month, $extra);
    $result .= block_evalcomix_add_select_range('year_'. $name, 2020, 2030, $year, $extra);
    $result .= block_evalcomix_add_select_range('hour_'. $name, 0, 23, $hour, $extra);
    $minutes = array(0 => '00', 5 => '05', 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 35 => 35,
        40 => 40, 45 => 45, 50 => 50, 55 => 55);
    $result .= block_evalcomix_add_select('minute_'. $name, $minutes, $minute, $extra);
    return $result;
}


/**
 * Get activity datas
 * @param object $cm  we pass it to save db access
 * @return object activity
 */
function block_evalcomix_get_activity_data($cm) {
    global $DB;
    $modname = $cm->modname;
    $activity = null;
    if (!$activity = $DB->get_record($modname, array('id' => $cm->instance))) {
        print_error('invalidid', $modname);
    }
    return $activity;
}

/**
 * @param int $courseid
 * @param object $cm course module ID
 * @return array datas of assessment modalities
 */
function block_evalcomix_get_evalcomix_activity_data($courseid, $cm) {
    global $DB, $CFG;

    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
    $result = array();
    if ($task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cm->id))) {
        $result['grademethod'] = $task->grademethod;
        $result['threshold'] = $task->threshold;
        $result['workteams'] = $task->workteams;
        if ($task->workteams == 1) {
            if ($groupcoordinators = $DB->get_records('block_evalcomix_coordinators', array('taskid' => $task->id))) {
                $coordinators = array();
                foreach ($groupcoordinators as $gc) {
                    $gcgroupid = $gc->groupid;
                    $coordinators[$gcgroupid] = $gc->userid;
                }
                if (!empty($coordinators)) {
                    $result['coordinators'] = $coordinators;
                }
            }
        }
        $result['toolEP'] = block_evalcomix_get_modality_tool($courseid, $task->id, 'teacher');
        $result['toolAE'] = block_evalcomix_get_modality_tool($courseid, $task->id, 'self');
        $result['toolEI'] = block_evalcomix_get_modality_tool($courseid, $task->id, 'peer');
        $result['weighingEP'] = block_evalcomix_get_evalcomix_modality_weighing($courseid, $task->id, 'teacher');
        $result['weighingAE'] = block_evalcomix_get_evalcomix_modality_weighing($courseid, $task->id, 'self');
        $result['weighingEI'] = block_evalcomix_get_evalcomix_modality_weighing($courseid, $task->id, 'peer');
        $timeae = block_evalcomix_get_evalcomix_modality_time($courseid, $task->id, 'self');
        if (isset($timeae['available'])) {
            $result['availableAE'] = $timeae['available'];
        }
        if (isset($timeae['timedue'])) {
            $result['timedueAE'] = $timeae['timedue'];
        }
        $timeei = block_evalcomix_get_evalcomix_modality_time($courseid, $task->id, 'peer');
        if (isset($timeei['available'])) {
            $result['availableEI'] = $timeei['available'];
        }
        if (isset($timeei['timedue'])) {
            $result['timedueEI'] = $timeei['timedue'];
        }
        $timeei = block_evalcomix_get_evalcomix_modality_extra($courseid, $task->id, 'peer');
        if (isset($timeei['anonymous'])) {
            $result['anonymousEI'] = $timeei['anonymous'];
        }
        if (isset($timeei['visible'])) {
            $result['alwaysvisibleEI'] = $timeei['visible'];
        }
        if (isset($timeei['whoassesses'])) {
            $result['whoassessesEI'] = $timeei['whoassesses'];
        }
    }
    return $result;
}

/**
 * @param int $courseid
 * @param int $taskid evalcomix task ID
 * @return array datas of assessment modalities time
 */
function block_evalcomix_get_evalcomix_modality_extra($courseid, $taskid, $modality) {
    global $DB, $CFG;
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes_extra.php');
    $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

    if (!$task = $DB->get_record('block_evalcomix_tasks', array('id' => $taskid))) {
        print_error('noevalcomixtaskid');
    }

    if ($modality != 'teacher' && $modality != 'self' && $modality != 'peer') {
        print_error('No modality');
    }

    $result = array();
    if ($mode = $DB->get_record('block_evalcomix_modes', array('taskid' => $task->id, 'modality' => $modality))) {
        if ($extra = $DB->get_record('block_evalcomix_modes_extra', array('modeid' => $mode->id))) {
            $result['anonymous'] = $extra->anonymous;
            $result['visible'] = $extra->visible;
            $result['whoassesses'] = $extra->whoassesses;
            return $result;
        }
    }
    return false;
}

/**
 * @param int $courseid
 * @param int $taskid evalcomix task ID
 * @return array datas of assessment modalities time
 */
function block_evalcomix_get_evalcomix_modality_time($courseid, $taskid, $modality) {
    global $DB, $CFG;
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes_time.php');
    $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

    if (!$task = $DB->get_record('block_evalcomix_tasks', array('id' => $taskid))) {
        print_error('noevalcomixtaskid');
    }

    if ($modality != 'teacher' && $modality != 'self' && $modality != 'peer') {
        print_error('No modality');
    }

    $result = array();
    if ($mode = $DB->get_record('block_evalcomix_modes', array('taskid' => $task->id, 'modality' => $modality))) {
        $time = $DB->get_record('block_evalcomix_modes_time', array('modeid' => $mode->id));
        $result['available'] = $time->timeavailable;
        $result['timedue'] = $time->timedue;
        return $result;
    }
    return false;

}

/**
 * @param int $courseid
 * @param int $taskid evalcomix task ID
 * @return array datas of assessment modalities weighing
 */
function block_evalcomix_get_evalcomix_modality_weighing($courseid, $taskid, $modality) {
    global $DB, $CFG;
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');
    $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

    if (!$task = $DB->get_record('block_evalcomix_tasks', array('id' => $taskid))) {
        print_error('noevalcomixtaskid');
    }

    if ($modality != 'teacher' && $modality != 'self' && $modality != 'peer') {
        print_error('No modality');
    }

    $result = array();
    if ($mode = $DB->get_record('block_evalcomix_modes', array('taskid' => $task->id, 'modality' => $modality))) {
        return $mode->weighing;
    }
    return false;

}


function block_evalcomix_get_modality_tool($courseid, $taskid, $modality) {
    global $DB;

    if ($modality != 'teacher' && $modality != 'self' && $modality != 'peer') {
        print_error('No modality');
    }

    $result = array();
    if ($mode = $DB->get_record('block_evalcomix_modes', array('taskid' => $taskid, 'modality' => $modality))) {
        if ($tool = $DB->get_record('block_evalcomix_tools', array('id' => $mode->toolid))) {
            return $tool;
        }
    }
    return false;
}

/**
 * Update database tools with Web Services tools
 * @param int $evxid primary key of evalcomix table
 * @param mixed $webtools tools of Web Service
 */
function block_evalcomix_update_tool_list($evxid, $webtools) {
    global $CFG, $DB, $COURSE;

    foreach ($webtools as $tool) {
        if (!$toolupdate = $DB->get_record('block_evalcomix_tools', array('evxid' => $evxid, 'idtool' => $tool->idtool))) {
            $now = time();
            $DB->insert_record('block_evalcomix_tools', array('evxid' => $evxid, 'title' => $tool->title, 'type' => $tool->type,
                'idtool' => (string)$tool->idtool, 'timecreated' => $now, 'timemodified' => $now));
        } else {
            $params = array('id' => $tool->id, 'evxid' => $evxid, 'title' => $tool->title, 'type' => $tool->type,
                'idtool' => (string)$tool->idtool, 'timemodified' => time());
            $DB->update_record('block_evalcomix_tools', $params);
        }
    }

    // If there are duplicate instruments from a restore, the activities are removed and configured properly.
    if ($oldtoolsrestored = $DB->get_records('block_evalcomix_tools', array('evxid' => $evxid, 'timemodified' => '-1'))) {
        require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
        require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');

        $activities = $DB->get_records('course_modules', array('course' => $COURSE->id));

        foreach ($activities as $activity) {
            if ($task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $activity->id))) {
                $module = block_evalcomix_tasks::get_type_task($task->instanceid);
                $xml = block_evalcomix_webservice_client::get_instrument($COURSE->id, $module, $task->instanceid,
                    BLOCK_EVALCOMIX_MOODLE_NAME);

                if (isset($xml->evaluacion->teacher) && $xml->evaluacion->teacher != null) {
                    if ($tool = $DB->get_record('block_evalcomix_tools', array('evxid' => $evxid,
                        'idtool' => $xml->evaluacion->teacher))) {
                        $modes = $DB->update_record('block_evalcomix_modes', array('taskid' => $task->id, 'modality' => 'teacher',
                            'toolid' => $tool->id));
                    }
                }
                if (isset($xml->evaluacion->self) && $xml->evaluacion->self != null) {
                    if ($tool = $DB->get_record('block_evalcomix_tools', array('evxid' => $evxid,
                        'idtool' => $xml->evaluacion->self))) {
                        $modes = $DB->update_record('block_evalcomix_modes', array('taskid' => $task->id, 'modality' => 'self',
                            'toolid' => $tool->id));
                    }
                }
                if (isset($xml->evaluacion->peer) && $xml->evaluacion->peer != null) {
                    if ($tool = $DB->get_record('block_evalcomix_tools', array('evxid' => $evxid,
                        'idtool' => $xml->evaluacion->peer))) {
                        $modes = $DB->update_record('block_evalcomix_modes', array('taskid' => $task->id, 'modality' => 'peer',
                            'toolid' => $tool->id));
                    }
                }
            }
        }
        // Delete old restored tools.
        foreach ($oldtoolsrestored as $tool) {
            $DB->delete_records('block_evalcomix_tools', array('id' => $tool->id));
        }
    }
}

/**
 * @param object $a tool object
 * @param object $b tool object
 * return relative position between objects depending on title tool
 */
function block_evalcomix_cmp_title_tool($a, $b) {
    return strcmp(strtolower($a->title), strtolower($b->title));
}

/**
 * @param object $a tool object
 * @param object $b tool object
 * return relative position between objects depending on type tool
 */
function block_evalcomix_cmp_type_tool($a, $b) {
    $value = strcmp(strtolower(get_string($a->type, 'block_evalcomix')), strtolower(get_string($b->type, 'block_evalcomix')));
    return $value;
}

/**
 * It gets course activities
 * @param array $params['courseid']
 * @param array $params['context']
 * @param array $params['modality'] ['teacher' | 'self' | 'peer']
 * @return array $elements[cmid] or false
 */
function block_evalcomix_get_elements_course($params) {
    global $CFG, $DB;
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_report.php');
    if (isset($params['courseid']) && isset($params['context']) && isset($params['modality'])) {
        $courseid = $params['courseid'];
        $context = $params['context'];
        $modality = $params['modality'];
    } else {
        return false;
    }

    $reportevalcomix = new block_evalcomix_grade_report($courseid, null, $context);

    $levels = $reportevalcomix->gtree->get_levels();
    $selfusers = array();
    $elements = array();
    foreach ($levels as $row) {
        foreach ($row as $element) {
            if ($element['type'] == 'item' || $element['type'] == 'categoryitem') {
                $itemtype = $element['object']->itemtype;
                $itemmodule = $element['object']->itemmodule;
                $iteminstance = $element['object']->iteminstance;

                if ($itemtype == 'mod' && $iteminstance && $itemmodule) {
                    if ($cm = get_coursemodule_from_instance($itemmodule, $iteminstance, $courseid)) {
                        if ($task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cm->id))) {
                            if ($mode = $DB->get_record('block_evalcomix_modes', array('taskid' => $task->id,
                                'modality' => $modality))) {
                                $cmid = $cm->id;
                                $elements[$cmid] = $element;
                            }
                        }
                    }
                }
            }
        }
    }
    return $elements;
}

function block_evalcomix_recalculate_grades() {
    global $CFG, $DB;
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_grades.php');
    if ($grades = $DB->get_records('block_evalcomix_grades', array())) {
        foreach ($grades as $grade) {
            $userid = $grade->userid;
            $cmid = $grade->cmid;
            $courseid = $grade->courseid;

            if ($cm = $DB->get_record('course_modules', array('id' => $cmid))) {
                $params = array('cmid' => $cmid, 'userid' => $userid, 'courseid' => $courseid);
                $finalgrade = block_evalcomix_grades::get_finalgrade_user_task($params);
                if ($finalgrade !== null && (int)$finalgrade > -1 && (int)$finalgrade !== (int)$grade->finalgrade) {
                    $DB->update_record('block_evalcomix_grades', array('id' => $grade->id, 'userid' => $grade->userid,
                        'cmid' => $grade->cmid, 'finalgrade' => $finalgrade, 'courseid' => $grade->courseid));
                }
            }
        }
    }
    return true;
}

function block_evalcomix_activity_assessed($cm, $studentid = array()) {
    global $DB;
    $result = false;

    if ($task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cm->id))) {
        if ($modes = $DB->get_records('block_evalcomix_modes', array('taskid' => $task->id))) {
            $modetypes = array();
            foreach ($modes as $mode) {
                $modality = $mode->modality;
                $modetypes[$modality] = $modality;
            }
            $sql = 'SELECT *
            FROM {block_evalcomix_assessments}
            WHERE taskid = ?';
            if (!empty($studentid)) {
                $sql .= ' AND studentid IN ('.implode(',', $studentid).')';
            }
            if ($assessments = $DB->get_records_sql($sql, array('taskid' => $task->id))) {
                $context = context_course::instance($cm->course);
                foreach ($assessments as $assessment) {
                    if ($assessment->studentid == $assessment->assessorid) {
                        if (!isset($modetypes['self'])) {
                            continue;
                        }
                    } else {
                        if (has_capability('moodle/grade:viewhidden', $context, $assessment->assessorid)) {
                            if (!isset($modetypes['teacher'])) {
                                continue;
                            }
                        } else {
                            if (!isset($modetypes['peer'])) {
                                continue;
                            }
                        }
                    }
                    if (is_enrolled($context, $assessment->studentid, 'mod/choice:choose', true)) {
                        $result = true;
                    }
                }
            }
        }
    }

    return $result;
}
