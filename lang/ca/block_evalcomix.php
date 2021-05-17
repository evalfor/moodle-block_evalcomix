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

/* Translation from Spanish to Catalan by USQUID-ESUP, UPF.
* http://www.usquidesup.upf.edu */

$string['pluginname'] = 'EvalCOMIX';
$string['evalcomix'] = 'EvalCOMIX';
$string['blocksettings'] = 'Configuració';
$string['blockstring'] = 'Contingut d\'EvalCOMIX';
$string['instruments'] = 'Gestió d\'instruments';
$string['evaluation'] = 'Avaluació d\'activitats';
$string['evalcomix:view'] = 'Consulta EvalCOMIX';
$string['evalcomix:edit'] = 'Edició EvalCOMIX';
$string['selinstrument'] = 'Planificació de l\'avaluació';
$string['whatis'] = 'EvalCOMIX permet la creació i gestió d\'instruments d\'avaluació (llistes de control, escales de valoració, diferencial semàntic,  i rúbriques) que poden ser utilitzades per avaluar Forums, Glossaris, Base de Dades, Wiki i Tasques.<br>
L\'avaluació amb aquests instruments creats pot ser realitzada per part del professor (avaluació del professor), pel propi estudiant (autoavaluació) o entre estudiants (avaluació entre iguals).
Per més informació es pot consultar el <a href="' . $CFG->wwwroot.'/lib/evalcomix/manual.pdf">Manual</a>';
$string['selfmod'] = 'Autoavaluació de l\'Estudiant';
$string['peermod'] = 'Avaluació entre Iguals';
$string['teachermod'] = 'Avaluació del Professorat';
$string['selfmodality'] = 'Autoavaluació de l\'Estudiant - AE ';
$string['peermodality'] = 'Avaluació entre Iguals - EI ';
$string['teachermodality'] = 'Avaluació del Professorat - EP';
$string['nuloption'] = 'Seleccioni instrument';
$string['grades'] = 'Notes: ';
$string['pon_EP'] = 'Ponderació - EP';
$string['pon_AE'] = 'Ponderació - AE';
$string['pon_EI'] = 'Ponderació - EI';
$string['availabledate_EI'] = 'EI - disponible a partir de: ';
$string['availabledate_AE'] = 'AE - disponible a partir de: ';
$string['ratingsforitem'] = 'Desglosament de la Qualificació';
$string['modality'] = 'Modalitat';
$string['grade'] = 'Qualificació';
$string['weighingfinalgrade'] = 'Pes en la nota final';
$string['finalgrade'] = 'Nota Final';
$string['nograde'] = 'Sense qualificar';
$string['timeopen'] = 'Període de qualificació obert';
$string['designsection'] = 'Disseny i Gestió d\'Instruments d\'Avaluació';
$string['assesssection'] = 'Avaluar Activitats';
$string['counttool'] = 'Nº total d\'instruments';
$string['newtool'] = 'Nou Instrument';
$string['open'] = 'Obrir';
$string['view'] = 'Consultar';
$string['delete'] = 'Eliminar';
$string['title'] = 'Títol';
$string['type'] = 'Tipus';
$string['anonymous_EI'] = 'Anònima - EI';
$string['details'] = 'Detalls';
$string['assess'] = 'Avaluar';
$string['set'] = 'Configurar';
$string['grade'] = 'Nota';
$string['evalcomixgrade'] = 'Qualificació d\'EvalCOMIX';
$string['moodlegrade'] = 'Qualificació de Moodle';
$string['nograde'] = 'Sense Qualificar';
$string['graphics'] = 'Gràfics';
$string['timedue_AE'] = 'AE - Fecha límit';
$string['timedue_EI'] = 'EI - Fecha límit';
$string['january'] = 'Gener';
$string['february'] = 'Febrer';
$string['march'] = 'Març';
$string['april'] = 'Abril';
$string['may'] = 'Maig';
$string['june'] = 'Juny';
$string['july'] = 'Juliol';
$string['august'] = 'Agost';
$string['september'] = 'Septembre';
$string['october'] = 'Octubre';
$string['november'] = 'Novembre';
$string['december'] = 'Decembre';
$string['save'] = 'Desar';
$string['cancel'] = 'Cancelar';
$string['evaluate'] = 'Avaluar';
$string['scale'] = 'Escala';
$string['list'] = 'Llista de Control';
$string['listscale'] = 'Llista + Escala';
$string['rubric'] = 'Rúbrica';
$string['mixed'] = 'Mixte';
$string['differential'] = 'Diferencial';
$string['argumentset'] = 'Argumentari';

$string['whatis'] = 'Gestió d\'instruments d\'avaluació';
$string['gradeof'] = 'Nota de ';
$string['confirmdeletetool'] = 'Esteu segur que voleu eliminar l\'instrument?';
/* ----------------------------- HELP ----------------------------- */
$string['timeopen_help'] = 'L\'Avaliació ente Iguals no s\'inclou en la nota actual d\'EvalCOMIX ja que encara es troba en període d\'avaluació.';
$string['evalcomixgrade_help'] = 'Mitja ponderada de les qualificacions d\'EvalCOMIX';
$string['moodlegrade_help'] = 'Mitja ponderada de les qualificacions d\'EvalCOMIX';
$string['finalgrade_help'] = 'Mitja ponderada de les qualificacions final d\'EvalCOMIX i la qualificació final de Moodle';
$string['teachermodality_help'] = 'Aquest serà l\'instrument d\'avaluació utilitzat pel professor per qualificar aquesta activitat als seus alumnes.';
$string['pon_EP_help'] = 'És el percentatge de la qualificació obtinguda per l\'instrument d\'avaluació del professor té sobre la nota final.';
$string['selfmodality_help'] = 'Aquest serà l\'instrument d\'autoavaluació utilitzat per l\'estudiant per qualificar el seu treball realitzat en aquesta activitat.';
$string['pon_AE_help'] = 'És el percentatge que la qualificació obtinguda per l\'instrument d\'autoavaluació de l\'estudiant té sobre la nota final.';
$string['peermodality_help'] = 'Aquest serà l\'instrument d\'avaluació utilitzat pels alumnes per avaluar aquesta activitat als seus companys.';
$string['pon_EI_help'] = 'És el percentatge que la qualificació obtinguda per l\'instrument d\'avaluació entre iguals té sobre la nota final.';
$string['availabledate_AE_help'] = 'Data a partir de la qual els estudiants podran avaluar la seva activitat.';
$string['timedue_AE_help'] = 'Data límit fins la qual els estudiants podran avaluar la seva activitat.';
$string['availabledate_EI_help'] = 'Data a partir de la qual els estudiants podran avaluar l\'activitat realitzada pels seus companys.';
$string['timedue_EI_help'] = 'Data límit fins la qual els estudiants podran avaluar l\'activitat realitzada pels seus companys.';
$string['anonymous_EI_help'] = 'Indica si els estudiants podran saber que companys els han qualificat.';
$string['whatis_help'] = 'EvalCOMIX permet la creació i gestió d\'instruments d\'avaluació (llistes de control, escales de valoració, diferencial semàntic,  i rúbriques) que poden ser utilitzades per avaluar Forums, Glossaris, Base de Dades, Wiki i Tasques.<br>
L\'avaluació amb aquests instruments creats pot ser realitzada per part del professor (avaluació del professor), pel propi estudiant (autoavaluació) o entre estudiants (avaluació entre iguals).
Per més informació es pot consultar el <a href="../manual.pdf">Manual</a>';
$string['selinstrument_help'] = 'Consulti el <a href="../manual.pdf">Manual</a> per més informació sobre com configurar una activitat d\'EvalCOMIX.';
/* --------------------------- END HELP --------------------------- */
$string['profile_task_by_student'] = 'Gràfica tarea per alumne';
$string['profile_task_by_group'] = 'Gràfica tarea per grup';
$string['profile_task_by_course'] = 'Gràfica tarea per classe';
$string['profile_student_by_teacher'] = 'Gràfica alumnat entre professors';
$string['profile_student_by_group'] = 'Gràfica alumnat entre iguals';
$string['profile_attribute_by_student'] = 'Gràfica atribut per alumne';
$string['profile_attribute_by_group'] = 'Gràfica atribut per grup';
$string['profile_attribute_by_course'] = 'Gràfica atribut per classe';
$string['titlegraficbegin'] = 'Inici';
$string['no_datas'] = 'No datas';

$string['linktoactivity'] = 'Clic per veure l\'actividad';
$string['sendgrades'] = 'Enviar notes d\'EvalCOMIX';
$string['deletegrades'] = 'Suprimir notes d\'EvalCOMIX';
$string['updategrades'] = 'Enviar últimes notes';
$string['gradessubmitted'] = 'Qualificacions d\'EvalCOMIX enviadas al Llibre de Qualificacions';
$string['gradesdeleted'] = 'Qualificacions d\'EvalCOMIX suprimides del Llibre de Qualificacions';

$string['confirm_add'] = 'Aquesta operació modificarà el Llibre de Qualificacions de Moodle.\nRealitzarà la Mitja Aritmètica entre les qualificacions de Moodle i EvalCOMIX.\nConfirma que vol continuar?';
$string['confirm_update'] = 'El llibre de qualificacions ha sigut modificat per EvalCOMIX previament.\nAquesta operació enviarà les qualificacions d\'EvalCOMIX que encara no estan al llibre de qualificacione.\nConfirma que vol continuar?';
$string['confirm_delete'] = 'Aquesta operació modificarà el Llibre de Qualificacions de Moodle.\nSuprimirà les qualificacions d\'EvalCOMIX del llibre de qualificacions de Moodle.\nConfirma que vol continuar?';
$string['noconfigured'] = 'Sense configurar';
$string['gradebook'] = 'Llibre de Qualificacions';

$string['poweredby'] = 'Desenvolupat per:<br>Grup de Recerca <br><span style="font-weight:bold; font-size: 10pt">EVALfor</span>';

$string['alertnotools'] = 'Encara no s\'han generat instruments d\'avaluación. Per crearl-os, ha d\'accedir a la següent secció';
$string['alertjavascript'] = 'Pel correcte funcionament d\'EvalCOMIX activi Javascript al seu navegador';
$string['studentwork1'] = 'Treball realitzat per l\'alumne';
$string['studentwork2'] = ' en l\'activitat ';

$string['reportsection'] = "Generació d'informes";
$string['notaskconfigured'] = 'No hi ha activitats configurades amb';
$string['studendtincluded'] = 'Estudiants Autoevaluados a incloure';
$string['selfitemincluded'] = "Items d'Autoavaluació a incloure";
$string['selftask'] = 'Informe detallat de Autoavaluacions';
$string['format'] = 'Format';
$string['export'] = 'Exportar';
$string['xls'] = 'Excel';
$string['excelexport'] = 'Exporta a full de càlcul Excel';
$string['selectallany'] = 'Seleccionar tots / cap';
$string['nostudentselfassessed'] = 'No hi ha estudiants autoevaluados';

$string['alwaysvisible_EI_help'] = 'Si está desmarcado, los estudiantes sólo podrán ver las evaluaciones de sus compañeros una vez que finalice la Fecha Límite. Si está marcado, los estudiantes podrán consultar en todo momento las evaluaciones de sus compañeros';
$string['alwaysvisible_EI'] = 'Siempre visible';

// Graphics.
$string['taskgraphic'] = 'Gràfica Activitat';
$string['studentgraphic'] = 'Gràfica Alumnat';
$string['activity'] = 'Activitat';
$string['selectactivity'] = 'Seleccioneu una activitat';
$string['selectstudent'] = 'Seleccioneu un alumne / a';
$string['selectgroup'] = 'Seleccioneu un grup';
$string['studentmod'] = 'Alumne';
$string['groupmod'] = 'Grup';
$string['classmod'] = 'Classe';
$string['nostudents'] = 'No hi ha dades per a aquesta activitat';
$string['nostudentsgroup'] = 'No hi ha dades en el grup seleccionat';