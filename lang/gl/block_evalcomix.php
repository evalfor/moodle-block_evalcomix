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
$string['blocksettings'] = 'Configuración';
$string['blockstring'] = 'Contido de EvalCOMIX';
$string['instruments'] = 'Xestión de instrumentos';
$string['evaluation'] = 'Avaliación de actividades';
$string['evalcomix:view'] = 'Consulta EvalCOMIX';
$string['evalcomix:edit'] = 'Edición EvalCOMIX';
$string['whatis'] = 'EvalCOMIX permite crear e xestionar instrumentos de avaliación (listaxes de control, escalas de valoración, diferencial semántico e rúbricas) que poden ser empregados para avaliar Foros, Glosarios, Base de datos, Wiki e Tarefas. <br>A avaliación con estes instrumentos pode ser realizada polo persoal docente (avaliación do profesorado) e o propio estudantado (autoavaliación), amais de ter lugar entre estudantes (avaliación entre iguais). Para obter unha maior información pódese consultar o<a href="' . $CFG->wwwroot.'/lib/evalcomix/manual.pdf">Manual</a>.';
$string['selfmodality'] = 'Autoavaliación do/a Estudante - AE ';
$string['peermodality'] = 'Avaliación entre Iguais - AI ';
$string['teachermodality'] = 'Avaliación do Profesorado - AP';
$string['selfmod'] = 'Autoavaliación';
$string['peermod'] = 'Avaliación entre Iguais';
$string['teachermod'] = 'Avaliación do Profesorado';
$string['nuloption'] = 'Seleccione un instrumento';
$string['grades'] = 'Notas: ';
$string['selinstrument'] = 'Planificación da Avaliación';
$string['pon_EP'] = 'Ponderación - AP';
$string['pon_AE'] = 'Ponderación - AE';
$string['pon_EI'] = 'Ponderación - AI';
$string['availabledate_EI'] = 'AI - dispoñible a partir de: ';
$string['availabledate_AE'] = 'AE - dispoñible a partir de: ';
$string['ratingsforitem'] = 'Desagregación da Cualificación';
$string['modality'] = 'Modalidade';
$string['grade'] = 'Cualificación';
$string['weighingfinalgrade'] = 'Peso na nota final';
$string['finalgrade'] = 'Nota Final';
$string['nograde'] = 'Sen cualificar';
$string['timeopen'] = 'Período de cualificación aberto';
$string['designsection'] = 'Deseño e Xestión de Instrumentos de Avaliación';
$string['assesssection'] = 'Avaliar Actividades';
$string['counttool'] = 'Nº total de instrumentos';
$string['newtool'] = 'Novo Instrumento';
$string['open'] = 'Abrir';
$string['view'] = 'Consultar';
$string['delete'] = 'Eliminar';
$string['title'] = 'Título';
$string['type'] = 'Tipo';
$string['anonymous_EI'] = 'Anónima - AI';
$string['details'] = 'Detalles';
$string['assess'] = 'Avaliar';
$string['set'] = 'Configurar';
$string['grade'] = 'Nota';
$string['weighingfinalgrade'] = 'Peso na nota final';
$string['evalcomixgrade'] = 'Cualificación de EvalCOMIX';
$string['moodlegrade'] = 'Cualificación de Moodle';
$string['nograde'] = 'Sen Cualificar';
$string['graphics'] = 'Gráficos';
$string['timedue_AE'] = 'AE - Data límite';
$string['timedue_EI'] = 'AI - Data límite';
$string['january'] = 'Xaneiro';
$string['february'] = 'Febreiro';
$string['march'] = 'Marzo';
$string['april'] = 'Abril';
$string['may'] = 'Maio';
$string['june'] = 'Xuño';
$string['july'] = 'Xullo';
$string['august'] = 'Agosto';
$string['september'] = 'Setembro';
$string['october'] = 'Outubro';
$string['november'] = 'Novembro';
$string['december'] = 'Decembro';
$string['save'] = 'Gardar';
$string['cancel'] = 'Cancelar';
$string['evaluate'] = 'Avaliar';
$string['scale'] = 'Escala';
$string['list'] = 'Listaxe de Control';
$string['listscale'] = 'Listaxe + Escala';
$string['rubric'] = 'Rúbrica';
$string['mixed'] = 'Mixto';
$string['differential'] = 'Diferencial';
$string['argumentset'] = 'Argumentario';

$string['whatis'] = 'Xestión de instrumentos de avaliación';
$string['gradeof'] = 'Nota de ';
$string['confirmdeletetool'] = 'Está seguro/a que desexa eliminar o instrumento?';
$string['finalgradecalculation'] = 'Cálculo da nota final';
$string['method'] = 'Método';
$string['weightedaveragewithallvalues'] = 'Media ponderada de todas as cualificacións';
$string['weightedaveragesmart'] = 'Media ponderada de cualificacións non extremas';
$string['confirmdeleteassessment'] = 'Está seguro/a de que desexa eliminar la avaliación?';

$string['mildoutlier'] = 'Valor atípico leve';
$string['extremeoutlier'] = 'Valor atípico extremo';
$string['overvaluation'] = 'Sobrevaloración';
$string['undervaluation'] = 'Infravaloración';
/* ----------------------------- HELP ----------------------------- */
$string['method_help'] = 'Esta opción permite establecer o xeito de calcular a nota final da actividade. Se se selecciona a opción "Media ponderada de todas as cualificacións", a cualificación final calcularase realizando a media ponderada das cualificacións outorgadas en cada modalidade de avaliación, sen ignorar ningunha. En cambio, de seleccionarse a opción "Media ponderada de valores non extremos", eliminaranse do cálculo aquelas cualificacións que se consideran extremas por superar determinadas cotas. Para obter máis información, lea o manual de EvalCOMIX.';
$string['timeopen_help'] = 'A Avaliación entre Iguais non se inclúe na nota actual de EvalCOMIX xa que aínda se atopa en período de avaliación.';
$string['evalcomixgrade_help'] = 'Media ponderada das cualificacións de EvalCOMIX';
$string['evalcomixgradesmart_help'] = 'Media ponderada das cualificacións de EvalCOMIX ignorando as sobrevaloracións, infravaloracións e valores atípicos extremos.';
$string['moodlegrade_help'] = 'Media ponderada das cualificacións de EvalCOMIX';
$string['finalgrade_help'] = 'Media aritmética da cualificación final de EvalCOMIX e a cualificación final de Moodle';
$string['teachermodality_help'] = 'Velaquí o instrumento de avaliación utilizado polo profesorado para cualificar esta actividade do seu alumnado.';
$string['pon_EP_help'] = 'É a porcentaxe que a cualificación obtida polo instrumento de avaliación do/a profesor/a ten na nota final.';
$string['selfmodality_help'] = 'Velaquí o instrumento de autoavaliación utilizado polo estudantado para cualificar o traballo que realizou nesta actividade.';
$string['pon_AE_help'] = 'É a porcentaxe que a cualificación obtida polo instrumento de autoavaliación do/a estudante ten na nota final.';
$string['peermodality_help'] = 'Velaquí o instrumento de avaliación utilizado polo alumnado para avaliar esta actividade dos compañeiros e compañeiras.';
$string['pon_EI_help'] = 'É a porcentaxe que a cualificación obtida polo instrumento de avaliación entre iguais ten sobre na nota final.';
$string['availabledate_AE_help'] = 'Data a partir da cal os e as estudantes poderán avaliar a súa actividade.';
$string['timedue_AE_help'] = 'Data límite até a cal os e as estudantes poderán avaliar a súa actividade.';
$string['availabledate_EI_help'] = 'Data a partir da cal os e as estudantes poderán avaliar a actividade realizada polos compañeiros e polas compañeiras.';
$string['timedue_EI_help'] = 'Data límite ata a cal os e as estudantes poderán avaliar os compañeiros e compañeiras.';
$string['anonymous_EI_help'] = 'Indica se o estudantado poderá coñecer os compañeiros e compañeiras que o cualificaron.';
$string['whatis_help'] = 'EvalCOMIX permite crear e xestionar instrumentos de avaliación (listaxes de control, escalas de valoración, diferencial semántico e rúbricas) que poden ser empregados para avaliar Foros, Glosarios, Base de datos, Wiki e Tarefas. <br>A avaliación con estes instrumentos pode ser realizada polo persoal docente (avaliación do profesorado) e o propio estudantado (autoavaliación), amais de ter lugar entre estudantes (avaliación entre iguais). Para obter unha maior información pódese consultar o <a href="../manual.pdf">Manual</a>.';
$string['selinstrument_help'] = 'Consulte o <a href="../manual.pdf">Manual</a> para obter unha maior información sobre como configurar unha actividade de EvalCOMIX.';
/* --------------------------- END HELP --------------------------- */
$string['profile_task_by_student'] = 'Gráfica tarefa por alumno/a';
$string['profile_task_by_group'] = 'Gráfica tarefa por grupo';
$string['profile_task_by_course'] = 'Gráfica tarefa por clase';
$string['profile_student_by_teacher'] = 'Gráfica avaliación de profesores/as';
$string['profile_student_by_group'] = 'Gráfica avaliación entre iguais';
$string['profile_attribute_by_student'] = 'Gráfica atributo por alumno/a';
$string['profile_attribute_by_group'] = 'Gráfica atributo por grupo';
$string['profile_attribute_by_course'] = 'Gráfica atributo por clase';
$string['titlegraficbegin'] = 'Inicio';
$string['no_datas'] = 'No hai datos';

$string['linktoactivity'] = 'Prema para ver a actividade';
$string['sendgrades'] = 'Enviar notas de EvalCOMIX';
$string['deletegrades'] = 'Quitar notas de EvalCOMIX';
$string['updategrades'] = 'Enviar últimas notas';
$string['gradessubmitted'] = 'Cualificacións de EvalCOMIX enviadas ao Libro de Cualificacións';
$string['gradesdeleted'] = 'Cualificaciones de EvalCOMIX suprimidas do Libro de Cualificacións';

$string['confirm_add'] = 'Esta operación modificará o Libro de cualificacións de Moodle.\nRealizará a media aritmética entre as cualificacións de Moodle e EvalCOMIX.\nConfirma que desexa continuar?';
$string['confirm_update'] = 'O Libro de cualificacións foi modificado por EvalCOMIX con anterioridade.\nEsta operación enviará as cualificacións de EvalCOMIX que aínda non están reflectidas no Libro de cualificacións.\nConfirma que desexa continuar?';
$string['confirm_delete'] = 'Esta operación modificará o Libro de cualificacións de Moodle.\nSuprimirá as cualificacións de EvalCOMIX do Libro de cualificacións de Moodle.\nConfirma que desexa continuar?';
$string['noconfigured'] = 'Sen Configurar';
$string['gradebook'] = 'Libro de Cualificacións';

$string['poweredby'] = 'Desenvolvido por:<br>Grupo de Investigación <br><span style="font-weight:bold; font-size: 10pt">EVALfor</span>';

$string['alertnotools'] = 'Aínda non se xeraron instrumentos de avaliación. Para crealos, deberá acceder á siguiente sección';
$string['alertjavascript'] = 'Para o correcto funcionamento de EvalCOMIX debe activar Javascript no seu navegador';
$string['studentwork1'] = 'Traballo realizado polo alumno/a';
$string['studentwork2'] = 'na actividade';

$string['reportsection'] = 'Xeración de Informes';
$string['notaskconfigured'] = 'No hai actividades configuradas con';
$string['studendtincluded'] = 'Estudantado Autoavaliado para incluir';
$string['selfitemincluded'] = 'Items de Autoavaliación para incluir';
$string['selftask'] = 'Informe detallado de Autoavaliacións';
$string['format'] = 'Formato';
$string['export'] = 'Exportar';
$string['xls'] = 'Excel';
$string['excelexport'] = 'Exportar a Folla de Cálculo Excel';
$string['selectallany'] = 'Seleccionar todos/ningún';
$string['nostudentselfassessed'] = 'No hai estudantado autoavaliado';

// Settings.
$string['admindescription'] = 'Configure as opcións de EvalCOMIX. Asegúrese de que os datos sexan correctos. En caso contrario, a integración non funcionará';
$string['adminheader'] = 'Configuración de EvalCOMIX';
$string['serverurl'] = 'URL de EvalCOMIX Server:';
$string['serverurlinfo'] = 'Introduza la URL do seu EvalCOMIX Server. Non inclúa ningunha barra oblicua ao final. Ej: http://localhost/evalcomix';
$string['validationheader'] = 'Axustes de validación';
$string['validationinfo'] = 'Por favor, garde a configuración antes de premer o botón de validación. Se a validación non é correcta, volva a comprobar o URL e o token';
$string['validationbutton'] = 'Validar URL';
$string['error_conection'] = 'A validación fallou: por favor, comprobe que o URL se corresponde co URL do servizo web EvalCOMIX e que introduciu correctamente o token';
$string['valid_conection'] = 'Validación levada a cabo con éxito';
$string['simple_error_conection'] = 'URL válido. Pero atopouse o siguiente erro:';
$string['token'] = 'Token';
$string['tokeninfo'] = 'Token xerado por EvalCOMIX Server. Para obtelo, acceda ao panel de control de EvalCOMIX Server e dea de alta este Moodle como LMS permitido.';

$string['alwaysvisible_EI_help'] = 'Se está desmarcado, o estudantado só poderá ver as avaluacións dos compañeiros e das compañeiras unha vez que finalice a Data Límite. De estar marcado, o estudantado poderán consultar as devanditas avaliacións en todo momento';
$string['alwaysvisible_EI'] = 'Sempre visible';
$string['whoassesses_EI'] = 'Quen avalía?';
$string['anystudent_EI'] = 'Calquera compañeiro/a';
$string['groups_EI'] = 'Grupos';
$string['specificstudents_EI'] = 'Estudantes específicos/as';
$string['whoassesses_EI_help'] = 'Esta opción permite controlar o estudantado que participará na avaliación entre iguais.

Si se selecciona la opción **'.$string['anystudent_EI'].'** cada estudante poderá avaluiar a calquera deos seus compañeiros e compañeiras.

Si se selecciona la opción **'.$string['groups_EI'].'**, respectarase a configuración dos grupos e agrupamentos da actividade.

Si se selecciona la opción **'.$string['specificstudents_EI'].'** poderase indicar quen avaliará e quen será avaliado/a. Esta opción desactivarase de seleccionarse "Avaliación de equipos de traballo"';
$string['assignstudents_EI'] = 'Asignar estudantes';
$string['assess_students'] = 'Estudantes avaliadores';
$string['studentstoassess'] = 'Estudantes que serán avaliados/as';
$string['search'] = 'Buscar';
$string['add_delete_student'] = 'Engadir/Eliminar Estudante';
$string['back'] = 'Volver';
$string['potentialstudents'] = 'Estudantes Candidatos/as';

$string['settings'] = 'Axustes';
$string['activities'] = 'Actividades';
$string['edition'] = 'Edición';
$string['settings_description'] = 'Desde este apartado pódense configurar aspectos relacionados coa taboa de avaliacións de EvalCOMIX';

$string['crontask'] = 'Tarefa para a actualización de cualificacións do libro de EvaLCOMIX';

// Graphics.
$string['taskgraphic'] = 'Gráfica Tarefa';
$string['studentgraphic'] = 'Gráfica Alumnado';
$string['activity'] = 'Actividade';
$string['selectactivity'] = 'Seleccione unha actividade';
$string['selectstudent'] = 'Seleccione un/unha alumno/a';
$string['selectgroup'] = 'Seleccione un grupo';
$string['studentmod'] = 'Alumno/a';
$string['groupmod'] = 'Grupo';
$string['classmod'] = 'Clase';
$string['nostudents'] = 'Non hai datos para esta actividade';
$string['nostudentsgroup'] = 'Non hai datos no grupo seleccionado';

$string['toolmanagerviewed'] = 'Xestor de instrumentos visto';
$string['activityassessorviewed'] = 'Avaliador/a de actividades visto';
$string['tooldeleted'] = 'Instrumento de avaliación borrado';
$string['studentassessed'] = 'Estudante avaliado';
$string['graphicsviewed'] = 'Gráficos visto';
$string['configurationviewed'] = 'Axustes visto';

// Tool editor.
$string['accept'] = 'Aceptar';
$string['selecttool'] = 'Elixa o tipo de instrumentos que se creará';
$string['alertdimension'] = 'Debe existir cando menos unha dimensión';
$string['alertsubdimension'] = 'Debe existir cando menos unha subdimensión';
$string['alertatrib'] = 'Debe existir cando menos un atributo';
$string['rubricremember'] = 'LEMBRE: NON deberían existir valores REPETIDOS';
$string['importfile'] = 'Importar Ficheiro';
$string['noatrib'] = 'Atributo Negativo';
$string['yesatrib'] = 'Atributo Positivo';

$string['comments'] = 'Comentarios';
$string['grade'] = 'Cualificación';
$string['nograde'] = 'Sen cualificación';
$string['alertsave'] = 'Avaliación gardada satisfactoriamente. Se o desexa, xa pode pechar a xanela';

$string['add_comments'] = 'Activar comentarios';
$string['checklist'] = 'Listaxe de Control';
$string['ratescale'] = 'Escala de Valoración';
$string['listrate'] = 'Listaxe de Control + Escala de Valoración';
$string['rubric'] = 'Rúbrica';
$string['differentail'] = 'Diferencial Semántico';
$string['mix'] = 'Instrumento Mixto';
$string['argument'] = 'Argumentario da avaliación';
$string['import'] = 'Importar';
$string['numdimensions'] = 'Nº Dimensións:';
$string['numvalues'] = 'Nº de Valores:';
$string['totalvalue'] = 'Valoración Global';
$string['dimension'] = 'Dimensión:';
$string['subdimension'] = 'Subdimensión:';
$string['numsubdimension'] = 'Nº Subdimensións:';
$string['numattributes'] = 'Nº de Atributos:';
$string['attribute'] = 'Atributos';
$string['porvalue'] = 'Valor Porcentual:';
$string['value'] = 'Valor';
$string['values'] = 'Valores';
$string['globalvalue'] = 'VALORACIÓN GLOBAL DA DIMENSIÓN:';
$string['novalue'] = 'Valor Negativo';
$string['yesvalue'] = 'Valor Positivo';
$string['idea'] = 'IDEA E DIRECCIÓN';
$string['design'] = 'DESEÑO';
$string['develop'] = 'DESENVOLVEMENTO';
$string['translation'] = 'TRADUCIÓN';
$string['colaboration'] = 'COLABORAN';
$string['license'] = 'LICENZA';
$string['addtool'] = 'Engadir un Instrumento';
$string['title'] = 'Título';
$string['titledim'] = 'Dimension';
$string['titlesubdim'] = 'Subdimension';
$string['titleatrib'] = 'Atributo';
$string['titlevalue'] = 'Valor';
$string['no'] = 'Non';
$string['yes'] = 'Si';
$string['observation'] = 'Comentarios';
$string['view'] = 'Pechar Vista Previa';

$string['windowselection'] = 'Xanela de selección';
$string['selectfile'] = 'Seleccione o fichero';
$string['upfile'] = 'Subir ficheiro';
$string['cancel'] = 'Cancelar';

$string['savedsaccessfully'] = 'O instrumento gardouse satisfactoriamente';
$string['ADimension'] = 'Este campo non pode estar baleiro. \"Nº de Dimensións\" debe ser un número maior que 0 e \"Valoración Global\" un número maior ou igual que 2';
$string['ATotal'] = 'Este campo non puede estar baleiro. \"Nº de Valores\" debe ser un número igual a 2 ou maior';
$string['ASubdimension'] = 'Este campo non pode estar baleiro. \"Nº Subdimensións\" debe ser un número maior que 0 e \"Nº de Valores\" un número igual a 2 ou maior';
$string['AAttribute'] = 'Este campo non pode estar baleiro. Por favor, especifique un número maior que 0';
$string['ADiferencial'] = '\"Nº de Atributos\" debe ser maior que 0. \"Nº de Valores\" debe ser IMPAR';
$string['ErrorFormato'] = 'O ficheiro está baleiro ou ben o formato é incorrecto';
$string['ErrorAcceso'] = 'Non se puido acceder ao instrumento';
$string['ErrorExtension'] = 'Formato Incorrecto. A extensión debe ser \"evx\"';
$string['ErrorSaveTitle'] = 'Error: O campo Título non pode estar baleiro';
$string['ErrorSaveTools'] = 'Error: Debe seleccionar cando menos un instrumento';

$string['TSave'] = 'Gardar';
$string['TImport'] = 'Importar';
$string['TExport'] = 'Exportar';
$string['TAumentar'] = 'Aumentar o tamaño da fonte';
$string['TDisminuir'] = 'Disminuir o tamaño de fonte';
$string['TView'] = 'Vista Previa';
$string['TPrint'] = 'Imprimir';
$string['THelp'] = 'Axuda';
$string['TAbout'] = 'Acerca de';

$string['mixed_por'] = 'Peso na nota final';

$string['handlerofco'] = 'Xestión de resultados da aprendizaje e competencias';
$string['competencies'] = 'Competencias';
$string['outcomes'] = 'Resultados da aprendizaxe';
$string['compidnumber'] = 'Código';
$string['compshortname'] = 'Nome curto';
$string['compdescription'] = 'Descrición';
$string['comptypes'] = 'Tipos de competencia';
$string['comptype'] = 'Tipo de competencia';
$string['newcomp'] = 'Nova competencia';
$string['newoutcome'] = 'Novo resultado da aprendizaxe';
$string['newcomptype'] = 'Novo tipo de competencia';
$string['compreport'] = 'Informe de desenvolvemento';
$string['compandout'] = 'Competencias e resultados da aprendizaxe';
$string['uploadcompetencies'] = 'Importar competencias e resultados';
$string['uploadcompetencies_help'] = 'As competencias e os resultados da aprendizaxe pódense cargar a través dun arqhivo de texto. O formato do arquivo debe ser o seguinte:

* Cada líña do arqhivo contén un rexistro
* Cada rexistro é unha serie de datos separados por comas (ou outros delimitadores)
* O primeiro rexistro contén unha listaxe de nomes de campo que definen o formato do resto do arqhivo
* Os nomes de campo requeridos son idnumber, shortname, result';
$string['idnumberduplicate'] = 'Valor de idnumber duplicado';
$string['invalidoutcome'] = 'Valor de outcome non válido. Debe ser 0 ou 1';
$string['invalididnumberupload'] = 'Valor de idnumber non válido. O tamaño debe ser menor que 100';
$string['missingidnumber'] = 'Falta a columna idnumber';
$string['missingshortname'] = 'Falta a columna shortname';
$string['missingoutcome'] = 'Falta a columna outcome';
$string['ignored'] = 'Ignorados';
$string['errors'] = 'Erros';
$string['importresult'] = 'Importar resultados';
$string['uploadcompetenciespreview'] = 'Vista preliminar de competencias subidas';
$string['choicecompetency'] = 'Seleccione unha competencia';
$string['choiceoutcome'] = 'Seleccione un resultado';
$string['associatecompandout'] = 'Asociar competencias e resultados';
$string['allstudens'] = 'Todos o estudantado';
$string['onestudent'] = 'Estudante específico/a';
$string['onegroup'] = 'Grupo específico';
$string['selectcomptype'] = 'Seleccione tipo de competencia';
$string['assessmentreport'] = 'Informe de avaliacións';
$string['unrealized'] = 'Non realizada';
$string['doneoutofrange'] = 'Realizou a autoavaliación ou a avaliación entre iguais, pero fóra de rango';
$string['doneoutofrangecomments'] = 'Realizou a autoavaliación ou avaliación entre iguais, fóra de rango e achega comentarios';
$string['donewithinrange'] = 'Realizou a autoavaliación ou a avaliación entre iguais dentro de rango';
$string['donewithinrangecomments'] = 'Realizou a autoavaliación ou avaliación entre iguais dentro de rango e achega comentarios';
$string['threshold'] = 'Limiar de sobrevaloración e infravaloración';
$string['threshold_help'] = 'Límite a partir do cal a puntuación dun/dunha estudante se considera sobrevaloración ou infravaloración. Por exemplo, se o limiar está establecido en 15 e a cualificación do profesorado (ou no seu defecto, a media da avaliación entre iguales) é de 50, daquela considerarase sobrevaloración calquera puntuación do/a estudante que supere os 65 (50+15) puntos e infravaloración as inferiores a 35 (50-15) puntos.';
$string['assessmentreporttotalAE'] = 'Total AE (Sobre 10)';
$string['assessmentreporttotalEI'] = 'Total AI (Sobre 10)';
$string['AE'] = 'AE';
$string['EI'] = 'AI';
$string['evaluationexporthelp'] = 'Informe de resultados de discrepancia entre a autoavaliación e as avaliacións entre iguais respecto a criterio (limiar de sobrevaloración ou infravaloración';
$string['evaluationandreports'] = 'Avaliación e informes';
$string['workteams'] = 'Equipos de traballo';
$string['workteamsassessments'] = 'Avaliación de equipos de traballo';
$string['assignteamcoordinators'] = 'Asignar coordinadores/as de equipo';
$string['workteamsassessments_help'] = 'Se activa esta opción, poderá nomear unha persoa coordinadora que represente o grupo.

Se hai **Avaliación do Profesorado - AP**, o profesorado só poderán avaliar os coordinadores e as coordinadoras, e esa avaliación seralle asignada a cada membro do grupo.

Se hai **Autoavaliación – AE**, só o/a coordinador/a poderá autoavaliarse, e a súa avaliación seralle asignada a cada membro do grupo.

Se hai **Avaliación entre Iguais – AI**, o alumnado só poderán avaliar aos coordinadores e ás coordinadoras de cada grupo, e cada avaliación seralle asignada a cada membro do grupo.

O alumnado que non estea en ningún grupo non recibirá avaliación. Os grupos que non teñan asignada unha persoa coordinadora non recibirán ningunha avaliación e tampouco poderán avaliar.';
$string['selectcoordinator'] = 'Elixe coordinador/a';
$string['alertnogroup'] = 'Ainda non se crearon grupos no curso. Para facelo deberá acceder á siguiente sección:';
$string['activityassessed'] = 'Deshabilitado debido a que xa se avaliou algún/algunha estudante';
$string['coordinatorassessed'] = 'Arestora, hai coordinadores/as que xa recibiron avaliación e, por tanto, non poderán ser substituídos/as. Se desexa cambialos/as, primeiro deberá eliminar as súas avaliacións';
$string['confirmdisabledworkteams'] = 'Nesta actividade xa se levaron a cabo avaliacións. Se desactiva esta opción e garda os cambios, todas esas avaliacións serán eliminadas e non se poderán recuperar. Confirma que desexa desactivar a opción?';
$string['crontaskdevdata'] = 'Tarefa para a descarga de datos do informe de desenvolvemento';
$string['reporttimeleft'] = 'Están a descargarse os datos do informe. Faltan {$a} para a súa descarga completa';
$string['inforeporttime'] = 'Os datos do informe descárganse en segundo plano. Se ao seleccionar un filtro ainda non estivesen dispoñibles, por favor, teña paciencia';
