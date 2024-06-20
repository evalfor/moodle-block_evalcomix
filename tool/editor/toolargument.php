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

class block_evalcomix_editor_toolargument extends block_evalcomix_editor {
    private $titulo;
    private $filediccionario;
    private $dimpor;
    private $porcentage;
    private $observation;
    private $view;
    private $commentatr;
    private $valuecommentatr;
    private $dimensionsid;
    private $atributosid;

    public function get_tool($id) {
    }
    public function get_titulo() {
        return $this->titulo;
    }

    public function get_dimpor() {
        return $this->dimpor[$this->id];
    }

    public function get_numvalores() {
        return array();
    }

    public function get_subdimpor() {
        return $this->subdimpor[$this->id];
    }

    public function get_porcentage() {
        return $this->porcentage;
    }

    public function get_valtotal($id) {
        return array();
    }

    public function get_numtotal($id) {
        return array();
    }

    public function get_valtotalpor($id) {
        return array();
    }

    public function get_valorestotal($id) {
        return array();
    }

    public function get_valglobal($id) {
        return array();
    }

    public function get_valglobalpor($id) {
        return array();
    }

    public function get_dimensionsid() {
        return $this->dimensionsId[$this->id];
    }

    public function get_atributosid() {
        return $this->atributosid[$this->id];
    }

    public function get_valoresid($id = 0) {
        return array();
    }

    public function get_valorestotalesid($id = 0) {
        return array();
    }

    public function set_titulo($titulo) {
        $this->titulo = $titulo;
    }

    public function set_dimpor($dimpor, $id=0) {
        $this->dimpor[$this->id] = $dimpor;
    }

    public function set_view($view, $id='') {
        $this->view = $view;
    }

    public function set_valglobalpor($valglobalpor, $id=0) {
    }
    public function set_dimensionsid($dimensionsid, $id = '') {
        $this->dimensionsId[$this->id] = $dimensionsid;
    }

    public function set_atributosid($atributosid, $id = '') {
        $this->atributosid[$this->id] = $atributosid;
    }

    public function set_valoresid($valoresid) {
    }

    public function set_valorestotalesid($valoresid) {
    }

    public function set_valtotalpor($valtotalpor) {
    }

    public function __construct($lang='es_utf8', $titulo = '', $dimension = array(), $numdim = 1, $subdimension = array(),
            $numsubdim = 1, $atributo = array(), $numatr = 1, $dimpor = array(), $subdimpor = array(),
            $atribpor = array(), $commentatr = array(), $id = 0, $observation = '', $porcentage=0,
            $valuecommentatr = '', $params = array()) {
            $this->filediccionario = 'lang/'.$lang.'/evalcomix.php';

        $params['id'] = $id;
        $params['dimension'] = $dimension;
        $params['subdimension'] = $subdimension;
        $params['numsubdim'] = $numsubdim;
        $params['subdimpor'] = $subdimpor;
        $params['atributo'] = $atributo;
        $params['numatr'] = $numatr;
        $params['numdim'] = $numdim;
        $params['atribpor'] = $atribpor;
        parent::__construct($params);

        $this->titulo = $titulo;
        $this->dimpor = $dimpor;
        $this->observation = (is_array($observation)) ? $observation : array($id => $observation);
        $this->porcentage = $porcentage;
        $this->view = 'design';
        $this->commentatr = $commentatr;
        $this->valuecommentatr = $valuecommentatr;

        if (!empty($params['dimensionsid'])) {
            $this->dimensionsId = $params['dimensionsid'];
        }
        if (!empty($params['atributosid'])) {
            $this->atributosid = $params['atributosid'];
        }
    }

    public function add_dimension($dim, $key) {
        $dimen;
        $id = $this->id;
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
        $this->competency[$id][$dim][$subdim] = array();
        $this->outcome[$id][$dim][$subdim] = array();
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

    public function display_body($data, $mix = '', $porcentage='') {
        if ($porcentage != '') {
            $this->porcentage = $porcentage;
        }
        if (isset($data['titulo'.$this->id])) {
            $this->titulo = stripslashes($data['titulo'.$this->id]);
        }

        $numdimen = count($this->dimension[$this->id]);

        if ($this->view == 'view' && !is_numeric($mix)) {
            echo '<input type="button" style="width:10em" value="'.get_string('view', 'block_evalcomix').'"
            onclick=\'javascript:location.href="generator.php?op=design&courseid='.$data['courseid'].'"\'><br>';
        }
        $id = $this->id;
            echo '
        <div id="cuerpo'.$id.'" class="cuerpo">
            <br>
            <label for="titulo'.$id.'" style="margin-left:1em">'.get_string('argument', 'block_evalcomix').
            ':</label><span class="labelcampo">
                <textarea class="width" id="titulo'.$id.'" name="titulo'.$id.'">'.$this->titulo.'</textarea></span>
            ';
        if ($this->view == 'design') {
            echo '
            <label for="numdimensiones'.$id.'">'.get_string('numdimensions', 'block_evalcomix').'</label>
            <span class="labelcampo">
                <input type="text" id="numdimensiones'.$id.'" name="numdimensiones'.$id.'"
                value="'.$this->numdim[$this->id].'" maxlength=2 onkeypress=\'javascript:return validar(event);\'/>
            </span>

            <input class="flecha" type="button" id="addDim"
            onclick=\'javascript:if (!validarEntero(document.getElementById("numdimensiones'.$id.'").value)) {
                alert("' . get_string('ADimension', 'block_evalcomix') . '"); return false;}
                sendPost("cuerpo'.$id.'", "mix='.$mix.'&id='.$id.'&addDim=1&titulo'.$id.
                '="+document.getElementById("titulo'.$id.
                '").value+"&numdimensiones="+ document.getElementById("numdimensiones'.$id.'").value +"", "mainform0");\'
                name="addDim" value=""/>
            ';
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

            $thisobservationid = '';
            if (isset($this->observation[$id])) {
                $thisobservationid = $this->observation[$id];
            }

            echo '
            <div id="comentario">
                <div id="marco">
                    <label for="observation'.$id.'">' . get_string('observation', 'block_evalcomix'). ':</label>
                    <textarea id="observation'.$id.'" style="width:100%" rows="4" cols="200">' .
                    $thisobservationid . '</textarea>
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

        $checked = '';

        if ($this->view == 'design') {
            echo '
            <div>
                <input type="button" class="delete" onclick=\'javascript:sendPost("cuerpo'.$id.'","mix='.$mix.
                    '&id='.$id.'&titulo'.$id.'="+document.getElementById("titulo'.$id.'").value+"&addDim=1&dd='.
                    $dim.'", "mainform0");\'>
                <input type="button" class="up" onclick=\'javascript:sendPost("cuerpo'.$id.'","mix='.$mix.'&id='.
                    $id.'&titulo'.$id.'="+document.getElementById("titulo'.$id.'").value+"&moveDim=1&dUp='.$dim.
                    '", "mainform0");\'>
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

                <input class="flecha" type="button" id="addSubDim'.$id.'" name="addSubDim'.$id.'"
                onclick=\'javascript:if (!validarEntero(document.getElementById("numsubdimensiones'.$id.'_'.$dim.'").value)) {
                    alert("' . get_string('ASubdimension', 'block_evalcomix') . '"); return false;}
                    sendPost("dimensiontag'.$id.'_'.$dim.'", "mix='.$mix.'&id='.$id.
                    '&addSubDim="+this.value+"&numsubdimensiones'.$dim.'="+ document.getElementById("numsubdimensiones'.
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
            $mix.'&id='.$id.'&dimpor'.$id.'="+document.getElementById("dimpor'.$id.'_'.$dim.'").value+"&dpi='.$dim.
            '&addDim=1", "mainform0");\'></span>';

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
                    <input type="button" class="add" onclick=\'javascript:sendPost("cuerpo'.$id.'","mix='.$mix.
                        '&id='.$id.'&titulo'.$id.'="+document.getElementById("titulo'.$id.'").value+"&addDim=1&ad='.
                        $dim.'", "mainform0");\'>
                    <input type="button" class="down" onclick=\'javascript:sendPost("cuerpo'.$id.'","mix='.$mix.
                        '&id='.$id.'&titulo'.$id.'="+document.getElementById("titulo'.$id.
                        '").value+"&moveDim=1&dDown='.$dim.'", "mainform0");\'>
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
                <input type="button" class="delete" onclick=\'javascript:sendPost("dimensiontag'.$id.'_'.$dim.
                '", "mix='.$mix.'&id='.$id.'&addSubDim='.$dim.'&dS=1&sd='.$subdim.'", "mainform0");\'>
                <input type="button" class="up" onclick=\'javascript:sendPost("dimensiontag'.$id.'_'.$dim.'", "mix='.
                $mix.'&id='.$id.'&moveSub='.$dim.'&sUp='.$subdim.'", "mainform0");\'>
                <br><br></div>
            ';
        }
        echo '
                <div class="margin">
                    <div class="float-md-left">
                        <label for="subdimension'.$id.'_'.$dim.'_'.$subdim.'">'.
                        get_string('subdimension', 'block_evalcomix').
                        '</label>
                        <span class="labelcampo">
                        <textarea  class="width" id="subdimension'.$id.'_'.$dim.'_'.$subdim.'"
                        name="subdimension'.$id.'_'.$dim.'_'.$subdim.'">'.
                        $this->subdimension[$this->id][$dim][$subdim]['nombre'].
                        '</textarea>
                        </span>
                    </div>
        ';
        if ($this->view == 'design') {
            echo '
                <div class="float-md-left">
                    <label for="numatributos'.$id.'_'.$dim.'_'.$subdim.'">'.get_string('numattributes', 'block_evalcomix').
                    '</label>
                    <span class="labelcampo"><input type="text" id="numatributos'.$id.'_'.$dim.'_'.$subdim.'"
                    name="numatributos'.$id.'_'.$dim.'_'.$subdim.'" value="'.$this->numatr[$this->id][$dim][$subdim].'"
                    maxlength=2 onkeypress=\'javascript:return validar(event);\'/></span>
                    <input class="flecha" type="button" id="addAtr'.$id.'" name="addAtr'.$id.'" style="font-size:1px"
                    onclick=\'javascript:if (!validarEntero(document.getElementById("numatributos'.$id.'_'.$dim.'_'.$subdim.
                    '").value)) {alert("' . get_string('AAttribute', 'block_evalcomix') . '");
                    return false;}sendPost("subdimensiontag'.$id.'_'.$dim.'_'.$subdim.'", "mix='.$mix.'&id='.$id.
                    '&addAtr="+this.value+"&numatributos'.$id.'_'.$dim.'_'.$subdim.'="+ document.getElementById("numatributos'.
                    $id.'_'.$dim.'_'.$subdim.'").value +"", "mainform0");\' value="'.$dim.'_'.$subdim.'"/>
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
                        '</span></th>
                        <th/>
            ';

        echo '</tr>';

        if (isset($this->atributo[$this->id][$dim][$subdim])) {
            $numattribute = count($this->atributo[$this->id][$dim][$subdim]);
            foreach ($this->atributo[$this->id][$dim][$subdim] as $atrib => $elematrib) {
                if (isset($data['atributo'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib])) {
                    $this->atributo[$this->id][$dim][$subdim][$atrib]['nombre'] = stripslashes($data['atributo'.$id.'_'.$dim.
                    '_'.$subdim.'_'.$atrib]);
                }
                echo '          <tr>
                                <td style="">';
                if ($this->view == 'design') {
                    echo '
                                <div style="margin-bottom:2em;">
                                    <input type="button" class="delete"
                                    onclick=\'javascript:sendPost("subdimensiontag'.$id.'_'.$dim.'_'.$subdim.'", "mix='.
                                    $mix.'&id='.$id.'&addAtr='.$dim.'_'.$subdim.'&dt='.$atrib.'", "mainform0");\'>
                                </div>
                                <div style="margin-top:2em;">
                                    <input type="button" class="add"
                                    onclick=\'javascript:sendPost("subdimensiontag'.$id.'_'.$dim.'_'.$subdim.'", "mix='.
                                    $mix.'&id='.$id.'&addAtr='.$dim.'_'.$subdim.'&at='.$atrib.'", "mainform0");\'>
                                </div>
                    ';
                }
                echo '</td>';

                if ($this->view == 'design') {
                    echo '
                            <td style="">
                                <div style="margin-bottom:2em;">
                                    <input type="button" class="up"
                                    onclick=\'javascript:sendPost("subdimensiontag'.$id.'_'.$dim.'_'.$subdim.'", "mix='.
                                    $mix.'&id='.$id.'&moveAtr='.$dim.'_'.$subdim.'&aUp='.$atrib.'", "mainform0");\'>
                                </div>
                                <div style="margin-top:2em;">
                                    <input type="button" class="down"
                                    onclick=\'javascript:sendPost("subdimensiontag'.$id.'_'.$dim.'_'.$subdim.'", "mix='.
                                    $mix.'&id='.$id.'&moveAtr='.$dim.'_'.$subdim.'&aDown='.$atrib.'", "mainform0");\'>
                                </div>
                            </td>
                    ';
                }

                echo '
                        <td><input class="porcentaje" type="text"
                        onchange=\'document.getElementById("sumpor'.$id.'_'.$dim.'_'.$subdim.'").value += this.id + "-";\'
                        onkeyup=\'javascript:if (document.getElementById("atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.
                        '").value > 100)document.getElementById("atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'").value = 100;\'
                        onkeypress=\'javascript:return validar(event);\'  maxlength="3"
                        name="atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'"
                        id="atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'"
                        value="'.$this->atribpor[$this->id][$dim][$subdim][$atrib].'"/>
                            <input class="botonporcentaje" type="button" onclick=\'javascript:sendPost("subdimensiontag'.
                            $id.'_'.$dim.'_'.$subdim.'", "mix='.$mix.'&id='.$id.
                            '&atribpor="+document.getElementById("atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.
                            '").value+"&api='.$atrib.'&addAtr='.$dim.'_'.$subdim.'", "mainform0");\'></td>
                            <td><span class="font"><textarea class="width" id="atributo'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'"
                            name="atributo'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.'">'.
                            $this->atributo[$this->id][$dim][$subdim][$atrib]['nombre'].'</textarea></span></td>
                ';

                echo '<td style="width:60%">
                        <textarea disabled style="width:97%;" id="atributocomment'.$id.'_'.$dim.'_'.$subdim.'_'.$atrib.
                        '"></textarea>
                    </td>';

                echo '</tr>';

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
            $root = '<ar:ArgumentSet xmlns:ar="http://avanza.uca.es/assessmentservice/argument"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://avanza.uca.es/assessmentservice/argument http://avanza.uca.es/assessmentservice/Argument.xsd"
    ';
            $rootend = '</ar:ArgumentSet>
    ';
        } else if ($mixed == '1') {
            $root = '<ArgumentSet ';
            $rootend = '</ArgumentSet>';
            $porcentagevalue = (isset($this->porcentage)) ? $this->porcentage : '';
            $percentage1 = ' percentage="' . $porcentagevalue . '"';
        }

        // Root.
        $title = (isset($this->titulo)) ? $this->titulo : '';
        $numdim = (isset($this->numdim[$id])) ? $this->numdim[$id] : '';
        $xml = $root . ' id="'. $idtool .'" name="' .
            htmlspecialchars($title, ENT_QUOTES) . '" dimensions="' .
            $numdim .'" ' . $percentage1 . '>
        ';

        if (isset($this->observation[$id])) {
            $xml .= '<Description>' . htmlspecialchars($this->observation[$id], ENT_QUOTES) . '</Description>
            ';
        }

        foreach ($this->dimension[$id] as $dim => $itemdim) {
            $dimid = (isset($this->dimensionsId[$id][$dim])) ? $this->dimensionsId[$id][$dim] : '';
            $dimname = (isset($this->dimension[$id][$dim]['nombre'])) ? $this->dimension[$id][$dim]['nombre'] : '';
            $numsubdim = (isset($this->numsubdim[$id][$dim])) ? $this->numsubdim[$id][$dim] : '';
            $dimpor = (isset($this->dimpor[$id][$dim])) ? $this->dimpor[$id][$dim] : '';

            $xml .= '<Dimension id="'.
                $dimid.'" name="' .
                htmlspecialchars($dimname, ENT_QUOTES) . '" subdimensions="' .
                $numsubdim . '" values="2" percentage="' .
                $dimpor . '">
    ';

            foreach ($this->subdimension[$id][$dim] as $subdim => $elemsubdim) {
                $subdimid = (isset($this->subdimensionsid[$id][$dim][$subdim]))
                    ? $this->subdimensionsid[$id][$dim][$subdim] : '';
                $subdimname = (isset($this->subdimension[$id][$dim][$subdim]['nombre']))
                    ? $this->subdimension[$id][$dim][$subdim]['nombre'] : '';
                $numatr = (isset($this->numatr[$id][$dim][$subdim])) ? $this->numatr[$id][$dim][$subdim] : '';
                $subdimpor = (isset($this->subdimpor[$id][$dim][$subdim])) ? $this->subdimpor[$id][$dim][$subdim] : '';

                $xml .= '<Subdimension id="'.
                    $subdimid.'" name="' .
                    htmlspecialchars($subdimname, ENT_QUOTES) . '" attributes="' .
                    $numatr . '" percentage="' .
                    $subdimpor . '">
    ';

                foreach ($this->atributo[$id][$dim][$subdim] as $atrib => $elematrib) {
                    $comment = '0';
                    if ($this->commentatr[$id][$dim][$subdim][$atrib] == 'visible') {
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

    public function print_tool($root = '') {
        $id = $this->id;
        $colspan = 0;

        echo '
                                <table class="tabla" border=1 cellpadding=5px >

                                <!--TITULO-INSTRUMENTO------------>
                                <tr>
                                   <th colspan="2">'.htmlspecialchars($this->titulo, ENT_QUOTES).'</th>
                                </tr>

                                <tr>
                                   <th colspan="2"></th>
                                </tr>


                                <tr>
                                   <td></td>
                                   <td></td>
                                </tr>';
        $i = 0;
        foreach ($this->dimension[$id] as $dim => $value) {
            $colspandim = 0;

            echo '
                                <tr id="dim">
                                    <!--DIMENSIÓN-TITLE----------->
                                    <td class="bold" colspan="2">
                                        <span>'.htmlspecialchars($this->dimension[$this->id][$dim]['nombre'], ENT_QUOTES).'</span>
                                    </td>
            ';

            echo '
                                </tr>
            ';
            $l = 0;
            foreach ($this->subdimension[$this->id][$dim] as $subdim => $elemsubdim) {
                echo '
                                <!--TITULO-SUBDIMENSIÓN------------>
                                <tr><td class="subdim" colspan="2">'.
                                htmlspecialchars($this->subdimension[$this->id][$dim][$subdim]['nombre'], ENT_QUOTES).'</td></tr>
                ';

                if (isset($this->atributo[$this->id][$dim][$subdim])) {
                    $j = 0;
                    foreach ($this->atributo[$this->id][$dim][$subdim] as $atrib => $elematrib) {
                        $vcomment = '';
                        if (isset($this->valuecommentAtr[$id][$dim][$subdim][$atrib])) {
                            $vcomment = $this->valuecommentAtr[$id][$dim][$subdim][$atrib];
                        }

                        echo '
                                <!--ATRIBUTOS---------------------->
                                <tr rowspan=0><td colspan="'.($colspan - $colspandim + 1) .'">'.
                                htmlspecialchars($this->atributo[$this->id][$dim][$subdim][$atrib]['nombre'], ENT_QUOTES).'</td>

                                    <td colspan="'.$colspan.'">
                                        <textarea rows="4" style="height:8em;width:100%" id="observaciones'.$i.'_'.$l.'_'.$j.
                                        '" name="observaciones'.$i.'_'.$l.'_'.$j.'" style="width:100%">'.$vcomment.'</textarea>
                                    </td>

                        ';

                        echo '
                                </tr>

                        ';

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

        $width = (empty($this->comment[$id])) ? 100 : 60;
        $comment = (empty($this->comment[$id])) ? (get_string('comments', 'block_evalcomix')).':' :
        htmlspecialchars($this->comment[$id], ENT_QUOTES);
        echo '<br><br><br>
                            <table class="tabla" border=1 cellpadding="5px">
                                <tr>
                                    <td>'.$comment.'</td>
                                    <td style="width:'.$width.'%"><textarea name="observaciones" id="observaciones"
                                    rows=4 cols=20 style="width:100%">'.$this->observation[$id].'</textarea></td>
                                </tr>
                            </table>
        ';
    }
}
