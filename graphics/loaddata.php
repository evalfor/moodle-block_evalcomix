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
 * Loading datas for Graphics
 *
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <info@ansaner.net>
 */
require_once('../../../config.php');
require_login();

$requestgraphic = optional_param('requestgraphic', '', PARAM_RAW);
$target = optional_param('target', '', PARAM_RAW);
$groupid = optional_param('group', 0, PARAM_INT);
$users = optional_param('user', '', PARAM_RAW);
$modality = optional_param('modality', '', PARAM_RAW);
$mode = required_param('mode', PARAM_INT);
$taskid = optional_param('task', 0, PARAM_INT);
$courseid      = required_param('id', PARAM_INT);
$check = optional_param('check', 0, PARAM_INT);
if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('nocourseid');
}
$context = context_course::instance($course->id);
$PAGE->set_context($context);
require_once($CFG->dirroot . '/blocks/evalcomix/graphics/graphicsrenderer.php');
$renderer = new block_evalcomix_graphic_renderer();

// Get students.
if ($requestgraphic == 'getstudents' && !empty($taskid)) {
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_assessments.php');
    if (!empty($taskid) && !$users = evalcomix_assessments::get_students_assessed($taskid)) {
        echo '<option style="color:#f00">'.get_string('nostudents', 'block_evalcomix').'</option>';
    }
    if (!empty($users)) {
        echo '<option value="0">'.get_string('selectstudent', 'block_evalcomix').'</option>';
        foreach ($users as $userid) {
            if ($user = $DB->get_record('user', array('id' => $userid, 'deleted' => '0'))) {
                echo '<option value="'.$userid.'" >'.$user->lastname . ', ' . $user->firstname.'</option>';
            }
        }
    }
}

// Get students of a group.
if ($requestgraphic == 'getstudentsgroup' && !empty($taskid) && $mode == 1 && !empty($groupid)) {
    require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_assessments.php');
    require_once($CFG->dirroot .'/blocks/evalcomix/graphics/graphicslib.php');

    if ($arraystudents = get_group_members_evaluated($groupid, $taskid)) {
        foreach ($arraystudents as $student) {
            $studentid = $student->id;
            echo '
            <div><input type="checkbox" name="stu-'.$studentid.'" id="stu-'.$studentid.'" checked value="'.$studentid.'"
                onclick="modify_graphic(1);
                "><label for="stu-'.$studentid.'">'
                .$student->lastname . ', ' . $student->firstname.'</label></div>
            ';
        }
    } else {
        echo '<div style="color:#f00">'.get_string('nostudentsgroup', 'block_evalcomix').'</div>';
    }
}

$xlabels = array();
// Datas for graphic task-student.
if ($requestgraphic == 'box' && $mode == 1 && !empty($taskid) && $modality == 'student' && !empty($users)) {
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/calculator_average.php');
    $assessments = evalcomix_assessments::get_assessments_by_modality($taskid, $users);
    $result = array();
    $arrayself = array();
    $arraypeer = array();
    $arrayteacher = array();
    if ($assessments) {
        $teachergrades = evalcomix_assessments::calculate_grades($assessments->teacherassessments);
        $peergrades = evalcomix_assessments::calculate_grades($assessments->peerassessments);

        $xlabels[] = array(get_string('teachermod', 'block_evalcomix'));
        if ($teachergrades) {
            foreach ($teachergrades as $teachergrade) {
                $arrayteacher[] = (int)$teachergrade;
            }
        }

        $selfassessment = $assessments->selfassessment;
        $xlabels[] = array(get_string('selfmod', 'block_evalcomix'));
        if ($selfassessment) {
            $arrayself[] = (int)$selfassessment->grade;
        }

        $xlabels[] = array(get_string('peermod', 'block_evalcomix'));
        if ($peergrades) {
            foreach ($peergrades as $peergrade) {
                $arraypeer[] = (int)$peergrade;
            }
        }

        $result = array(
            'type' => 'box',
            'title' => array(get_string('profile_task_by_student', 'block_evalcomix')),
            'min' => 0,
            'max' => 100,
            'xlabels' => $xlabels,
            'datas' => array(
                $arrayteacher,
                $arrayself,
                $arraypeer,
            )
        );
    } else {
        $result = array(
                'type' => 'box',
                'title' => array(get_string('profile_task_by_student', 'block_evalcomix')),
                'min' => 0,
                'max' => 100,
                'xlabels' => 'no datas',
                'datas' => array(
                    array(get_string('no_datas', 'block_evalcomix'), 0)
                )
            );
    }
    $jsondata['status'] = (count($result) > 0);
    $jsondata['result'] = $result;
    echo json_encode($jsondata);
}

// Datas for graphic task-group.
if ($requestgraphic == 'box' && $mode == 1 && !empty($taskid) && $modality == 'group' && !empty($groupid)) {
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/calculator_average.php');
    require_once($CFG->dirroot .'/blocks/evalcomix/graphics/graphicslib.php');

    $arraystudents = array();
    if (empty($users) && empty($check)) {
        $arraystudents = get_group_members_evaluated($groupid, $taskid);
    } else {
        $studentids = explode('-', $users);
        foreach ($studentids as $studentid) {
            if (!empty($studentid) && $user = $DB->get_record('user', array('id' => $studentid))) {
                $arraystudents[$user->id] = $user;
            }
        }
    }

    if (!empty($arraystudents)) {
        $xlabels[0] = get_string('teachermod', 'block_evalcomix');
        $xlabels[1] = get_string('selfmod', 'block_evalcomix');
        $xlabels[2] = get_string('peermod', 'block_evalcomix');
        $tgrades = array();
        $pgrades = array();
        $sgrades = array();
        foreach ($arraystudents as $student) {
            $studentid = $student->id;
            $assessments = evalcomix_assessments::get_assessments_by_modality($taskid, (int)$studentid);
            if ($assessments) {
                if (!empty($assessments->teacherassessments)) {
                    $tgrade = evalcomix_assessments::calculate_gradearray($assessments->teacherassessments);
                    array_push($tgrades, $tgrade);
                }
                if (!empty($assessments->peerassessments)) {
                    $pgrade = evalcomix_assessments::calculate_gradearray($assessments->peerassessments);
                    array_push($pgrades, $pgrade);
                }
                if ($assessments->selfassessment) {
                    $selfassessment = $assessments->selfassessment;
                    $sgrade = (int)$selfassessment->grade;
                    array_push($sgrades, $sgrade);
                }
            }
        }
        $result = array(
                    'type' => 'box',
                    'title' => array(get_string('profile_task_by_group', 'block_evalcomix')),
                    'min' => 0,
                    'max' => 100,
                    'xlabels' => $xlabels,
                    'datas' => array(
                        $tgrades,
                        $sgrades,
                        $pgrades,
                    )
                );
    } else {
        $result = array(
                'type' => 'box',
                'title' => array(get_string('profile_task_by_group', 'block_evalcomix')),
                'min' => 0,
                'max' => 100,
                'xlabels' => array(get_string('no_datas', 'block_evalcomix')),
                'datas' => array(
                    array(get_string('no_datas', 'block_evalcomix'))
                )
            );
    }
    $jsondata['status'] = (count($result) > 0);
    $jsondata['result'] = $result;
    echo json_encode($jsondata);
}

// Datas for graphic task-class.
if ($requestgraphic == 'box' && $mode == 1 && !empty($taskid) && $modality == 'class') {
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/calculator_average.php');
    $students = evalcomix_assessments::get_students_assessed($taskid);
    if ($students) {
        $xlabels[0] = get_string('teachermod', 'block_evalcomix');
        $xlabels[1] = get_string('selfmod', 'block_evalcomix');
        $xlabels[2] = get_string('peermod', 'block_evalcomix');
        $tgrades = array();
        $pgrades = array();
        $sgrades = array();
        foreach ($students as $student) {
            if ($DB->get_record('user', array('id' => $student, 'deleted' => '0'))) {
                $assessments = evalcomix_assessments::get_assessments_by_modality($taskid, $student);
                if (!empty($assessments->teacherassessments)) {
                    $tgrade = evalcomix_assessments::calculate_gradearray($assessments->teacherassessments);
                    array_push($tgrades, $tgrade);
                }

                if (!empty($assessments->peerassessments)) {
                    $pgrade = evalcomix_assessments::calculate_gradearray($assessments->peerassessments);
                    array_push($pgrades, $pgrade);
                }

                if ($assessments->selfassessment) {
                    $selfassessment = $assessments->selfassessment;
                    $sgrade = (int)$selfassessment->grade;
                    array_push($sgrades, $sgrade);
                }
            }
        }
        $result = array(
                    'type' => 'box',
                    'title' => array(get_string('profile_task_by_course', 'block_evalcomix')),
                    'min' => 0,
                    'max' => 100,
                    'xlabels' => $xlabels,
                    'datas' => array(
                        $tgrades,
                        $sgrades,
                        $pgrades,
                    )
                );
    } else {
        $result = array(
                'type' => 'box',
                'title' => array(get_string('profile_task_by_course', 'block_evalcomix')),
                'min' => 0,
                'max' => 100,
                'xlabels' => array(get_string('no_datas', 'block_evalcomix')),
                'datas' => array(
                    array(get_string('no_datas', 'block_evalcomix'))
                )
            );
    }
    $jsondata['status'] = (count($result) > 0);
    $jsondata['result'] = $result;
    echo json_encode($jsondata);
}

// Datas for graphic student-teacher.
if ($requestgraphic == 'bar' && $mode == 2 && !empty($taskid) && $modality == 'teacher' && !empty($users)) {
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
    $assessments = evalcomix_assessments::get_assessments_by_modality($taskid, $users);
    $teachergrades = array();
    if (!empty($assessments->teacherassessments)) {
        foreach ($assessments->teacherassessments as $tassessment) {
            if ($teacher = $DB->get_record('user', array('id' => $tassessment->assessorid))) {
                if (is_numeric($tassessment->grade)) {
                    $xlabels[] = $teacher->lastname . ', ' . $teacher->firstname;
                    $teachergrades[] = $tassessment->grade;
                }
            }
        }
        if (!empty($teachergrades)) {
            $result = array(
                'type' => 'bar',
                'title' => array(get_string('profile_student_by_teacher', 'block_evalcomix')),
                'min' => 0,
                'max' => 100,
                'xlabels' => $xlabels,
                'datas' => $teachergrades
                );
        } else {
            $result = array(
                'type' => 'bar',
                'title' => array(get_string('profile_student_by_teacher', 'block_evalcomix')),
                'min' => 0,
                'max' => 100,
                'xlabels' => $xlabels,
                'datas' => array(array(get_string('no_datas', 'block_evalcomix'), 0))
                );
        }
    } else {
        $result = array(
                'type' => 'bar',
                'title' => array(get_string('profile_student_by_teacher', 'block_evalcomix')),
                'min' => 0,
                'max' => 100,
                'xlabels' => array(get_string('no_datas', 'block_evalcomix')),
                'datas' => array(array(get_string('no_datas', 'block_evalcomix'), 0))
                );
    }

    $jsondata['status'] = (count($result) > 0);
    $jsondata['result'] = $result;
    echo json_encode($jsondata);
}

// Datas for graphic student-peer.
if ($requestgraphic == 'bar' && $mode == 2 && !empty($taskid) && $modality == 'peer' && !empty($users)) {
    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
    $assessments = evalcomix_assessments::get_assessments_by_modality($taskid, $users);
    $peergrades = array();
    if (!empty($assessments->peerassessments)) {
        foreach ($assessments->peerassessments as $passessment) {
            if ($peer = $DB->get_record('user', array('id' => $passessment->assessorid))) {
                if (is_numeric($passessment->grade)) {
                    $xlabels[] = $peer->lastname . ', ' . $peer->firstname;
                    $peergrades[] = $passessment->grade;
                }
            }
        }
        $result = array(
                    'type' => 'bar',
                    'title' => array(get_string('profile_student_by_group', 'block_evalcomix')),
                    'min' => 0,
                    'max' => 100,
                    'xlabels' => $xlabels,
                    'datas' => $peergrades
                );
    } else {
        $result = array(
                    'type' => 'bar',
                    'title' => array(get_string('profile_student_by_group', 'block_evalcomix')),
                    'min' => 0,
                    'max' => 100,
                    'xlabels' => array(get_string('no_datas', 'block_evalcomix')),
                    'datas' => array(array(get_string('no_datas', 'block_evalcomix'), 0))
                );
    }

    $jsondata['status'] = (count($result) > 0);
    $jsondata['result'] = $result;
    echo json_encode($jsondata);
}