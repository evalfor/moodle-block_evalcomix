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
 * block_evalcomix_editor_tooldifferential
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, <info@ansaner.net>
 */
class block_evalcomix_editor_tooldifferential extends block_evalcomix_editor {
    /** @var string */
    private $titulo;
    /** @var array */
    private $valtotal;
    /** @var array */
    private $numtotal;
    /** @var array */
    private $valorestotal;
    /** @var array */
    private $valtotalpor;
    /** @var array */
    private $valglobal;
    /** @var array */
    private $valglobalpor;
    /** @var array */
    private $dimpor;
    /** @var array */
    private $atributopos;
    /** @var array */
    private $porcentage;
    /** @var array */
    private $observation;
    /** @var array */
    private $view;
    /** @var array */
    private $commentatr;
    /** @var array */
    private $dimensionsid;
    /** @var array */
    private $atributosid;
    /** @var array */
    private $atributosposid;
    /** @var array */
    private $valoresid;
    /** @var array */
    public $subdimensionsid;
    /** @var array */
    private $valuecommentatr;

    /**
     * Method
     */
    public function get_tool($id) {
    }

    /**
     * Method
     */
    public function get_titulo() {
        return $this->titulo;
    }

    /**
     * Method
     */
    public function get_valores() {
        return $this->valores[$this->id];
    }

    /**
     * Method
     */
    public function get_numvalores() {
        return $this->numvalores[$this->id];
    }

    /**
     * Method
     */
    public function get_valtotal() {
        return (isset($this->valtotal[$this->id])) ? $this->valtotal[$this->id] : null;
    }

    /**
     * Method
     */
    public function get_numtotal($id = 0) {
        return $this->numtotal[$this->id];
    }

    /**
     * Method
     */
    public function get_valtotalpor() {
        return $this->valtotalpor[$this->id];
    }

    /**
     * Method
     */
    public function get_valorestotal($id = 0) {
        if (isset($this->valorestotal[$this->id])) {
            return $this->valorestotal[$this->id];
        }
    }

    /**
     * Method
     */
    public function get_valglobal() {
        return $this->valglobal[$this->id];
    }

    /**
     * Method
     */
    public function get_valglobalpor() {
        return $this->valglobalpor[$this->id];
    }

    /**
     * Method
     */
    public function get_dimpor() {
        return $this->dimpor[$this->id];
    }

    /**
     * Method
     */
    public function get_subdimpor() {
        return $this->subdimpor[$this->id];
    }

    /**
     * Method
     */
    public function get_commentatr($id = 0) {
        return $this->commentatr[$this->id];
    }

    /**
     * Method
     */
    public function get_porcentage() {
        return $this->porcentage;
    }

    /**
     * Method
     */
    public function get_dimensionsid() {
        return $this->dimensionsid[$this->id];
    }

    /**
     * Method
     */
    public function get_atributosid() {
        return $this->atributosid[$this->id];
    }

    /**
     * Method
     */
    public function get_valoresid() {
        return $this->valoresid[$this->id];
    }

    /**
     * Method
     */
    public function get_valorestotalesid() {
        return null;
    }

    /**
     * Method
     */
    public function get_atributopos() {
        return $this->atributopos[$this->id];
    }

    /**
     * Method
     */
    public function get_atributosposid() {
        return $this->atributosposid[$this->id];
    }

    /**
     * Method
     */
    public function set_titulo($titulo) {
        $this->titulo = $titulo;
    }

    /**
     * Method
     */
    public function set_valtotal($valtotal) {
        $this->valtotal[$this->id] = $valtotal;
    }

    /**
     * Method
     */
    public function set_numtotal($numtotal) {
        $this->numtotal[$this->id] = $numtotal;
    }

    /**
     * Method
     */
    public function set_valtotalpor($valtotalpor) {
        $this->valtotalpor[$this->id] = $valtotalpor;
    }

    /**
     * Method
     */
    public function set_valorestotal($valorestotal) {
        $this->valorestotal[$this->id] = $valorestotal;
    }

    /**
     * Method
     */
    public function set_valglobal($valglobal) {
        $this->valglobal[$this->id] = $valglobal;
    }

    /**
     * Method
     */
    public function set_valglobalpor($valglobalpor, $id = 0) {
        $this->valglobalpor[$this->id] = $valglobalpor;
    }

    /**
     * Method
     */
    public function set_dimpor($dimpor, $id = 0) {
        $this->dimpor[$this->id] = $dimpor;
    }

    /**
     * Method
     */
    public function set_view($view, $id = '') {
        $this->view = $view;
    }

    /**
     * Method
     */
    public function set_commentatr($comment) {
        $this->commentatr[$this->id] = $comment;
    }

    /**
     * Method
     */
    public function set_dimensionsid($dimensionsid, $id = '') {
        $this->dimensionsid[$this->id] = $dimensionsid;
    }

    /**
     * Method
     */
    public function set_atributosid($atributosid, $id = '') {
        $this->atributosid[$this->id] = $atributosid;
    }

    /**
     * Method
     */
    public function set_valoresid($valoresid, $id = '') {
        $this->valoresid[$this->id] = $valoresid;
    }

    /**
     * Method
     */
    public function set_atributopos($atributo) {
        $this->atributopos[$this->id] = $atributo;
    }

    /**
     * Method
     */
    public function set_atributosposid($atributo, $id) {
        $this->atributosposid[$this->id] = $atributo;
    }

    /**
     * Method
     */
    public function __construct(
        $lang = 'es_utf8',
        $titulo = '',
        $dimension = [],
        $numdim = 1,
        $subdimension = [],
        $numsubdim = 1,
        $atributo = [],
        $numatr = 1,
        $valores = [],
        $numvalores = 2,
        $valtotal = [],
        $numtotal = 0,
        $valorestotal = [],
        $valglobal = false,
        $valglobalpor = [],
        $dimpor = [],
        $subdimpor = [],
        $atribpor = [],
        $commentatr = [],
        $id = 0,
        $observation = '',
        $porcentage = 0,
        $atributopos = null,
        $valueattribute = '',
        $valuecommentatr = '',
        $params = []
    ) {

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
        $this->titulo = $titulo;
        $this->valtotal = $valtotal;
        $this->numtotal = $numtotal;
        $this->valorestotal = $valorestotal;
        $this->valglobal = $valglobal;
        $this->valglobalpor = $valglobalpor;
        $this->dimpor = $dimpor;
        $this->observation = $observation;
        $this->porcentage = $porcentage;
        $this->atributopos = $atributopos;
        $this->view = 'design';
        $this->commentatr = $commentatr;

        $this->valueattribute = $valueattribute;
        $this->valuecommentatr = $valuecommentatr;

        if (!isset($this->atributopos)) {
            foreach ($this->atributo[$this->id] as $dim => $value1) {
                $this->numvalores[$this->id][$dim]++;
                $this->valores[$this->id][$dim][3]['nombre'] = get_string('titlevalue', 'block_evalcomix') .
                    $this->numvalores[$this->id][$dim];
                foreach ($value1 as $subdim => $value2) {
                    foreach ($value2 as $atrib => $value3) {
                        $this->atributo[$this->id][$dim][$subdim][$atrib]['nombre'] = get_string('noatrib', 'block_evalcomix') .
                            $this->numatr[$this->id][$dim][$subdim];
                        $this->atributopos[$this->id][$dim][$subdim][$atrib]['nombre'] = get_string(
                            'yesatrib',
                            'block_evalcomix'
                        ) . $this->numatr[$this->id][$dim][$subdim];
                    }
                }
            }
        }

        if (!empty($params['dimensionsid'])) {
            $this->dimensionsid = $params['dimensionsid'];
        }
        if (!empty($params['atributosid'])) {
            $this->atributosid = $params['atributosid'];
        }
        if (!empty($params['atributosposid'])) {
            $this->atributosposid = $params['atributosposid'];
        }
        if (!empty($params['valoresid'])) {
            $this->valoresid = $params['valoresid'];
        }
        $this->dimensionsid[$this->id] = ['0' => 'aux'];
    }

    /**
     * Method
     */
    public function add_attribute($dim, $subdim, $atrib, $key) {
        $id = $this->id;
        $this->numatr[$id][$dim][$subdim]++;

        if (!isset($atrib)) {
            $atrib = $key;
            $this->atributo[$id][$dim][$subdim][$atrib]['nombre'] = get_string('noatrib', 'block_evalcomix') .
                $this->numatr[$id][$dim][$subdim];
            $this->atributopos[$id][$dim][$subdim][$atrib]['nombre'] = get_string('yesatrib', 'block_evalcomix') .
                $this->numatr[$id][$dim][$subdim];
        } else {
            $newindex = $key;
            $elem['nombre'] = get_string('noatrib', 'block_evalcomix') . $this->numatr[$id][$dim][$subdim];
            $this->atributo[$id][$dim][$subdim] = $this->array_add($this->atributo[$id][$dim][$subdim], $atrib, $elem, $newindex);
            $elem['nombre'] = get_string('yesatrib', 'block_evalcomix') . $this->numatr[$id][$dim][$subdim];
            $this->atributopos[$id][$dim][$subdim] = $this->array_add(
                $this->atributopos[$id][$dim][$subdim],
                $atrib,
                $elem,
                $newindex
            );
            $this->commentatr[$id][$dim][$subdim][$newindex] = 'hidden';
        }
    }

    /**
     * Method
     */
    public function remove_dimension($dim, $id = 0) {
    }

    /**
     * Method
     */
    public function remove_subdimension($dim, $subdim) {
    }

    /**
     * Method
     */
    public function remove_attribute($dim, $subdim, $atrib) {
        $id = $this->id;
        if (isset($this->atributo[$id][$dim][$subdim][$atrib])) {
            if ($this->numatr[$id][$dim][$subdim] > 1) {
                $this->numatr[$id][$dim][$subdim]--;
                $this->atributo[$id][$dim][$subdim] = $this->array_remove($this->atributo[$id][$dim][$subdim], $atrib);
                $this->atribpor[$id][$dim][$subdim] = $this->array_remove($this->atribpor[$id][$dim][$subdim], $atrib);
                $this->atributopos[$id][$dim][$subdim] = $this->array_remove($this->atributopos[$id][$dim][$subdim], $atrib);
            }
        }
        return 1;
    }

    /**
     * Method
     */
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
            case 'atributo':
                $this->atributo[$id][$dim][$subdim] = $blockdata;
                // No break.
            case 'atributopos':
                $this->atributopos[$id][$dim][$subdim] = $blockdata;
        }
    }

    /**
     * Method
     */
    public function display_body($data, $mix = '', $porcentage = '') {
        $id = $this->id;
        if ($porcentage != '') {
            $this->porcentage = $porcentage;
        }
        foreach ($this->dimension[$id] as $dim => $elemdim) {
            foreach ($this->subdimension[$id][$dim] as $subdim => $elemsubdim) {
                echo '<div id="subdimensiontag' . $id . '_' . $dim . '_' . $subdim . '">';
                $this->display_subdimension($dim, $subdim, $data, $id, $mix);
                echo '</div>';
            }
        }
    }

    /**
     * Method
     */
    public function display_dimension($dim, $data, $id = 0, $mix = '') {
    }

    /**
     * Method
     */
    public function display_subdimension($dim, $subdim, $data, $id = 0, $mix = '') {
        $id = $this->id;
        if (isset($data['titulo' . $this->id]) && empty($data['modalclose'])) {
            $this->titulo = stripslashes($data['titulo' . $this->id]);
        }
        if (isset($data['numvalores' . $id . '_' . $dim]) && $data['numvalores' . $id . '_' . $dim] > 0) {
            $this->numvalores[$id][$dim] = stripslashes($data['numvalores' . $id . '_' . $dim]);
        }

        $checked = '';
        $disabled = 'disabled';

        if ($this->view == 'view' && !is_numeric($mix)) {
            echo '<input type="button" style="width:10em" value="' . get_string('closeview', 'block_evalcomix') . '"
            onclick=\'javascript:location.href="generator.php?op=design&courseid=' . $data['courseid'] . '"\'><br>';
        }

        $numdimen = 1;
        $id = $this->id;

        echo '
        <div id="cuerpo' . $id . '" class="margin">
            <div class="float-md-left">
                <label for="titulo' . $id . '">' . get_string('differentail', 'block_evalcomix') . ':</label><span class="labelcampo">
                <textarea class="width" id="titulo' . $id . '" name="titulo' . $id . '">' . $this->titulo . '</textarea></span>
            </div>
        ';
        if ($this->view == 'design') {
            echo '
            <div class="float-md-left">
                <label for="numatributos' . $id . '_' . $dim . '_' . $subdim . '">' . get_string('numattributes', 'block_evalcomix') . '</label>
                <span class="labelcampo"><input type="text" id="numatributos' . $id . '_' . $dim . '_' . $subdim . '"
                name="numatributos' . $id . '_' . $dim . '_' . $subdim . '" value="' . $this->numatr[$id][$dim][$subdim] . '" maxlength=2
                onkeypress=\'javascript:return validar(event);\'/></span>
                <label for="numvalores' . $id . '_' . $dim . '">' . get_string('numvalues', 'block_evalcomix') . '</label>
                <span class="labelcampo"><input type="text" id="numvalores' . $id . '_' . $dim . '" name="numvalores' . $id . '_' . $dim . '"
                value="' . $this->numvalores[$id][$dim] . '" maxlength=2 onkeypress=\'javascript:return validar(event);\'/></span>
                <input class="flecha" type="button" id="addAtr" name="addAtr" style="font-size:1px"
                onclick=\'
                if (!validarEntero(document.getElementById("numatributos' . $id . '_' . $dim . '_' . $subdim .
                '").value)) {
                    alert("' . get_string('AAttribute', 'block_evalcomix') . '");
                }
                if ((!validarEntero(document.getElementById("numvalores' . $id . '_' . $dim . '").value) ||
                (document.getElementById("numvalores' . $id . '_' . $dim . '").value)%2==0)) {
                    alert("' .
                get_string('ADiferencial', 'block_evalcomix') . '"); return false;
                }
                sendPost("subdimensiontag' .
                $id . '_' . $dim . '_' . $subdim . '", "mix=' . $mix . '&id=' . $id . '&titulo' . $id . '="+document.getElementById("titulo' . $id .
                '").value+"&addAtr="+this.value+"", "mainform0", "mainform0");\' value="' . $dim . '_' . $subdim . '"/>
            </div>
            <div class="float-md-left margin">
            </div>
        ';
        }
        if (isset($mix) && is_numeric($mix)) {
            echo '
            <div class="float-md-left">
                <span class="labelcampo">
                    <label for="toolpor_' . $id . '">' . get_string('porvalue', 'block_evalcomix') . '</label>
                    <input class="porcentaje" type="text" name="toolpor_' . $id . '" id="toolpor_' . $id . '" value="' .
                    $this->porcentage . '"
                    onchange=\'document.getElementById("sumpor").value += this.id + "-";\'
                    onkeyup=\'javascript:if (document.getElementById("toolpor_' . $id . '").value > 100)
                        document.getElementById("toolpor_' . $id . '").value = 100;\'
                    onkeypress=\'javascript:return validar(event);\'/></span>
                    <input class="botonporcentaje" type="button" onclick=\'javascript:sendPost("body", "id=' . $id .
                    '&titulo' .
                    $id . '="+document.getElementById("titulo' . $id . '").value+"&toolpor_' . $id . '="+document.getElementById("toolpor_' .
                    $id . '").value+"&addtool' . $id . '=1", "mainform0");\'>
                </span>
            </div>
            ';
        }
        $this->display_competencies($dim, $subdim, $mix, $label = 'title');
        echo '<br/><br/>';

        if (isset($data['subdimension' . $id . '_' . $dim . '_' . $subdim])) {
            $this->subdimension[$id][$dim][$subdim]['nombre'] = stripslashes($data['subdimension' . $id . '_' . $dim . '_' . $subdim]);
        }

        echo '
                <input type="hidden" id="sumpor' . $id . '_' . $dim . '_' . $subdim . '" value=""/>
                <div class="margin">
                    <table class="maintable">
                        <th/><th/><th/>
                        <th style="background-color:#BFE4AB;text-align:right;">' . get_string('novalue', 'block_evalcomix') . '</th>
                    ';
        $inicio = (int)($this->numvalores[$id][$dim] / 2);
        $valores = [];
        for ($i = 0; $i < $this->numvalores[$id][$dim]; $i++) {
            $valores[$id][$dim][$i]['nombre'] = ((-1) * $inicio + $i);
            echo '<th class="grado" style="background-color:#BFE4AB;">' . ((-1) * $inicio + $i) . '</th>
            ';
        }
        echo '<th style="text-align:left;background-color:#BFE4AB;">' . get_string('yesvalue', 'block_evalcomix') . '</th>';
        $this->valores = $valores;

        $numattribute = count($this->atributo[$id][$dim][$subdim]);
        if (isset($this->atributo[$id][$dim][$subdim])) {
            foreach ($this->atributo[$id][$dim][$subdim] as $atrib => $elematrib) {
                if (isset($data['atributo' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib])) {
                    $this->atributo[$id][$dim][$subdim][$atrib]['nombre'] = stripslashes($data['atributo' .
                        $id . '_' . $dim . '_' . $subdim . '_' . $atrib]);
                }
                if (isset($data['atributopos' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib])) {
                    $this->atributopos[$id][$dim][$subdim][$atrib]['nombre'] = stripslashes($data['atributopos' .
                    $id . '_' . $dim . '_' . $subdim . '_' . $atrib]);
                }

                echo '  <tr>
                            <td style="">
                ';
                if ($this->view == 'design') {
                    echo '
                                <div style="margin-bottom:2em;">
                                    <input type="button" class="delete" onclick=\'javascript:sendPost("subdimensiontag' .
                                    $id . '_' . $dim . '_' . $subdim . '", "mix=' . $mix . '&id=' . $id . '&titulo' . $id .
                                    '="+document.getElementById("titulo' . $id . '").value+"&addAtr=' .
                                    $dim . '_' . $subdim . '&dt=' . $atrib . '", "mainform0");\'>
                                        </div>
                                        <div style="margin-top:2em;">
                                            <input type="button" class="add" onclick=\'javascript:sendPost("subdimensiontag' .
                                            $id . '_' . $dim . '_' . $subdim . '", "mix=' . $mix . '&id=' . $id . '&titulo' . $id .
                                            '="+document.getElementById("titulo' . $id . '").value+"&addAtr=' .
                                            $dim . '_' . $subdim . '&at=' . $atrib . '", "mainform0");\'>
                                        </div>
                    ';
                }

                echo '</td>';

                if ($this->view == 'design') {
                    echo '
                            <td style="">
                                <div style="margin-bottom:2em;">
                                    <input type="button" class="up" onclick=\'javascript:sendPost("subdimensiontag' .
                                    $id . '_' . $dim . '_' . $subdim . '", "mix=' . $mix . '&id=' . $id . '&moveAtr=' . $dim . '_' . $subdim .
                                    '&aUp=' . $atrib . '", "mainform0");\'>
                                </div>
                                <div style="margin-top:2em;">
                                    <input type="button" class="down" onclick=\'javascript:sendPost("subdimensiontag' .
                                    $id . '_' . $dim . '_' . $subdim . '", "mix=' . $mix . '&id=' . $id . '&moveAtr=' . $dim . '_' . $subdim .
                                    '&aDown=' . $atrib . '", "mainform0");\'>
                                </div>
                            </td>
                    ';
                }
                echo '
                            <td><input class="porcentaje" type="text" onchange=\'document.getElementById("sumpor' .
                            $id . '_' . $dim . '_' . $subdim . '").value += this.id + "-";\'
                            onkeyup=\'javascript:if (document.getElementById("atribpor' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib .
                            '").value > 100)document.getElementById("atribpor' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib .
                            '").value = 100;\' onkeypress=\'javascript:return validar(event);\'  maxlength="3"
                            name="atribpor' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib . '"
                            id="atribpor' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib . '"
                            value="' . $this->atribpor[$id][$dim][$subdim][$atrib] . '"/></span>
                            <input class="botonporcentaje" type="button"
                            onclick=\'javascript:sendPost("subdimensiontag' . $id . '_' . $dim . '_' . $subdim . '", "mix=' . $mix .
                            '&id=' . $id . '&titulo' . $id . '="+document.getElementById("titulo' . $id .
                            '").value+"&atribpor="+document.getElementById("atribpor' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib .
                            '").value+"&api=' . $atrib . '&addAtr=' . $dim . '_' . $subdim . '", "mainform0");\'></td>
                            <td><span class="font"><textarea class="width" id="atributo' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib . '"
                            name="atributo' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib . '">' .
                            $this->atributo[$id][$dim][$subdim][$atrib]['nombre'] . '</textarea></span></td>
                            ';

                for ($i = 0; $i < $this->numvalores[$id][$dim]; $i++) {
                    echo '<td><input type="radio" name="radio' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib . '" /></td>
                    ';
                }
                echo '                  <td><span class="font"><textarea class="width"
                id="atributopos' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib . '" name="atributopos' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib .
                '">' . $this->atributopos[$id][$dim][$subdim][$atrib]['nombre'] . '</textarea></span></td>';
                echo '</tr>';

                $visible = null;
                if (isset($data['commentatr' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib])) {
                    $visible = stripslashes($data['commentatr' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib]);
                    $this->commentatr[$id][$dim][$subdim][$atrib] = $visible;
                }

                if (
                    isset($this->commentatr[$id][$dim][$subdim][$atrib])
                        && $this->commentatr[$id][$dim][$subdim][$atrib] == 'visible'
                ) {
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
                        <input type="button" class="showcomment" title="' . get_string('add_comments', 'block_evalcomix') . '"
                            onclick=\'javascript:sendPost("subdimensiontag' . $id . '_' . $dim . '_' . $subdim . '", "mix=' .
                            $mix . '&id=' . $id . '&commentatr' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib . '=' . $novisible .
                            '&comAtr=' . $atrib . '&addAtr=' . $dim . '_' . $subdim . '", "mainform0");\'>
                    </div>';
                }
                echo '
                </td><td/><td/>
                <td colspan="' . $this->numvalores[$id][$dim] . '">';

                if ($visible == 'visible') {
                    $divheight = 'height:2.5em';
                    $textheight = 'height:2em';
                } else {
                    $divheight = 'height:0.5em';
                    $textheight = 'height:0.5em';
                }
                echo '<div class="atrcomment" id="atribcomment' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib . '"
                style="' . $divheight . '">
                    <textarea disabled style="width:97%; visibility:' . $visible . '; ' . $textheight . '"
                    id="atributocomment' . $id . '_' . $dim . '_' . $subdim . '_' . $atrib . '"></textarea>
                    </div>
                </td><td></td>
                ';

                echo '</tr>';
            }
        }
        echo '</table>
        </div>

        ';

        echo '<input type="hidden" id="sumpor3' . $id . '" value=""/>
            <input type="hidden" name="courseid" id="courseid" value="' . $data['courseid'] . '">
        </div>
        ';
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
    public function export($params = []) {
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
            $root = '<sd:SemanticDifferential xmlns:sd="http://avanza.uca.es/assessmentservice/semanticdifferential"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://avanza.uca.es/assessmentservice/semanticdifferential
    http://avanza.uca.es/assessmentservice/SemanticDifferential.xsd"
    ';
            $rootend = '</sd:SemanticDifferential>
    ';
        } else if ($mixed == '1') {
            $root = '<SemanticDifferential ';
            $rootend = '</SemanticDifferential>';
            $percentagevalue = (isset($this->porcentage)) ? $this->porcentage : '';
            $percentage1 = ' percentage="' . $percentagevalue . '"';
        }

        foreach ($this->atributo[$this->id] as $dim => $value1) {
            foreach ($value1 as $subdim => $value2) {
                $numatr = (isset($this->numatr[$this->id][$dim][$subdim])) ? $this->numatr[$this->id][$dim][$subdim] : '';
                $numvalues = (isset($this->numvalores[$id][$dim])) ? $this->numvalores[$id][$dim] : '';
                $xml = $root . ' id="' .
                    $idtool . '" name="' .
                    htmlspecialchars($this->titulo, ENT_QUOTES) . '" attributes="' .
                    $numatr . '" values="' .
                    $numvalues . '" ' .
                    $percentage1 . '>
';

                if (isset($this->observation[$id])) {
                    $xml .= '<Description>' . htmlspecialchars($this->observation[$id], ENT_QUOTES) . '</Description>
';
                }

                $xml .= "<Values>\n";
                $inicio = (int)($this->numvalores[$id][$dim] / 2);

                foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                    $valueid = (isset($this->valoresid[$id][$dim][$grado])) ? $this->valoresid[$id][$dim][$grado] : '';
                    $xml .= '<Value id="' . $valueid . '">' . $elemvalue['nombre'] . "</Value>\n";
                }
                $xml .= "</Values>\n";
                foreach ($value2 as $atrib => $value3) {
                    $comment = '';
                    if (
                        isset($this->commentatr[$id][$dim][$subdim][$atrib])
                            && $this->commentatr[$id][$dim][$subdim][$atrib] == 'visible'
                    ) {
                        $comment = '1';
                    }

                    $atribid = (isset($this->atributosid[$id][$dim][$subdim][$atrib]))
                        ? $this->atributosid[$id][$dim][$subdim][$atrib] : '';
                    $atribposid = (isset($this->atributosposid[$id][$dim][$subdim][$atrib]))
                        ? $this->atributosposid[$id][$dim][$subdim][$atrib] : '';
                    $namen = (isset($this->atributo[$id][$dim][$subdim][$atrib]['nombre']))
                        ? $this->atributo[$id][$dim][$subdim][$atrib]['nombre'] : '';
                    $namep = (isset($this->atributopos[$this->id][$dim][$subdim][$atrib]['nombre']))
                        ? $this->atributopos[$this->id][$dim][$subdim][$atrib]['nombre'] : '';
                    $atribpor = (isset($this->atribpor[$id][$dim][$subdim][$atrib]))
                        ? $this->atribpor[$id][$dim][$subdim][$atrib] : '';

                    $xml .= '<Attribute idNeg="' .
                        $atribid . '" idPos="' .
                        $atribposid . '" nameN="'
                        . htmlspecialchars($namen, ENT_QUOTES) . '" nameP="' .
                        htmlspecialchars($namep, ENT_QUOTES) . '" comment="' .
                        $comment . '" percentage="' .
                        $atribpor . '">0</Attribute>
';
                }
            }
        }

        $xml .= $rootend;

        return $xml;
    }

    /**
     * Method
     */
    public function get_subdimensionid_from_xml($toolid) {
        $result = [];
        $id = $this->id;
        $result[$id][0][0] = (string)$toolid;
        return $result;
    }

    /**
     * Method
     */
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
                                   <td colspan="' . ($colspan + 3) . '">' . get_string('mixed_por', 'block_evalcomix') . ': ' .
                                   $this->porcentage . '%</td>
                                </tr>
            ';
        }

        echo '
                                <tr>
                                   <th colspan="' . ($colspan + 3) . '">' . htmlspecialchars($this->titulo, ENT_QUOTES) . '</th>
                                </tr>

                                <tr>
                                   <th colspan="' . ($colspan + 3) . '"></th>
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
                                    <td class="bold" colspan="' . ($colspan - $colspandim + 2) . '"></td>
            ';
            foreach ($this->valores[$this->id][$dim] as $grado => $elemvalue) {
                echo '
                                    <td class="td">' .
                                    htmlspecialchars($this->valores[$this->id][$dim][$grado]['nombre'], ENT_QUOTES) . '</td>
                ';
            }

            echo '    <td></td>
                                </tr>
            ';

            $l = 0;
            foreach ($this->subdimension[$this->id][$dim] as $subdim => $elemsubdim) {
                echo '
                                <!--TITULO-SUBDIMENSIÓN------------>
                                <tr><td class="subdim" colspan="' . ($colspan + 1) . '"></td><td></td></tr>
                ';

                if (isset($this->atributo[$this->id][$dim][$subdim])) {
                    $j = 0;
                    foreach ($this->atributo[$this->id][$dim][$subdim] as $atrib => $elematrib) {
                        $indexdif = $j * 2;
                        echo '
                                <!--ATRIBUTOS---------------------->
                                <tr rowspan=0>
                                    <td class="atribpor">' . $this->atribpor[$this->id][$dim][$subdim][$atrib] . '%</td>
                                    <td colspan="' . ($colspan - $colspandim + 1) . '">' .
                                    htmlspecialchars($this->atributo[$this->id][$dim][$subdim][$atrib]['nombre'], ENT_QUOTES) . '</td>
                        ';

                        $k = 1;
                        foreach ($this->valores[$id][$dim] as $grado => $elemvalue) {
                            $checked = '';
                            if (
                                isset($this->valueattribute[$id][$dim][$subdim][$atrib]) &&
                                $this->valueattribute[$id][$dim][$subdim][$atrib] == $this->valores[$id][$dim][$grado]['nombre']
                            ) {
                                $checked = 'checked';
                            }
                            echo '
                                <td><input class="custom-radio" type="radio" name="radio' . $i . $l . $indexdif . '" value="' . $k . '" ' .
                                    $checked . ' ></td>
                            ';
                            ++$k;
                        }
                        echo '<td>' . htmlspecialchars($this->atributopos[$id][$dim][$subdim][$atrib]['nombre'], ENT_QUOTES) . '</td>';
                        echo '
                                </tr>
                        ';
                        if (
                            isset($this->commentatr[$id][$dim][$subdim][$atrib])
                                && $this->commentatr[$id][$dim][$subdim][$atrib] == 'visible'
                        ) {
                            $vcomment = '';
                            if (isset($this->valuecommentAtr[$id][$dim][$subdim][$atrib])) {
                                $vcomment = $this->valuecommentAtr[$id][$dim][$subdim][$atrib];
                            }
                            echo '
                                    <tr>
                                    <td colspan="2"></td>
                                    <td colspan="' . $colspan . '">
                                        <textarea rows="2" style="height:6em;width:100%" id="observaciones' . $i . '_' . $l . '_' .
                                        $indexdif . '" name="observaciones' . $i . '_' . $l . '_' . $indexdif . '" style="width:100%">' . $vcomment .
                                        '</textarea>
                                    </td>
                                    </tr>
                            ';
                        }
                        echo '

                                <tr></tr>
                                <tr></tr>
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
        if (isset($this->valorestotal[$id])) {
            echo '
                    <table class="tabla" border=1 cellpadding=5px >
                                <tr><td class="global" colspan="1">' . strtoupper(get_string('totalvalue', 'block_evalcomix')) . '</td>
            ';

            foreach ($this->valorestotal[$id] as $grado => $elemvalue) {
                echo '<th>' . htmlspecialchars($this->valorestotal[$id][$grado]['nombre'], ENT_QUOTES) . '</th>
                ';
            }

            echo '<tr><td class="global" colspan="1"></td>';
            foreach ($this->valorestotal[$id] as $grado => $elemvalue) {
                echo '<td><input type="radio" name="radio' . $dim . '_' . $subdim . '_' . $atrib . '" /></td>
                ';
            }
        }

        echo '
                                </tr>
                            </table>
        ';
        if (isset($globalcomment)) {
            $globalcomment = ($globalcomment === 'global_comment') ? $this->observation : $globalcomment;
            echo "<br><label for='observaciones'>" . strtoupper(get_string('comments', 'block_evalcomix')) . "</label><br>
            <textarea name='observaciones' id='observaciones' rows=4 cols=20 style='width:100%'>" . $globalcomment . "</textarea>";
        }
    }
}
