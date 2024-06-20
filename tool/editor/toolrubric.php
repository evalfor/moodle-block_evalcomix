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

class block_evalcomix_editor_toolrubric extends block_evalcomix_editor {
    private $titulo;
    private $valtotal;
    private $numtotal;
    private $valorestotal;
    private $valtotalpor;
    private $valglobal;
    private $valglobalpor;
    private $filediccionario;
    private $dimpor;
    private $numrango;
    private $rango;
    private $description;
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

    public $rangoid;

    public $descriptionsid;

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
        } else {
             return null;
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

    public function get_numrango() {
        return $this->numrango;
    }

    public function get_rango() {
        return $this->rango[$this->id];
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
        $result = (isset($this->atributosid[$this->id])) ? $this->atributosid[$this->id] : array();
        return $result;
    }

    public function get_valoresid() {
        return $this->valoresid[$this->id];
    }

    public function get_valorestotalesid() {
        return $this->valorestotalesid[$this->id];
    }

    public function get_rangoid() {
        return $this->rangoid[$this->id];
    }

    public function get_descriptionsid() {
        return $this->descriptionsid[$this->id];
    }

    public function get_description() {
        return $this->description[$this->id];
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

    public function set_commentdim($comment) {
        $this->commentdim[$this->id] = $comment;
    }

    public function set_rango($rango) {
        $this->rango = $rango;
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

    public function set_rangoid($valoresid, $id = '') {
        $this->rangoid[$this->id] = $valoresid;
    }

    public function set_descriptionsid($valoresid, $id = '') {
        $this->descriptionsid[$this->id] = $valoresid;
    }


    public function __construct ($lang='es_utf8', $titulo = '', $dimension = array(), $numdim = 1, $subdimension = array(),
            $numsubdim = 1, $atributo = array(), $numatr = 1, $valores = array(), $numvalores = 2, $valtotal = array(),
            $numtotal = 0, $valorestotal = array(), $valglobal = false, $valglobalpor = array(), $dimpor = array(),
            $subdimpor = array(), $atribpor = array(), $commentatr = array(), $commentdim = array(), $id = 0, $observation = '',
            $porcentage = 0, $valtotalpor = array(), $rango = null, $numrango = null, $description = null,
            $valueattribute = null, $valueglobaldim = null, $valuetotalvalue = null, $valuecommentatr = null,
            $valuecommentdim = null, $params = array()) {

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
        $this->rango = $rango;
        $this->numrango = $numrango;
        $this->view = 'design';
        $this->description = $description;
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
        if (!empty($params['rangoid'])) {
            $this->rangoid = $params['rangoid'];
        }
        if (!empty($params['descriptionsid'])) {
            $this->descriptionsid = $params['descriptionsid'];
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

        if (!isset($this->numrango)) {
            $i = 0;
            foreach ($this->valores[$this->id] as $dim => $valor) {
                $i = 0;
                foreach ($valor as $key => $elem) {
                    $this->numrango[$this->id][$dim][$key] = 2;
                    $i++;
                    $this->rango[$this->id][$dim][$key][0] = $i;
                    $i++;
                    $this->rango[$this->id][$dim][$key][1] = $i;
                }
            }
        }
    }

    public function add_dimension($dim, $key, $id) {
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
            $this->commentdim[$this->id][$newindex] = 'hidden';
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
        $this->numvalores[$id][$dimen] = 2;
        $this->numrango[$id][$dimen][0] = 2;
        $this->numrango[$id][$dimen][1] = 2;
        $this->rango[$id][$dimen][0][0] = 1;
        $this->rango[$id][$dimen][0][1] = 2;
        $this->rango[$id][$dimen][1][0] = 3;
        $this->rango[$id][$dimen][1][1] = 4;
        $this->competency[$id][$dim][$subdim] = array();
        $this->outcome[$id][$dim][$subdim] = array();
    }

    public function add_attribute($dim, $subdim, $atrib, $key, $id) {
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

    public function add_values($dim, $key, $id) {
        $id = $this->id;
        $this->numvalores[$id][$dim]++;
        $this->valores[$id][$dim][$key]['nombre'] = get_string('titlevalue', 'block_evalcomix').$this->numvalores[$id][$dim];
        $this->numrango[$id][$dim][$key] = 2;
        $this->rango[$id][$dim][$key][0] = 0;
        $this->rango[$id][$dim][$key][1] = 0;
    }

    public function add_total_values($key, $id) {
        $id = $this->id;
        if (isset($this->numtotal[$id])) {
            $this->numtotal[$id]++;
        } else {
            $this->numtotal[$id] = 1;
        }
        $this->valorestotal[$id][$key]['nombre'] = get_string('titlevalue', 'block_evalcomix').$this->numtotal[$id];
    }

    public function remove_total_values($grado, $id) {
        $id = $this->id;
        if ($this->numtotal > 2) {
            $this->numtotal[$id]--;
            $this->valorestotal[$id] = $this->array_remove($this->valorestotal[$id], $grado);
        }
    }

    public function remove_dimension($dim, $id = 0) {
        $id = $this->id;
        if ($this->numdim[$id] > 1) {
            if ($this->numsubdim[$id][$dim] > 0) {
                $this->numsubdim[$id][$dim]--;
            }
            if ($this->numvalores[$id][$dim] > 2) {
                $this->numvalores[$id][$dim]--;
            }
            $this->dimension[$id] = $this->array_remove($this->dimension[$id], $dim);
            $this->subdimension[$id] = $this->array_remove($this->subdimension[$id], $dim);
            $this->atributo[$id] = $this->array_remove($this->atributo[$id], $dim);
            $this->description[$id] = $this->array_remove($this->description[$id], $dim);
            $this->valores[$id] = $this->array_remove($this->valores[$id], $dim);
            $this->numsubdim = $this->array_remove($this->numsubdim, $dim);
            $this->numatr = $this->array_remove($this->numatr, $dim);
            $this->numdim[$id]--;
        } else {
            echo '<span class="mensaje">'.get_string('alertdimension', 'block_evalcomix').'</span>';
        }
        return 1;
    }

    public function remove_values($dim, $grado, $id) {
        $id = $this->id;
        if ($this->numvalores[$id][$dim] > 2) {
            $this->numvalores[$id][$dim]--;
            $this->valores[$id][$dim] = $this->array_remove($this->valores[$id][$dim], $grado);
            $this->rango[$id][$dim] = $this->array_remove($this->rango[$id][$dim], $grado);
            $this->numrango[$id][$dim] = $this->array_remove($this->numrango[$id][$dim], $grado);
        }
    }

    public function add_range($dim, $grado, $key, $id) {
        $id = $this->id;
        $this->numrango[$id][$dim][$grado]++;
        $this->rango[$id][$dim][$grado][$key] = 0;
    }

    public function remove_range($dim, $grado, $key, $id) {
        $id = $this->id;
        if ($this->numrango[$id][$dim][$grado] > 0) {
            $this->numrango[$id][$dim][$grado]--;
            $this->rango[$id][$dim][$grado] = $this->array_remove($this->rango[$id][$dim][$grado], $key);
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
        if (isset($data['numvalores'.$this->id]) && $data['numvalores'.$this->id] >= 2) {
            $this->numtotal[$this->id] = stripslashes($data['numvalores'.$id]);
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

        $numdimen = count($this->dimension[$this->id]);

        $dim = null;
        $subdim = null;
        $atrib = null;

        echo '
        <div id="cuerpo'.$id.'" class="cuerpo">
            <br>
            <label for="titulo'.$id.'" style="margin-left:1em">'.get_string('rubric', 'block_evalcomix').
            ':</label><span class="labelcampo">
                <textarea class="width" id="titulo'.$id.'" name="titulo'.$id.'">'.$this->titulo.'</textarea></span>
        ';
        if ($this->view == 'design') {
            echo '
            <label for="numdimensiones'.$id.'">'.get_string('numdimensions', 'block_evalcomix').'</label>
            <span class="labelcampo">
                <input type="text" id="numdimensiones'.$id.'" name="numdimensiones'.$id.'" value="'.$this->numdim[$id].'"
                maxlength=2 onkeypress=\'javascript:return validar(event);\'/>
            </span>
            <label for="valtotal'.$id.'">'.get_string('totalvalue', 'block_evalcomix').'</label>
            <input type="checkbox" id="valtotal'.$id.'" name="valtotal'.$id.'" '.$checked.'
            onclick="javascript:if (this.checked)document.getElementById(\'numvalores'.$id.'\').disabled=false;
            else document.getElementById(\'numvalores'.$id.'\').disabled=true;"/>

            <label for="numvalores'.$id.'">'.get_string('numvalues', 'block_evalcomix').'</label>
            <span class="labelcampo">';
        }
        $numtotal = '';
        if (isset($this->numtotal[$id])) {
            $numtotal = $this->numtotal[$id];
        }
        echo '
                <input type="text" id="numvalores'.$id.'" name="numvalores'.$id.'" '.$disabled.' value="'.$numtotal.'"
                maxlength=2 onkeypress=\'javascript:return validar(event);\'/>
            </span>
            <input class="flecha" type="button" id="addDim"
            onclick=\'javascript:if (!validarEntero(document.getElementById("numdimensiones'.$id.'").value)
                || (document.getElementById("valtotal'.$id.'").checked
            && !validarEntero(document.getElementById("numvalores'.$id.'").value))) {
                alert("' . get_string('ADimension', 'block_evalcomix') . '"); return false;}
                sendPost("cuerpo'.$id.'", "mix='.$mix.'&id='.$id.'&addDim=1&titulo'.$id.
                '="+document.getElementById("titulo'.$id.'").value+"&numvalores="+document.getElementById("numvalores'.
                $id.'").value + "&numdimensiones="+ document.getElementById("numdimensiones'.$id.
                '").value +"", "mainform0");\' name="addDim" value=""/>
        ';
        if (isset($mix) && is_numeric($mix)) {
            echo '
            <span class="labelcampo">
                <label for="toolpor_'.$id.'">'.get_string('porvalue', 'block_evalcomix').'</label>
                <input class="porcentaje" type="text" name="toolpor_'.$id.'" id="toolpor_'.$id.'"
                value="'.$this->porcentage.'" onchange=\'document.getElementById("sumpor").value += this.id + "-";\'
                onkeyup=\'javascript:if (document.getElementById("toolpor_'.$id.'").value > 100)
                    document.getElementById("toolpor_'.$id.'").value = 100;\'
                onkeypress=\'javascript:return validar(event);\'/></span>
                <input class="botonporcentaje" type="button"
                onclick=\'javascript:sendPost("body", "id='.$id.'&toolpor_'.$id.'="+document.getElementById("toolpor_'.$id.
                '").value+"&addtool'.$id.'=1", "mainform0");\'>
            </span>';
        }

        echo '<br/>';
        flush();

        foreach ($this->dimension[$id] as $dim => $value) {
            echo '<div class="dimension" id="dimensiontag'.$id.'_'.$dim.'">
                <input type="hidden" name="dimensiontagname" value="'.$id.'_'.$dim.'">
            ';
            $this->display_dimension($dim, $data, $id, $mix);
            echo '</div>';
        }
        if (isset($this->valtotal[$this->id]) && ($this->valtotal[$this->id] == 'true' || $this->valtotal[$this->id] == 't')) {
            echo '
                <div class="valoraciontotal">
            ';
            if ($this->view == 'design') {
                echo '
                    <input type="button" class="delete" onclick=\'javascript:document.getElementById("valtotal'.$id.
                    '").checked=false;sendPost("cuerpo'.$id.'", "mix='.$mix.'&id='.$id.'&addDim=1&valtotal=false", "mainform0");\'>
                ';
            }
            echo '
                        <div class="margin">
                            <label for="numdimensiones">'.strtoupper(get_string('totalvalue', 'block_evalcomix')).':</label>
                            <span class="labelcampo"></span>
            ';
            if ($this->view == 'design') {
                echo '
                            <label for="numvalorestotal">'.get_string('numvalues', 'block_evalcomix').'</label>
                            <span class="labelcampo">
                            <input type="text" id="numvalores_'.$id.'" name="numvalores'.$id.'"
                            value="'.$this->numtotal[$id].'" maxlength=2
                            onkeyup=\'javascript:var valores=document.getElementsByName("numvalores'.$id.'");
                            for (var i=0; i<valores.length; i++) {valores[i].value=this.value;}\'
                            onkeypress=\'javascript:return validar(event);\'/>
                            <input class="flecha" type="button" id="addDim"
                            onclick=\'javascript:if (!validarEntero(document.getElementById("numvalores_'.$id.'").value)) {
                                alert("' . get_string('ATotal', 'block_evalcomix') . '"); return false;}
                                sendPost("cuerpo'.$id.'", "mix='.$mix.'&id='.$id.
                                '&addDim=1&numvalores="+document.getElementById("numvalores_'.$id.'").value + "", "mainform0");\'
                                name="addDim" value=""/>
                ';
            }
            echo '
                            <span class="labelcampo"><label for="dimpor">'.get_string('porvalue', 'block_evalcomix').
                            ':</label><span class="labelcampo">
                            <input class="porcentaje" type="text" name="valtotalpor'.$id.'" id="valtotalpor'.$id.'"
                            value="'.$this->valtotalpor[$id].'" onchange=\'document.getElementById("sumpor3'.$id.
                            '").value += this.id + "-";\' onkeyup=\'javascript:if (document.getElementById("valtotalpor'.
                            $id.'").value > 100)document.getElementById("valtotalpor'.$id.'").value = 100;\'
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
                echo '<td><input type="radio" name="radio'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'" /></td>
                ';
            }
            echo '</tr></table>
                    </div>
                </div>';
        }
        if (!is_numeric($mix)) {
            if (!empty($data['observation'.$id])) {
                $this->observation[$id] = stripslashes($data['observation'.$id]);
            } else if (isset($data['save']) && $data['save'] == '1') {
                $this->observation[$id] = '';
            }
            $observation = '';
            if (isset($this->observation[$id])) {
                $observation = $this->observation[$id];
            }
            echo '
                <div id="comentario">
                    <div id="marco">
                        <label for="observation'.$id.'">' . get_string('observation', 'block_evalcomix'). ':</label>
                        <textarea id="observation'.$id.'" style="width:100%" rows="4" cols="200">' .
                        $observation . '</textarea>
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

    public function display_dimension ($dim = 0, $data = array(), $id = 0, $mix = '') {
        $id = $this->id;
        $subdim = null;
        $atrib = null;
        if (isset($data['dimension'.$id.'_'.$dim])) {
            $this->dimension[$id][$dim]['nombre'] = stripslashes($data['dimension'.$id.'_'.$dim]);
        }
        if (isset($data['valglobal'.$id.'_'.$dim])) {
            $this->valglobal[$id][$dim] = stripslashes($data['valglobal'.$id.'_'.$dim]);
        }
        $checked = '';
        $globalchecked = '';
        if (isset($this->valglobal[$id][$dim]) && $this->valglobal[$id][$dim] == "true") {
            $globalchecked = 'checked';
        }

        if ($this->view == 'design') {
            $numtotal = '';
            if (isset($this->numtotal[$id])) {
                $numtotal = $this->numtotal[$id];
            }
            echo '
        <div>
            <input type="button" class="delete" onclick=\'javascript:sendPost("cuerpo'.$id.'","mix='.$mix.'&id='.
            $id.'&titulo'.$id.'="+document.getElementById("titulo'.$id.'").value+"&addDim=1&numvalores='.$numtotal.
            '&dd='.$dim.'", "mainform0");\'>
            <input type="button" class="up" onclick=\'javascript:sendPost("cuerpo'.$id.'","mix='.$mix.'&id='.$id.
            '&titulo'.$id.'="+document.getElementById("titulo'.$id.'").value+"&moveDim=1&numvalores='.$numtotal.
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
                name="numsubdimensiones'.$dim.'" value="'.$this->numsubdim[$id][$dim].'" maxlength=2
                onkeypress=\'javascript:return validar(event);\'/></span>

            <label for="numvalores'.$id.'_'.$dim.'">'.get_string('numvalues', 'block_evalcomix').'</label>
            <span class="labelcampo"><input type="text" id="numvalores'.$id.'_'.$dim.'" name="numvalores'.$id.'_'.$dim.'"
            value="'.$this->numvalores[$id][$dim].'" maxlength=2 onkeypress=\'javascript:return validar(event);\'/></span>
            <label for="valtotal'.$id.'">'.get_string('totalvalue', 'block_evalcomix').'</label>
            <input type="checkbox" id="valglobal'.$id.'_'.$dim.'" name="valglobal'.$id.'_'.$dim.'" '.$globalchecked.' />

            <input class="flecha" type="button" id="addSubDim" name="addSubDim"
            onclick=\'javascript:if (!validarEntero(document.getElementById("numvalores'.$id.'_'.$dim.'").value)
                || !validarEntero(document.getElementById("numsubdimensiones'.$id.'_'.$dim.'").value)) {
                    alert("' . get_string('ASubdimension', 'block_evalcomix') . '"); return false;}sendPost("dimensiontag'.
                    $id.'_'.$dim.
                    '", "mix='.$mix.'&id='.$id.'&addSubDim="+this.value+"&numvalores'.$id.'_'.$dim.
                    '="+ document.getElementById("numvalores'.$id.'_'.$dim.'").value +"&numsubdimensiones'.$id.'_'.$dim.
                    '="+ document.getElementById("numsubdimensiones'.$id.'_'.$dim.'").value +"", "mainform0");\'
                    style="font-size:1px" value="'.$dim.'"/>
            ';
        }
        echo '
            <span class="labelcampo"><label for="dimpor'.$id.'_'.$dim.'">'.get_string('porvalue', 'block_evalcomix').
            '</label><span class="labelcampo">
            <input class="porcentaje" type="text" maxlength="3" name="dimpor'.$id.'_'.$dim.'"
            id="dimpor'.$id.'_'.$dim.'" value="'.$this->dimpor[$id][$dim].'"
            onchange=\'javascript:document.getElementById("sumpor3'.$id.'").value += this.id +"-";;\'
            onkeyup=\'javascript:if (document.getElementById("dimpor'.$id.'_'.$dim.'").value > 100)
                document.getElementById("dimpor'.$id.'_'.$dim.'").value = 100;\'
            onkeypress=\'javascript:return validar(event);\'/></span>
            <input class="botonporcentaje" type="button" onclick=\'javascript:
            sendPost("cuerpo'.$id.'", "mix='.$mix.'&id='.$id.'&dimpor'.$id.'="+document.getElementById("dimpor'.$id.'_'.$dim.
            '").value+"&dpi='.$dim.'&addDim=1", "mainform0");\'></span>
            <br>
            <div id="rubrica">
            ';

        if ($this->view == 'design') {
            echo "<i><span style='color:#1c38e2''>
                    <!--RECUERDE: Los valores deberán ser colocados de MENOR A MAYOR.
                    En caso contrario, se reordenarán automáticamente<br>-->
                    ".get_string('rubricremember', 'block_evalcomix')."<br>
                    <!--RECUERDE: El límite MÁXIMO de la escala es 100.
                    Una vez alcanzado el límite no se admitirán nuevos valores-->
                </span></i><br>";
            foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                if (isset($data['valor'.$id.'_'.$dim.'_'.$grado])) {
                    $this->valores[$id][$dim][$grado]['nombre'] = stripslashes($data['valor'.$id.'_'.$dim.'_'.$grado]);
                }
                echo '
                <div class="rango">
                    <label for="rango'.$id.'_'.$dim.'_1">'.get_string('titlevalue', 'block_evalcomix').':</label><br>
                    <input type="text" class="itemrango1" id="valor'.$id.'_'.$dim.'_'.$grado.'"
                    name="valor'.$id.'_'.$dim.'_'.$grado.'"
                    value="'.htmlspecialchars($this->valores[$id][$dim][$grado]['nombre'], ENT_QUOTES).'"
                    onkeyup=\'javascript:var valores=document.getElementsByName("valor'.$id.'_'.$dim.'_'.$grado.'");
                    for (var i=0; i<valores.length; i++) {valores[i].value=this.value;}\'/><br><br>
                    <label for="numrango'.$id.'_'.$dim.'_'.$grado.'">'.get_string('numvalues', 'block_evalcomix').'</label><br>
                    <input type="text" class="itemrango2" id="numrango'.$id.'_'.$dim.'_'.$grado.'"
                    onkeypress=\'javascript:return validar(event);\' maxlength="2"
                    name="numrango'.$id.'_'.$dim.'_'.$grado.'" value="'.$this->numrango[$id][$dim][$grado].'"/>
                    <input class="flecha" type="button" id="addrango'.$id.'_'.$dim.'_'.$grado.'"
                    name="addrango'.$id.'_'.$dim.'_'.$grado.'" onclick=\'javascript:
                        var valor = document.getElementById("numrango'.$id.'_'.$dim.'_'.$grado.'");
                        if (valor.value <= 0) {
                            alert("Debe existir, al menos, un valor en el rango");
                            return false;
                        }
                        /*var size = valores.length;
                        var last = size - 1;
                        if (valores[last].id == valores[last].value == 100) {
                            alert("El límite máximo de la escala es 100. Ya ha alcanzado el límite máximo.
                            Recuerde que la escala es ascendente, por lo que no podrá añadir valores superiores a 100.\n
                            Para añadir nuevos valores intente reconfigurar la escala.");return false;
                        }*/
                        sendPost("dimensiontag'.$id.'_'.$dim.'", "mix='.$mix.'&id='.$id.'&addSubDim="+'.$dim.
                        '+"&addrango="+this.value+"", "mainform0");\' style="font-size:1px" value="'.$dim.'_'.$grado.'"/><br><br>
                ';

                foreach ($this->rango[$id][$dim][$grado] as $key => $rango) {
                    echo '
                    <select id= "select'.$id.'_'.$dim.'_'.$grado.'_'.$key.'" name="select'.$id.'_'.$dim.'" onchange=\'javascript:
                    /*var valores=document.getElementsByName("select'.$id.'_'.$dim.'");
                    for (var i=0; i<valores.length; i++) {
                        var repeated = 0;
                        if (valores[i].id != this.id && valores[i].value==this.value) {
                            //alert("Ha introducido un valor que ya existe en la escala.
                            \n\nRecuerde: NO puede haber VALORES REPETIDOS");
                            valores[i].style.border="1px solid #f00";
                            repeated = 1;
                            //this.value = '. $rango .'
                            //return false;
                            //break;
                        }
                    }
                    if (repeated == 1) {
                        return false;
                    }*/
                    sendPost("dimensiontag'.$id.'_'.$dim.'", "mix='.$mix.'&id='.$id.'&addSubDim="+'.
                    $dim.'+"&idrango="+this.id+"&sel="+this.value+"&addrango='.$dim.'_'.$grado.
                    '", "mainform0");\' id="rango'.$id.'_'.$dim.'_'.$grado.'_'.$key.'">';
                    for ($i = 0; $i <= 100; $i++) {
                        $selected = '';
                        if ($this->rango[$id][$dim][$grado][$key] == $i) {
                            $selected = 'selected';
                        }
                        echo '<option '.$selected.' value='.$i.'>'.$i.'</option>';
                    }
                    echo
                    '</select>';
                }
                echo'
                </div>
                ';
            }
        }
        echo '<div class="clear"></div>
            </div>
        ';

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
                    <!--<input type="hidden" id="sumpor'.$id.'_'.$dim.'_'.$subdim.'" value=""/>-->
                    <input type="button" class="delete"
                    onclick=\'javascript:document.getElementById("valglobal'.$id.'_'.$dim.'").checked=false;
                    sendPost("dimensiontag'.$id.'_'.$dim.'", "mix='.$mix.'&id='.$id.'&addSubDim='.$dim.
                    '&valglobal'.$id.'_'.$dim.'=false", "mainform0");\'>
                ';
            }
            echo '
                    <div class="margin">
                        <label>'.get_string('globalvalue', 'block_evalcomix').'</label>
                        <span class="labelcampo"></span>
                        <span class="labelcampo"><label for="name="valglobalpor'.$id.'_'.$dim.'">'.
                        get_string('porvalue', 'block_evalcomix').
                        '</label><span class="labelcampo">
                        <input class="porcentaje" type="text" maxlength="3"  name="valglobalpor'.$id.'_'.$dim.'"
                        id="valglobalpor'.$id.'_'.$dim.'" value="'.$this->valglobalpor[$id][$dim].'"
                        onchange=\'document.getElementById("sumpor2'.$id.'_'.$dim.'").value += this.id + "-";\'
                        onkeyup=\'javascript:if (document.getElementById("valglobalpor'.$id.'_'.$dim.'").value > 100)
                            document.getElementById("valglobalpor'.$id.'_'.$dim.'").value = 100;\'
                        onkeypress=\'javascript:return validar(event);\'/></span>
                        <input class="botonporcentaje" type="button"
                        onclick=\'javascript:sendPost("dimensiontag'.$id.'_'.$dim.'", "mix='.$mix.'&id='.$id.
                        '&subdimpor="+document.getElementById("valglobalpor'.$id.'_'.$dim.'").value+"&spi=vg&addSubDim='.$dim.
                        '", "mainform0");\'></span>

                        <table class="maintable">
            ';
            foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                if (isset($data['valor'.$id.'_'.$dim.'_'.$grado])) {
                    $this->valores[$id][$dim][$grado]['nombre'] = stripslashes($data['valor'.$id.'_'.$dim.'_'.$grado]);
                }
                echo '<th class="grado" colspan="'.$this->numrango[$id][$dim][$grado].'"><input class="valores"
                type="text" id="valor'.$id.'_'.$dim.'_'.$grado.'" name="valor'.$id.'_'.$dim.'_'.$grado.'"
                value="'.htmlspecialchars($this->valores[$id][$dim][$grado]['nombre'], ENT_QUOTES).'"
                onkeyup=\'javascript:var valores=document.getElementsByName("valor'.$id.'_'.$dim.'_'.$grado.'");
                for (var i=0; i<valores.length; i++) {valores[i].value=this.value;}\'/></th>
                ';
            }
            echo '<tr>';
            foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                foreach ($this->rango[$id][$dim][$grado] as $key => $rango) {
                    echo '
                    <td class="descripcion">
                        <input type="radio" id="radio'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'_'.$grado.'_'.$key.'"
                        name="radio'.$id.'_'.$dim.'_'.$atrib.'" />
                        <label for="radio'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'_'.$grado.'_'.$key.'">'.
                        $this->rango[$id][$dim][$grado][$key].'</label>
                    </td>
                    ';
                }
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

            $totalrange = 0;
            foreach ($this->numrango[$id][$dim] as $grado) {
                $totalrange += $grado;
            }

            echo '<tr>
                <td colspan="'.$totalrange.'">';

            if ($this->view == 'design') {
                echo '
                <div>
                    <input type="button" class="showcomment" title="'. get_string('add_comments', 'block_evalcomix') .'"
                        onclick=\'javascript:sendPost("dimensiontag'.$id.'_'.$dim.'", "mix='.$mix.'&id='.$id.
                        '&commentdim'.$id.'_'.$dim.'='.$novisible.'&comDim='.$dim.'&addSubDim='.$dim.'", "mainform0");\'>
                </div>';
            }

            echo '
                </td></tr><tr>
                <td colspan="'.$totalrange.'">';

            if ($visible == 'visible') {
                $divheight = 'height:2.5em';
                $textheight = 'height:2em';
            } else {
                $divheight = 'height:0em';
                $textheight = 'height:0em';
            }
            echo '<div class="atrcomment" id="atribcomment'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'" style="'.$divheight.'">
            <textarea disabled style="width:97%; visibility:'.$visible.'; '.$textheight.'"
            id="atributocomment'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'"></textarea>
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
                    <input type="button" class="add" onclick=\'javascript:sendPost("cuerpo'.$id.'","mix='.$mix.
                    '&id='.$id.'&titulo'.$id.'="+document.getElementById("titulo'.$id.
                    '").value+"&addDim=1&numvalores="+document.getElementById("numvalores'.$id.'").value + "&ad='.$dim.
                    '", "mainform0");\'>
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

    public function display_subdimension($dim, $subdim, $data, $id = 0, $mix = '') {
        $id = $this->id;

        if (isset($data['subdimension'.$dim.'_'.$subdim]) && empty($data['modalclose'])) {
            $this->subdimension[$id][$dim][$subdim]['nombre'] = stripslashes($data['subdimension'.$dim.'_'.$subdim]);
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
                    <div class="float-md-left mr-2">
                        <label for="subdimension'.$id.'_'.$dim.'_'.$subdim.'">'.get_string('subdimension', 'block_evalcomix').
                        '</label>
                        <span class="labelcampo"><textarea  class="width" id="subdimension'.$dim.'_'.$subdim.'"
                        name="subdimension'.$dim.'_'.$subdim.'">'.$this->subdimension[$id][$dim][$subdim]['nombre'].
                        '</textarea></span>
                    </div>
        ';
        if ($this->view == 'design') {
            echo '
                <div class="float-md-left mr-2">
                    <label for="numatributos'.$id.'_'.$dim.'_'.$subdim.'">'.get_string('numattributes', 'block_evalcomix').
                    '</label>
                    <span class="labelcampo"><input type="text" id="numatributos'.$id.'_'.$dim.'_'.$subdim.
                    '" name="numatributos'.$id.'_'.$dim.'_'.$subdim.'" value="'.$this->numatr[$id][$dim][$subdim].
                    '" maxlength=2 onkeypress=\'javascript:return validar(event);\'/></span>
                    <input class="flecha" type="button" id="addAtr" name="addAtr" style="font-size:1px"
                    onclick=\'javascript:if (!validarEntero(document.getElementById("numatributos'.$id.'_'.$dim.'_'.$subdim.
                    '").value)) {alert("' . get_string('AAttribute', 'block_evalcomix') . '");
                    return false;}sendPost("subdimensiontag'.$id.'_'.
                    $dim.'_'.$subdim.'", "mix='.$mix.'&id='.$id.'&addAtr="+this.value+"&numatributos'.$id.'_'.$dim.'_'.$subdim.
                    '="+ document.getElementById("numatributos'.$id.'_'.$dim.'_'.$subdim.'").value +"", "mainform0");\'
                    value="'.$dim.'_'.$subdim.'"/>
                </div>
            ';
        }
        echo '
                <div class="float-md-left mr-2">
                    <span class="labelcampo"><label for="subdimpor'.$id.'_'.$dim.'_'.$subdim.'">'.
                    get_string('porvalue', 'block_evalcomix').
                    '</label><span class="labelcampo">
                    <input class="porcentaje" type="text" maxlength="3" id="subdimpor'.$id.'_'.$dim.'_'.$subdim.'"
                    name="subdimpor'.$id.'_'.$dim.'_'.$subdim.'" value="'.$this->subdimpor[$id][$dim][$subdim].'"
                    onchange=\'document.getElementById("sumpor2'.$id.'_'.$dim.'").value += this.id + "-";\'
                    onkeyup=\'javascript:if (document.getElementById("subdimpor'.$id.'_'.$dim.'_'.$subdim.'").value > 100)
                        document.getElementById("subdimpor'.$id.'_'.$dim.'_'.$subdim.'").value = 100;\'
                    onkeypress=\'javascript:return validar(event);\'/></span>
                    <input class="botonporcentaje" type="button" onclick=\'javascript:sendPost("dimensiontag'.$id.'_'.$dim.
                    '", "mix='.$mix.'&id='.$id.'&subdimpor="+document.getElementById("subdimpor'.$id.'_'.$dim.'_'.$subdim.
                    '").value+"&spi='.$subdim.'&addSubDim='.$dim.'", "mainform0");\'>
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
        foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
            if (isset($data['valor'.$id.'_'.$dim.'_'.$grado])) {
                $this->valores[$id][$dim][$grado]['nombre'] = stripslashes($data['valor'.$id.'_'.$dim.'_'.$grado]);
            }
            echo '
            <th class="grado" colspan="'.$this->numrango[$id][$dim][$grado].'">
                <input style="background-color:#DDE0DD;height:  2em" type="text" class="itemrango1"
                id="valor'.$id.'_'.$dim.'_'.$grado.'" name="valor'.$id.'_'.$dim.'_'.$grado.'"
                value="'.htmlspecialchars($this->valores[$id][$dim][$grado]['nombre'], ENT_QUOTES).'"
                onkeyup=\'javascript:var valores=document.getElementsByName("valor'.$id.'_'.$dim.'_'.$grado.'");
                for (var i=0; i<valores.length; i++) {valores[i].value=this.value;}\'/>
            </th>
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

                echo '
                <tr rowspan="2">
                    <td style="">
                ';
                if ($this->view == 'design') {
                    echo '
                        <div style="margin-bottom:2em;">
                            <input type="button" class="delete"
                            onclick=\'javascript:sendPost("subdimensiontag'.$id.'_'.$dim.'_'.$subdim.'", "mix='.$mix.
                            '&id='.$id.'&addAtr='.$dim.'_'.$subdim.'&dt='.$atrib.'", "mainform0");\'>
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


                    <td>
                        <input class="porcentaje" type="text" onchange=\'document.getElementById("sumpor'.
                        $id.'_'.$dim.'_'.$subdim.'").value += this.id + "-";\'
                        onkeyup=\'javascript:if (document.getElementById("atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.
                        '").value > 100)document.getElementById("atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'").value = 100;\'
                        onkeypress=\'javascript:return validar(event);\'  maxlength="3"
                        name="atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'" id="atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.
                        '" value="'.$this->atribpor[$id][$dim][$subdim][$atrib].'"/>
                        <input class="botonporcentaje" type="button" onclick=\'javascript:sendPost("subdimensiontag'.
                        $id.'_'.$dim.'_'.$subdim.'", "mix='.$mix.'&id='.$id.'&atribpor="+document.getElementById("atribpor'.
                        $id.'_'.$dim.'_'.$subdim.'_'.$atrib.'").value+"&api='.$atrib.'&addAtr='.$dim.'_'.$subdim.
                        '", "mainform0");\'>
                    </td>
                    <td>
                        <span class="font"><textarea class="width" id="atributo'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'"
                        name="atributo'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'">'.
                        $this->atributo[$id][$dim][$subdim][$atrib]['nombre'].'</textarea></span>
                    </td>
                ';
                foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                    if (isset($data['descripcion'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'_'.$grado])) {
                        $this->description[$id][$dim][$subdim][$atrib][$grado] = stripslashes($data['descripcion'.
                        $id.'_'.$dim.'_'.$subdim.'_'.$atrib.'_'.$grado]);
                    }

                    $description = '';
                    if (isset($this->description[$id][$dim][$subdim][$atrib][$grado])) {
                        $description = $this->description[$id][$dim][$subdim][$atrib][$grado];
                    }
                    echo '
                    <td colspan="'.$this->numrango[$id][$dim][$grado].'">
                        <textarea rows="3" cols="30" id="descripcion'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'_'.$grado.'">'
                        .$description.'</textarea>
                    ';
                }
                echo '
                </tr>
                <tr>
                    <td></td><td></td><td></td><td/>
                ';
                foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                    foreach ($this->rango[$id][$dim][$grado] as $key => $rango) {
                        echo '
                    <td class="descripcion">
                        <input type="radio" id="radio'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'_'.$grado.'_'.$key.'"
                        name="radio'.$id.'_'.$dim.'_'.$atrib.'" />
                        <label for="radio'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'_'.$grado.'_'.$key.'">'.
                        $this->rango[$id][$dim][$grado][$key].'</label>
                    </td>
                    ';
                    }
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
                echo '
                <tr>
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
                $totalrange = 0;
                foreach ($this->numrango[$id][$dim] as $grado) {
                    $totalrange += $grado;
                }
                echo '
                </td><td/><td/>
                <td colspan="'.$totalrange.'">';

                if ($visible == 'visible') {
                    $divheight = 'height:2.5em';
                    $textheight = 'height:2em';
                } else {
                    $divheight = 'height:0.5em';
                    $textheight = 'height:0.5em';
                }
                echo '<div class="atrcomment" id="atribcomment'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'"
                style="'.$divheight.'">
                <textarea disabled style="width:97%; visibility:'.$visible.'; '.$textheight.'; background-color:#ffffb5"
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
                <div>
                    <input type="button" class="add" onclick=\'javascript:sendPost("dimensiontag'.$id.'_'.
                    $dim.'", "mix='.$mix.'&id='.$id.'&addSubDim='.$dim.'&sd='.$subdim.'&aS=1'.'", "mainform0");\'>
                    <input type="button" class="down" onclick=\'javascript:sendPost("dimensiontag'.$id.'_'.
                    $dim.'", "mix='.$mix.'&id='.$id.'&moveSub='.$dim.'&sDown='.$subdim.'", "mainform0");\'>
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
            $root = '<ru:Rubric xmlns:ru="http://avanza.uca.es/assessmentservice/rubric"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://avanza.uca.es/assessmentservice/rubric http://avanza.uca.es/assessmentservice/Rubric.xsd"
        ';
            $rootend = '</ru:Rubric>
        ';
        } else if ($mixed == '1') {
            $root = '<Rubric ';
            $rootend = '</Rubric>';
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
            $dimid = (empty($this->dimensionsid[$id][$dim])) ? '' : $this->dimensionsid[$id][$dim];
            $numsubdim = (isset($this->numsubdim[$id][$dim])) ? $this->numsubdim[$id][$dim] : '';
            $dimname = (isset($this->dimension[$id][$dim]['nombre'])) ? $this->dimension[$id][$dim]['nombre'] : '';
            $numvaloresdim = (isset($this->numvalores[$id][$dim])) ? $this->numvalores[$id][$dim] : 0;
            $dimpor = (isset($this->dimpor[$id][$dim])) ? $this->dimpor[$id][$dim] : '';
            $xml .= '<Dimension id="'.$dimid.'" name="' .
                htmlspecialchars($dimname, ENT_QUOTES) .
                '" subdimensions="' . $numsubdim .
                '" values="' . $numvaloresdim .
                '" percentage="' . $dimpor . '">
        ';
            $xml .= "<Values>\n";

            foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                $xml .= '<Value id="'.$this->valoresid[$id][$dim][$grado].'" name="' .
                    htmlspecialchars($this->valores[$id][$dim][$grado]['nombre'], ENT_QUOTES) . "\" instances=\"" .
                    $this->numrango[$id][$dim][$grado] . "\">\n";
                foreach ($this->rango[$id][$dim][$grado] as $key => $rango) {
                    $rangoid = (isset($this->rangoid[$id][$dim][$grado][$key])) ? $this->rangoid[$id][$dim][$grado][$key] : '';
                    $rangoname = (isset($this->rango[$id][$dim][$grado][$key])) ? $this->rango[$id][$dim][$grado][$key] : '';
                    $xml .= '<instance id="'.$rangoid.'">'. $rangoname . "</instance>\n";
                }
                $xml .= "</Value>\n";
            }
            $xml .= "</Values>\n";

            foreach ($this->subdimension[$id][$dim] as $subdim => $elemsubdim) {
                $xml .= '<Subdimension id="'.$this->subdimensionsid[$id][$dim][$subdim].'" name="' .
                    htmlspecialchars($this->subdimension[$id][$dim][$subdim]['nombre'], ENT_QUOTES) . '" attributes="' .
                    $this->numatr[$id][$dim][$subdim] . '" percentage="' . $this->subdimpor[$id][$dim][$subdim] . '">
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
                        $atribpor . '">
                    <descriptions>'."\n";
                    $j = 0;
                    foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                        $desid = (isset($this->descriptionsid[$id][$dim][$subdim][$atrib][$grado]))
                            ? $this->descriptionsid[$id][$dim][$subdim][$atrib][$grado] : '';
                        $desc = (isset($this->description[$id][$dim][$subdim][$atrib][$grado]))
                            ? $this->description[$id][$dim][$subdim][$atrib][$grado] : '';

                        $xml .= '<description id="'.
                            $desid.'" value="' . $j . '">' .
                            htmlspecialchars($desc, ENT_QUOTES) . "</description>\n";
                        $j++;
                    }
                    $xml .= '</descriptions>
                    <selection>
                        <val>0</val>
                        <instance>0</instance>
                        </selection>
        </Attribute>
        ';
                }

                $xml .= "</Subdimension>\n";
            }

            if (isset($this->valglobal[$id][$dim]) && ($this->valglobal[$id][$dim] == 'true'
                    || $this->valglobal[$id][$dim] == 't')) {
                $comment = '';
                if ($this->commentdim[$id][$dim] == 'visible') {
                    $comment = '1';
                }
                $xml .= '<DimensionAssessment percentage="' . $this->valglobalpor[$id][$dim] . '">
                    <Attribute name="Global assessment" comment="'.$comment.'" percentage="0">
                    <descriptions>'."\n";

                $j = 0;
                foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                    $xml .= '<description value="' . $j . '"></description>'."\n";
                    ++$j;
                }
                $xml .= '</descriptions>
                        <selection>
                            <val>0</val>
                            <instance>0</instance>
                        </selection>'."\n";

                $xml .= '</Attribute>
                </DimensionAssessment>';
            }

            $xml .= "</Dimension>\n";
        }

        if (isset($this->valtotal[$id]) && ($this->valtotal[$id] == 'true' || $this->valtotal[$id] == 't')) {
            $xml .= '<GlobalAssessment values="' . $this->numtotal[$id] . '" percentage="'.$this->valtotalpor[$id].'">
                <Values>
        ';
            foreach ($this->valorestotal[$id] as $grado => $elemvalue) {
                $totalvalue = (isset($this->valorestotalesid[$id][$grado])) ? $this->valorestotalesid[$id][$grado] : '';
                $totalname = (isset($this->valorestotal[$id][$grado]['nombre'])) ? $this->valorestotal[$id][$grado]['nombre'] : '';
                $xml .= '<Value id="'.$totalvalue.'">'. htmlspecialchars($totalname, ENT_QUOTES) . "</Value>\n";
            }
            $xml .= '</Values>

                <Attribute name="Global assessment" percentage="0">0</Attribute>
            </GlobalAssessment>
        ';
        }
        $xml .= $rootend;

        return $xml;
    }

    public function print_tool($globalcomment = 'global_comment') {
        $id = $this->id;
        $max = 0;
        $maxrango = array();
        foreach ($this->dimension[$id] as $dim => $value) {
            $maxrango[$dim] = array_sum($this->numrango[$id][$dim]);
        }
        $colspan = max($maxrango);

        echo '
                                <table class="tabla" border=1 cellpadding=5px >

                                <!--TITULO-INSTRUMENTO------------>
        ';
        if (is_numeric($this->porcentage)) {
            echo '
                                <tr>
                                   <td colspan="'.($colspan + 2) .'">'. get_string('mixed_por', 'block_evalcomix'). ': ' .
                                   $this->porcentage.'%</td>
                                </tr>
                ';
        }

        echo '
                                </tr>
                                <tr>
                                   <th colspan="'.($colspan + 2) .'">'.htmlspecialchars($this->titulo, ENT_QUOTES).'</th>
                                </tr>

                                <tr>
                                   <th colspan="'.($colspan + 2) .'"></th>
                                </tr>


                                <tr>
                                   <td></td>
                                   <td></td>
                                </tr>';
        $i = 0;
        foreach ($this->dimension[$id] as $dim => $value) {
            $colspandim = array_sum($this->numrango[$id][$dim]);

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
            foreach ($this->valores[$this->id][$dim] as $grado => $elemvalue) {
                echo '
                                    <td class="td" colspan='.$this->numrango[$id][$dim][$grado].'>'.
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
                                    <td class="subdim" colspan="'.($colspan + 1).'">'.
                                    htmlspecialchars($this->subdimension[$this->id][$dim][$subdim]['nombre'], ENT_QUOTES).
                                    '</td></tr>
                ';

                if (isset($this->atributo[$this->id][$dim][$subdim])) {
                    $j = 0;
                    foreach ($this->atributo[$this->id][$dim][$subdim] as $atrib => $elematrib) {
                        echo '
                                <!--ATRIBUTOS---------------------->
                                <tr rowspan=2>
                                <td class="atribpor">'.$this->atribpor[$this->id][$dim][$subdim][$atrib].'%</td>
                                <td colspan="'. ($colspan - $colspandim + 1) .'">'.
                                htmlspecialchars($this->atributo[$this->id][$dim][$subdim][$atrib]['nombre'], ENT_QUOTES).'</td>
                        ';

                        foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                            echo '<td colspan="'.$this->numrango[$id][$dim][$grado].'">'.
                            htmlspecialchars($this->description[$id][$dim][$subdim][$atrib][$grado], ENT_QUOTES).'</td>';
                        }
                        echo '
                                </tr>
                                <tr><td colspan="'. ($colspan - $colspandim + 2) .'"></td>
                        ';
                        $k = 0;
                        foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                            $m = 0;
                            foreach ($this->rango[$id][$dim][$grado] as $key => $rango) {
                                $checked = '';
                                if (isset($this->valueattribute[$id][$dim][$subdim][$atrib]) &&
                                    $this->valueattribute[$id][$dim][$subdim][$atrib] == $this->rango[$id][$dim][$grado][$key]) {
                                    $checked = 'checked';
                                }
                                echo '<td class="descripcion" style="text-align:center"    >' .
                                $this->rango[$id][$dim][$grado][$key] .'
                                    <div><input type="radio" class="custom-radio" id="radio' . $i . $l . $j . $k . $m .
                                    '" name="radio' . $i . $l . $j . '" '. $checked .' value=' .
                                    $this->rango[$id][$dim][$grado][$key] . '></div>
                                    </td>';
                                ++$m;
                            }
                            ++$k;
                        }

                        echo '
                                </tr>
                                <tr>
                                    <td colspan="'. ($colspan - $colspandim + 2) .'"></td>
                        ';
                        if (isset($this->commentatr[$id][$dim][$subdim][$atrib])
                                && $this->commentatr[$id][$dim][$subdim][$atrib] == 'visible') {
                            $vcomment = '';
                            if (isset($this->valuecommentAtr[$id][$dim][$subdim][$atrib])) {
                                $vcomment = $this->valuecommentAtr[$id][$dim][$subdim][$atrib];
                            }
                            echo '
                                    <td colspan="'.$colspan.'">
                                        <textarea rows="2" style="height:6em;width:100%" id="observaciones'.
                                        $dim.'_'.$subdim.'_'.$atrib.'" name="observaciones'.$dim.'_'.$subdim.'_'.$atrib.
                                        '" style="width:100%">'.$vcomment.'</textarea>
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
                            <td class='global' colspan='".($colspan - $colspandim + 1) ."'>".
                            get_string('globalvalue', 'block_evalcomix')."</td>
                ";

                foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                    foreach ($this->rango[$id][$dim][$grado] as $key => $rango) {
                        $checked = '';
                        if (isset($this->valueglobaldim[$id][$dim]) &&
                        $this->valueglobaldim[$id][$dim] == $this->rango[$id][$dim][$grado][$key]) {
                            $checked = 'checked';
                        }
                        echo '<td class="descripcion" style="text-align:center"    >' .
                        $this->rango[$id][$dim][$grado][$key] . '
                                <div><input type="radio" class="custom-radio" id="radio' . $i . '" name="radio' . $i . '" '.
                                $checked .' value=' . $this->rango[$id][$dim][$grado][$key] . '></div>
                                </td>';
                    }
                }
                echo "
                        </tr>
                        <tr>
                            <td colspan='".($colspan - $colspandim + 2) ."'></td>
                ";
                if (isset($this->commentdim[$id][$dim]) && $this->commentdim[$id][$dim] == 'visible') {
                    $vcomment = '';
                    if (isset($this->valuecommentDim[$id][$dim])) {
                        $vcomment = $this->valuecommentDim[$id][$dim];
                    }

                    echo "
                            <td colspan='".$colspandim."'>
                                <textarea rows='3' style='height:6em;width:100%' id='observaciones".$dim.
                                "' name='observaciones".
                                $dim."' style='width:100%'>".$vcomment."</textarea>
                            </td>
                    ";
                }
                echo "
                        </tr>
                ";
            }
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
                                    <td class="global" colspan="1">'.
                                    strtoupper(get_string('totalvalue', 'block_evalcomix')).'</td>
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
                htmlspecialchars($this->valorestotal[$id][$grado]['nombre'], ENT_QUOTES).'" '.$checked.' /></td>
                ';
            }
            echo '
                                </tr>
                            </table>
        ';
        }

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
