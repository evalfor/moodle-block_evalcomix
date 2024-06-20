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

class block_evalcomix_editor {
    public $id;
    public $dimension;
    public $numdim;
    public $valores;
    public $numvalores;
    public $subdimension;
    public $numsubdim;
    public $subdimpor;
    public $atributo;
    public $numatr;
    public $atribpor;
    public $competency;
    public $outcome;
    public $allcompetencies;
    public $alloutcomes;
    public $subdimensionsid;

    public function get_id() {
        return $this->id;
    }

    public function get_dimension() {
        return $this->dimension[$this->id];
    }

    public function get_numdim() {
        return $this->numdim[$this->id];
    }

    public function get_valores() {
        return $this->valores[$this->id];
    }

    public function get_numvalores() {
        return $this->numvalores[$this->id];
    }

    public function get_subdimension() {
        return $this->subdimension[$this->id];

    }

    public function get_numsubdim() {
        return $this->numsubdim[$this->id];
    }

    public function get_subdimpor() {
        if (isset($this->subdimpor[$this->id])) {
            return $this->subdimpor[$this->id];
        }
    }

    public function get_atributo() {
        return $this->atributo[$this->id];
    }

    public function get_numatr() {
        return $this->numatr[$this->id];
    }

    public function get_atribpor() {
        return $this->atribpor[$this->id];
    }

    public function get_competency($id = null) {
        $result = array();
        if (!is_numeric($id)) {
            $result = (isset($this->competency)) ? $this->competency : array();
        } else {
            $result = (isset($this->competency[$id])) ? $this->competency[$id] : array();
        }

        return $result;
    }

    public function get_outcome($id = null) {
        $result = array();
        if (!is_numeric($id)) {
            $result = (isset($this->outcome)) ? $this->outcome : array();
        } else {
            $result = (isset($this->outcome[$id])) ? $this->outcome[$id] : array();
        }

        return $result;
    }

    public function get_subdimensionsid() {
        return $this->subdimensionsid[$this->id];
    }

    public function set_id($id) {
        $this->id = $id;
    }

    public function set_dimension($dimension) {
        $this->dimension[$this->id] = $dimension;
    }

    public function set_numdim($numdim) {
        $this->numdim[$this->id] = $numdim;
    }

    public function set_valores($valores) {
        $this->valores[$this->id] = $valores;
    }

    public function set_numvalores($numvalores) {
        $this->numvalores[$this->id] = $numvalores;
    }

    public function set_subdimension($subdimension) {
        unset($this->subdimension[$this->id]);
        $this->subdimension[$this->id] = $subdimension;
    }

    public function set_numsubdim($numsubdim) {
        $this->numsubdim[$this->id] = $numsubdim;
    }

    public function set_subdimpor($subdimpor) {
        $this->subdimpor[$this->id] = $subdimpor;
    }

    public function set_atributo($atributo) {
        $this->atributo[$this->id] = $atributo;
    }

    public function set_numatr($numatr) {
        $this->numatr[$this->id] = $numatr;
    }

    public function set_atribpor($atribpor, $id = 0) {
        $this->atribpor[$this->id] = $atribpor;
    }

    public function set_competency($competency) {
        $this->competency = $competency;
    }

    public function set_outcome($outcome) {
        $this->outcome = $outcome;
    }

    public function set_subdimensionsid($subdimensionsid, $id = '') {
        $this->subdimensionsid[$this->id] = $subdimensionsid;
    }

    public function __construct($params = array()) {
        $this->id = (isset($params['id'])) ? $params['id'] : 0;
        $this->dimension = (isset($params['dimension'])) ? $params['dimension'] : array();
        $this->subdimension = (isset($params['subdimension'])) ? $params['subdimension'] : array();
        $this->competency = (isset($params['competency'])) ? $params['competency'] : array();
        $this->outcome = (isset($params['outcome'])) ? $params['outcome'] : array();
        $this->allcompetencies = (isset($params['allcompetencies'])) ? $params['allcompetencies'] : array();
        $this->alloutcomes = (isset($params['alloutcomes'])) ? $params['alloutcomes'] : array();
        $this->subdimensionsid = (isset($params['subdimensionsid'])) ? $params['subdimensionsid'] : array();
        $this->numsubdim = (isset($params['numsubdim'])) ? $params['numsubdim'] : array();
        $this->subdimpor = (isset($params['subdimpor'])) ? $params['subdimpor'] : array();
        $this->atributo = (isset($params['atributo'])) ? $params['atributo'] : array();
        $this->numatr = (isset($params['numatr'])) ? $params['numatr'] : array();
        $this->numdim = (isset($params['numdim'])) ? $params['numdim'] : array();
        $this->numvalores = (isset($params['numvalores'])) ? $params['numvalores'] : array();
        $this->valores = (isset($params['valores'])) ? $params['valores'] : array();
        $this->atribpor = (isset($params['atribpor'])) ? $params['atribpor'] : array();

        if (empty($this->competency) && !empty($this->subdimension)) {
            foreach ($this->subdimension as $key => $item1) {
                foreach ($item1 as $dim => $item2) {
                    foreach ($item2 as $subdim => $item3) {
                        $this->competency[$key][$dim][$subdim] = array();
                    }
                }
            }
        }
        if (empty($this->outcome) && !empty($this->subdimension)) {
            foreach ($this->subdimension as $key => $item1) {
                foreach ($item1 as $dim => $item2) {
                    foreach ($item2 as $subdim => $item3) {
                        $this->outcome[$key][$dim][$subdim] = array();
                    }
                }
            }
        }
    }

    /**
     * This function adds $elem to @array after $i.
     * @param array $array
     * @param int $i index of the element from which to insert the item $elem in $array
     * @param string $elem nuevo elemento a añadir
     * @param int $index indice del nuevo elemento. Si no se especifica, el nuevo índice será $i+1
     * @return array $array with the new item
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

    /**
     * This function removes the $i element from $array
     * @param array $array
     * @param int $i key of the element to remove from $array
     * @return array $array without the element
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

    public function up_block($params) {
        require_once('array.class.php');
        $id = $this->id;

        $instancename = $params['instanceName'];
        $blockdata = $params['blockData'];
        $blockindex = $params['blockIndex'];
        $blockname = $params['blockName'];
        if (isset($params['dim'])) {
            $dim = $params['dim'];
        }
        if (isset($params['subdim'])) {
            $subdim = $params['subdim'];
        }

        if (isset($blockdata)) {
            $previousindex = block_evalcomix_array_util::get_previous_item($blockdata, $blockindex);
            if ($previousindex !== false) {
                $elem['nombre'] = $instancename;
                $blockdata = $this->array_remove($blockdata, $blockindex);
                $blockdata = block_evalcomix_array_util::array_add_left($blockdata, $previousindex, $elem, $blockindex);
            }
        }
        switch ($blockname) {
            case 'dimension':{
                $this->dimension[$id] = $blockdata;
            }break;
            case 'subdimension':{
                $this->subdimension[$id][$dim] = $blockdata;
            }break;
            case 'atributo':{
                $this->atributo[$id][$dim][$subdim] = $blockdata;
            }
        }
    }

    public function down_block($params) {
        require_once('array.class.php');
        $id = $this->id;

        $instancename = $params['instanceName'];
        $blockdata = $params['blockData'];
        $blockindex = $params['blockIndex'];
        $blockname = $params['blockName'];
        $dim = null;
        if (isset($params['dim'])) {
            $dim = $params['dim'];
        }
        $subdim = null;
        if (isset($params['subdim'])) {
            $subdim = $params['subdim'];
        }
        if (isset($blockdata)) {
            $nextindex = block_evalcomix_array_util::get_next_item($blockdata, $blockindex);
            if ($nextindex !== false) {
                $elem['nombre'] = $instancename;
                $blockdata = $this->array_remove($blockdata, $blockindex);
                $blockdata = block_evalcomix_array_util::array_add_rigth($blockdata, $nextindex, $elem, $blockindex);
            }
        }
        switch ($blockname) {
            case 'dimension':{
                $this->dimension[$id] = $blockdata;
            }break;
            case 'subdimension':{
                if ($dim !== null) {
                    $this->subdimension[$id][$dim] = $blockdata;
                }
            }break;
            case 'atributo':{
                if ($dim !== null && $subdim !== null) {
                    $this->atributo[$id][$dim][$subdim] = $blockdata;
                }
            }
        }
    }

    public function add_competency($id, $dimkey, $subdimkey, $newcompkey, $shortname, $compkey = null) {
        if (!isset($compkey)) {
            $this->competency[$id][$dimkey][$subdimkey][$newcompkey] = $shortname;
        } else {
            $this->competency[$id][$dimkey][$subdimkey] = $this->array_add($this->competency[$id][$dimkey][$subdimkey],
                $compkey, $shortname, $newcompkey);
        }
    }

    public function remove_competency($id, $dimkey, $subdimkey, $compkey) {
        if (isset($this->competency[$id][$dimkey][$subdimkey][$compkey])) {
            unset($this->competency[$id][$dimkey][$subdimkey][$compkey]);
        }
        return 1;
    }

    public function add_outcome($id, $dimkey, $subdimkey, $newoutkey, $shortname, $outkey = null) {
        if (!isset($outkey)) {
            $this->outcome[$id][$dimkey][$subdimkey][$newoutkey] = $shortname;
        } else {
            $this->outcome[$id][$dimkey][$subdimkey] = $this->array_add($this->outcome[$id][$dimkey][$subdimkey],
                $outkey, $shortname, $newoutkey);
        }
    }

    public function remove_outcome($id, $dimkey, $subdimkey, $outkey) {
        if (isset($this->outcome[$id][$dimkey][$subdimkey][$outkey])) {
            unset($this->outcome[$id][$dimkey][$subdimkey][$outkey]);
        }
        return 1;
    }

    public function remove_dimension($dim, $id = 0) {
        $id = $this->id;
        if ($this->numdim[$id] > 1) {
            if ($this->numsubdim[$id][$dim] > 0) {
                $this->numsubdim[$id][$dim]--;
            }
            if (isset($this->numvalores[$id][$dim]) && $this->numvalores[$id][$dim] > 2) {
                $this->numvalores[$id][$dim]--;
            }
            $this->dimension[$id] = $this->array_remove($this->dimension[$id], $dim);
            $this->subdimension[$id] = $this->array_remove($this->subdimension[$id], $dim);
            $this->atributo[$id] = $this->array_remove($this->atributo[$id], $dim);
            if (isset($this->valores[$id])) {
                $this->valores[$id] = $this->array_remove($this->valores[$id], $dim);
            }
            $this->numsubdim[$id] = $this->array_remove($this->numsubdim[$id], $dim);
            $this->numatr[$id] = $this->array_remove($this->numatr[$id], $dim);
            unset($this->competency[$id][$dim]);
            unset($this->outcome[$id][$dim]);
            $this->numdim[$id]--;
        } else {
            echo '<span class="mensaje">'.get_string('alertdimension', 'block_evalcomix').'</span>';
        }
        return 1;
    }

    public function add_subdimension($dim, $subdim, $key, $id = 0) {
        $subdimen;
        $id = $this->id;
        $this->numsubdim[$id][$dim] += 1;
        if (!isset($subdim)) {
            $subdim = $key;
            $subdimen = $subdim;
            $this->subdimension[$id][$dim][$subdim]['nombre'] = get_string('titlesubdim', 'block_evalcomix').
                $this->numsubdim[$id][$dim];
        } else {
            $newindex = $key;
            $subdimen = $newindex;
            $elem['nombre'] = get_string('titlesubdim', 'block_evalcomix').$this->numsubdim[$id][$dim];
            $this->subdimension[$id][$dim] = $this->array_add($this->subdimension[$id][$dim], $subdim, $elem, $newindex);
        }
        $this->numatr[$id][$dim][$subdimen] = 1;
        $atrib = $key++;
        $this->atributo[$id][$dim][$subdimen][$atrib]['nombre'] = get_string('titleatrib', 'block_evalcomix').
            $this->numatr[$id][$dim][$subdim];
        $this->atribpor[$id][$dim][$subdimen][$atrib] = 100;
    }

    public function remove_subdimension($dim, $subdim) {
        $id = $this->id;
        if ($this->numsubdim[$id][$dim] > 1) {
            $this->numsubdim[$id][$dim]--;
            $this->subdimension[$id][$dim] = $this->array_remove($this->subdimension[$id][$dim], $subdim);
            $this->subdimpor[$id][$dim] = $this->array_remove($this->subdimpor[$id][$dim], $subdim);
            if (empty($this->subdimension[$id][$dim])) {
                unset($this->subdimension[$id][$dim]);
            }
            $this->atributo[$id][$dim] = $this->array_remove($this->atributo[$id][$dim], $subdim);
            $this->numatr[$id][$dim] = $this->array_remove($this->numatr[$id][$dim], $subdim);
            unset($this->competency[$id][$dim][$subdim]);
            unset($this->outcome[$id][$dim][$subdim]);
        } else {
            echo '<span class="mensaje">'.get_string('alertsubdimension', 'block_evalcomix').'</span>';
        }
        return 1;
    }

    public function remove_attribute($dim, $subdim, $atrib) {
        $id = $this->id;
        if (isset($this->atributo[$id][$dim][$subdim][$atrib])) {
            if ($this->numatr[$id][$dim][$subdim] > 1) {
                $this->numatr[$id][$dim][$subdim]--;

                $this->atributo[$id][$dim][$subdim] = $this->array_remove($this->atributo[$id][$dim][$subdim], $atrib);
                $this->atribpor[$id][$dim][$subdim] = $this->array_remove($this->atribpor[$id][$dim][$subdim], $atrib);
            } else {
                echo '<span class="mensaje">'.get_string('alertatrib', 'block_evalcomix').'</span>';
            }
        }
        return 1;
    }

    public function display_competencies($dim, $subdim, $mix = '', $label = 'subdimension') {
        $id = $this->id;
        echo '
                <div class="float-md-left">
        ';
        $extra = ($label == 'title') ? '&modaltitle'.$this->id.'=1' : '';

        echo '
                    <!-- Button trigger modal -->
                    <div class="mb-2">
                        <button type="button" class="bg-compcolor text-white" data-toggle="modal" data-target="#subcomp'.
                        $id.'_'.$dim.'_'.$subdim.'" id="modalbutton'.$id.'_'.$dim.'_'.$subdim.'">
                          '.get_string('associatecompandout', 'block_evalcomix').'
                        </button>
                    </div>
                    <div id="compout">
        ';
        if (isset($this->competency[$id][$dim][$subdim])) {
            foreach ($this->competency[$id][$dim][$subdim] as $comp => $competency) {
                $onclick = 'sendPost(\'subdimensiontag'.$id.'_'.$dim.'_'.$subdim.'\', \'mix='.$mix.'&id='.$id.
                           '&modalclose='.$dim.'_'.$subdim.'&modalDelCompSub='.$dim.'_'.$subdim.'_'.$comp.$extra.
                           '\', \'mainform0\');';
                $this->display_competency($comp, $onclick);
            }
        }
        if (isset($this->outcome[$id][$dim][$subdim])) {
            foreach ($this->outcome[$id][$dim][$subdim] as $out => $outcome) {
                $onclick = 'sendPost(\'subdimensiontag'.$id.'_'.$dim.'_'.$subdim.'\', \'mix='.$mix.'&id='.$id.
                           '&modalclose='.$dim.'_'.$subdim.'&modalDelOutSub='.$dim.'_'.$subdim.'_'.$out.
                           $extra.'\', \'mainform0\');';
                $this->display_competency($out, $onclick);
            }
        }

        echo '  </div>
                <!-- Modal -->
                    <div class="modal fade" id="subcomp'. $id.'_'.$dim.'_'.$subdim.'" tabindex="-1" role="dialog"
                    aria-labelledby="subcomplabel'. $id.'_'.$dim.'_'.$subdim.'" aria-hidden="true" data-backdrop="static">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="subcomplabel'. $id.'_'.$dim.'_'.$subdim.'">'.
                            get_string('associatecompandout', 'block_evalcomix').'</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            onclick="sendPost(\'cuerpo'.$id.'\', \'mix='.$mix.'&id='.$id.
                                $extra.'&modalclosevoid='.$dim.'_'.$subdim.'&modalclose='.$dim.'_'.$subdim.'\', \'mainform0\');">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                <div id="modal'.$id.'_'.$dim.'_'.$subdim.'">
        ';
        $this->display_competencies_modal($id, $dim, $subdim, $mix, $label);

        echo '  </div>
                <div class="modal-footer">
                            <button type="button" class="btn bg-compcolor text-white" onclick="
                            sendPost(\'cuerpo'.$id.'\', \'mix='.$mix.'&id='.$id.
                                $extra.'&modalclosevoid='.$dim.'_'.$subdim.'&modalclose='.$dim.'_'.$subdim.'\', \'mainform0\');
                            " data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="clear"></div>
        ';
    }

    public function display_competencies_modal($id, $dim, $subdim, $mix = '', $label = 'subdimension') {
        $comptypes = block_evalcomix_editor_tool::get_course_comptypes();
        $extra = ($label == 'title') ? '&modaltitle'.$this->id.'=1' : '';

        echo '
                          <div class="modal-body">
                            <h6>'.get_string('subdimension', 'block_evalcomix').' '.
                            htmlentities($this->subdimension[$id][$dim][$subdim]['nombre'], ENT_QUOTES).'</h6><hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6>'.get_string('choicecompetency', 'block_evalcomix').'</h6>
                                    <div id="compbox" class="border p-1 mb-1 rounded clearfix">';

        if (isset($this->competency[$id][$dim][$subdim])) {
            foreach ($this->competency[$id][$dim][$subdim] as $comp => $competency) {
                $onclick = 'sendPost(\'modal'.$id.'_'.$dim.'_'.$subdim.'\', \'mix='.$mix.'&id='.$id.
                           '&modalDelComp='.$dim.'_'.$subdim.'_'.$comp.$extra.'\', \'mainform0\');';
                $this->display_competency($comp, $onclick);
            }
        }
        echo '
                                    </div>

                                    <select class="form-control overflow-auto" id="selcomp" size="10" title="'.
                                            get_string('choicecompetency', 'block_evalcomix').'" data-live-search="true"
                                        onchange="
                                        sendPost(\'modal'.$id.'_'.$dim.'_'.$subdim.'\', \'mix='.$mix.'&id='.$id.$extra.
                                        '&modalCompSel=\'+this.options[this.selectedIndex].text+\'&modalAddComp='.
                                        $dim.'_'.$subdim.'_\'+this.value, \'mainform0\');
                                    ">';

        if ($coursecompetencies = block_evalcomix_editor_tool::get_course_competencies()) {
            foreach ($coursecompetencies as $item) {
                if (isset($this->competency[$id][$dim][$subdim])
                        && array_key_exists($item->idnumber, $this->competency[$id][$dim][$subdim])) {
                    continue;
                }
                echo '<option data-subtext="'.$item->shortname.'" value="'.$item->idnumber.'">('.$item->idnumber.
                ') '.$item->shortname.'</option>';
            }
        }
        echo '
                        </select>
                        <div class="border px-1 pt-1 mt-3 rounded clearfix">
                            <h6>'.get_string('newcomp', 'block_evalcomix').'
                            <a href=# class="btn rounded-circle text-white px-1 py-1"
                            style="background-color:#0C4A15;line-height:1" onclick="
                            var b = document.getElementById(\'newcomp'.$id.'_'.$dim.'_'.$subdim.'\').style.display;
                            if (b == \'block\') {
                                document.getElementById(\'newcomp'.$id.'_'.$dim.'_'.$subdim.'\').style.display = \'none\';
                            } else {
                                document.getElementById(\'newcomp'.$id.'_'.$dim.'_'.$subdim.'\').style.display = \'block\';
                            }
                            ">></a>
                            </h6>
                            <div id="newcomp'.$id.'_'.$dim.'_'.$subdim.'" style="display:none">
                            <div class="form-group mb-2" id="fg1-'.$id.'_'.$dim.'_'.$subdim.'">
                                <label for="compidnumber'.$id.'_'.$dim.'_'.$subdim.'" class="mb-0">'.
                                get_string('compidnumber', 'block_evalcomix').'</label>
                                <input type="text" class="w-100" id="compidnumber'.$id.'_'.$dim.'_'.$subdim.'"
                                name="compidnumber'.$id.'_'.$dim.'_'.$subdim.'" placeholder="'.
                                get_string('compidnumber', 'block_evalcomix').'">
                            </div>
                            <div class="form-group mb-2">
                                <label for="compshortname'.$id.'_'.$dim.'_'.$subdim.'" class="mb-0">'.
                                get_string('compshortname', 'block_evalcomix').'</label>
                                <input type="text" class="w-100" id="compshortname'.$id.'_'.$dim.'_'.$subdim.'"
                                name="compshortname'.$id.'_'.$dim.'_'.$subdim.'" placeholder="'.
                                get_string('compshortname', 'block_evalcomix').'">
                            </div>
                            <div class="form-group mb-2">
                                <label for="compdescription'.$id.'_'.$dim.'_'.$subdim.'" class="mb-0">'.
                                get_string('compdescription', 'block_evalcomix').'</label>
                                <textarea class="w-100" id="compdescription'.$id.'_'.$dim.'_'.$subdim.'"
                                name="compdescription'.$id.'_'.$dim.'_'.$subdim.'"></textarea>
                            </div>
                            <div class="form-group mb-2">
                                <label for="comptype'.$id.'_'.$dim.'_'.$subdim.'" class="mb-0">'.
                                get_string('comptype', 'block_evalcomix').'</label>
                                <select class="w-100" id="comptype'.$id.'_'.$dim.'_'.$subdim.'"
                                name="comptype'.$id.'_'.$dim.'_'.$subdim.'">
                                    <option value="">'.get_string('selectcomptype', 'block_evalcomix').'</option>';
        foreach ($comptypes as $comptype) {
            echo '<option value="'.$comptype->shortname.'">'.$comptype->shortname.'</option>';
        }

        echo '
                                </select>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-default" onclick="
                                var idnumber = document.getElementById(\'compidnumber'.$id.'_'.$dim.'_'.$subdim.'\');
                                var shortname = document.getElementById(\'compshortname'.
                                $id.'_'.$dim.'_'.$subdim.'\');
                                var descriptionvalue = document.getElementById(\'compdescription'.
                                $id.'_'.$dim.'_'.$subdim.'\').value.trim();
                                var comptype = document.getElementById(\'comptype'.
                                $id.'_'.$dim.'_'.$subdim.'\').value.trim();
                                var idnumbervalue = idnumber.value.trim();
                                var shortnamevalue = shortname.value.trim();
                                if (!idnumbervalue) {
                                    idnumber.style.border=\'1px solid #FF0000\';
                                    return false;
                                }
                                if (!shortnamevalue) {
                                    shortname.style.border=\'1px solid #FF0000\';
                                    return false;
                                }
                                const coursecompetencies = [];';
        if (isset($coursecompetencies)) {
            $i = 0;
            foreach ($coursecompetencies as $cc) {
                echo 'coursecompetencies['.$i.'] = \''. strtolower($cc->idnumber).'\';';
                ++$i;
            }
        }
        echo '
            var error = false;
            var length = coursecompetencies.length;
            for (var i = 0; i < length; i++) {
                if (coursecompetencies[i] == idnumbervalue.toLowerCase()) {
                    error = true;
                }
            }
            if (error == true) {
                idnumber.style.border=\'1px solid #FF0000\';
                var fg = document.getElementById(\'fg1-'.$id.'_'.$dim.'_'.$subdim.'\');
                fg.innerHTML = `<span class=\'text-danger\'>Code ya existe</span> <BR>` + fg.innerHTML;
                return false;
            } else {
                if (idnumbervalue && shortnamevalue) {
            sendPost(\'modal'.$id.'_'.$dim.'_'.$subdim.'\', \'mix='.$mix.'&id='.$id.'&modalcreatecomp='.$dim.'_'.$subdim.
            '&modalidnumber=\'+idnumbervalue+\'&modalshortname=\'+shortnamevalue+\'&modaldescription=\'+
            descriptionvalue+\'&modalcomptype=\'+comptype+\''.$extra.'\', \'mainform0\');
                }
            }return false;
                                ">'.get_string('create').'</button>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>'.get_string('choiceoutcome', 'block_evalcomix').'</h6>
                        <div id="outbox" class="border p-1 mb-1 rounded clearfix">';
        if (isset($this->outcome[$id][$dim][$subdim])) {
            foreach ($this->outcome[$id][$dim][$subdim] as $out => $outcome) {
                $onclick = 'sendPost(\'modal'.$id.'_'.$dim.'_'.$subdim.'\', \'mix='.$mix.'&id='.$id.$extra.
                           '&modalDelOut='.$dim.'_'.$subdim.'_'.$out.'\', \'mainform0\');';
                $this->display_competency($out, $onclick);
            }
        }
        echo '
                        </div>
                        <select class="form-control overflow-auto" id="selcomp" size="10" title="'.
                                get_string('choiceoutcome', 'block_evalcomix').'" data-live-search="true"
                            onchange="
                            sendPost(\'modal'.$id.'_'.$dim.'_'.$subdim.'\', \'mix='.$mix.'&id='.$id.$extra.
                                    '&modalOutSel=\'+this.options[this.selectedIndex].text+\'&modalAddOut='.
                            $dim.'_'.$subdim.'_\'+this.value, \'mainform0\');
                        ">';
        if ($courseoutcomes = block_evalcomix_editor_tool::get_course_outcomes()) {
            foreach ($courseoutcomes as $item) {
                if (isset($this->outcome[$id][$dim][$subdim])
                        && array_key_exists($item->idnumber, $this->outcome[$id][$dim][$subdim])) {
                    continue;
                }
                echo '<option data-subtext="'.$item->shortname.'" value="'.$item->idnumber.'">('.$item->idnumber.
                ') '.$item->shortname.'</option>';
            }
        }
        echo '
                        </select>
                        <div class="border px-1 pt-1 mt-3 rounded clearfix">
                            <h6>'.get_string('newoutcome', 'block_evalcomix').'
                            <a href=# class="btn rounded-circle text-white px-1 py-1"
                            style="background-color:#0C4A15;line-height:1" onclick="
                            var b = document.getElementById(\'newout'.$id.'_'.$dim.'_'.$subdim.'\').style.display;
                            if (b == \'block\') {
                                document.getElementById(\'newout'.$id.'_'.$dim.'_'.$subdim.'\').style.display = \'none\';
                            } else {
                                document.getElementById(\'newout'.$id.'_'.$dim.'_'.$subdim.'\').style.display = \'block\';
                            }
                            ">></a>
                            </h6>
                            <div id="newout'.$id.'_'.$dim.'_'.$subdim.'" style="display:none">
                                <div class="form-group mb-2" id="ofg1-'.$id.'_'.$dim.'_'.$subdim.'">
                                    <label for="outidnumber'.$id.'_'.$dim.'_'.$subdim.'" class="mb-0">'.
                                    get_string('compidnumber', 'block_evalcomix').'</label>
                                    <input type="text" class="w-100" id="outidnumber'.$id.'_'.$dim.'_'.$subdim.'"
                                    name="outidnumber'.$id.'_'.$dim.'_'.$subdim.'" placeholder="'.
                                    get_string('compidnumber', 'block_evalcomix').'">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="outshortname'.$id.'_'.$dim.'_'.$subdim.'" class="mb-0">'.
                                    get_string('compshortname', 'block_evalcomix').'</label>
                                    <input type="text" class="w-100" id="outshortname'.$id.'_'.$dim.'_'.$subdim.'"
                                    name="outshortname'.$id.'_'.$dim.'_'.$subdim.'" placeholder="'.
                                    get_string('compshortname', 'block_evalcomix').'">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="outdescription'.$id.'_'.$dim.'_'.$subdim.'" class="mb-0">'.
                                    get_string('compdescription', 'block_evalcomix').'</label>
                                    <textarea class="w-100" id="outdescription'.$id.'_'.$dim.'_'.$subdim.'"
                                    name="outdescription'.$id.'_'.$dim.'_'.$subdim.'"></textarea>
                                </div>
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-default" onclick="
                                    var idnumber = document.getElementById(\'outidnumber'.$id.'_'.$dim.'_'.$subdim.'\');
                                    var shortname = document.getElementById(\'outshortname'.
                                    $id.'_'.$dim.'_'.$subdim.'\');
                                    var descriptionvalue = document.getElementById(\'outdescription'.
                                    $id.'_'.$dim.'_'.$subdim.'\').value.trim();
                                    var idnumbervalue = idnumber.value.trim();
                                    var shortnamevalue = shortname.value.trim();
                                    if (!idnumbervalue) {
                                        idnumber.style.border=\'1px solid #FF0000\';
                                        return false;
                                    }
                                    if (!shortnamevalue) {
                                        shortname.style.border=\'1px solid #FF0000\';
                                        return false;
                                    }
                                    const courseoutcomes = [];';
        if (isset($courseoutcomes)) {
            $i = 0;
            foreach ($courseoutcomes as $co) {
                echo 'courseoutcomes['.$i.'] = \''. strtolower($co->idnumber).'\';';
                ++$i;
            }
        }
        echo '
            var error = false;
            var length = courseoutcomes.length;
            for (var i = 0; i < length; i++) {
                if (courseoutcomes[i] == idnumbervalue.toLowerCase()) {
                    error = true;
                }
            }
            if (error == true) {
                idnumber.style.border=\'1px solid #FF0000\';
                var fg = document.getElementById(\'ofg1-'.$id.'_'.$dim.'_'.$subdim.'\');
                fg.innerHTML = `<span class=\'text-danger\'>Code ya existe</span> <BR>` + fg.innerHTML;
            } else {
                if (idnumbervalue && shortnamevalue) {
            sendPost(\'modal'.$id.'_'.$dim.'_'.$subdim.'\', \'mix='.$mix.'&id='.$id.'&modalcreateout='.$dim.'_'.$subdim.
            '&modalidnumber=\'+idnumbervalue+\'&modaldescription=\'+
            descriptionvalue+\'&modalshortname=\'+shortnamevalue+\''.$extra.'\', \'mainform0\');
                }
            }return false;
                                ">'.get_string('create').'</button>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
        ';
    }

    public function display_competency($competency, $onclick = '') {
        echo '
            <div class="float-md-left pl-2 d-inline-block border border-secondary w-auto">'.
                $competency.
                ' <button type="button" class="btn btn-secondary btn-sm border py-0" onclick="'.$onclick.'
                ">x</button></div>';
    }

    public function get_subdimensionid_from_xml($toolid) {
        global $CFG;
        $result = array();
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
        if ($xmlstring = block_evalcomix_webservice_client::get_tool($toolid)) {
            $id = $this->id;

            $xml = simplexml_load_string($xmlstring);
            foreach ($xml->Dimension as $dimen) {
                $dim = key($this->dimension[$id]);
                if ($dimdata = current($this->dimension[$id])) {
                    next($this->dimension[$id]);
                }
                foreach ($dimen->Subdimension as $subdimen) {
                    $subdim = key($this->subdimension[$id][$dim]);
                    if ($subdata = current($this->subdimension[$id][$dim])) {
                        next($this->subdimension[$id][$dim]);
                    }
                    $result[$id][$dim][$subdim] = (string)$subdimen['id'];
                }
            }
        }
        return $result;
    }

    public function get_competency_string($id, $dim, $subdim) {
        $idnumbers = array();
        if (isset($this->competency[$id][$dim][$subdim])) {
            $idnumbers = array_keys($this->competency[$id][$dim][$subdim]);
        }
        if (isset($this->outcome[$id][$dim][$subdim])) {
            $idnumbersout = array_keys($this->outcome[$id][$dim][$subdim]);
            $idnumbers = array_merge($idnumbers, $idnumbersout);
        }
        if (!empty($idnumbers)) {
            return '('.implode(', ', $idnumbers).')';
        }
        return '';
    }
}
