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

defined('MOODLE_INTERNAL') || die();

require_once('configeval.php');

class block_evalcomix extends block_base {

    public function init() {
        $this->title = get_string('evalcomix', 'block_evalcomix');
    }

    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '<div class="text-center"><span style="color:#E67300;
        font-size:8pt">'.get_string('poweredby', 'block_evalcomix').'</span></div>';

        global $USER;
        global $COURSE;

        $systemcontext = context_system::instance();
        $coursecontext = context_course::instance($COURSE->id);
        $autorizado = false;

        if (is_siteadmin($USER)) {
            $autorizado = true;
        }

        if (has_capability('moodle/grade:viewhidden', $coursecontext)) {
            $autorizado = true;
        }
        global $CFG;

        $this->content->text .= "<STYLE type='text/css'>";
        $this->content->text .= " LI.eps_li {list-style-type:none; background:url('') no-repeat
        scroll 0px -893px transparent; margin:0 0 3px; padding:0 0 0 9px; }
        #block_evalcomix_ul_block {padding: 0px; margin-left: 21%;color:#00648C;
        font-weight:bold;}      </STYLE>";

        $this->content->text .= "<img src='".$CFG->wwwroot . BLOCK_EVALCOMIX_EVXLOGOROOT ."' alt='' align='absmiddle'
        width='100%'>";
        $this->content->text .= "<ul id='block_evalcomix_ul_block'><li class='eps_li'>";

        if ($autorizado == true) {
            $this->content->text .= "<li class='mb-2'><a title='Handler of competencies and outcomes'
            href='".$CFG->wwwroot ."/blocks/evalcomix/competency/index.php?id=".$COURSE->id."' name='clickcomp'
            class='p-0'>".get_string('handlerofco', 'block_evalcomix')."</a></li>";
        }

        if ($autorizado == true) {
            $this->content->text .= "<li class='mb-2'><a title='Instruments'
            href='".$CFG->wwwroot ."/blocks/evalcomix/tool/index.php?id=".$COURSE->id."' name='clickinst'
            class='p-0' >".get_string('instruments', 'block_evalcomix')."</a></li>";
        }

        $this->content->text .= "<li class='mb-2'>
        <a href='".$CFG->wwwroot ."/blocks/evalcomix/assessment/index.php?id=".$COURSE->id."' name='clickeval'
        class='p-0'>".get_string('evaluationandreports', 'block_evalcomix')."</a></li>";

        $this->content->text .= '</ul>';

        return $this->content;
    }

    // Permite configurar una instancia (editar el bloque por profesores).
    public function instance_allow_config() {
        return true;
    }

    public function has_config() {
        return true;
    }
}
