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
 * @author     Daniel Cabeza Sánchez <info@ansaner.net>
 */

class block_evalcomix_competency_renderer extends plugin_renderer_base {
    public function display_main_page($courseid, $datas, $option = 'competency', $sort = '', $dir = '', $search = '') {
        global $CFG;
        $output = '';

        require_once($CFG->dirroot . '/blocks/evalcomix/renderer.php');
        $output .= block_evalcomix_renderer::display_main_menu($courseid, 'competency');

        $output .= '
        <div class="row">
            <div class="col-md-9">
                <h3 class="mb-5">'.get_string('compandout', 'block_evalcomix').'</h3>
            </div>
            <div class="col-md-3 text-right">
                <input type="text" placeholder="Buscar" id="besearch" autofocus value="'.$search.
                '" onkeyup="ajax(\'loadsearch.php?id='. $courseid. '&o='.$option.'&search=\'+this.value, \'#changetable\')">
            </div>
        </div>
        ';

        $active1 = '';
        $active2 = '';
        $active3 = '';
        switch ($option) {
            case 'outcome':
                $active1 = 'active';
            break;
            case 'competency':
                $active2 = 'active';
            break;
            case 'type':
                $active3 = 'active';
            break;
            default:
                $active1 = 'active';
        }

        $output .= '
        <div class="text-right">
            <button type="button" onclick="location.href=\''.$CFG->wwwroot.'/blocks/evalcomix/competency/index.php?id='.$courseid.
            '&o='.$option.'&e=1\'">'.get_string('export', 'block_evalcomix').'</button>
        ';
        $coursecontext = context_course::instance($courseid);
        if (has_capability('moodle/block:edit', $coursecontext)) {
            $output .= '
            <button type="button" onclick="location.href=\''.$CFG->wwwroot.'/blocks/evalcomix/competency/import.php?id='.
            $courseid.'&o=import\'">'.get_string('import', 'block_evalcomix').'</button>
            ';
        }
        $output .= '
        </div>
        ';

        if ($option !== 'import') {
            $output .= '
            <div class="mb-3 border-bottom">
                <ul class="nav nav-pills nav-fill">
                    <li class="nav-item">
                        <a class="nav-link '.$active1.'" href="#" onclick="ajax(\'loaddata.php?id='.$courseid.
                        '&o=outcome\', \'#change\')">'. get_string('outcomes', 'block_evalcomix').'</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link '.$active2.'" href="#" onclick="ajax(\'loaddata.php?id='.$courseid.'\', \'#change\')">'.
                        get_string('competencies', 'block_evalcomix').'</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link '.$active3.'" href="#" onclick="ajax(\'loaddata.php?id='.$courseid.
                        '&o=type\', \'#change\')">'. get_string('comptypes', 'block_evalcomix').'</a>
                    </li>
                </ul>
            </div>
            ';
        }

        switch ($option) {
            case 'competency':
                $output .= $this->display_competencies_table($courseid, $datas, $sort, $dir, $search);
            break;
            case 'outcome':
                $output .= $this->display_outcomes_table($courseid, $datas, $sort, $dir, $search);
            break;
            case 'type':
                $output .= $this->display_competencytypes_table($courseid, $datas, $sort, $dir, $search);
            break;
            case 'import':
                $output .= $this->display_import($courseid);
            break;
            default:
                $output .= $this->display_competencies_table($courseid, $datas, $sort, $dir, $search);
        }

        return $output;
    }

    public function display_competencies_table($courseid, $datas, $sort = '', $dir = '', $search = '') {
        global $CFG;
        $output = '';

        $paramsbase = array('id' => $courseid, 'o' => 'competency');
        if (!empty($search)) {
            $paramsbase['search'] = $search;
        }

        $baseurl = new moodle_url($CFG->wwwroot . '/blocks/evalcomix/competency/index.php', $paramsbase);
        if (!empty($sort) && !empty($dir)) {
            uasort($datas, 'self::cmp_'.$sort.'_datas_'.strtolower($dir));
        }
        // These columns are always shown in the users list.
        $columns = array(
            'idnumber' => get_string('compidnumber', 'block_evalcomix'),
            'shortname' => get_string('compshortname', 'block_evalcomix'),
            'description' => get_string('compdescription', 'block_evalcomix'),
            'type' => get_string('comptype', 'block_evalcomix'));

        $requiredcolumns = array('idnumber', 'shortname', 'type');
        $processedcolumns = $this->get_tableheader($columns, $requiredcolumns, $baseurl, $sort, $dir);

        $output .= '<div id="changetable"><span class="font-italic">'.count($datas) . ' ' .
        get_string('competencies', 'block_evalcomix') . '</span>
        <table class="generaltable">
            <thead>
                <tr>
        ';

        foreach ($processedcolumns as $column) {
            $output .= '<th>'.$column.'</th>';
        }

        $output .= '
                    <th class="text-right">
        ';
        $coursecontext = context_course::instance($courseid);
        if (has_capability('moodle/block:edit', $coursecontext)) {
            $output .= '
                    <button type="button" onclick="location.href=\''.$CFG->wwwroot.
                    '/blocks/evalcomix/competency/edit.php?id='.$courseid.'&o=competency\'">'.
                    get_string('newcomp', 'block_evalcomix').'</button></th>
            ';
        }

        $output .= '
                </tr>
            </thead>

            <tbody>
        ';

        foreach ($datas as $data) {
            $output .= '
                <tr>
                    <td>'. $data->idnumber .'</td>
                    <td>'. $data->shortname .'</td>
                    <td>'. $data->description .'</td>
                    <td>'. $data->typename .'</td>
            ';

            if (has_capability('moodle/block:edit', $coursecontext)) {
                $output .= '
                    <td class="text-right">
                        <input type="image" src="'. $CFG->wwwroot.'/blocks/evalcomix/images/edit.png" title="'.
                        get_string('open', 'block_evalcomix') .'" alt="'. get_string('open', 'block_evalcomix') .'" width="20"
                        onclick="location.href=\''. $CFG->wwwroot.'/blocks/evalcomix/competency/edit.php?id='.$courseid.
                        '&o=competency&iid=' .$data->id.'\'">
                        <input type="image"src="'. $CFG->wwwroot.'/blocks/evalcomix/images/delete.png" title="'.
                        get_string('delete', 'block_evalcomix').'" alt="'. get_string('delete', 'block_evalcomix').'" width="20"
                        value="" onclick="if (confirm(\''.get_string('confirmdeletetool', 'block_evalcomix').'\'))
                            location.href=\''. $CFG->wwwroot.'/blocks/evalcomix/competency/edit.php?id='.$courseid.
                            '&o=competency&del=1&iid=' .$data->id.'\';">
                    </td>
                ';
            }

            $output .= '
                </tr>
            ';
        }

        $output .= '
            </tbody>
        </table>
        </div>';

        return $output;
    }

    public function display_outcomes_table($courseid, $datas, $sort = '', $dir = '', $search = '') {
        global $CFG;
        $output = '';

        $paramsbase = array('id' => $courseid, 'o' => 'outcome');
        if (!empty($search)) {
            $paramsbase['search'] = $search;
        }

        $baseurl = new moodle_url($CFG->wwwroot . '/blocks/evalcomix/competency/index.php', $paramsbase);
        if (!empty($sort) && !empty($dir)) {
            uasort($datas, 'self::cmp_'.$sort.'_datas_'.strtolower($dir));
        }
        // These columns are always shown in the users list.
        $columns = array(
            'idnumber' => get_string('compidnumber', 'block_evalcomix'),
            'shortname' => get_string('compshortname', 'block_evalcomix'),
            'description' => get_string('compdescription', 'block_evalcomix')
        );

        $requiredcolumns = array('idnumber', 'shortname');
        $processedcolumns = $this->get_tableheader($columns, $requiredcolumns, $baseurl, $sort, $dir);

        $output .= '<div id="changetable"><span class="font-italic">'.count($datas) . ' ' .
        get_string('outcomes', 'block_evalcomix') . '</span>
        <table class="generaltable">
            <thead>
                <tr>
        ';

        foreach ($processedcolumns as $column) {
            $output .= '<th>'.$column.'</th>';
        }

        $output .= '
                    <th class="text-right">
        ';
        $coursecontext = context_course::instance($courseid);
        if (has_capability('moodle/block:edit', $coursecontext)) {
            $output .= '
                    <button type="button" onclick="location.href=\''.$CFG->wwwroot.
                    '/blocks/evalcomix/competency/edit.php?id='.$courseid.'&o=outcome\'">'.
                    get_string('newoutcome', 'block_evalcomix').'</button></th>
            ';
        }
        $output .= '
                </tr>
            </thead>

            <tbody>
        ';

        foreach ($datas as $data) {
            $output .= '
                <tr>
                    <td>'. $data->idnumber .'</td>
                    <td>'. $data->shortname .'</td>
                    <td>'. $data->description .'</td>
                    <td>'. $data->typeid .'</td>
            ';

            if (has_capability('moodle/block:edit', $coursecontext)) {
                $output .= '
                    <td class="text-right">
                        <input type="image" src="'. $CFG->wwwroot.'/blocks/evalcomix/images/edit.png" title="'.
                        get_string('open', 'block_evalcomix') .'" alt="'. get_string('open', 'block_evalcomix') .'" width="20"
                        onclick="location.href=\''. $CFG->wwwroot.'/blocks/evalcomix/competency/edit.php?id='.$courseid.
                        '&o=outcome&iid=' .$data->id.'\'">
                        <input type="image"src="'. $CFG->wwwroot.'/blocks/evalcomix/images/delete.png" title="'.
                        get_string('delete', 'block_evalcomix').'" alt="'. get_string('delete', 'block_evalcomix').'" width="20"
                        value="" onclick="if (confirm(\''.get_string('confirmdeletetool', 'block_evalcomix').'\'))
                            location.href=\''. $CFG->wwwroot.'/blocks/evalcomix/competency/edit.php?id='.$courseid.
                            '&o=outcome&del=1&iid=' .$data->id.'\';">
                    </td>';
            }

            $output .= '
                </tr>
            ';
        }

        $output .= '
            </tbody>
        </table>
        </div>';

        return $output;
    }

    public function display_competencytypes_table($courseid, $datas, $sort = '', $dir = '', $search = '') {
        global $CFG;
        $output = '';

        $paramsbase = array('id' => $courseid, 'o' => 'type');
        if (!empty($search)) {
            $paramsbase['search'] = $search;
        }

        $baseurl = new moodle_url($CFG->wwwroot . '/blocks/evalcomix/competency/index.php', $paramsbase);
        if (!empty($sort) && !empty($dir)) {
            uasort($datas, 'self::cmp_'.$sort.'_datas_'.strtolower($dir));
        }
        // These columns are always shown in the users list.
        $columns = array(
            'shortname' => get_string('compshortname', 'block_evalcomix'),
            'description' => get_string('compdescription', 'block_evalcomix')
        );

        $requiredcolumns = array('shortname');
        $processedcolumns = $this->get_tableheader($columns, $requiredcolumns, $baseurl, $sort, $dir);

        $output .= '<div id="changetable"><span class="font-italic">'.count($datas) . ' ' .
        get_string('comptypes', 'block_evalcomix') . '</span>
        <table class="generaltable">
            <thead>
                <tr>
                <tr>
        ';

        foreach ($processedcolumns as $column) {
            $output .= '<th>'.$column.'</th>';
        }

        $output .= '
                    <th class="text-right">
        ';
        $coursecontext = context_course::instance($courseid);
        if (has_capability('moodle/block:edit', $coursecontext)) {
            $output .= '
                    <button type="button" onclick="location.href=\''.$CFG->wwwroot.
                    '/blocks/evalcomix/competency/edit.php?id='.$courseid.'&o=type\'">'.
                    get_string('newcomptype', 'block_evalcomix').'</button></th>
            ';
        }
        $output .= '
                </tr>
            </thead>

            <tbody>
        ';

        foreach ($datas as $data) {
            $output .= '
                <tr>
                    <td>'.$data->shortname.'</td>
                    <td>'.$data->description.'</td>
            ';

            if (has_capability('moodle/block:edit', $coursecontext)) {
                $output .= '
                    <td class="text-right">
                        <input type="image" src="'. $CFG->wwwroot.'/blocks/evalcomix/images/edit.png" title="'.
                        get_string('open', 'block_evalcomix') .'" alt="'. get_string('open', 'block_evalcomix') .'" width="20"
                        onclick="location.href=\''. $CFG->wwwroot.'/blocks/evalcomix/competency/edit.php?id='.
                        $courseid.'&o=type&iid=' .$data->id.'\'">
                        <input type="image"src="'. $CFG->wwwroot.'/blocks/evalcomix/images/delete.png" title="'.
                        get_string('delete', 'block_evalcomix').'" alt="'. get_string('delete', 'block_evalcomix').'" width="20"
                        value="" onclick="if (confirm(\''.get_string('confirmdeletetool', 'block_evalcomix').'\'))
                            location.href=\''. $CFG->wwwroot.'/blocks/evalcomix/competency/edit.php?id='.$courseid.'&o=type&iid='
                        .$data->id.'&del=1\';">
                    </td>';
            }

            $output .= '
                </tr>
            ';
        }

        $output .= '
            </tbody>
        </table>
        </div>';

        return $output;
    }

    public function display_import_result($competencies, $outcomes, $types, $ignored, $errors, $returnurl = '') {
        $output = '';

        $output .= '
        <h3>'.get_string('importresult', 'block_evalcomix').'</h3>
        <div class="border p-2">
            <p>'.get_string('competencies', 'block_evalcomix').': '.$competencies.'</p>
            <p>'.get_string('outcomes', 'block_evalcomix').': '.$outcomes.'</p>
            <p>'.get_string('comptypes', 'block_evalcomix').': '.$types.'</p>
            <p>'.get_string('ignored', 'block_evalcomix').': '.$ignored.'</p>
            <p>'.get_string('errors', 'block_evalcomix').': '.$errors.'</p>
        </div>
        ';
        if (!empty($returnurl)) {
            $output .= '
            <div class="text-center">
                <button type="button" onclick="location.href=\''.$returnurl.'&continue=1\'">'.get_string('continue').'</button>
            </div>
            ';
        }

        return $output;
    }

    public function display_report_page($courseid, $competencydatas, $outcomedatas, $groups, $groupselected, $students,
            $studentselected = 0) {
        global $CFG;
        $output = '';

        require_once($CFG->dirroot . '/blocks/evalcomix/renderer.php');
        $output .= block_evalcomix_renderer::display_main_menu($courseid, 'report');

        $output .= '
        <h3 class="mb-5">'.get_string('compreport', 'block_evalcomix').'</h3>
        ';

        $checked0 = '';
        $checked1 = '';
        $checked2 = '';
        $disabled1 = 'disabled';
        $disabled2 = 'disabled';
        if (is_numeric($studentselected) && $studentselected > 0) {
            $checked0 = '';
            $checked1 = 'checked';
            $checked2 = '';
            $disabled1 = '';
            $disabled2 = 'disabled';
        } else if (is_numeric($groupselected) && $groupselected > 0) {
            $checked0 = '';
            $checked1 = '';
            $checked2 = 'checked';
            $disabled1 = 'disabled';
            $disabled2 = '';
        }

        $context = context_course::instance($courseid);
        if (has_capability('moodle/grade:viewhidden', $context)) {
            $output .= '
            <div class="form-row text-center">
                <div class="form-group col-md-3">
                    <input class="form-check-input" type="radio" name="compreportstudent" id="beall" value="0" '.$checked0.'
                        onclick="
                        var e = document.getElementById(\'beselectstudent\');
                        var f = document.getElementById(\'beselectgroup\');
                        e.getElementsByTagName(\'option\')[0].selected = \'selected\';
                        f.getElementsByTagName(\'option\')[0].selected = \'selected\';
                        e.disabled = true;
                        f.disabled = true;
                        $(\'#loading\').html(\'<div><center><img src=../images/loader-4.gif ><br/></center></div>\');
                        ajax(\''.$CFG->wwwroot.'/blocks/evalcomix/competency/loadreport.php?id='.$courseid.
                            '\', \'#bechange\', \'get\', {}, `$(\'#loading\').html(\'<br>\');`);
                        ">
                    <label class="form-check-label" for="beall">
                        '.get_string('allstudens', 'block_evalcomix').'
                    </label>
                </div>
                <div class="form-group col-md-3">
                    <input class="form-check-input" type="radio" name="compreportstudent" id="beone" value="1" '.$checked1.'
                        onclick="
                        var e = document.getElementById(\'beselectstudent\');
                        var f = document.getElementById(\'beselectgroup\');
                        e.disabled = false;
                        f.disabled = true;
                        e.getElementsByTagName(\'option\')[0].selected = \'selected\';
                        f.getElementsByTagName(\'option\')[0].selected = \'selected\';
                        ajax(\''.$CFG->wwwroot.'/blocks/evalcomix/competency/loadreport.php?id='.$courseid.
                            '&u=-1&g=-1\', \'#bechange\', \'get\', {}, `$(\'#loading\').html(\'<br>\');`);
                        ">
                    <label class="form-check-label" for="beone">
                        '.get_string('onestudent', 'block_evalcomix').'
                    </label>
                    <br>
                    <select id="beselectstudent" class="" '.$disabled1.' onchange="
                        $(\'#loading\').html(\'<div><center><img src=../images/loader-4.gif ><br/></center></div>\');
                        ajax(\''.$CFG->wwwroot.'/blocks/evalcomix/competency/loadreport.php?id='.$courseid.
                            '&u=\'+this.value, \'#bechange\', \'get\', {}, `$(\'#loading\').html(\'<br>\');`);
                    ">
                        <option value="0">'.get_string('selectstudent', 'block_evalcomix').'</option>
            ';

            foreach ($students as $student) {
                $selected = ($checked1 === 'checked' && $student->id == $studentselected) ? 'selected' : '';
                $output .= '<option value="'.$student->id.'" '.$selected.'>'.fullname($student).'</option>';
            }

            $output .= '
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <input class="form-check-input" type="radio" name="compreportstudent" id="begroupone" value="1" '.$checked2.'
                        onclick="
                        var e = document.getElementById(\'beselectstudent\');
                        var f = document.getElementById(\'beselectgroup\');
                        e.disabled = true;
                        f.disabled = false;
                        e.getElementsByTagName(\'option\')[0].selected = \'selected\';
                        f.getElementsByTagName(\'option\')[0].selected = \'selected\';
                        ajax(\''.$CFG->wwwroot.'/blocks/evalcomix/competency/loadreport.php?id='.$courseid.
                            '&u=-1&g=-1\', \'#bechange\', \'get\', {}, `$(\'#loading\').html(\'<br>\');`);
                        ">
                    <label class="form-check-label" for="begroupone">
                        '.get_string('onegroup', 'block_evalcomix').'
                    </label>
                    <br>
                    <select id="beselectgroup" class="" '.$disabled2.' onchange="
                        $(\'#loading\').html(\'<div><center><img src=../images/loader-4.gif ><br/></center></div>\');
                        ajax(\''.$CFG->wwwroot.'/blocks/evalcomix/competency/loadreport.php?id='.$courseid.
                            '&g=\'+this.value, \'#bechange\', \'get\', {}, `$(\'#loading\').html(\'<br>\');`);
                    ">
                        <option value="0">'.get_string('selectgroup', 'block_evalcomix').'</option>
            ';

            foreach ($groups as $group) {
                $selected = ($checked2 === 'checked' && $group->id == $groupselected) ? 'selected' : '';
                $output .= '<option value="'.$group->id.'" '.$selected.'>'.$group->name.'</option>';
            }

            $output .= '
                    </select>
                </div>
            </div>
            ';
        }

        $output .= '<div id="loading"><br></div>';
        $output .= '<div id="bechange">';
        $output .= $this->display_reports($courseid, $competencydatas, $outcomedatas, $groups, $groupselected, $students,
            $studentselected);
        $output .= '</div>';

        return $output;
    }

    public function display_reports($courseid, $competencydatas, $outcomedatas, $groups, $groupselected, $students,
            $studentselected = 0) {
        global $CFG;
        $competencyxdatas = (isset($competencydatas->xdatas)) ? array_reverse($competencydatas->xdatas) : array();
        $competencyydatas = (isset($competencydatas->ydatas)) ? array_reverse($competencydatas->ydatas) : array();
        $outcomexdatas = (isset($outcomedatas->xdatas)) ? array_reverse($outcomedatas->xdatas) : array();
        $outcomeydatas = (isset($outcomedatas->ydatas)) ? array_reverse($outcomedatas->ydatas) : array();
        $competencytitle = (isset($competencydatas->gradebytask)) ? $competencydatas->gradebytask : array();
        $outcometitle = (isset($outcomedatas->gradebytask)) ? $outcomedatas->gradebytask : array();

        $output = '';
        $context = context_course::instance($courseid);
        if (has_capability('moodle/grade:viewhidden', $context)) {
            $exportdisabled = (!empty($outcomexdatas) || !empty($competencyxdatas)) ? '' : 'disabled';
            $output .= '<div class="form-group text-right">
                        <button type="button" onclick="location.href=\''.
                        $CFG->wwwroot.'/blocks/evalcomix/competency/report.php?id='.
                        $courseid.'&g='.$groupselected.'&u='.$studentselected.'&e=1\'" '.$exportdisabled.'>'.
                        get_string('export', 'block_evalcomix').'</button>
                    </div>';
        }
        $output .= '<h4>'.get_string('outcomes', 'block_evalcomix').'</h4>';
        $output .= $this->display_report($outcomexdatas, $outcomeydatas, 'orange', 'bediv2', array('title' => $outcometitle));
        $output .= '<h4 class="mt-5">'.get_string('competencies', 'block_evalcomix').'</h4>';
        $output .= $this->display_report($competencyxdatas, $competencyydatas, 'blue', 'bediv1',
            array('title' => $competencytitle));

        return $output;
    }

    public function display_report($xdatas, $ydatas, $color = 'blue', $div = 'myDiv', $extra = array()) {
        global $CFG;
        $output = '';

        $output = $this->display_report_bootstrap($xdatas, $ydatas, $color, $div, $extra);

        return $output;
    }

    public function display_report_bootstrap($xdatas, $ydatas, $color = 'blue', $div = 'myDiv', $extra = array()) {
        global $CFG, $COURSE;
        $output = '';
        $colorgradient = array(0 => '#41AED9', 1 => '#3ba4cd', 2 => '#3498bf', 3 => '#2e8fb4', 4 => '#2782a6', 5 => '#207698',
        6 => '#1a6c8c', 7 => '#13617f', 8 => '#0d5673', 9 => '#064a65', 10 => '#004059');
        if ($color == 'orange') {
            $colorgradient = array(0 => '#FFA50D', 1 => '#fe9a0d', 2 => '#fd900d', 3 => '#fc850d', 4 => '#fb7b0d', 5 => '#fa710c',
            6 => '#f9660c', 7 => '#f85c0c', 8 => '#f7510c', 9 => '#f6470c', 10 => '#F53D0C');
        }
        $modinfo = get_fast_modinfo($COURSE->id);
        $gradebytask = (isset($extra['title'])) ? $extra['title'] : '';
        $output .= '<table>';
        foreach ($xdatas as $label => $grade) {
            $level = floor($grade / 10);
            $title = '';
            if (!empty($gradebytask[$label])) {
                foreach ($gradebytask[$label] as $cmid => $value) {
                    $title .= $modinfo->cms[$cmid]->name . ' ('. $value.'%)<br>';
                }
            }
            $output .= '
            <div class="row">
                <div class="col-md-3 col-sm-3 label-progress d-flex flex-column justify-content-center" >'.$label.'</div>
                <div class="col-md-8 col-sm-8 progress mt-2 px-0" data-toggle="tooltip" data-html="true" data-placement="top"
                title="'.$title.'">
            ';
            if ($grade > 0) {
                $output .= '
                <div class="progress-bar" role="progressbar" aria-valuenow="'.$grade.'"
                aria-valuemin="0" aria-valuemax="100" style="width:'.$grade.'%;background-image: linear-gradient(to right, '.
                $colorgradient[0].', '.$colorgradient[$level].')">
                '.$grade.'%
                </div>';
            } else {
                $output .= '<div class="d-flex flex-column justify-content-center">0%</div>';
            }
            $output .= '
            </div>
            </div>
            ';
        }
        $output .= '</table>';
        return $output;
    }

    public function display_report_plotly($xdatas, $ydatas, $div = 'myDiv') {
        global $CFG;
        $output = '';

        $output .= '
            <div id="'.$div.'"><!-- Plotly chart will be drawn inside this DIV --></div>
        ';

        $output .= '
            <script>
            var data = [{
              type: "bar",
              x: ['.implode(',', $xdatas).'],
              y: ["'.implode('","', $ydatas).'"],
              orientation: "h"
            }];

            var layout = {
                xaxis: {range: [0, 100]},
                margin: {
                    t:0,
                    l: 200,
                    pad: 10,
                },
            };

            Plotly.newPlot("'.$div.'", data, layout);
            </script>
        ';

        return $output;
    }

    public function get_tableheader($columns, $sortcolumns, $baseurl, $sort, $dir) {
        global $CFG;
        $result = array();

        foreach ($columns as $key => $column) {
            $cname = $key;
            $columndir = 'ASC';
            $columnicon = '';
            if (in_array($key, $sortcolumns)) {
                if ($sort == $key) {
                    $cname = $sort;
                    $columndir = $dir == "ASC" ? "DESC" : "ASC";
                    $columnicon = ($dir == "ASC") ? "sort_asc" : "sort_desc";
                    $columnicon = $this->output->pix_icon('t/' . $columnicon, get_string(strtolower($columndir)), 'core',
                                                        ['class' => 'iconsort']);
                }
                $result[$cname] = "<a href=\"".$baseurl."&sort=$cname&dir=$columndir\">".get_string('comp'.$key,
                'block_evalcomix')."</a>$columnicon";
            } else {
                $result[$cname] = $column;
            }
        }

        return $result;
    }

    public static function cmp_idnumber_datas_asc($a, $b) {
        return strcmp(strtolower($a->idnumber), strtolower($b->idnumber));
    }

    public static function cmp_idnumber_datas_desc($a, $b) {
        return strcmp(strtolower($b->idnumber), strtolower($a->idnumber));
    }

    public static function cmp_shortname_datas_asc($a, $b) {
        return strcmp(strtolower($a->shortname), strtolower($b->shortname));
    }

    public static function cmp_shortname_datas_desc($a, $b) {
        return strcmp(strtolower($b->shortname), strtolower($a->shortname));
    }

    public static function cmp_type_datas_asc($a, $b) {
        return strcmp(strtolower($a->typename), strtolower($b->typename));
    }

    public static function cmp_type_datas_desc($a, $b) {
        return strcmp(strtolower($b->typename), strtolower($a->typename));
    }
}
