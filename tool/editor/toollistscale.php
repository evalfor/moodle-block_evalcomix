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

class block_evalcomix_editor_toollistscale extends block_evalcomix_editor {
    private $titulo;
    private $valtotal;
    private $numtotal;
    private $valorestotal;
    private $valtotalpor;
    private $valglobal;
    private $valglobalpor;
    private $filediccionario;
    private $dimpor;
    public $valoreslista;
    private $porcentage;
    private $observation;
    private $view;
    private $commentatr;
    private $commentdim;
    private $valuecommentatr;
    private $valuecommentdim;
    private $dimensionsid;
    private $atributosid;
    private $valoresid;
    private $valorestotalesid;
    private $valoreslistaid;
    private $comment;

    public function get_tool($id) {
    }

    public function get_titulo() {
        return $this->titulo;
    }

    public function get_valtotal() {
        return $this->valtotal[$this->id];
    }

    public function get_numtotal($id=0) {
        if (isset($this->numtotal[$this->id])) {
            return $this->numtotal[$this->id];
        }
    }

    public function get_valtotalpor() {
        return $this->valtotalpor[$this->id];
    }

    public function get_valorestotal($id=0) {
        if (isset($this->valorestotal[$this->id])) {
            return $this->valorestotal[$this->id];
        }
    }

    public function get_valglobal() {
        if (isset($this->valglobal[$this->id])) {
            return $this->valglobal[$this->id];
        }
    }

    public function get_valglobalpor() {
        if (isset($this->valglobalpor[$this->id])) {
            return $this->valglobalpor[$this->id];
        }
    }

    public function get_dimpor() {
        return $this->dimpor[$this->id];
    }

    public function get_commentatr($id = 0) {
        return $this->commentatr[$this->id];
    }

    public function get_commentdim($id = 0) {
        return $this->commentdim[$this->id];
    }

    public function get_porcentage() {
        return $this->porcentage;
    }

    public function get_dimensionsid() {
        return $this->dimensionsid[$this->id];
    }

    public function get_atributosid() {
        return $this->atributosid[$this->id];
    }

    public function get_valoresid() {
        return $this->valoresid[$this->id];
    }

    public function get_valorestotalesid() {
        if (isset($this->valorestotalesid[$this->id])) {
            return $this->valorestotalesid[$this->id];
        }
        return null;
    }

    public function get_valoreslistaid() {
        return $this->valoreslistaid[$this->id];
    }

    public function get_valoreslista() {
        return $this->valoreslista[$this->id];
    }

    // Set methods.
    public function set_titulo($titulo) {
        $this->titulo = $titulo;
    }

    public function set_valtotal($valtotal) {
        $this->valtotal[$this->id] = $valtotal;
    }

    public function set_numtotal($numtotal) {
        $this->numtotal[$this->id] = $numtotal;
    }

    public function set_valtotalpor($valtotalpor) {
        $this->valtotalpor[$this->id] = $valtotalpor;
    }

    public function set_valorestotal($valorestotal) {
        $this->valorestotal[$this->id] = $valorestotal;
    }

    public function set_valglobal($valglobal) {
        $this->valglobal[$this->id] = $valglobal;
    }

    public function set_valglobalpor($valglobalpor, $id=0) {
        $this->valglobalpor[$this->id] = $valglobalpor;
    }

    public function set_dimpor($dimpor, $id=0) {
        $this->dimpor[$this->id] = $dimpor;
    }

    public function set_view($view, $id='') {
        $this->view = $view;
    }

    public function set_commentatr($comment) {
        $this->commentatr[$this->id] = $comment;
    }

    public function set_commentdim($comment) {
        $this->commentdim[$this->id] = $comment;
    }

    public function set_dimensionsid($dimensionsid, $id = '') {
        $this->dimensionsid[$this->id] = $dimensionsid;
    }

    public function set_atributosid($atributosid, $id = '') {
        $this->atributosid[$this->id] = $atributosid;
    }

    public function set_valoresid($valoresid, $id = '') {
        $this->valoresid[$this->id] = $valoresid;
    }

    public function set_valorestotalesid($valoresid, $id = '') {
        $this->valorestotalesid[$this->id] = $valoresid;
    }

    public function set_valoreslistaid($valoresid, $id = '') {
        $this->valoreslistaid[$this->id] = $valoresid;
    }

    public function __construct ($lang='es_utf8', $titulo = '', $dimension = array(), $numdim = 1, $subdimension = array(),
            $numsubdim = 1, $atributo = array(), $numatr = 1, $valores = array(), $numvalores = 2, $valtotal = array(),
            $numtotal = 0, $valorestotal = array(), $valglobal = false, $valglobalpor = array(), $dimpor = array(),
            $subdimpor = array(), $atribpor = array(), $commentatr = array(), $commentdim = array(), $id=0, $observation = '',
            $porcentage = 0, $valtotalpor = array(), $valueattribute = array(), $valueglobaldim = array(),
            $valuetotalvalue = '', $valuecommentatr = '', $valuecommentdim = '', $params = array()) {

        $params['id'] = $id;
        $params['dimension'] = $dimension;
        $params['subdimension'] = $subdimension;
        $params['numsubdim'] = $numsubdim;
        $params['subdimpor'] = $subdimpor;
        $params['atributo'] = $atributo;
        $params['numatr'] = $numatr;
        $params['numdim'] = $numdim;
        $params['numvalores'] = $numvalores;
        $params['valores'] = $valores;
        $params['atribpor'] = $atribpor;
        parent::__construct($params);

        $this->filediccionario = 'lang/'.$lang.'/evalcomix.php';
        $this->titulo = $titulo;
        $this->valtotal = $valtotal;
        $this->numtotal = $numtotal;
        $this->valorestotal = $valorestotal;
        $this->valglobal = $valglobal;
        $this->valglobalpor = $valglobalpor;
        $this->dimpor = $dimpor;
        $this->observation = (is_array($observation)) ? $observation : array($id => $observation);
        $this->porcentage = $porcentage;
        $this->valtotalpor = $valtotalpor;
        $this->view = 'design';
        foreach ($this->subdimension[$this->id] as $dim => $value) {
            $this->valoreslista[$this->id][$dim][0]['nombre'] = get_string('no', 'block_evalcomix');
            $this->valoreslista[$this->id][$dim][1]['nombre'] = get_string('yes', 'block_evalcomix');
        }
        $this->commentatr = $commentatr;
        $this->commentdim = $commentdim;
        $this->valueattribute = $valueattribute;
        $this->valueglobaldim = $valueglobaldim;
        $this->valuetotalvalue = $valuetotalvalue;
        $this->valuecommentatr = $valuecommentatr;
        $this->valuecommentdim = $valuecommentdim;
        if (!empty($params['dimensionsid'])) {
            $this->dimensionsid = $params['dimensionsid'];
        }
        if (!empty($params['atributosid'])) {
            $this->atributosid = $params['atributosid'];
        }
        if (!empty($params['valoresid'])) {
            $this->valoresid = $params['valoresid'];
        }
        if (!empty($params['valorestotalesid'])) {
            $this->valorestotalesid = $params['valorestotalesid'];
        }
        if (!empty($params['valoreslistaid'])) {
            $this->valoreslistaid = $params['valoreslistaid'];
        }
        if (!empty($params['valoreslista'])) {
            $this->valoreslista = $params['valoreslista'];
        }
    }

    public function add_dimension($dim, $key) {
        $id = $this->id;
        $dimen;
        $this->numdim[$id] += 1;
        if (!isset($dim)) {
            $dim = $key;
            $dimen = $dim;
            $key++;
            $this->dimension[$id][$dim]['nombre'] = get_string('titledim', 'block_evalcomix').$this->numdim[$id];
        } else {
            $newindex = $key;
            $dimen = $newindex;
            $elem['nombre'] = get_string('titledim', 'block_evalcomix').$this->numdim[$id];
            $this->dimension[$id] = $this->array_add($this->dimension[$id], $dim, $elem, $newindex);
            $this->commentdim[$id][$dim] = 'hidden';
        }

        $subdim = $key;
        $key++;
        $this->numatr[$id][$dimen][$subdim] = 1;
        $this->numsubdim[$id][$dimen] = 1;
        $this->atributo[$id][$dimen][$subdim][0]['nombre'] = get_string('titleatrib', 'block_evalcomix').
            $this->numatr[$id][$dimen][$subdim];
        $this->atribpor[$id][$dimen][$subdim][0] = 100;
        $this->subdimension[$id][$dimen][$subdim]['nombre'] = get_string('titlesubdim', 'block_evalcomix').
            $this->numsubdim[$id][$dimen];
        $this->subdimpor[$id][$dimen][$subdim] = 100;
        $this->valores[$id][$dimen][0]['nombre'] = get_string('titlevalue', 'block_evalcomix').'1';
        $this->valores[$id][$dimen][1]['nombre'] = get_string('titlevalue', 'block_evalcomix').'2';
        $this->valoreslista[$id][$dimen][0]['nombre'] = 'No';
        $this->valoreslista[$id][$dimen][1]['nombre'] = 'Yes';
        $this->numvalores[$id][$dimen] = 2;
        $this->competency[$id][$dim][$subdim] = array();
        $this->outcome[$id][$dim][$subdim] = array();
    }

    public function add_attribute($dim, $subdim, $atrib, $key) {
        $id = $this->id;
        $this->numatr[$id][$dim][$subdim]++;

        if (!isset($atrib)) {
            $atrib = $key;
            $this->atributo[$id][$dim][$subdim][$atrib]['nombre'] = get_string('titleatrib', 'block_evalcomix').
                $this->numatr[$id][$dim][$subdim];
        } else {
            $newindex = $key;
            $elem['nombre'] = get_string('titleatrib', 'block_evalcomix').$this->numatr[$id][$dim][$subdim];
            $this->atributo[$id][$dim][$subdim] = $this->array_add($this->atributo[$id][$dim][$subdim], $atrib, $elem, $newindex);
            $this->commentatr[$id][$dim][$subdim][$newindex] = 'hidden';
        }
    }

    public function add_values($dim, $key) {
        $id = $this->id;
        $this->numvalores[$id][$dim]++;
        $this->valores[$id][$dim][$key]['nombre'] = get_string('titlevalue', 'block_evalcomix').$this->numvalores[$id][$dim];
    }

    public function add_total_values($key) {
        $id = $this->id;
        if (isset($this->numtotal[$id])) {
            $this->numtotal[$id]++;
        } else {
            $this->numtotal[$id] = 1;
        }
        $this->valorestotal[$id][$key]['nombre'] = get_string('titlevalue', 'block_evalcomix').$this->numtotal[$id];
    }

    public function remove_total_values($grado) {
        $id = $this->id;
        if ($this->numtotal[$id] > 2) {
            $this->numtotal[$id]--;
            $this->valorestotal[$id] = $this->array_remove($this->valorestotal[$id], $grado);
        }
    }

    public function remove_values($dim, $grado) {
        $id = $this->id;
        if ($this->numvalores[$id][$dim] > 2) {
            $this->numvalores[$id][$dim]--;
            $this->valores[$id][$dim] = $this->array_remove($this->valores[$id][$dim], $grado);
        }
    }

    public function display_body($data, $mix='', $porcentage='') {
        if ($porcentage != '') {
            $this->porcentage = $porcentage;
        }
        if (isset($data['titulo'.$this->id])) {
            $this->titulo = stripslashes($data['titulo'.$this->id]);
        }
        if (isset($data['valtotal'.$this->id])) {
            $this->valtotal[$this->id] = stripslashes($data['valtotal'.$this->id]);
        }
        $id = $this->id;
        if (isset($data['numvalores'.$id.'']) && $data['numvalores'.$id.''] >= 2) {
            $this->numtotal[$this->id] = stripslashes($data['numvalores'.$this->id]);
        }
        $checked = '';
        $disabled = 'disabled';
        if (isset($this->valtotal[$this->id]) && ($this->valtotal[$this->id] == 'true' || $this->valtotal[$this->id] == 't')) {
            $checked = 'checked';
            $disabled = '';
        }

        if ($this->view == 'view' && !is_numeric($mix)) {
            echo '<input type="button" style="width:10em" value="'.get_string('view', 'block_evalcomix').'"
            onclick=\'javascript:location.href="generator.php?op=design&courseid='.$data['courseid'].'"\'><br>';
        }
        $numdimen = count($this->dimension);

        $id = $this->id;
        $dim = null;
        $subdim = null;
        $atrib = null;

        if (isset($this->numdim[$this->id])) {
            $thisnumdimid = $this->numdim[$this->id];
        } else {
            $thisnumdimid = null;
        }

        if (isset($this->numtotal[$id])) {
            $thisnumtotalid = $this->numtotal[$id];
        } else {
            $thisnumtotalid = null;
        }

        echo '
        <div id="cuerpo'.$id.'"  class="cuerpo">
            <br>
            <label for="titulo'.$id.'" style="margin-left:1em">'.get_string('listrate', 'block_evalcomix').
            ':</label><span class="labelcampo">
                <textarea class="width" id="titulo'.$id.'" name="titulo'.$id.'">'.$this->titulo.'</textarea></span>
        ';
        if ($this->view == 'design') {
            echo '
            <label for="numdimensiones'.$id.'">'.get_string('numdimensions', 'block_evalcomix').'</label>
            <span class="labelcampo">
                <input type="text" id="numdimensiones'.$id.'" name="numdimensiones'.$id.'" value="'.$thisnumdimid .'"
                maxlength=2 onkeypress=\'javascript:return validar(event);\'/>
            </span>
            <label for="valtotal'.$id.'">'.get_string('totalvalue', 'block_evalcomix').'</label>
            <input type="checkbox" id="valtotal'.$id.'" name="valtotal'.$id.'" '.$checked.'
            onclick="javascript:if (this.checked)document.getElementById(\'numvalores'.$id.'\').disabled=false;
            else document.getElementById(\'numvalores'.$id.'\').disabled=true;"/>

            <label for="numvalores'.$id.'">'.get_string('numvalues', 'block_evalcomix').'</label>
            <span class="labelcampo">
                <input type="text" id="numvalores'.$id.'" name="numvalores'.$id.'" '.$disabled.' value="'.$thisnumtotalid.'"
                maxlength=2 onkeypress=\'javascript:return validar(event);\'/>
            </span>
            <input class="flecha" type="button" id="addDim" onclick=\'javascript:
            if (!validarEntero(document.getElementById("numdimensiones'.$id.'").value)
                || (document.getElementById("valtotal'.$id.'").checked
                && !validarEntero(document.getElementById("numvalores'.$id.'").value))) {
                    alert("' . get_string('ADimension', 'block_evalcomix') . '"); return false;}
                    sendPost("cuerpo'.$id.'", "mix='.$mix.'&id='.$id.
                    '&addDim=1&numvalores="+document.getElementById("numvalores'.$id.'").value + "&titulo'.$id.
                    '="+document.getElementById("titulo'.$id.
                    '").value+"&numdimensiones="+ document.getElementById("numdimensiones'.$id.
                    '").value +"", "mainform0");\' name="addDim" value=""/>
            ';
        }

        if (isset($mix) && is_numeric($mix)) {
            echo '
            <span class="labelcampo">
                <label for="toolpor_'.$id.'">'.get_string('porvalue', 'block_evalcomix').'</label>
                <input class="porcentaje" type="text" name="toolpor_'.$id.'" id="toolpor_'.$id.'"
                value="'.$this->porcentage.'" onchange=\'document.getElementById("sumpor").value += this.id + "-";\'
                onkeyup=\'javascript:if (document.getElementById("toolpor_'.$id.'").value > 100)
                    document.getElementById("toolpor_'.$id.'").value = 100;\'
                onkeypress=\'javascript:return validar(event);\'/></span>
                <input class="botonporcentaje" type="button" onclick=\'javascript:sendPost("body", "id='.$id.
                '&toolpor_'.$id.'="+document.getElementById("toolpor_'.$id.'").value+"&addtool'.$id.'=1", "mainform0");\'>
            </span>';
        }

        echo '<br/>';

        flush();

        foreach ($this->dimension[$id] as $dim => $value) {
            echo '<div class="dimension" id="dimensiontag'.$id.'_'.$dim.'">';
            $this->display_dimension($dim, $data, $id, $mix);
            echo '</div>';
        }

        if (isset($this->valtotal[$this->id]) && ($this->valtotal[$this->id] == 'true' || $this->valtotal[$this->id] == 't')) {
            echo '
                <div class="valoraciontotal">
            ';
            if ($this->view == 'design') {
                echo '
                    <input type="button" class="delete"
                    onclick=\'javascript:document.getElementById("valtotal'.$id.'").checked=false;sendPost("cuerpo'.$id.
                    '", "mix='.$mix.'&id='.$id.'&addDim=1&valtotal=false", "mainform0");\'>
                ';
            }
            echo '
                    <div class="margin">
                        <label for="numdimensiones">'.strtoupper(get_string('totalvalue', 'block_evalcomix')).':</label>
                        <span class="labelcampo"></span>
                        <label for="numvalorestotal">'.get_string('numvalues', 'block_evalcomix').'</label>
                        <span class="labelcampo">
                        <input type="text" id="numvalores_'.$id.'" name="numvalores'.$id.'" value="'.$this->numtotal[$id].'"
                        maxlength=2 onkeyup=\'javascript:var valores=document.getElementsByName("numvalores'.$id.'");
                        for (var i=0; i<valores.length; i++) {valores[i].value=this.value;}\'
                        onkeypress=\'javascript:return validar(event);\'/>
                        <input class="flecha" type="button" id="addDim" onclick=\'javascript:
                        if (!validarEntero(document.getElementById("numvalores_'.$id.'").value)) {
                            alert("' . get_string('ATotal', 'block_evalcomix') . '"); return false;}sendPost("cuerpo'.$id.
                            '", "mix='.$mix.'&id='.$id.'&addDim=1&numvalores="+document.getElementById("numvalores_'.$id.
                            '").value + "", "mainform0");\' name="addDim" value=""/>
                        <span class="labelcampo"><label for="dimpor">'.get_string('porvalue', 'block_evalcomix').
                        ':</label><span class="labelcampo">
                        <input class="porcentaje" type="text" name="valtotalpor'.$id.'" id="valtotalpor'.$id.'"
                        value="'.$this->valtotalpor[$id].'" onchange=\'document.getElementById("sumpor3'.$id.
                        '").value += this.id + "-";\' onkeyup=\'javascript:if (document.getElementById("valtotalpor'.$id.
                        '").value > 100)document.getElementById("valtotalpor'.$id.'").value = 100;\'
                        onkeypress=\'javascript:return validar(event);\'/></span>
                        <input class="botonporcentaje" type="button" onclick=\'javascript:sendPost("cuerpo'.$id.
                        '", "mix='.$mix.'&id='.$id.'&dimpor'.$id.'="+document.getElementById("valtotalpor'.$id.
                        '").value+"&dpi=vt&addDim=1", "mainform0");\'></span>

                        <table class="maintable">
            ';

            if (isset($this->valorestotal[$id])) {
                foreach ($this->valorestotal[$id] as $grado => $elemvalue) {
                    if (isset($data['valor'.$id.'_'.$grado])) {
                        $this->valorestotal[$id][$grado]['nombre'] = stripslashes($data['valor'.$id.'_'.$grado]);
                    }
                    echo '<th class="grado"><input class="valores" type="text" id="valor'.$id.'_'.$grado.'"
                    name="valor'.$id.'_'.$grado.'" value="'.
                    htmlspecialchars($this->valorestotal[$id][$grado]['nombre'], ENT_QUOTES).
                    '"/></th>
                    ';
                }
            }
            echo '<tr>';
            foreach ($this->valorestotal[$id] as $grado => $elemvalue) {
                echo '<td><input type="radio" name="radio'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'" /></td>';
            }
            echo '</tr></table>
                </div>
            </div>';
        }
        if (!is_numeric($mix)) {
            if (isset($data['observation'.$id]) && trim($data['observation'.$id]) != '') {
                $this->observation[$id] = stripslashes($data['observation'.$id]);
            } else if (isset($data['save']) && $data['save'] == '1') {
                $this->observation[$id] = '';
            }

            $thisobservationid = '';
            if (isset($this->observation[$id])) {
                $thisobservationid = $this->observation[$id];
            }

            echo '
            <div id="comentario">
                <div id="marco">
                    <label for="observation'.$id.'">' . get_string('observation', 'block_evalcomix'). ':</label>
                    <textarea id="observation'.$id.'" style="width:100%" rows="4" cols="200">' . $thisobservationid . '</textarea>
                </div>
            </div>
            ';
        }
        echo '<input type="hidden" id="sumpor3'.$id.'" value=""/>
            <input type="hidden" name="courseid" id="courseid" value="'.$data['courseid'].'">
        </div>
        ';

        flush();
    }

    public function display_dimension($dim, $data, $id=0, $mix='') {
        $id = $this->id;

        if (isset($data['dimension'.$id.'_'.$dim])) {
            $this->dimension[$id][$dim]['nombre'] = stripslashes($data['dimension'.$id.'_'.$dim]);
        }
        if (isset($data['numvalores'.$id.'_'.$dim]) && $data['numvalores'.$id.'_'.$dim] > 1) {
            $this->numvalores[$id][$dim] = stripslashes($data['numvalores'.$id.'_'.$dim]);
        }
        if (isset($data['valglobal'.$id.'_'.$dim])) {
            $this->valglobal[$id][$dim] = stripslashes($data['valglobal'.$id.'_'.$dim]);
        }
        $checked = '';
        if (isset($this->valglobal[$id][$dim]) && $this->valglobal[$id][$dim] == "true") {
            $globalchecked = 'checked';
        } else {
            $globalchecked = 'null';
        }

        if (isset($this->numtotal[$id])) {
            $thisnumtotalid = $this->numtotal[$id];
        } else {
            $thisnumtotalid = null;
        }

        if ($this->view == 'design') {
            echo '
            <div>
                <input type="button" class="delete"
                onclick=\'javascript:sendPost("cuerpo'.$id.'","mix='.$mix.'&id='.$id.'&titulo'.$id.
                '="+document.getElementById("titulo'.$id.'").value+"&addDim=1&numvalores='.$thisnumtotalid.'&dd='.$dim.
                '", "mainform0");\'>
                <input type="button" class="up" onclick=\'javascript:sendPost("cuerpo'.$id.'","mix='.$mix.'&id='.$id.
                '&titulo'.$id.'="+document.getElementById("titulo'.$id.'").value+"&moveDim=1&numvalores='.$thisnumtotalid.
                '&dUp='.$dim.'", "mainform0");\'>
            <br>
            </div>
            ';
        }

        echo '
            <input type="hidden" id="sumpor2'.$id.'_'.$dim.'" value=""/>
            <div class="margin">
                <label for="dimension'.$id.'_'.$dim.'">'.get_string('dimension', 'block_evalcomix').'</label>
                <span class="labelcampo">
                    <textarea class="width" id="dimension'.$id.'_'.$dim.'" name="dimension'.$id.'_'.$dim.'">'.
                    $this->dimension[$id][$dim]['nombre'] .'</textarea></span>
        ';
        if ($this->view == 'design') {
            echo '
            <label for="numsubdimensiones'.$id.'_'.$dim.'">'.get_string('numsubdimension', 'block_evalcomix').'</label>
                <span class="labelcampo"><input type="text" id="numsubdimensiones'.$id.'_'.$dim.'"
                name="numsubdimensiones'.$id.'_'.$dim.'" value="'.$this->numsubdim[$id][$dim].'" maxlength=2
                onkeypress=\'javascript:return validar(event);\'/></span>

                <label for="numvalores'.$id.'_'.$dim.'">'.get_string('numvalues', 'block_evalcomix').'</label>
                <span class="labelcampo"><input type="text" id="numvalores'.$id.'_'.$dim.'" name="numvalores'.$id.'_'.$dim.'"
                value="'.$this->numvalores[$id][$dim].'" maxlength=2 onkeypress=\'javascript:return validar(event);\'/></span>
                <label for="valtotal'.$id.'">'.get_string('totalvalue', 'block_evalcomix').'</label>
                <input type="checkbox" id="valglobal'.$id.'_'.$dim.'" name="valglobal'.$id.'_'.$dim.'" '.$globalchecked.' />

                <input class="flecha" type="button" id="addSubDim'.$id.'" name="addSubDim'.$id.'"
                onclick=\'javascript:if (!validarEntero(document.getElementById("numvalores'.$id.'_'.$dim.'").value)
                    || !validarEntero(document.getElementById("numsubdimensiones'.$id.'_'.$dim.'").value)) {
                        alert("' . get_string('ASubdimension', 'block_evalcomix') . '"); return false;}
                        sendPost("dimensiontag'.$id.'_'.$dim.'", "mix='.$mix.'&id='.$id.
                        '&addSubDim="+this.value+"&numvalores'.$id.'_'.$dim.'="+ document.getElementById("numvalores'.
                        $id.'_'.$dim.'").value +"&numsubdimensiones'.$id.'_'.$dim.
                        '="+ document.getElementById("numsubdimensiones'.$id.'_'.$dim.'").value +"", "mainform0");\'
                        style="font-size:1px" value="'.$dim.'"/>
            ';
        }
        echo '
            <span class="labelcampo"><label for="dimpor'.$dim.'">'.
            get_string('porvalue', 'block_evalcomix').'</label><span class="labelcampo">
            <input class="porcentaje" type="text" maxlength="3" name="dimpor'.$id.'_'.$dim.'"
            id="dimpor'.$id.'_'.$dim.'" onchange=\'javascript:document.getElementById("sumpor3'.$id.
            '").value += this.id +"-";;\' value="'.$this->dimpor[$id][$dim].'" onkeyup=\'javascript:
            if (document.getElementById("dimpor'.$id.'_'.$dim.'").value > 100)document.getElementById("dimpor'.
            $id.'_'.$dim.'").value = 100;\' onkeypress=\'javascript:return validar(event);\'/></span>
            <input class="botonporcentaje" type="button" onclick=\'javascript:sendPost("cuerpo'.$id.'", "mix='.
            $mix.'&id='.$id.'&dimpor'.$id.'="+document.getElementById("dimpor'.$id.'_'.$dim.'").value+"&dpi='.
            $dim.'&addDim=1", "mainform0");\'></span>';

        if (isset($this->subdimension[$id][$dim])) {
            foreach ($this->subdimension[$id][$dim] as $subdim => $elemsubdim) {
                echo '
                    <div class="subdimension" id="subdimensiontag'.$id.'_'.$dim.'_'.$subdim.'">
                ';
                $this->display_subdimension($dim, $subdim, $data, $id, $mix);
                echo '</div>
                ';
            }
        }
        if (isset($this->valglobal[$id][$dim]) && $this->valglobal[$id][$dim] == "true") {
            echo '
                <div class="valoracionglobal">
            ';
            if ($this->view == 'design') {
                echo '
                    <input type="button" class="delete"
                    onclick=\'javascript:document.getElementById("valglobal'.$id.'_'.$dim.'").checked=false;
                    sendPost("dimensiontag'.$id.'_'.$dim.'", "mix='.$mix.'&id='.$id.'&addSubDim='.$dim.'&valglobal'.
                    $dim.'=false", "mainform0", "mainform0", "mainform0");\'>
                ';
            }

            echo '
                    <div class="margin">
                        <label>'.get_string('globalvalue', 'block_evalcomix').'</label>
                        <span class="labelcampo"></span>
                        <span class="labelcampo"><label for="name="valglobalpor'.$id.'_'.$dim.'">'.
                        get_string('porvalue', 'block_evalcomix').'</label><span class="labelcampo">
                        <input class="porcentaje" type="text" maxlength="3"  name="valglobalpor'.$id.'_'.$dim.'"
                        id="valglobalpor'.$id.'_'.$dim.'" value="'.$this->valglobalpor[$id][$dim].'"
                        onchange=\'document.getElementById("sumpor2'.$id.'_'.$dim.'").value += this.id + "-";\'
                        onkeyup=\'javascript:if (document.getElementById("valglobalpor'.$id.'_'.$dim.'").value > 100)
                            document.getElementById("valglobalpor'.$id.'_'.$dim.'").value = 100;\'
                        onkeypress=\'javascript:return validar(event);\'/></span>
                        <input class="botonporcentaje" type="button" onclick=\'javascript:sendPost("dimensiontag'.
                        $id.'_'.$dim.'", "mix='.$mix.'&id='.$id.'&subdimpor="+document.getElementById("valglobalpor'.
                        $id.'_'.$dim.'").value+"&spi=vg&addSubDim='.$dim.'", "mainform0");\'></span>

                        <table class="maintable">
            ';
            foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                if (isset($data['valor'.$id.'_'.$dim.'_'.$grado])) {
                    $this->valores[$id][$dim][$grado]['nombre'] = stripslashes($data['valor'.$id.'_'.$dim.'_'.$grado]);
                }
                echo '<th class="grado"><input class="valores" type="text" id="valor'.$id.'_'.$dim.'_'.$grado.'"
                name="valor'.$id.'_'.$dim.'_'.$grado.'"
                value="'.htmlspecialchars($this->valores[$id][$dim][$grado]['nombre'], ENT_QUOTES).'"
                onkeyup=\'javascript:unificarValores("valor'.$id.'_'.$dim.'_'.$grado.'", this);\'/></th>
                ';
            }
            echo '<tr>';
            foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                echo '<td><input type="radio" name="radio'.$id.'_'.$dim.'_'.$subdim.'" /></td>
                ';
            }
            echo '</tr>';

            $visible = null;
            if (isset($data['commentdim'.$id.'_'.$dim])) {
                $visible = stripslashes($data['commentdim'.$id.'_'.$dim]);
                $this->commentdim[$id][$dim] = $visible;
            }

            if (isset($this->commentdim[$id][$dim]) && $this->commentdim[$id][$dim] == 'visible') {
                $visible = 'visible';
                $novisible = 'hidden';
            } else {
                $novisible = 'visible';
                $visible = 'hidden';
            }

            echo '<tr>
                <td colspan="'.$this->numvalores[$id][$dim].'">';

            if ($this->view == 'design') {
                echo '
                <div>
                    <input type="button" class="showcomment" title="'. get_string('add_comments', 'block_evalcomix') .'"
                        onclick=\'javascript:sendPost("dimensiontag'.$id.'_'.$dim.'", "mix='.$mix.'&id='.$id.'&commentdim'.
                        $id.'_'.$dim.'='.$novisible.'&comDim='.$dim.'&addSubDim='.$dim.'", "mainform0");\'>
                </div>';
            }

            echo '
                </td></tr><tr>
                <td colspan="'.$this->numvalores[$id][$dim].'">';

            if ($visible == 'visible') {
                $divheight = 'height:2.5em';
                $textheight = 'height:2em';
            } else {
                $divheight = 'height:0em';
                $textheight = 'height:0em';
            }

            echo '<div class="atrcomment" id="atribcomment'.$id.'_'.$dim.'_'.$subdim.'" style="'.$divheight.'">
            <textarea disabled style="width:97%; visibility:'.$visible.'; '.$textheight.'"
            id="atributocomment'.$id.'_'.$dim.'_'.$subdim.'"></textarea>
                </div>
            </td></tr>
            ';

            echo '</table></div>
                </div>';
        }

        echo '</div>
        ';
        if ($this->view == 'design') {
            echo '<div>
                    <input type="button" class="add" onclick=\'javascript:sendPost("cuerpo'.$id.'","mix='.
                    $mix.'&id='.$id.'&titulo'.$id.'="+document.getElementById("titulo'.$id.
                    '").value+"&addDim=1&numvalores="+document.getElementById("numvalores'.$id.'").value + "&ad='.
                    $dim.'", "mainform0");\'>
                    <input type="button" class="down" onclick=\'javascript:sendPost("cuerpo'.$id.'","mix='.$mix.
                    '&mix='.$mix.'&id='.$id.'&titulo'.$id.'="+document.getElementById("titulo'.$id.
                    '").value+"&moveDim=1&numvalores="+document.getElementById("numvalores'.$id.'").value + "&dDown='.
                    $dim.'", "mainform0");\'>
                    <br>
                </div>
            ';
        }
        flush();
    }

    public function display_subdimension($dim, $subdim, $data, $id=0, $mix='') {
        $id = $this->id;

        if (isset($data['subdimension'.$id.'_'.$dim.'_'.$subdim]) && empty($data['modalclose'])) {
            $this->subdimension[$id][$dim][$subdim]['nombre'] = stripslashes($data['subdimension'.$id.'_'.$dim.'_'.$subdim]);
        }

        if ($this->view == 'design') {
            echo '
            <input type="hidden" id="sumpor'.$id.'_'.$dim.'_'.$subdim.'" value=""/>
            <div>
                <input type="button" class="delete" onclick=\'javascript:sendPost("dimensiontag'.$id.'_'.$dim.
                '", "mix='.$mix.'&id='.$id.'&addSubDim='.$dim.'&dS=1&sd='.$subdim.'", "mainform0");\'>
                <input type="button" class="up" onclick=\'javascript:sendPost("dimensiontag'.$id.'_'.$dim.
                '", "mix='.$mix.'&id='.$id.'&moveSub='.$dim.'&sUp='.$subdim.'", "mainform0");\'>
                <br><br>
            </div>
            ';
        }
        echo '
                <div class="margin">
                    <div class="float-md-left">
                        <label for="subdimension'.$id.'_'.$dim.'_'.$subdim.'">'.
                        get_string('subdimension', 'block_evalcomix').'</label>
                        <span class="labelcampo"><textarea  class="width" id="subdimension'.$id.'_'.$dim.'_'.$subdim.
                        '" name="subdimension'.$id.'_'.$dim.'_'.$subdim.'">'.
                        $this->subdimension[$id][$dim][$subdim]['nombre'].
                        '</textarea></span>
                    </div>
        ';
        if ($this->view == 'design') {
            echo '
                <div class="float-md-left">
                    <label for="numatributos'.$id.'_'.$dim.'_'.$subdim.'">'.get_string('numattributes', 'block_evalcomix').
                    '</label>
                    <span class="labelcampo"><input type="text" id="numatributos'.$id.'_'.$dim.'_'.$subdim.'"
                    name="numatributos'.$id.'_'.$dim.'_'.$subdim.'" value="'.$this->numatr[$id][$dim][$subdim].'"
                    maxlength=2 onkeypress=\'javascript:return validar(event);\'/></span>
                    <input class="flecha" type="button" id="addAtr" name="addAtr" style="font-size:1px"
                    onclick=\'javascript:if (!validarEntero(document.getElementById("numatributos'.
                    $id.'_'.$dim.'_'.$subdim.'").value)) {alert("' . get_string('AAttribute', 'block_evalcomix') .
                    '"); return false;}sendPost("subdimensiontag'.$id.'_'.$dim.'_'.$subdim.'", "mix='.$mix.'&id='.$id.
                    '&addAtr="+this.value+"&numatributos'.$id.'_'.$dim.'_'.$subdim.
                    '="+ document.getElementById("numatributos'.$id.'_'.$dim.'_'.$subdim.'").value +"", "mainform0");\'
                    value="'.$dim.'_'.$subdim.'"/>
                </div>
            ';
        }
        echo '
                <div class="float-md-left">
                    <span class="labelcampo"><label for="subdimpor'.$id.'_'.$dim.'_'.$subdim.'">'.
                    get_string('porvalue', 'block_evalcomix').'</label><span class="labelcampo">
                    <input class="porcentaje" type="text" maxlength="3" id="subdimpor'.$id.'_'.$dim.'_'.$subdim.'"
                    name="subdimpor'.$id.'_'.$dim.'_'.$subdim.'" value="'.$this->subdimpor[$id][$dim][$subdim].'"
                    onchange=\'document.getElementById("sumpor2'.$id.'_'.$dim.'").value += this.id + "-";\'
                    onkeyup=\'javascript:if (document.getElementById("subdimpor'.$id.'_'.$dim.'_'.$subdim.'").value > 100)
                        document.getElementById("subdimpor'.$id.'_'.$dim.'_'.$subdim.'").value = 100;\'
                    onkeypress=\'javascript:return validar(event);\'/></span>
                    <input class="botonporcentaje" type="button" onclick=\'javascript:sendPost("dimensiontag'.
                    $id.'_'.$dim.'", "mix='.$mix.'&id='.$id.'&subdimpor="+document.getElementById("subdimpor'.
                    $id.'_'.$dim.'_'.$subdim.'").value+"&spi='.$subdim.'&addSubDim='.$dim.'", "mainform0");\'>
                    </span>
                </div>
        ';

        $this->display_competencies($dim, $subdim, $mix);

        echo '
                <br>
                <br>
                    <table class="maintable">
                        <tr><th/><th/><th/>
                        <th style="text-align:right;"><span class="font">'.get_string('attribute', 'block_evalcomix').
                        '</span>  <span class="atributovalores" style="font-size:1em">/</span> <span class="atributovalores">'.
                        get_string('values', 'block_evalcomix').'</span></th>
                    ';
        foreach ($this->valoreslista[$id][$dim] as $grado => $elemvalue) {
            if (isset($data['valorlista'.$id.'_'.$dim.'_'.$grado])) {
                $this->valoreslista[$id][$dim][$grado]['nombre'] = stripslashes($data['valorlista'.$id.'_'.$dim.'_'.$grado]);
            }

            echo '<th class="grado"><input class="valores" style="background-color:#F7F9FA;width:7em"
            onkeyup=\'javascript:unificarValores("valorlista'.$id.'_'.$dim.'_'.$grado.'", this);\'
            type="text" id="valorlista'.$id.'_'.$dim.'_'.$grado.'" name="valorlista'.$id.'_'.$dim.'_'.$grado.'"
            value="'.htmlspecialchars($this->valoreslista[$id][$dim][$grado]['nombre'], ENT_QUOTES).'"/></th>
            ';
        }

        foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
            if (isset($data['valor'.$id.'_'.$dim.'_'.$grado])) {
                $this->valores[$id][$dim][$grado]['nombre'] = stripslashes($data['valor'.$id.'_'.$dim.'_'.$grado]);
            }

            echo '<th class="grado"><input class="valores"
            onkeyup=\'javascript:unificarValores("valor'.$id.'_'.$dim.'_'.$grado.'", this);\'
            type="text" id="valor'.$id .'_'. $dim.'_'.$grado.'" name="valor'.$id .'_'.$dim.'_'.$grado.'"
            value="'.htmlspecialchars($this->valores[$id][$dim][$grado]['nombre'], ENT_QUOTES).'"/></th>
            ';
        }
        echo '</tr>';
        $numattribute = count($this->atributo[$id][$dim][$subdim]);
        if (isset($this->atributo[$id][$dim][$subdim])) {
            foreach ($this->atributo[$id][$dim][$subdim] as $atrib => $elematrib) {
                if (isset($data['atributo'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib])) {
                    $this->atributo[$id][$dim][$subdim][$atrib]['nombre'] = stripslashes($data['atributo'.
                    $id.'_'.$dim.'_'.$subdim.'_'.$atrib]);
                }
                echo '          <tr>
                            <td style="">
                ';
                if ($this->view == 'design') {
                    echo '
                                <div style="margin-bottom:2em;">
                                    <input type="button" class="delete" onclick=\'javascript:sendPost("subdimensiontag'.
                                    $id.'_'.$dim.'_'.$subdim.'", "mix='.$mix.'&id='.$id.'&addAtr='.$dim.'_'.$subdim.
                                    '&dt='.$atrib.'", "mainform0");\'>
                                </div>
                                <div style="margin-top:2em;">
                                    <input type="button" class="add" onclick=\'javascript:sendPost("subdimensiontag'.
                                    $id.'_'.$dim.'_'.$subdim.'", "mix='.$mix.'&id='.$id.'&addAtr='.$dim.'_'.$subdim.
                                    '&at='.$atrib.'", "mainform0");\'>
                                </div>
                    ';
                }
                echo '</td>';

                if ($this->view == 'design') {
                    echo '
                            <td style="">
                                <div style="margin-bottom:2em;">
                                    <input type="button" class="up" onclick=\'javascript:sendPost("subdimensiontag'.
                                    $id.'_'.$dim.'_'.$subdim.'", "mix='.$mix.'&id='.$id.'&moveAtr='.$dim.'_'.$subdim.
                                    '&aUp='.$atrib.'", "mainform0");\'>
                                </div>
                                <div style="margin-top:2em;">
                                    <input type="button" class="down" onclick=\'javascript:sendPost("subdimensiontag'.
                                    $id.'_'.$dim.'_'.$subdim.'", "mix='.$mix.'&id='.$id.'&moveAtr='.$dim.'_'.$subdim.
                                    '&aDown='.$atrib.'", "mainform0");\'>
                                </div>
                            </td>
                    ';
                }

                echo '
                            <td><input class="porcentaje" type="text" onchange=\'document.getElementById("sumpor'.
                            $id.'_'.$dim.'_'.$subdim.'").value += this.id + "-";\' onkeyup=\'javascript:
                            if (document.getElementById("atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'").value > 100)
                                document.getElementById("atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'").value = 100;\'
                            onkeypress=\'javascript:return validar(event);\'  maxlength="3"
                            name="atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'"
                            id="atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'"
                            value="'.$this->atribpor[$id][$dim][$subdim][$atrib].'"/></span>
                                <input class="botonporcentaje" type="button" onclick=\'javascript:sendPost("subdimensiontag'.
                                $id.'_'.$dim.'_'.$subdim.'", "mix='.$mix.'&id='.$id.
                                '&atribpor="+document.getElementById("atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.
                                '").value+"&api='.$atrib.'&addAtr='.$dim.'_'.$subdim.'", "mainform0");\'></td>
                            <td><span class="font"><textarea class="width" id="atributo'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.
                            '" name="atributo'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'">'.
                            $this->atributo[$id][$dim][$subdim][$atrib]['nombre'].'</textarea></span></td>
                            ';
                $i = 0;
                foreach ($this->valoreslista[$id][$dim] as $grado => $elemvalue) {
                    $checked = '';
                    if (!$i) {
                        $checked = 'checked';
                    }
                    echo '<td><input type="radio" '.$checked.' id="radiovl'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'_'.$grado.
                    '" value="'.$i.'" name="radiovl'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'"
                    onclick=\'javascript:if (document.getElementById("radiovl'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'_'.$grado.
                    '").value==0) {var valores=document.getElementsByName("radio'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.
                    '");for (i=0;i<valores.length;i++) {valores[i].disabled=true;}}
                    else if (document.getElementById("radiovl'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'_'.$grado.
                    '").value==1) {var valores=document.getElementsByName("radio'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.
                    '");for (i=0;i<valores.length;i++) {valores[i].disabled=false;}}\' /></td>
                    ';
                    $i++;
                }
                foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                    echo '<td><input type="radio" disabled name="radio'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.
                    '" /></td>
                    ';
                }

                echo '</tr>';

                $visible = null;
                if (isset($data['commentatr'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib])) {
                    $visible = stripslashes($data['commentatr'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib]);
                    $this->commentatr[$id][$dim][$subdim][$atrib] = $visible;
                }

                if (isset($this->commentatr[$id][$dim][$subdim][$atrib])
                        && $this->commentatr[$id][$dim][$subdim][$atrib] == 'visible') {
                    $visible = 'visible';
                    $novisible = 'hidden';
                } else {
                    $novisible = 'visible';
                    $visible = 'hidden';
                }

                echo '<tr>
                <td></td>
                <td>';

                if ($this->view == 'design') {
                    echo '
                    <div style="text-align:right">
                        <input type="button" class="showcomment" title="'.
                        get_string('add_comments', 'block_evalcomix') .'"
                            onclick=\'javascript:sendPost("subdimensiontag'.$id.'_'.$dim.'_'.$subdim.'", "mix='.
                            $mix.'&id='.$id.'&commentatr'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'='.$novisible.
                            '&comAtr='.$atrib.'&addAtr='.$dim.'_'.$subdim.'", "mainform0");\'>
                    </div>';
                }
                echo '
                </td><td/><td/>
                <td colspan="'.($this->numvalores[$id][$dim] + 2) .'">';

                if ($visible == 'visible') {
                    $divheight = 'height:2.5em';
                    $textheight = 'height:2em';
                } else {
                    $divheight = 'height:0.5em';
                    $textheight = 'height:0.5em';
                }
                echo '<div class="atrcomment" id="atribcomment'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'"
                style="'.$divheight.'">
                <textarea disabled style="width:97%; visibility:'.$visible.'; '.$textheight.'"
                id="atributocomment'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'"></textarea>
                    </div>
                </td>
                ';

                echo '</tr>';
            }
        }
        echo '</table>
                </div>
        ';
        if ($this->view == 'design') {
            echo '
                <div><input type="button" class="add" onclick=\'javascript:sendPost("dimensiontag'.$id.'_'.$dim.'", "mix='.
                $mix.'&id='.$id.'&addSubDim='.$dim.'&sd='.$subdim.'&aS=1'.'", "mainform0");\'>
                <input type="button" class="down" onclick=\'javascript:sendPost("dimensiontag'.$id.'_'.$dim.'", "mix='.
                $mix.'&id='.$id.'&moveSub='.$dim.'&sDown='.$subdim.'", "mainform0");\'>
                <br></div>
        ';
        }
        flush();
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

        $id = $this->id;
        $root = '';
        $rootend = '';
        $percentage1 = '';
        if ($mixed == '0') {
            $root = '<ce:ControlListEvaluationSet xmlns:ce="http://avanza.uca.es/assessmentservice/controllistevaluationset"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://avanza.uca.es/assessmentservice/controllistevaluationset
        http://avanza.uca.es/assessmentservice/ControlListEvaluationSet.xsd"
';
            $rootend = '</ce:ControlListEvaluationSet>
';
        } else if ($mixed == '1') {
            $root = '<ControlListEvaluationSet ';
            $rootend = '</ControlListEvaluationSet>';
            $percentage1 = ' percentage="' . $this->porcentage . '"';
        }

        $xml = $root . ' id="'. $idtool .'" name="' . htmlspecialchars($this->titulo, ENT_QUOTES) . '" dimensions="' .
        $this->numdim[$id] .'" ' . $percentage1 . '>
';

        if (isset($this->observation[$id])) {
                $xml .= '<Description>' . htmlspecialchars($this->observation[$id], ENT_QUOTES) . '</Description>
';
        }

        foreach ($this->dimension[$id] as $dim => $itemdim) {
            $dimid = (isset($this->dimensionsid[$id][$dim])) ? $this->dimensionsid[$id][$dim] : '';
            $dimname = (isset($this->dimension[$id][$dim]['nombre'])) ?
            htmlspecialchars($this->dimension[$id][$dim]['nombre'], ENT_QUOTES) : '';
            $numsubdim = (isset($this->numsubdim[$id][$dim])) ? $this->numsubdim[$id][$dim] : '';
            $numvaloresdim = (isset($this->numvalores[$id][$dim])) ? $this->numvalores[$id][$dim] : 0;
            $dimpor = (isset($this->dimpor[$id][$dim])) ? $this->dimpor[$id][$dim] : '';
            $xml .= '<Dimension id="'
                . $dimid.'" name="'
                . $dimname . '" subdimensions="'
                . $numsubdim . '" values="'
                . $numvaloresdim . '" percentage="'
                . $dimpor .'">
';

            $xml .= "<ControlListValues>\n";
            foreach ($this->valoreslista[$id][$dim] as $grado => $elemvalue) {
                $valorlistaid = (isset($this->valoreslistaid[$id][$dim][$grado]))
                    ? $this->valoreslistaid[$id][$dim][$grado] : '';
                $valorlista = (isset($this->valoreslista[$id][$dim][$grado]['nombre']))
                    ? htmlspecialchars($this->valoreslista[$id][$dim][$grado]['nombre'], ENT_QUOTES) : '';
                $xml .= '<Value id="'.$valorlistaid.'">' . $valorlista . "</Value>\n";
            }
            $xml .= "</ControlListValues>\n";

            $xml .= "<Values>\n";
            foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                $valoresid = '';
                if (isset($this->valoresid[$id][$dim][$grado])) {
                    $valoresid = $this->valoresid[$id][$dim][$grado];
                }
                $valuename = (isset($this->valores[$id][$dim][$grado]['nombre']))
                    ? htmlspecialchars($this->valores[$id][$dim][$grado]['nombre'], ENT_QUOTES) : '';
                $xml .= '<Value id="'.$valoresid.'">'. $valuename . "</Value>\n";
            }
            $xml .= "</Values>\n";

            foreach ($this->subdimension[$id][$dim] as $subdim => $elemsubdim) {
                $subid = (isset($this->subdimensionsid[$id][$dim][$subdim]))
                    ? $this->subdimensionsid[$id][$dim][$subdim] : '';
                $subname = (isset($this->subdimension[$id][$dim][$subdim]['nombre']))
                    ? htmlspecialchars($this->subdimension[$id][$dim][$subdim]['nombre'], ENT_QUOTES) : '';
                $numatr = (isset($this->numatr[$id][$dim][$subdim])) ? $this->numatr[$id][$dim][$subdim] : '';
                $subdimpor = (isset($this->subdimpor[$id][$dim][$subdim])) ? $this->subdimpor[$id][$dim][$subdim] : '';

                $xml .= '<Subdimension id="'
                    . $subid.'" name="'
                    . $subname . '" attributes="'
                    . $numatr . '" percentage="'
                    . $subdimpor . '">
';

                foreach ($this->atributo[$id][$dim][$subdim] as $atrib => $elematrib) {
                    $comment = '';
                    if (isset($this->commentatr[$id][$dim][$subdim][$atrib])
                        && $this->commentatr[$id][$dim][$subdim][$atrib] == 'visible') {
                        $comment = '1';
                    }
                    $atrid = (isset($this->atributosid[$id][$dim][$subdim][$atrib]))
                        ? $this->atributosid[$id][$dim][$subdim][$atrib] : '';
                    $atrname = (isset($this->atributo[$id][$dim][$subdim][$atrib]['nombre']))
                        ? $this->atributo[$id][$dim][$subdim][$atrib]['nombre'] : '';
                    $atrpor = (isset($this->atribpor[$id][$dim][$subdim][$atrib]))
                        ? $this->atribpor[$id][$dim][$subdim][$atrib] : '';

                    $xml .= '<Attribute id="'
                        . $atrid .'" name="'
                        . htmlspecialchars($atrname, ENT_QUOTES) . '" comment="'
                        . $comment .'" percentage="'
                        . $atrpor . '">
            <selectionControlList>0</selectionControlList>
            <selection>0</selection>
        </Attribute>
';
                }

                $xml .= "</Subdimension>\n";
            }

            if (isset($this->valglobal[$id][$dim]) && ($this->valglobal[$id][$dim] == 'true'
                    || $this->valglobal[$id][$dim] == 't')) {
                $comment = '';
                if (isset($this->commentdim[$id][$dim]) && $this->commentdim[$id][$dim] == 'visible') {
                    $comment = '1';
                }

                $xml .= '<DimensionAssessment percentage="' . $this->valglobalpor[$id][$dim] . '">
                    <Attribute name="Global assessment" comment="'. $comment .'" percentage="100">0</Attribute>
                </DimensionAssessment>';
            }

            $xml .= "</Dimension>\n";
        }

        if (isset($this->valtotal[$id]) && ($this->valtotal[$id] == 'true' || $this->valtotal[$id] == 't')) {
            $xml .= '<GlobalAssessment values="' . $this->numtotal[$id] . '" percentage="' . $this->valtotalpor[$id] . '">
                <Values>
';
            foreach ($this->valorestotal[$id] as $grado => $elemvalue) {
                $totalvalueid = (isset($this->valorestotalesid[$id][$grado])) ? $this->valorestotalesid[$id][$grado] : '';
                $totalvalue = (isset($this->valorestotal[$id][$grado]['nombre'])) ?
                $this->valorestotal[$id][$grado]['nombre'] : '';
                $xml .= '<Value id="'.$totalvalueid.'">'. htmlspecialchars($totalvalue, ENT_QUOTES) . "</Value>\n";
            }
            $xml .= '</Values>

                <Attribute name="Global assessment" percentage="100">0</Attribute>
            </GlobalAssessment>
';
        }
        $xml .= $rootend;

        return $xml;
    }

    public function print_tool($globalcomment = 'global_comment') {
        $id = $this->id;
        $colspan = max($this->numvalores[$id]);
        echo '
                                <table class="tabla" border=1 cellpadding=5px >

                                <!--TITULO-INSTRUMENTO------------>
        ';
        if (is_numeric($this->porcentage)) {
            echo '
                                <tr>
                                   <td colspan="'.($colspan + 4) .'">'. get_string('mixed_por', 'block_evalcomix'). ': ' .
                                   $this->porcentage.'%</td>
                                </tr>
            ';
        }

        echo '
                                <tr>
                                   <th colspan="'.($colspan + 4) .'">'.htmlspecialchars($this->titulo, ENT_QUOTES).'</th>
                                </tr>

                                <tr>
                                   <th colspan="'.($colspan + 4) .'"></th>
                                </tr>


                                <tr>
                                   <td></td>
                                   <td></td>
                                </tr>';

        $i = 0;
        foreach ($this->dimension[$id] as $dim => $value) {
            $colspandim = $this->numvalores[$this->id][$dim];

            echo '
                                <tr id="dim">
                                    <!--DIMENSIÓN-TITLE----------->
                                    <td class="pordim">
                                    '.$this->dimpor[$this->id][$dim].'%
                                    </td>
                                    <td class="bold" colspan="'.($colspan - $colspandim + 1) .'">
                                        <span>'.htmlspecialchars($this->dimension[$this->id][$dim]['nombre'], ENT_QUOTES).'</span>
                                    </td>
            ';
            foreach ($this->valoreslista[$id][$dim] as $grado => $elemvalue) {
                echo '<th class="grado">'.htmlspecialchars($this->valoreslista[$id][$dim][$grado]['nombre'], ENT_QUOTES).'</th>
                ';
            }
            foreach ($this->valores[$this->id][$dim] as $grado => $elemvalue) {
                echo '
                        <td class="td">'.htmlspecialchars($this->valores[$this->id][$dim][$grado]['nombre'], ENT_QUOTES).'</td>
                ';
            }

            echo '
                    </tr>
            ';

            $l = 0;
            foreach ($this->subdimension[$this->id][$dim] as $subdim => $elemsubdim) {
                echo '
                                <!--TITULO-SUBDIMENSIÓN------------>
                                <tr>
                                    <td class="subdimpor">'.$this->subdimpor[$this->id][$dim][$subdim].'%</td>
                                    <td class="subdim" colspan="'.($colspan + 3).'">'.
                                    htmlspecialchars($this->subdimension[$this->id][$dim][$subdim]['nombre'], ENT_QUOTES).'</td>
                                    </tr>
                ';

                if (isset($this->atributo[$this->id][$dim][$subdim])) {
                    $j = 0;
                    foreach ($this->atributo[$this->id][$dim][$subdim] as $atrib => $elematrib) {
                        echo '
                                <!--ATRIBUTOS---------------------->
                                <tr rowspan=0>
                                    <td class="atribpor">'.$this->atribpor[$this->id][$dim][$subdim][$atrib].'%</td>
                                    <td colspan="'.($colspan - $colspandim + 1) .'">'.
                                    htmlspecialchars($this->atributo[$this->id][$dim][$subdim][$atrib]['nombre'], ENT_QUOTES).
                                    '</td>
                        ';

                        $ilista = 1;
                        foreach ($this->valoreslista[$id][$dim] as $grado => $elemvalue) {
                            $disabled = '';
                            if (isset($this->valueattribute[$id][$dim][$subdim][$atrib]) &&
                            $this->valueattribute[$id][$dim][$subdim][$atrib] == '0_0') {
                                $valuechecklist = 1;
                                $disabled = 'disabled';
                            } else if (isset($this->valueattribute[$id][$dim][$subdim][$atrib])
                                    && $this->valueattribute[$id][$dim][$subdim][$atrib] != '') {
                                $valuechecklist = 2;
                            } else {
                                $valuechecklist = 2;
                            }
                            $checked = '';
                            if ($valuechecklist == $ilista) {
                                $checked = 'checked';
                            }

                            echo '<td>
                                <input type="radio" class="custom-radio" id="radio'. $i.$l.'_'.$j.'_'.$j . '_' . $ilista .
                                '" value="'.$ilista.'" name="radio'. $i.$l.'_'.$j.'_'.$j .'"
                        '.$checked.' style="width:100%" onclick=\'javascript:var valores = this.form.elements["radio'.$i.$l.$j.'"];
                        if (document.getElementById("radio'. $i.$l.'_'.$j.'_'.$j . '_' . $ilista . '").value==1) {
                            for(i=0;i<valores.length;i++) {
                                valores[i].disabled=true;
                            }
                        }
                        else if (document.getElementById("radio'. $i.$l.'_'.$j.'_'.$j . '_' . $ilista . '").value==2) {

                            for(i=0;i<valores.length;i++) {
                                valores[i].disabled=false;
                            }
                        }\' /></td>';
                            $ilista++;
                        }

                        $k = 3;
                        foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                            $checked = '';

                            if (isset($this->valueattribute[$id][$dim][$subdim][$atrib]) &&
                            $this->valueattribute[$id][$dim][$subdim][$atrib] == $this->valores[$id][$dim][$grado]['nombre']) {
                                $checked = 'checked';
                            }

                            echo '
                                <td><input type="radio" class="custom-radio" '.$disabled.' name="radio'.$i.$l.$j.'" value="'.$k.
                                '" '.$checked.' style="width:100%"></td>
                            ';
                            ++$k;
                        }
                        echo '
                                </tr>
                                <tr>
                                    <td colspan="'.(2 + $colspan - $colspandim).'"></td>
                        ';

                        if (isset($this->commentatr[$id][$dim][$subdim][$atrib]) &&
                                $this->commentatr[$id][$dim][$subdim][$atrib] == 'visible') {
                            $vcomment = '';
                            if (isset($this->valuecommentAtr[$id][$dim][$subdim][$atrib])) {
                                $vcomment = $this->valuecommentAtr[$id][$dim][$subdim][$atrib];
                            }

                            echo '
                                    <td colspan="'.($colspandim + 2).'">
                                        <textarea rows="2" style="height:6em;width:100%" id="observaciones'.$i.'_'.$l.'_'.$j.
                                        '" name="observaciones'.$i.'_'.$l.'_'.$j.'" style="width:100%">'.$vcomment.'</textarea>
                                    </td>
                            ';
                        }
                        echo '
                                </tr>
                                <tr></tr>
                                <tr></tr>
                        ';
                        ++$j;
                    }
                }
                ++$l;
            }

            if (isset($this->valglobal[$id][$dim]) && $this->valglobal[$id][$dim] == 'true') {
                echo "
                        <tr>
                            <td class='subdimpor'>".$this->valglobalpor[$id][$dim]."%</td>
                            <td class='global' colspan='".($colspan - $colspandim + 3) ."'>".
                            get_string('globalvalue', 'block_evalcomix')."</td>
                ";

                $k = 3;
                foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                    $checked = '';
                    if (isset($this->valueglobaldim[$id][$dim]) &&
                            $this->valueglobaldim[$id][$dim] == $this->valores[$id][$dim][$grado]['nombre']) {
                        $checked = 'checked';
                    }
                    echo "
                            <td class='td'><input type='radio' class='custom-radio' name='radio".$i."' value='".$k."' ". $checked .
                            " style='width:100%'></td>
                    ";
                    ++$k;
                }
            }

            echo "
                    </tr>
                    <tr>
                        <td colspan='".($colspan - $colspandim + 4) ."'></td>
            ";
            if (isset($this->commentdim[$id][$dim]) && $this->commentdim[$id][$dim] == 'visible') {
                $vcomment = '';
                if (isset($this->valuecommentDim[$id][$dim])) {
                    $vcomment = $this->valuecommentDim[$id][$dim];
                }

                echo "
                        <td colspan='".$colspandim."'>
                            <textarea rows='3' style='height:6em;width:100%' id='observaciones".$i."' name='observaciones".$i.
                            "' style='width:100%'>".$vcomment."</textarea>
                        </td>
                ";
            }
            echo "
                    </tr>
            ";
            ++$i;
        }

        echo '
                            </table>
        ';
        if (isset($this->valorestotal[$id])) {
            echo '
                    <table class="tabla" border=1 cellpadding=5px >
                                <tr>
                                    <td class="pordim">'.$this->valtotalpor[$id].'%</td>
                                    <td class="global" colspan="1">'.strtoupper(get_string('totalvalue', 'block_evalcomix')).'</td>
            ';

            foreach ($this->valorestotal[$id] as $grado => $elemvalue) {
                echo '<th>'.htmlspecialchars($this->valorestotal[$id][$grado]['nombre'], ENT_QUOTES).'</th>
                ';
            }

            echo '<tr><td class="global" colspan="2"></td>';
            foreach ($this->valorestotal[$id] as $grado => $elemvalue) {
                $checked = '';
                if (isset($this->valuetotalvalue[$id]) &&
                        $this->valuetotalvalue[$id] == $this->valorestotal[$id][$grado]['nombre']) {
                    $checked = 'checked';
                }

                echo '<td><input type="radio" class="custom-radio" name="total" value="'.
                htmlspecialchars($this->valorestotal[$id][$grado]['nombre'], ENT_QUOTES).'" '.$checked.' style="width:100%"/></td>
                ';
            }
        }

        echo '
                                </tr>
                            </table>
        ';
        if (isset($globalcomment)) {
            $globalcomment = ($globalcomment === 'global_comment') ? $this->observation[$id] : $globalcomment;
            $width = (empty($this->comment[$id])) ? 100 : 60;
            $comment = (empty($this->comment[$id])) ? (get_string('comments', 'block_evalcomix')).':' : $this->comment[$id];
            echo '<br><br><br>
                            <table class="tabla" border=1 cellpadding="5px">
                                <tr>
                                    <td>'.htmlspecialchars($comment, ENT_QUOTES).'</td>
                                    <td style="width:'.$width.
                                    '%"><textarea name="observaciones" id="observaciones" rows=4 cols=20 style="width:100%">'.
                                    $globalcomment.'</textarea></td>
                                </tr>
                            </table>
            ';
        }
    }
}
