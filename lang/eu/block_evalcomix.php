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

$string['pluginname'] = 'EvalCOMIX';
$string['evalcomix'] = 'EvalCOMIX';
$string['blocksettings'] = 'Konfigurazioa';
$string['blockstring'] = 'EvalCOMIXen edukia';
$string['instruments'] = 'Tresnen kudeaketa';
$string['evaluation'] = 'Jardueren ebaluazioa';
$string['evalcomix:view'] = 'EvalCOMIX kontsulta';
$string['evalcomix:edit'] = 'EvalCOMIX argitalpena';
$string['whatis'] = 'EvalCOMIXek, foro, glosario, datu-base, Wiki eta zereginak ebaluatzeko tresnen (kontrol zerrendak, balorazio eskalak, diferentzial semantikoa, eta errubrikak)sormena eta kudeaketa ahalbidetzen ditu. Tresna hauekin sortutako ebaluazioa, irakasleak (irakaslearen ebaluazioa), ikasle berak (auto-ebaluazioa) edo ikasleen artean (berdinen arteko ebaluazioa) burutu daiteke. Informazio gehiagorako <a href="' . $CFG->wwwroot.'/lib/evalcomix/manual.pdf">Manual</a> kontsulta daiteke';
$string['selfmod'] = 'Ikaslearen Autoebaluazioa';
$string['peermod'] = 'Berdinen arteko Ebaluazioa';
$string['teachermod'] = 'Irakasleriaren Ebaluazioa';
$string['selfmodality'] = 'Ikaslearen Autoebaluazioa - IA';
$string['peermodality'] = 'Berdinen arteko Ebaluazioa - BE';
$string['teachermodality'] = 'Irakasleriaren Ebaluazioa - IE';
$string['nuloption'] = 'Tresna aukeratu';
$string['grades'] = 'Oharrak: ';
$string['selinstrument'] = 'Ebaluazioaren plangintza';
$string['pon_EP'] = 'Haztapen - IE';
$string['pon_AE'] = 'Haztapen - IA';
$string['pon_EI'] = 'Haztapen - BE';
$string['availabledate_EI'] = 'BE - -tik disponigarria';
$string['availabledate_AE'] = 'IA - -tik disponigarria';
$string['ratingsforitem'] = 'Kalifikazioaren xehatzea';
$string['modality'] = 'Modalitatea';
$string['grade'] = 'Kalifikazioa';
$string['weighingfinalgrade'] = 'Azken kalifikazioan pisua';
$string['finalgrade'] = 'Azken kalifikazioa';
$string['nograde'] = 'Kalifikatu gabe';
$string['timeopen'] = 'Kalifikazioaldi irekia';
$string['designsection'] = 'Ebaluazio-Tresnen Diseinu eta Kudeaketa';
$string['assesssection'] = 'Jarduerak ebaluatu';
$string['counttool'] = 'Tresnen kopuru guztira';
$string['newtool'] = 'Tresna berria';
$string['open'] = 'Ireki';
$string['view'] = 'Kontsultatu';
$string['delete'] = 'Deuseztatu';
$string['title'] = 'Izenburua';
$string['type'] = 'Mota';
$string['anonymous_EI'] = 'Izengabekoa - BA';
$string['details'] = 'Xehetasunak';
$string['assess'] = 'Ebaluatu';
$string['set'] = 'Konfiguratu';
$string['grade'] = 'Kalifikazioa';
$string['weighingfinalgrade'] = 'Azken kalifikazioan pisua';
$string['evalcomixgrade'] = 'EvalCOMIXen kalifikazioa';
$string['moodlegrade'] = 'Moodleren kalifikazioa';
$string['nograde'] = 'Kalifikatu gabe';
$string['graphics'] = 'Grafikoak';
$string['timedue_AE'] = 'IA – Muga-data';
$string['timedue_EI'] = 'BA - Muga-data';
$string['january'] = 'Urtarrila';
$string['february'] = 'Otsaila';
$string['march'] = 'Martxoa';
$string['april'] = 'Apirila';
$string['may'] = 'Maiatza';
$string['june'] = 'Ekaina';
$string['july'] = 'Uztaila';
$string['august'] = 'Abuztua';
$string['september'] = 'Iraila';
$string['october'] = 'Urria';
$string['november'] = 'Azaroa';
$string['december'] = 'Abendua';
$string['save'] = 'Gorde';
$string['cancel'] = 'Indargabetu';
$string['evaluate'] = 'Ebaluatu';
$string['scale'] = 'Eskala';
$string['list'] = 'Kontrol zerrenda';
$string['listscale'] = 'Zerrenda + Eskala';
$string['rubric'] = 'Errubrika';
$string['mixed'] = 'Mistoa';
$string['differential'] = 'Diferentziala';
$string['argumentset'] = 'Argudioen bilduma ';

$string['whatis'] = 'Ebaluazio- tresnen kudeaketa';
$string['gradeof'] = ' -ren kalifikazioa ';
$string['confirmdeletetool'] = 'Ziur zaude tresna ezabatu nahi duzula?';
$string['confirmdeleteassessment'] = 'Ziur zaude ebaluazioa ezabatu nahi duzula?';

/* ----------------------------- HELP ----------------------------- */
$string['timeopen_help'] = 'Berdinen arteko Ebaluazioa, EvalCOMIXen egungo kalifikazioan ez da sartzen, oraindik ebaluazio-aldian dago eta.';
$string['evalcomixgrade_help'] = 'EvalCOMIXeko kalifikazioen batezbesteko haztatua';
$string['moodlegrade_help'] = 'Moodleko kalifikazioen batezbesteko haztatua';
$string['finalgrade_help'] = 'EvalCOMIXeko azken kalifikazioaren batezbesteko aritmetikoa eta Moodlen azken kalifikazioa';
$string['teachermodality_help'] = 'Hau, jarduera hau ikasleei kalifikatzeko irakasleak erabiliko duen ebaluazio-tresna izango da.';
$string['pon_EP_help'] = 'Azken kalifikazioaren gainean, irakaslearen ebaluazio-tresnak lortutako kalifikazioaren ehunekoa da.';
$string['selfmodality_help'] = 'Hau, jarduera honetan burututako lana kalifikatzeko ikasleak erabilitako auto-ebaluaziorako tresna izango da';
$string['pon_AE_help'] = 'Azken kalifikazioaren gainean, ikaslearen auto-ebaluaziorako tresnak lortutako kalifikazioaren ehunekoa da';
$string['peermodality_help'] = 'Hau, kideei burututako jarduera ebaluatzeko ikasleak erabilitako ebaluaziorako tresna izango da';
$string['pon_EI_help'] = 'Azken kalifikazioaren gainean, berdinen arteko ebaluazio-tresnak lortutako kalifikazioaren ehunekoa da';
$string['availabledate_AE_help'] = 'Ikasleek, beren jarduera ebaluatu ahal izaten hasteko data.';
$string['timedue_AE_help'] = 'Ikasleek, beren jarduera ebaluatu ahal izateko azken data. ';
$string['availabledate_EI_help'] = 'Ikasleek, beren kideek burututako jarduera ebaluatu ahal izaten hasteko data.';
$string['timedue_EI_help'] = 'Ikasleek, beren kideak  ebaluatu ahal izateko azken data.';
$string['anonymous_EI_help'] = 'Adierazi, ikasleek jakin ahalko duten zein kideengatik izan diren kalifikatuak.';
$string['whatis_help'] = 'EvalCOMIXek, foro, glosario, datu-base, Wiki eta zereginak ebaluatzeko tresnen (kontrol zerrendak, balorazio eskalak, diferentzial semantikoa, eta errubrikak)sormena eta kudeaketa.<br>Tresna hauekin sortutako ebaluazioa, irakasleak (irakaslearen ebaluazioa), ikasle berak (auto-ebaluazioa) edo ikasleen artean (berdinen arteko ebaluazioa) burutu daiteke. Informazio gehiagorako <a href="../manual.pdf">Manual</a> kontsulta daiteke';
$string['selinstrument_help'] = 'EvalCOMIXeko jarduera bat konfiguratzeko informazio gehiagorako, <a href="../manual.pdf">Manual</a> kontsultatu';
/* --------------------------- END HELP --------------------------- */
$string['profile_task_by_student'] = 'Ikasleko jarduera-irudia';
$string['profile_task_by_group'] = 'Taldeko jarduera-irudia';
$string['profile_task_by_course'] = 'Gelako jarduera-irudia';
$string['profile_student_by_teacher'] = 'Irakasleko ikasle-irudia';
$string['profile_student_by_group'] = 'Berdinen arteko ikasle-irudia';
$string['profile_attribute_by_student'] = 'Ikasleko atributu-irudia';
$string['profile_attribute_by_group'] = 'Taldeko atributu-irudia';
$string['profile_attribute_by_course'] = 'Gelako atributu-irudia';
$string['titlegraficbegin'] = 'Hasiera';
$string['no_datas'] = 'No datas';

$string['linktoactivity'] = 'Jarduera ikusteko klik';
$string['sendgrades'] = 'EvalCOMIXeko kalifikazioak bidali';
$string['deletegrades'] = 'EvalCOMIXeko kalifikazioak ezeztatu';
$string['updategrades'] = 'Azken kalifikazioak bidali';
$string['gradessubmitted'] = 'EvalCOMIXeko kalifikazioak, kalifikazio-liburua bidaliak';
$string['gradesdeleted'] = 'EvalCOMIXeko kalifikazioak, kalifikazio-liburutik ezeztatu';

$string['confirm_add'] = 'Eragiketa honek Moodleren kalifikazio-liburua aldatuko du.\nMoodle eta EvalCOMIXen arteko kalifikazioak batezbesteko aritmetikoa egingo du.\n¿jarraitu nahi duzula berresten al duzu?';
$string['confirm_update'] = 'Aurretik, EvalCOMIXek aldatu du kalifikazio-liburua.\nEragiketa honek, kalifikazio-liburuan oraindik islatzen ez diren EvalCOMIXeko kalifikazioak bidaliko ditu.\n¿jarraitu nahi duzula berresten al duzu?';
$string['confirm_delete'] = 'Eragiketa honek Moodleren kalifikazio-liburua aldatuko du.n\EvalCOMIXeko kalifikazioak ezeztatu ditu Moodleren kalifikazio-liburutik.\n¿jarraitu nahi duzula berresten al duzu? ';
$string['noconfigured'] = 'Konfiguratu gabe';
$string['gradebook'] = 'Kalifikazio-liburua';

$string['poweredby'] = 'Garatu duena:<br>Ikerketa-taldea <br><span style="font-weight:bold; font-size: 10pt">EVALfor</span>';

$string['alertnotools'] = 'Oraindik ez dira sortu ebaluazio-tresnak.Sortzeko, ondoko atalera jo behar duzu';
$string['alertjavascript'] = 'EvalCOMIXen funtzionamendu zuzenerako, Javascript aktibatu behar duzu zure nabigatzailean.';
$string['studentwork1'] = 'Ikasleak egindako lana';
$string['studentwork2'] = 'jardueran';

$string['reportsection'] = 'Txostenak';
$string['notaskconfigured'] = 'No hay actividades configuradas con';
$string['studendtincluded'] = 'Estudiantes Autoevaluados a  incluir';
$string['selfitemincluded'] = 'Items de Autoevaluación a incluir';
$string['selftask'] = 'Informe detallado de Autoevaluaciones';
$string['format'] = 'Formatuan';
$string['export'] = 'Esportatu';
$string['xls'] = 'Excel';
$string['excelexport'] = 'Exportar a Hoja de Cálculo Excel';
$string['selectallany'] = 'hautatu / none guztiak';
$string['nostudentselfassessed'] = 'There is not assessed student';

$string['alwaysvisible_EI_help'] = 'Si está desmarcado, los estudiantes sólo podrán ver las evaluaciones de sus compañeros una vez que finalice la Fecha Límite. Si está marcado, los estudiantes podrán consultar en todo momento las evaluaciones de sus compañeros';
$string['alwaysvisible_EI'] = 'Siempre visible';

// Graphics.
$string['taskgraphic'] = 'Jarduera-grafikoa';
$string['studentgraphic'] = 'Ikasle grafikoa';
$string['activity'] = 'Jarduera';
$string['selectactivity'] = 'Aukeratu jarduera bat';
$string['selectstudent'] = 'Aukeratu ikasle bat';
$string['selectgroup'] = 'Talde bat aukeratu';
$string['studentmod'] = 'Ikaslea';
$string['groupmod'] = 'Taldea';
$string['classmod'] = 'Klase';
$string['nostudents'] = 'Ez da jarduerarako daturik';
$string['nostudentsgroup'] = 'Ez da datuak hautatutako taldean';
