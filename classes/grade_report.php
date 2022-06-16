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
 * Class providing an API for the grader report building and displaying.
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/gradelib.php');
require_once($CFG->dirroot . '/grade/lib.php');
require_once($CFG->libdir . '/tablelib.php');
require_once($CFG->libdir . '/grade/grade_item.php');
require_once($CFG->dirroot . '/blocks/evalcomix/lib.php');

class block_evalcomix_grade_report extends grade_report {

    /**
     * Object responsible for working out the operation between Moodle and EvalCOMIX grades
     * @var mixed $calculator
     */
    public $calculator;

    /**
     * The EvalCOMIX final grades.
     * @var mixed $gradeviewer
     */
    public $gradeviewer;

    /**
     * The id of the grade_item by which this report will be sorted.
     * The id of the grade_item by which this report will be sorted.
     * @var int $sortitemid
     */
    public $sortitemid;

    /**
     * Sortorder used in the SQL selections.
     * @var int $sortorder
     */
    public $sortorder;

    /**
     * An SQL fragment affecting the search for users.
     * @var string $userselect
     */
    public $userselect;

    /**
     * The bound params for $userselect
     * @var array $userselectparams
     */
    public $userselectparams = array();

    /**
     * cm_ids and names of the table activities
     * @var array $activities of two dimensions ['id']['name']
     */
    public $activities = array();

    /**
     * Indicates if $cmid es configured with evalcomix
     * @var array $activitiesconfigured [$cmid]
     */
    public $activitiesconfigured = array();

    /**
     * Array of course_modules
     * @var array $cm [$cmid]
     */
    public $cm = array();

    /**
     * @var int $studentsperpage
     */
    public $studentsperpage = 50;

    /**
     * Capability check caching
     *
     */
    public $canviewhidden;

    /**
     * Array of Course Groups
     */
    public $coursegroups = array();

    /**
     * Array of Course Groupings
     */
    public $coursegroupings = array();

    public $groupwheresql;

    /**
     * Constructor. Sets local copies of user preferences and initialises grade_tree.
     * @param int $courseid
     * @param object $gpr grade plugin return tracking object
     * @param string $context
     * @param int $page The current page being viewed (when report is paged)
     * @param int $sortitemid The id of the grade_item by which to sort the table
     */
    public function __construct($courseid, $gpr, $context, $page=null, $sortitemid=null) {
        global $CFG, $USER;
        parent::__construct($courseid, $gpr, $context, $page);

        // Basado en el constructor de la clase grade_report_grader.

        // Grab the grade_tree for this course.
        $this->gtree = new grade_tree($this->courseid, false, false);
        $this->add_activities_to_gtree();

        $this->gradeviewer = new block_evalcomix_grade_expert_db_block();
        $this->calculator = new block_evalcomix_calculator_average();

        $this->sortitemid = $sortitemid;

        // Base url for sorting by first/last name.

        $this->baseurl = new moodle_url('index.php', array('id' => $this->courseid));
        $this->studentsperpage = $this->get_pref('studentsperpage');
        $studentsperpage = $this->studentsperpage;
        if (!empty($studentsperpage)) {
            $this->baseurl->params(array('perpage' => $studentsperpage, 'page' => $this->page));
        }

        $this->pbarurl = new moodle_url('/blocks/evalcomix/assessment/index.php',
        array('id' => $this->courseid, 'perpage' => $studentsperpage));

        $this->setup_groups();

        $this->setup_sortitemid();

        $this->canviewhidden = has_capability('moodle/grade:viewhidden', context_course::instance($this->course->id));

        $coursegroups = $this->load_groups();
        $coursegroupings = $this->load_groupings();
    }

    /**
     * Add activities that are not in mdl_grade_item table to gtree
     */
    private function add_activities_to_gtree() {
        global $DB;

        $itemmodules = array('forum', 'glossary', 'data', 'wiki');
        $levels = $this->gtree->get_levels();
        // Obtiene las actividades que hay en la tabla mdl_grade_items del curso en el que estemos.
        foreach ($itemmodules as $itemmodule) {

            // Para que se salte los foros de novedades.
            if ($itemmodule == 'forum') {
                $activities = $DB->get_records($itemmodule, array('course' => $this->courseid, 'type' => 'general'));
            } else {
                $activities = $DB->get_records($itemmodule, array('course' => $this->courseid));
            }

            foreach ($activities as $activity) {

                $exists = false;

                foreach ($levels as $row) {
                    foreach ($row as $element) {
                        if (isset($element['object']->iteminstance) && ($activity->id == $element['object']->iteminstance) &&
                        ($itemmodule == $element['object']->itemmodule)) {
                            $exists = true;
                        }
                    }
                }

                if (!$exists) {
                    // Parámetros del nuevo grade_item a introducir 'categoryid' => $activity->id.
                    $activityparams = array ('courseid' => $this->courseid, 'itemname' => $activity->name, 'itemtype' => 'mod',
                                        'itemmodule' => $itemmodule, 'iteminstance' => $activity->id, 'itemnumber' => 0);

                    // Creación del objeto.
                    $gradeitem = new grade_item($activityparams, false);

                    // Para llevar la cuenta del números de items.
                    $numitem = count($this->gtree->levels[1]);

                    // Parámetros para rellenar el array.
                    $activityparams2 = array ('object' => $gradeitem, 'type' => 'item', 'depth' => 1,
                        'prev' => $numitem, 'next' => 0);

                    // Indicamos que el que estaba anterior ya no va a ser el último elemento del array.
                    $this->gtree->levels[1][$numitem - 1]['next'] = $numitem + 1;
                    // Añadimos el item al array.
                    $this->gtree->levels[1][] = $activityparams2;
                }

            }
        }
    }

    /**
     * Setting the sort order, this depends on last state
     * all this should be in the new table class that we might need to use
     * for displaying grades.
     */
    private function setup_sortitemid() {

        global $SESSION;

        if ($this->sortitemid) {
            if (!isset($SESSION->gradeuserreport->sort)) {
                if ($this->sortitemid == 'firstname' || $this->sortitemid == 'lastname') {
                    $this->sortorder = $SESSION->gradeuserreport->sort = 'ASC';
                } else {
                    $this->sortorder = $SESSION->gradeuserreport->sort = 'DESC';
                }
            } else {
                // This is the first sort, i.e. by last name.
                if (!isset($SESSION->gradeuserreport->sortitemid)) {
                    if ($this->sortitemid == 'firstname' || $this->sortitemid == 'lastname') {
                        $this->sortorder = $SESSION->gradeuserreport->sort = 'ASC';
                    } else {
                        $this->sortorder = $SESSION->gradeuserreport->sort = 'DESC';
                    }
                } else if ($SESSION->gradeuserreport->sortitemid == $this->sortitemid) {
                    // Same as last sort.
                    if ($SESSION->gradeuserreport->sort == 'ASC') {
                        $this->sortorder = $SESSION->gradeuserreport->sort = 'DESC';
                    } else {
                        $this->sortorder = $SESSION->gradeuserreport->sort = 'ASC';
                    }
                } else {
                    if ($this->sortitemid == 'firstname' || $this->sortitemid == 'lastname') {
                        $this->sortorder = $SESSION->gradeuserreport->sort = 'ASC';
                    } else {
                        $this->sortorder = $SESSION->gradeuserreport->sort = 'DESC';
                    }
                }
            }
            $SESSION->gradeuserreport->sortitemid = $this->sortitemid;
        } else {
            // Not requesting sort, use last setting (for paging).

            if (isset($SESSION->gradeuserreport->sortitemid)) {
                $this->sortitemid = $SESSION->gradeuserreport->sortitemid;
            } else {
                $this->sortitemid = 'lastname';
            }

            if (isset($SESSION->gradeuserreport->sort)) {
                $this->sortorder = $SESSION->gradeuserreport->sort;
            } else {
                $this->sortorder = 'ASC';
            }
        }
    }


    /**
     * Processes the data sent by the form (grades and feedbacks).
     * Caller is responsible for all access control checks
     * @param array $data form submission (with magic quotes)
     * @return array empty array if success, array of warnings if something fails.
     */
    public function process_data($data) {
        global $CFG, $DB, $COURSE, $USER;

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/webservice_evalcomix_client.php');
        require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
        require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');

        $saveconfact = 0;

        if (!empty($data['cmid'])) {
            $activity = $data['cmid'];
            $module = block_evalcomix_tasks::get_type_task($activity);
            $saveconfact = 1;
        }
        $timeavailableae = 0;
        $timedueae = 0;
        $idtoolae = 0;
        $ponae = 0;
        $timeavailableei = 0;
        $timedueei = 0;
        $idtoolei = 0;
        $ponei = 0;
        $idtoolep = 0;
        $ponep = 0;

        if (isset($data['toolEP']) && $data['toolEP'] != 0) {
            $toolep = $DB->get_record('block_evalcomix_tools', array('id' => $data['toolEP']));
            $idtoolep = $toolep->idtool;
            $ponep = $data['pon_EP'];
        }
        if (isset($data['toolAE']) && $data['toolAE'] != 0) {
            $timeavailableae = mktime($data['hour_available_AE'], $data['minute_available_AE'], 0,
            $data['month_available_AE'], $data['day_available_AE'], $data['year_available_AE']);
            $timedueae = mktime($data['hour_timedue_AE'], $data['minute_timedue_AE'], 0, $data['month_timedue_AE'],
            $data['day_timedue_AE'], $data['year_timedue_AE']);
            $toolae = $DB->get_record('block_evalcomix_tools', array('id' => $data['toolAE']));
            $idtoolae = $toolae->idtool;
            $ponae = $data['pon_AE'];
        }
        if (isset($data['toolEI']) && $data['toolEI'] != 0) {
            $timeavailableei = mktime($data['hour_available_EI'], $data['minute_available_EI'], 0,
            $data['month_available_EI'],
            $data['day_available_EI'], $data['year_available_EI']);
            $timedueei = mktime($data['hour_timedue_EI'], $data['minute_timedue_EI'], 0, $data['month_timedue_EI'],
            $data['day_timedue_EI'], $data['year_timedue_EI']);
            $toolei = $DB->get_record('block_evalcomix_tools', array('id' => $data['toolEI']));
            $idtoolei = $toolei->idtool;
            $ponei = $data['pon_EI'];
        }

        if (isset($data['save']) && $data['save'] == get_string('save', 'block_evalcomix')) {
            require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');
            require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes_time.php');
            require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes_extra.php');
            $dataexists = false;
            $taskexists = false;
            $modalitydelete = false;
            $taskid = null;
            if ((isset($data['toolEP']) && $data['toolEP'] != '0') ||
                (isset($data['toolAE']) && $data['toolAE'] != '0') ||
                (isset($data['toolEI']) && $data['toolEI'] != '0') ) {
                $dataexists = true;
            }

            $task = new block_evalcomix_tasks('', $data['cmid'], $data['maxgrade'], '50', time());
            if ($task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $data['cmid']))) {
                $taskid = $task->id;
                $taskexists = true;
                $params = array('id' => $task->id, 'instanceid' => $data['cmid'], 'maxgrade' => $data['maxgrade'],
                    'weighing' => '50', 'timemodified' => time(), 'visible' => 1,
                    'grademethod' => $data['grademethod'], 'threshold' => $data['threshold'], 'workteams' => $data['workteams']);
                $DB->update_record('block_evalcomix_tasks', $params);

                $groupcoordinators = $DB->get_records('block_evalcomix_coordinators', array('taskid' => $taskid));
                $coordinators = (isset($data['coordinators'])) ? $data['coordinators'] : array();
                if (!empty($data['workteams'])) {
                    if ($groupcoordinators) {
                        foreach ($groupcoordinators as $gc) {
                            $gcgroupid = $gc->groupid;
                            if (isset($coordinators[$gcgroupid])) {
                                $coordinatorid = $coordinators[$gcgroupid];
                                if ($gc->userid != $coordinatorid) {
                                    $DB->update_record('block_evalcomix_coordinators', array('id' => $gc->id, 'taskid' => $taskid,
                                    'groupid' => $gcgroupid, 'userid' => $coordinatorid));
                                }
                            } else {
                                $DB->delete_records('block_evalcomix_coordinators', array('id' => $gc->id));
                            }
                            unset($coordinators[$gcgroupid]);
                        }
                        if (!empty($coordinators)) {
                            foreach ($coordinators as $gcgroupid => $coordinatorid) {
                                $DB->insert_record('block_evalcomix_coordinators', array('taskid' => $taskid,
                                'groupid' => $gcgroupid, 'userid' => $coordinatorid));
                            }
                        }
                    } else {
                        foreach ($coordinators as $gcgroupid => $coordinatorid) {
                            $DB->insert_record('block_evalcomix_coordinators', array('taskid' => $taskid, 'groupid' => $gcgroupid,
                            'userid' => $coordinatorid));
                        }
                    }
                } else {
                    if (!empty($groupcoordinators)) {
                        foreach ($groupcoordinators as $gc) {
                            $DB->delete_records('block_evalcomix_coordinators', array('id' => $gc->id));
                        }
                        if ($assessments = $DB->get_records('block_evalcomix_assessments', array('taskid' => $taskid))) {
                            $lms = BLOCK_EVALCOMIX_MOODLE_NAME;
                            $module = block_evalcomix_tasks::get_type_task($task->instanceid);
                            foreach ($assessments as $assessment) {
                                $mode = self::get_type_evaluation($assessment->studentid, $COURSE->id, $assessment->assessorid);
                                try {
                                    block_evalcomix_webservice_client::delete_ws_assessment($COURSE->id, $module, $task->instanceid,
                                    $assessment->studentid, $assessment->assessorid, $mode, $lms);
                                } catch (Exception $e) {
                                    continue;
                                }
                            }
                            $DB->delete_records('block_evalcomix_assessments', array('taskid' => $taskid));
                        }
                    }
                }
            } else if ($dataexists == true) {
                $taskid = $DB->insert_record('block_evalcomix_tasks', array('instanceid' => $data['cmid'],
                    'maxgrade' => $data['maxgrade'], 'weighing' => '50', 'timemodified' => time(), 'visible' => '1',
                    'grademethod' => $data['grademethod'], 'threshold' => $data['threshold'], 'workteams' => $data['workteams']));
                if (!empty($data['workteams'])) {
                    $coordinators = (isset($data['coordinators'])) ? $data['coordinators'] : array();
                    foreach ($coordinators as $gcgroupid => $coordinatorid) {
                        $DB->insert_record('block_evalcomix_coordinators', array('taskid' => $taskid, 'groupid' => $gcgroupid,
                        'userid' => $coordinatorid));
                    }
                }
            }

            if ($data['toolEP'] != 0) {
                if ($modeid = $DB->get_record('block_evalcomix_modes', array('taskid' => $taskid, 'modality' => 'teacher'))) {
                    $params = array('id' => $modeid->id, 'taskid' => $taskid, 'toolid' => $data['toolEP'], 'modality' => 'teacher',
                        'weighing' => $data['pon_EP']);
                    $DB->update_record('block_evalcomix_modes', $params);
                } else {
                    $modeid = $DB->insert_record('block_evalcomix_modes', array('taskid' => $taskid, 'toolid' => $data['toolEP'],
                        'modality' => 'teacher', 'weighing' => $data['pon_EP']));
                }
            } else if ($taskexists == true) {
                if ($modeid = $DB->get_record('block_evalcomix_modes', array('taskid' => $taskid, 'modality' => 'teacher'))) {
                    $DB->delete_records('block_evalcomix_modes', array('id' => $modeid->id));
                    $modalitydelete = true;
                }
            }

            if ($data['toolAE'] != 0) {
                $modeae = new block_evalcomix_modes('', $taskid, $data['toolAE'], 'self', $data['pon_AE']);
                if ($modeid = $DB->get_record('block_evalcomix_modes', array('taskid' => $taskid, 'modality' => 'self'))) {
                    $params = array('id' => $modeid->id, 'taskid' => $taskid, 'toolid' => $data['toolAE'], 'modality' => 'self',
                        'weighing' => $data['pon_AE']);
                    $DB->update_record('block_evalcomix_modes', $params);
                    $timeavailable = mktime($data['hour_available_AE'], $data['minute_available_AE'], 0,
                    $data['month_available_AE'],
                    $data['day_available_AE'], $data['year_available_AE']);
                        $timedue = mktime($data['hour_timedue_AE'], $data['minute_timedue_AE'], 0,
                        $data['month_timedue_AE'],
                    $data['day_timedue_AE'], $data['year_timedue_AE']);
                    if ($modeaetimeid = $DB->get_record('block_evalcomix_modes_time', array('modeid' => $modeid->id))) {
                        $params = array('id' => $modeaetimeid->id, 'modeid' => $modeid->id, 'timeavailable' => $timeavailable,
                            'timedue' => $timedue);
                        $DB->update_record('block_evalcomix_modes_time', $params);
                    }
                } else {
                    $paramsinsert = array('taskid' => $taskid, 'toolid' => $data['toolAE'], 'modality' => 'self',
                        'weighing' => $data['pon_AE']);
                    $modeid = $DB->insert_record('block_evalcomix_modes', $paramsinsert);
                    $timeavailable = mktime($data['hour_available_AE'], $data['minute_available_AE'], 0,
                    $data['month_available_AE'],
                    $data['day_available_AE'], $data['year_available_AE']);
                    $timedue = mktime($data['hour_timedue_AE'], $data['minute_timedue_AE'], 0,
                    $data['month_timedue_AE'],
                    $data['day_timedue_AE'], $data['year_timedue_AE']);
                    $DB->insert_record('block_evalcomix_modes_time', array('modeid' => $modeid, 'timeavailable' => $timeavailable,
                        'timedue' => $timedue));
                }
            } else if ($taskexists == true) {
                if ($modeid = $DB->get_record('block_evalcomix_modes', array('taskid' => $taskid, 'modality' => 'self'))) {
                    if ($modeaetime = $DB->get_record('block_evalcomix_modes_time', array('modeid' => $modeid->id))) {
                        $DB->delete_records('block_evalcomix_modes_time', array('id' => $modeaetime->id));
                    }
                    $DB->delete_records('block_evalcomix_modes', array('id' => $modeid->id));
                    $modalitydelete = true;
                }
            }

            if ($data['toolEI'] != 0) {
                $anonymous = 0;
                if (isset($data['anonymousEI']) && $data['anonymousEI'] == 'on') {
                    $anonymous = 1;
                }
                $alwaysvisible = 0;
                if (!empty($data['alwaysvisibleEI']) && $data['alwaysvisibleEI']) {
                    $alwaysvisible = 1;
                }
                $whoassesses = 0;
                if (!empty($data['whoassessesEI']) && $data['whoassessesEI']) {
                    $whoassesses = $data['whoassessesEI'];
                }

                if ($modeid = $DB->get_record('block_evalcomix_modes', array('taskid' => $taskid, 'modality' => 'peer'))) {
                    $params = array('id' => $modeid->id, 'taskid' => $taskid, 'toolid' => $data['toolEI'], 'modality' => 'peer',
                        'weighing' => $data['pon_EI']);
                    $DB->update_record('block_evalcomix_modes', $params);

                    $timeavailable = mktime($data['hour_available_EI'], $data['minute_available_EI'], 0,
                    $data['month_available_EI'],
                        $data['day_available_EI'], $data['year_available_EI']);
                    $timedue = mktime($data['hour_timedue_EI'], $data['minute_timedue_EI'], 0, $data['month_timedue_EI'],
                        $data['day_timedue_EI'], $data['year_timedue_EI']);

                    if ($modeeitimeid = $DB->get_record('block_evalcomix_modes_time', array('modeid' => $modeid->id))) {
                        $params = array('id' => $modeeitimeid->id, 'modeid' => $modeid->id, 'timeavailable' => $timeavailable,
                            'timedue' => $timedue);
                        $DB->update_record('block_evalcomix_modes_time', $params);
                    }

                    if ($modeeiextraobject = $DB->get_record('block_evalcomix_modes_extra', array('modeid' => $modeid->id))) {
                        $modeeiextraid = $modeeiextraobject->id;
                        $params = array('id' => $modeeiextraid, 'anonymous' => $anonymous, 'visible' => $alwaysvisible,
                            'whoassesses' => $whoassesses, 'modeid' => $modeid->id);
                        $DB->update_record('block_evalcomix_modes_extra', $params);
                    } else {
                        $DB->insert_record('block_evalcomix_modes_extra', array('modeid' => $modeid->id, 'anonymous' => $anonymous,
                        'visible' => $alwaysvisible, 'whoassesses' => $whoassesses));
                    }
                } else {
                    $modeid = $DB->insert_record('block_evalcomix_modes', array('taskid' => $taskid, 'toolid' => $data['toolEI'],
                        'modality' => 'peer', 'weighing' => $data['pon_EI']));
                    $timeavailable = mktime($data['hour_available_EI'], $data['minute_available_EI'], 0,
                        $data['month_available_EI'], $data['day_available_EI'], $data['year_available_EI']);
                    $timedue = mktime($data['hour_timedue_EI'], $data['minute_timedue_EI'], 0,
                        $data['month_timedue_EI'], $data['day_timedue_EI'], $data['year_timedue_EI']);

                    $DB->insert_record('block_evalcomix_modes_time', array('modeid' => $modeid, 'timeavailable' => $timeavailable,
                        'timedue' => $timedue));
                    $DB->insert_record('block_evalcomix_modes_extra', array('modeid' => $modeid, 'anonymous' => $anonymous,
                        'visible' => $alwaysvisible, 'whoassesses' => $whoassesses));
                }
            } else if ($taskexists == true) {
                if ($modeid = $DB->get_record('block_evalcomix_modes', array('taskid' => $taskid, 'modality' => 'peer'))) {

                    if ($extra = $DB->get_record('block_evalcomix_modes_extra', array('modeid' => $modeid->id))) {
                        $DB->delete_records('block_evalcomix_modes_extra', array('id' => $extra->id));
                    }

                    if ($modeeitime = $DB->get_record('block_evalcomix_modes_time', array('modeid' => $modeid->id))) {
                        $DB->delete_records('block_evalcomix_modes_time', array('id' => $modeeitime->id));
                    }
                    $DB->delete_records('block_evalcomix_modes', array('id' => $modeid->id));
                    $modalitydelete = true;
                }
            }

            if ($taskexists == true && $dataexists == true) {
                require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_grades.php');

                if ($grades = $DB->get_records('block_evalcomix_grades', array('courseid' => $this->courseid,
                    'cmid' => $task->instanceid))) {
                    foreach ($grades as $grade) {
                        $user = $grade->userid;

                        $params = array('cmid' => $task->instanceid, 'userid' => $user, 'courseid' => $this->courseid);
                        $finalgrade = block_evalcomix_grades::get_finalgrade_user_task($params);
                        if ($finalgrade !== null) {
                            $DB->update_record('block_evalcomix_grades', array('id' => $grade->id, 'userid' => $grade->userid,
                                'cmid' => $grade->cmid, 'finalgrade' => $finalgrade, 'courseid' => $grade->courseid));
                        } else {
                            $DB->delete_records('block_evalcomix_grades', array('id' => $grade->id));
                        }
                    }
                }
            }

            // Recalculamos en cualquier caso las notas.
            require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_grades.php');
            if ($grades = $DB->get_records('block_evalcomix_grades', array('courseid' => $this->courseid,
                'cmid' => $task->instanceid))) {
                foreach ($grades as $grade) {
                    $user = $grade->userid;

                    $params = array('cmid' => $task->instanceid, 'userid' => $user, 'courseid' => $this->courseid);
                    $finalgrade = block_evalcomix_grades::get_finalgrade_user_task($params);
                    if ($finalgrade !== null) {
                        $DB->update_record('block_evalcomix_grades', array('id' => $grade->id, 'userid' => $grade->userid,
                                'cmid' => $grade->cmid, 'finalgrade' => $finalgrade, 'courseid' => $grade->courseid));
                    }
                }
            }
        }

        // Check if there is data from any evaluation carried out with evalcomix to save it in the Moodle database.
        // It is done here since that method is processed every time the page is reloaded.
        if (isset($data['stu']) && $data['stu'] != 0 && $data['cma'] != 0) {
            $activity = $data['cma'];
            $module = block_evalcomix_tasks::get_type_task($activity);

            $mode = self::get_type_evaluation($data['stu'], $this->courseid);

            if ($task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $data['cma']))) {
                $tool = block_evalcomix_get_modality_tool($this->courseid, $task->id, $mode);
                $evalcomixassessment = block_evalcomix_webservice_client::get_ws_singlegrade($tool->idtool,
                    $this->courseid, $module, $activity, $data['stu'], $USER->id, $mode, BLOCK_EVALCOMIX_MOODLE_NAME);
            }

            // If $evalcomixassessment->grade == -1  means that the grade is empty.
            if ($evalcomixassessment != null) {

                // Checks if the assessment exists in the table mdl_blocks_evalcomix_assessments.
                $params = array('taskid' => $evalcomixassessment->taskid, 'assessorid' => $evalcomixassessment->assessorid,
                    'studentid' => $evalcomixassessment->studentid);

                $evxassessmentobject = $DB->get_record('block_evalcomix_assessments', $params);

                if ($evxassessmentobject != false) {
                    if ($evalcomixassessment->grade != -1) { // If the grade is not null.
                        $params['grade'] = $evalcomixassessment->grade;
                        $params['id'] = $evxassessmentobject->id;
                        $DB->update_record('block_evalcomix_assessments', $params);
                    } else { // If the grade is null.
                        $DB->delete_records('block_evalcomix_assessments', array('id' => $evxassessmentobject->id));
                    }
                } else if ($evalcomixassessment->grade != -1) { // If it does not exist and the grade is not null inserts it.
                    $DB->insert_record('block_evalcomix_assessments', array('taskid' => $evalcomixassessment->taskid,
                        'assessorid' => $evalcomixassessment->assessorid, 'studentid' => $evalcomixassessment->studentid,
                        'grade' => $evalcomixassessment->grade, 'timemodified' => time()));
                }
            }
            // Save the finalgrade.
            require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_grades.php');
            $params = array('cmid' => $data['cma'], 'userid' => $data['stu'], 'courseid' => $this->courseid);
            $finalgrade = block_evalcomix_grades::get_finalgrade_user_task($params);
            if ($finalgrade !== null) {
                if ($gradeobject = $DB->get_record('block_evalcomix_grades', $params)) {
                    $params['finalgrade'] = $finalgrade;
                    $params['id'] = $gradeobject->id;
                    $DB->update_record('block_evalcomix_grades', $params);
                } else {
                    $params['finalgrade'] = $finalgrade;
                    $DB->insert_record('block_evalcomix_grades', $params);
                }
            } else {
                if ($gradeobject = $DB->get_record('block_evalcomix_grades', $params)) {
                    $DB->delete_records('block_evalcomix_grades', array('id' => $gradeobject->id));
                }
            }
        }

    }

    /**
     * Processes a single action against a category, grade_item or grade.
     * @param string $target eid ({type}{id}, e.g. c4 for category4)
     * @param string $action Which action to take (edit, delete etc...)
     * @return
     */
    public function process_action($target, $action) {
        // Véase método grade_report_grader::process_action();.
    }

    /**
     * Returns whether or not to display fixed students column.
     * Includes a browser check, because IE6 doesn't support the scrollbar.
     *
     * @return bool
     */
    public function is_fixed_students() {
    }

    /**
     * Checks if the $USER is an editing permits user (admin or teacher)
     * @return bool
     */
    private function editing_permits_user() {
        global $USER;

        $coursecontext = context_course::instance($this->courseid);
        $permits = false;

        if (is_siteadmin($USER)) {
            $permits = true;
        }

        if (has_capability('moodle/block:edit', $coursecontext)) {
            $permits = true;
        }

        return $permits;
    }


    /**
     * Checks if an activity is configured in evalcomix
     * @param int $cmid id of a course_module
     * @return bool
     */
    public function configured_activity($cmid) {
        global $DB;

        // Checks if exists one row in table mdl_block_evalcomix_tasks for that course module id.
        $task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cmid));
        if ($task) {
            // Checks if exists at least one row in table mdl_block_evalcomix_modes for that $task.
            $modes = $DB->get_records('block_evalcomix_modes', array('taskid' => $task->id));
            if ($modes) {
                return true;
            }
        }
        return false;
    }

    /**
     * Builds the grade table and returns it in HTML
     * @return string HTML
     */
    public function create_grade_table() {
        global $CFG, $OUTPUT, $USER, $DB;
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_assessments.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes_time.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes_extra.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_grades.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_allowedusers.php');

        $context = context_course::instance($this->courseid);
        $table = '
            <div id="div"></div>
            <div class="wrapper1">
                <div class="div1 w-100" id="div1">
                </div>
            </div>
            <div class="wrapper2">
                <div class="div2 w-100" id="div2">

            <!-- <div id="wrapper2" style="overflow:auto;overflow-y:hidden;"> -->
            <table border=1 id="user-grades"
            class="gradestable flexible boxaligncenter generaltable text-right block_evalcomix_font_size_08">';

        // Obtains course´s users.
        $users = $this->load_users();

        $table .= $this->get_headers();
        $table .= "
                <script type=\"text/javascript\" src=\"../ajax.js\"></script>
                <script type=\"text/javascript\">
                    var recarga = 0;

                     function relocate(page,params)
                     {
                          var body = document.body;
                          form=document.createElement('form');
                          form.method = 'POST';
                          form.action = page;
                          form.name = 'jsform';
                          for (index in params)
                          {
                                var input = document.createElement('input');
                                input.type='hidden';
                                input.name=index;
                                input.id=index;
                                input.value=params[index];
                                form.appendChild(input);
                          }
                          body.appendChild(form);
                          form.submit();
                     }

                    function change_recarga() {
                        recarga = 1;
                    }

                    function urlDetalles(u) {
                        win2 = window.open(u, 0, \"menubar=0,location=1,scrollbars,resizable,width=780,height=500\");
                        checkChildDetalles();
                    }

                    function url(u, alu, cmidassign, page, courseid, nombre, workteams) {
                        win2 = window.open(u, nombre, \"menubar=0,location=0,scrollbars,resizable,width=780,height=500\");
                        checkChild(alu, cmidassign, page, courseid, workteams);
                    }

                    function checkChild(alu, cmidassign, page, course, workteams) {
                        if (win2.closed) {
                            /*relocate(window.location, {'stu':alu, 'cma':cmidassign}); */

                            doWork('evalcomixtablegrade_'+alu+'_'+cmidassign,
'servidor.php?id=".$this->courseid."&eva=".$USER->id."', 'courseid='+course+'&page='+page+'&stu='+alu+'&cma='+cmidassign);
                            if (workteams && workteams.length > 0) {
                                /*var join = workteams.join(',');
                                ajax('".$CFG->wwwroot.
        "/blocks/evalcomix/assessment/loadworkteams.php?id='+course+'&stu='+alu+'&cma='+cmidassign+'&newstu='+join,
        '#evalcomixtablegrade_'+alu+'_'+cmidassign);*/

                                var len = workteams.length;
                                for (var i = 0; i < len; i++) {
                                    var alugr = workteams[i];
                                    if (alu == alugr) {
                                        continue;
                                    }
                                    ajax('".$CFG->wwwroot.
        "/blocks/evalcomix/assessment/loadworkteams.php?id='+course+'&stu='+alu+'&cma='+cmidassign+'&newstu='+alugr,
'#evalcomixtablegrade_'+alugr+'_'+cmidassign);
                                }
                            }
                        } else {
                            setTimeout(checkChild, 10, alu, cmidassign, page, course, workteams);
                        }
                    }



                    function checkChildDetalles() {
                        if (win2.closed) {
                            if (recarga == 1) {
                                window.location.reload(true);
                            }
                            recarga = 0;
                        }
                        else {
                            setTimeout(\"checkChildDetalles()\",1);
                        }
                    }

                    function checkChild_old(alu) {
                        if (win2.closed) {
                            var url = String(window.location);
                            var long = url.length;
                            if (url.substring(long-6, long-1) != \"&stu=\") {
                                location.href = window.location + \"&stu=\"+alu;
                            }
                            else window.location.reload(true);

                        }
                        else setTimeout(\"checkChild_old(\"+alu+\")\",1);
                    }

                </script>

                <script type='text/javascript'>//<![CDATA[
                    var table = document.getElementById('user-grades');

                    var div11 = document.getElementById('div1');
                    var div22 = document.getElementById('div2');
                    var div = document.getElementById('div');
                    var documentwidth = $(div).width();

                    if ((table.offsetWidth+100) < documentwidth) {
                        div11.style.width = (documentwidth - 50) +'px';
                        div22.style.width = (documentwidth - 50)+'px';
                        $(div22).css('text-align','center');
                        $(table).css('margin','0 auto');
                    }
                    else {
                        div11.style.width = (table.offsetWidth+100)+'px';
                        div22.style.width = (table.offsetWidth+100)+'px';
                    }

                    $(window).on(\"load\", function() {
                    $(function() {
                        $(\".wrapper1\").scroll(function() {
                            $(\".wrapper2\")
                                .scrollLeft($(\".wrapper1\").scrollLeft());
                        });
                        $(\".wrapper2\").scroll(function() {
                            $(\".wrapper1\")
                                .scrollLeft($(\".wrapper2\").scrollLeft());
                        });
                    });
                    });//]]>

                    </script>

                <noscript>
                    <div class='text-danger'>".get_string('alertjavascript', 'block_evalcomix')."</div>
                </noscript>\n";

        $tools = $this->load_tools();
        $lang = current_language();
        // Array $finalgrades with two dimensions [$taskinstance][$userid] that contains the finalgrades.
        $finalgrades = block_evalcomix_grades::get_grades($this->courseid);

        $index = 0;
        if (isset($this->activities['id'])) {
            $numactivities = count($this->activities['id']);
        } else {
            $numactivities = 0;
        }

        $tasksarray = array();
        $groupmodes = array();
        $typeinstrument = array();
        $cm = array();
        $whoassesses = array();
        $coordinators = array();

        for ($i = 0; $i < $numactivities; $i++) {
            $cmid = $this->activities['id'][$i];

            if (!$this->canviewhidden && $this->cm[$cmid]->visible == 0) {
                continue;
            }

            $groupmodes[$cmid] = $this->cm[$cmid]->groupmode;

            $typeinstrument[$cmid] = $this->activities['modulename'][$i];

            if ($tasksarray[$cmid] = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cmid))) {
                $taskid = $tasksarray[$cmid]->id;
                $modeteacher[$cmid] = $DB->get_record('block_evalcomix_modes', array('taskid' => $taskid, 'modality' => 'teacher'));
                $modetime[$cmid]['self'] = $this->get_modestime($tasksarray[$cmid]->id, 'self');
                $modetime[$cmid]['peer'] = $this->get_modestime($tasksarray[$cmid]->id, 'peer');
                if ($modepeer[$cmid] = $DB->get_record('block_evalcomix_modes', array('taskid' => $taskid, 'modality' => 'peer'))) {
                    if ($modepeerextra = $DB->get_record('block_evalcomix_modes_extra', array('modeid' => $modepeer[$cmid]->id))) {
                        $whoassesses[$cmid] = $modepeerextra->whoassesses;
                    }
                }
                if ($tasksarray[$cmid]->workteams == 1) {
                    if ($taskcoordinators = $DB->get_records('block_evalcomix_coordinators', array('taskid' => $taskid))) {
                        foreach ($taskcoordinators as $tc) {
                            $coordinators[$taskid][] = $tc->userid;
                        }
                    }
                }
            }
        }

        // To know if the documents uploaded by the students must be shown.
        $assessments = $this->load_assessments($tasksarray);
        $now = getdate();
        $nowtimestamp = mktime($now["hours"], $now["minutes"], $now["seconds"], $now["mon"], $now["mday"], $now["year"]);

        foreach ($users as $user) {
            if ($user->suspendedenrolment) {
                continue;
            }
            $mode = self::get_type_evaluation($user->id, $this->courseid);
            if ($mode == 'self' || $mode == 'peer') {
                $gidloginuser = $this->get_groupids($USER->id);
                $giduser = $this->get_groupids($user->id);
            }

            $background = 'bg-white';
            if ($index % 2 == 0) {
                $background = 'background-color-tr';
            }
            $index++;
            $aux = array();
            $table .= '
                    <tr class="block_evalcomix-border '. $background .'">
                    <td class="block_evalcomix_usertopic"><div class="userpic">'. $OUTPUT->user_picture($user) .'</div></td>
                    <td class="block_evalcomix-border"><a href="'.$CFG->wwwroot.'/user/view.php?id='.
                    $user->id.'&course='.$this->courseid.'">'. fullname($user) .'</a></td>';

            for ($i = 0; $i < $numactivities; $i++) {
                $allowedusers = array();
                $evaluate = '';
                $cmid = $this->activities['id'][$i];
                if (!$this->canviewhidden && $this->cm[$cmid]->visible == 0) {
                    continue;
                }

                // Boolean, if there is not a grade it will not show the details link.
                $showdetails = true;

                $table .= '<td class="block_evalcomix-border" title="'.htmlentities(fullname($user)."\n".
                    $this->activities['name'][$i], ENT_QUOTES, "UTF-8").'">';
                $table .= '<div id="evalcomixtablegrade_'.$user->id.'_'.$cmid.'">';
                // Only show the user´s grade or all grades if the USER is a teacher or admin.
                if ($this->cm[$cmid]->visible == 0) {
                    $table .= '<span class="font-italic text-dark" >Actividad Oculta</span>';
                } else if ((has_capability('moodle/grade:viewhidden', $context, $USER->id) || $user->id == $USER->id) &&
                    isset($finalgrades[$this->activities['id'][$i]][$user->id])
                    && $finalgrades[$this->activities['id'][$i]][$user->id] > -3) {

                    if ($finalgrades[$cmid][$user->id] > -1) {
                        $table .= format_float($finalgrades[$cmid][$user->id], 2);
                        $aux[] = $finalgrades[$cmid][$user->id];
                    } else {
                        $table .= '-';
                    }
                    // If there is not anything to assess.
                    if ($finalgrades[$cmid][$user->id] == -2) {
                        $showdetails = false;
                    }
                } else { // There is not grade configured.
                    if ($this->activitiesconfigured[$cmid]) {
                        $table .= '-';
                        // Not configured.
                    } else {
                        $table .= '<span class="font-italic text-danger">'.
                         get_string('noconfigured', 'block_evalcomix').'</span>';
                    }
                    $showdetails = false;
                }

                // Checks if $this->activities['id'] is configured in evalcomix.
                if ($this->activitiesconfigured[$cmid]) {
                    if ($this->cm[$cmid]->visible == 0) {
                        $table .= '<div><span class="font-italic text-dark font-weight-bold">Configurada</span></div>';
                        continue;
                    }

                    $taskid = $tasksarray[$cmid]->id;
                    if (isset($tools[$taskid][$mode])) {
                        $tool = $tools[$taskid][$mode];
                        $urlinstrument = 'assessment_form.php?id='.$this->courseid.'&a='.$cmid.'&t='.
                        $tool->idtool.'&s='.$user->id.'&mode=assess';
                        $membersgroup = array();
                        if ($tasksarray[$cmid]->workteams == 1) {
                            if (!empty($this->coursegroups)) {
                                foreach ($this->coursegroups as $groupid => $mgroup) {
                                    if (in_array($user->id, $mgroup)) {
                                        $membersgroup = $mgroup;
                                    }
                                }
                            }
                        }
                        $evaluate = '<input type="image" value="'.get_string('evaluate', 'block_evalcomix').'" title="'.
                        get_string('evaluate', 'block_evalcomix').'" class="block_evalcomix_w_16"
                        src="../images/evaluar.png"
                        onclick="javascript:url(\'' . $urlinstrument . '\',\'' . $user->id . '\',\'' .
                        $this->activities['id'][$i] . '\',\'' .
                        $this->page . '\',\'' . $this->courseid . '\', \'\', [' . implode(',', $membersgroup) . ']);"/>';
                        $assessorid = $USER->id;
                        $studentid = $user->id;
                        if (isset($assessments[$taskid][$assessorid][$studentid])) {
                            $evaluate = '<input type="image" value="'.get_string('evaluate', 'block_evalcomix').'" title="'.
                            get_string('evaluate', 'block_evalcomix').'" class="block_evalcomix_w_16"
                            src="../images/evaluar2.png" onclick="javascript:url(\'' . $urlinstrument . '\',\'' .
                            $user->id . '\',\'' .
                            $this->activities['id'][$i] . '\', \'' . $this->page . '\',\'' . $this->courseid .
                            '\', \'\', [' . implode(',', $membersgroup) . ']);"/>';
                        }
                    }

                    if ($showdetails) {
                        $paramsurlpopup = 'cid='.$context->id.'&itemid='.$tasksarray[$cmid]->id.'&userid='.$user->id . '&popup=1';
                        $details = '<input  type="image" value="'.get_string('details', 'block_evalcomix').'"
                        class="block_evalcomix_w_16" title='.get_string('details', 'block_evalcomix').'
                        src="../images/lupa.png"
onclick="javascript:urlDetalles(\''.$CFG->wwwroot. '/blocks/evalcomix/assessment/details.php?'.$paramsurlpopup.'\');"/>';
                    } else {
                        $details = '';
                    }

                    // Show user´s documents.
                    $paramsurlpopup = 'assessment/user_activity.php?id='.$user->id.'&course='.$this->courseid.'&mod='.$cmid;
                    $title = get_string('studentwork1', 'block_evalcomix').
                    get_string('studentwork2', 'block_evalcomix'). $this->activities['name'][$i];
                    $documents = ' <input type="image" value="'.$title.'"
                    title="'.$title. '" src="../images/task.png"
                    onclick="javascript:urlDetalles(\''. $CFG->wwwroot. '/blocks/evalcomix/'.$paramsurlpopup. '\');"/>';
                    // If the $USER isn´t a teacher or admin evaluate if it should show Evaluate and Details buttons.
                    if ($mode == 'self' || $mode == 'peer') {
                        $whoassesses = null;
                        if ($task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cmid))) {
                            $taskid = $task->id;
                            if ($modepeer = $DB->get_record('block_evalcomix_modes', array('taskid' => $taskid,
                                'modality' => 'peer'))) {
                                if ($modepeerextra = $DB->get_record('block_evalcomix_modes_extra',
                                    array('modeid' => $modepeer->id))) {
                                    $whoassesses = $modepeerextra->whoassesses;
                                }
                            }
                        }

                        $condition = true;
                        if ($whoassesses != null) {
                            $condition = $this->student_can_assess($user, $this->cm[$cmid]);
                        }

                        if ($condition) {
                            if ($mode == 'self') { // Details always are shown in selfassessment.
                                $table .= $details;
                            }
                            if ($modetime[$cmid][$mode] != false) {
                                $available = $modetime[$cmid][$mode]->timeavailable;
                                $due = $modetime[$cmid][$mode]->timedue;

                                // If the availability of the activity started.
                                if ($nowtimestamp >= $available) {
                                    $paramsurlactivity = 'id='.$user->id.'&course='.$this->courseid.'&mod='.$cmid;
                                    $title = get_string('studentwork1', 'block_evalcomix').
                                    get_string('studentwork2', 'block_evalcomix').
                                    $this->activities['name'][$i];
                                    $table .= ' <input type="image" value="'.$title.'"
                                    title="'.$title.'" src="../images/task.png"
onclick="javascript:urlDetalles(\''.$CFG->wwwroot. '/blocks/evalcomix/assessment/user_activity.php?'.$paramsurlactivity.'\');"/>';
                                }

                                if ($nowtimestamp >= $due && $mode == 'peer' && $showdetails == true) {
                                    $urlpeerinstrument = block_evalcomix_webservice_client::get_ws_view_assessment($this->courseid,
                                        $typeinstrument[$cmid], $this->activities['id'][$i], $USER->id, $user->id, 'peer',
                                        BLOCK_EVALCOMIX_MOODLE_NAME);
                                    $table .= '<input type="image" value="'.get_string('details', 'block_evalcomix').'"
                                    class="block_evalcomix_w_16"
                                    title='.get_string('details', 'block_evalcomix').' src="../images/lupa.png"
                                    onclick="javascript:urlDetalles(\''. $urlpeerinstrument .'\');"/>';
                                }
                                // Show the buttons if they must be availables.
                                if ($nowtimestamp >= $available && $nowtimestamp < $due) {
                                    if ($mode == 'self') {
                                        if ($tasksarray[$cmid]->workteams == 1) {
                                            if (isset($coordinators[$taskid]) && in_array($user->id, $coordinators[$taskid])) {
                                                $table .= $evaluate;
                                            }
                                        } else {
                                            $table .= $evaluate;
                                        }
                                    } else if ($mode == 'peer') {
                                        if ($tasksarray[$cmid]->workteams == 1) {
                                            if (!$this->same_group($USER->id, $user->id) && isset($coordinators[$taskid])
                                                    && in_array($user->id, $coordinators[$taskid])) {
                                                $table .= $evaluate;
                                            }
                                        } else {
                                            $table .= $evaluate;
                                        }
                                    }
                                }
                            }
                        }
                    } else { // If mode == 'teacher'.
                        $table .= $details;
                        if ($modeteacher[$cmid] != null && $modeteacher[$cmid]->modality == 'teacher') {
                            if ($tasksarray[$cmid]->workteams == 1) {
                                if (isset($coordinators[$taskid]) && in_array($user->id, $coordinators[$taskid])) {
                                    $table .= $documents;
                                    $table .= $evaluate;
                                }
                            } else {
                                $table .= $documents;
                                $table .= $evaluate;
                            }
                        }
                    }
                }
                $table .= '</div>';
            }

            $table .= '</td>';

            // Calculates average.
            if (count($aux) == 0) {
                $average = 0;
            } else {
                $average = $this->calculator->calculate_one_array($aux);
                $average = round($average, 2, PHP_ROUND_HALF_UP);
                unset($aux);
            }

            $table .= '<td class="block_evalcomix-border">' . $average . '</td></tr>';
        }

        $table .= '</table></div>';
        $table .= '</div>';

        return $table;
    }

    /**
     * Return a modes_time object
     * @param $cmid
     * @param $modality
     * @return object evalcomix_modes_time
     */
    public function get_modes_time($cmid, $modality) {
        global $DB;
        if ($task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cmid))) {
            if ($mode = $DB->get_record('block_evalcomix_modes', array('taskid' => $task->id, 'modality' => $modality))) {
                if ($modetime = $DB->get_record('block_evalcomix_modes_time', array('modeid' => $mode->id))) {
                    return $modetime;
                }
            }
        }
        return false;
    }

    /**
     * Return a modes_time object
     * @param $taskid
     * @param $modality
     * @return object evalcomix_modes_time
     */
    public function get_modestime($taskid, $modality) {
        global $DB;
        if ($mode = $DB->get_record('block_evalcomix_modes', array('taskid' => $taskid, 'modality' => $modality))) {
            if ($modetime = $DB->get_record('block_evalcomix_modes_time', array('modeid' => $mode->id))) {
                return $modetime;
            }
        }

        return false;
    }
    /**
     * Return the groupmode of a course module
     * 0: No groups
     * 1: Separated groups
     * 2: Visible groups
     * @param $cmid
     * @return int groupmode
     */
    public function get_groupmode($cmid) {
        global $DB;

        $cm = $DB->get_record('course_modules', array('id' => $cmid));

        if ($cm) {
            return $cm->groupmode;
        } else {
            return 0;
        }
    }

    /**
     * Return the groupmode of a course module
     * 0: No groups
     * 1: Separated groups
     * 2: Visible groups
     * @param $cmid
     * @return int groupmode
     */
    public function get_cm($cmid) {
        global $DB;

        $cm = $DB->get_record('course_modules', array('id' => $cmid));

        if ($cm) {
            return $cm;
        } else {
            return 0;
        }
    }
    /**
     * Return the groupids of an user or -1 if doesn´t exist a group for that user
     * @param $userid
     * @return array groupids of false if there is not any group for $userid
     */
    public function get_groupids($userid) {
        if (!empty($this->coursegroups)) {
            $result = array();
            foreach ($this->coursegroups as $groupid => $group) {
                if (in_array($userid, $group)) {
                    $result[] = $groupid;
                }
            }
            if (!empty($result)) {
                return $result;
            }
        }

        return -1;
    }

    /**
     * Load groupings with their group ids
     */
    public function load_groupings() {
        global $DB;
        if ($groupings = $DB->get_records('groupings', array('courseid' => $this->courseid))) {
            foreach ($groupings as $grouping) {
                $groupingid = $grouping->id;
                if ($groupingsgroups = $DB->get_records('groupings_groups', array('groupingid' => $groupingid))) {
                    foreach ($groupingsgroups as $gg) {
                        $this->coursegroupings[$groupingid][] = $gg->groupid;
                    }
                }
            }
        }
        return $this->coursegroupings;
    }

    /**
     * Load groups with their user ids
     */
    public function load_groups() {
        global $DB;
        if ($groups = $DB->get_records('groups', array('courseid' => $this->courseid))) {
            foreach ($groups as $group) {
                $groupid = $group->id;
                if ($groupsmembers = $DB->get_records('groups_members', array('groupid' => $groupid))) {
                    foreach ($groupsmembers as $gm) {
                        $this->coursegroups[$groupid][] = $gm->userid;
                    }
                }
            }
        }
        return $this->coursegroups;
    }
    /**
     * Return true if there is a grouping between two groups in one activity
     * @param $gid1
     * @param $gid2
     * @param $cmid
     * @return bool
     */
    public function same_grouping($gid1, $gid2, $cm) {
        global $DB;

        // If it exists.
        if ($cm) {
            // If that activity has a grouping.
            if ($cm->groupingid != 0) {
                $grouping1 = $DB->get_record('groupings_groups', array('groupingid' => $cm->groupingid, 'groupid' => $gid1));
                $grouping2 = $DB->get_record('groupings_groups', array('groupingid' => $cm->groupingid, 'groupid' => $gid2));

                // If those groups are in the same grouping of the activity.
                if ($grouping1 && $grouping2) {
                    return true;
                }
            }
        }

        return false;
    }

    public function same_group($uid1, $uid2) {
        if (!empty($this->coursegroups)) {
            foreach ($this->coursegroups as $group) {
                if (in_array($uid1, $group) && in_array($uid2, $group)) {
                    return true;
                }
            }
        }
        return false;
    }
    /**
     * Return true if there is a grouping between two groups in one activity
     * @param $uid1
     * @param $uid2
     * @param $cm
     * @return bool
     */
    public function same_grouping_by_users($uid1, $uid2, $cm) {
        if ($cm->groupingid) {
            $groupingid = $cm->groupingid;
            $groupinggroups = (!empty($this->coursegroupings[$groupingid])) ? $this->coursegroupings[$groupingid] : array();
            $groups1 = $this->get_groupids($uid1);
            $groups2 = $this->get_groupids($uid2);
            if (is_array($groups1) && is_array($groups2)) {
                $intersect1 = array_intersect($groups1, $groupinggroups);
                $intersect2 = array_intersect($groups2, $groupinggroups);
                if (!empty($intersect1) && !empty($intersect2)) {
                    return true;
                }
            }
            return false;
        }
    }


    /**
     * Return teacher, self or peer, according to the user id received
     * @param $userid
     * @param $courseid
     * @param $assessorid
     * @return string $mode
     */
    public static function get_type_evaluation($userid, $courseid, $assessorid = '0') {
        global $USER;

        $context = context_course::instance($courseid);
        if ($assessorid != '0') {
            $evaluatorid = $assessorid;
        } else {
            $evaluatorid = $USER->id;
        }

        if ($evaluatorid == $userid) {
            $mode = 'self';
        } else {
            if (has_capability('moodle/grade:viewhidden', $context, $evaluatorid)) {
                $mode = 'teacher';
            } else {
                $mode = 'peer';
            }
        }
        return $mode;
    }


    /**
     * Builds and returns the headers of the table
     * @return string HTML
     */
    public function get_headers() {
        global $USER, $DB, $COURSE;
        $course = $DB->get_record('course', array('id' => $this->courseid), '*', MUST_EXIST);

        $total = '';
        // To print  Lastname / Firstname.
        $arrows = $this->get_sort_arrows();

        $header = '<tr class="block_evalcomix-border">
                    <th colspan="2">'.$arrows['studentname'].'</th>';

        $levels = $this->gtree->get_levels();
        foreach ($levels as $row) {
            foreach ($row as $element) {
                if ($element['object']->hidden == 1 && !$this->canviewhidden) {
                    continue;
                }

                if (isset($element['object']->itemnumber) && $element['object']->itemnumber == 0
                    && $element['object']->itemtype != 'manual') {
                    // Checks if it is an activity.
                    if ($element['type'] == 'item') {
                        if ($cm = get_coursemodule_from_instance($element['object']->itemmodule,
                            $element['object']->iteminstance, $this->courseid)) {
                            $cmid = $cm->id;
                            if (!$task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cmid))) {
                                $paramtask = array('instanceid' => $cmid, 'maxgrade' => 100, 'weighing' => 50,
                                    'timemodified' => time(), 'visible' => '1');
                                $DB->insert_record('block_evalcomix_tasks', $paramtask);
                            } else if ($task->visible == 0) {
                                continue;
                            }
                            $this->activitiesconfigured[$cmid] = $this->configured_activity($cmid);
                            if (!$this->canviewhidden && !$this->activitiesconfigured[$cmid]) {
                                continue;
                            }

                            if (property_exists($cm, 'availability')) {
                                $grey = false;
                                $hide = false;
                                global $USER;
                                $this->modinfo = new course_modinfo($course, $USER->id);
                                $instances = $this->modinfo->get_instances_of($element['object']->itemmodule);
                                if (!empty($instances[$element['object']->iteminstance])) {
                                    $cminfo = $instances[$element['object']->iteminstance];
                                    if (!$cminfo->uservisible) {
                                        // If there is 'availableinfo' text then it is only greyed
                                        // out and not entirely hidden.
                                        if (!$cminfo->availableinfo) {
                                            $hide = true;
                                        }
                                        $grey = true;
                                    }
                                }
                            }

                            if (!$this->canviewhidden) {
                                if (!empty($hide) || !empty($grey)) {
                                    continue;
                                }

                                $gm = true;
                                if (property_exists($cm, 'groupmembersonly') && !$cm->groupmembersonly) {
                                    $gm = false;
                                }

                                if ($gm) {
                                    $groupingid = $cm->groupingid;
                                    $intersect1 = array();
                                    $groups = $this->get_groupids($USER->id);
                                    if (isset($this->coursegroupings[$groupingid]) && is_array($groups)) {
                                        $groupinggroups = $this->coursegroupings[$groupingid];
                                        $intersect1 = array_intersect($groups, $groupinggroups);
                                    }

                                    if ($cm->groupingid && empty($intersect1)) {
                                        continue;
                                    }
                                }
                            }
                            if ($cm->visible == 0 && !$this->canviewhidden) {
                                continue;
                            }
                        }
                        $header .= '<th class="block_evalcomix-border">'.$this->print_header_element($element, true);

                        $header .= '</th>';
                    }
                    // Checks if it is the total grade of the course.
                    if ($element['type'] == 'courseitem') {
                        $total = '<th class="block_evalcomix-border">
                            '.$this->print_header_element($element, true).'</th>';
                    }
                }
            }
        }

        $header .= $total.'</tr>';

        return $header;
    }

    /**
     * Builds and return icon and name of the header element
     * @return string HTML
     */
    public function print_header_element(&$element, $withlink=false, $spacerifnone=false) {
        global $CFG, $DB;

        $header = $this->gtree->get_element_icon($element, $spacerifnone);

        $name = $element['object']->get_name();
        $dots = '';
        if (strlen($name) > 24) {
            $dots = '... ';
        }

        $header .= substr($element['object']->get_name(), 0, 24) . $dots;

        if ($element['type'] != 'item' and $element['type'] != 'categoryitem' and
            $element['type'] != 'courseitem') {
            return $header;
        }

        $itemtype     = $element['object']->itemtype;
        $itemmodule   = $element['object']->itemmodule;
        $iteminstance = $element['object']->iteminstance;

        $course = $DB->get_record('course', array('id' => $this->courseid), '*', MUST_EXIST);

        if ($withlink and $itemtype == 'mod' and $iteminstance and $itemmodule) {
            if ($cm = get_coursemodule_from_instance($itemmodule, $iteminstance, $this->courseid)) {

                // Insert id and name in $this->activities[] to know the activities order.
                $this->activities['id'][] = $cm->id;
                $this->activities['name'][] = $element['object']->itemname;
                $this->activities['modulename'][] = $itemmodule;
                $cmid = $cm->id;
                $this->cm[$cmid] = $cm;

                $a = new stdClass();
                $a->name = get_string('modulename', $element['object']->itemmodule);

                $title = get_string('linktoactivity', 'block_evalcomix');
                $dir = $CFG->dirroot.'/mod/'.$itemmodule;

                if (file_exists($dir.'/grade.php')) {
                    $url = $CFG->wwwroot.'/mod/'.$itemmodule.'/grade.php?id='.$cm->id;
                } else {
                    $url = $CFG->wwwroot.'/mod/'.$itemmodule.'/view.php?id='.$cm->id;
                }

                $grey = false;
                if (property_exists($cm, 'availability')) {
                    global $USER;
                    $this->modinfo = new course_modinfo($course, $USER->id);
                    $instances = $this->modinfo->get_instances_of($element['object']->itemmodule);
                    if (!empty($instances[$element['object']->iteminstance])) {
                        $cminfo = $instances[$element['object']->iteminstance];
                        if (!$cminfo->uservisible) {
                            // If there is 'availableinfo' text then it is only greyed
                            // out and not entirely hidden.
                            $grey = true;
                        }
                    }
                }

                $style = '';
                if ($cm->visible == 0 || $grey == true) {
                    $style = 'text-secondary';
                }
                $header = '<a class="'.$style.'" href="'.$url.'" title="'.s($title).'" target="v"
                    onclick="window.open(\'\', \'v\', \'scrollbars,resizable,width=1000,height=600\');"
                    >'.$header.'</a>';

                $width = 'block_evalcomix_w_17';

                if ($this->activitiesconfigured[$cmid] == false) {
                    $width = 'block_evalcomix_w_25';
                }
                // If $USER has editing permits.
                if ($this->editing_permits_user()) {
                    $header .= '<input type="image" class="border-0 '.$width.'" src="../images/edit.png"
                    title='.get_string('set', 'block_evalcomix').'
                    alt='.get_string('set', 'block_evalcomix').' onclick="location.href=\'activity_edit_form.php?id=' .
                    $this->courseid . '&a=' . $cm->id .
                    '\'">';
                }
            }
        }

        return $header;
    }


    /*
     * Returns the an array of two dimensions with ids and names of activities
     * To obtain the data: $this->activities['id'][] / $this->activities['name'][]
     * @return array activities of the object
     */
    public function get_activities() {
        return $this->activities;
    }


    /**
     * It gets grades involved in the report.
     * @param string $mode indicates source of grades: Only EvalCOMIX ('evalcomix') or a
     *                     combination of Moodle and EvalCOMIX grades ('moodleevx').
     * @return array|object Grades of the report.
     */
    public function get_grades($mode = 'evalcomix') {
        $evalcomixgrades = $this->get_evalcomix_grades();
        if ($mode == 'evalcomix') {
            $grades = $evalcomixgrades;
        } else if ($mode == 'moodleevx') {
            $moodlegrades = $this->get_moodle_grades();
            $grades = $this->calculator->calculate($moodlegrades, $evalcomixgrades, $this->activities, $this->users);
        }

        return $grades;
    }

    /**
     * It gets EvalCOMIX grades
     * @return array grades
     */
    public function get_evalcomix_grades() {
        return $this->gradeviewer->get_grades($this->courseid, $this->users);
    }

    /**
     * we supply the userids in this query, and get all the grades
     * pulls out all the grades, this does not need to worry about paging
     */
    public function get_moodle_grades() {
        global $DB;
        $moodlegrades = array();

        // Looking for activities that have been evaluated.
        $items = $DB->get_records('grade_items', array('courseid' => $this->courseid, 'itemtype' => 'mod'));

        foreach ($items as $item) {
            // Looking for grades of the activities that have been evaluated.
            $grades = $DB->get_records('grade_grades', array('itemid' => $item->id));
            foreach ($grades as $grade) {
                // Looking for cmid of the activities that have been evaluated.
                $cm = $DB->get_record('course_modules', array('course' => $this->courseid, 'instance' => $item->iteminstance));
                if (isset($cm)) {
                    $moodlegrades[$cm->id][$grade->userid] = $grade->finalgrade;
                }
            }
        }

        return $moodlegrades;
    }

    /**
     * @return array of course tools by [taskid][modality]
     */
    public function load_tools() {
        global $DB;
        $result = array();
        if ($evalcomix = $DB->get_record('block_evalcomix', array('courseid' => $this->courseid))) {
            if ($tools = $DB->get_records('block_evalcomix_tools', array('evxid' => $evalcomix->id))) {
                foreach ($tools as $tool) {
                    if ($modes = $DB->get_records('block_evalcomix_modes', array('toolid' => $tool->id))) {
                        foreach ($modes as $mode) {
                            $taskid = $mode->taskid;
                            $modality = $mode->modality;
                            $result[$taskid][$modality] = $tool;
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @param array tasks
     * @return array of course assessments by [taskid][assessor][student]
     */
    public function load_assessments($tasks) {
        global $DB;
        $result = array();
        if (is_array($tasks)) {
            foreach ($tasks as $task) {
                if (isset($task->id)) {
                    if ($assessments = $DB->get_records('block_evalcomix_assessments', array('taskid' => $task->id))) {
                        $taskid = $task->id;
                        foreach ($assessments as $assessment) {
                            $assessorid = $assessment->assessorid;
                            $studentid = $assessment->studentid;
                            $result[$taskid][$assessorid][$studentid] = $assessment;
                        }
                    }
                }
            }
        }
        return $result;
    }


    /**
     * pulls out the userids of the users to be display, and sorts them
     */
    public function load_users($page = true) {
        global $CFG, $DB;

        // Limit to users with a gradeable role.
        list($gradebookrolessql, $gradebookrolesparams) =
        $DB->get_in_or_equal(explode(',', $this->gradebookroles), SQL_PARAMS_NAMED, 'grbr0');

        // Limit to users with an active enrollment.
        list($enrolledsql, $enrolledparams) = get_enrolled_sql($this->context);

        // Fields we need from the user table.
        $userfields = 'u.id,u.picture,u.firstname,u.lastname,u.firstnamephonetic,u.lastnamephonetic,
        u.middlename,u.alternatename,u.imagealt,u.email,u.idnumber';

        $sortjoin = $sort = $params = null;

        // If the user has clicked one of the sort asc/desc arrows.
        if (is_numeric($this->sortitemid)) {
            $params = array_merge(array('gitemid' => $this->sortitemid), $gradebookrolesparams,
                $this->groupwheresql_params, $enrolledparams);

            $sortjoin = "LEFT JOIN {grade_grades} g ON g.userid = u.id AND g.itemid = $this->sortitemid";
            $sort = "g.finalgrade $this->sortorder";

        } else {
            $sortjoin = '';
            switch($this->sortitemid) {
                case 'lastname':
                    $sort = "u.lastname $this->sortorder, u.firstname $this->sortorder";
                    break;
                case 'firstname':
                    $sort = "u.firstname $this->sortorder, u.lastname $this->sortorder";
                    break;
                case 'idnumber':
                default:
                    $sort = "u.idnumber $this->sortorder";
                    break;
            }

            $params = array_merge($gradebookrolesparams, $this->groupwheresql_params, $enrolledparams);
        }

        $sqlcontext = '';
        if ($parents = $this->context->get_parent_context_ids()) {
            $sqlcontext = (' IN ('.$this->context->id.','.implode(',', $parents).')');
        } else {
            $sqlcontext = (' ='.$this->context->id);
        }
        $sql = "SELECT $userfields
                  FROM {user} u
                  JOIN ($enrolledsql) je ON je.id = u.id
                       $this->groupsql
                       $sortjoin
                  JOIN (
                           SELECT DISTINCT ra.userid
                             FROM {role_assignments} ra
                            WHERE ra.roleid IN ($this->gradebookroles)
                              AND ra.contextid " . $sqlcontext . "
                       ) rainner ON rainner.userid = u.id
                   AND u.deleted = 0
                   $this->groupwheresql
              ORDER BY $sort";

        if ($page == true) {
            $this->users = $DB->get_records_sql($sql, $params, $this->studentsperpage * $this->page, $this->studentsperpage);
        } else {
            $this->users = $DB->get_records_sql($sql, $params);
        }

        if (empty($this->users)) {
            $this->userselect = '';
            $this->users = array();
            $this->userselect_params = array();
        } else {
            list($usql, $uparams) = $DB->get_in_or_equal(array_keys($this->users), SQL_PARAMS_NAMED, 'usid0');
            $this->userselect = "AND g.userid $usql";
            $this->userselect_params = $uparams;

            // Add a flag to each user indicating whether their enrolment is active.
            $sql = "SELECT ue.userid
                      FROM {user_enrolments} ue
                      JOIN {enrol} e ON e.id = ue.enrolid
                     WHERE ue.userid $usql
                           AND ue.status = :uestatus
                           AND e.status = :estatus
                           AND e.courseid = :courseid
                  GROUP BY ue.userid";
            $coursecontext = context_course::instance($this->courseid);
            $params = array_merge($uparams, array('estatus' => ENROL_INSTANCE_ENABLED, 'uestatus' => ENROL_USER_ACTIVE,
            'courseid' => $coursecontext->instanceid));
            $useractiveenrolments = $DB->get_records_sql($sql, $params);

            foreach ($this->users as $user) {
                $this->users[$user->id]->suspendedenrolment = !array_key_exists($user->id, $useractiveenrolments);
            }
        }

        return $this->users;
    }

    /**
     * Refactored function for generating HTML of sorting links with matching arrows.
     * Returns an array with 'studentname' and 'idnumber' as keys, with HTML ready
     * to inject into a table header cell.
     * @return array An associative array of HTML sorting links+arrows
     */
    public function get_sort_arrows() {
        global $OUTPUT;
        $arrows = array();

        $strsortasc   = $this->get_lang_string('sortasc', 'grades');
        $strsortdesc  = $this->get_lang_string('sortdesc', 'grades');
        $strfirstname = $this->get_lang_string('firstname');
        $strlastname  = $this->get_lang_string('lastname');

        $firstlink = html_writer::link(new moodle_url($this->baseurl, array('sortitemid' => 'firstname')), $strfirstname);
        $lastlink = html_writer::link(new moodle_url($this->baseurl, array('sortitemid' => 'lastname')), $strlastname);
        $idnumberlink = html_writer::link(new moodle_url($this->baseurl, array('sortitemid' => 'idnumber')),
            get_string('idnumber'));

        $arrows['studentname'] = $lastlink;

        if ($this->sortitemid === 'lastname') {
            if ($this->sortorder == 'ASC') {
                $arrows['studentname'] .= '<img src="'.$OUTPUT->image_url('t/' . 'up') . '" alt="'.$strsortasc.'" /> ';
            } else {
                $arrows['studentname'] .= '<img src="'.$OUTPUT->image_url('t/' . 'down') . '" alt="'.$strsortasc.'" /> ';
            }
        }

        $arrows['studentname'] .= ' ' . $firstlink;

        if ($this->sortitemid === 'firstname') {
            if ($this->sortorder == 'ASC') {
                $arrows['studentname'] .= '<img src="'.$OUTPUT->image_url('t/' . 'up') . '" alt="'.$strsortdesc.'" /> ';
            } else {
                $arrows['studentname'] .= '<img src="'.$OUTPUT->image_url('t/' . 'down') . '" alt="'.$strsortdesc.'" /> ';
            }
        }

        $arrows['idnumber'] = $idnumberlink;

        if ('idnumber' == $this->sortitemid) {
            if ($this->sortorder == 'ASC') {
                $arrows['idnumber'] .= '<img src="'.$OUTPUT->image_url('t/' . 'up') . '" alt="'.$strsortasc.'" /> ';
            } else {
                $arrows['idnumber'] .= '<img src="'.$OUTPUT->image_url('t/' . 'down') . '" alt="'.$strsortasc.'" /> ';
            }
        }

        return $arrows;
    }

    /**
     * This function indicates whether a student can evaluate himself or another student in a activity
     *
     * @param object $user
     * @param object $cm
     * @return bool
     */
    public function student_can_assess($user, $cm) {
        global $DB, $USER;
        $result = false;

        $mode = self::get_type_evaluation($user->id, $this->courseid);
        if ($mode == 'self') {
            $result = true;
        } else if ($mode == 'peer') {
            // Obtains the groupmode of the activity.
            $groupmode = $cm->groupmode;

            $groupmembersonly = 0;
            if (property_exists($cm, 'groupmembersonly')) {
                $groupmembersonly = $cm->groupmembersonly;
            }

            $groupingid = $cm->groupingid;
            $samegrouping = false;
            $samegroup = $this->same_group($USER->id, $user->id);

            if ($groupingid != 0) {
                $samegrouping = $this->same_grouping_by_users($USER->id, $user->id, $cm);
            }

            $whoassesses = null;
            if ($task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cm->id))) {
                $taskid = $task->id;
                if ($modepeer = $DB->get_record('block_evalcomix_modes', array('taskid' => $taskid, 'modality' => 'peer'))) {
                    if ($modepeerextra = $DB->get_record('block_evalcomix_modes_extra', array('modeid' => $modepeer->id))) {
                        $whoassesses = $modepeerextra->whoassesses;
                    }
                }
            }

            /*Groupmode == 1 -> Separated Groups */
            if ($whoassesses != null) {
                switch($whoassesses) {
                    case 0: {
                        $result = true;
                    }
                    break;
                    case 1: {
                        $result = ((!$groupmembersonly && (
                                            ($samegrouping && (
                                                    ($groupmode != 1
                                                    || $samegroup))
                                            )
                                            ||
                                            (!$groupingid &&
                                                (($groupmode != 1
                                                    || $samegroup)))
                                            )
                                        )
                                        || ($groupmembersonly  && (
                                            (!$groupingid &&
                                                ((($groupmode != 1 && $gidloginuser != -1 && $giduser != -1)
                                                    || $samegroup)))
                                            ||
                                            ($samegrouping && (
                                                ($groupmode != 1
                                                    || $samegroup)))
                                            )
                                        ));
                    }
                    break;
                    case 2: {
                        if ($evalcomixallowedusers = $DB->get_records('block_evalcomix_allowedusers',
                            array('cmid' => $cm->id, 'assessorid' => $USER->id))) {
                            foreach ($evalcomixallowedusers as $auser) {
                                $indexuser = $auser->studentid;
                                $allowedusers[$indexuser] = true;
                            }
                        }
                        $userid = $user->id;
                        if (isset($allowedusers[$userid])) {
                            $result = $allowedusers[$userid];
                        } else if ($USER->id == $user->id) {
                            $result = true;
                        }
                    }
                    break;
                }
            }
        }
        return $result;
    }
}
