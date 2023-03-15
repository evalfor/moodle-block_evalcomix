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

/*
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, <info@ansaner.net>
 */

class block_evalcomix_editor_toolmix {
    private $titulo;
    private $listtool = array();
    private $index;
    private $toolpor;
    private $view;
    private $observation;
    private $plantillasid;

    public function __construct($lang='es_utf8', $titulo = '', $observation = '', $params = array()) {
        $this->titulo = $titulo;
        $this->index = 0;
        $this->toolpor = array();
        $this->observation = $observation;
        $this->view = 'design';
        if (isset($params['plantillasId'])) {
            $this->plantillasId = $params['plantillasId'];
        }
    }

    public function get_toolpor() {
        return $this->toolpor;
    }

    public function get_tool($id) {
        return $this->listtool[$id];
    }

    public function get_tools() {
        return $this->listtool;
    }

    public function get_numtool() {
        return count($this->listtool);
    }

    public function get_titulo($id = 0) {
        return $this->titulo;
    }

    public function get_dimension($id = 0) {
        return $this->listtool[$id]->get_dimension();
    }

    public function get_numdim($id = 0) {
        return $this->listtool[$id]->get_numdim();
    }

    public function get_subdimension($id = 0) {
        return $this->listtool[$id]->get_subdimension();
    }

    public function get_numsubdim($id = 0) {
        return $this->listtool[$id]->get_numsubdim();
    }

    public function get_atributo($id = 0) {
        return $this->listtool[$id]->get_atributo();
    }

    public function get_numatr($id = 0) {
        return $this->listtool[$id]->get_numatr();
    }

    public function get_valores($id = 0) {
        return $this->listtool[$id]->get_valores();
    }

    public function get_numvalores($id = 0) {
        return $this->listtool[$id]->get_numvalores();
    }

    public function get_valtotal($id = 0) {
        return $this->listtool[$id]->get_valtotal();
    }

    public function get_numtotal($id = 0) {
        return $this->listtool[$id]->get_numtotal();
    }

    public function get_valtotalpor($id = 0) {
        return $this->listtool[$id]->get_valtotalpor();
    }

    public function get_valorestotal($id = 0) {
        return $this->listtool[$id]->get_valorestotal();
    }

    public function get_valglobal($id = 0) {
        return $this->listtool[$id]->get_valglobal();
    }

    public function get_valglobalpor($id = 0) {
        return $this->listtool[$id]->get_valglobalpor();
    }

    public function get_dimpor($id = 0) {
        return $this->listtool[$id]->get_dimpor();
    }

    public function get_subdimpor($id = 0) {
        return $this->listtool[$id]->get_subdimpor();
    }

    public function get_atribpor($id = 0) {
        return $this->listtool[$id]->get_atribpor();
    }

    public function get_numrango($id = 0) {
        return $this->listtool[$id]->get_numrango();;
    }

    public function get_rango($id = 0) {
        return $this->listtool[$id]->get_rango();
    }

    public function get_dimensionsid() {
        return array();
    }

    public function get_subdimensionsid() {
        $result = array();
        foreach ($this->listtool as $id => $tool) {
            $result[$id] = $tool->get_subdimensionsid();
        }
        return $result;
    }

    public function get_atributosid() {
        return array();
    }

    public function get_valoresid() {
        return array();
    }

    public function get_valorestotalesid() {
        return array();
    }

    public function get_plantillasid($id = 0) {
        return $this->plantillasId;
    }

    public function get_competency_string($id, $dim, $subdim) {
        return $this->listtool[$id]->get_competency_string($id, $dim, $subdim);
    }

    public function set_titulo($titulo, $id = 0) {
        $this->listtool[$id]->set_titulo($titulo);
    }

    public function set_dimension($dimension, $id = 0) {
        $this->listtool[$id]->set_dimension($dimension);
    }

    public function set_numdim($numdim, $id = 0) {
        $this->listtool[$id]->set_numdim($numdim);
    }

    public function set_subdimension($subdimension, $id = 0) {
        $this->listtool[$id]->set_subdimension($subdimension);
    }

    public function set_numsubdim($numsubdim, $id = 0) {
        $this->listtool[$id]->set_numsubdim($numsubdim);
    }

    public function set_atributo($atributo, $id = 0) {
        $this->listtool[$id]->set_atributo($atributo);
    }

    public function set_numatr($numatr, $id = 0) {
        $this->listtool[$id]->set_numatr($numatr);
    }

    public function set_valores($valores, $id = 0) {
        $this->listtool[$id]->set_valores($valores);
    }

    public function set_numvalores($numvalores, $id = 0) {
        $this->listtool[$id]->set_numvalores($numvalores);
    }

    public function set_valtotal($valtotal, $id = 0) {
        $this->listtool[$id]->set_valtotal($valtotal);
    }

    public function set_numtotal($numtotal, $id = 0) {
        $this->listtool[$id]->set_numtotal($numtotal);
    }

    public function set_valtotalpor($valtotalpor, $id = 0) {
        $this->listtool[$id]->set_valtotalpor($valtotalpor, $id);
    }

    public function set_valorestotal($valorestotal, $id = 0) {
        $this->listtool[$id]->set_valorestotal($valorestotal);
    }

    public function set_valglobal($valglobal, $id = 0) {
        $this->listtool[$id]->set_valglobal($valglobal);
    }

    public function set_valglobalpor($valglobalpor, $id = 0) {
        $this->listtool[$id]->set_valglobalpor($valglobalpor);
    }

    public function set_dimpor($dimpor, $id) {
        $this->listtool[$id]->set_dimpor($dimpor, $id);
    }

    public function set_subdimpor($subdimpor, $id=0) {
        $this->listtool[$id]->set_subdimpor($subdimpor);
    }

    public function set_atribpor($atribpor, $id = 0) {
        $this->listtool[$id]->set_atribpor($atribpor, $id);
    }

    public function set_rango($rango, $id = 0) {
        $this->listtool[$id]->set_rango($rango);
    }

    public function set_dimensionsid($dimensionsid, $id = '') {
    }
    public function set_subdimensionsid($subdimensionsid, $id = '') {
    }
    public function set_atributosid($atributosid, $id = '') {
    }
    public function set_valoresid($valoresid, $id = '') {
    }
    public function set_valorestotalesid($valoresid, $id = '') {
    }
    public function set_plantillasid($plantillas, $id = '') {
        $this->plantillasId = $plantillas;
    }


    public function set_toolpor($porcentages) {
        $this->toolpor = $porcentages;
    }

    public function set_tools($listtool) {
        $this->listtool = $listtool;
        foreach ($listtool as $id => $tool) {
            $this->toolpor[$id] = $tool->get_porcentage();
            $this->index = $id;
        }
        $this->index++;
    }

    public function set_view($view, $id='') {
        $this->view = $view;
        foreach ($this->listtool as $key => $tool) {
            $this->listtool[$key]->set_view($view, $id);
        }
    }

    public function add_dimension($dim, $key, $id) {
        $this->listtool[$id]->add_dimension($dim, $key, $id);
    }

    public function remove_dimension($dim, $id = 0) {
        return $this->listtool[$id]->remove_dimension($dim, $id);
    }

    public function add_subdimension($dim, $subdim, $key, $id = 0) {
        return $this->listtool[$id]->add_subdimension($dim, $subdim, $key, $id);
    }

    public function remove_subdimension($dim, $subdim, $id=0) {
        return $this->listtool[$id]->remove_subdimension($dim, $subdim, $id);
    }

    public function add_values($dim, $key, $id) {
        return $this->listtool[$id]->add_values($dim, $key, $id);
    }

    public function remove_values($dim, $grado, $id=0) {
        return $this->listtool[$id]->remove_values($dim, $grado, $id);
    }
    public function add_attribute($dim, $subdim, $atrib, $key, $id = 0) {
        return $this->listtool[$id]->add_attribute($dim, $subdim, $atrib, $key, $id);
    }

    public function remove_attribute($dim, $subdim, $atrib, $id) {
        return $this->listtool[$id]->remove_attribute($dim, $subdim, $atrib, $id);
    }

    public function add_total_values($key, $id) {
        return $this->listtool[$id]->add_total_values($key, $id);
    }

    public function remove_total_values($grado, $id) {
        return $this->listtool[$id]->remove_total_values($grado, $id);
    }

    public function add_range($dim, $grado, $key, $id) {
        return $this->listtool[$id]->add_range($dim, $grado, $key, $id);
    }

    public function remove_range($dim, $grado, $key, $id) {
        return $this->listtool[$id]->remove_range($dim, $grado, $key, $id);
    }

    public function add_competency($id, $dimkey, $subdimkey, $newcompkey, $shortname, $compkey) {
        return $this->listtool[$id]->add_competency($id, $dimkey, $subdimkey, $newcompkey, $shortname, $compkey);
    }

    public function remove_competency($id, $dimkey, $subdimkey, $compkey) {
        return $this->listtool[$id]->remove_competency($id, $dimkey, $subdimkey, $compkey);
    }

    public function add_outcome($id, $dimkey, $subdimkey, $newcompkey, $shortname, $compkey) {
        return $this->listtool[$id]->add_outcome($id, $dimkey, $subdimkey, $newcompkey, $shortname, $compkey);
    }

    public function remove_outcome($id, $dimkey, $subdimkey, $compkey) {
        return $this->listtool[$id]->remove_outcome($id, $dimkey, $subdimkey, $compkey);
    }

    public function display_competencies_modal($id, $dim, $subdim, $mix = '') {
        return $this->listtool[$id]->display_competencies_modal($id, $dim, $subdim, $mix);
    }

    public function get_subdimensionid_from_xml($toolid, $identifier = 0) {
        global $CFG;
        $result = array();
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');

        if ($xmlstring = block_evalcomix_webservice_client::get_tool($toolid)) {
            $xml = simplexml_load_string($xmlstring);

            foreach ($xml as $a => $simpletool) {
                if ($a === 'Description') {
                    continue;
                }
                $id = key($this->listtool);
                if ($tooldata = current($this->listtool)) {
                    next($this->listtool);
                }echo "holaaaaaaa";echo $id;
                if (isset($this->listtool[$id])) {
                    $dimensions = $this->listtool[$id]->get_dimension();
                    $subdimensions = $this->listtool[$id]->get_subdimension();
                    $tagname = dom_import_simplexml($simpletool)->tagName;

                    $typetool = '';
                    if ($tagname[2] == ':') {
                        $type = explode(':', $tagname);
                        $typetool = $type[1];
                    } else {
                        $typetool = $tagname;
                    }
                    if ($typetool == 'SemanticDifferential') {
                        foreach ($subdimensions as $dim => $value1) {
                            foreach ($value1 as $subdim => $value2) {
                                $result[$id][$dim][$subdim] = (string)$simpletool['id'];
                            }
                        }
                    } else {
                        foreach ($simpletool->Dimension as $dimen) {
                            $dim = key($dimensions);
                            if ($dimdata = current($dimensions)) {
                                next($dimensions);
                            }
                            foreach ($dimen->Subdimension as $subdimen) {
                                if (isset($subdimensions[$dim])) {
                                    $subdim = key($subdimensions[$dim]);
                                    if ($subdata = current($subdimensions[$dim])) {
                                        next($subdimensions[$dim]);
                                    }
                                    $result[$id][$dim][$subdim] = (string)$subdimen['id'];
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function get_competency($id = 0) {
        $result = array();
        if (empty($id)) {
            foreach ($this->listtool as $key => $tool) {
                $result[$key] = $tool->get_competency($key);
            }
        } else {
            $result[$id] = $this->listtool[$id]->get_competency($id);
        }
        return $result;
    }

    public function get_outcome($id = 0) {
        $result = array();
        if (empty($id)) {
            foreach ($this->listtool as $id => $tool) {
                $result[$id] = $tool->get_outcome($id);
            }
        } else {
            $result[$id] = $this->listtool[$id]->get_outcome($id);
        }
        return $result;
    }

    public function up_block($params) {
        require_once('array.class.php');

        if (!isset($params['mixed'])) {
            $id = $params['id'];
            return $this->listtool[$id]->up_block($params);
        }
        $id = $params['id'];
        $instance = $params['instance'];
        $blockdata = $params['blockData'];
        $blockindex = $params['blockIndex'];
        $blockname = $params['blockName'];

        if (isset($blockdata)) {
            $previousindex = block_evalcomix_array_util::get_previous_item($blockdata, $blockindex);
            if ($previousindex !== false) {
                $elem = $instance;
                $blockdata = $this->array_remove($blockdata, $blockindex);
                $blockdata = block_evalcomix_array_util::array_add_left($blockdata, $previousindex, $elem, $blockindex);
            }
        }
        $this->listtool = $blockdata;
    }

    public function down_block($params) {
        require_once('array.class.php');

        if (!isset($params['mixed'])) {
            $id = $params['id'];
            return $this->listtool[$id]->down_block($params);
        }

        $id = $params['id'];
        $instance = $params['instance'];
        $blockdata = $params['blockData'];
        $blockindex = $params['blockIndex'];
        $blockname = $params['blockName'];

        if (isset($blockdata)) {
            $nextindex = block_evalcomix_array_util::get_next_item($blockdata, $blockindex);
            if ($nextindex !== false) {
                $elem = $instance;
                $blockdata = $this->array_remove($blockdata, $blockindex);
                $blockdata = block_evalcomix_array_util::array_add_rigth($blockdata, $nextindex, $elem, $blockindex);
            }
        }
        $this->listtool = $blockdata;
    }

    public function add($type, $index = null) {
        $id = $this->index;

        $language = '';
        $titulo = get_string('title', 'block_evalcomix');
        list($usec, $sec) = explode(' ', microtime());
        $seed = (float)$sec + ((float)$usec * 100000);
        mt_srand($seed);
        $dim = mt_rand();
        $numdim[$id] = 1;
        $dimension[$id][$dim]['nombre'] = get_string('titledim', 'block_evalcomix');
        $commentdim[$id][$dim] = 'hidden';
        $valglobal[$id][$dim] = false;
        $valglobalpor[$id][$dim] = null;
        $dimpor[$id][$dim] = 100;

        $subdim = 0;
        $subdimension[$id][$dim][$subdim]['nombre'] = get_string('titlesubdim', 'block_evalcomix');
        $numsubdim[$id][$dim] = 1;
        $subdimpor[$id][$dim][$subdim] = 100;

        $atrib = 0;
        $atributo[$id][$dim][$subdim][$atrib]['nombre'] = get_string('titleatrib', 'block_evalcomix');
        $numatr[$id][$dim][$subdim] = 1;
        $commentatr[$id][$dim][$subdim][$atrib] = 'hidden';
        $atribpor[$id][$dim][$subdim][$atrib] = 100;

        $numvalores[$id][$dim] = 2;
        $valores[$id][$dim][0]['nombre'] = get_string('titlevalue', 'block_evalcomix').'1';
        $valores[$id][$dim][1]['nombre'] = get_string('titlevalue', 'block_evalcomix').'2';
        if ($type == 'lista') {
            $valores[$id][$dim][0]['nombre'] = get_string('no', 'block_evalcomix');
            $valores[$id][$dim][1]['nombre'] = get_string('yes', 'block_evalcomix');
        }

        $competency[$dim][$subdim] = array();
        $outcome[$dim][$subdim] = array();

        $numtotal = array($id => 0);
        $valorestotal = array();
        $valtotal = null;
        $tool;
        switch ($type) {
            case 'lista':{
                $tool = new block_evalcomix_editor_toollist($language, $titulo, $dimension, $numdim, $subdimension,
                    $numsubdim, $atributo, $numatr, $valores, $numvalores, $valtotal, $numtotal, $valorestotal, $valglobal,
                    $valglobalpor, $dimpor, $subdimpor, $atribpor, $commentatr, $id);
            }break;
            case 'escala':{
                $tool = new block_evalcomix_editor_toolscale($language, $titulo, $dimension, $numdim, $subdimension,
                    $numsubdim, $atributo, $numatr, $valores, $numvalores, $valtotal, $numtotal, $valorestotal, $valglobal,
                    $valglobalpor, $dimpor, $subdimpor, $atribpor, $commentatr, $commentdim, $id);
            }break;
            case 'listaescala':{
                $tool = new block_evalcomix_editor_toollistscale($language, $titulo, $dimension, $numdim, $subdimension,
                    $numsubdim, $atributo, $numatr, $valores, $numvalores, $valtotal, $numtotal, $valorestotal, $valglobal,
                    $valglobalpor, $dimpor, $subdimpor, $atribpor, $commentatr, $commentdim, $id);
            }break;
            case 'diferencial':{
                $tool = new block_evalcomix_editor_tooldifferential($language, $titulo, $dimension, $numdim, $subdimension,
                    $numsubdim, $atributo, $numatr, $valores, $numvalores, $valtotal, $numtotal, $valorestotal, $valglobal,
                    $valglobalpor, $dimpor, $subdimpor, $atribpor, $commentatr, $id, 1);
            }break;
            case 'rubrica':{
                $tool = new block_evalcomix_editor_toolrubric($language, $titulo, $dimension, $numdim, $subdimension,
                    $numsubdim, $atributo, $numatr, $valores, $numvalores, $valtotal, $numtotal, $valorestotal, $valglobal,
                    $valglobalpor, $dimpor, $subdimpor, $atribpor, $commentatr, $commentdim, $id);
            }break;
            case 'mixta':{
                $tool = new block_evalcomix_editor_toolmix($language, $titulo);
            }break;
        }
        $tool->set_competency($competency);
        $tool->set_outcome($outcome);
        if (isset($index) && $index != '') {
            $this->listtool = $this->array_add($this->listtool, $index, $tool, $this->index);
            $this->index++;
        } else {
            $this->listtool[$this->index] = $tool;
            $this->index++;
        }
    }

    public function remove($index) {
        $this->listtool = $this->array_remove($this->listtool, $index);
    }

    public function display_body($data) {
        if ($this->view == 'view') {
            echo '<input type="button" style="width:10em" value="'.get_string('view', 'block_evalcomix').'"
                onclick=\'javascript:location.href="generator.php?op=design&courseid='.$data['courseid'].'"\'><br>';
        }
        if (isset($data['titulo'])) {
            $this->titulo = stripslashes($data['titulo']);
        }
        echo '
        <div id="cuerpomix">
                <label for="titulo">'.get_string('mix', 'block_evalcomix').'</label>
                <span class="labelcampo">
                    <textarea class="width" id="titulo" name="titulo" rows="3" cols="10">'.$this->titulo.'</textarea>
                </span>
        ';
        if ($this->view == 'design') {
            echo '
                <select id="seltool" name="seltool" onchange=\'javascript:
                    sendPost("body", "nopor=1&observation0="+document.getElementById("observation0").value +
                    "&titulo="+document.getElementById("titulo").value+"&at=1&addtool="+this.value+"", "mainform0");\'>
                    <option value="0">'.get_string('addtool', 'block_evalcomix').'</option>
                    <option value="escala">'.get_string('ratescale', 'block_evalcomix').'</option>
                    <option value="listaescala">'.get_string('listrate', 'block_evalcomix').'</option>
                    <option value="lista">'.get_string('checklist', 'block_evalcomix').'</option>
                    <option value="rubrica">'.get_string('rubric', 'block_evalcomix').'</option>
                    <option value="diferencial">'.get_string('differentail', 'block_evalcomix').'</option>
                </select>
                <input type="hidden" id="sumpor" value=""/>
            ';
        }
        $mix = '';
        foreach ($this->listtool as $id => $value) {
            echo '
                <div class="bordertool">
            ';
            if ($this->view == 'design') {
                echo '
                    <div>
                        <input type="button" class="delete" onclick=\'javascript:sendPost("body","nopor=1&mix='.$mix.
                        '&amp;id='.$id.'&amp;titulo="+document.getElementById("titulo").value+"&amp;addtool'.$id.
                        '=1&amp;observation0="+document.getElementById("observation0").value + "&amp;dt=1", "mainform0");\'>
                        <input type="button" class="up" onclick=\'javascript:sendPost("body","nopor=1&mix='.$mix.
                        '&amp;id='.$id.'&amp;titulo="+document.getElementById("titulo").value+"&amp;tUp='.$id.
                        '&amp;observation0="+document.getElementById("observation0").value + "&amp;moveTool=1", "mainform0");\'>
                        <br>
                    </div>
                ';
            }
            $value->display_body($data, $id, $this->toolpor[$id]);

            if ($this->view == 'design') {
                echo '
                    <div>
                        <input type="button" class="add" onclick=\'javascript:mostrar("newtool'.$id.'")\'>
                        <span id="newtool'.$id.'">
                            <select id="seltool'.$id.'" name="addtool'.$id.'"
                            onchange=\'javascript:sendPost("body", "nopor=1&id='.$id.
                                '&observation0="+document.getElementById("observation0").value +
                                "&at=1&titulo="+document.getElementById("titulo").value+"&addtool'.$id.
                                '="+this.value+"", "mainform0");\'>
                                <option value="0">'.get_string('addtool', 'block_evalcomix').'</option>
                                <option value="escala">'.get_string('ratescale', 'block_evalcomix').'</option>
                                <option value="listaescala">'.get_string('listrate', 'block_evalcomix').'</option>
                                <option value="lista">'.get_string('checklist', 'block_evalcomix').'</option>
                                <option value="rubrica">'.get_string('rubric', 'block_evalcomix').'</option>
                                <option value="diferencial">'.get_string('differentail', 'block_evalcomix').'</option>
                            </select>
                        </span>
                    </div>
                    <div>
                        <input type="button" class="down" onclick=\'javascript:sendPost("body","nopor=1&mix='.
                        $mix.'&amp;id='.$id.'&amp;titulo="+document.getElementById("titulo").value+"&amp;tDown='.
                        $id.'&amp;observation0="+document.getElementById("observation0").value+"&amp;moveTool=1", "mainform0");\'>
                    </div>
                ';
            }
            echo '
                </div>';
        }

        if (isset($data['observation0'])) {
            $this->observation = stripslashes($data['observation0']);
        }
        echo '
                <div id="comentario">
                    <div id="marco">
                        <label for="observation0">' . get_string('observation', 'block_evalcomix'). ':</label>
                        <textarea id="observation0" style="width:100%" rows="4" cols="200">' . $this->observation . '</textarea>
                    </div>
                </div>
            ';

        echo '
            <input type="hidden" name="courseid" id="courseid" value="'.$data['courseid'].'">
        </div>
        ';
    }

    public function display_dimension($dim, $data, $id) {
        $this->listtool[$id]->display_dimension($dim, $data);
    }

    public function display_subdimension($dim, $subdim, $data, $id) {
        $this->listtool[$id]->display_subdimension($dim, $subdim, $data);
    }

    /*
    @param $array
    @param $i índice del elemento a eliminar en $array
    @return $array sin el elemento
    Elimina de @array el elemento $i
    */
    public function array_remove($array, $i) {
        $arrayaux = array();
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if ($key != $i) {
                    $arrayaux[$key] = $value;
                }
            }
        }
        return $arrayaux;
    }

    /*
    @param $array - array o tabla hash
    @param $i índice del elemento a partir del que introducirá el elemento $elem en $array
    @param $elem nuevo elemento a añadir
    @param $index indice del nuevo elemento. Si no se especifica, el nuevo índice será $i+1
    @return $array con el nuevo elemento
    Añade $elem a @array a continuación de $i.
    */
    public function array_add($array, $i, $elem, $index) {
        $arrayaux = array();
        $flag = false;
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $arrayaux[$key] = $value;
                if ($key == $i) {
                    $flag = true;
                }
                if ($flag == true) {
                    $newkey = $key + 1;
                    if (isset($index)) {
                        $newkey = $index;
                    }
                    $arrayaux[$newkey] = $elem;
                    $flag = false;
                }
            }
        }
        return $arrayaux;
    }

    public function export($params = array()) {
        $mixed = 0;
        if (isset($params['mixed'])) {
            $mixed = $params['mixed'];
        }
        $idtool = '';
        if (isset($params['id'])) {
            $idtool = $params['id'];
        }

        $root = '';
        $rootend = '';
        if ($mixed == '0') {
            $root = '<mt:MixTool xmlns:mt="http://avanza.uca.es/assessmentservice/mixtool"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:schemaLocation="http://avanza.uca.es/assessmentservice/mixtool http://avanza.uca.es/assessmentservice/MixTool.xsd"';
            $rootend = '</mt:MixTool>';
        } else if ($mixed == '1') {
            $root = '<MixTool ';
            $rootend = '</MixTool>';
        }

        $xml = $root . ' id="'. $idtool .'" name="' . htmlspecialchars($this->titulo) . '" instruments="' .
        count($this->listtool) .'">';

        if (isset($this->observation)) {
            $xml .= '<Description>' . htmlspecialchars($this->observation) . '</Description>
';
        }

        foreach ($this->listtool as $id => $value) {
            $tid = (isset($this->plantillasId[$id])) ? $this->plantillasId[$id] : '';
            $xml .= $this->listtool[$id]->export(array('mixed' => '1', 'id' => $tid));
        }
        $xml .= $rootend;
        return $xml;
    }

    public function display_body_view($data) {
        if (isset($data['titulo'])) {
            $this->titulo = stripslashes($data['titulo']);
        }

        echo '
        <div id="cuerpomix">
                <label for="titulo">'.get_string('mix', 'block_evalcomix').'</label>
                <span class="labelcampo">
                    <span class="titulovista">'.$this->titulo.'</span>
                </span>
        ';
        foreach ($this->listtool as $id => $value) {
            echo '
                <div class="bordertool">
            ';

            $value->display_body_view($data, $id, $this->toolpor[$id]);;

            echo '<br>
                </div>';
        }

        if (isset($data['observation0'])) {
            $this->observation = stripslashes($data['observation0']);
        }

        echo '
                <div id="comentario">
                    <div id="marco">
                        <label for="observation0">' . get_string('observation', 'block_evalcomix'). ':</label>
                        <textarea id="observation0" style="width:100%" rows="4" cols="200">' . $this->observation . '</textarea>
                    </div>
                </div>
            ';

        echo '

        </div>
        ';
    }

    public function print_tool($root = '') {
        foreach ($this->listtool as $tool) {
            $tool->print_tool();
            echo '<br><br><br>';
        }
    }
}
