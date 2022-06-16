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
 * @author     Daniel Cabeza Sánchez <info@ansaner.net>
 */

require_once('../../../../config.php');
$courseid = required_param('courseid', PARAM_INT);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
require_course_login($course);

unset($SESSION->tool);

$posttype = optional_param('type', '', PARAM_ALPHA);

$type = 'escala';
$types = array('lista', 'escala', 'listaescala', 'rubrica', 'mixta', 'diferencial', 'argumentario', 'importar');
if (in_array($posttype, $types)) {
    $type = $posttype;
}

$titulo = get_string('title', 'block_evalcomix');
$secuencia = 0;
$dimension = null;
$numdim = null;
$subdimension = null;
$numsubdim = null;
$atributo = null;
$numatr = null;
$valores = null;
$numvalores = null;
$numtotal = null;
$valorestotal = null;
$valglobal = null;
$valglobalpor = null;
$dimpor = null;
$subdimpor = null;
$atribpor = null;
$commentatr = null;
$commentdim = null;

if ($type != 'mixta' && $type != 'importar' && !isset($SESSION->open)) {
    $indextool = 0;
    $dim = $secuencia;
    $numdim[$indextool] = 1;
    $dimension[$indextool][$dim]['nombre'] = get_string('titledim', 'block_evalcomix') . '1';
    $commentdim[$indextool][$dim] = 'hidden';
    $valglobal[$indextool][$dim] = false;
    $valglobalpor[$indextool][$dim] = null;
    $dimpor[$indextool][$dim] = 100;
    $subdim = $secuencia++;
    $subdimension[$indextool][$dim][$subdim]['nombre'] = get_string('titlesubdim', 'block_evalcomix').'1';
    $numsubdim[$indextool][$dim] = 1;
    $subdimpor[$indextool][$dim][$subdim] = 100;

    $atrib = $secuencia++;
    $atributo[$indextool][$dim][$subdim][$atrib]['nombre'] = get_string('titleatrib', 'block_evalcomix').'1';
    $commentatr[$indextool][$dim][$subdim][$atrib] = 'hidden';
    $numatr[$indextool][$dim][$subdim] = 1;
    $atribpor[$indextool][$dim][$subdim][$atrib] = 100;

    $numvalores[$indextool][$dim] = 2;
    $valores[$indextool][$dim][0]['nombre'] = get_string('titlevalue', 'block_evalcomix').'1';
    $valores[$indextool][$dim][1]['nombre'] = get_string('titlevalue', 'block_evalcomix').'2';
    if ($type == 'lista') {
        $valores[$indextool][$dim][0]['nombre'] = get_string('no', 'block_evalcomix');
        $valores[$indextool][$dim][1]['nombre'] = get_string('yes', 'block_evalcomix');
    }

    $numtotal = array();
    $valorestotal = array();
}

$secuencia++;
$language = 'es_utf8';
if (isset($SESSION->lang)) {
    $language = $SESSION->lang;
}

require_once('tool.php');
$tool = new block_evalcomix_editor_tool($language, $type, $titulo, $dimension, $numdim, $subdimension, $numsubdim, $atributo,
$numatr, $valores, $numvalores, array('id' => 0), $numtotal, $valorestotal, $valglobal, $valglobalpor, $dimpor,
$subdimpor, $atribpor, $commentatr, $commentdim);
$toolobj = serialize($tool);
$SESSION->tool = $toolobj;
$SESSION->secuencia = $secuencia;
