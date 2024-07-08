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

class block_evalcomix_editor_toollist extends block_evalcomix_editor {
    private $titulo;
    private $valtotal;
    private $numtotal;
    private $valorestotal;
    private $valtotalpor;
    private $valglobal;
    private $valglobalpor;
    private $filediccionario;
    private $dimpor;
    private $porcentage;
    private $observation;
    private $view;
    private $commentatr;
    private $valuecommentatr;
    private $dimensionsid;
    private $atributosid;
    private $valoresid;
    private $valorestotalesid;
	private $valueglobaldim;

    public function get_tool($id) {
    }

    public function get_titulo() {
        return $this->titulo;
    }

    public function get_valtotal() {
        $valtotal = (isset($this->valtotal[$this->id])) ? $this->valtotal[$this->id] : null;
        return $valtotal;
    }

    public function get_numtotal($id=0) {
        return $this->numtotal[$this->id];
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
        $valglobal = (isset($this->valglobal[$this->id])) ? $this->valglobal[$this->id] : null;
        return $valglobal;
    }

    public function get_valglobalpor() {
    }

    public function get_dimpor() {
        return $this->dimpor[$this->id];
    }

    public function get_commentatr($id = 0) {
        return $this->commentatr[$this->id];
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
        return array();
    }

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

    public function __construct($lang='es_utf8', $titulo = '', $dimension = array(), $numdim = 1, $subdimension = array(),
            $numsubdim = 1, $atributo = array(), $numatr = 1, $valores = array(), $numvalores = 2, $valtotal = array(),
            $numtotal = 0, $valorestotal = array(), $valglobal = false, $valglobalpor = array(), $dimpor = array(),
            $subdimpor = array(), $atribpor = array(), $commentatr = array(), $id = 0, $observation = '', $porcentage=0,
            $valueattribute = '', $valuecommentatr = '', $params = array()) {
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
        $this->view = 'design';
        $this->commentatr = $commentatr;
        $this->valueattribute = $valueattribute;
        $this->valuecommentatr = $valuecommentatr;
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
    }

    public function add_dimension($dim, $key) {
        $dimen;
        $this->numdim[$this->id] += 1;
        if (!isset($dim)) {
            $dim = $key;
            $dimen = $dim;
            $key++;
            $this->dimension[$this->id][$dim]['nombre'] = get_string('titledim', 'block_evalcomix').$this->numdim[$this->id];
        } else {
            $newindex = $key;
            $dimen = $newindex;
            $elem['nombre'] = get_string('titledim', 'block_evalcomix').$this->numdim[$this->id];
            $this->dimension[$this->id] = $this->array_add($this->dimension[$this->id], $dim, $elem, $newindex);
        }

        $subdim = $key;
        $key++;
        $this->numatr[$this->id][$dimen][$subdim] = 1;
        $this->numsubdim[$this->id][$dimen] = 1;
        $this->atributo[$this->id][$dimen][$subdim][0]['nombre'] = get_string('titleatrib', 'block_evalcomix').
            $this->numatr[$this->id][$dimen][$subdim];
        $this->atribpor[$this->id][$dimen][$subdim][0] = 100;
        $this->subdimension[$this->id][$dimen][$subdim]['nombre'] = get_string('titlesubdim', 'block_evalcomix').
            $this->numsubdim[$this->id][$dimen];
        $this->subdimpor[$this->id][$dimen][$subdim] = 100;
        $this->valores[$this->id][$dimen][0]['nombre'] = 'No';
        $this->valores[$this->id][$dimen][1]['nombre'] = 'Yes';
        $this->numvalores[$this->id][$dimen] = 2;
        $this->competency[$this->id][$dim][$subdim] = array();
        $this->outcome[$this->id][$dim][$subdim] = array();
    }

    public function add_attribute($dim, $subdim, $atrib, $key) {
        $this->numatr[$this->id][$dim][$subdim]++;

        if (!isset($atrib)) {
            $atrib = $key;
            $this->atributo[$this->id][$dim][$subdim][$atrib]['nombre'] = get_string('titleatrib', 'block_evalcomix').
                $this->numatr[$this->id][$dim][$subdim];
        } else {
            $newindex = $key;
            $elem['nombre'] = get_string('titleatrib', 'block_evalcomix').$this->numatr[$this->id][$dim][$subdim];
            $this->atributo[$this->id][$dim][$subdim] = $this->array_add($this->atributo[$this->id][$dim][$subdim],
                $atrib, $elem, $newindex);
            $this->commentatr[$this->id][$dim][$subdim][$newindex] = 'hidden';
        }
    }

    public function remove_total_values($grado) {
        if ($this->numtotal > 2) {
            $this->numtotal--;
            $this->valorestotal = $this->array_remove($this->valorestotal, $grado);
        }
    }

    public function display_body($data, $mix = '', $porcentage='') {
        if ($porcentage != '') {
            $this->porcentage = $porcentage;
        }
        if (isset($data['titulo'.$this->id])) {
            $this->titulo = stripslashes($data['titulo'.$this->id]);
        }
        if (isset($data['valtotal'.$this->id])) {
            $this->valtotal[$this->id] = stripslashes($data['valtotal'.$this->id]);
        }
        if (isset($data['numvalores'.$this->id]) && $data['numvalores'.$this->id] >= 2) {
            $this->numtotal[$this->id] = stripslashes($data['numvalores'.$this->id]);
        }
        $numdimen = count($this->dimension[$this->id]);

        if ($this->view == 'view' && !is_numeric($mix)) {
            echo '<input type="button" style="width:10em" value="'.get_string('view', 'block_evalcomix').'"
            onclick=\'javascript:location.href="generator.php?op=design&courseid='.$data['courseid'].'"\'><br>';
        }
        $id = $this->id;

        echo '
        <div id="cuerpo'.$id.'"  class="cuerpo">
            <br>
            <label for="titulo'.$id.'" style="margin-left:1em">'.get_string('checklist', 'block_evalcomix').
            ':</label><span class="labelcampo">
                <textarea class="width" id="titulo'.$id.'" name="titulo'.$id.'">'.$this->titulo.'</textarea></span>
            ';
        if ($this->view == 'design') {
            echo '
            <label for="numdimensiones'.$id.'">'.get_string('numdimensions', 'block_evalcomix').'</label>
            <span class="labelcampo">
                <input type="text" id="numdimensiones'.$id.'" name="numdimensiones'.$id.'" value="'.$this->numdim[$this->id].
                '" maxlength=2 onkeypress=\'javascript:return validar(event);\'/>
            </span>

            <input class="flecha" type="button" id="addDim"
            onclick=\'javascript:if (!validarEntero(document.getElementById("numdimensiones'.$id.'").value)) {
                alert("' . get_string('ADimension', 'block_evalcomix') . '"); return false;}
                sendPost("cuerpo'.$id.'", "mix='.$mix.'&id='.$id.'&addDim=1&titulo'.$id.'="+document.getElementById("titulo'.
                $id.'").value+"&numdimensiones="+ document.getElementById("numdimensiones'.$id.'").value +"", "mainform0");\'
                name="addDim" value=""/>
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
                <input class="botonporcentaje" type="button" onclick=\'javascript:sendPost("body", "id='.
                $id.'&toolpor_'.$id.'="+document.getElementById("toolpor_'.$id.'").value+"&addtool'.$id.
                '=1", "mainform0");\'>
            </span>';
        }

        echo '<br/>';
        flush();
        foreach ($this->dimension[$id] as $dim => $value) {
            echo '<div class="dimension" id="dimensiontag'.$id.'_'.$dim.'">';
            $this->display_dimension($dim, $data, $id, $mix);
            echo '</div>';
        }

        if (!is_numeric($mix)) {
            if (isset($data['observation'.$id]) && trim($data['observation'.$id]) != '') {
                $this->observation[$id] = stripslashes($data['observation'.$id]);
            } else if (isset($data['save']) && $data['save'] == '1') {
                $this->observation[$id] = '';
            }

            $observationid = '';
            if (isset($this->observation[$id])) {
                $observationid = $this->observation[$id];
            }

            echo '
            <div id="comentario">
                <div id="marco">
                    <label for="observation'.$id.'">' . get_string('observation', 'block_evalcomix'). ':</label>
                    <textarea id="observation'.$id.'" style="width:100%" rows="4" cols="200">' . $observationid .
                    '</textarea>
                </div>
            </div>
            ';
        }
        echo '
            <input type="hidden" id="sumpor3'.$id.'" value=""/>
            <input type="hidden" name="courseid" id="courseid" value="'.$data['courseid'].'">
        </div>

        ';
        flush();
    }

    public function display_dimension($dim, $data, $id=0, $mix='') {
        $id = $this->id;
        if (isset($data['dimension'.$id.'_'.$dim])) {
            $this->dimension[$this->id][$dim]['nombre'] = stripslashes($data['dimension'.$id.'_'.$dim]);
        }

        if (isset($data['numvalores'.$id.'_'.$dim]) && $data['numvalores'.$id.'_'.$dim] > 1) {
            $this->numvalores[$this->id][$dim] = stripslashes($data['numvalores'.$id.'_'.$dim]);
        }

        $thisnumtotalid = (isset($this->numtotal[$id])) ? $this->numtotal[$id] : null;

        if ($this->view == 'design') {
            echo '
            <div>
                <input type="button" class="delete" onclick=\'javascript:sendPost("cuerpo'.$id
                .'","mix='.$mix.'&id='.$id.'&titulo'.$id.'="+document.getElementById("titulo'.$id
                .'").value+"&addDim=1&numvalores='.$thisnumtotalid.'&dd='.$dim.'", "mainform0");\'>
                <input type="button" class="up" onclick=\'javascript:sendPost("cuerpo'.$id.'","mix='.$mix
                .'&id='.$id.'&titulo'.$id.'="+document.getElementById("titulo'.$id
                .'").value+"&moveDim=1&dUp='.$dim.'", "mainform0");\'>
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
                    $this->dimension[$this->id][$dim]['nombre'] .'</textarea>
                </span>
        ';
        if ($this->view == 'design') {
            echo '
            <label for="numsubdimensiones'.$id.'_'.$dim.'">'.get_string('numsubdimension', 'block_evalcomix').'</label>
            <span class="labelcampo"><input type="text" id="numsubdimensiones'.$id.'_'.$dim.'"
            name="numsubdimensiones'.$id.'_'.$dim.'" value="'.$this->numsubdim[$this->id][$dim].'"
            maxlength=2 onkeypress=\'javascript:return validar(event);\'/></span>

            <label for="numvalores'.$id.'_'.$dim.'">'.get_string('numvalues', 'block_evalcomix').'</label>
            <span class="labelcampo"><input type="text" disabled id="numvalores'.$id.'_'.$dim.'"
            name="numvalores'.$id.'_'.$dim.'" value="'.$this->numvalores[$this->id][$dim].'" maxlength=2
            onkeypress=\'javascript:return validar(event);\'/></span>

            <input class="flecha" type="button" id="addSubDim'.$id.'" name="addSubDim'.$id.'"
            onclick=\'javascript:if (!validarEntero(document.getElementById("numvalores'.$id.'_'.$dim.'").value)
                || !validarEntero(document.getElementById("numsubdimensiones'.$id.'_'.$dim.'").value)) {alert("' .
            get_string('ASubdimension', 'block_evalcomix') . '"); return false;}sendPost("dimensiontag'.$id.'_'.$dim.
            '", "mix='.$mix.
            '&id='.$id.'&addSubDim="+this.value+"&numvalores'.$id.'_'.$dim.'="+ document.getElementById("numvalores'.
            $id.'_'.$dim.'").value +"&numsubdimensiones'.$dim.'="+ document.getElementById("numsubdimensiones'.
            $id.'_'.$dim.'").value +"", "mainform0");\' style="font-size:1px" value="'.$dim.'"/>
            ';
        }
        echo '
            <span class="labelcampo"><label for="dimpor'.$id.'_'.$dim.'">'.get_string('porvalue', 'block_evalcomix').
            '</label><span class="labelcampo">
            <input class="porcentaje" type="text" maxlength="3" name="dimpor'.$id.'_'.$dim.'" id="dimpor'.$id.'_'.$dim.'"
            value="'.$this->dimpor[$this->id][$dim].'" onchange=\'javascript:document.getElementById("sumpor3'.$id.
            '").value += this.id +"-";;\' onkeyup=\'javascript:if (document.getElementById("dimpor'.$id.'_'.$dim.
            '").value > 100)document.getElementById("dimpor'.$id.'_'.$dim.'").value = 100;\'
            onkeypress=\'javascript:return validar(event);\'/></span>
            <input class="botonporcentaje" type="button" onclick=\'javascript:sendPost("cuerpo'.$id.'", "mix='.
            $mix.'&id='.$id.'&dimpor'.$id.'="+document.getElementById("dimpor'.$id.'_'.$dim.'").value+"&dpi='.
            $dim.'&addDim=1", "mainform0");\'></span>';

        if (isset($this->subdimension[$this->id][$dim])) {
            foreach ($this->subdimension[$this->id][$dim] as $subdim => $elemsubdim) {
                echo '
                    <div class="subdimension" id="subdimensiontag'.$id.'_'.$dim.'_'.$subdim.'">
                ';
                $this->display_subdimension($dim, $subdim, $data, $id, $mix);
                echo '</div>
                ';
            }
        }

        echo '</div>';

        if ($this->view == 'design') {
            echo '<div>
                    <input type="button" class="add" onclick=\'javascript:sendPost("cuerpo'.$id.'","mix='.$mix.'&id='.
                    $id.'&titulo'.$id.'="+document.getElementById("titulo'.$id.'").value+"&addDim=1&ad='.$dim.
                    '", "mainform0");\'>
                    <input type="button" class="down" onclick=\'javascript:sendPost("cuerpo'.$id.'","mix='.$mix.'&mix='.
                    $mix.'&id='.$id.'&moveDim=1&dDown='.$dim.'", "mainform0");\'>
                <br></div>
            ';
        }
        flush();
    }

    public function display_subdimension($dim, $subdim, $data, $id='0', $mix='') {
        $id = $this->id;

        if (isset($data['subdimension'.$id.'_'.$dim.'_'.$subdim]) && empty($data['modalclose'])) {
            $this->subdimension[$id][$dim][$subdim]['nombre'] = stripslashes($data['subdimension'.$id.'_'.$dim.'_'.$subdim]);
        }

        if ($this->view == 'design') {
            echo '
            <input type="hidden" id="sumpor'.$id.'_'.$dim.'_'.$subdim.'" value=""/>
            <div>
                <input type="button" class="delete" onclick=\'javascript:sendPost("dimensiontag'.$id.'_'.
                $dim.'", "mix='.$mix.'&id='.$id.'&addSubDim='.$dim.'&dS=1&sd='.$subdim.'", "mainform0");\'>
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
                        <span class="labelcampo">
                        <textarea  class="width" id="subdimension'.$id.'_'.$dim.'_'.$subdim.'" name="subdimension'.$id.
                        '_'.$dim.'_'.$subdim.'">'.$this->subdimension[$this->id][$dim][$subdim]['nombre'].'</textarea>
                        </span>
                    </div>
        ';
        if ($this->view == 'design') {
            echo '
                <div class="float-md-left">
                    <label for="numatributos'.$id.'_'.$dim.'_'.$subdim.'">'.get_string('numattributes', 'block_evalcomix').
                    '</label>
                    <span class="labelcampo"><input type="text" id="numatributos'.$id.'_'.$dim.'_'.$subdim.
                    '" name="numatributos'.$id.'_'.$dim.'_'.$subdim.'" value="'.$this->numatr[$this->id][$dim][$subdim].
                    '" maxlength=2 onkeypress=\'javascript:return validar(event);\'/></span>
                    <input class="flecha" type="button" id="addAtr'.$id.'" name="addAtr'.$id.'"
                    style="font-size:1px" onclick=\'javascript:if (!validarEntero(document.getElementById("numatributos'.
                    $id.'_'.$dim.'_'.$subdim.'").value)) {alert("' . get_string('AAttribute', 'block_evalcomix') . '");
                    return false;}sendPost("subdimensiontag'.$id.'_'.$dim.'_'.$subdim.'", "mix='.$mix.'&id='.$id.
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
                    name="subdimpor'.$id.'_'.$dim.'_'.$subdim.'" value="'.$this->subdimpor[$this->id][$dim][$subdim].'"
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
        foreach ($this->valores[$this->id][$dim] as $grado => $elemvalue) {
            if (isset($data['valor'.$id.'_'.$dim.'_'.$grado])) {
                $this->valores[$this->id][$dim][$grado]['nombre'] = stripslashes($data['valor'.$id.'_'.$dim.'_'.$grado]);
            }

            echo '<th class="grado"><input class="valores"
            onkeyup=\'javascript:var valores=document.getElementsByName("valor'.$id.'_'.$dim.'_'.$grado.'");
            for (var i=0; i<valores.length; i++) {valores[i].value=this.value;}\' type="text"
            id="valor'.$id.'_'.$dim.'_'.$grado.'" name="valor'.$id.'_'.$dim.'_'.$grado.'" value="'.
            htmlspecialchars($this->valores[$this->id][$dim][$grado]['nombre'], ENT_QUOTES).'"/></th>
            ';
        }
        echo '</tr>';
        $numattribute = count($this->atributo[$this->id][$dim][$subdim]);
        if (isset($this->atributo[$this->id][$dim][$subdim])) {
            foreach ($this->atributo[$this->id][$dim][$subdim] as $atrib => $elematrib) {
                if (isset($data['atributo'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib])) {
                    $this->atributo[$this->id][$dim][$subdim][$atrib]['nombre'] = stripslashes($data['atributo'.$id.
                    '_'.$dim.'_'.$subdim.'_'.$atrib]);
                }
                echo '          <tr>
                            <td style="">';
                if ($this->view == 'design') {
                    echo '
                                <div style="margin-bottom:2em;">
                                    <input type="button" class="delete" onclick=\'javascript:sendPost("subdimensiontag'.
                                    $id.'_'.$dim.'_'.$subdim.'", "mix='.$mix.'&id='.$id.'&addAtr='.$dim.'_'.$subdim.'&dt='.
                                    $atrib.'", "mainform0");\'>
                                    </div>
                                <div style="margin-top:2em;">
                                    <input type="button" class="add" onclick=\'javascript:sendPost("subdimensiontag'.
                                    $id.'_'.$dim.'_'.$subdim.'", "mix='.$mix.'&id='.$id.'&addAtr='.$dim.'_'.$subdim.'&at='.
                                    $atrib.'", "mainform0");\'>
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
                        $id.'_'.$dim.'_'.$subdim.'").value += this.id + "-";\'
                        onkeyup=\'javascript:if (document.getElementById("atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.
                        '").value > 100)document.getElementById("atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'").value = 100;\'
                        onkeypress=\'javascript:return validar(event);\'  maxlength="3"
                        name="atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'" id="atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.
                        '" value="'.$this->atribpor[$this->id][$dim][$subdim][$atrib].'"/>
                        <input class="botonporcentaje" type="button" onclick=\'javascript:sendPost("subdimensiontag'.
                        $id.'_'.$dim.'_'.$subdim.'", "mix='.$mix.'&id='.$id.'&atribpor="+document.getElementById("atribpor'.
                        $id.'_'.$dim.'_'.$subdim.'_'.$atrib.'").value+"&api='.$atrib.'&addAtr='.$dim.'_'.$subdim.
                        '", "mainform0");\'></td>
                        <td><span class="font"><textarea class="width" id="atributo'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.
                        '" name="atributo'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'">'.
                        $this->atributo[$this->id][$dim][$subdim][$atrib]['nombre'].'</textarea></span></td>
                ';
                foreach ($this->valores[$this->id][$dim] as $grado => $elemvalue) {
                    echo '<td><input type="radio" name="radio'.$dim.'_'.$subdim.'_'.$atrib.'" /></td>
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
                        <input type="button" class="showcomment" title="'. get_string('add_comments', 'block_evalcomix') .'"
                            onclick=\'javascript:sendPost("subdimensiontag'.$id.'_'.$dim.'_'.$subdim.'", "mix='.
                            $mix.'&id='.$id.'&commentatr'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'='.$novisible.'&comAtr='.
                            $atrib.'&addAtr='.$dim.'_'.$subdim.'", "mainform0");\'>
                    </div>';
                }
                echo '
                </td><td/><td></td>
                <td colspan="'.$this->numvalores[$id][$dim].'">';

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
                <div><input type="button" class="add" onclick=\'javascript:sendPost("dimensiontag'.$id.'_'.$dim.
                '", "mix='.$mix.'&id='.$id.'&addSubDim='.$dim.'&sd='.$subdim.'&aS=1'.'", "mainform0");\'>
                <input type="button" class="down" onclick=\'javascript:sendPost("dimensiontag'.$id.'_'.$dim.'", "mix='.
                $mix.'&id='.$id.'&moveSub='.$dim.'&sDown='.$subdim.'", "mainform0");\'>
                <br></div>
            ';
        }
        flush();
    }

    /**
     * This function exports the tool in XML format
     * @param int $mixed
     *      0 --> It is not part of a mixed instrument or xsd header is not desired
     *      1 --> is part of a mixed instrument or xsd header is desired
     * @param string $id
     * @return string XML
     */
    public function export($params = array()) {
        $id = $this->id;

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
        $percentage1 = '';
        if ($mixed == '0') {
            $root = '<cl:ControlList xmlns:cl="http://avanza.uca.es/assessmentservice/controllist"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://avanza.uca.es/assessmentservice/controllist http://avanza.uca.es/assessmentservice/ControlList.xsd"
    ';
            $rootend = '</cl:ControlList>
    ';
        } else if ($mixed == '1') {
            $root = '<ControlList ';
            $rootend = '</ControlList>';
            $percentage1 = ' percentage="' . $this->porcentage . '"';
        }

        $title = (isset($this->titulo)) ? $this->titulo : '';
        $numdim = (isset($this->numdim[$id])) ? $this->numdim[$id] : '';
        $xml = $root . ' id="'.
            $idtool .'" name="' .
            htmlspecialchars($title, ENT_QUOTES) . '" dimensions="' .
            $numdim .'" ' . $percentage1 . '>
        ';

        if (isset($this->observation[$id])) {
            $xml .= '<Description>' . htmlspecialchars($this->observation[$id], ENT_QUOTES) . '</Description>
            ';
        }

        foreach ($this->dimension[$id] as $dim => $itemdim) {
            $dimid = (isset($this->dimensionsid[$id][$dim])) ? $this->dimensionsid[$id][$dim] : '';
            $dimname = (isset($this->dimension[$id][$dim]['nombre'])) ? $this->dimension[$id][$dim]['nombre'] : '';
            $numsubdim = (isset($this->numsubdim[$id][$dim])) ? $this->numsubdim[$id][$dim] : '';
            $dimpor = (isset($this->dimpor[$id][$dim])) ? $this->dimpor[$id][$dim] : '';
            $xml .= '<Dimension id="'.
                $dimid .'" name="' .
                htmlspecialchars($dimname, ENT_QUOTES) . '" subdimensions="' .
                $numsubdim . '" values="2" percentage="' .
                $dimpor . '">
    ';

            $xml .= "<Values>\n";
            foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                $valueid = (isset($this->valoresid[$id][$dim][$grado])) ? $this->valoresid[$id][$dim][$grado] : '';
                $valuename = (isset($this->valores[$id][$dim][$grado]['nombre']))
                    ? $this->valores[$id][$dim][$grado]['nombre'] : '';
                $xml .= '<Value id="'.
                    $valueid.'">'.
                    htmlspecialchars($valuename, ENT_QUOTES) . "</Value>\n";
            }
            $xml .= "</Values>\n";

            foreach ($this->subdimension[$id][$dim] as $subdim => $elemsubdim) {
                $subdimid = (isset($this->subdimensionsid[$id][$dim][$subdim]))
                    ? $this->subdimensionsid[$id][$dim][$subdim] : '';
                $subdimname = (isset($this->subdimension[$id][$dim][$subdim]['nombre']))
                    ? $this->subdimension[$id][$dim][$subdim]['nombre'] : '';
                $numatr = (isset($this->numatr[$id][$dim][$subdim]))
                    ? $this->numatr[$id][$dim][$subdim] : '';
                $subdimpor = (isset($this->subdimpor[$id][$dim][$subdim]))
                    ? $this->subdimpor[$id][$dim][$subdim] : '';

                $xml .= '<Subdimension id="'.
                    $subdimid.'" name="' .
                    htmlspecialchars($subdimname, ENT_QUOTES) . '" attributes="' .
                    $numatr . '" percentage="' .
                    $subdimpor . '">
    ';

                foreach ($this->atributo[$id][$dim][$subdim] as $atrib => $elematrib) {
                    $comment = '';
                    if (isset($this->commentatr[$id][$dim][$subdim][$atrib])
                            && $this->commentatr[$id][$dim][$subdim][$atrib] == 'visible') {
                        $comment = '1';
                    }
                    $atribid = (isset($this->atributosid[$id][$dim][$subdim][$atrib]))
                        ? $this->atributosid[$id][$dim][$subdim][$atrib] : '';
                    $atribname = (isset($this->atributo[$id][$dim][$subdim][$atrib]['nombre']))
                        ? $this->atributo[$id][$dim][$subdim][$atrib]['nombre'] : '';
                    $atribpor = (isset($this->atribpor[$id][$dim][$subdim][$atrib]))
                        ? $this->atribpor[$id][$dim][$subdim][$atrib] : '';

                    $xml .= '<Attribute id="'.
                        $atribid .'" name="' .
                        htmlspecialchars($atribname, ENT_QUOTES) . '" comment="'.
                        $comment .'" percentage="' .
                        $atribpor . '">0</Attribute>
    ';
                }

                $xml .= "</Subdimension>\n";
            }
            $xml .= "</Dimension>\n";
        }

        $xml .= $rootend;

        return $xml;
    }

    public function print_tool($globalcomment = 'global_comment') {
        $id = $this->id;

        echo '
                                <table class="tabla" border=1 cellpadding=5px >

                                <!--TITULO-INSTRUMENTO------------>
        ';
        if (is_numeric($this->porcentage)) {
            echo '
                                <tr>
                                   <td colspan="4">'. get_string('mixed_por', 'block_evalcomix'). ': ' . $this->porcentage.'%</td>
                                </tr>
            ';
        }

        echo '
                                <tr>
                                   <th colspan="4">'.htmlspecialchars($this->titulo, ENT_QUOTES).'</th>
                                </tr>

                                <tr>
                                   <th colspan="4"></th>
                                </tr>


                                <tr>
                                   <td></td>
                                   <td></td>
                                </tr>';
        $i = 0;
        foreach ($this->dimension[$id] as $dim => $value) {
            echo '
                            <tr id="dim">
                                    <!--DIMENSIÓN-TITLE----------->
                                    <td class="pordim">
                                    '.$this->dimpor[$this->id][$dim].'%
                                    </td>
                                    <td class="bold" colspan="1">
                                        <span>'.htmlspecialchars($this->dimension[$this->id][$dim]['nombre'], ENT_QUOTES).'</span>
                                    </td>
            ';
            foreach ($this->valores[$this->id][$dim] as $grado => $elemvalue) {
                echo '
                                    <td class="td">'.
                                    htmlspecialchars($this->valores[$this->id][$dim][$grado]['nombre'], ENT_QUOTES).'</td>
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
                                    <td class="subdim" colspan="3">'.
                                    htmlspecialchars($this->subdimension[$this->id][$dim][$subdim]['nombre'], ENT_QUOTES).
                                    '</td></tr>
                ';
                $j = 0;
                if (isset($this->atributo[$this->id][$dim][$subdim])) {
                    foreach ($this->atributo[$this->id][$dim][$subdim] as $atrib => $elematrib) {
                        echo '
                                <!--ATRIBUTOS---------------------->
                                <tr rowspan=0>
                                    <td class="atribpor">'.$this->atribpor[$this->id][$dim][$subdim][$atrib].'%</td>
                                    <td colspan="1">'.
                                    htmlspecialchars($this->atributo[$this->id][$dim][$subdim][$atrib]['nombre'], ENT_QUOTES).
                                    '</td>
                        ';
                        $k = 1;
                        foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                            $checked = '';

                            if (isset($this->valueattribute[$id][$dim][$subdim][$atrib])
                            && $this->valueattribute[$id][$dim][$subdim][$atrib] == $this->valores[$id][$dim][$grado]['nombre']) {
                                $checked = 'checked';
                            }

                            echo "
                                <td class='td'><input class='custom-radio' type='radio' name='radio".$i.$l.$j."' value='".$k."' ".
                                $checked." style='width:100%'></td>
                            ";
                            ++$k;
                        }

                        echo '
                            </tr>
                        ';

                        if (isset($this->commentatr[$id][$dim][$subdim][$atrib]) &&
                                $this->commentatr[$id][$dim][$subdim][$atrib] == 'visible') {
                            $vcomment = '';
                            if (isset($this->valuecommentAtr[$id][$dim][$subdim][$atrib])) {
                                $vcomment = $this->valuecommentAtr[$id][$dim][$subdim][$atrib];
                            }

                            echo '
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="5">
                                        <textarea rows="2" style="height:6em;width:100%" id=""observaciones'.$i.'_'.$l.'_'.$j.
                                        '" name="observaciones'.$i.'_'.$l.'_'.$j.'" style="width:100%">'.$vcomment.'</textarea>
                                    </td>
                                </tr>
                            ';
                        }

                        ++$j;
                    }

                }
                ++$l;
            }
            ++$i;
        }

        echo '
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
                                    <td style="width:'.$width.'%"><textarea name="observaciones" id="observaciones"
                                    rows=4 cols=20 style="width:100%">'.$globalcomment.'</textarea></td>
                                </tr>
                            </table>
            ';
        }
    }
}
