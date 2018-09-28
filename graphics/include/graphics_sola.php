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
/*
    Autores:
        - Gonzalo Saavedra Postigo
        - David Mariscal Martínez
        - Aris Burgos Pintos
    Organización: ITelligent Information Technologies, S.L.
    Licencia: gpl-3.0
*/

echo '<script type="text/javascript">
        if(window.addEventListener){
          window.addEventListener(\'load\',function(){
            graf = new Grafica (\'LineChart\',document.getElementById(\'grafica\'),document.getElementById(\'tdGraficas\'));
            graf.generargraficaLine ('.parsea_string($titulo).',
                                     '.$minvalor.',
                                     '.$maxvalor.',
                                     '.parsea($arraydata).',
                                     '.parsea_boolean($blnincluirlimites).',
                                     '.parsea_boolean($blnincluirdispersion).',
                                     '.$vallims.');
          },true);
        }
        else{
            if (window.attachEvent){
                window.attachEvent(\'onload\', function(){
                graf = new Grafica (\'LineChart\',document.getElementById(\'grafica\'),document.getElementById(\'tdGraficas\'));
                graf.generargraficaLine ('.parsea_string($titulo).',
                                                     '.$minvalor.',
                                                     '.$maxvalor.',
                                                     '.parsea($arraydata).',
                                                     '.parsea_boolean($blnincluirlimites).',
                                                     '.parsea_boolean($blnincluirdispersion).',
                                                     '.$vallims.');
                          });
                }
        }

        </script>';
?>

<script type="text/javascript">
document.getElementById('pstPT').className ="pestania";
document.getElementById('pstPA').className ="pestania";
document.getElementById('pstPAtr').className ="pestania";
document.getElementById('pstPAleat').className ="pestania_activa";
</script>

<table style="background-color: transparent; display: inline;">
        <tr id="tdGraficas" style="width: 100%; background-color: transparent; vertical-align: top;">
            <div id="graficas" style="width: 100%; text-align: center; display: inline;" class="shadowbox">
                <div id="grafica" style="text-align: center;" ></div>
            </div>
        </tr>
 </table>

