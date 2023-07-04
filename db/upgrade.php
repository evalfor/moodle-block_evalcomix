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

/**
 * @param int $oldversion
 */
function xmldb_block_evalcomix_upgrade($oldversion = 201111802) {
    global $DB;
    $dbman = $DB->get_manager();

    $result = true;

    // Add a new column newcol to the mdl_question_myqtype.
    if ($oldversion < 2012013003) {

        // Define table block_evalcomix to be created.
        $table = new xmldb_table('block_evalcomix');

        // Adding fields to table block_evalcomix.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0');
        $table->add_field('viewmode', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, 'evx');
        $table->add_field('sendgradebook', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, null, null, '0');

        // Adding keys to table block_evalcomix.
        $table->add_key('id', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_evalcomix.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2012013003, 'evalcomix');
    }

    if ($oldversion < 2012013004) {
        // Define table block_evalcomix_tasks to be created.
        $table = new xmldb_table('block_evalcomix_tasks');

        // Adding fields to table block_evalcomix_tasks.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('instanceid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0');
        $table->add_field('maxgrade', XMLDB_TYPE_NUMBER, '10, 5', null, null, null, '0');
        $table->add_field('weighing', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0');

        // Adding keys to table block_evalcomix_tasks.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_evalcomix_tasks.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2012013004, 'evalcomix');
    }

    if ($oldversion < 2012013005) {
        // Define table block_evalcomix_modes to be created.
        $table = new xmldb_table('block_evalcomix_modes');

        // Adding fields to table block_evalcomix_modes.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('taskid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0');
        $table->add_field('toolid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0');
        $table->add_field('modality', XMLDB_TYPE_CHAR, '7', null, XMLDB_NOTNULL, null, 'teacher');
        $table->add_field('weighing', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0');

        // Adding keys to table block_evalcomix_modes.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_evalcomix_modes.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2012013005, 'evalcomix');
    }

    if ($oldversion < 2012013006) {

        // Define table block_evalcomix_modes_time to be created.
        $table = new xmldb_table('block_evalcomix_modes_time');

        // Adding fields to table block_evalcomix_modes_time.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('modeid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0');
        $table->add_field('timeavailable', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, '0');
        $table->add_field('timedue', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, '0');

        // Adding keys to table block_evalcomix_modes_time.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_evalcomix_modes_time.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2012013006, 'evalcomix');
    }

    if ($oldversion < 2012013007) {

        // Define table block_evalcomix_modes_extra to be created.
        $table = new xmldb_table('block_evalcomix_modes_extra');

        // Adding fields to table block_evalcomix_modes_extra.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('anonymous', XMLDB_TYPE_INTEGER, '1', null, null, null, '0');
        $table->add_field('modeid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table block_evalcomix_modes_extra.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_evalcomix_modes_extra.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2012013007, 'evalcomix');
    }

    if ($oldversion < 2012013008) {

        // Define table block_evalcomix_tools to be created.
        $table = new xmldb_table('block_evalcomix_tools');

        // Adding fields to table block_evalcomix_tools.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('evxid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('title', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('type', XMLDB_TYPE_CHAR, '12', null, XMLDB_NOTNULL, null, 'scale');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('idtool', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table block_evalcomix_tools.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_evalcomix_tools.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2012013008, 'evalcomix');
    }

    if ($oldversion < 2013102505) {

        // Define table block_evalcomix_grades to be created.
        $table = new xmldb_table('block_evalcomix_grades');

        // Adding fields to table block_evalcomix_grades.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('cmid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('finalgrade', XMLDB_TYPE_NUMBER, '10, 5', null, null, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_evalcomix_grades.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_evalcomix_grades.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2013102505, 'evalcomix');

        global $CFG;
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_grades.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_assessments.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes_extra.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes_time.php');

        if ($tasks = evalcomix_tasks::fetch_all(array())) {
            foreach ($tasks as $task) {
                echo '<br><br><br>Procesando task: ' . $task->id . '<br>';
                echo 'Finalgrades asociados: <br>';
                if ($cm = $DB->get_record('course_modules', array('id' => $task->instanceid))) {
                    $courseid = $cm->course;
                    if ($assessments = $DB->get_records('block_evalcomix_assessments', array('taskid' => $task->id))) {
                        foreach ($assessments as $assessment) {
                            $params = array();

                            $params['cmid'] = $task->instanceid;
                            $params['userid'] = $assessment->studentid;
                            $params['courseid'] = $courseid;
                            $finalgrade = block_evalcomix_grades::get_finalgrade_user_task($params);
                            echo "finalgrade: $finalgrade <br>";
                            if ($finalgrade !== null) {
                                if ($gradeobject = block_evalcomix_grades::fetch($params)) {
                                    $gradeobject->finalgrade = $finalgrade;
                                    $gradeobject->update();
                                } else {
                                    $params['finalgrade'] = $finalgrade;
                                    $gradeobject = new block_evalcomix_grades($params);
                                    $gradeobject->insert();
                                }
                            } else {
                                if ($gradeobject = block_evalcomix_grades::fetch($params)) {
                                    $gradeobject->delete();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    if ($oldversion < 2013111801) {
        // Define table block_evalcomix_allowedusers to be created.
        $table = new xmldb_table('block_evalcomix_allowedusers');

        // Adding fields to table block_evalcomix_allowedusers.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('cmid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('assessorid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('studentid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_evalcomix_allowedusers.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_evalcomix_allowedusers.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2013111801, 'evalcomix');
    }

    if ($oldversion < 2013111802) {

        // Define field whoassesses to be added to block_evalcomix_modes_extra.
        $table = new xmldb_table('block_evalcomix_modes_extra');
        $field1 = new xmldb_field('visible', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        if (!$dbman->field_exists($table, $field1)) {
            $dbman->add_field($table, $field1);
        }

        $field2 = new xmldb_field('whoassesses', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'visible');
        // Conditionally launch add field whoassesses.
        if (!$dbman->field_exists($table, $field2)) {
            $dbman->add_field($table, $field2);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2013111802, 'evalcomix');
    }

    if ($oldversion < 2013121700) {

        // Define field visible to be added to block_evalcomix_tasks.
        $table = new xmldb_table('block_evalcomix_tasks');
        $field = new xmldb_field('visible', XMLDB_TYPE_INTEGER, '1', null, null, null, '1', 'timemodified');

        // Conditionally launch add field visible.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2013121700, 'evalcomix');
    }

    if ($oldversion < 2014040716) {

        // Define field idtool to be added to block_evalcomix_tools.
        $table = new xmldb_table('block_evalcomix_tools');
        $field = new xmldb_field('idtool', XMLDB_TYPE_CHAR, '40', null, XMLDB_NOTNULL, null, '0', 'timemodified');

        // Conditionally launch add field idtool.
        if ($dbman->field_exists($table, $field)) {
            $dbman->change_field_type($table, $field);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2014040716, 'evalcomix');
    }

    if ($oldversion < 2021081102) {

        // Define field grademethod to be added to block_evalcomix_tasks.
        $table = new xmldb_table('block_evalcomix_tasks');
        $field = new xmldb_field('grademethod', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'visible');

        // Conditionally launch add field grademethod.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2021081102, 'evalcomix');
    }

    if ($oldversion < 2022010401) {

        // Define table block_evalcomix_competencies to be created.
        $table = new xmldb_table('block_evalcomix_competencies');

        // Adding fields to table block_evalcomix_competencies.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('idnumber', XMLDB_TYPE_CHAR, '100', null, null, null, null);
        $table->add_field('shortname', XMLDB_TYPE_CHAR, '100', null, null, null, null);
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('typeid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('outcome', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table block_evalcomix_competencies.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for block_evalcomix_competencies.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

           // Define table block_evalcomix_comptype to be created.
        $table2 = new xmldb_table('block_evalcomix_comptype');

        // Adding fields to table block_evalcomix_comptype.
        $table2->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table2->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table2->add_field('shortname', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table2->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Adding keys to table block_evalcomix_comptype.
        $table2->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for block_evalcomix_comptype.
        if (!$dbman->table_exists($table2)) {
            $dbman->create_table($table2);
        }

         // Define table block_evalcomix_subdimension to be created.
        $table3 = new xmldb_table('block_evalcomix_subdimension');

        // Adding fields to table block_evalcomix_subdimension.
        $table3->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table3->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table3->add_field('toolid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table3->add_field('competencyid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table3->add_field('subdimensionid', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_evalcomix_subdimension.
        $table3->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for block_evalcomix_subdimension.
        if (!$dbman->table_exists($table3)) {
            $dbman->create_table($table3);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2022010401, 'evalcomix');
    }

    if ($oldversion < 2022033100) {

         // Define field threshold to be added to block_evalcomix_tasks.
        $table = new xmldb_table('block_evalcomix_tasks');
        $field = new xmldb_field('threshold', XMLDB_TYPE_INTEGER, '10', null, null, null, '15', 'grademethod');

        // Conditionally launch add field threshold.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2022033100, 'evalcomix');
    }

    if ($oldversion < 2022051600) {

        // Define field workteams to be added to block_evalcomix_tasks.
        $table = new xmldb_table('block_evalcomix_tasks');
        $field = new xmldb_field('workteams', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'threshold');

        // Conditionally launch add field workteams.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define table block_evalcomix_coordinators to be created.
        $newtable = new xmldb_table('block_evalcomix_coordinators');

        // Adding fields to table block_evalcomix_coordinators.
        $newtable->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $newtable->add_field('taskid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $newtable->add_field('groupid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $newtable->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_evalcomix_coordinators.
        $newtable->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for block_evalcomix_coordinators.
        if (!$dbman->table_exists($newtable)) {
            $dbman->create_table($newtable);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2022051600, 'evalcomix');
    }

    if ($oldversion < 2022112802) {

        // Define field idassessment to be added to block_evalcomix_assessments.
        $table = new xmldb_table('block_evalcomix_assessments');
        $field = new xmldb_field('idassessment', XMLDB_TYPE_CHAR, '64', null, XMLDB_NOTNULL, null, '0', 'timemodified');

        // Conditionally launch add field idassessment.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2022112802, 'evalcomix');
    }

    if ($oldversion < 2023031800) {

        // Define table block_evalcomix_dr_pending to be created.
        $table = new xmldb_table('block_evalcomix_dr_pending');

        // Adding fields to table block_evalcomix_dr_pending.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('cmid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('modeid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('idassessment', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('idsubdimension', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);

        // Adding keys to table block_evalcomix_dr_pending.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table block_evalcomix_dr_pending.
        $table->add_index('block_evalcomix_courseid_ix', XMLDB_INDEX_NOTUNIQUE, ['courseid']);
        $table->add_index('block_evalcomix_cmid_ix', XMLDB_INDEX_NOTUNIQUE, ['cmid']);
        $table->add_index('block_evalcomix_idass_ix', XMLDB_INDEX_NOTUNIQUE, ['idassessment']);
        $table->add_index('block_evalcomix_subass_ix', XMLDB_INDEX_UNIQUE, ['idsubdimension', 'idassessment']);

        // Conditionally launch create table for block_evalcomix_dr_pending.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table block_evalcomix_dr_grade to be created.
        $table = new xmldb_table('block_evalcomix_dr_grade');

        // Adding fields to table block_evalcomix_dr_grade.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('cmid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('modeid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('idassessment', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('idsubdimension', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('grade', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table block_evalcomix_dr_grade.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table block_evalcomix_dr_grade.
        $table->add_index('block_evalcomix_drgcmid_ix', XMLDB_INDEX_NOTUNIQUE, ['cmid']);
        $table->add_index('block_evalcomix_drg_subass_ix', XMLDB_INDEX_UNIQUE, ['idsubdimension', 'idassessment']);
        $table->add_index('block_evalcomix_drgass_ix', XMLDB_INDEX_NOTUNIQUE, ['idassessment']);

        // Conditionally launch create table for block_evalcomix_dr_grade.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2023031800, 'evalcomix');
    }

    if ($oldversion < 2023042400) {

        // Define field modeid to be added to block_evalcomix_assessments.
        $table = new xmldb_table('block_evalcomix_assessments');
        $field = new xmldb_field('modeid', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'taskid');

        // Conditionally launch add field modeid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Evalcomix savepoint reached.
        upgrade_block_savepoint(true, 2023042400, 'evalcomix');
    }

    return $result;
}
