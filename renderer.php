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
 * Defines the renderer for the block_evalcomix
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

require_once('../../../config.php');
require_login();

class block_evalcomix_renderer extends plugin_renderer_base {

    public $validformats = array('xls');
    /**
     * print selftask form
     * @param array $params
     * @return string $output with HTML code
     */
    public function view_form_selftask($params) {
        global $CFG;
        require_once($CFG->dirroot . '/blocks/evalcomix/assessment/lib.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes.php');

        $courseid = $params['courseid'];
        $context = $params['context'];
        $params['modality'] = 'self';

        $elements = get_elements_course($params);

        echo '
        <script type="text/javascript" src="'.$CFG->wwwroot.'/blocks/evalcomix/reports/javascript/select_all_in.js">
        </script>
        <script type="text/javascript" src="'.$CFG->wwwroot.'/blocks/evalcomix/ajax.js"></script>
        ';
        $output = '';
        $output .= html_writer::start_tag('center');

        $output .= $this->logoheader();
        $output .= html_writer::start_tag('div');
        $output .= html_writer::empty_tag('input', array('type' => 'button',
        'class' => 'text-secondary', 'value' => get_string('assesssection', 'block_evalcomix'),
        'onclick' => "location.href='". $CFG->wwwroot ."/blocks/evalcomix/assessment/index.php?id=".$courseid ."'"));
        $output .= html_writer::end_tag('div');
        $output .= html_writer::start_tag('h1');
        $output .= get_string('selftask', 'block_evalcomix');
        $output .= html_writer::end_tag('h1');

        $output .= html_writer::start_tag('form', array('action' => 'download_preview.php?id='.
        $courseid.'&mode=selftask', 'method' => 'post', 'class' => 'selftaskform'));
        $output .= html_writer::start_tag('fieldset', array());
        $output .= html_writer::start_tag('legend', array());
        $output .= get_string('selfitemincluded', 'block_evalcomix');
        $output .= html_writer::end_tag('legend');

        $numactivities = 0;
        $output .= html_writer::start_tag('table');
        foreach ($elements as $key => $element) {
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::start_tag('td', array());
            $checked = '0';
            $atrib = array('type' => 'radio', 'name' => 'task', 'value' => $key,
            'onclick' => "doWork('users', '". $CFG->wwwroot."/blocks/evalcomix/reports/userajax.php?
            id=".$courseid."&tid=".$key."', 'one=1');var el=document.getElementById('submit');el.disabled=false");

            $output .= html_writer::empty_tag('input', $atrib);
            $output .= html_writer::end_tag('td');
            $output .= html_writer::start_tag('td', array());

            $output .= html_writer::start_tag('label', array('for' => $key));
            $output .= $element['object']->get_name();
            $output .= html_writer::end_tag('label');
            $output .= html_writer::end_tag('td');
            $output .= html_writer::end_tag('tr');

            ++$numactivities;
        }

        $output .= html_writer::end_tag('table');
        $output .= html_writer::end_tag('fieldset');

        if (empty($elements)) {
            $output .= html_writer::start_tag('div', array('class' => 'font-italic'));
            $output .= get_string('notaskconfigured', 'block_evalcomix').': ' .
            get_string($modality.'mod', 'block_evalcomix');
            $output .= html_writer::end_tag('div');
        } else {

            $output .= html_writer::start_tag('fieldset', array());
            $output .= html_writer::start_tag('legend', array('class' => 'font-weight-bold;text-left'));
            $output .= get_string('studendtincluded', 'block_evalcomix');
            $output .= html_writer::end_tag('legend');
            $output .= html_writer::start_tag('div', array('id' => 'users'));
        }
        $output .= html_writer::end_tag('div');
        $output .= html_writer::end_tag('fieldset');

        $output .= html_writer::start_tag('fieldset', array());
        $output .= html_writer::start_tag('legend', array('class' => 'font-weight-bold;text-left'));
        $output .= get_string('format', 'block_evalcomix');
        $output .= html_writer::end_tag('legend');

        $first = true;
        foreach ($this->validformats as $format) {
            $atrib = array('type' => 'radio', 'name' => 'format', 'value' => $format);
            if ($first == true) {
                $atrib = array('type' => 'radio', 'name' => 'format', 'checked' => 'checked', 'value' => $format);
                $first = false;
            }
            $output .= html_writer::empty_tag('input', $atrib);
            $output .= html_writer::start_tag('label', array('for' => 'format'));
            $output .= get_string($format, 'block_evalcomix');
            $output .= html_writer::end_tag('label');
        }
        $output .= html_writer::end_tag('fieldset');

        $output .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'na', 'value' => $numactivities));
        $output .= html_writer::empty_tag('input', array('type' => 'submit', 'id' => 'submit',
        'disabled' => 'true', 'value' => get_string('export', 'block_evalcomix')));

        $output .= html_writer::end_tag('form');
        $output .= html_writer::end_tag('center');

        return $output;
    }

    /**
     * print teachertask form
     * @param array $params
     * @return string $output with HTML code
     */
    public function view_form_teachertask($params) {
        global $CFG;
        require_once($CFG->dirroot . '/blocks/evalcomix/assessment/lib.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes.php');

        $courseid = $params['courseid'];
        $context = $params['context'];
        $params['modality'] = 'teacher';

        $elements = get_elements_course($params);

        echo '
        <script type="text/javascript" src="'.$CFG->wwwroot.'/blocks/evalcomix/reports/javascript/select_all_in.js">
        </script>
        <script type="text/javascript" src="'.$CFG->wwwroot.'/blocks/evalcomix/ajax.js"></script>
        ';
        $output = '';
        $output .= html_writer::start_tag('center');

        $output .= $this->logoheader();
        $output .= html_writer::start_tag('div');
        $output .= html_writer::empty_tag('input', array('type' => 'button',
        'class' => 'text-secondary', 'value' => get_string('assesssection', 'block_evalcomix'),
        'onclick' => "location.href='". $CFG->wwwroot ."/blocks/evalcomix/assessment/index.php?id=".$courseid ."'"));
        $output .= html_writer::end_tag('div');
        $output .= html_writer::start_tag('h1');
        $output .= get_string('selftask', 'block_evalcomix');
        $output .= html_writer::end_tag('h1');

        $output .= html_writer::start_tag('form', array('action' => 'download_preview.php?
        id='.$courseid.'&mode=teachertask', 'method' => 'post', 'class' => 'teachertaskform'));
        $output .= html_writer::start_tag('fieldset', array());
        $output .= html_writer::start_tag('legend', array());
        $output .= get_string('teacheritemincluded', 'block_evalcomix');
        $output .= html_writer::end_tag('legend');

        $output .= html_writer::start_tag('table');
        $numactivities = 0;
        foreach ($elements as $key => $element) {
            $output .= html_writer::start_tag('tr');
            $output .= html_writer::start_tag('td', array());
            $checked = '0';

            $atrib = array('type' => 'radio', 'name' => 'task', 'value' => $key,
            'onclick' => "doWork('assessors', '". $CFG->wwwroot."/blocks/evalcomix/reports/assessorajax.php?id=".
            $courseid."&mode=teacher&tid=".$key."', 'one=1');document.getElementById('users').innerHTML='';
            document.getElementById('submit').disabled=true");

            $output .= html_writer::empty_tag('input', $atrib);
            $output .= html_writer::end_tag('td');
            $output .= html_writer::start_tag('td', array());
            $output .= html_writer::start_tag('label', array('for' => $key));
            $output .= $element['object']->get_name();
            $output .= html_writer::end_tag('label');
            $output .= html_writer::end_tag('td');
            $output .= html_writer::end_tag('tr');
            $numactivities++;
        }

        $output .= html_writer::end_tag('table');
        $output .= html_writer::end_tag('fieldset');

        if (empty($elements)) {
            $output .= html_writer::start_tag('div', array());
            $output .= get_string('notaskconfigured', 'block_evalcomix').': ' .
            get_string($modality.'mod', 'block_evalcomix');
            $output .= html_writer::end_tag('div');
        } else {
            $output .= html_writer::start_tag('fieldset', array());
            $output .= html_writer::start_tag('legend', array());
            $output .= get_string('teacherincluded', 'block_evalcomix');
            $output .= html_writer::end_tag('legend');
            $output .= html_writer::start_tag('div', array('id' => 'assessors'));
            $output .= html_writer::end_tag('div');
            $output .= html_writer::end_tag('fieldset');

            $output .= html_writer::start_tag('fieldset', array());
            $output .= html_writer::start_tag('legend', array());
            $output .= get_string('studendtincluded', 'block_evalcomix');
            $output .= html_writer::end_tag('legend');
            $output .= html_writer::start_tag('div', array('id' => 'users'));
        }
        $output .= html_writer::end_tag('div');
        $output .= html_writer::end_tag('fieldset');

        $output .= html_writer::start_tag('fieldset', array());
        $output .= html_writer::start_tag('legend', array());
        $output .= get_string('format', 'block_evalcomix');
        $output .= html_writer::end_tag('legend');

        $first = true;
        foreach ($this->validformats as $format) {
            $atrib = array('type' => 'radio', 'name' => 'format', 'value' => $format);
            if ($first == true) {
                $atrib = array('type' => 'radio', 'name' => 'format', 'checked' => 'checked', 'value' => $format);
                $first = false;
            }
            $output .= html_writer::empty_tag('input', $atrib);
            $output .= html_writer::start_tag('label', array('for' => 'format'));
            $output .= get_string($format, 'block_evalcomix');
            $output .= html_writer::end_tag('label');
        }
        $output .= html_writer::end_tag('fieldset');

        $output .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'na', 'value' => $numactivities));
        $output .= html_writer::empty_tag('input', array('type' => 'submit', 'id' => 'submit',
        'disabled' => 'true', 'value' => get_string('export', 'block_evalcomix')));

        $output .= html_writer::end_tag('form');
        $output .= html_writer::end_tag('center');

        return $output;
    }


    public function logoheader() {
        global $CFG;
        include_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
        $output = html_writer::start_tag('center');
        $output .= html_writer::start_tag('div');
        $output .= html_writer::empty_tag('img', array('src' => $CFG->wwwroot . BLOCK_EVALCOMIX_EVXLOGOROOT,
        'width' => '230', 'alt' => 'EvalCOMIX'));
        $output .= html_writer::end_tag('div');
        $output .= html_writer::end_tag('center');

        return $output;
    }
}
