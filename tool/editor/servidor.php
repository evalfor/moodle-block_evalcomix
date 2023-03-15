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
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
require_once('../../../../config.php');
$courseid = required_param('courseid', PARAM_INT);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
require_course_login($course);
$context = context_course::instance($courseid);
require_capability('moodle/grade:viewhidden', $context);
require_once('controller.php');

$postcleaned = (array)data_submitted();
$postcleaned = clean_param_array($postcleaned, PARAM_RAW);

// Print option.
$print = optional_param('op', null, PARAM_ALPHA);
if (isset($print)) {
    $id = null;
    if (isset($postcleaned['id'])) {
        $id = $postcleaned['id'];
    }

    $tool->display_header(array('courseid' => $courseid));
    $tool->view_tool('..');
    $tool->display_footer();
} else if (isset($_FILES['Filetype']['name'])) { // After selecting the import file.
    $tool->display_header(array('courseid' => $courseid));
    $namefile = 'Filetype';
    $extension = explode(".", $_FILES[$namefile]['name']);
    $num = count($extension) - 1;
    if (!$_FILES[$namefile]['name'] || ($extension[$num] != "evx")) {
        print '<br style="color:#f00">' . $string['ErrorFormato'] . '<br>
        <input type="button" style="width:5em" value="Volver" name="vover" onclick=\'javascript:history.back(-1)\'>';
        exit;
    }

    $xmlstring = file_get_contents($_FILES[$namefile]['tmp_name']);
    if (!($xml = simplexml_load_string($xmlstring))) {
        echo $string['ErrorAcceso'];
        exit;
    }
    $tool->import($xml);
    $tool->display_body(array('courseid' => $courseid));
    $tool->display_footer();
} else if (isset($postcleaned['mix']) && $postcleaned['mix'] != '') {
    $id = $postcleaned['mix'];
    $instrument = $tool->get_tool($id);

    if (isset($postcleaned['addDim']) || isset($postcleaned['addtool'.$id])) {
        $instrument->display_body($postcleaned, $id);
    } else if (isset($postcleaned['addSubDim'])) {
        $dim = $postcleaned['addSubDim'];
        $id = $postcleaned['id'];
        $instrument->display_dimension($dim, $postcleaned, null, $id);
    } else if (isset($postcleaned['addAtr'])) {
        $valor = explode('_', $postcleaned['addAtr']);
        $dim = $valor[0];
        $subdim = $valor[1];
        $id = $postcleaned['id'];
        $instrument->display_subdimension($dim, $subdim, $postcleaned, null, $id);
    }
    if (isset($postcleaned['moveDim']) || isset($postcleaned['moveTool'])) {
        $instrument->display_body($postcleaned, $id);
    } else if (isset($postcleaned['moveSub'])) {
        $dim = $postcleaned['moveSub'];
        $id = $postcleaned['id'];
        $instrument->display_dimension($dim, $postcleaned, null, $id);
    } else if (isset($postcleaned['moveAtr'])) {
        $valor = explode('_', $postcleaned['moveAtr']);
        $dim = $valor[0];
        $subdim = $valor[1];
        $id = $postcleaned['id'];
        $instrument->display_subdimension($dim, $subdim, $postcleaned, null, $id);
    } else if (isset($postcleaned['modalAddComp']) && isset($postcleaned['modalCompSel'])) {
        $value = explode('_', $postcleaned['modalAddComp']);
        $label = (!empty($postcleaned['modaltitle'.$id])) ? 'title' : 'subdimension';
        $instrument->display_competencies_modal($id, $value[0], $value[1], $postcleaned['mix'], $label);
    } else if (isset($postcleaned['modalAddOut']) && isset($postcleaned['modalOutSel'])) {
        $value = explode('_', $postcleaned['modalAddOut']);
        $instrument->display_competencies_modal($id, $value[0], $value[1], $postcleaned['mix']);
    } else if (isset($postcleaned['modalDelComp'])) {
        $value = explode('_', $postcleaned['modalDelComp']);
        $instrument->display_competencies_modal($id, $value[0], $value[1], $postcleaned['mix']);
    } else if (isset($postcleaned['modalDelCompSub'])) {
        $value = explode('_', $postcleaned['modalDelCompSub']);
        $id = $postcleaned['id'];
        $instrument->display_subdimension($value[0], $value[1], $postcleaned, $id);
    } else if (isset($postcleaned['modalDelOut'])) {
        $value = explode('_', $postcleaned['modalDelOut']);
        $instrument->display_competencies_modal($id, $value[0], $value[1], $postcleaned['mix']);
    } else if (isset($postcleaned['modalDelOutSub'])) {
        $value = explode('_', $postcleaned['modalDelOutSub']);
        $id = $postcleaned['id'];
        $instrument->display_subdimension($value[0], $value[1], $postcleaned, $id, $postcleaned['mix']);
    } else if (isset($postcleaned['modalclose'])) {
        $value = explode('_', $postcleaned['modalclose']);
        $id = $postcleaned['id'];
        $instrument->display_subdimension($value[0], $value[1], $postcleaned, $id, $postcleaned['mix']);
    }
} else {
    if (isset($postcleaned['id'])) {
        $id = $postcleaned['id'];
    }
    if (isset($postcleaned['save']) && (isset($postcleaned['addtool'.$id]) || isset($postcleaned['addDim'])
            || isset($postcleaned['addtool']))) {
        $tool->display_header($postcleaned);
        $tool->display_body($postcleaned);
        $id = $SESSION->id;
        if (isset($id)) {
            $get = '&id='.$id;
        }
        $componentid = '';
        if (isset($postcleaned['id'])) {
            $componentid = $postcleaned['id'];
        }

        $error = false;
        if (!isset($postcleaned['titulo'.$componentid]) || trim($postcleaned['titulo'.$componentid]) == '') {
            echo "<script type='text/javascript'>alert('". $string['ErrorSaveTitle'] ."');</script>";
            echo "<script type='text/javascript'>location.href = 'generator.php';</script>";$tool->display_footer();
            $error = true;
        }
        if (isset($postcleaned['seltool'])) {
            $numtool = $tool->get_numtool();
            if ($numtool == 0) {
                echo "<script type='text/javascript'>alert('". $string['ErrorSaveTools'] ."');</script>";
                echo "<script type='text/javascript'>location.href = 'generator.php';</script>";$tool->display_footer();
                $error = true;
            }
        }

        if ($error == false) {
            $xmlstring = $tool->export();
            $xml = simplexml_load_string($xmlstring);
            $toolaux = new block_evalcomix_editor_tool('es_utf8', '', '', '', '', '', '', '', '', '', '', ''
            , '', '', '', '', '', '', '', '', '');
            $toolaux->import($xml);
            try {
                $resultparams = $toolaux->save($id);
                if ($tool->type == 'mixta') {
                    $toolauxplantillasid = $toolaux->get_plantillasid();
                    $toolauxplantillas = $toolaux->get_tools();
                    $plantillasid = array();

                    if (isset($toolplantillas) && isset($toolauxplantillasid)) {
                        foreach ($toolplantillas as $pid => $plantilla) {
                            if ($key1 = key($toolauxplantillasid) && $data1 = current($toolauxplantillasid)) {
                                $plantillasid[$pid] = $data1;
                                next($toolauxplantillasid);
                            }

                            if ($key2 = key($toolauxplantillas) && $plantillaaux2 = current($toolauxplantillas)) {
                                block_evalcomix_response_tool($plantilla, $plantillaaux2);
                                next($toolauxplantillas);
                            }
                        }
                    }
                    $tool->set_plantillasid($plantillasid, '');
                    $tool->save_competencies($id, $courseid);
                } else {
                    block_evalcomix_response_tool($tool, $toolaux);
                    $tool->save_competencies($id, $courseid);
                }
                echo "<script type='text/javascript'>location.href = 'generator.php?courseid=".
                $postcleaned['courseid']."&save=1';</script>";$tool->display_footer();
            } catch (Exception $e) {
                var_dump($e);
                echo "<script type='text/javascript'>alert('There is a problem. Tool is not saved');</script>";
            }
        }

        $tool->display_footer();

    } else if (isset($postcleaned['addtool'.$id]) || isset($postcleaned['addtool']) || isset($postcleaned['moveTool'])) {
        $tool->display_header($postcleaned);
        $tool->display_body($postcleaned);
        $tool->display_footer();
    } else if (isset($postcleaned['addDim'])) {
        $tool->display_body($postcleaned);
    } else if (isset($postcleaned['addSubDim'])) {
        $dim = $postcleaned['addSubDim'];
        $id = $postcleaned['id'];
        $tool->display_dimension($dim, $postcleaned, $id);
    } else if (isset($postcleaned['addAtr'])) {
        $valor = explode('_', $postcleaned['addAtr']);
        $dim = $valor[0];
        $subdim = $valor[1];
        $id = $postcleaned['id'];
        $tool->display_subdimension($dim, $subdim, $postcleaned, $id);
    } else if (isset($postcleaned['moveAtr'])) {
        $valor = explode('_', $postcleaned['moveAtr']);
        $dim = $valor[0];
        $subdim = $valor[1];
        $id = $postcleaned['id'];
        $tool->display_subdimension($dim, $subdim, $postcleaned, $id);
    } else if (isset($postcleaned['moveSub'])) {
        $dim = $postcleaned['moveSub'];
        $id = $postcleaned['id'];
        $tool->display_dimension($dim, $postcleaned, $id);
    } else if (isset($postcleaned['moveDim'])) {
        $tool->display_body($postcleaned);
    } else if (isset($postcleaned['addComp'])) {
        $valor = explode('_', $postcleaned['addComp']);
        $dim = $valor[0];
        $subdim = $valor[1];
        $id = $postcleaned['id'];
        $tool->display_subdimension($dim, $subdim, $postcleaned, $id);
    } else if (isset($postcleaned['modalAddComp']) && isset($postcleaned['modalCompSel']) && isset($postcleaned['mix'])) {
        $value = explode('_', $postcleaned['modalAddComp']);
        $label = (!empty($postcleaned['modaltitle'.$id])) ? 'title' : 'subdimension';
        $tool->display_competencies_modal($id, $value[0], $value[1], $postcleaned['mix'], $label);
    } else if (isset($postcleaned['modalAddOut']) && isset($postcleaned['modalOutSel']) && isset($postcleaned['mix'])) {
        $value = explode('_', $postcleaned['modalAddOut']);
        $label = (!empty($postcleaned['modaltitle'.$id])) ? 'title' : 'subdimension';
        $tool->display_competencies_modal($id, $value[0], $value[1], $postcleaned['mix'], $label);
    } else if (isset($postcleaned['modalDelComp']) && isset($postcleaned['mix'])) {
        $value = explode('_', $postcleaned['modalDelComp']);
        $label = (!empty($postcleaned['modaltitle'.$id])) ? 'title' : 'subdimension';
        $tool->display_competencies_modal($id, $value[0], $value[1], $postcleaned['mix'], $label);
    } else if (isset($postcleaned['modalDelCompSub']) && isset($postcleaned['mix'])) {
        $value = explode('_', $postcleaned['modalDelCompSub']);
        $id = $postcleaned['id'];
        $tool->display_subdimension($value[0], $value[1], $postcleaned, $id, $postcleaned['mix']);
    } else if (isset($postcleaned['modalDelOut']) && isset($postcleaned['mix'])) {
        $value = explode('_', $postcleaned['modalDelOut']);
        $label = (!empty($postcleaned['modaltitle'.$id])) ? 'title' : 'subdimension';
        $tool->display_competencies_modal($id, $value[0], $value[1], $postcleaned['mix'], $label);
    } else if (isset($postcleaned['modalDelOutSub']) && isset($postcleaned['mix'])) {
        $value = explode('_', $postcleaned['modalDelOutSub']);
        $id = $postcleaned['id'];
        $label = (!empty($postcleaned['modaltitle'.$id])) ? 'title' : 'subdimension';
        $tool->display_subdimension($value[0], $value[1], $postcleaned, $id);
    } else if (isset($postcleaned['modalclose'])) {
        $tool->display_body($postcleaned);
    } else if (isset($postcleaned['modalcreatecomp'])) {
        $value = explode('_', $postcleaned['modalcreatecomp']);
        $label = (!empty($postcleaned['modaltitle'.$id])) ? 'title' : 'subdimension';
        $tool->display_competencies_modal($id, $value[0], $value[1], $postcleaned['mix'], $label);
    } else if (isset($postcleaned['modalcreateout'])) {
        $value = explode('_', $postcleaned['modalcreateout']);
        $label = (!empty($postcleaned['modaltitle'.$id])) ? 'title' : 'subdimension';
        $tool->display_competencies_modal($id, $value[0], $value[1], $postcleaned['mix'], $label);
    }
}

$toolobj = serialize($tool);
$SESSION->tool = $toolobj;
$SESSION->secuencia = $secuencia;

function block_evalcomix_response_tool($tool, $toolaux) {
    $type = '';
    if (!isset($tool->type)) {
        $classtype = get_class($tool);
        switch($classtype) {
            case 'toollist':
                $type = 'lista';
                break;
            case 'toolscale':
                $type = 'escala';
                break;
            case 'toollistscale':
                $type = 'listaescala';
                break;
            case 'toolrubric':
                $type = 'rubrica';
                break;
            case 'tooldifferential':
                $type = 'diferencial';
                break;
            case 'toolargument':
                $type = 'argumentario';
                break;
        }
    } else {
        $type = $tool->type;
    }

    $toolauxdimensionsid = $toolaux->get_dimensionsid();
    $toolauxsubdimensionsid = $toolaux->get_subdimensionsid();
    $toolauxatributosid = $toolaux->get_atributosid();
    $toolauxvaloresid = $toolaux->get_valoresid();
    $toolauxvalorestotalesid = $toolaux->get_valorestotalesid();
    $toolauxvaloreslistaid = array();
    $toolauxrangoid = array();
    $toolauxdescriptionsid = array();
    $toolauxatributosposid = array();

    switch($type) {
        case 'rubrica':{
            $toolauxrangoid = $toolaux->get_rangoid();
            $toolauxdescriptionsid = $toolaux->get_descriptionsid();
        } break;
        case 'listaescala':{
            $toolauxvaloreslistaid = $toolaux->get_valoreslistaid();
        } break;
        case 'diferencial':{
            $toolauxatributosposid = $toolaux->get_atributosposid();
        }
    }

    $tooldimensions = $tool->get_dimension('');
    $toolsubdimensions = $tool->get_subdimension('');
    $toolatributos = $tool->get_atributo('');
    $toolvalores = $tool->get_valores('');
    $toolvalorestotales = $tool->get_valorestotal('');
    $toolrango = array();
    $tooldescription = array();
    $toolvaloreslista = array();
    $toolatributospos = array();
    switch($type) {
        case 'rubrica':{
            $toolrango = $tool->get_rango('');
            $tooldescription = $tool->get_description('');
        }break;
        case 'listaescala':{
            $toolvaloreslista = $tool->get_valoreslista();
        }break;
        case 'diferencial':{
            $toolatributospos = $tool->get_atributopos('');
        }
    }

    $dimensionsid = array();
    $subdimensionsid = array();
    $atributosid = array();
    $valoreslistaid = array();
    $valoresid = array();
    $rangoid = array();
    $descriptionsid = array();
    $atributosposid = array();
    if (isset($toolauxdimensionsid) && isset($toolauxsubdimensionsid) && isset($toolauxatributosid)) {
        foreach ($tooldimensions as $dim => $valuedimensions) {
            $key2 = null;
            if ($data2 = current($toolauxdimensionsid)) {
                $key2 = key($toolauxdimensionsid);
                $dimensionsid[$dim] = $data2;
                next($toolauxdimensionsid);
            }

            if (isset($toolvalores[$dim])) {
                foreach ($toolvalores[$dim] as $grado => $values) {
                    if ($key2 !== null && isset($toolauxvaloresid[$key2]) && $datav = current($toolauxvaloresid[$key2])) {
                        $keyv = key($toolauxvaloresid[$key2]);
                        $valoresid[$dim][$grado] = $datav;
                        next($toolauxvaloresid[$key2]);
                    }
                    if ($type == 'rubrica') {
                        foreach ($toolrango[$dim][$grado] as $rgrado => $rango) {
                            if ($key2 !== null && $datar = current($toolauxrangoid[$key2][$keyv])) {
                                $keyr = key($toolauxrangoid[$key2][$keyv]);
                                $rangoid[$dim][$grado][$rgrado] = $datar;
                                next($toolauxrangoid[$key2][$keyv]);
                            }
                        }
                    }
                }
            }

            if ($type == 'listaescala') {
                foreach ($toolvaloreslista[$dim] as $grado => $values) {
                    if ($key2 !== null && $datavl = current($toolauxvaloreslistaid[$key2])) {
                        $keyvl = key($toolauxvaloreslistaid[$key2]);
                        $valoreslistaid[$dim][$grado] = $datavl;
                        next($toolauxvaloreslistaid[$key2]);
                    }
                }
            }
            $key3 = null;
            foreach ($toolsubdimensions[$dim] as $subdim => $valuesubdimensions) {
                if ($key2 !== null && $data3 = current($toolauxsubdimensionsid[$key2])) {
                    $key3 = key($toolauxsubdimensionsid[$key2]);
                    $subdimensionsid[$dim][$subdim] = $data3;
                    next($toolauxsubdimensionsid[$key2]);
                }
                $key4 = null;
                foreach ($toolatributos[$dim][$subdim] as $atrib => $valueattributes) {
                    if ($key2 !== null && $key3 !== null && $data4 = current($toolauxatributosid[$key2][$key3])) {
                        $key4 = key($toolauxatributosid[$key2][$key3]);
                        $atributosid[$dim][$subdim][$atrib] = $data4;
                        next($toolauxatributosid[$key2][$key3]);
                    }

                    if ($type == 'rubrica') {
                        foreach ($tooldescription[$dim][$subdim][$atrib] as $gradod => $valuedescription) {
                            if ($key2 !== null && $key3 !== null && $key4 !== null
                                    && $data5 = current($toolauxdescriptionsid[$key2][$key3][$key4])) {
                                $key5 = key($toolauxdescriptionsid[$key2][$key3][$key4]);
                                $descriptionsid[$dim][$subdim][$atrib][$gradod] = $data5;
                                next($toolauxdescriptionsid[$key2][$key3][$key4]);
                            }
                        }
                    }
                }
                if ($type == 'diferencial') {
                    foreach ($toolatributospos[$dim][$subdim] as $atrib => $valueattributes) {
                        if ($key2 !== null && $key3 !== null && $data6 = current($toolauxatributosposid[$key2][$key3])) {
                            $key6 = key($toolauxatributosposid[$key2][$key3]);
                            $atributosposid[$dim][$subdim][$atrib] = $data6;
                            next($toolauxatributosposid[$key2][$key3]);
                        }
                    }
                }
            }
        }

        $tool->set_dimensionsid($dimensionsid, '');
        $tool->set_valoresid($valoresid, '');
        $tool->set_subdimensionsid($subdimensionsid, '');
        $tool->set_atributosid($atributosid, '');
        if ($type == 'listaescala') {
            $tool->set_valoreslistaid($valoreslistaid, '');
        }
        if ($type == 'rubrica') {
            $tool->set_rangoid($rangoid, '');
            $tool->set_descriptionsid($descriptionsid, '');
        }
        if ($type == 'diferencial') {
            $tool->set_atributosposid($atributosposid, '');
        }
    }
    $valorestotalesid = array();
    if (!empty($toolauxvalorestotalesid)) {
        foreach ($toolvalorestotales as $grade => $value) {
            if ($key7 = key($toolauxvalorestotalesid)
                    && $data7 = current($toolauxvalorestotalesid)) {
                $valorestotalesid[$grade] = $data7;
                next($toolauxvalorestotalesid);
            }
        }
        $tool->set_valorestotalesid($valorestotalesid, '');
    }
}
