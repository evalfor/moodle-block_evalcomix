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
 * Defines the renderer class for the graphics of block_evalcomix
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>
 */

class block_evalcomix_graphic_renderer {

    /**
     * This function shows graphics
     * @param int $courseid
     * @param string $mode can be '1' for task-graphic or '2' for student-graphic
     * @return string html of a grafic
     */
    public function display_graphics($courseid, $mode = 1) {
        global $CFG;

        $taskselected = 'block_evalcomix_tab_selected';
        $studentselected = '';
        if ($mode == 2) {
            $taskselected = '';
            $studentselected = 'block_evalcomix_tab_selected';
        }

        $taskurl = new moodle_url($CFG->wwwroot . '/blocks/evalcomix/graphics/index.php', array('id' => $courseid, 'mode' => 1));
        $studenturl = new moodle_url($CFG->wwwroot . '/blocks/evalcomix/graphics/index.php', array('id' => $courseid, 'mode' => 2));

        $output = '
        <script>
            function modify_graphic(flag = 0) {
                var courseid=document.getElementById(\'courseid\');
                var mode=document.getElementById(\'mode\').value;
                var taskselect=document.getElementById(\'selectactivity\');
                var taskid = taskselect.options[taskselect.selectedIndex].value;
                var teachermod=document.getElementById(\'teachermod\');
                var peermod=document.getElementById(\'peermod\');
                var studentmod=document.getElementById(\'studentmod\');
                var groupmod=document.getElementById(\'groupmod\');
                var classmod=document.getElementById(\'classmod\');
                var studentselect=document.getElementById(\'selectstudent\');
                var studentid = studentselect.options[studentselect.selectedIndex].value;
                var groupid = document.getElementById("selectgroup");

                var ids=\'\';
                var div= document.getElementById(\'block_evalcomix_checks\');
                if (div) {
                    var checks=div.getElementsByTagName(\'input\');
                    for(i=0;i<checks.length;i++){
                        if(checks[i].type == "checkbox" && checks[i].checked == true){
                            ids = ids + checks[i].value + \'-\';
                        }
                    }
                }

                var params = "";
                // Graphic: task-student.
                if (mode == 1 && taskid && studentmod.checked && studentid) {
                    params = "requestgraphic=box&id='.$courseid.'&mode='.$mode.'&modality=student&task="+taskid+"&user="+studentid;
                }

                // Graphic: task-group.
                if (mode == 1 && taskid && groupmod.checked && groupid) {
                    params = "requestgraphic=box&id='.$courseid.'&mode='.$mode
                        .'&modality=group&task="+taskid+"&group="+groupid.value;
                    if (flag == 1) {
                        params = params + "&check=1";
                    }
                    if (ids) {
                        params = params + "&user=" + ids;
                    }
                }

                // Graphic: task-class.
                if (mode == 1 && taskid && classmod.checked) {
                    params = "requestgraphic=box&id='.$courseid.'&mode='.$mode.'&modality=class&task="+taskid;
                }

                // Graphic: student-teacher.
                if (mode == 2 &&taskid && teachermod.checked && studentid) {
                    params = "requestgraphic=bar&id='.$courseid.'&mode='.$mode.'&modality=teacher&task="+taskid+"&user="+studentid;
                }

                // Graphic: student-peer.
                if ( mode == 2 &&taskid && peermod.checked && studentid) {
                    params = "requestgraphic=bar&id='.$courseid.'&mode='.$mode.'&modality=peer&task="+taskid+"&user="+studentid;
                }

                $.ajax({
                    data: params,
                    type: "GET",
                    dataType: "json",
                    url: "'.$CFG->wwwroot.'/blocks/evalcomix/graphics/loaddata.php",
                    success: function(response){
                        if(response.status){
                            update_grafica(response.result);
                        }
                    }
                });
            }

            function update_grafica(options) {
                var type = options.type;
                var title = options.title;
                var min = options.min;
                var max = options.max;
                var points = options.datas;

                var count = points.length;
                var data = [];
                var xaxis = options.xlabels;
                var yaxis = [];
                var widthx = [];

                if (type == "bar") {
                    for (var i = 0; i < count; i++){
                        yaxis[i] = points[i].toString();
                        widthx[i] = 0.3;
                    }

                    var data = [
                        {
                            x: xaxis,
                            y: yaxis,
                            type: \'bar\',
                            width: widthx,
                        }
                    ];
                } else if (type == "box") {
                    for (var i = 0; i < count; i++){
                        data[i] = {
                            name: xaxis[i].toString(),
                            y: points[i],
                            type: \'box\',
                        };
                    }
                }
                var layout = {
                    yaxis: {range: [0, 100]},
                    title: {
                        text: title.toString(),
                    }
                };

                Plotly.newPlot(\'myDiv\', data, layout, {showSendToCloud: false});
            }

            // Function to add event listener to selectstudent
            function load_selectstudent() {
                var el = document.getElementById("selectstudent");
                if (el) {
                    el.addEventListener("change", modify_graphic, false);
                }
            }

            // Function to add event listener to classmod
            function load_classmod() {
                var el = document.getElementById("classmod");
                if (el) {
                    el.addEventListener("click", modify_graphic, false);
                }
            }

            // Function to add event listener to groupmod
            function load_groupmod() {
                var el = document.getElementById("selectgroup");
                if (el) {
                    el.addEventListener("change", modify_graphic, false);
                }
            }

            document.addEventListener("DOMContentLoaded", load_selectstudent, false);
            document.addEventListener("DOMContentLoaded", load_classmod, false);
            document.addEventListener("DOMContentLoaded", load_groupmod, false);
        </script>

        <input type="hidden" id="courseid" name="courseid" value="'.$courseid.'">
        <input type="hidden" id="mode" name="mode" value="'.$mode.'">
        <div id="changeable">
            <div>
                <span class="block_evalcomix_graphic_tab '.$taskselected.'">
                <a href="'.$taskurl.'">'.get_string('taskgraphic', 'block_evalcomix').'</a></span>
                <span class="block_evalcomix_graphic_tab '.$studentselected.'">
                <a href="'.$studenturl.'">'.get_string('studentgraphic', 'block_evalcomix').'</a></span>
            </div>
            <div class="w-100 border border-secondary">
                <table class="w-100" cellpadding="5">
                    <tr>
                        <td class="block_evalcomix_table_filter align-top">
                            <div id="block_evalcomix_filters">
                        '. $this->display_graphics_filters($courseid, $mode).'
                            </div>
                        </td>
                        <td class="block_evalcomix_table_graphic">
                            <div id="block_evalcomix_graphic">
                                <div id="myDiv">
                                </div>
                                <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
                                <script>
                                    var y0=[0,10,20,5,4],y1=[10,20,30,1],y3=[13]
                                    var trace1 = {
                                          y: y0,
                                          type: "box"
                                    };
                                    var trace2 = {
                                          y: y1,
                                          type: "box"
                                    };
                                    var trace3 = {
                                          y: y3,
                                          type: "box"
                                    };
                                    var data = [trace1, trace2, trace3];
                                    Plotly.newPlot("myDiv", data, {}, {});
                                </script>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="clearfix"></div>
        </div>
        ';

        return $output;
    }

    /**
     * This function shows filters for graphics
     * @param int $courseid
     * @param string $mode can be '1' for task graphic or '2' for student graphic
     * @return string html of filters
     */
    public function display_graphics_filters($courseid, $mode) {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
        $tasks = block_evalcomix_tasks::get_moodle_course_tasks($courseid);
        $output = '
            <div class="block_evalcomix_filter_block">
                <label for="selectactivity">'.get_string('activity', 'block_evalcomix').'</label>
                <div>
                    <select id="selectactivity" name="selectactivity"
                    onchange="var e=document.getElementById(\'block_evalcomix_filter_student_modality\');
                        if (e) {
                            e.style.display = \'inline\';
                        }

                        var ts=document.getElementById(\'block_evalcomix_filter_task_students\');
                        if (ts) {
                            ts.style.display = \'none\';
                        }

                        var ss=document.getElementById(\'block_evalcomix_filter_student_select\');
                        if (ss) {
                            ss.style.display = \'none\';
                        }

                        var d=document.getElementById(\'block_evalcomix_filter_task_group\');
                        if (d) {
                            d.style.display = \'none\';
                        }

                        var elements = document.getElementById(\'selectgroup\');
                        if (elements) {
                            var eoptions = elements.options;
                            for(var i = 0; i < eoptions.length; i++){
                                eoptions[i].selected = false;
                            }
                        }

                        var c=document.getElementById(\'block_evalcomix_checks\');
                        if (c) {
                            c.style.display = \'none\';
                        }

                        var f=document.getElementById(\'block_evalcomix_filter_task_modality\');
                        if (f) {
                            f.style.display = \'inline\';
                        }

                        var checks=document.getElementsByTagName(\'input\');
                        for(i=0;i<checks.length;i++){
                            if(checks[i].checked == true){
                                checks[i].checked = false;
                            }
                        }
                    ">
                        <option value="0">'.get_string('selectactivity', 'block_evalcomix').'</option>
        ';
        foreach ($tasks as $task) {
            $output .= '<option value="'.$task['id'].'">'.$task['nombre'].'</option>';
        }
        $output .= '
                    </select>
                </div>
            </div>
        ';

        if ($mode == 1) {
            // For task graphic.
            $output .= $this->display_graphics_filters_task($courseid);
        } else if ($mode == 2) {
            // For student graphic.
            $output .= $this->display_graphics_filters_student($courseid);
        }

        return $output;
    }

    /**
     * This function shows filters for task graphic
     * @param int $courseid
     * @return string html of filters
     */
    public function display_graphics_filters_task($courseid) {
        global $CFG, $DB;
        $output = '';

        $mode = 1;
        $studentchecked = $groupchecked = $classchecked = '';

        $output .= '
        <div id="block_evalcomix_filter_task_modality" class="block_evalcomix_display_none">
            <div class="block_evalcomix_filter_block">
                <div>
                    <input type="radio" name="modality" id="studentmod" value="student" '.$studentchecked.'
                    onclick="var e=document.getElementById(\'block_evalcomix_filter_task_students\');
                        e.style.display = \'inline\';
                    var f=document.getElementById(\'selectactivity\');
                    var g=document.getElementById(\'block_evalcomix_filter_task_group\');
                    g.style.display = \'none\';
                    var elements = document.getElementById(\'selectgroup\').options;
                    for(var i = 0; i < elements.length; i++){
                        elements[i].selected = false;
                    }
                    var c=document.getElementById(\'block_evalcomix_checks\');
                    c.style.display = \'none\';
                    doWork(\'selectstudent\', \''.$CFG->wwwroot.'/blocks/evalcomix/graphics/loaddata.php\',
                        \'requestgraphic=getstudents&id='.$courseid.'&mode='.$mode.
                        '&task=\'+f.options[f.selectedIndex].value+\'&modality=student\');"><label for="studentmod">'.
                        get_string('studentmod', 'block_evalcomix').'</label>
                </div>
                <div>
                    <input type="radio" name="modality" id="groupmod" value="group" '.$groupchecked.'
                    onclick="var e=document.getElementById(\'block_evalcomix_filter_task_students\');
                    e.style.display = \'none\';
                    var f=document.getElementById(\'block_evalcomix_filter_task_group\');
                    f.style.display = \'inline\'"><label for="groupmod">'
                    .get_string('groupmod', 'block_evalcomix').'</label>
                </div>
                <div>
                    <input type="radio" name="modality" id="classmod" value="class" '.$classchecked.'
                    onclick="var e=document.getElementById(\'block_evalcomix_filter_task_students\');
                    e.style.display = \'none\';
                    var f=document.getElementById(\'block_evalcomix_filter_task_group\');
                    f.style.display = \'none\';
                    var elements = document.getElementById(\'selectgroup\').options;
                    for(var i = 0; i < elements.length; i++){
                        elements[i].selected = false;
                    }
                    var c=document.getElementById(\'block_evalcomix_checks\');
                    c.style.display = \'none\';"><label for="classmod">'
                    .get_string('classmod', 'block_evalcomix').'</label>
                </div>
            </div>
        </div>

        <div id="block_evalcomix_filter_task_students" class="block_evalcomix_display_none">
            <div class="block_evalcomix_filter_block">
                <label for="selectstudent">'.get_string('studentmod', 'block_evalcomix').'</label>
                <div>
                    <select id="selectstudent" name="selectstudent">
                        <option value="0">'.get_string('selectstudent', 'block_evalcomix').'</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="block_evalcomix_filter_task_group" class="block_evalcomix_display_none">
            <div class="block_evalcomix_filter_block">
                <label for="selectstudent">'.get_string('groupmod', 'block_evalcomix').'</label>
                <div>
                    <select id="selectgroup" name="selectgroup"
                        onchange="var f = document.getElementById(\'selectgroup\');
                        var t = document.getElementById(\'selectactivity\');
                        var taskid = t.options[t.selectedIndex].value;
                        var c=document.getElementById(\'block_evalcomix_checks\');
                        c.style.display = \'inline\';
                        var div= document.getElementById(\'block_evalcomix_checks\');
                        if (div) {
                            var checks=div.getElementsByTagName(\'input\');
                            for(i=0;i<checks.length;i++){
                                if(checks[i].type == \'checkbox\' && checks[i].checked == true){
                                    checks[i].checked = false;
                                }
                            }
                        }
                        doWork(\'block_evalcomix_checks\', \''.$CFG->wwwroot.'/blocks/evalcomix/graphics/loaddata.php\',
                                \'requestgraphic=getstudentsgroup&id='.$courseid.'&mode='.$mode.
                                '&task=\'+taskid+\'&modality=group&group=\'+f.options[f.selectedIndex].value);">
                        <option value="0">'.get_string('selectgroup', 'block_evalcomix').'</option>
        ';
        if ($groups = $DB->get_records('groups', array('courseid' => $courseid))) {
            foreach ($groups as $group) {
                $output .= '<option value="'.$group->id.'">'. $group->name . '</option>';
            }
        }
        $output .= '
                        </select>
                    </div>
                </div>
            </div>
            <div class="block_evalcomix_filter_block" >
                <div id="block_evalcomix_checks" class="block_evalcomix_display_none"></div>
            </div>
        ';

        return $output;
    }

    /**
     * This function shows filters for student graphic
     * @param int $courseid
     * @return string html of filters
     */
    public function display_graphics_filters_student($courseid) {
        global $CFG, $DB;

        $mode = 2;
        $teacherchecked = '';
        $peerchecked = '';

        $output = '
            <div id="block_evalcomix_filter_student_modality" class="block_evalcomix_display_none">
                <div class="block_evalcomix_filter_block">
                    <div>
                        <input type="radio" name="modality" id="teachermod" value="teacher" '.$teacherchecked.'
                            onclick="var e=document.getElementById(\'block_evalcomix_filter_student_select\');
                            e.style.display = \'inline\';
                            var f=document.getElementById(\'selectactivity\');
                            doWork(\'selectstudent\', \''.$CFG->wwwroot.'/blocks/evalcomix/graphics/loaddata.php\',
                            \'requestgraphic=getstudents&id='.$courseid.'&mode='.$mode.
                            '&task=\'+f.options[f.selectedIndex].value+\'&modality=teacher\');
                            "><label for="teachermod">'.
                            get_string('teachermod', 'block_evalcomix').'</label>
                    </div>
                    <div>
                        <input type="radio" name="modality" id="peermod" value="peer" '.$peerchecked.'
                        onclick="var e=document.getElementById(\'block_evalcomix_filter_student_select\');
                            e.style.display = \'inline\';
                            var f=document.getElementById(\'selectactivity\');
                            doWork(\'selectstudent\', \''.$CFG->wwwroot.'/blocks/evalcomix/graphics/loaddata.php\',
                            \'requestgraphic=getstudents&id='.$courseid.'&mode='.$mode.
                            '&task=\'+f.options[f.selectedIndex].value+\'&modality=peer\');
                            "><label for="peermod">'
                        .get_string('peermod', 'block_evalcomix').'</label>
                    </div>
                </div>
            </div>
            <div class="block_evalcomix_filter_block">
                <div id="block_evalcomix_filter_student_select" class="block_evalcomix_display_none">
                    <label for="selectstudent">'.get_string('studentmod', 'block_evalcomix').'</label>
                    <div>
                        <select id="selectstudent" name="selectstudent">
                            <option value="0">'.get_string('selectstudent', 'block_evalcomix').'</option>
                        </select>
                    </div>
                </div>
            </div>
        ';

        return $output;
    }
}
