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
defined('MOODLE_INTERNAL') || die();
    /* ---------------------------------------------------------
       ---------------------------------------------------------
        Autores:
            - Gonzalo Saavedra Postigo
            - David Mariscal Martínez
            - Aris Burgos Pintos
        Organización: ITelligent Information Technologies, S.L.
        Licencia: gpl-3.0
      ----------------------------------------------------------
      ---------------------------------------------------------- */
?>


<link rel="stylesheet" type="text/css" href="style/border.css">
<link rel="stylesheet" type="text/css" href="style/bordercolors.css">
<link rel="stylesheet" type="text/css" href="style/filter.css">
<link rel="stylesheet" type="text/css" href="style/general.css">
<!-- Scripts for borders -->
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/border.js"></script>
<script type="text/javascript" src="js/bordercolors.js"></script>

<script type="text/javascript" src="js/google.js"></script>
<script type="text/javascript" src="js/grafica.ITelligent.js"></script>
<script type="text/javascript" src="js/grafica_layout.ITelligent.js"></script>

<?php

function parsea($lista) {
    $r = "[";
    $t = count($lista);
    for ($i = 0; $i < $t; $i++) {
        $r .= "[";
        $l = count($lista[$i]);
        for ($j = 0; $j < $l; $j++) {
            $dato = $lista[$i][$j];
            if ($j == 0) {
                $dato = '"'.$dato.'"';
            }
            $r .= $dato;
            if ($j != ($l - 1)) {
                $r .= ",";
            }
        }
        $r .= "]";
        if ($i != ($t - 1)) {
            $r .= ",";
        }
    }

    return ($r."]");
}

function parsea_boolean($b) {
    return (($b) ? 'true' : 'false');
}

function parsea_string($s) {
    return ('"'.$s.'"');
}

class graphic {
     // Metodos forma 2 (solo gráficos google según el estándar hablado).

    public static function draw_perfil_tarea($idcurso) {
        require('include/graphics_perfil_tarea.php');
    }

    public static function draw_perfil_alumnado($idcurso) {
        require('include/graphics_perfil_alumnado.php');
    }

    public static function draw_perfil_atributos($idcurso) {
        require('include/graphics_perfil_atributos.php');
    }

    // Metodos forma 2 (solo gráficos google según el estándar hablado)
    // $minvalor : valor mínimo del eje que es numérico
    // $maxvalor : valor máximo del eje que es numérico
    // $arraydata : valor de los datos del tipo ['profesor', nota1, nota2, nota3] ['autoevaluacion', nota1, nota2, nota3]...
    // $blnincluirlimites : incluir limites LS LM LI
    // $blnincluirdispersion : incluir Dispersión si procediera.

    public static function draw_perfil_tarea_sola($titulo, $minvalor, $maxvalor, $arraydata,
        $blnincluirlimites, $blnincluirdispersion, $vallims) {
        // Mapear datos obteniendo los promedios y desviaciones típicas.

        // Mostrar resultado.
        require('include/graphics_sola.php');
    }
}
