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

class backup_evalcomix_block_structure_step extends backup_block_structure_step {

    protected function define_structure() {

        // Define each element separated.
        $evalcomix = new backup_nested_element('evalcomix', array('id'), array('viewmode'));
        $evalcomixenvironment = new backup_nested_element('environment', null, array('courseid', 'moodlename'));
        $evalcomixtools = new backup_nested_element('tools');
        $evalcomixtool = new backup_nested_element('tool', array('id'), array('title', 'type', 'timecreated',
            'timemodified', 'idtool', 'code'));
        $evalcomixtasks = new backup_nested_element('tasks');
        $evalcomixtask = new backup_nested_element('task', array('id'),
            array('instanceid', 'maxgrade', 'weighing', 'timemodified', 'visible', 'grademethod', 'workteams'));
        $evalcomixassessments = new backup_nested_element('assessments');
        $evalcomixassessment = new backup_nested_element('assessment', array('id'),
            array('assessorid', 'studentid', 'grade', 'timemodified'));
        $evalcomixmodes = new backup_nested_element('modes');
        $evalcomixmode = new backup_nested_element('mode', array('id'), array('toolid', 'modality', 'weighing'));
        $evalcomixmodestime = new backup_nested_element('mode_time', array('id'), array('timeavailable', 'timedue'));
        $evalcomixmodesextra = new backup_nested_element('mode_extra', array('id'), array('anonymous', 'visible', 'whoassesses'));
        $evalcomixcoordinators = new backup_nested_element('coordinators');
        $evalcomixcoordinator = new backup_nested_element('coordinator', array('id'), array('groupid', 'userid'));
        $evalcomixgrades = new backup_nested_element('grades');
        $evalcomixgrade = new backup_nested_element('grade', array('id'), array('userid', 'cmid', 'finalgrade', 'courseid'));
        $evalcomixallowedusers = new backup_nested_element('allowedusers');
        $evalcomixalloweduser = new backup_nested_element('alloweduser', array('id'), array('cmid', 'assessorid', 'studentid'));
        $evalcomixcompetencysection = new backup_nested_element('competencysection');
        $evalcomixcomptypes = new backup_nested_element('competencytypes');
        $evalcomixcomptype = new backup_nested_element('competencytype', array('id'), array('shortname', 'description'));
        $evalcomixcompetencies = new backup_nested_element('competencies');
        $evalcomixcompetency = new backup_nested_element('competency', array('id'), array('idnumber', 'shortname',
            'description', 'typeid', 'outcome'));
        $evalcomixsubdimensions = new backup_nested_element('subdimensions');
        $evalcomixsubdimension = new backup_nested_element('subdimension', array('id'), array('toolid', 'competencyid',
            'subdimensionid'));

        // Build the tree.
        $evalcomix->add_child($evalcomixtools);
        $evalcomix->add_child($evalcomixenvironment);
        $evalcomix->add_child($evalcomixtasks);
        $evalcomix->add_child($evalcomixgrades);
        $evalcomixgrades->add_child($evalcomixgrade);
        $evalcomix->add_child($evalcomixallowedusers);
        $evalcomixallowedusers->add_child($evalcomixalloweduser);
        $evalcomixtools->add_child($evalcomixtool);
        $evalcomixtasks->add_child($evalcomixtask);
        $evalcomixtask->add_child($evalcomixassessments);
        $evalcomixtask->add_child($evalcomixmodes);
        $evalcomixtask->add_child($evalcomixcoordinators);
        $evalcomixassessments->add_child($evalcomixassessment);
        $evalcomixmodes->add_child($evalcomixmode);
        $evalcomixmode->add_child($evalcomixmodestime);
        $evalcomixmode->add_child($evalcomixmodesextra);
        $evalcomixcoordinators->add_child($evalcomixcoordinator);
        $evalcomix->add_child($evalcomixcompetencysection);
        $evalcomixcompetencysection->add_child($evalcomixcomptypes);
        $evalcomixcompetencysection->add_child($evalcomixcompetencies);
        $evalcomixcomptypes->add_child($evalcomixcomptype);
        $evalcomixcompetencies->add_child($evalcomixcompetency);
        $evalcomixcompetencysection->add_child($evalcomixsubdimensions);
        $evalcomixsubdimensions->add_child($evalcomixsubdimension);

        // Define sources.
        global $DB, $COURSE, $CFG;
        $courseid = $this->get_courseid();
        $cms = $DB->get_records('course_modules', array('course' => $courseid));
        $items = array();
        foreach ($cms as $cm) {
            $items[] = $cm->id;
        }
        $inparams = array();
        if (!empty($items)) {
            list($insql, $inparams) = $DB->get_in_or_equal($items);
            foreach ($inparams as $key => $value) {
                $inparams[$key] = backup_helper::is_sqlparam($value);
            }
        }

        if ($block = $DB->get_record('block_evalcomix', array('courseid' => $courseid))) {
            $evalcomix->set_source_table('block_evalcomix', array('id' => backup_helper::is_sqlparam($block->id)));
        }

        require_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
        $evalcomixenvironment->set_source_array(array((object)array('courseid' => $COURSE->id,
            'moodlename' => BLOCK_EVALCOMIX_MOODLE_NAME)));

        try {
            $arrayxmltool = array();
            $xml = block_evalcomix_webservice_client::get_ws_xml_tools2(array('courseid' => $courseid));
            foreach ($xml as $toolxml) {
                $id = (string)$toolxml['id'];
                foreach ($toolxml as $txml) {
                    $arrayxmltool[$id] = $txml->asXML();
                }
            }
            if ($tools = $DB->get_records('block_evalcomix_tools', array('evxid' => $block->id))) {
                $array = array();
                foreach ($tools as $tool) {
                    $time = time();
                    $idtool = $tool->idtool;
                    if (isset($arrayxmltool[$idtool])) {
                        $array[] = (object)array('id' => $tool->id, 'title' => $tool->title, 'type' => $tool->type,
                            'timecreated' => $time, 'timemodified' => $time, 'idtool' => $idtool, 'code' => $arrayxmltool[$idtool]);
                    }
                }
                $evalcomixtool->set_source_array($array);
            }
        } catch (Exception $e) {
             echo $e->getMessage();
            // This exception is not handled.
        }

        if (!empty($inparams)) {
            $evalcomixtask->set_source_sql("
                SELECT *
                  FROM {block_evalcomix_tasks}
                 WHERE instanceid $insql", $inparams);

            $evalcomixgrade->set_source_sql("
                SELECT *
                  FROM {block_evalcomix_grades}
                 WHERE cmid $insql", $inparams);

            $evalcomixalloweduser->set_source_sql("
                SELECT *
                  FROM {block_evalcomix_allowedusers}
                 WHERE cmid $insql", $inparams);
        }

        $evalcomixassessment->set_source_table('block_evalcomix_assessments', array('taskid' => backup::VAR_PARENTID));
        $evalcomixmode->set_source_table('block_evalcomix_modes', array('taskid' => backup::VAR_PARENTID));
        $evalcomixmodestime->set_source_table('block_evalcomix_modes_time', array('modeid' => backup::VAR_PARENTID));
        $evalcomixmodesextra->set_source_table('block_evalcomix_modes_extra', array('modeid' => backup::VAR_PARENTID));
        $evalcomixcomptype->set_source_table('block_evalcomix_comptype', array('courseid' => backup::VAR_COURSEID));
        $evalcomixcompetency->set_source_table('block_evalcomix_competencies', array('courseid' => backup::VAR_COURSEID));
        $evalcomixsubdimension->set_source_table('block_evalcomix_subdimension', array('courseid' => backup::VAR_COURSEID));
        $evalcomixcoordinator->set_source_table('block_evalcomix_coordinators', array('taskid' => backup::VAR_PARENTID));

        // Define annotations.
        $evalcomixtask->annotate_ids('course_modules', 'instanceid');
        $evalcomixassessment->annotate_ids('user', 'assessorid');
        $evalcomixassessment->annotate_ids('user', 'studentid');
        $evalcomixcoordinator->annotate_ids('user', 'userid');
        $evalcomixcoordinator->annotate_ids('groups', 'groupid');

        return $this->prepare_block_structure($evalcomix);
    }
}
