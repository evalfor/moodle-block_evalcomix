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
global $CFG, $SESSION;

require_once('tool.php');
require_once('toolscale.php');
require_once('toollist.php');

if (!isset($SESSION->tool)) {
    require_once('inicio.php');
}

$toolobj = $SESSION->tool;
$secuencia = $SESSION->secuencia;
$tool = unserialize($toolobj);

$op = optional_param('op', '', PARAM_ALPHA);
$courseid = required_param('courseid', PARAM_INT);
switch($op) {
    case 'export':{
        $xml1 = $tool->export();
        if (isset($xml1)) {
            $filename = $CFG->tempdir. '/evalcomix_file-'.microtime().'.evx';
            $fp = fopen($filename, 'wb');
            fwrite($fp, $xml1);
            header("Location: download.php?fic=".$filename.'&courseid='.$courseid);
        }
    }break;
    case 'import':{
        $tool->display_dialog();
        exit;
    }break;
    case 'view':{
        if (!isset($id)) {
            $id = null;
        }
        $tool->set_view('view', $id);
    }break;
    case 'design':{
        if (!isset($id)) {
            $id = null;
        }
        $tool->set_view('design', $id);
    }break;
}

if ($secuencia < 100) {
    $secuencia = 100;
}

$id = optional_param('id', '', PARAM_ALPHANUM);
$postnumdim = optional_param('numdimensiones', null, PARAM_INT);
$postadddim = optional_param('addDim', null, PARAM_ALPHANUM);
$postsubdim = optional_param('addSubDim', null, PARAM_INT);
$addim = optional_param('ad', null, PARAM_INT);
$deldim = optional_param('dd', null, PARAM_INT);
$postaddatr = optional_param('addAtr', null, PARAM_RAW);
$postaddatrib = optional_param('at', null, PARAM_INT);
$postdelatrib = optional_param('dt', null, PARAM_INT);
$postnumvalortotal = optional_param('numvalores'.$id, null, PARAM_INT);
$postvalortotal = optional_param('valtotal'.$id, null, PARAM_ALPHANUM);
$postmoveatr = optional_param('moveAtr', null, PARAM_RAW);
$postupatr = optional_param('aUp', null, PARAM_INT);
$postdownatr = optional_param('aDown', null, PARAM_INT);
$postmovesub = optional_param('moveSub', null, PARAM_RAW);
$postupsub = optional_param('sUp', null, PARAM_INT);
$postdownsub = optional_param('sDown', null, PARAM_INT);
$postmovedim = optional_param('moveDim', null, PARAM_RAW);
$postupdim = optional_param('dUp', null, PARAM_INT);
$postdowndim = optional_param('dDown', null, PARAM_INT);
$postmovetool = optional_param('moveTool', null, PARAM_RAW);
$postuptool = optional_param('tUp', null, PARAM_INT);
$postdowntool = optional_param('tDown', null, PARAM_INT);
$posttypetool = optional_param('addtool'.$id, null, PARAM_RAW);
$postsaved = optional_param('save', null, PARAM_RAW);
$postcomatr = optional_param('comAtr', null, PARAM_RAW);
$postmodaladdcomp = optional_param('modalAddComp', null, PARAM_RAW);
$postmodalcompsel = optional_param('modalCompSel', null, PARAM_RAW);
$postmodaladdout = optional_param('modalAddOut', null, PARAM_RAW);
$postmodaloutsel = optional_param('modalOutSel', null, PARAM_RAW);
$postmodaldelcomp = optional_param('modalDelComp', null, PARAM_RAW);
$postmodaldelcompsub = optional_param('modalDelCompSub', null, PARAM_RAW);
$postmodaldelout = optional_param('modalDelOut', null, PARAM_RAW);
$postmodaldeloutsub = optional_param('modalDelOutSub', null, PARAM_RAW);
$posttitle = optional_param('modaltitle'.$id, 0, PARAM_INT);
$postmodalclose = optional_param('modalclose', null, PARAM_RAW);
$postmodalclosevoid = optional_param('modalclosevoid', null, PARAM_RAW);
$postmodalcreatecomp = optional_param('modalcreatecomp', null, PARAM_RAW);
$postmodalcreateout = optional_param('modalcreateout', null, PARAM_RAW);
$postmodalidnumber = optional_param('modalidnumber', null, PARAM_RAW);
$postmodalshortname = optional_param('modalshortname', null, PARAM_RAW);
$postmodaldescription = optional_param('modaldescription', null, PARAM_RAW);
$postmodalcomptype = optional_param('modalcomptype', null, PARAM_RAW);

// Determination and handling of events.
// Add/Remove tool event.
if (isset($posttypetool)) {
    $posttypetool = optional_param('addtool'.$id, null, PARAM_RAW);
    $postdeletetool = optional_param('dt', null, PARAM_INT);
    $postaddtool = optional_param('at', null, PARAM_INT);
    $posttoolpor = optional_param('toolpor_'.$id, null, PARAM_INT); // Tool percentage.
    $postitemmod = optional_param('sumpor', null, PARAM_RAW);
    $postnopor = optional_param('nopor', null, PARAM_RAW);

    if (isset($postdeletetool)) {
        $tool->remove($id);
    } else if (isset($postaddtool) && $postaddtool != '') {
        $tool->add($posttypetool, $id);
    }

    // Dimension percentage.
    $toolpor = $tool->get_toolpor();

    if ((!isset($postnopor) || $postnopor == '') && isset($posttoolpor) && is_numeric($posttoolpor)
            && $posttoolpor >= 0 && $posttoolpor <= 100 && !isset($postdeletetool)) {
        $portool = $posttoolpor;
        $index = $id;
        $numtool = $tool->get_numtool();
        $porcentage = $portool;
        if ($numtool > 1) {
            $porcentage = floor((100 - $portool) / ($numtool - 1));
        }

        // Set of percentages entered by the user and to try to keep.
        // Id-id-id-.
        $aux = explode('-', $postitemmod);
        $iditemmod = array_unique($aux);
        array_pop($iditemmod);

        $sumamod = 0;
        $nummod = 0;
        $poninput = array();
        // Indicates whether the user who has commanded has pressed a button related to any of the values received or not.
        $samebutton = 1;
        foreach ($iditemmod as $key => $cod) {
            $postpor = optional_param($cod, null, PARAM_RAW);
            if (isset($postpor)) {
                $nummod++;
                $sumamod += $postpor;
                $aux = explode('_', $cod);
                $poninput[$aux[1]] = $postpor;
                if ($aux[1] == $index) {
                    $samebutton = 0;
                }
            }
        }
        if ($samebutton) {
            $nummod++;
            $poninput[$index] = $portool;
            $sumamod += $portool;
        }

        $state = 0;
        if ($nummod == $numtool && $sumamod != 100) {
            $state = 0;
        } else if ($nummod != $numtool && $sumamod > 100 ) {
            $state = 0;
        } else if ($nummod == $numtool && $sumamod == 100) {
            $state = 2;
        } else {
            $state = 1;
        }

        if ($state == 0) {
            // Percentage to be distributed by the rest of the attributes.
            $porcentage = $portool;
            if ($numtool > 1) {
                $porcentage = floor((100 - $portool) / ($numtool - 1));
                foreach ($toolpor as $key => $value) {
                    if ((string)$key != (string)$index) {
                        $toolpor[$key] = $porcentage;
                    }
                }
                $portool[$index] = $portool;
            } else {
                $portool[$index] = 100;
            }
        } else if ($state == 1) {
            $porcentage;
            $sumpercentage = array_sum($poninput);
            if ($numtool > 1) {
                $porcentage = floor((100 - $sumpercentage) / ($numtool - $nummod));
            } else {
                $toolpor[$index] = 100;
            }

            foreach ($toolpor as $key => $value) {
                if (isset($poninput[$key])) {
                    $toolpor[$key] = $poninput[$key];
                } else if ((string)$key == (string)$index) {
                    $toolpor[$index] = $poratrib;
                } else {
                    $toolpor[$key] = $porcentage;
                }
            }
        } else if ($state == 2) {
            foreach ($toolpor as $key => $value) {
                $toolpor[$key] = $poninput[$key];
            }
        }

        $tool->set_toolpor($toolpor);
    } else if (!isset($postsaved)) {
        $numtool = $tool->get_numtool();
        if ($numtool > 0) {
            $porcentage = floor(100 / $numtool);
            $tools = $tool->get_tools();
            foreach ($tools as $key => $value) {
                $toolpor[$key] = $porcentage;
            }
            $tool->set_toolpor($toolpor);
        }
    }
}

// Add/Delete dimension event.
if (isset($postadddim)) {
    $numdim = count($tool->get_dimension($id));
    if (isset($postnumdim) && !isset($addim) && !isset($deldim)) {
        if ($postnumdim > $numdim) {
            $nd = $numdim;
            for ($i = $nd; $i < $postnumdim; $i++) {
                $key = $secuencia;
                $tool->add_dimension(null, $key, $id);
                $secuencia++;
                $SESSION->secuencia = $secuencia;
            }
        } else if ($postnumdim < $numdim) {
            $i = 0;

            $dimension = $tool->get_dimension($id);
            foreach ($dimension as $key => $value) {
                if ($i >= $postnumdim) {
                    $dim = $key;
                    $tool->remove_dimension($dim);
                }
                $i++;
            }
        }
    } else if (isset($addim)) {
        $key = $secuencia;
        $dim = $addim;
        $tool->add_dimension($dim, $key, $id);
        $secuencia++;
        $SESSION->secuencia = $secuencia;
    } else if (isset($deldim)) {
        $dim = $deldim;
        $tool->remove_dimension($dim, $id);
    }

    // Dimension percentage.
    $postdimpor = optional_param('dimpor'.$id, null, PARAM_INT);
    $postdimindex = optional_param('dpi', null, PARAM_ALPHANUM);
    $postitemmod = optional_param('sumpor3'.$id, null, PARAM_RAW);

    $dimpor = $tool->get_dimpor($id);
    if (isset($postdimpor) && is_numeric($postdimpor) && $postdimpor >= 0 && $postdimpor <= 100 && isset($postdimindex)) {
        $pordim = $postdimpor;
        $index = $postdimindex;
        $numdimen = $tool->get_numdim($id);
        if ($tool->get_valtotal($id) == 'true' || $tool->get_valtotal($id) == 't') {
            $numdimen++;
        }
        $porcentage = $pordim;
        if ($numdimen > 1) {
            $porcentage = floor((100 - $pordim) / ($numdimen - 1));
        } else {
            $dimpor[$index] = 100;
        }

        // Set of percentages entered by the user and to try to keep.
        // Id-id-id-...
        $aux = explode('-', $postitemmod);
        $iditemmod = array_unique($aux);
        array_pop($iditemmod);

        $sumamod = 0;
        $nummod = 0;
        $poninput = array();
        $samebutton = 1; // Indicates if the user has pressed a button related to any of the received values.
        foreach ($iditemmod as $key => $cod) {
            $postpor = optional_param($cod, null, PARAM_RAW);
            if (isset($postpor)) {
                $nummod++;
                $sumamod += $postpor;
                $aux = explode('_', $cod);
                if ($aux[0] == 'valtotalpor'.$id) {
                    $poninput['vt'] = $postpor;
                    if ($index == 'vt') {
                        $samebutton = 0;
                    }
                } else {
                    $poninput[$aux[1]] = $postpor;
                    if ($aux[1] == $index) {
                        $samebutton = 0;
                    }
                }
            }
        }
        if ($samebutton) {
            $nummod++;
            $poninput[$index] = $pordim;
            $sumamod += $pordim;
        }

        $state = 0;
        if ($nummod == $numdimen && $sumamod != 100) {
            $state = 0;
        } else if ($nummod != $numdimen && $sumamod > 100 ) {
            $state = 0;
        } else if ($nummod == $numdimen && $sumamod == 100) {
            $state = 2;
        } else {
            $state = 1;
        }

        if ($state == 0) {
            // Percentage to distribute for the rest of attributes.
            $porcentage = $pordim;
            if ($numdimen > 1) {
                $porcentage = floor((100 - $pordim) / ($numdimen - 1));
                foreach ($dimpor as $key => $value) {
                    $dimpor[$key] = $porcentage;
                    if ((string)$key == (string)$index) {
                        $dimpor[$key] = $pordim;
                    }
                }
                if ($index == 'vt') {
                    $valtotalpor = $pordim;
                } else if ($valtotal == 'true') {
                    $valtotalpor = $porcentage;
                }
            } else {
                $dimpor[$index] = 100;
            }
        } else if ($state == 1) {
            $porcentage;
            $sumpercentage = array_sum($poninput);
            if ($numdimen > 1) {
                $porcentage = floor((100 - $sumpercentage) / ($numdimen - $nummod));
            } else {
                $dimpor[$index] = 100;
            }
            foreach ($dimpor as $key => $value) {
                if (isset($poninput[$key])) {
                    $dimpor[$key] = $poninput[$key];
                } else if ((string)$key == (string)$index) {
                    $dimpor[$index] = $pordim;
                } else {
                    $dimpor[$key] = $porcentage;
                }
            }
            if (isset($poninput['vt'])) {
                $valtotalpor = $poninput['vt'];
            } else {
                $valtotalpor = $porcentage;
            }
        } else if ($state == 2) {
            foreach ($dimpor as $key => $value) {
                if (isset($poninput[$key])) {
                    $dimpor[$key] = $poninput[$key];
                }
            }
            if (isset($valtotal) && $valtotal == 'true') {
                $valtotalpor = $poninput['vt'];
            }
        }

        if (isset($valtotalpor)) {
            $tool->set_valtotalpor($valtotalpor, $id);
        }
        $tool->set_dimpor($dimpor, $id);
    } else if (!isset($postsaved)) {
        $numdimen = $tool->get_numdim($id);
        $booltotal = optional_param('valtotal'.$id, '', PARAM_ALPHA);
        if ($booltotal == 'true') {
            $numdimen++;
        }
        $porcentage = floor(100 / $numdimen);
        $dimen = $tool->get_dimension($id);
        foreach ($dimen as $key => $value) {
            $dimpor[$key] = $porcentage;
        }
        if ($booltotal == 'true') {
            $tool->set_valtotalpor($porcentage, $id);
        }
        $tool->set_dimpor($dimpor, $id);
    }

    // Total value.
    if ($postvalortotal == 'true' && isset($postnumvalortotal)) {
        $numtotal = $tool->get_numtotal($id);
        if ($postnumvalortotal > $numtotal) {
            $nvalores = $numtotal;
            // There will be at least 2 values. We control the case that the number of values entered is 1.
            $numvalortotal = $postnumvalortotal;
            if ($postnumvalortotal < 2) {
                $numvalortotal = 2;
            }
            for ($i = $nvalores; $i < $numvalortotal; $i++) {
                $key = $secuencia;
                $tool->add_total_values($key, $id);
                $secuencia++;
                $SESSION->secuencia = $secuencia;
            }
        } else if ($postnumvalortotal < $numtotal) {
            $i = 0;
            $valorestotal = $tool->get_valorestotal($id);
            foreach ($valorestotal as $key => $value) {
                if ($i >= $postnumvalortotal) {
                    $grado = $key;
                    $tool->remove_total_values($grado, $id);
                }
                $i++;
            }
        }
    }
} else if (isset($postsubdim)) { // Add/delete subdimension and values event.
    $dim = $postsubdim;
    $subdim = optional_param('sd', null, PARAM_RAW);
    $postaddsubdim = optional_param('aS', null, PARAM_RAW);
    $postdelsubdim = optional_param('dS', null, PARAM_RAW);
    $postnumvalues = optional_param('numvalores'.$id.'_'.$dim, null, PARAM_RAW);
    $postnumsubdim = optional_param('numsubdimensiones'.$id.'_'.$dim, null, PARAM_RAW);

    $numvalores = $tool->get_numvalores($id);
    if (isset($numvalores[$dim]) && $postnumvalues > $numvalores[$dim]) {
        $nvalores = $numvalores[$dim];
        for ($i = $nvalores; $i < $postnumvalues; $i++) {
            $key = $secuencia;
            $tool->add_values($dim, $key, $id);
            $secuencia++;
            $SESSION->secuencia = $secuencia;
        }
    } else if (isset($numvalores[$dim]) && $postnumvalues < $numvalores[$dim]) {
        $i = 0;
        $valores = $tool->get_valores($id);
        foreach ($valores[$dim] as $key => $value) {
            if ($i >= $postnumvalues) {
                $grado = $key;
                $tool->remove_values($dim, $grado, $id);
            }
            $i++;
        }
    }

    if (isset($postnumsubdim) && !isset($postaddsubdim) && !isset($postdelsubdim)) {
        $numsubdim = $tool->get_numsubdim($id);
        if ($postnumsubdim > $numsubdim[$dim]) {
            $ns = $numsubdim[$dim];
            for ($i = $ns; $i < $postnumsubdim; $i++) {
                $key = $secuencia;
                $tool->add_subdimension($dim, null, $key, $id);
                $secuencia++;
                $SESSION->secuencia = $secuencia;
            }
        } else if ($postnumsubdim < $numsubdim[$dim]) {
            $i = 0;
            $subdimension = $tool->get_subdimension($id);
            foreach ($subdimension[$dim] as $key => $value) {
                if ($i >= $postnumsubdim) {
                    $subdim = $key;
                    $tool->remove_subdimension($dim, $subdim, $id);
                }
                $i++;
            }
        }
    } else if (isset($postaddsubdim)) {
        $key = $secuencia;
        $tool->add_subdimension($dim, $subdim, $key, $id);
        $secuencia++;
        $SESSION->secuencia = $secuencia;
    } else if (isset($postdelsubdim)) {
        $tool->remove_subdimension($dim, $subdim, $id);
    }

    // Range.
    $postaddrango = optional_param('addrango', null, PARAM_RAW);
    $postidrango = optional_param('idrango', null, PARAM_RAW);

    if (isset($postaddrango)) {
        $postselect = optional_param('sel', null, PARAM_INT);

        $rango[$id] = $tool->get_rango($id);

        if (isset($postaddrango)) {
            $aux = explode('_', $postaddrango);
            $grado = $aux[1];
        }
        if (isset($postidrango)) {
            $aux2 = explode('_', $postidrango);
            $key = $aux2[3];
        }
        if (isset($postselect) && $postselect >= 0 && $postselect <= 100 && isset($grado) && isset($key)) {
            $rango[$id][$dim][$grado][$key] = $postselect;
            $tool->set_rango($rango, $id);
        }

        $postnumrango = optional_param('numrango'.$id.'_'.$dim.'_'.$grado, null, PARAM_RAW);
        $numrango = $tool->get_numrango($id);
        if (isset($numrango) && $postnumrango > $numrango[$id][$dim][$grado]) {
            $nvalores = $numrango[$id][$dim][$grado];
            for ($i = $nvalores; $i < $postnumrango; $i++) {
                $key = $secuencia;
                $tool->add_range($dim, $grado, $key, $id);
                $secuencia++;
                $SESSION->secuencia = $secuencia;
            }
        } else if (isset($numrango) && $postnumrango < $numrango[$id][$dim][$grado]) {
            $i = 0;
            $rango[$id] = $tool->get_rango($id);
            foreach ($rango[$id][$dim][$grado] as $key => $value) {
                if ($i >= $postnumrango) {
                    $tool->remove_range($dim, $grado, $key, $id);
                }
                $i++;
            }
        }
    }

    $postsubdimpor = optional_param('subdimpor', null, PARAM_RAW);
    $postsubdimindex = optional_param('spi', null, PARAM_RAW);
    $postitemmod = optional_param('sumpor2'.$id.'_'.$dim, null, PARAM_RAW);
    $postcomdim = optional_param('comDim', null, PARAM_RAW);

    if (isset($postsubdimpor) && is_numeric($postsubdimpor) && $postsubdimpor >= 0
            && $postsubdimpor <= 100 && isset($postsubdimindex)) {
        $porsubdim = $postsubdimpor;
        $subdimpor = $tool->get_subdimpor($id);
        $index = $postsubdimindex;
        $numsubdimen = $tool->get_numsubdim($id);
        $valglobal = $tool->get_valglobal($id);
        $valglobalpor = $tool->get_valglobalpor($id);
        $subdimen = $tool->get_subdimension($id);
        if (isset($valglobal[$dim]) && $valglobal[$dim] == 'true') {
            $numsubdimen[$dim]++;
        }

        // Set of percentages entered by the user and to try to keep.
        // Id-id-id-...
        $aux = explode('-', $postitemmod);
        $iditemmod = array_unique($aux);
        array_pop($iditemmod);

        $sumamod = 0;
        $nummod = 0;
        $poninput = array();
        // Indicates if the user who has sent has pressed a button related to any of the values received or not.
        $samebutton = 1;
        foreach ($iditemmod as $key => $cod) {
            $postpor = optional_param($cod, null, PARAM_RAW);
            if (isset($postpor)) {
                $nummod++;
                $sumamod += $postpor;
                $aux = explode('_', $cod);
                if ($aux[0] == 'valglobalpor'.$id) {
                    $poninput['vg'] = $postpor;
                    if ($index == 'vg') {
                        $samebutton = 0;
                    }
                } else {
                    $poninput[$aux[2]] = $postpor;
                    if ($aux[2] == $index) {
                        $samebutton = 0;
                    }
                }
            }
        }
        if ($samebutton) {
            $nummod++;
            $poninput[$index] = $porsubdim;
            $sumamod += $porsubdim;
        }

        $state = 0;
        if ($nummod == $numsubdimen[$dim] && $sumamod != 100) {
            $state = 0;
        } else if ($nummod != $numsubdimen[$dim] && $sumamod > 100 ) {
            $state = 0;
        } else if ($nummod == $numsubdimen[$dim] && $sumamod == 100) {
            $state = 2;
        } else {
            $state = 1;
        }

        if ($state == 0) {
            $porcentage = $porsubdim;
            if ($numsubdimen[$dim] > 1) {
                $porcentage = floor((100 - $porsubdim) / ($numsubdimen[$dim] - 1));
                foreach ($subdimpor[$dim] as $key => $value) {
                    $subdimpor[$dim][$key] = $porcentage;
                    if ((string)$key == (string)$index) {
                        $subdimpor[$dim][$key] = $porsubdim;
                    }
                }
                if ($index == 'vg') {
                    $valglobalpor[$dim] = $porsubdim;
                } else if ($valglobal[$dim] == 'true') {
                    $valglobalpor[$dim] = $porcentage;
                }
            } else {
                $subdimpor[$dim][$index] = 100;
            }
        } else if ($state == 1) {
            $porcentage;
            $sumpercentage = array_sum($poninput);
            if ($numsubdimen[$dim] > 1) {
                $porcentage = floor((100 - $sumpercentage) / ($numsubdimen[$dim] - $nummod));
            } else {
                $subdimpor[$dim][$index] = 100;
            }

            foreach ($subdimpor[$dim] as $key => $value) {
                if (isset($poninput[$key])) {
                    $subdimpor[$dim][$key] = $poninput[$key];
                } else if ((string)$key == (string)$index) {
                    $subdimpor[$dim][$index] = $porsubdim;
                } else {
                    $subdimpor[$dim][$key] = $porcentage;
                }
            }
            if (isset($poninput['vg'])) {
                $valglobalpor[$dim] = $poninput['vg'];
            } else {
                $valglobalpor[$dim] = $porcentage;
            }
        } else if ($state == 2) {
            foreach ($subdimpor[$dim] as $key => $value) {
                $subdimpor[$dim][$key] = $poninput[$key];
            }
            if (isset($valglobal[$dim]) && $valglobal[$dim] == 'true') {
                $valglobalpor[$dim] = $poninput['vg'];
            }
        }

        $tool->set_subdimpor($subdimpor, $id);

        if (isset($valglobalpor[$dim])) {
            $tool->set_valglobalpor($valglobalpor, $id);
        }
    } else if (!isset($postcomdim)) {
        $numsubdim = $tool->get_numsubdim($id);
        $valglobalpor = $tool->get_valglobalpor($id);
        $postvalglobal = optional_param('valglobal'.$id.'_'.$dim, '', PARAM_RAW);
        if ($postvalglobal == 'true') {
            $numsubdim[$dim]++;
        }
        $porcentage = floor(100 / $numsubdim[$dim]);
        $subdimen = $tool->get_subdimension($id);
        $subdimpor = $tool->get_subdimpor($id);
        foreach ($subdimen as $iddim => $value) {
            if ((string)$iddim == (string)$dim) {
                foreach ($value as $key => $value2) {
                    $subdimpor[$dim][$key] = $porcentage;
                }
            }
        }

        if ($postvalglobal == 'true') {
            $valglobalpor[$dim] = $porcentage;
            $tool->set_valglobalpor($valglobalpor, $id);
        }

        $tool->set_subdimpor($subdimpor, $id);
    }
} else if (isset($postaddatr)) { // Add/Delete attribute event.
    $data = explode('_', $postaddatr);
    $dim = $data[0];
    $subdim = $data[1];

    $numatr = $tool->get_numatr($id);
    $postnumatr = optional_param('numatributos'.$id.'_'.$dim.'_'.$subdim, null, PARAM_RAW);

    if (!isset($postdelatrib) && !isset($postaddatrib) && isset($postnumatr)) {
        if ($postnumatr > $numatr[$dim][$subdim]) {
            $na = $numatr[$dim][$subdim];
            for ($i = $na; $i < $postnumatr; $i++) {
                $key = $secuencia;
                $tool->add_attribute($dim, $subdim, null, $key, $id);
                $secuencia++;
                $SESSION->secuencia = $secuencia;
            }
        } else if ($postnumatr < $numatr[$dim][$subdim]) {
            $na = $numatr[$dim][$subdim];
            $diferencia = $na - $postnumatr;
            $i = 0;
            $atributo = $tool->get_atributo($id);
            foreach ($atributo[$dim][$subdim] as $key => $value) {
                if ($i >= $postnumatr) {
                    $atrib = $key;
                    $tool->remove_attribute($dim, $subdim, $atrib, $id);
                }
                $i++;
            }
        }
    } else if (isset($postaddatrib)) {
        $key = $secuencia;
        $tool->add_attribute($dim, $subdim, $postaddatrib, $key, $id);
        $secuencia++;
        $SESSION->secuencia = $secuencia;
    } else if (isset($postdelatrib)) {
        $tool->remove_attribute($dim, $subdim, $postdelatrib, $id);
    }

    $postatribpor = optional_param('atribpor', null, PARAM_RAW);
    $postatribindex = optional_param('api', null, PARAM_RAW);
    $postitemmod = optional_param('sumpor'.$id.'_'.$dim.'_'.$subdim, null, PARAM_RAW);

    $atribpor = $tool->get_atribpor($id);
    if (isset($postatribpor) && is_numeric($postatribpor) && $postatribpor >= 0 && $postatribpor <= 100 && isset($postatribindex)) {
        // Set of percentages entered by the user and to try to keep.
        // Id-id-id-...
        $aux = explode('-', $postitemmod);
        $iditemmod = array_unique($aux);
        array_pop($iditemmod);

        $poratrib = $postatribpor;
        $index = $postatribindex;

        $sumamod = 0;
        $numatribb = $tool->get_numatr($id);
        $nummod = 0;
        $poninput = array();
        // Indicates whether the user has pressed a button related to any of the values received or not.
        $samebutton = 1;
        foreach ($iditemmod as $key => $cod) {
            $postpor = optional_param($cod, null, PARAM_RAW);
            if (isset($postpor)) {
                $nummod++;
                $sumamod += $postpor;
                $aux = explode('_', $cod);
                $poninput[$aux[3]] = $postpor;
                if ($aux[3] == $index) {
                    $samebutton = 0;
                }
            }
        }
        if ($samebutton) {
            $nummod++;
            $poninput[$index] = $poratrib;
            $sumamod += $poratrib;
        }

        /*
        * state == 0 occurs when a percentage value has been entered in each attribute of the subdimension in
        question and the sum is different from 100
        *            occurs when, regardless of the number of entered percentage values, the sum is greater than 100
        * state == 1 occurs when some percentage values are entered in each attribute of the subdimension and the sum
        is less than 100
        * state == 2 occurs when a percentage value has been entered in each attribute of the subdimension in question
        and the sum is 100
        */

        $state = 0;
        if ($nummod == $numatribb[$dim][$subdim] && $sumamod != 100) {
            $state = 0;
        } else if ($nummod != $numatribb[$dim][$subdim] && $sumamod > 100 ) {
            $state = 0;
        } else if ($nummod == $numatribb[$dim][$subdim] && $sumamod == 100) {
            $state = 2;
        } else {
            $state = 1;
        }

        if ($state == 0) {
            if ($numatribb[$dim][$subdim] > 1) {
                $porcentage = floor((100 - $poratrib) / ($numatribb[$dim][$subdim] - 1));
                foreach ($atribpor[$dim][$subdim] as $key => $value) {
                    if ((string)$key != (string)$index) {
                        $atribpor[$dim][$subdim][$key] = $porcentage;
                    }
                }
                $atribpor[$dim][$subdim][$index] = $poratrib;
            } else {
                $atribpor[$dim][$subdim][$index] = 100;
            }
        } else if ($state == 1) {
            $apor = array(); // Contains the percentage value of each attribute of the subdimension in question.
            foreach ($atribpor[$dim][$subdim] as $keypor => $vpor) {
                $postatribporone = optional_param('atribpor'.$id.'_'.$dim.'_'.$subdim.'_'.$keypor, null, PARAM_RAW);
                if (isset($postatribporone)) {
                    $apor[$keypor] = $postatribporone;
                }
                $sumapor = array_sum($apor);
            }
            if ($sumapor == 100) {
                foreach ($atribpor[$dim][$subdim] as $key => $value) {
                    $atribpor[$dim][$subdim][$key] = $apor[$key];
                }
            } else {
                $porcentage;
                $sumpercentage = array_sum($poninput);
                if ($numatribb[$dim][$subdim] > 1) {
                    $porcentage = floor((100 - $sumpercentage) / ($numatribb[$dim][$subdim] - $nummod));
                } else {
                    $atribpor[$dim][$subdim][$index] = 100;
                }
                foreach ($atribpor[$dim][$subdim] as $key => $value) {
                    if (isset($poninput[$key])) {
                        $atribpor[$dim][$subdim][$key] = $poninput[$key];
                    } else if ((string)$key == (string)$index) {
                        $atribpor[$dim][$subdim][$index] = $poratrib;
                    } else {
                        $atribpor[$dim][$subdim][$key] = $porcentage;
                    }
                }
            }
        } else if ($state == 2) {
            foreach ($atribpor[$dim][$subdim] as $key => $value) {
                $atribpor[$dim][$subdim][$key] = $poninput[$key];
            }
        }
        $tool->set_atribpor($atribpor, $id);
    } else if (!isset($postcomatr)) {
        $numatrib = $tool->get_numatr($id);
        $porcentage = floor(100 / $numatrib[$dim][$subdim]);
        $atrib = $tool->get_atributo($id);
        foreach ($atrib[$dim][$subdim] as $key => $value) {
            $atribpor[$dim][$subdim][$key] = $porcentage;
        }
        $tool->set_atribpor($atribpor, $id);
    }
}

// Move attribute event.
if (isset($postmoveatr)) {
    $data = explode('_', $postmoveatr);
    $dim = $data[0];
    $subdim = $data[1];

    $atributo = $tool->get_atributo($id);
    $params['dim'] = $dim;
    $params['subdim'] = $subdim;
    $params['blockData'] = $atributo[$dim][$subdim];
    $params['blockName'] = 'atributo';
    $params['id'] = $id;

    if (isset($postupatr)) {
        $params['blockIndex'] = $postupatr;
        $params['instanceName'] = $atributo[$dim][$subdim][$postupatr]['nombre'];
        $tool->up_block($params);

        if ($tool->type == 'diferencial') {
            $atributopos = $tool->get_atributopos($id);
            $params['blockIndex'] = $postupatr;
            $params['instanceName'] = $atributopos[$dim][$subdim][$postupatr]['nombre'];
            $tool->up_block($params);
        }
    } else if (isset($postdownatr)) {
        $params['blockIndex'] = $postdownatr;
        $params['instanceName'] = $atributo[$dim][$subdim][$postdownatr]['nombre'];
        $tool->down_block($params);
        if ($tool->type == 'diferencial') {
            $atributopos = $tool->get_atributopos($id);
            $params['blockIndex'] = $postdownatr;
            $params['blockName'] = 'atributopos';
            $params['instanceName'] = $atributopos[$dim][$subdim][$postdownatr]['nombre'];
            $tool->down_block($params);
        }
    }
}

// Move subdimension event.
if (isset($postmovesub)) {
    $dim = $postmovesub;
    $subdimension = $tool->get_subdimension($id);
    $params['dim'] = $dim;
    $params['blockData'] = $subdimension[$dim];
    $params['blockName'] = 'subdimension';
    $params['id'] = $id;
    if (isset($postupsub)) {
        $params['blockIndex'] = $postupsub;
        $params['instanceName'] = $subdimension[$dim][$postupsub]['nombre'];
        $tool->up_block($params);
    } else if (isset($postdownsub)) {
        $params['blockIndex'] = $postdownsub;
        $params['instanceName'] = $subdimension[$dim][$postdownsub]['nombre'];
        $tool->down_block($params);
    }
}

// Move dimension event.
if (isset($postmovedim)) {
    $dimension = $tool->get_dimension($id);
    $params['blockData'] = $dimension;
    $params['blockName'] = 'dimension';
    $params['id'] = $id;
    if (isset($postupdim)) {
        $params['blockIndex'] = $postupdim;
        $params['instanceName'] = $dimension[$postupdim]['nombre'];
        $tool->up_block($params);
    } else if (isset($postdowndim)) {
        $params['blockIndex'] = $postdowndim;
        $params['instanceName'] = $dimension[$postdowndim]['nombre'];
        $tool->down_block($params);
    }
}

// Move tool event.
if (isset($postmovetool)) {
    $tools = $tool->get_tools();
    $params['blockData'] = $tools;
    $params['blockName'] = 'tool';
    $params['mixed'] = 'mixed';
    if (isset($postuptool)) {
        $params['id'] = $postuptool;
        $params['blockIndex'] = $postuptool;
        $params['instance'] = $tools[$postuptool];
        $tool->up_block($params);
    } else if (isset($postdowntool)) {
        $params['id'] = $postdowntool;
        $params['blockIndex'] = $postdowntool;
        $params['instance'] = $tools[$postdowntool];
        $tool->down_block($params);
    }
}

// Add competency event.
if (isset($postmodaladdcomp) && isset($postmodalcompsel)) {
    $value = explode('_', $postmodaladdcomp);
    $dimkey = array_shift($value);
    $subdimkey = array_shift($value);
    $idnumber = implode('_', $value);
    $previouscompetencystring = $tool->get_competency_string($id, $dimkey, $subdimkey);
    $label = (!empty($posttitle)) ? 'title' : 'subdimension';
    $tool->add_competency($id, $dimkey, $subdimkey, $idnumber, $postmodalcompsel);
    $tool->edit_competencies_in_label($id, $dimkey, $subdimkey, $label, $previouscompetencystring);
}

// Add outcome event.
if (isset($postmodaladdout) && isset($postmodaloutsel)) {
    $value = explode('_', $postmodaladdout);
    $dimkey = array_shift($value);
    $subdimkey = array_shift($value);
    $idnumber = implode('_', $value);
    $previouscompetencystring = $tool->get_competency_string($id, $dimkey, $subdimkey);
    $label = (!empty($posttitle)) ? 'title' : 'subdimension';
    $tool->add_outcome($id, $dimkey, $subdimkey, $idnumber, $postmodaloutsel);
    $tool->edit_competencies_in_label($id, $dimkey, $subdimkey, $label, $previouscompetencystring);
}

// Delete competency event.
if (isset($postmodaldelcomp) || isset($postmodaldelcompsub)) {
    $string = (isset($postmodaldelcomp)) ? $postmodaldelcomp : $postmodaldelcompsub;
    $value = explode('_', $string);
    $dimkey = array_shift($value);
    $subdimkey = array_shift($value);
    $idnumber = implode('_', $value);
    $previouscompetencystring = $tool->get_competency_string($id, $dimkey, $subdimkey);
    $label = (!empty($posttitle)) ? 'title' : 'subdimension';
    $tool->remove_competency($id, $dimkey, $subdimkey, $idnumber);
    $tool->edit_competencies_in_label($id, $dimkey, $subdimkey, $label, $previouscompetencystring);
}

// Delete outcome event.
if (isset($postmodaldelout) || isset($postmodaldeloutsub)) {
    $string = (isset($postmodaldelout)) ? $postmodaldelout : $postmodaldeloutsub;
    $value = explode('_', $string);
    $dimkey = array_shift($value);
    $subdimkey = array_shift($value);
    $idnumber = implode('_', $value);
    $previouscompetencystring = $tool->get_competency_string($id, $dimkey, $subdimkey);
    $label = (!empty($posttitle)) ? 'title' : 'subdimension';
    $tool->remove_outcome($id, $dimkey, $subdimkey, $idnumber);
    $tool->edit_competencies_in_label($id, $dimkey, $subdimkey, $label, $previouscompetencystring);
}

// Close modal.
if (isset($postmodalclosevoid)) {
    $value = explode('_', $postmodalclosevoid);
    $dimkey = $value[0];
    $subdimkey = $value[1];
    $label = (!empty($posttitle)) ? 'title' : 'subdimension';
    $previouscompetencystring = $tool->get_competency_string($id, $dimkey, $subdimkey);
    $tool->edit_competencies_in_label($id, $dimkey, $subdimkey, $label, $previouscompetencystring);
}

// Create competency.
if (isset($postmodalcreatecomp)) {
    if (isset($postmodalidnumber) && isset($postmodalshortname)) {
        $comptype = (isset($postmodalcomptype)) ? $postmodalcomptype : null;
        block_evalcomix_editor_tool::create_competency($postmodalidnumber, $postmodalshortname, $postmodaldescription,
        $comptype);
    }
}

// Create outcome.
if (isset($postmodalcreateout)) {
    if (isset($postmodalidnumber) && isset($postmodalshortname)) {
        block_evalcomix_editor_tool::create_outcome($postmodalidnumber, $postmodalshortname, $postmodaldescription);
    }
}
