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

defined('MOODLE_INTERNAL') || die();

define('BLOCK_EVALCOMIX_GRADE_METHOD_WA_ALL', 1);
define('BLOCK_EVALCOMIX_GRADE_METHOD_WA_SMART', 2);
define('BLOCK_EVALCOMIX_GRADE_METHOD_WA_SMART_WITH_REDUCTION', 3);
define('BLOCK_EVALCOMIX_GRADE_METHOD_OVERVALUATION', 15);
define('BLOCK_EVALCOMIX_GRADE_METHOD_COLOR_EI_EXTREME', 'color-extreme');
define('BLOCK_EVALCOMIX_GRADE_METHOD_COLOR_EI_MILD', 'color-mild');
define('BLOCK_EVALCOMIX_GRADE_METHOD_COLOR_UPPER', 'color-upper');
define('BLOCK_EVALCOMIX_GRADE_METHOD_COLOR_LOWER', 'color-lower');
define('BLOCK_EVALCOMIX_GRADE_METHOD_BGCOLOR_EI_EXTREME', 'bg-extreme');
define('BLOCK_EVALCOMIX_GRADE_METHOD_BGCOLOR_EI_MILD', 'bg-mild');
define('BLOCK_EVALCOMIX_GRADE_METHOD_BGCOLOR_UPPER', 'bg-upper');
define('BLOCK_EVALCOMIX_GRADE_METHOD_BGCOLOR_LOWER', 'bg-lower');
define('BLOCK_EVALCOMIX_GRADE_METHOD_MIN_PEERS', 4);

require_once('evalcomix_object.php');
require_once('evalcomix_modes.php');

/**
 * Define class block_evalcomix_grades
 *
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

/**
 * Manage grades
 *
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
class block_evalcomix_grades extends block_evalcomix_object {
    /**
     * Table name
     * @var string $table
     */
    public $table = 'block_evalcomix_grades';

    /**
     * Array of required table fields, must start with 'id'.
     * @var array $requiredfields
     */
    public $requiredfields = ['id', 'userid', 'cmid', 'finalgrade', 'courseid'];

    /**
     * Array of optional table fields.
     * @var array $requiredfields
     */
    public $optionalfields = [];

    /**
     * course_module ID
     * @var int $cmid
     */
    public $cmid;

    /**
     * student ID associated
     * @var int $userid
     */
    public $userid;

    /**
     * Finalgrade
     * @var float $finalgrade
     */
    public $finalgrade;

    /**
     * @var int $courseid
     */
    public $courseid;

    /**
     * Constructor
     *
     * @param int $params[id] ID
     * @param int $params[cmid] //foreign key of table 'course_modules'
     * @param int $params[userid] //foreign key of table 'user'
     * @param float $params[finalgrade] Grade
     */
    public function __construct($params = []) {
        if (isset($params['id'])) {
            $this->id = intval($params['id']);
        }
        if (isset($params['finalgrade'])) {
            $this->finalgrade = floatval($params['finalgrade']);
        }
        if (isset($params['courseid'])) {
            $this->courseid = intval($params['courseid']);
        }

        // Por si queremos crear una instancia vacía (para usar evalcomix_object::fetch_all_helper es necesario).
        if (isset($params['cmid']) && is_numeric($params['cmid']) && !is_float($params['cmid']) && (int)$params['cmid'] > 0) {
            $this->cmid = $params['cmid'];
        } else {
            $this->cmid = 0;
        }

        // Por si queremos crear una instancia vacía (para usar evalcomix_object::fetch_all_helper es necesario).
        if (isset($params['userid']) && is_numeric($params['userid']) && !is_float($params['userid']) && $params['userid'] > '0') {
            $this->userid = (int)$params['userid'];
        } else {
            $this->userid = 0;
        }
    }

    /**
     * Calculates finalgrade for a student in a activity
     * @param int $params[cmid] course module id
     * @param int $params[userid] student id
     * @return the finalgrade or -1 if there is only peer-assessment but assessment period hasn't finished
     * or -2 if there is not any assessment
     */
    public static function get_finalgrade_user_task($params) {
        global $CFG, $DB;

        if (!isset($params['cmid']) || !isset($params['userid']) || !isset($params['courseid'])) {
            return null;
        }
        $cmid = $params['cmid'];
        $userid = $params['userid'];
        $courseid = $params['courseid'];

        $coursecontext = context_course::instance($courseid);

        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
        if (!$task = $DB->get_record('block_evalcomix_tasks', ['instanceid' => $cmid])) {
            return null;
        }

        return self::calculate_finalgrades($coursecontext, $task, $userid);
    }

    /**
     * Get EvalCOMIX finalgrades for the assessment table
     * @var int courseid
     * @return array with finalgrades by cmid and userid
     */
    public static function get_grades($courseid) {
        global $DB;
        $result = [];
        if ($finalgrades = $DB->get_records('block_evalcomix_grades', ['courseid' => $courseid])) {
            foreach ($finalgrades as $finalgrade) {
                $userid = $finalgrade->userid;
                $cmid = $finalgrade->cmid;
                $result[$cmid][$userid] = $finalgrade->finalgrade;
            }
        }
        return $result;
    }

    /**
     * Recovery mode data
     *
     * @param int $taskid
     * @param int $now
     * @return array mode data
     */
    public static function load_task_modes($taskid, $now) {
        global $DB;
        $paramsmodes = ['taskid' => $taskid];
        $modes = $DB->get_records('block_evalcomix_modes', $paramsmodes);
        if (!$modes) {
            return null;
        }

        $inperiod = false;
        $inselfperiod = false;
        $teacherweight = -1;
        $selfweight = -1;
        $peerweight = -1;

        $modeeitime = null;
        $modeaetime = null;

        // Obtains activity´s weights.
        foreach ($modes as $mode) {
            switch ($mode->modality) {
                case 'teacher':
                    $teacherweight = $mode->weighing;
                    break;
                case 'self':
                    $selfweight = $mode->weighing;
                    $modeaetime = $DB->get_record('block_evalcomix_modes_time', ['modeid' => $mode->id]);
                    if ($modeaetime && $now >= $modeaetime->timeavailable && $now <= $modeaetime->timedue) {
                        $inselfperiod = true;
                    }
                    break;
                case 'peer':
                    $peerweight = $mode->weighing;
                    $modeeitime = $DB->get_record('block_evalcomix_modes_time', ['modeid' => $mode->id]);
                    if ($modeeitime && $now >= $modeeitime->timeavailable && $now <= $modeeitime->timedue) {
                        $inperiod = true;
                    }
                    break;
                default:
            }
        }

        return ['teacherweight' => $teacherweight, 'selfweight' => $selfweight, 'peerweight' => $peerweight,
            'inperiod' => $inperiod, 'inselfperiod' => $inselfperiod];
    }

    /**
     * classify assessments by modality
     *
     * @param array $assessments
     * @param object $coursecontext
     * @param array $weights
     * @param bool $inperiod
     * @return array assessments by modality
     */
    public static function classify_assessments($assessments, $coursecontext, $weights, $inperiod) {
        $selfgrade = -1;
        $teachergrade = 0;
        $setteachergrades = [];
        $numteachers = 0;
        $peergrades = [];
        $numpeers = 0;
        $peergradesexist = false;

        foreach ($assessments as $assessment) {
            // If it is a self assessment.
            if ($assessment->studentid == $assessment->assessorid && $weights['selfweight'] != -1) {
                $selfgrade = $assessment->grade;
            } else if (has_capability('moodle/grade:viewhidden', $coursecontext, $assessment->assessorid)) {
                // If it is a teacher assessment.
                if ($weights['teacherweight'] != -1) {
                    $teachergrade += $assessment->grade;
                    $numteachers++;
                    $setteachergrades[] = $assessment->grade;
                }
            } else if ($assessment->studentid != $assessment->assessorid && $weights['peerweight'] != -1) {
                // If it is a peer assessment.
                // Only gets grades when the assessment period in the task is finished.
                $peergradesexist = true;
                if (!$inperiod) {
                    $peergrades[] = $assessment->grade;
                }
            }
        }

        // Calculates teacher's grade.
        if ($numteachers > 0) {
            $teachergrade = round($teachergrade / $numteachers, 2);
        }
        $numpeers = count($peergrades);

        return ['teachergrade' => $teachergrade, 'selfgrade' => $selfgrade, 'peergrades' => $peergrades,
            'numteachers' => $numteachers, 'numpeers' => $numpeers, 'peergradesexist' => $peergradesexist,
            'setteachergrades' => $setteachergrades];
    }

    /**
     * Calculate finalgrade for method all
     *
     * @param array $modedata
     * @param array $gradedata
     * @return int Finalgrade
     */
    public static function calculate_finalgrades_method_all($modedata, $gradedata) {
        // Extracts $teacherweight, $selfweight, $peerweight, $inperiod, $inselfperiod and setteachergrades.
        $teacherweight = $modedata['teacherweight'];
        $selfweight = $modedata['selfweight'];
        $peerweight = $modedata['peerweight'];
        $inperiod = $modedata['inperiod'];

        // Extracts $teachergrade, $selfgrade, $peergrades, $numteachers, $numpeers and $peergradesexist.
        $teachergrade = $gradedata['teachergrade'];
        $selfgrade = $gradedata['selfgrade'];
        $peergrades = $gradedata['peergrades'];
        $numteachers = $gradedata['numteachers'];
        $numpeers = $gradedata['numpeers'];
        $peergradesexist = $gradedata['peergradesexist'];

        $result = null;

        // Calculates peergrade.
        $peergrade = 0;
        if (!empty($peergrades)) {
            foreach ($peergrades as $item) {
                $peergrade += $item;
            }
        }

        if ($numpeers > 0) {
            $peergrade = round($peergrade / $numpeers, 2);
        }
        // Calculates the total grade.
        if ($numteachers > 0 || ($numpeers > 0 && $peergradesexist == true) || $selfgrade != -1) {
            if ($selfgrade == -1) {
                $selfgrade = 0;
            }
            $result = $selfgrade * ($selfweight / 100) + $teachergrade * ($teacherweight / 100) +
                $peergrade * ($peerweight / 100);
        } else if ($inperiod == true) {
            // There is peer assessments but assessment period hasn't finished.
            $result = -1;
        } else {
            $result = -2;
        }
        return $result;
    }

    /**
     * Calculate finalgrade for method no extreme
     *
     * @param object $task
     * @param array $modedata
     * @param array $gradedata
     * @param bool $withreduction
     * @return int Finalgrade
     */
    public static function calculate_finalgrades_method_no_extreme($task, $modedata, $gradedata, $withreduction = false) {
        // Extracts $teacherweight, $selfweight, $peerweight, $inperiod and $inselfperiod.
        $teacherweight = $modedata['teacherweight'];
        $selfweight = $modedata['selfweight'];
        $peerweight = $modedata['peerweight'];
        $inperiod = $modedata['inperiod'];
        $inselfperiod = $modedata['inselfperiod'];

        // Extracts $teachergrade, $selfgrade, $peergrades, $numteachers, $numpeers and $peergradesexist.
        $teachergrade = $gradedata['teachergrade'];
        $setteachergrades = $gradedata['setteachergrades'];
        $selfgrade = $gradedata['selfgrade'];
        $peergrades = $gradedata['peergrades'];
        $numteachers = $gradedata['numteachers'];
        $numpeers = $gradedata['numpeers'];

        $key =
            ($teacherweight > -1 ? 'T' : '') .
            ($selfweight > -1 ? 'S' : '') .
            ($peerweight > -1 ? 'P' : '');

        $result = null;
        switch ($key) {
            case 'TSP':
                // Teacher + Self + Peer modes.
                if ($numteachers > 0) {
                    $result += $teachergrade * ($teacherweight / 100);
                    $result += self::teacher_self_grade(
                        $selfgrade,
                        $selfweight,
                        $teachergrade,
                        $inselfperiod,
                        $setteachergrades,
                        $task,
                        $withreduction
                    );
                    $result += self::teacher_peer_grade(
                        $peergrades,
                        $peerweight,
                        $teachergrade,
                        $inperiod,
                        $setteachergrades,
                        $task
                    );
                } else {
                    return -1;
                }
                break;
            case 'TS':
                // Teacher + Self modes.
                if ($numteachers > 0) {
                    $result += $teachergrade * ($teacherweight / 100);
                    $result += self::teacher_self_grade(
                        $selfgrade,
                        $selfweight,
                        $teachergrade,
                        $inselfperiod,
                        $setteachergrades,
                        $task,
                        $withreduction
                    );
                } else {
                    return -1;
                }
                break;
            case 'TP':
                // Teacher + Peer modes.
                if ($numteachers > 0) {
                    $result += $teachergrade * ($teacherweight / 100);
                    $result += self::teacher_peer_grade(
                        $peergrades,
                        $peerweight,
                        $teachergrade,
                        $inperiod,
                        $setteachergrades,
                        $task
                    );
                } else {
                    return -1;
                }
                break;
            case 'T':
                // Teacher mode.
                if ($teachergrade > 0) {
                    $result = $teachergrade * ($teacherweight / 100);
                } else {
                    $result = 0;
                }
                break;
            case 'SP':
                // Self + Peer modes.
                $result = self::peer_self_grade(
                    $task,
                    $inperiod,
                    $peergrades,
                    $numpeers,
                    $peerweight,
                    $selfgrade,
                    $selfweight,
                    $withreduction
                );
                break;
            case 'S':
                // Self mode.
                if ($selfgrade > 0) {
                    $result = $selfgrade * ($selfweight / 100);
                } else {
                    $result = 0;
                }
                break;
            case 'P':
                // Peer mode.
                if (!$inperiod) {
                    $setofgrades = [];
                    foreach ($peergrades as $grade) {
                        if (!self::is_extreme_grade($grade, $peergrades)) {
                            $setofgrades[] = $grade;
                        }
                    }
                    if (count($setofgrades) > 0) {
                        $peergrade = self::average($setofgrades);
                        $peergrade = round($peergrade, 2);
                        $result = $peergrade * ($peerweight / 100);
                    } else {
                        return -1;
                    }
                } else {
                    return -1;
                }
                break;
            default:
                return -3;
        }

        return $result;
    }

    /**
     * Calculate finalgrades
     *
     * @param object $coursecontext
     * @param object $task
     * @param int $userid
     * @return int Finalgrade
     */
    public static function calculate_finalgrades($coursecontext, $task, $userid) {
        global $CFG, $DB;
        if (empty($coursecontext) || empty($task)) {
            return null;
        }

        $result = null;
        $now = time();

        $modedata = self::load_task_modes($task->id, $now);
        if (!$modedata) {
            return -3;
        }

        $params2 = ['taskid' => $task->id, 'studentid' => $userid];
        if (!$assessments = $DB->get_records('block_evalcomix_assessments', $params2)) {
            return null;
        }

        $weights = [
            'teacherweight' => $modedata['teacherweight'],
            'selfweight' => $modedata['selfweight'],
            'peerweight' => $modedata['peerweight'],
        ];
        $gradedata = self::classify_assessments($assessments, $coursecontext, $weights, $modedata['inperiod']);

        require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');
        if ($task->grademethod == BLOCK_EVALCOMIX_GRADE_METHOD_WA_ALL) {
            $result = self::calculate_finalgrades_method_all($modedata, $gradedata);
        } else if ($task->grademethod == BLOCK_EVALCOMIX_GRADE_METHOD_WA_SMART) {
            $result = self::calculate_finalgrades_method_no_extreme($task, $modedata, $gradedata);
        } else if ($task->grademethod == BLOCK_EVALCOMIX_GRADE_METHOD_WA_SMART_WITH_REDUCTION) {
            $result = self::calculate_finalgrades_method_no_extreme($task, $modedata, $gradedata, true);
        }
        return $result;
    }

    /**
     * get_main_set_of_grades
     *
     * @param int $taskid
     * @param array $assessment
     * @return array main set of grades
     */
    public static function get_main_set_of_grades($taskid, $assessment) {
        global $COURSE, $DB;
        $result = new stdClass();
        $result->mode = '';
        $result->mainsetofgrades = [];
        $context = context_course::instance($COURSE->id);

        $assessments = [];
        $teachermode = null;
        $peermode = null;
        $paramsmodes = ['taskid' => $taskid];
        if ($modes = $DB->get_records('block_evalcomix_modes', $paramsmodes)) {
            foreach ($modes as $mode) {
                switch ($mode->modality) {
                    case 'teacher':
                        $teachermode = $mode;
                        break;
                    case 'peer':
                        $peermode = $mode;
                }
            }
            if (!empty($teachermode)) {
                $result->mode = 'teacher';
            } else if (!empty($peermode)) {
                $result->mode = 'peer';
            }

            if (
                !empty($result->mode) && $assessments = $DB->get_records(
                    'block_evalcomix_assessments',
                    ['studentid' => $assessment->studentid, 'taskid' => $taskid]
                )
            ) {
                foreach ($assessments as $item) {
                    if (!empty($teachermode)) {
                        if (has_capability('moodle/grade:viewhidden', $context, $item->assessorid)) {
                            $result->mainsetofgrades[] = $item->grade;
                        }
                    } else if (!empty($peermode)) {
                        if ($item->assessorid != $item->studentid) {
                            $result->mainsetofgrades[] = $item->grade;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * is_extreme_grade
     *
     * @param int $grade
     * @param array $setofgrades
     * @return bool
     */
    public static function is_extreme_grade($grade, $setofgrades) {
        $result = false;

        if (!is_numeric($grade) || !is_array($setofgrades) || empty($setofgrades)) {
            return null;
        }

        $countgrades = count($setofgrades);
        $average = self::average($setofgrades);
        $standarddeviation = self::standard_deviation($setofgrades);

        if (
            $standarddeviation && $countgrades > BLOCK_EVALCOMIX_GRADE_METHOD_MIN_PEERS
                && (($grade >= ($average + 1.5 * $standarddeviation))
                || ($grade <= ($average - 1.5 * $standarddeviation)))
        ) {
            $result = true;
        }

        return $result;
    }

    /**
     * is_mild_grade
     *
     * @param int $grade
     * @param array $setofgrades
     * @return bool
     */
    public static function is_mild_grade($grade, $setofgrades) {
        $result = false;

        if (!is_numeric($grade) || !is_array($setofgrades) || empty($setofgrades)) {
            return null;
        }

        $countgrades = count($setofgrades);
        $average = self::average($setofgrades);
        $standarddeviation = self::standard_deviation($setofgrades);

        if (
            $standarddeviation && $countgrades > BLOCK_EVALCOMIX_GRADE_METHOD_MIN_PEERS
                && (($grade >= ($average + $standarddeviation))
                || ($grade <= ($average - $standarddeviation)))
        ) {
            $result = true;
        }

        return $result;
    }

    /**
     * is_upper_grade
     *
     * @param int $grade
     * @param array $setofgrades
     * @param int $limit
     * @return bool
     */
    public static function is_upper_grade($grade, $setofgrades, $limit = BLOCK_EVALCOMIX_GRADE_METHOD_OVERVALUATION) {
        $result = false;

        $average = self::average($setofgrades);
        if ($average !== null) {
            if (($grade > ($average + $limit))) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * is_lower_grade
     *
     * @param int $grade
     * @param array $setofgrades
     * @param int $limit
     * @return bool
     */
    public static function is_lower_grade($grade, $setofgrades, $limit = BLOCK_EVALCOMIX_GRADE_METHOD_OVERVALUATION) {
        $result = false;

        $average = self::average($setofgrades);
        if ($average !== null) {
            if (($grade < ($average - $limit))) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * average
     *
     * @param array $setofgrades
     * @return int average
     */
    public static function average($setofgrades) {
        $countgrades = count($setofgrades);
        $average = null;
        if ($countgrades > 0) {
            $average = array_sum($setofgrades) / $countgrades;
        }
        return $average;
    }

    /**
     * standard deviation
     *
     * @param array $setofgrades
     * @return int standard deviation
     */
    public static function standard_deviation($setofgrades) {
        if (empty($setofgrades)) {
            return null;
        }

        $countgrades = count($setofgrades);
        $average = self::average($setofgrades);

        $sum = 0;
        for ($i = 0; $i < $countgrades; ++$i) {
            $sum += ($setofgrades[$i] - $average) * ($setofgrades[$i] - $average);
        }

        $variance = $sum / $countgrades;
        return sqrt($variance);
    }

    /**
     * standard deviation
     *
     * @param array $setofgrades
     * @return int standard deviation
     */
    public static function teacher_peer_grade($peergrades, $peerweight, $teachergrade, $inperiod, $setteachergrades, $task) {
        $result = 0;
        if (!$inperiod) {
            $setofgrades = [];
            foreach ($peergrades as $grade) {
                if (
                    !self::is_upper_grade($grade, $setteachergrades, $task->threshold)
                        && !self::is_lower_grade($grade, $setteachergrades, $task->threshold)
                ) {
                    $setofgrades[] = $grade;
                }
            }

            if (count($setofgrades) > 0) {
                $peergrade = self::average($setofgrades);
                $peergrade = round($peergrade, 2);
                $result += $peergrade * ($peerweight / 100);
            } else {
                $result += $teachergrade * ($peerweight / 100);
            }
        }
        return $result;
    }

    /**
     * teacher_self_grade
     *
     * @param int $selfgrade
     * @param int $selfweight
     * @param int $teachergrade
     * @param bool $inselfperiod
     * @param array $setteachergrades
     * @param object $task
     * @param bool $withreduction
     * @return int grade
     */
    public static function teacher_self_grade(
        $selfgrade,
        $selfweight,
        $teachergrade,
        $inselfperiod,
        $setteachergrades,
        $task,
        $withreduction = false
    ) {
        $result = 0;
        if ($selfgrade >= 0) {
            if (
                self::is_upper_grade($selfgrade, $setteachergrades, $task->threshold)
                    || self::is_lower_grade($selfgrade, $setteachergrades, $task->threshold)
            ) {
                if (!$withreduction) {
                    $result += $teachergrade * ($selfweight / 100);
                }
            } else {
                $result += $selfgrade * ($selfweight / 100);
            }
        } else {
            if (!$inselfperiod && !$withreduction) {
                $result += $teachergrade * ($selfweight / 100);
            }
        }
        return $result;
    }

    /**
     * peer_self_grade
     *
     * @param object $task
     * @param bool $inperiod
     * @param array $peergrades
     * @param int $numpeers
     * @param int $peerweight
     * @param int $selfgrade
     * @param int $selfweight
     * @param bool $withreduction
     * @return int grade
     */
    public static function peer_self_grade(
        $task,
        $inperiod,
        $peergrades,
        $numpeers,
        $peerweight,
        $selfgrade,
        $selfweight,
        $withreduction = false
    ) {
        if ($inperiod) {
            return -1;
        }
        $setofgrades = [];
        foreach ($peergrades as $grade) {
            if (!self::is_extreme_grade($grade, $peergrades)) {
                $setofgrades[] = $grade;
            }
        }
        if (count($setofgrades) > 0) {
            $peergrade = self::average($setofgrades);
            $peergrade = round($peergrade, 2);
            if ($selfgrade >= 0) {
                if (
                    $numpeers > BLOCK_EVALCOMIX_GRADE_METHOD_MIN_PEERS
                        && (self::is_upper_grade($selfgrade, $setofgrades, $task->threshold)
                        || self::is_lower_grade($selfgrade, $setofgrades, $task->threshold))
                ) {
                    $result = $peergrade * ($selfweight / 100) + $peergrade * ($peerweight / 100);
                    if ($withreduction) {
                        $result = $peergrade * ($peerweight / 100);
                    }
                } else {
                    $result = $selfgrade * ($selfweight / 100) + $peergrade * ($peerweight / 100);
                }
            } else {
                $result = $peergrade * ($peerweight / 100);
            }
        } else {
            $result = $selfgrade * ($selfweight / 100);
        }
        return $result;
    }

    /**
     * is out of range
     *
     * @param object $task
     * @param array $setofgrades
     * @param object $assessment
     * @return bool
     */
    public static function is_out_of_range($task, $setofgrades, $assessment) {
        $outofrange = false;
        if ($task->grademethod == BLOCK_EVALCOMIX_GRADE_METHOD_WA_ALL || empty($setofgrades->mainsetofgrades)) {
            return false;
        }

        $grade = $assessment->grade;
        $mainsetofgrades = $setofgrades->mainsetofgrades;
        $mode = $setofgrades->mode;

        if ($mode == 'peer') {
            $countmainsetofgrades = count($mainsetofgrades);
            if (
                $assessment->assessorid == $assessment->studentid
                    && $countmainsetofgrades > BLOCK_EVALCOMIX_GRADE_METHOD_MIN_PEERS
                    && (self::is_upper_grade(
                        $grade,
                        $mainsetofgrades,
                        $task->threshold
                    )
                    || self::is_lower_grade(
                        $grade,
                        $mainsetofgrades,
                        $task->threshold
                    ))
            ) {
                $outofrange = true;
            } else if (
                $assessment->assessorid != $assessment->studentid
                    && self::is_extreme_grade($grade, $mainsetofgrades)
            ) {
                $outofrange = true;
            }
        } else {
            if (
                self::is_upper_grade(
                    $grade,
                    $mainsetofgrades,
                    $task->threshold
                )
                    || self::is_lower_grade(
                        $grade,
                        $mainsetofgrades,
                        $task->threshold
                    )
            ) {
                $outofrange = true;
            }
        }

        return $outofrange;
    }
}
