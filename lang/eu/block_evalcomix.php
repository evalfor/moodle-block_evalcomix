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

// Tool editor.
$string['accept'] = 'Onartu';
$string['selecttool'] = 'Sortu beharreko tresna mota aukeratu';
$string['alertdimension'] = 'Dimentsio bat egon behar du gutxienez';
$string['alertsubdimension'] = 'Azpi-dimentsio bat egon behar du gutxienez';
$string['alertatrib'] = 'Atributu bat egon behar du gutxienez';
$string['rubricremember'] = 'GOGOAN IZAN: Ezin dira egon balore ERREPIKATUAK';
$string['importfile'] = 'Fitxategia inportatu';
$string['noatrib'] = 'Atributu negatiboa';
$string['yesatrib'] = 'Atributu positiboa';

$string['comments'] = 'Oharrak';
$string['grade'] = 'Kalifikazioa';
$string['nograde'] = 'Kalifikaziorik gabe';
$string['alertsave'] = 'Ebaluazioa behar bezala gordeta. Nahi izanez gero, leihoa itxi dezakezu';

$string['add_comments'] = 'Oharrak aktibatu';
$string['checklist'] = 'Kontrol zerrenda';
$string['ratescale'] = 'Balorazio eskala';
$string['listrate'] = 'Kontrol zerrenda + Balorazio eskala';
$string['rubric'] = 'Errubrika';
$string['differentail'] = 'Diferentzial semantikoa';
$string['mix'] = 'Tresna mistoa';
$string['argument'] = 'Ebaluaziorako argudio zerrenda';
$string['import'] = 'Inportatu';
$string['numdimensions'] = 'Dimentsio kopurua';
$string['numvalues'] = 'Balio kopurua';
$string['totalvalue'] = 'Balorazio orokorra';
$string['dimension'] = 'Dimentsioa';
$string['subdimension'] = 'Azpi-dimentsioa';
$string['numsubdimension'] = 'Dimentsio kopurua';
$string['numattributes'] = 'Atributu kopurua';
$string['attribute'] = 'Atributuak';
$string['porvalue'] = 'Balioa ehunekoetan';
$string['value'] = 'Balioa';
$string['values'] = 'Balioak';
$string['globalvalue'] = 'DIMENTSIOAREN BALORAZIO OROKORRA:';
$string['novalue'] = 'Balio negatiboa';
$string['yesvalue'] = 'Balio positiboa';
$string['idea'] = 'IDEIA ETA ZUZENDARITZA';
$string['design'] = 'DISEINUA';
$string['develop'] = 'GARAPENA';
$string['translation'] = 'ITZULPENA';
$string['colaboration'] = 'LANKIDETZA';
$string['license'] = 'LIZENTZIA';
$string['addtool'] = 'Tresna bat gehitu';
$string['title'] = 'Izenburua';
$string['titledim'] = 'Dimentsioa';
$string['titlesubdim'] = 'Azpi-dimentsioa';
$string['titleatrib'] = 'Atributua';
$string['titlevalue'] = 'Balioa';
$string['no'] = 'Ez';
$string['yes'] = 'Bai';
$string['observation'] = 'Oharrak';
$string['view'] = 'Aurre bistaratzea itxi';

$string['windowselection'] = 'Aukeraketarako leihoa';
$string['selectfile'] = 'Fitxategia aukeratu';
$string['upfile'] = 'Fitxategia igo';
$string['cancel'] = 'Ezeztatu';

$string['savedsaccessfully'] = 'Tresna behar bezala gorde da';
$string['ADimension'] = 'Eremu hau ezin daiteke hutsik egon. \"Dimentsio kopurua\" 0 baino handiagoa den zenbakia izan behar du eta \"Balorazio orokorra\" 2ren berdin edo handiagoa den zenbaki bat';
$string['ATotal'] = 'Eremu hau ezin daiteke hutsik egon. \"Balio kopurua\" 2ren berdin edo handiagoa den zenbaki bat izan behar du';
$string['ASubdimension'] = 'Eremu hau ezin daiteke hutsik egon. \"Azpi-dimentsio kopurua\" 0 baino handiagoa den zenbakia izan behar du eta \"Balio kopurua\" 2ren berdin edo handiagoa';
$string['AAttribute'] = 'Eremu hau ezin daiteke hutsik egon. Mesedez, 0 baino handiagoa den zenbaki bat adierazi';
$string['ADiferencial'] = '\"Atributu kopurua\" 0 baino handiagoa izan behar du. \"Balio kopurua\" BAKOITIA izan behar du';
$string['ErrorFormato'] = 'Fitxategia hutsik dago edo formatua desegokia da';
$string['ErrorAcceso'] = 'Tresnan ezin izan da sartu';
$string['ErrorExtension'] = 'Formatu desegokia. Luzapena \"evx\" izan behar du';
$string['ErrorSaveTitle'] = 'Error: Izenburua ezin da hutsik egon';
$string['ErrorSaveTools'] = 'Errorea: bat, gutxienez, tresna bat aukeratu behar duzu';

$string['TSave'] = 'Gorde';
$string['TImport'] = 'Inportatu';
$string['TExport'] = 'Esportatu';
$string['TAumentar'] = 'Testu tamaina handiagotu';
$string['TDisminuir'] = 'Testu tamaina txikiagotu';
$string['TView'] = 'Aurre bistaratzea';
$string['TPrint'] = 'Inprimatu';
$string['THelp'] = 'Laguntza';
$string['TAbout'] = '-i buruz';

$string['mixed_por'] = 'Pisua';

$string['handlerofco'] = 'Trebetasunen kudeaketa eta ikaskuntzaren emaitzak';
$string['competencies'] = 'Gaitasunak';
$string['outcomes'] = 'Ikaskuntzaren emaitzak';
$string['compidnumber'] = 'Kodea';
$string['compshortname'] = 'Izen laburra';
$string['compdescription'] = 'Deskribapena';
$string['comptypes'] = 'Lehiaketa motak';
$string['comptype'] = 'Lehiaketa mota';
$string['newcomp'] = ' Konposizio berria ';
$string['newoutcome'] = 'Ikaskuntzaren emaitza berria';
$string['newcomptype'] = 'Konpen mota berria';
$string['compreport'] = 'Garapen txostena';
$string['compandout'] = 'Konpetentziak eta ikaskuntzaren emaitzak';
$string['uploadcompetencies'] = 'Inportatu gaitasunak eta emaitzak';
$string['uploadcompetencies_help'] = 'Gaitasunak eta ikaskuntza-emaitzak testu-fitxategi baten bidez igo daitezke. Fitxategiaren formatuak honako hau izan behar du:

* Fitxategiko lerro bakoitzak erregistro bat dauka
* Erregistro bakoitza komaz (edo beste mugatzaile batzuekin) bereizitako datu sorta bat da
* Lehenengo erregistroak gainerako fitxategiaren formatua definitzen duten eremu-izenen zerrenda dauka
* Beharrezko eremuen izenak idzenbakia, izen laburra, emaitza dira';
$string['idnumberduplicate'] = 'Bikoiztu ID zenbakiaren balioa';
$string['invalidoutcome'] = 'Emaitza balio baliogabea. 0 edo 1 izan behar du';
$string['invalididnumberupload'] = 'ID-zenbakiaren balio baliogabea. Tamainak 100 baino txikiagoa izan behar du';
$string['missingidnumber'] = 'Zutabearen ID zenbakia falta da';
$string['missingshortname'] = 'Zutabearen izen laburra falta da';
$string['missingoutcome'] = 'Emaitza zutabea falta da';
$string['ignored'] = 'Ez ikusi egin da';
$string['errors'] = 'Akatsak';
$string['importresult'] = 'Inportatu emaitzak';
$string['uploadcompetenciespreview'] = 'Igotako gaitasunen aurrebista';
$string['choicecompetency'] = 'Aukeratu lehiaketa bat';
$string['choiceoutcome'] = 'Aukeratu emaitza bat';
$string['associatecompandout'] = 'Lotu gaitasunak eta emaitzak';
$string['allstudens'] = 'Ikasle guztiak';
$string['onestudent'] = 'Ikasle espezifikoa';
$string['onegroup'] = 'Talde zehatza';
$string['evaluationandreports'] = 'Ebaluazioa eta txostenak';
$string['workteams'] = 'Lan-taldeak';
$string['workteamsassessments'] = 'Lan-taldeen ebaluazioa';
$string['assignteamcoordinators'] = 'Taldeko koordinatzaileak esleitu';
$string['workteamsassessments_help'] = 'Aukera hau aktibatzen baduzu, taldearen ordezkari izateko koordinatzaile bat izendatzeko aukera izango duzu.

**Irakasleen Ebaluazioa - EP** badago, irakasleek koordinatzaileak soilik ebaluatu ahal izango dituzte eta ebaluazio hori bere taldeko kide bakoitzari esleituko zaio.

**Autoebaluazioa – ​​AE** badago, koordinatzaileak bakarrik egin dezake autoebaluazioa eta bere ebaluazioa bere taldeko kide bakoitzari esleituko zaio.

**Berdinen arteko ebaluazioa – ​​EI** badago, ikasleek talde bakoitzeko koordinatzaileak soilik ebaluatu ahal izango dituzte eta ebaluazio bakoitza taldeko kide bakoitzari esleituko zaio.

Edozein taldetan ez dauden ikasleek ez dute ebaluaziorik jasoko. Koordinatzaile izendaturik ez duten taldeek ez dute inolako ebaluaziorik jasoko eta ezin izango dute ebaluatu.';
$string['selectcoordinator'] = 'aukeratu koordinatzailea';
$string['alertnogroup'] = 'Ikastaroan oraindik ez da talderik sortu. Horiek sortzeko hurrengo atalera sartu behar duzu:';
$string['activityassessed'] = 'Desgaituta dago ikasle bat dagoeneko proba egin duelako';
$string['coordinatorassessed'] = 'Gaur egun, badira dagoeneko ebaluazioren bat jaso duten koordinatzaileak. Ebaluazioa jaso dutenak ezin dira ordezkatu.';
$string['confirmdisabledworkteams'] = 'Jarduera honetan ebaluazioak egin dira dagoeneko. Aukera hau desgaitzen baduzu eta aldaketak gordetzen badituzu, ebaluazio horiek guztiak ezabatu egingo dira eta ezin izango dira berreskuratu. Ziur aukera desgaitu nahi duzula?';
