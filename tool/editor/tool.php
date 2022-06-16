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

defined('MOODLE_INTERNAL') || die;

require_once('tooleditor.php');
require_once('toolscale.php');
require_once('toollistscale.php');
require_once('tooldifferential.php');
require_once('toollist.php');
require_once('toolrubric.php');
require_once('toolmix.php');
require_once('toolargument.php');

class block_evalcomix_editor_tool {
    private $object;
    public $language;
    public $type;

    public function __construct($language, $type, $titulo, $dimension, $numdim, $subdimension, $numsubdim, $atributo,
            $numatr, $valores, $numvalores, $valtotal, $numtotal, $valorestotal, $valglobal, $valglobalpor, $dimpor,
            $subdimpor, $atribpor, $commentatr, $commentdim) {
        switch($type) {
            case 'lista':{
                $this->object = new block_evalcomix_editor_toollist($language, $titulo, $dimension, $numdim, $subdimension,
                    $numsubdim, $atributo, $numatr, $valores, $numvalores, $valtotal, $numtotal, $valorestotal, $valglobal,
                    $valglobalpor, $dimpor, $subdimpor, $atribpor, $commentatr);
            }break;
            case 'escala':{
                $this->object = new block_evalcomix_editor_toolscale($language, $titulo, $dimension, $numdim, $subdimension,
                    $numsubdim, $atributo, $numatr, $valores, $numvalores, $valtotal, $numtotal, $valorestotal, $valglobal,
                    $valglobalpor, $dimpor, $subdimpor, $atribpor, $commentatr, $commentdim);
            }break;
            case 'listaescala':{
                $this->object = new block_evalcomix_editor_toollistscale($language, $titulo, $dimension, $numdim, $subdimension,
                    $numsubdim, $atributo, $numatr, $valores, $numvalores, $valtotal, $numtotal, $valorestotal, $valglobal,
                    $valglobalpor, $dimpor, $subdimpor, $atribpor, $commentatr, $commentdim);
            }break;
            case 'diferencial':{
                $this->object = new block_evalcomix_editor_tooldifferential($language, $titulo, $dimension, $numdim, $subdimension,
                    $numsubdim, $atributo, $numatr, $valores, $numvalores, $valtotal, $numtotal, $valorestotal, $valglobal,
                    $valglobalpor, $dimpor, $subdimpor, $atribpor, $commentatr);
            }break;
            case 'rubrica':{
                $this->object = new block_evalcomix_editor_toolrubric($language, $titulo, $dimension, $numdim, $subdimension,
                    $numsubdim, $atributo, $numatr, $valores, $numvalores, $valtotal, $numtotal, $valorestotal, $valglobal,
                    $valglobalpor, $dimpor, $subdimpor, $atribpor, $commentatr, $commentdim);
            }break;
            case 'mixta':{
                $this->object = new block_evalcomix_editor_toolmix($language, $titulo);
            }break;
            case 'argumentario':{
                $this->object = new block_evalcomix_editor_toolargument($language, $titulo, $dimension, $numdim, $subdimension,
                    $numsubdim, $atributo, $numatr, $dimpor, $subdimpor, $atribpor, $commentatr);
            }break;
        }
        $this->language = $language;
        $this->type = $type;
    }

    public function display_header($data = '') {
        global $CFG;

        echo '
            <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
            <html>
                <head>
                    <title>EvalCOMIX 4.3</title>

                    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
                    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
                    <link href="'. $CFG->wwwroot . '/blocks/evalcomix/style/copia.css" type="text/css" rel="stylesheet">
                    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
                    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
                    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
                    <!-- Latest compiled and minified CSS -->
                    <link rel="stylesheet"
                    href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
                    <!-- Latest compiled and minified JavaScript -->
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    <script type="text/javascript" src="javascript/size.js"></script>
                    <script type="text/javascript" src="javascript/rollover.js"></script>
                    <script type="text/javascript" src="javascript/ajax.js"></script>
                    <script type="text/javascript" src="javascript/check.js"></script>
                    <script type="text/javascript" src="javascript/valida.js"></script>
                    <script language="JavaScript" type="text/javascript">

                        function abrir() {
                            abrirVentana("ventana-modal-frame.html", 500, 300, "ventana-modal");
                        }

                        function ventanaSecundaria (URL) {
                            window.open(URL,"ventana1","width=700,height=500,left=300,top=100, scrollbars=NO")
                        }

                        function mostrar(capa) {
                            var obj = document.getElementById(capa)
                            if (obj.style.visibility== "hidden")  obj.style.visibility= "visible";
                            else if (obj.style.visibility == "visible") obj.style.visibility= "hidden";
                            else obj.style.visibility = "visible";
                        }

                        document.onkeydown = function() {
                            if (window.event && window.event.keyCode == 116) {
                                window.event.keyCode = 505;
                            }
                            if (window.event && window.event.keyCode == 505) {
                                return false;
                                // window.frame(main).location.reload(True);
                            }
                        }
                        document.onkeydown = function(e) {
                            var key;
                            var evento;
                            if (window.event) {
                                if (window.event && window.event.keyCode == 116) {
                                    window.event.keyCode = 505;
                                }
                                if (window.event && window.event.keyCode == 505) {
                                    return false;
                                    // window.frame(main).location.reload(True);
                                }
                            }
                            else {
                                evento = e;
                                key = e.which; // Firefox
                                if (evento && key == 116) {
                                    key = 505;
                                }
                                if (evento && key == 505) {
                                    return false;
                                    // window.frame(main).location.reload(True);
                                }
                            }


                        }

                        function unificarValores(tagname, element)
                        {
                            var valores = document.getElementsByName(tagname);
                            for (var i=0; i<valores.length; i++) {
                                valores[i].value=element.value;
                            }
                        }
                        function confirmar(mensaje) {
                            var r=confirm(mensaje);
                            if (r==true) {
                                return true;
                            }
                            return false;
                        }
                        function imprimir(que) {
                            var ifrm = document.createElement("IFRAME");
                            ifrm.setAttribute("src", "generator.php?op=view");
                            ifrm.setAttribute("id", "frameprint");
                            ifrm.style.width = 640+"px";
                            ifrm.style.height = 480+"px";
                            document.body.appendChild(ifrm);
                            var id = "frameprint";
                            var iframe = document.frames ? document.frames[id] : document.getElementById(id);
                            var ifWin = iframe.contentWindow || iframe;
                            ifWin.focus();
                            ifWin.print();

                        }

                        function checkwindow() {
                            window.opener.location.reload();
                            /*window.opener.close();*/
                        }

                        document.oncontextmenu=function() {return false;}

                    </script>
                </head>

                <!--<body id="body" onunload="checkwindow();" > -->
                <body id="body" >
                    <noscript>
                        <div style="color:#f00">
                        Para el correcto funcionamiento de EvalCOMIX debe habilitar Javascript en su navegador</div>
                    </noscript>
                    <div id="cabecera">
                        <div id="menu">
                            <a href="#" onclick=\'javascript:
        var cadena;
        if (document.getElementById("cuerpomix")) {
            cadena="addtool=1&titulo="+document.getElementById("titulo").value+""}
        else {cadena="id=0&addDim=1&titulo0="+document.getElementById("titulo0").value+"";}
        sendPost("html","courseid='.$data['courseid'].'&amp;save=1&amp;"+cadena+"","mainform0");\'>
        <img id="guardar" src="'. $CFG->wwwroot . '/blocks/evalcomix/images/guardar.png"
        onmouseover="javascript:cAmbiaOver(this.id, \''. $CFG->wwwroot . '/blocks/evalcomix/images/guardarhover.png\');"
        onmouseout="javascript:cAmbiaOut(this.id, \''. $CFG->wwwroot . '/blocks/evalcomix/images/guardar.png\');" alt="' .
        get_string('TSave', 'block_evalcomix') . '" title="' . get_string('TSave', 'block_evalcomix') . '"/></a>

        <a href="generator.php?op=export&courseid='.$data['courseid'].'" onclick=\'javascript:
        var r=confirm("         Estás a punto de exportar el instrumento.
        \n\nAsegúrate de haber GUARDADO todos los cambios realizados\n");
        if (r==true) {return true;}return false;\'><img id="exportar" src="'.
        $CFG->wwwroot . '/blocks/evalcomix/images/exportar.png" alt="' .
        get_string('TExport', 'block_evalcomix') . '" title="' . get_string('TExport', 'block_evalcomix') .
        '" onmouseover="javascript:cAmbiaOver(this.id, \''. $CFG->wwwroot . '/blocks/evalcomix/images/exportarhover.png\');"
        onmouseout="javascript:cAmbiaOut(this.id, \''. $CFG->wwwroot . '/blocks/evalcomix/images/exportar.png\');"/></a>

        <a onClick="MasTxt(\'mainform0\');" href=#><img id="aumentar" src="'.
        $CFG->wwwroot . '/blocks/evalcomix/images/aumentar.png" alt="Aumentar" title="' .
        get_string('TAumentar', 'block_evalcomix') . '"
        onmouseover="javascript:cAmbiaOver(this.id, \''. $CFG->wwwroot . '/blocks/evalcomix/images/aumentarhover.png\');"
        onmouseout="javascript:cAmbiaOut(this.id, \''. $CFG->wwwroot . '/blocks/evalcomix/images/aumentar.png\');"/></a>

        <a onClick="MenosTxt(\'mainform0\');" href=#><img id="disminuir" src="'.
        $CFG->wwwroot .'/blocks/evalcomix/images/disminuir.png"
        alt="Disminuir" title="'.
        get_string('TDisminuir', 'block_evalcomix') . '"
        onmouseover="javascript:cAmbiaOver(this.id, \''. $CFG->wwwroot . '/blocks/evalcomix/images/disminuirhover.png\');"
        onmouseout="javascript:cAmbiaOut(this.id, \''. $CFG->wwwroot . '/blocks/evalcomix/images/disminuir.png\');"/></a>

        <a href="generator.php?op=view"><img id="visualizar"
        src="'. $CFG->wwwroot . '/blocks/evalcomix/images/visualizar.png" alt="Ver" title="'.
        get_string('TView', 'block_evalcomix') . '"
        onmouseover="javascript:cAmbiaOver(this.id, \''. $CFG->wwwroot . '/blocks/evalcomix/images/visualizarhover.png\');"
        onmouseout="javascript:cAmbiaOut(this.id, \''. $CFG->wwwroot . '/blocks/evalcomix/images/visualizar.png\');"/></a>
        <a href="servidor.php?op=imprimir"><img id="imprimir" src="'.
        $CFG->wwwroot . '/blocks/evalcomix/images/imprimir.png" alt="'.
        get_string('TPrint', 'block_evalcomix') . '" title="' . get_string('TPrint', 'block_evalcomix') .
        '" onmouseover="javascript:cAmbiaOver(this.id, \''. $CFG->wwwroot . '/blocks/evalcomix/images/imprimirhover.png\');"
        onmouseout="javascript:cAmbiaOut(this.id, \''. $CFG->wwwroot . '/blocks/evalcomix/images/imprimir.png\');"/></a>
        ';

        $lang = 'es_ES';
        if ($this->language == 'en_utf8') {
            $lang = 'en_US';
        }

        if (!isset($id)) {
            $id = null;
        }
        echo '
                            <a  href="http://avanza.uca.es/assessmentservice/help/'.$lang.
                            '/" target="_blank"><img id="ayuda" src="'.
                            $CFG->wwwroot . '/blocks/evalcomix/images/ayuda.png" alt="' .
                            get_string('THelp', 'block_evalcomix') . '" title="' . get_string('THelp', 'block_evalcomix') . '"
                            onmouseover="javascript:cAmbiaOver(this.id, \''.
                            $CFG->wwwroot . '/blocks/evalcomix/images/ayudahover.png\');"
                            onmouseout="javascript:cAmbiaOut(this.id, \''.
                            $CFG->wwwroot . '/blocks/evalcomix/images/ayuda.png\');"/></a>
                            <a  href=\'javascript:mostrar("about")\'><img id="acerca"
                            src="'. $CFG->wwwroot . '/blocks/evalcomix/images/acerca.png" alt="'
                            . get_string('TAbout', 'block_evalcomix') . '"
                            title="' . get_string('TAbout', 'block_evalcomix') .
                            '" onmouseover="javascript:cAmbiaOver(this.id, \''.
                            $CFG->wwwroot . '/blocks/evalcomix/images/acercahover.png\');"
                            onmouseout="javascript:cAmbiaOut(this.id, \''.
                            $CFG->wwwroot . '/blocks/evalcomix/images/acerca.png\');"/></a>
                        </div>
                    </div>
                    <div id="about">
                        <div style="margin:10px">Acerca de</div>
                        <div id="about_white">
                            '.get_string('idea', 'block_evalcomix').'<BR>
                            <div class="about_linea">María Soledad Ibarra Sáiz</div>
                            <div class="about_linea">Gregorio Rodríguez Gómez</div>
                            '.get_string('design', 'block_evalcomix').'<BR>
                            <div class="about_linea">Álvaro Martínez Del Val</div>
                            <div class="about_linea">Daniel Cabeza Sánchez</div>
                            '.get_string('develop', 'block_evalcomix').'<BR>
                            <div class="about_linea">Daniel Cabeza Sánchez</div>
                            '.get_string('translation', 'block_evalcomix').'<BR>
                            <div class="about_linea">Daniel Cabeza Sánchez</div>
                            '.get_string('colaboration', 'block_evalcomix').'<BR>
                            <div class="about_linea">Juan A. Caballero Hernández</div>
                            <div class="about_linea">Claudia Ortega Gómez</div>
                            <div class="about_linea">Álvaro Martínez Del Val</div>
                            <div class="about_linea">Juan Manuel Dodero Beardo</div>
                            <div class="about_linea">Miguel A. Gómez Ruiz</div>
                            <div class="about_linea">Álvaro R. León Rodríguez</div>
                            <div class="about_linea">Antonio Gámez Mellado</div>
                            '.get_string('license', 'block_evalcomix').'<BR>
                            <div class="about_linea">GNU GPL v2</div><br>
                            <input type="button" style="margin-left:4em;width:5em;" onclick=\'javascript:mostrar("about");\'
                            value="Cerrar"/>
                        </div>
                    </div>
                    <div id="loader" style="text-align:center"></div>
                    <form id="mainform0" name="mainform'.$id.'" method="POST" action="generator.php">
                    ';

            flush();
    }

    public function export() {
        return $this->object->export();
    }

    public function display_tool($data, $id) {
        return $this->object->display_tool($data, $id);
    }

    public function display_body($data) {
        $html = $this->object->display_body($data);

        return $html;
    }

    public function display_dimension($dim, $data, $id) {
        return $this->object->display_dimension($dim, $data, $id);
    }

    public function display_subdimension($dim, $subdim, $data, $id) {
        return $this->object->display_subdimension($dim, $subdim, $data, $id);
    }

    public function display_competencies_modal($id, $dim, $subdim, $mix = '', $label = 'subdimension') {
        return $this->object->display_competencies_modal($id, $dim, $subdim, $mix, $label);
    }

    public function display_footer() {
        echo '      </form>
                </body>
            </html>';
            flush();
    }

    public function display_dialog() {
        global $CFG;
        $type = required_param('type', PARAM_ALPHA);
        $identifier = required_param('identifier', PARAM_ALPHANUM);
        $courseid = required_param('courseid', PARAM_INT);
        echo '
        <div id="bgmenu">
            <div class="text-center bg-white"><img src="'.$CFG->wwwroot.'/blocks/evalcomix/images/evalcomix.jpg"
            alt="EvalCOMIX"></div>
            <div id="menu">
                <div class="mb-3 ml-1"><b>'.get_string('importfile', 'block_evalcomix').'</b></div>
                    <form name="formimport" enctype="multipart/form-data" action="servidor.php" method=post>
                        <input type="hidden" name="courseid" value="'.$courseid.'">
                        <label for="Filetype">' . get_string('selectfile', 'block_evalcomix') . ':</label><br>
                        <input type="file" name="Filetype" id="Filetype"><br><br><br>
                        <input type="submit" value="' . get_string('upfile', 'block_evalcomix') . '"
                        onclick=\'javascript:if (document.formimport.Filetype.value.lastIndexOf(".evx") == -1) {
                            alert("' . get_string('ErrorExtension', 'block_evalcomix') . '");return false;}\'>
                        <input type="button" value="'.get_string('cancel').'"
    onclick="location.href=\''.$CFG->wwwroot .'/blocks/evalcomix/tool/editor/selection.php?type=new&courseid='.
        $courseid.'&identifier='.$identifier.'\'">
                    </form>
                </div>
            </div>
        </div>
        </body>
    </html>
        ';
    }

    public function add_dimension($dim, $key, $id = 0) {
        $this->object->add_dimension($dim, $key, $id);
    }
    public function add_subdimension($dim, $subdim, $key, $id=0) {
        return $this->object->add_subdimension($dim, $subdim, $key, $id);
    }

    public function up_block($params) {
        return $this->object->up_block($params);
    }
    public function down_block($params) {
        return $this->object->down_block($params);
    }

    public function add_attribute($dim, $subdim, $atrib, $key, $id=0) {
        return $this->object->add_attribute($dim, $subdim, $atrib, $key, $id);
    }

    public function add_values($dim, $key, $id=0) {
        return $this->object->add_values($dim, $key, $id);
    }

    public function add_total_values($key, $id=0) {
        return $this->object->add_total_values($key, $id);
    }

    public function remove_total_values($grado, $id=0) {
        return $this->object->remove_total_values($grado, $id);
    }

    public function remove_dimension($dim, $id=0) {
        return $this->object->remove_dimension($dim, $id);
    }

    public function remove_subdimension($dim, $subdim, $id=0) {
        return $this->object->remove_subdimension($dim, $subdim, $id);
    }

    public function remove_attribute($dim, $subdim, $atrib, $id=0) {
        return $this->object->remove_attribute($dim, $subdim, $atrib, $id);
    }

    public function remove_values($dim, $grado, $id=0) {
        return $this->object->remove_values($dim, $grado, $id);
    }

    public function add_range($dim, $grado, $key, $id=0) {
        return $this->object->add_range($dim, $grado, $key, $id);
    }

    public function remove_range($dim, $grado, $key, $id=0) {
        return $this->object->remove_range($dim, $grado, $key, $id);
    }

    public function add($type, $index = null) {
        return $this->object->add($type, $index);
    }

    public function remove($index) {
        return $this->object->remove($index);
    }

    public function get_numtool() {
        return $this->object->get_numtool();
    }

    public function get_toolpor() {
        return $this->object->get_toolpor();
    }

    public function get_tools() {
        return $this->object->get_tools();
    }

    public function get_tool($id) {
        return $this->object->get_tool($id);
    }

    public function get_titulo($id) {
        return $this->object->get_titulo($id);
    }

    public function get_dimension($id) {
        return $this->object->get_dimension($id);
    }

    public function get_numdim($id) {
        return $this->object->get_numdim($id);
    }

    public function get_subdimension($id) {
        return $this->object->get_subdimension($id);
    }

    public function get_numsubdim($id) {
        return $this->object->get_numsubdim($id);
    }

    public function get_atributo($id) {
        return $this->object->get_atributo($id);
    }

    public function get_numatr($id) {
        return $this->object->get_numatr($id);
    }

    public function get_valores($id) {
        return $this->object->get_valores($id);
    }

    public function get_numvalores($id) {
        return $this->object->get_numvalores($id);
    }

    public function get_valtotal($id) {
        return $this->object->get_valtotal($id);
    }

    public function get_numtotal($id = null) {
        return $this->object->get_numtotal($id);
    }

    public function get_valtotalpor($id) {
        return $this->object->get_valtotalpor($id);
    }

    public function get_valorestotal($id) {
        return $this->object->get_valorestotal($id);
    }

    public function get_valglobal($id) {
        return $this->object->get_valglobal($id);
    }

    public function get_valglobalpor($id) {
        return $this->object->get_valglobalpor($id);
    }

    public function get_dimpor($id) {
        return $this->object->get_dimpor($id);
    }

    public function get_subdimpor($id) {
        return $this->object->get_subdimpor($id);
    }

    public function get_atribpor($id) {
        return $this->object->get_atribpor($id);
    }

    public function get_numrango($id) {
        return $this->object->get_numrango($id);
    }

    public function get_rango($id) {
        return $this->object->get_rango($id);
    }

    public function get_description($id) {
        return $this->object->get_description($id);
    }

    public function get_commentatr($id) {
        return $this->object->get_commentatr($id);
    }

    public function get_porcentage() {
        return $this->object->get_porcentage();
    }

    public function get_dimensionsid() {
        return $this->object->get_dimensionsid();
    }

    public function get_subdimensionsid() {
        return $this->object->get_subdimensionsid();
    }

    public function get_atributosid() {
        return $this->object->get_atributosid();
    }

    public function get_valoresid() {
        return $this->object->get_valoresid();
    }

    public function get_valorestotalesid() {
        return $this->object->get_valorestotalesid();
    }

    public function get_valoreslistaid() {
        return $this->object->get_valoreslistaid();
    }

    public function get_rangoid() {
        return $this->object->get_rangoid();
    }

    public function get_descriptionsid() {
        return $this->object->get_descriptionsid();
    }

    public function get_atributopos() {
        return $this->object->get_atributopos();
    }

    public function get_atributosposid() {
        return $this->object->get_atributosposid();
    }

    public function get_plantillasid() {
        return $this->object->get_plantillasid();
    }

    public function get_valoreslista() {
        return $this->object->get_valoreslista();
    }

    public function get_competency() {
        return $this->object->get_competency();
    }

    public function get_outcome() {
        return $this->object->get_outcome();
    }

    public function get_competency_string($id, $dim, $subdim) {
        return $this->object->get_competency_string($id, $dim, $subdim);
    }

    public function set_id($id) {
        $this->object->set_id($id);
    }

    public function set_titulo($titulo, $id) {
        $this->object->set_titulo($titulo, $id);
    }

    public function set_dimension($dimension, $id) {
        $this->object->set_dimension($dimension, $id);
    }

    public function set_numdim($numdim, $id) {
        $this->object->set_numdim($numdim, $id);
    }

    public function set_subdimension($subdimension, $id) {
        $this->object->set_subdimension($subdimension, $id);
    }

    public function set_numsubdim($numsubdim, $id) {
        $this->object->set_numsubdim($numsubdim, $id);
    }

    public function set_atributo($atributo, $id) {
        $this->object->set_atributo($atributo, $id);
    }

    public function set_numatr($numatr, $id) {
        $this->object->set_numatr($numatr, $id);
    }

    public function set_valores($valores, $id) {
        $this->object->set_valores($valores, $id);
    }

    public function set_numvalores($numvalores, $id) {
        $this->object->set_numvalores($numvalores, $id);
    }

    public function set_valtotal($valtotal, $id) {
        $this->object->set_valtotal($valtotal, $id);
    }

    public function set_numtotal($numtotal, $id) {
        $this->object->set_numtotal($numtotal, $id);
    }

    public function set_valtotalpor($valtotalpor, $id) {
        $this->object->set_valtotalpor($valtotalpor, $id);
    }

    public function set_valorestotal($valorestotal, $id) {
        $this->object->set_valorestotal($valorestotal, $id);
    }

    public function set_valglobal($valglobal, $id) {
        $this->object->set_valglobal($valglobal, $id);
    }

    public function set_valglobalpor($valglobalpor, $id) {
        $this->object->set_valglobalpor($valglobalpor, $id);
    }

    public function set_dimpor($dimpor, $id) {
        $this->object->set_dimpor($dimpor, $id);
    }

    public function set_subdimpor($subdimpor, $id) {
        $this->object->set_subdimpor($subdimpor, $id);
    }

    public function set_atribpor($atribpor, $id) {
        $this->object->set_atribpor($atribpor, $id);
    }

    public function set_rango($rango, $id) {
        $this->object->set_rango($rango, $id);
    }

    public function set_toolpor($porcentage) {
        $this->object->set_toolpor($porcentage);
    }

    public function set_view($view, $id) {
        $this->object->set_view($view, $id);
    }

    public function set_commentatr($comment) {
        $this->object->set_commentatr($comment);
    }

    public function set_dimensionsid($dimensionsid, $id) {
        $this->object->set_dimensionsid($dimensionsid, $id);
    }

    public function set_subdimensionsid($subdimensionsid, $id) {
        $this->object->set_subdimensionsid($subdimensionsid, $id);
    }

    public function set_atributosid($atributosid, $id) {
        $this->object->set_atributosid($atributosid, $id);
    }

    public function set_valoresid($valoresid, $id) {
        $this->object->set_valoresid($valoresid, $id);
    }

    public function set_valorestotalesid($valoresid, $id) {
        $this->object->set_valorestotalesid($valoresid, $id);
    }

    public function set_valoreslistaid($valoresid, $id) {
        $this->object->set_valoreslistaid($valoresid, $id);
    }

    public function set_rangoid($valoresid, $id) {
        $this->object->set_rangoid($valoresid, $id);
    }

    public function set_descriptionsid($valoresid, $id) {
        $this->object->set_descriptionsid($valoresid, $id);
    }

    public function set_atributopos($atributo, $id) {
        $this->object->set_atributopos($atributo, $id);
    }

    public function set_atributosposid($atributo, $id) {
        $this->object->set_atributosposid($atributo, $id);
    }

    public function set_plantillasid($plantillas, $id) {
        $this->object->set_plantillasid($plantillas, $id);
    }

    public function add_outcome($id, $dimkey, $subdimkey, $newoutkey, $shortname, $outkey = null) {
        $this->object->add_outcome($id, $dimkey, $subdimkey, $newoutkey, $shortname, $outkey);
    }

    public function add_competency($id, $dimkey, $subdimkey, $newcompkey, $shortname, $compkey = null) {
        $this->object->add_competency($id, $dimkey, $subdimkey, $newcompkey, $shortname, $compkey);
    }

    public function remove_competency($id, $dimkey, $subdimkey, $compkey) {
        $this->object->remove_competency($id, $dimkey, $subdimkey, $compkey);
    }

    public function remove_outcome($id, $dimkey, $subdimkey, $outkey) {
        $this->object->remove_outcome($id, $dimkey, $subdimkey, $outkey);
    }

    public function import($xml, $toolid = '') {
        global $DB;

        unset($this->object);
        $typeevx3 = dom_import_simplexml($xml)->tagName;
        $type = '';
        if ($typeevx3 == 'mt:MixTool' || $typeevx3 == 'MixTool') {
            $this->type = 'mixta';
            $this->object = new block_evalcomix_editor_toolmix($this->language, (string)$xml['name'], (string)$xml->Description);
            $tools = array();

            $index = 1;
            if (isset($xml->Description)) {
                $index = 0;
            }

            $plantillasid = array();
            $i = 0;
            foreach ($xml as $valor) {
                if ($index == 0) {
                    $index = 1;
                    continue;
                }
                $tool = $this->import_simple_tool($valor, $i);
                $tools[$i] = $tool;
                $plantillasid[$i] = (string)$valor['id'];
                $subdimensionsid[$i] = $tool->get_subdimensionsid();
                if (!empty($toolid)) {
                    $competency = $this->set_competency_from_origen($i, $toolid, $subdimensionsid, 0);
                    $outcome = $this->set_competency_from_origen($i, $toolid, $subdimensionsid, 1);
                    $tool->set_competency($competency);
                    $tool->set_outcome($outcome);
                }
                ++$i;
            }
            $this->object->set_tools($tools);
            $this->object->set_plantillasid($plantillasid);
        } else {
            $this->object = $this->import_simple_tool($xml);

            $tagname = dom_import_simplexml($xml)->tagName;
            $typetool = '';
            if ($tagname[2] == ':') {
                $typeevx3 = explode(':', $tagname);
                $typetool = $typeevx3[1];
            } else {
                $typetool = $tagname;
            }

            $type = '';
            switch($typetool) {
                case 'ControlList':
                    $type = 'lista';
                    break;
                case 'EvaluationSet':
                    $type = 'escala';
                    break;
                case 'Rubric':
                    $type = 'rubrica';
                    break;
                case 'ControlListEvaluationSet':
                    $type = 'listaescala';
                    break;
                case 'SemanticDifferential':
                    $type = 'diferencial';
                    break;
                case 'ArgumentSet':
                    $type = 'argumentario';
                    break;
            }
            $this->type = $type;
        }
    }

    public function import_simple_tool($xml, $id = 0) {
        $language = $this->language;
        $dimension;
        $numdim;
        $subdimension;
        $numsubdim;
        $atributo;
        $numatr;
        $valores;
        $numvalores;
        $numtotal = null;
        $valores;
        $valtotal = null;
        $valtotalpor;
        $valorestotal = null;
        $valglobal;
        $valglobalpor;
        $subdimpor;
        $atribpor;
        $numrango;
        $rango;
        $observation = null;
        $description;

        $tagname = dom_import_simplexml($xml)->tagName;
        $typetool = '';
        if ($tagname[2] == ':') {
            $typeevx3 = explode(':', $tagname);
            $typetool = $typeevx3[1];
        } else {
            $typetool = $tagname;
        }

        $type = '';
        switch($typetool) {
            case 'ControlList':
                $type = 'lista';
                break;
            case 'EvaluationSet':
                $type = 'escala';
                break;
            case 'Rubric':
                $type = 'rubrica';
                break;
            case 'ControlListEvaluationSet':
                $type = 'lista+escala';
                break;
            case 'SemanticDifferential':
                $type = 'diferencial';
                break;
            case 'ArgumentSet':
                $type = 'argumentario';
                break;
        }

        if ($type == 'diferencial') {
            if ($this->type != 'mixta') {
                $this->type = $type;
            }

            $titulo = (string)$xml['name'];
            $dim = 0;
            $numvalores[$id][$dim] = (string)$xml['values'];
            $numdim[$id] = 1;
            $dimension[$id][$dim]['nombre'] = "Dimension1";
            $valglobal[$id][$dim] = false;
            $valglobalpor[$id][$dim] = null;
            $dimpor[$id][$dim] = 100;
            $subdim = 0;
            $subdimension[$id][$dim][$subdim]['nombre'] = 'subdimension1';
            $numsubdim[$id][$dim] = 1;
            $subdimpor[$id][$dim][$subdim] = 100;
            $numatr[$id][$dim][$subdim] = (string)$xml['attributes'];
            $percentage = (string)$xml['percentage'];
            $valuecommentatr = array();
            $observation[$id] = (string)$xml->Description;

            $atributosid = array();
            $atributosposid = array();
            $valoresid = array();

            $j = 0;
            foreach ($xml->Values[0] as $values) {
                $valores[$id][$dim][$j]['nombre'] = (string)$values;
                $valoresid[$id][$dim][$j] = (string)$values['id'];
                ++$j;
            }

            $atrib = 0;
            foreach ($xml->Attribute as $attribute) {
                $atributo[$id][$dim][$subdim][$atrib]['nombre'] = (string)$attribute['nameN'];
                $atributopos[$id][$dim][$subdim][$atrib]['nombre'] = (string)$attribute['nameP'];
                $atribpor[$id][$dim][$subdim][$atrib] = (string)$attribute['percentage'];
                $commentatr[$id][$dim][$subdim][$atrib] = 'hidden';
                $valueattribute[$id][$dim][$subdim][$atrib] = (string)$attribute;
                $atributosid[$id][$dim][$subdim][$atrib] = (string)$attribute['idNeg'];
                $atributosposid[$id][$dim][$subdim][$atrib] = (string)$attribute['idPos'];

                if ((string)$attribute['comment'] == 1) {
                    $commentatr[$id][$dim][$subdim][$atrib] = 'visible';
                }

                if ((string)$attribute['comment'] != 1 && (string)$attribute['comment'] != '') {
                    $commentatr[$id][$dim][$subdim][$atrib] = 'visible';
                    $valuecommentatr[$id][$dim][$subdim][$atrib] = (string)$attribute['comment'];
                }

                ++$atrib;
            }

            $params['atributosid'] = $atributosid;
            $params['atributosposid'] = $atributosposid;
            $params['valoresid'] = $valoresid;
            $toolid = (string)$xml['id'];
            $subdimensionsid[$id][$dim][$subdim] = $toolid;
            $params['subdimensionsid'] = $subdimensionsid;
            $params['competency'] = $this->set_competency_from_origen($id, $toolid, $subdimensionsid, 0);
            $params['outcome'] = $this->set_competency_from_origen($id, $toolid, $subdimensionsid, 1);
        } else {
            $valtotal = null;
            if (isset($xml->GlobalAssessment->Values->Value[0])) {
                $valtotal[$id] = 'true';
                $valuetotalvalue[$id] = (string)$xml->GlobalAssessment->Attribute;
            } else {
                $valuetotalvalue[$id] = '';
            }
            $titulo = (string)$xml['name'];
            $observation[$id] = (string)$xml->Description;
            $percentage = (string)$xml['percentage'];
            $numdim[$id] = (string)$xml['dimensions'];
            $valglobal = $valglobalpor = $commentdim = array();

            // For form filled.
            $valueattribute = array();
            $valueglobaldim = array();
            $valuecommentatr = array();
            $valuecommentdim = array();
            $dimensionsid = array();
            $subdimensionsid = array();
            $atributosid = array();
            $valoresid = array();
            $valoreslistaid = array();
            $valoreslista = array();
            $rangoid = array();
            $descriptionsid = array();
            $competency = array();
            $outcome = array();

            // Datas of dimensions.
            $dim = 0;
            foreach ($xml->Dimension as $dimen) {
                if (isset($dimen->DimensionAssessment[0]->Attribute)) {
                    $valglobal[$id][$dim] = 'true';
                    $valglobalpor[$id][$dim] = (string)$dimen->DimensionAssessment['percentage'];
                    $valueglobaldim[$id][$dim] = (string)$dimen->DimensionAssessment[0]->Attribute;
                    if ($type == 'rubrica') {
                        $valueglobaldim[$id][$dim] = (string)$dimen->DimensionAssessment[0]->Attribute->selection->instance;
                    }
                    $commentdim[$id][$dim] = 'hidden';
                    if ((string)$dimen->DimensionAssessment[0]->Attribute['comment'] == '1') {
                            $commentdim[$id][$dim] = 'visible';
                    }
                    if ((string)$dimen->DimensionAssessment[0]->Attribute['comment'] != '1'
                            && (string)$dimen->DimensionAssessment[0]->Attribute['comment'] != '') {
                        $commentdim[$id][$dim] = 'visible';
                        $valuecommentdim[$id][$dim] = (string)$dimen->DimensionAssessment[0]->Attribute['comment'];
                    }
                }
                $dimension[$id][$dim]['nombre'] = (string)$dimen['name'];
                $dimpor[$id][$dim] = (string)$dimen['percentage'];
                $numsubdim[$id][$dim] = (string)$dimen['subdimensions'];
                $numvalores[$id][$dim] = (string)$dimen['values'];
                $dimensionsid[$id][$dim] = (string)$dimen['id'];

                // Values of dimensions.
                $grado = 0;
                if (isset($dimen->Values[0])) {
                    foreach ($dimen->Values[0] as $values) {
                        if ($type != 'rubrica') {
                            $valores[$id][$dim][$grado]['nombre'] = (string)$values;
                            $valoresid[$id][$dim][$grado] = (string)$values['id'];
                        } else {
                            $valores[$id][$dim][$grado]['nombre'] = (string)$values['name'];
                            $numrango[$id][$dim][$grado] = (string)$values['instances'];
                            $i = 0;
                            foreach ($values->instance as $range) {
                                $rango[$id][$dim][$grado][$i] = (string)$range;
                                $rangoid[$id][$dim][$grado][$i] = (string)$range['id'];
                                $i++;
                            }
                            $valoresid[$id][$dim][$grado] = (string)$values['id'];
                        }
                        $grado++;
                    }
                }

                // Values of checklist of checklist+ratescales.
                if ($type == 'lista+escala') {
                    $grado = 0;
                    foreach ($dimen->ControlListValues[0] as $values) {
                        $valoreslista[$id][$dim][$grado]['nombre'] = (string)$values;
                        $this->valoreslista[$id][$dim][$grado]['nombre'] = (string)$values;
                        $valoreslistaid[$id][$dim][$grado] = (string)$values['id'];
                        $grado++;
                    }
                }

                // Datas of subdimension.
                $subdim = 0;
                foreach ($dimen->Subdimension as $subdimen) {
                    $subdimension[$id][$dim][$subdim]['nombre'] = (string)$subdimen['name'];
                    $subdimpor[$id][$dim][$subdim] = (string)$subdimen['percentage'];
                    $numatr[$id][$dim][$subdim] = (string)$subdimen['attributes'];
                    $subdimensionsid[$id][$dim][$subdim] = (string)$subdimen['id'];
                    $competency[$id][$dim][$subdim] = array();
                    $outcome[$id][$dim][$subdim] = array();

                    // Datas of attributes.
                    $atrib = 0;
                    foreach ($subdimen->Attribute as $attribute) {
                        $atributo[$id][$dim][$subdim][$atrib]['nombre'] = (string)$attribute['name'];
                        $atribpor[$id][$dim][$subdim][$atrib] = (string)$attribute['percentage'];
                        $atributosid[$id][$dim][$subdim][$atrib] = (string)$attribute['id'];
                        $commentatr[$id][$dim][$subdim][$atrib] = 'hidden';

                        if ((string)$attribute['comment'] == '1') {
                            $commentatr[$id][$dim][$subdim][$atrib] = 'visible';
                        }

                        if ((string)$attribute['comment'] != '1' && (string)$attribute['comment'] != '') {
                            $commentatr[$id][$dim][$subdim][$atrib] = 'visible';
                            $valuecommentatr[$id][$dim][$subdim][$atrib] = (string)$attribute['comment'];
                        }

                        $valueattribute[$id][$dim][$subdim][$atrib] = (string)$attribute;
                        if ($type == 'lista+escala') {
                            $valueattribute[$id][$dim][$subdim][$atrib] = (string)$attribute->selection;
                        }

                        if ($type == 'rubrica') {
                            $valueattribute[$id][$dim][$subdim][$atrib] = (string)$attribute->selection->instance;
                        }

                        // Descriptions of rubrics.
                        if ($type == 'rubrica') {
                            foreach ($attribute->descriptions[0] as $descrip) {
                                $grado = (string)$descrip['value'];
                                $description[$id][$dim][$subdim][$atrib][$grado] = (string)$descrip;
                                $descriptionsid[$id][$dim][$subdim][$atrib][$grado] = (string)$descrip['id'];
                            }
                        }
                        $atrib++;
                    }
                    $subdim++;
                }
                $dim++;
            }

            $params['dimensionsid'] = $dimensionsid;
            $params['subdimensionsid'] = $subdimensionsid;
            $params['atributosid'] = $atributosid;
            $params['valoresid'] = $valoresid;
            $params['valoreslistaid'] = $valoreslistaid;
            $params['valoreslista'] = $valoreslista;
            $params['rangoid'] = $rangoid;
            $params['descriptionsid'] = $descriptionsid;

            // Datas of total values.
            $numtotal = $valtotalpor = $valorestotal = null;
            if (isset($xml->GlobalAssessment[0]->Values[0]->Value)) {
                $valorestotalesid = array();
                $numtotal[$id] = (string)$xml->GlobalAssessment['values'];
                $valtotalpor[$id] = (string)$xml->GlobalAssessment['percentage'];
                $grado = 0;
                foreach ($xml->GlobalAssessment[0]->Values[0] as $value) {
                    $valorestotal[$id][$grado]['nombre'] = (string)$value;
                    $valorestotalesid[$id][$grado] = (string)$value['id'];
                    $grado++;
                }
                $params['valorestotalesid'] = $valorestotalesid;
            }
        }
        $toolid = (string)$xml['id'];

        $params['outcome'] = $this->set_competency_from_origen($id, $toolid, $subdimensionsid, 1);
        $params['competency'] = (!empty($subdimensionsid)) ?
            $this->set_competency_from_origen($id, $toolid, $subdimensionsid) : $competency;
        $instrument;
        switch($type) {
            case 'lista':{
                $instrument = new block_evalcomix_editor_toollist($language, $titulo, $dimension, $numdim, $subdimension,
                    $numsubdim, $atributo, $numatr, $valores, $numvalores, $valtotal, $numtotal, $valorestotal, $valglobal,
                    $valglobalpor, $dimpor, $subdimpor, $atribpor, $commentatr, $id, $observation, $percentage, $valueattribute,
                    $valuecommentatr, $params);
            }break;
            case 'escala':{
                $instrument = new block_evalcomix_editor_toolscale($language, $titulo, $dimension, $numdim, $subdimension,
                    $numsubdim, $atributo, $numatr, $valores, $numvalores, $valtotal, $numtotal, $valorestotal, $valglobal,
                    $valglobalpor, $dimpor, $subdimpor, $atribpor, $commentatr, $commentdim, $id, $observation, $percentage,
                    $valtotalpor, $valueattribute, $valueglobaldim, $valuetotalvalue, $valuecommentatr, $valuecommentdim,
                    $params);
            }break;
            case 'lista+escala':{
                $instrument = new block_evalcomix_editor_toollistscale($language, $titulo, $dimension, $numdim, $subdimension,
                    $numsubdim, $atributo, $numatr, $valores, $numvalores, $valtotal, $numtotal, $valorestotal, $valglobal,
                    $valglobalpor, $dimpor, $subdimpor, $atribpor, $commentatr, $commentdim, $id, $observation, $percentage,
                    $valtotalpor, $valueattribute, $valueglobaldim, $valuetotalvalue, $valuecommentatr, $valuecommentdim,
                    $params);
            }break;
            case 'rubrica':{
                $instrument = new block_evalcomix_editor_toolrubric($language, $titulo, $dimension, $numdim, $subdimension,
                    $numsubdim, $atributo, $numatr, $valores, $numvalores, $valtotal, $numtotal, $valorestotal, $valglobal,
                    $valglobalpor, $dimpor, $subdimpor, $atribpor, $commentatr, $commentdim, $id, $observation, $percentage,
                    $valtotalpor, $rango, $numrango, $description, $valueattribute, $valueglobaldim, $valuetotalvalue,
                    $valuecommentatr, $valuecommentdim, $params);
            }break;
            case 'diferencial':{
                $instrument = new block_evalcomix_editor_tooldifferential($language, $titulo, $dimension, $numdim,
                    $subdimension, $numsubdim, $atributo, $numatr, $valores, $numvalores, $valtotal, $numtotal, $valorestotal,
                    $valglobal, $valglobalpor, $dimpor, $subdimpor, $atribpor, $commentatr, $id, $observation, $percentage,
                    $atributopos, $valueattribute, $valuecommentatr, $params);
            }break;
            case 'argumentario':{
                $instrument = new block_evalcomix_editor_toolargument($language, $titulo, $dimension, $numdim, $subdimension,
                    $numsubdim, $atributo, $numatr, $dimpor, $subdimpor, $atribpor, $commentatr, $id, $observation,
                    $percentage, $valuecommentatr, $params);
            }break;
        }
        return $instrument;
    }

    public function display_view() {
            $id = '';
            echo '
            <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
            <html>
                <head>
                    <title>EvalCOMIX 4.2</title>
                    <link href="style/copia.css" type="text/css" rel="stylesheet">
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                    <script type="text/javascript" src="javascript/size.js"></script>
                    <script type="text/javascript" src="javascript/rollover.js"></script>
                    <script type="text/javascript" src="javascript/ajax.js"></script>
                    <script type="text/javascript" src="javascript/check.js"></script>
                    <script type="text/javascript" src="javascript/valida.js"></script>
                    <script type="text/javascript" src="javascript/ventana-modal.js"></script>
                    <script language="JavaScript" type="text/javascript">

                        document.onkeydown = function() {
                            if (window.event && window.event.keyCode == 116) {
                                window.event.keyCode = 505;
                            }
                            if (window.event && window.event.keyCode == 505) {
                                return false;
                                // window.frame(main).location.reload(True);
                            }
                        }
                        document.onkeydown = function(e) {
                            var key;
                            var evento;
                            if (window.event) {
                                if (window.event && window.event.keyCode == 116) {
                                    window.event.keyCode = 505;
                                }
                                if (window.event && window.event.keyCode == 505) {
                                    return false;
                                    // window.frame(main).location.reload(True);
                                }
                            }
                            else {
                                evento = e;
                                key = e.which; // Firefox
                                if (evento && key == 116) {
                                    key = 505;
                                }
                                if (evento && key == 505) {
                                    return false;
                                    // window.frame(main).location.reload(True);
                                }
                            }
                        }
                        function imprimir(que) {
                            var ventana = window.open("", "", "");
                            var contenido = "<html><head><link href=\'style/copia.css\' type=\'text/css\'
                            rel=\'stylesheet\'></head><body onload=\'window.print();window.close();\'>";
                            contenido = contenido + document.getElementById(que).innerHTML + "</body></html>";
                            ventana.document.open();
                            ventana.document.write(contenido);
                            ventana.document.close();
                        }

                        document.oncontextmenu=function() {return false;}

                    </script>
                    <style type="text/css">
                        #mainform0{
                            border: 1px solid #00f;
                        }
                        .dimension, .valoracionglobal, .valoraciontotal, #comentario{
                            border: 2px solid #6B8F6B
;
                        }
                        .subdimension{
                            background-color: #F1F2F1;
                            margin: 0.7em 2em 0em 2em;
                            overflow:visible
                        }
                    </style>
                </head>

                <body id="body" onload=\'javascript:window.print();location.href="generator.php"\'>

                    <form id="mainform0" name="mainform'.$id.'" method="POST" action="generator.php">
        ';
    }
    public function display_body_view($data, $mix='', $porcentage='') {
        return $this->object->display_body_view($data, $mix, $porcentage);
    }
    public function display_dimension_view($dim, $data, $id=0, $mix='') {
        return $this->object->display_dimension_view($dim, $data, $id, $mix);
    }
    public function display_subdimension_view($dim, $subdim, $data, $id=0, $mix='') {
        return $this->object->display_subdimension_view($dim, $data, $id, $mix);
    }
    public function print_tool() {
        return $this->object->print_tool();
    }

    public function view_assessment_header() {
        echo '
        <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
                <html>

                    <head>
                        <title>EVALCOMIX</title>
                        <style>
body {color:#333;background-color: #fff;font-family: "arial";font-size: 0.72em;     margin:0; }
p{margin:0; }  form{margin:0; }  h1,h2,h3,h4,h5,h6{color: #146C84; } #linea{height: 5px;background-color: #f5751a; }
#titulo{font-size: 1em;color:#146C84;font-weight: bold;font-style:italic;margin-top: -2em;margin-left:5em;margin-bottom: 1em; }
#cabecera{border-bottom: 1px solid #000; }  /*campos1------------------------------------*/
#ca1_env{float: right;margin-bottom: 1em; } /*-------------------------------------------*/
#crear{padding-left: 0.5em; }#dim{background-color: #146C84;color: #fff; }
.planmenu{text-decoration:none;border-right: 1px solid #fff;padding: 0.8em 1em 0.7em 0;color:#fff; }
/*.planmenu:hover{text-decoration:none;color: #fff;background-color: #00aaff;border-right: 1px solid #000000;
padding-right: 1em; }*/  .tam{width: 85%; }  .fields{margin-bottom: 1em; }  .fields legend{color: #146C84;font-weight: bold; }
.tabla{width: 100%;background-color: #E5F0FD;font-family: "arial";  margin:0;   padding:0; /*   font-size: 1em;*/ }
.tabla th{background-color: #146C84;color: #fff; }  .td{    font-size: 0.8em;font-weight: bold;text-align:center; }
.rub{width: 12em; }  .eval{margin:0;    padding:0; }  .global{text-align:right;font-style: italic;font-weight: bold; }
.boton_est{text-decoration:none;color: #0000ff;font-weight:bold;padding: 0.3em 0.8em 0.3 0.8em;
background-color: #a3a3a3;border-right: 1px solid #000;border-bottom: 1px solid #000;border-top: 1px solid #fff;
border-left: 1px solid #fff; }  .botones{padding-bottom: 2em; }  .boton{float:right; }  .table_rubrica{width: 90%; }
._rubrica textarea{width:100%; }  .arubric{padding: 5% 40% 5% 40%;background-color:#fff;text-decoration:none; }
.float{margin-left: 1em;float:left; }  .obligatorio{font-size: 0.7em;font-weight: bold; }  .bold{font-weight: bold; }
.subdim{font-style:italic;font-weight:bold; }  .rango{text-align:center; }
.search_menu{text-decoration:none;color:#146f8f;padding:0.1em 0.2em 0.1em 0.2em;background-color:#e3e3e3;
border: 1px solid #a3a3a3;font-weight: bold; }  .clear{clear:both; }  .pordim{  font-weight: bold;  witdh: 3em;  }
.subdimpor{     font-style:italic;  font-weight:bold;   text-align:center;  font-size: 0.9em; }
.atribpor{  text-align:right;   font-size:0.8em }  .showcomment,
.showcomment:hover{     background-image: url("../images/editar.gif");  width: 19px;    height: 16px;   border:0;
background-color:#fff;  background-repeat: no-repeat; }  .showcomment{  border: 1px solid #434343;   }
.showcomment:hover{     border: 2px solid #0076C1; }
.custom-radio{width:15px;height:15px;cursor: pointer;}
                        </style>
                        ';

    }

    public function view_tool($root = '', $grade = '', $print='view', $title = '') {
        require('lang/'. $this->language . '/evalcomix.php');
        $wprint = '';
        if ($print == 'print') {
            $wprint = 'onload="window.print()"';
        }
        $this->view_assessment_header();
        echo '
                    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
                    <script language="JavaScript" type="text/javascript">
                        function muestra_oculta(id) {
                            if (document.getElementById) { //se obtiene el id
                                var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
                                if (el.style.display == "none") {
                                    el.style.display = "block";
                                    el.disabled = false;
                                }
                                else {
                                    el.style.display = "none";
                                    el.disabled = true;
                                }
                                //el.style.display = (el.style.display == "none") ? "block" : "none";
                                //damos un atributo display:none que oculta el div
                            }
                        }
                        window.onload = function() {
                            var valores=document.getElementsByName("comAtrib");
                            for (var i=0; i<valores.length; i++) {
                                valores[i].style.display = "none";

                            }
                        }
                    </script>
                </head>
                    <body '. $wprint .'>
                    <div class="clear"></div>
                    <div class="eval" id="evalid">
                    <h2>'.$title.'</h2>
                        <form name="mainform" method="post" action="">
        ';
        echo '
                <div class="boton" style="margin-right: 1em;">
                <input type="button" name="imprimir" value="'.get_string('TPrint', 'block_evalcomix').'"
                onclick="javascript:
                var ficha = document.getElementById(\'evalid\');
                var ventimp=window.open(\'\',\'popimpr\');ventimp.document.write(ficha.innerHTML);
                ventimp.document.close();ventimp.print();ventimp.close();">
            </div>
            <div class="clear"></div>';

        $this->object->print_tool();

        echo '
                    </form>

                </div>
            </div>
        ';

        $tool = '';
        if (isset($_GET['pla'])) {
            $tool = $_GET['pla'];
        }

        echo '
            <div class="clear"></div>
                <hr><br>
        ';

        if ($grade != '') {
            echo "<div style='text-align:right;font-size:1.7em'><span>".get_string('grade', 'block_evalcomix').": " .
            $grade . "</span></div>";
        }

        echo '
                <br><hr>
                <div class="botones">
                    <div class="boton" style="margin-right: 1em;">
!--                         <input type="button" name="imprimir" value="Imprimir" onclick="window.focus();
window.print().window.close();"> -->

                            <input type="button" name="imprimir" value="'.get_string('TPrint', 'block_evalcomix').'"
                            onclick="javascript:
                            var ficha = document.getElementById(\'evalid\');
                            var ventimp=window.open(\'\',\'popimpr\');ventimp.document.write(ficha.innerHTML);
                            ventimp.document.close();ventimp.print();ventimp.close();">

                </div>
                <div class="clear"></div>

                <div class="clear"></div>
                <br>
                </body>

            </html>
        ';
    }

    public function assessment_tool($root = '', $assessmentid = 0, $idtool = 0, $grade = '', $saved = '', $title = '') {
        require('lang/'. $this->language . '/evalcomix.php');
        $action = $root . '/assessment/saveassess.php?ass=' . $assessmentid . '&tool='.$idtool;
        $this->view_assessment_header();
        echo '  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
                    <script type="text/javascript" src="'.$root.'/client/javascript/ajax.js"></script>
                    <script>
                        function limpiar_mainform() {
                            if (confirm(\'¿Confirma que desea borrar todas las calificaciones asignadas al instrumentos?\'))
                                for (i=0;i<document.mainform.elements.length;i++) {
                                    if (document.mainform.elements[i].type == "radio"
                                    && document.mainform.elements[i].checked == true)
                                      document.mainform.elements[i].checked=false;
                                    else if (document.mainform.elements[i].type == "textarea")
                                        document.mainform.elements[i].value = "";
                            }
                        }

                        function muestra_oculta(id) {
                            if (document.getElementById) { //se obtiene el id
                                var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
                                if (el.style.display == "none") {
                                    el.style.display = "block";
                                    el.disabled = false;
                                }
                                else {
                                    el.style.display = "none";
                                    el.disabled = true;
                                }
                                //el.style.display = (el.style.display == "none") ? "block" : "none";
                                //damos un atributo display:none que oculta el div
                            }
                        }
                        window.onload = function() {
                            var valores=document.getElementsByName("comAtrib");
                            for (var i=0; i<valores.length; i++) {
                                valores[i].style.display = "none";

                            }
                        }

                    </script>
                </head>
                    <body>
                    <div class="clear"></div>
        ';

        echo '
                    <div class="eval" id="evalid">
                        <h2>'.$title.'</h2>

                        <form id="mainform" name="mainform" method="post" action="'.$action.'">
                            <div class="boton" style="margin-right: 1em;">
                            <input type="button" name="imprimir" value="'.get_string('TPrint', 'block_evalcomix').'"
                            onclick="javascript:
                            var ficha = document.getElementById(\'evalid\');
                            var ventimp=window.open(\'\',\'popimpr\');ventimp.document.write(ficha.innerHTML);
                            ventimp.document.close();ventimp.print();ventimp.close();">
                            </div>
        ';
        echo "<input type='button' name='".get_string('TSave', 'block_evalcomix')."' value='".
        get_string('TSave', 'block_evalcomix')."' onclick='sendPostAssess(\"totalgrade\",\"uno=1\",\"mainform\",\"".
        $action."\");alert(\"".get_string('alertsave', 'block_evalcomix')."\");'>";
        $type = get_class($this->object);
        if ($type == 'toolargument' && $grade != '') {
            $gradeexploded = explode('/', $grade);
            $score = $gradeexploded[0];
            echo "
                    <div class='eval' id='evalid'>
                        <div style='text-align:right; font-size:1.5em;'>
                            <label for='grade'>".get_string('grade', 'block_evalcomix') .": </label>
                            <select id='grade' name='grade'>
                                <option value='-1'>".get_string('nograde', 'block_evalcomix')."</option><br>
            ";

            for ($i = 100; $i >= 0; --$i) {
                $selected = '';
                if (is_numeric($score) && $score == $i) {
                    $selected = 'selected';
                }
                echo "<option value='$i' $selected>$i</option><br>";
            }
            echo "
                            </select>
                        </div>";
        }

        $this->object->print_tool();

        echo "<input type='button' name='".get_string('TSave', 'block_evalcomix')."' value='".
        get_string('TSave', 'block_evalcomix')."' onclick='sendPostAssess(\"totalgrade\",\"uno=1\",\"mainform\",\"".
        $action."\");alert(\"".get_string('alertsave', 'block_evalcomix')."\");'>";

        echo "<input type='button' onclick=\"javascript:limpiar_mainform()\" value='Reset'>";

        echo "<div style='text-align:right;font-size:1.7em'><span>".get_string('grade', 'block_evalcomix').
        ": </span><span id='totalgrade'>" . $grade . "</span></div>";

        echo '
                    </form>
            </div>
        ';

        if ($saved == 'saved') {
            echo '<script type="text/javascript" language="javascript">alert("'.
            get_string('alertsave', 'block_evalcomix').'");</script>';
        }

        echo '
        <hr>
        <div class="botones">
            <div class="boton" style="margin-right: 1em;">
                <input type="button" name="imprimir" value="'.get_string('TPrint', 'block_evalcomix').'"
                onclick="javascript:
                var ficha = document.getElementById(\'evalid\');
                var ventimp=window.open(\'\',\'popimpr\');ventimp.document.write(ficha.innerHTML);
                ventimp.document.close();ventimp.print();ventimp.close();">
        </div>
        </div>';
        echo '
            <div class="clear"></div>

                <div class="clear"></div>

                </body>

            </html>
        ';
    }

    public function assessment_tool_mixed($root = '', $assessmentid = 0, $idtool = '', $grade = '', $saved = '',
            $tools = array(), $title = '') {
        require('lang/'. $this->language . '/evalcomix.php');
        $action = $root . '/assessment/saveassess.php?ass=' . $assessmentid . '&tool='.$idtool;
        $this->view_assessment_header();
        echo '
                    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
                        <script type="text/javascript" src="'.$root.'/client/javascript/ajax.js"></script>
                        <script>
                            function limpiar_mainform(form) {
                                if (confirm(\'¿Confirma que desea borrar todas las calificaciones asignadas al instrumentos?\'))
                                    for (i=0;i<form.elements.length;i++) {
                                        if (form.elements[i].type == "radio" && form.elements[i].checked == true)
                                          form.elements[i].checked=false;
                                        else if (form.elements[i].type == "textarea") form.elements[i].value = "";
                                }
                            }
                        </script>
                    </head>

                    <body>
                        <div class="clear"></div>
                        <div class="eval" id="evalid">
                        <h2>'.$title.'</h2>
        ';

        $listtool = $this->object->get_tools();
        $countlisttool = count($listtool) - 1;
        $i = 0;
        foreach ($listtool as $tool) {
            $type = get_class($tool);
            $idsingle = '';
            foreach ($tools as $key => $item) {
                $object = $item->object;
                if (get_class($object) == $type  && $object->get_titulo() == $tool->get_titulo()
                        && $object->get_dimension() == $tool->get_dimension()
                        && $object->get_subdimension() == $tool->get_subdimension()
                        && $object->get_valores() == $tool->get_valores()
                        && $object->get_atributo() == $tool->get_atributo()
                        && $object->get_commentatr() == $tool->get_commentatr()) {
                    if ($type != 'toollist') {
                        if ($object->get_valglobal() == $tool->get_valglobal()
                            && $object->get_valtotal() == $tool->get_valtotal()) {
                            if ($type == 'toolrubric') {
                                $getrango1 = $object->get_rango();
                                $getrango2 = $tool->get_rango();
                                list(, $objectrango) = current($getrango1);
                                list(, $toolrango) = current($getrango2);
                                if ($objectrango == $toolrango) {
                                    $idsingle = $key;
                                    break;
                                }
                            } else {
                                $idsingle = $key;
                                break;
                            }
                        }
                    } else {
                        $idsingle = $key;
                        break;
                    }
                }
            }
            if ($idsingle == '') {
                break;
            }
            unset($tools[$idsingle]);
            echo '
                        <form name="form'. $i .'" id="form'. $i .'" method="post" action="'.$action.'">
                            <!-- <input type="hidden" id="cod" name="cod" value="'.$idsingle.'"> -->
                            <input type="hidden" id="cod_form'. $i .'" name="cod_form'. $i .'" value="'.$idsingle.'">
                            <div class="boton" style="margin-right: 1em;">
                            <input type="button" name="imprimir" value="'.get_string('TPrint', 'block_evalcomix').'"
                            onclick="javascript:
                            var ficha = document.getElementById(\'evalid\');
                            var ventimp=window.open(\'\',\'popimpr\');ventimp.document.write(ficha.innerHTML);
                            ventimp.document.close();ventimp.print();ventimp.close();">
                            </div>
                            <!-- <input type="submit" name="submit" value="'.get_string('TSave', 'block_evalcomix').'"> -->
            ';

            echo "<input type='button' name='".get_string('TSave', 'block_evalcomix')."'
            value='".get_string('TSave', 'block_evalcomix')."'
            onclick='sendPostAssess(\"totalgrade\",\"uno=1\",\"form".$i."\",\"".$action."\");alert(\"".
            get_string('alertsave', 'block_evalcomix')."\");'>";
            $globalcomment = 'none';
            if ($i == $countlisttool) {
                $globalcomment = 'globalcomment';
            }
            $tool->print_tool($globalcomment);

            echo "
                            </div>
                            <!-- <input type='submit' name='".get_string('TSave', 'block_evalcomix')."'
                            value='".get_string('TSave', 'block_evalcomix')."'> -->
                            <input type='button' name='".get_string('TSave', 'block_evalcomix')."'
                            value='".get_string('TSave', 'block_evalcomix')."'
                            onclick='sendPostAssess(\"totalgrade\",\"uno=1\",\"form".$i."\",\"".$action."\");
                            alert(\"".get_string('alertsave', 'block_evalcomix')."\");'>
                            <input type='button' onclick=\"javascript:limpiar_mainform(form".$i.")\" value='Reset'>
                            <div class='boton' style='margin-right: 1em;'>
                            <input type='button' name='imprimir' value='".get_string('TPrint', 'block_evalcomix')."'
                            onclick=\"javascript:
                            var ficha = document.getElementById('evalid');
                            var ventimp=window.open('','popimpr');ventimp.document.write(ficha.innerHTML);
                            ventimp.document.close();ventimp.print();ventimp.close();\">
                            </div>

                        </form>

                        </div><br><br><hr>
            ";
            ++$i;
        }
        echo "<div style='text-align:right;font-size:1.7em'><span>".get_string('grade', 'block_evalcomix').
        ": </span><span id='totalgrade'>" . $grade . "</span></div>";

        if ($saved == 'saved') {
            echo '<script type="text/javascript" language="javascript">
            alert("'.get_string('alertsave', 'block_evalcomix').'");</script>';
        }

        echo '
            <div class="clear"></div>

                <div class="clear"></div>

                </body>

            </html>
        ';
    }

    public function view_tool_mixed($root = '', $grade = '', $title = '') {
        require('lang/'. $this->language . '/evalcomix.php');
        $action = '';
        $this->view_assessment_header();
        echo '
                    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
                    <script>
                        function limpiar_mainform(form) {
                            if (confirm(\'¿Confirma que desea borrar todas las calificaciones asignadas al instrumentos?\'))
                                for (i=0;i<form.elements.length;i++) {
                                    if (form.elements[i].type == "radio" && form.elements[i].checked == true)
                                      form.elements[i].checked=false;
                                    else if (form.elements[i].type == "textarea") form.elements[i].value = "";
                            }
                        }
                    </script>
                </head>
                    <body>
                    <div class="clear"></div>
                    <div class="boton" style="margin-right: 1em;">
                        <!-- <input type="button" name="imprimir" value="'.get_string('TPrint', 'block_evalcomix').'"
                        onclick="window.print();"> -->
                        <input type="button" name="imprimir" value="Imprimir" onclick="javascript:
                            var ficha = document.getElementById(\'evalid\');
                            var ventimp=window.open(\'\',\'popimpr\');ventimp.document.write(ficha.innerHTML);
                            ventimp.document.close();ventimp.print();ventimp.close();">
                    </div>
                    <div class="eval" id="evalid">
                    <h2>'.$title.'</h2>
        ';

        $listtool = $this->object->get_tools();
        $countlisttool = count($listtool) - 1;
        $i = 0;
        foreach ($listtool as $tool) {
            echo '
                        <form name="form'. $i .'" method="post" action="'.$action.'">
            ';

            $globalcomment = 'none';
            if ($i == $countlisttool) {
                $globalcomment = 'globalcomment';
            }
            $tool->print_tool($globalcomment);

            echo "
                        </form>

                        </div><br><br><br><hr>
            ";
            ++$i;
        }

        echo "<div style='text-align:right;font-size:1.7em'><span>".get_string('grade', 'block_evalcomix').": " .
        $grade . "</span></div>";

        echo '<div class="botones">
                    <div class="boton" style="margin-right: 1em;">
                        <!-- <input type="button" name="imprimir" value="'.get_string('TPrint', 'block_evalcomix').'"
                        onclick="window.print();"> -->
                        <input type="button" name="imprimir" value="Imprimir" onclick="javascript:
                            var ficha = document.getElementById(\'evalid\');
                            var ventimp=window.open(\'\',\'popimpr\');ventimp.document.write(ficha.innerHTML);
                            ventimp.document.close();ventimp.print();ventimp.close();">
                    </div>
                </div>';

        echo '
            <div class="clear"></div>

                <div class="clear"></div>

                </body>

            </html>
        ';
    }

    public function save($id = '') {
        global $CFG;
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
        $xml = $this->object->export((array('mixed' => '1', 'id' => $id)));

        if ($result = block_evalcomix_webservice_client::post_ws_xml_tools(array('toolxml' => $xml, 'id' => $id))) {
            return array('xml' => $xml);
        }
        return false;
    }

    public function save_competencies($id, $courseid) {
        global $DB;
        $subdimensionsid = $this->object->get_subdimensionid_from_xml($id);
        $competency = $this->get_competency();
        $outcome = $this->get_outcome();

        $hashsavedcompetencies = array();
        if ($savedcompetencies = $DB->get_records('block_evalcomix_competencies', array('courseid' => $courseid))) {
            foreach ($savedcompetencies as $c) {
                $hashsavedcompetencies[$c->idnumber] = $c;
            }
        }

        if ($tool = $DB->get_record('block_evalcomix_tools', array('idtool' => $id))) {
            $hashsavedsubdimensions = array();
            if ($savedsubdimensions = $DB->get_records('block_evalcomix_subdimension', array('courseid' => $courseid,
                    'toolid' => $tool->id))) {
                foreach ($savedsubdimensions as $item) {
                    $subid = $item->subdimensionid;
                    $cid = $item->competencyid;
                    $hashsavedsubdimensions[$subid][$cid] = $item;
                }
            }

            foreach ($competency as $id => $datatool) {
                foreach ($datatool as $dim => $datadim) {
                    foreach ($datadim as $subdim => $datasubdim) {
                        if (isset($subdimensionsid[$id][$dim][$subdim])) {
                            $subdimensionid = $subdimensionsid[$id][$dim][$subdim];
                            foreach ($datasubdim as $keycomp => $datacomp) {
                                if (isset($hashsavedcompetencies[$keycomp])) {
                                    $comp = $hashsavedcompetencies[$keycomp];
                                    $compid = $comp->id;
                                    if (!isset($hashsavedsubdimensions[$subdimensionid][$compid])) {
                                        $DB->insert_record('block_evalcomix_subdimension', array('courseid' => $courseid,
                                        'toolid' => $tool->id, 'subdimensionid' => $subdimensionid,
                                        'competencyid' => $compid));
                                    } else {
                                        unset($hashsavedsubdimensions[$subdimensionid][$compid]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            foreach ($outcome as $id => $datatool) {
                foreach ($datatool as $dim => $datadim) {
                    foreach ($datadim as $subdim => $datasubdim) {
                        if (isset($subdimensionsid[$id][$dim][$subdim])) {
                            $subdimensionid = $subdimensionsid[$id][$dim][$subdim];
                            foreach ($datasubdim as $keycomp => $datacomp) {
                                if (isset($hashsavedcompetencies[$keycomp])) {
                                    $comp = $hashsavedcompetencies[$keycomp];
                                    $compid = $comp->id;
                                    if (!isset($hashsavedsubdimensions[$subdimensionid][$compid])) {
                                        $DB->insert_record('block_evalcomix_subdimension', array('courseid' => $courseid,
                                        'toolid' => $tool->id, 'subdimensionid' => $subdimensionid,
                                        'competencyid' => $compid));
                                    } else {
                                        unset($hashsavedsubdimensions[$subdimensionid][$compid]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (!empty($hashsavedsubdimensions)) {
                foreach ($hashsavedsubdimensions as $subid => $item1) {
                    foreach ($item1 as $cid => $item2) {
                        $DB->delete_records('block_evalcomix_subdimension', array('id' => $item2->id));
                    }
                }
            }
        }
    }

    public static function get_course_competencies() {
        global $DB, $COURSE;

        return $DB->get_records('block_evalcomix_competencies', array('courseid' => $COURSE->id, 'outcome' => 0));
    }

    public static function get_course_outcomes() {
        global $DB, $COURSE;

        return $DB->get_records('block_evalcomix_competencies', array('courseid' => $COURSE->id, 'outcome' => 1));
    }

    public static function get_course_comptypes() {
        global $DB, $COURSE;

        return $DB->get_records('block_evalcomix_comptype', array('courseid' => $COURSE->id));
    }

    public static function create_competency($idnumber, $shortname, $description, $comptype) {
        global $DB, $COURSE;

        if (isset($idnumber) && isset($shortname)) {
            if (!$DB->get_record('block_evalcomix_competencies', array('courseid' => $COURSE->id, 'idnumber' => $idnumber,
                    'outcome' => 0))) {
                $params = array();
                $params['courseid'] = $COURSE->id;
                $params['idnumber'] = $idnumber;
                $params['shortname'] = $shortname;
                $params['outcome'] = 0;
                $params['description'] = (string)$description;
                $params['timecreated'] = time();
                if ($ct = $DB->get_record('block_evalcomix_comptype', array('courseid' => $COURSE->id,
                        'shortname' => $comptype))) {
                    $params['typeid'] = $ct->id;
                }
                $id = $DB->insert_record('block_evalcomix_competencies', $params);
                return $id;
            }
        }
        return 0;
    }

    public static function create_outcome($idnumber, $shortname, $description) {
        global $DB, $COURSE;

        if (isset($idnumber) && isset($shortname)) {
            if (!$DB->get_record('block_evalcomix_competencies', array('courseid' => $COURSE->id, 'idnumber' => $idnumber,
                    'outcome' => 1))) {
                $params = array();
                $params['courseid'] = $COURSE->id;
                $params['idnumber'] = $idnumber;
                $params['shortname'] = $shortname;
                $params['outcome'] = 1;
                $params['description'] = (string)$description;
                $params['timecreated'] = time();
                $id = $DB->insert_record('block_evalcomix_competencies', $params);
                return $id;
            }
        }
        return 0;
    }

    public function set_competency_from_origen($id, $toolid, $subdimensionsid, $outcome = 0) {
        global $DB, $COURSE;
        $result = array();

        $sql = '
        SELECT bes.*, bec.idnumber, bec.shortname
        FROM {block_evalcomix_subdimension} bes, {block_evalcomix_tools} bet, {block_evalcomix_competencies} bec
        WHERE bes.toolid = bet.id
            AND bes.competencyid = bec.id
            AND bec.outcome = :outcome
            AND bes.courseid = :courseid
            AND bet.idtool = :toolid
        ';
        if ($subcomp = $DB->get_records_sql($sql, array('outcome' => $outcome, 'courseid' => $COURSE->id,
                'toolid' => $toolid))) {
            $hashsubdimensions = array();
            foreach ($subcomp as $data) {
                $subid = $data->subdimensionid;
                $cid = $data->idnumber;
                $hashsubdimensions[$subid][$cid] = $data;
            }

            if (isset($subdimensionsid[$id])) {
                foreach ($subdimensionsid[$id] as $dim => $item1) {
                    foreach ($item1 as $subdim => $item2) {
                        if (isset($hashsubdimensions[$item2])) {
                            foreach ($hashsubdimensions[$item2] as $cid => $item3) {
                                $result[$id][$dim][$subdim][$cid] = $item3->shortname;
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function edit_competencies_in_label($id, $dimkey, $subdimkey, $label,
            $previouscompetencystring) {
        $latercompetencystring = $this->get_competency_string($id, $dimkey, $subdimkey);
        if ($label === 'subdimension') {
            $subdimensions = $this->get_subdimension($id);
            $subdimensionnamemainform = optional_param('subdimension'.$id.'_'.$dimkey.'_'.$subdimkey, '', PARAM_RAW);
            $subdimensionname = $subdimensions[$dimkey][$subdimkey]['nombre'];
            if ($subdimensionnamemainform !== '') {
                $pos = strpos($subdimensionname, $subdimensionnamemainform);
                $subdimensionname = ($pos === false) ? $subdimensionnamemainform : $subdimensionname;
            }
            $subdimensionname = (!empty($subdimensionname)) ? $subdimensionname : $subdimensions[$dimkey][$subdimkey]['nombre'];

            if (empty($previouscompetencystring)) {
                $subdimensions[$dimkey][$subdimkey]['nombre'] = $subdimensionname . ' ' . $latercompetencystring;
            } else {
                $pos = strpos($subdimensionname, $previouscompetencystring);
                if ($pos === false) {
                    $subdimensionname = $this->competency_strpos($latercompetencystring, $subdimensionname);
                    $subdimensions[$dimkey][$subdimkey]['nombre'] = $subdimensionname . ' ' . $latercompetencystring;
                } else {
                    $subdimensions[$dimkey][$subdimkey]['nombre'] = str_replace($previouscompetencystring,
                    $latercompetencystring, $subdimensionname);
                }
            }

            $this->set_subdimension($subdimensions, $id);
        } else if ($label = 'title') {
            $titlename = $this->get_titulo($id);
            $titlemainform = optional_param('titulo'.$id, '', PARAM_RAW);
            if ($titlemainform !== '') {
                $pos = strpos($titlename, $titlemainform);
                $titlename = ($pos === false) ? $titlemainform : $titlename;
            }

            if (empty($previouscompetencystring)) {
                $titlename = $titlename . ' ' . $latercompetencystring;
            } else {
                $pos = strpos($titlename, $previouscompetencystring);
                if ($pos === false) {
                    $titlename = $this->competency_strpos($latercompetencystring, $titlename);
                    $titlename = $titlename . ' ' . $latercompetencystring;
                } else {
                    $titlename = str_replace($previouscompetencystring,
                    $latercompetencystring, $titlename);
                }
            }

            $this->set_titulo($titlename, $id);
        }
    }

    public function competency_strpos($haystack, $needle) {
        $mainhaystack = $this->get_content_in_brackets($haystack);
        $mainneedle = $this->get_content_in_brackets($needle);

        $competenciesstring = array_pop($mainhaystack);
        $previouscompetenciesstring = array_pop($mainneedle);

        $competencies = explode(', ', $competenciesstring);
        $previouscompetencies = explode(', ', $previouscompetenciesstring);

        $flag = false;
        $result = '';
        foreach ($previouscompetencies as $pc) {
            if (in_array($pc, $competencies)) {
                $flag = true;
            }
        }

        if ($flag === false) {
            return $needle;
        }
        return str_replace('('.$previouscompetenciesstring.')', '', $needle);

    }

    public function get_content_in_brackets($string) {
        $length = strlen($string);
        $result = array();
        $content = '';
        $char = '';
        for ($i = 0; $i < $length; $i++) {
            if ($string[$i] == '(') {
                $char = '(';
                continue;
            } else if ($string[$i] == ')') {
                $char = ')';
            }

            if ($char == '(') {
                $content .= $string[$i];
            } else if ($char == ')') {
                $result[] = $content;
                $content = '';
                $char = '';
            }
        }
        return $result;
    }
}
