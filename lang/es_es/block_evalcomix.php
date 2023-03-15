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
$string['blockstring'] = 'Contenido de EvalCOMIX';
$string['instruments'] = 'Gestión de instrumentos';
$string['evaluation'] = 'Evaluación de actividades';
$string['evalcomix:view'] = 'Consulta EvalCOMIX';
$string['evalcomix:edit'] = 'Edición EvalCOMIX';
$string['whatis'] = 'EvalCOMIX permite la creación y gestión de instrumentos de evaluación (listas de control, escalas de valoración, diferencial semántico,  y rúbricas) que pueden ser utilizados para evaluar Foros, Glosarios, Base de Datos, Wiki y Tareas.<br>La evaluación con estos instrumentos creados puede ser realizada por parte del profesor (evaluación del profesor), el propio estudiante (autoevaluación) o entre estudiantes (evaluación entre iguales). Para una mayor información se puede consultar el <a href="' . $CFG->wwwroot.'/lib/evalcomix/manual.pdf">Manual</a>';
$string['selfmodality'] = 'Autoevaluación del Estudiante - AE ';
$string['peermodality'] = 'Evaluación entre Iguales - EI ';
$string['teachermodality'] = 'Evaluación del Profesorado - EP';
$string['selfmod'] = 'Autoevaluación';
$string['peermod'] = 'Evaluación entre Iguales';
$string['teachermod'] = 'Evaluación del Profesor';
$string['nuloption'] = 'Seleccione instrumento';
$string['grades'] = 'Notas: ';
$string['selinstrument'] = 'Planificación de la Evaluación';
$string['pon_EP'] = 'Ponderación - EP';
$string['pon_AE'] = 'Ponderación - AE';
$string['pon_EI'] = 'Ponderación - EI';
$string['availabledate_EI'] = 'EI - disponible a partir de: ';
$string['availabledate_AE'] = 'AE - disponible a partir de: ';
$string['ratingsforitem'] = 'Desglose de la Calificación';
$string['modality'] = 'Modalidad';
$string['grade'] = 'Calificación';
$string['weighingfinalgrade'] = 'Peso en la nota final';
$string['finalgrade'] = 'Nota Final';
$string['nograde'] = 'Sin calificar';
$string['timeopen'] = 'Período de calificación abierto';
$string['designsection'] = 'Diseño y Gestión de Instrumentos de Evaluación';
$string['assesssection'] = 'Evaluar Actividades';
$string['counttool'] = 'Nº total de instrumentos';
$string['newtool'] = 'Nuevo Instrumento';
$string['open'] = 'Abrir';
$string['view'] = 'Consultar';
$string['delete'] = 'Eliminar';
$string['title'] = 'Título';
$string['type'] = 'Tipo';
$string['anonymous_EI'] = 'Anónima - EI';
$string['details'] = 'Detalles';
$string['assess'] = 'Evaluar';
$string['set'] = 'Configurar';
$string['grade'] = 'Nota';
$string['weighingfinalgrade'] = 'Peso en la nota final';
$string['evalcomixgrade'] = 'Calificación de EvalCOMIX';
$string['moodlegrade'] = 'Calificación de Moodle';
$string['nograde'] = 'Sin Calificar';
$string['graphics'] = 'Gráficos';
$string['timedue_AE'] = 'AE - Fecha límite';
$string['timedue_EI'] = 'EI - Fecha límite';
$string['january'] = 'Enero';
$string['february'] = 'Febrero';
$string['march'] = 'Marzo';
$string['april'] = 'Abril';
$string['may'] = 'Mayo';
$string['june'] = 'Junio';
$string['july'] = 'Julio';
$string['august'] = 'Agosto';
$string['september'] = 'Septiembre';
$string['october'] = 'Octubre';
$string['november'] = 'Noviembre';
$string['december'] = 'Diciembre';
$string['save'] = 'Guardar';
$string['cancel'] = 'Cancelar';
$string['evaluate'] = 'Evaluar';
$string['scale'] = 'Escala';
$string['list'] = 'Lista de Control';
$string['listscale'] = 'Lista + Escala';
$string['rubric'] = 'Rúbrica';
$string['mixed'] = 'Mixto';
$string['differential'] = 'Diferencial';
$string['argumentset'] = 'Argumentario  ';

$string['whatis'] = 'Gestión de instrumentos de evaluación';
$string['gradeof'] = 'Nota de ';
$string['confirmdeletetool'] = '¿Está seguro que desea eliminar el instrumento?';
$string['finalgradecalculation'] = 'Cálculo de la nota final';
$string['method'] = 'Método';
$string['weightedaveragewithallvalues'] = 'Media ponderada de todas las calificaciones';
$string['weightedaveragesmart'] = 'Media ponderada de calificaciones no extremas';
$string['confirmdeleteassessment'] = '¿Está seguro que desea eliminar la evaluación?';

$string['mildoutlier'] = 'Valor atípico leve';
$string['extremeoutlier'] = 'Valor atípico extremo';
$string['overvaluation'] = 'Sobrevaloración';
$string['undervaluation'] = 'Infravaloración';
/* ----------------------------- HELP ----------------------------- */
$string['method_help'] = 'Esta opción permite establecer cómo se calculará la nota final de la actividad. Si se selecciona la opción "Media ponderada de todas las calificaciones", la calificación final se calculará realizando la media ponderada de las calificaciones otorgadas en cada modalidad de evaluación sin ignorar ninguna. En cambio, si se selecciona la opción "Media ponderada de valores no extremos", se eliminarán del cálculo aquellas calificaciones que se consideran extremas por superar determinadas cotas. Para más información lea el manual de EvalCOMIX';
/* ----------------------------- HELP ----------------------------- */
$string['timeopen_help'] = 'La Evaluación entre Iguales no se incluye en la nota actual de EvalCOMIX ya que aún se encuentra en período de evaluación.';
$string['evalcomixgrade_help'] = 'Media ponderada de las calificaciones de EvalCOMIX';
$string['evalcomixgradesmart_help'] = 'Media ponderada de las calificaciones de EvalCOMIX ignorando las sobrevaloraciones, infravaloraciones y valores atípicos extremos.';
$string['moodlegrade_help'] = 'Media ponderada de las calificaciones de EvalCOMIX';
$string['finalgrade_help'] = 'Media aritmética de la calificación final de EvalCOMIX y la calificación final de Moodle';
$string['teachermodality_help'] = 'Este será el instrumento de evaluación utilizado por el profesor para calificar esta actividad a sus alumnos.';
$string['pon_EP_help'] = 'Es el porcentaje que la calificación obtenida por el instrumento de evaluación del profesor tiene sobre la nota final.';
$string['selfmodality_help'] = 'Este será el instrumento de autoevaluación utilizado por el estudiante para calificar su trabajo realizado en esta actividad.';
$string['pon_AE_help'] = 'Es el porcentaje que la calificación obtenida por el instrumento de autoevaluación del estudiante tiene sobre la nota final.';
$string['peermodality_help'] = 'Este será el instrumento de evaluación utilizado por los alumnos para evaluar esta actividad a sus compañeros.';
$string['pon_EI_help'] = 'Es el porcentaje que la calificación obtenida por el instrumento de evaluación entre iguales tiene sobre la nota final.';
$string['availabledate_AE_help'] = 'Fecha a partir de la cual los estudiantes podrán evaluar su actividad.';
$string['timedue_AE_help'] = 'Fecha límite hasta la cual los estudiantes podrán evaluar su actividad.';
$string['availabledate_EI_help'] = 'Fecha a partir de la cual los estudiantes podrán evaluar la actividad realizada por sus compañeros.';
$string['timedue_EI_help'] = 'Fecha límite hasta la cual los estudiantes podrán evaluar a sus compañeros.';
$string['anonymous_EI_help'] = 'Indica si los estudiantes podrán saber qué compañeros les han calificado.';
$string['whatis_help'] = 'EvalCOMIX permite la creación y gestión de instrumentos de evaluación (listas de control, escalas de valoración, diferencial semántico,  y rúbricas) que pueden ser utilizados para evaluar Foros, Glosarios, Base de Datos, Wiki y Tareas.<br>La evaluación con estos instrumentos creados puede ser realizada por parte del profesor (evaluación del profesor), el propio estudiante (autoevaluación) o entre estudiantes (evaluación entre iguales). Para una mayor información se puede consultar el <a href="../manual.pdf">Manual</a>';
$string['selinstrument_help'] = 'Consulte el <a href="../manual.pdf">Manual</a> para una mayor información sobre cómo configurar una actividad de EvalCOMIX.';
/* --------------------------- END HELP --------------------------- */
$string['profile_task_by_student'] = 'Gráfica tarea por alumno';
$string['profile_task_by_group'] = 'Gráfica tarea por grupo';
$string['profile_task_by_course'] = 'Gráfica tarea por clase';
$string['profile_student_by_teacher'] = 'Gráfica evaluación de profesores';
$string['profile_student_by_group'] = 'Gráfica evaluación entre iguales';
$string['profile_attribute_by_student'] = 'Gráfica atributo por alumno';
$string['profile_attribute_by_group'] = 'Gráfica atributo por grupo';
$string['profile_attribute_by_course'] = 'Gráfica atributo por clase';
$string['titlegraficbegin'] = 'Inicio';
$string['no_datas'] = 'No hay datos';

$string['linktoactivity'] = 'Clic para ver la actividad';
$string['sendgrades'] = 'Enviar notas de EvalCOMIX';
$string['deletegrades'] = 'Quitar notas de EvalCOMIX';
$string['updategrades'] = 'Enviar últimas notas';
$string['gradessubmitted'] = 'Calificaciones de EvalCOMIX enviadas al Libro de Calificaciones';
$string['gradesdeleted'] = 'Calificaciones de EvalCOMIX suprimidas del Libro de Calificaciones';

$string['confirm_add'] = 'Esta operación modificará el Libro de Calificaciones de Moodle.\nRealizará la Media Aritmética entre las calificaciones de Moodle y EvalCOMIX.\n¿Confirma que desea continuar?';
$string['confirm_update'] = 'El libro de calificaciones ha sido modificado por EvalCOMIX con anterioridad.\nEsta operación enviará las calificaciones de EvalCOMIX que todavía no están reflejadas en el libro de calificaciones.\n¿Confirma que desea continuar?';
$string['confirm_delete'] = 'Esta operación modificará el Libro de Calificaciones de Moodle.\nSuprimirá las calificaciones de EvalCOMIX del libro de calificaciones de Moodle.\n¿Confirma que desea continuar?';
$string['noconfigured'] = 'Sin Configurar';
$string['gradebook'] = 'Libro de Calificaciones';

$string['poweredby'] = 'Desarrollado por:<br>Grupo de Investigación <br><span style="font-weight:bold; font-size: 10pt">EVALfor</span>';

$string['alertnotools'] = 'Todavía no se han generado instrumentos de evaluación. Para crearlos, deberá acceder a la siguiente sección';
$string['alertjavascript'] = 'Para el correcto funcionamiento de EvalCOMIX debe activar Javascript en su navegador';
$string['studentwork1'] = 'Trabajo realizado por el alumno';
$string['studentwork2'] = ' en la actividad ';

$string['reportsection'] = 'Generación de Informes';
$string['notaskconfigured'] = 'No hay actividades configuradas con';
$string['studendtincluded'] = 'Estudiantes Autoevaluados a  incluir';
$string['selfitemincluded'] = 'Items de Autoevaluación a incluir';
$string['selftask'] = 'Informe detallado de Autoevaluaciones';
$string['format'] = 'Formato';
$string['export'] = 'Exportar';
$string['xls'] = 'Excel';
$string['excelexport'] = 'Exportar a Hoja de Cálculo Excel';
$string['nostudentselfassessed'] = 'No hay estudiantes autoevaluados';

// Settings.
$string['admindescription'] = 'Configura las opciones del servidor de EvalCOMIX. Asegúrate de que los datos sean correctos. En otro caso la integración no funcionará';
$string['adminheader'] = 'Configuración de EvalCOMIX';
$string['serverurl'] = 'URL del servidor de EvalCOMIX:';
$string['serverurlinfo'] = 'Introduce la URL de tu servidor EvalCOMIX. No incluya barra inclinada al final. Ej: http://localhost/evalcomix';
$string['validationheader'] = 'Ajustes de validación';
$string['validationinfo'] = 'Por favor, guarde la configuración antes de hacer clic en el botón de validación. Si la validación no es correcta, vuelva a comprobar la URL y el token';
$string['validationbutton'] = 'Validar URL';
$string['error_conection'] = 'La validación falló: por favor, compruebe que la URL se corresponde con la URL del servicio web EvalCOMIX y que ha introducido correctamente el token';
$string['valid_conection'] = 'Validación llevada a cabo con éxito';
$string['simple_error_conection'] = 'URL válida. Pero se encontró el siguiente error:';

$string['alwaysvisible_EI_help'] = 'Si está desmarcado, los estudiantes sólo podrán ver las evaluaciones de sus compañeros una vez que finalice la Fecha Límite. Si está marcado, los estudiantes podrán consultar en todo momento las evaluaciones de sus compañeros';
$string['alwaysvisible_EI'] = 'Siempre visible';
$string['whoassesses_EI'] = 'Quién evalúa';
$string['anystudent_EI'] = 'Cualquier compañero';
$string['groups_EI'] = 'Grupos';
$string['specificstudents_EI'] = 'Estudiantes específicos';
$string['whoassesses_EI_help'] = 'Esta opción permite controlar qué estudiantes participarán en la evaluación entre iguales. Si se selecciona la opción "'.$string['anystudent_EI'].'" cada estudiante podrá evaluar a cualquiera de sus compañeros. Si se selecciona la opción "'.$string['groups_EI'].'", se respetará la configuración de grupos y agrupamientos de la actividad. Si se selecciona la opción "'.$string['specificstudents_EI'].'" se podrá indicar quiénes evaluarán y quiénes serán evaluados';
$string['assignstudents_EI'] = 'Asignar estudiantes';
$string['assess_students'] = 'Estudiantes evaluadores';
$string['studentstoassess'] = 'Estudiantes a los que evaluará';
$string['search'] = 'Buscar';
$string['add_delete_student'] = 'Añadir/Eliminar Estudiante';
$string['back'] = 'Volver';
$string['potentialstudents'] = 'Estudiantes Candidatos';

$string['settings'] = 'Ajustes';
$string['activities'] = 'Actividades';
$string['edition'] = 'Edición';
$string['settings_description'] = 'Desde este apartado se puede configurar aspectos relacionados con la tabla de evaluaciones de EvalCOMIX';

$string['crontask'] = 'Tarea para la actualización de calificaciones del libro de EvaLCOMIX';

// Graphics.
$string['taskgraphic'] = 'Gráfica Tarea';
$string['studentgraphic'] = 'Gráfica Alumnado';
$string['activity'] = 'Actividad';
$string['selectactivity'] = 'Seleccione una actividad';
$string['selectstudent'] = 'Seleccione un alumno/a';
$string['selectgroup'] = 'Seleccione un grupo';
$string['studentmod'] = 'Alumno/a';
$string['groupmod'] = 'Grupo';
$string['classmod'] = 'Clase';
$string['nostudents'] = 'No hay datos para esta actividad';
$string['nostudentsgroup'] = 'No hay datos en el grupo seleccionado';

// Tool editor.
$string['accept'] = 'Aceptar';
$string['selecttool'] = 'Elija el tipo de instrumento a crear';
$string['alertdimension'] = 'Debe existir al menos una dimensión';
$string['alertsubdimension'] = 'Debe existir al menos una subdimensión';
$string['alertatrib'] = 'Debe existir al menos un atributo';
$string['rubricremember'] = 'RECUERDE: NO deberían existir valores REPETIDOS';
$string['importfile'] = 'Importar Fichero';
$string['noatrib'] = 'Atributo Negativo';
$string['yesatrib'] = 'Atributo Positivo';

$string['comments'] = 'Comentarios';
$string['grade'] = 'Calificación';
$string['nograde'] = 'Sin calificación';
$string['alertsave'] = "Evaluación guardada satisfactoriamente.Si lo desea, ya puede cerrar la ventana";

$string['add_comments'] = 'Activar comentarios';
$string['checklist'] = 'Lista de Control';
$string['ratescale'] = 'Escala de Valoración';
$string['listrate'] = 'Lista de Control + Escala de Valoración';
$string['rubric'] = 'Rúbrica';
$string['differentail'] = 'Diferencial Semántico';
$string['mix'] = 'Instrumento Mixto';
$string['argument'] = 'Argumentario Evaluativo';
$string['import'] = 'Importar';
$string['numdimensions'] = 'Nº Dimensiones:';
$string['numvalues'] = 'Nº de Valores:';
$string['totalvalue'] = 'Valoración Global';
$string['dimension'] = 'Dimensión:';
$string['subdimension'] = 'Subdimensión:';
$string['numsubdimension'] = 'Nº Subdimensiones:';
$string['numattributes'] = 'Nº de Atributos:';
$string['attribute'] = 'Atributos';
$string['porvalue'] = 'Valor Porcentual:';
$string['value'] = 'Valor';
$string['values'] = 'Valores';
$string['globalvalue'] = 'VALORACIÓN GLOBAL DIMENSIÓN:';
$string['novalue'] = 'Valor Negativo';
$string['yesvalue'] = 'Valor Positivo';
$string['idea'] = 'IDEA Y DIRECCIÓN';
$string['design'] = 'DISEÑO';
$string['develop'] = 'DESARROLLO';
$string['translation'] = 'TRADUCCIÓN';
$string['colaboration'] = 'COLABORAN';
$string['license'] = 'LICENCIA';
$string['addtool'] = 'Añadir un Instrumento';
$string['title'] = 'Título';
$string['titledim'] = 'Dimension';
$string['titlesubdim'] = 'Subdimension';
$string['titleatrib'] = 'Atributo';
$string['titlevalue'] = 'Valor';
$string['no'] = 'No';
$string['yes'] = 'Sí';
$string['observation'] = 'Comentarios';
$string['view'] = 'Cerrar Vista Previa';

$string['windowselection'] = 'Ventana de selección';
$string['selectfile'] = 'Seleccione el fichero';
$string['upfile'] = 'Subir fichero';
$string['cancel'] = 'Cancelar';

$string['savedsaccessfully'] = 'El instrumento se ha guardado satisfactoriamente';
$string['ADimension'] = 'Este campo no puede estar vacío. \"Nº de Dimensiones\" debe ser un número mayor que 0 y \"Valoración Global\" un número mayor o igual que 2';
$string['ATotal'] = 'Este campo no puede estar vacío. \"Nº de Valores\" debe ser un número mayor o igual que 2';
$string['ASubdimension'] = 'Este campo no puede estar vacío. \"Nº Subdimensiones\" debe ser un número mayor que 0 y \"Nº de Valores\" mayor o igual que 2';
$string['AAttribute'] = 'Este campo no puede estar vacío. Por favor, especifique un número mayor que 0';
$string['ADiferencial'] = '\"Nº de Atributos\" debe ser mayor que 0. \"Nº de Valores\" debe ser IMPAR';
$string['ErrorFormato'] = 'El fichero está vacío o el formato es incorrecto';
$string['ErrorAcceso'] = 'No se pudo acceder al instrumento';
$string['ErrorExtension'] = 'Formato Incorrecto. La extensión debe ser \"evx\"';
$string['ErrorSaveTitle'] = 'Error: El campo Título no puede estar vacío';
$string['ErrorSaveTools'] = 'Error: Debe seleccionar al menos un instrumento';

$string['TSave'] = 'Guardar';
$string['TImport'] = 'Importar';
$string['TExport'] = 'Exportar';
$string['TAumentar'] = 'Aumentar tamaño de fuente';
$string['TDisminuir'] = 'Disminuir tamaño de fuente';
$string['TView'] = 'Vista Previa';
$string['TPrint'] = 'Imprimir';
$string['THelp'] = 'Ayuda';
$string['TAbout'] = 'Acerca de';

$string['mixed_por'] = 'Peso en la nota final';

$string['handlerofco'] = 'Gestión de resultados de aprendizaje y competencias';
$string['competencies'] = 'Competencias';
$string['outcomes'] = 'Resultados de aprendizaje';
$string['compidnumber'] = 'Código';
$string['compshortname'] = 'Nombre corto';
$string['compdescription'] = 'Descripción';
$string['comptypes'] = 'Tipos de competencia';
$string['comptype'] = 'Tipo de competencia';
$string['newcomp'] = 'Nueva competencia';
$string['newoutcome'] = 'Nuevo resultado de aprendizaje';
$string['newcomptype'] = 'Nuevo tipo de competencia';
$string['compreport'] = 'Informe de desarrollo';
$string['compandout'] = 'Competencias y resultados de aprendizaje';
$string['uploadcompetencies'] = 'Importar competencias y resultados';
$string['uploadcompetencies_help'] = 'Las competencias y resultados de aprendizaje se pueden cargar a través de un archivo de texto. El formato del archivo debe ser el siguiente:

* Cada línea del archivo contiene un registro
* Cada registro es una serie de datos separados por comas (u otros delimitadores)
* El primer registro contiene una lista de nombres de campo que definen el formato del resto del archivo
* Los nombres de campo requeridos son idnumber, shortname, result';
$string['idnumberduplicate'] = 'Valor de idnumber duplicado';
$string['invalidoutcome'] = 'Valor de outcome inválido. Debe ser 0 o 1';
$string['invalididnumberupload'] = 'Valor de idnumber no válido. El tamaño debe ser menor que 100';
$string['missingidnumber'] = 'Falta la columna idnumber';
$string['missingshortname'] = 'Falta la columna shortname';
$string['missingoutcome'] = 'Falta la columna outcome';
$string['ignored'] = 'Ignorados';
$string['errors'] = 'Errores';
$string['importresult'] = 'Importar resultados';
$string['uploadcompetenciespreview'] = 'Vista preliminar de competencias subidas';
$string['choicecompetency'] = 'Seleccione una competencia';
$string['choiceoutcome'] = 'Seleccione un resultado';
$string['associatecompandout'] = 'Asociar competencias y resultados';
$string['allstudens'] = 'Todos los estudiantes';
$string['onestudent'] = 'Estudiante específico';
$string['onegroup'] = 'Grupo específico';
$string['selectcomptype'] = 'Seleccione tipo de competencia';
$string['assessmentreport'] = 'Informe de evaluaciones';
$string['unrealized'] = 'No realizada';
$string['doneoutofrange'] = 'Ha realizado la autoevaluación o evaluación entre iguales pero fuera de rango';
$string['doneoutofrangecomments'] = 'Ha realizado la autoevaluación o evaluación entre iguales, fuera de rango y aporta comentarios';
$string['donewithinrange'] = 'Ha realizado la autoevaluación o evaluación entre iguales dentro de rango';
$string['donewithinrangecomments'] = 'Ha realizado la autoevaluación o evaluación entre iguales dentro de rango y aporta comentarios';
$string['threshold'] = 'Umbral de sobrevaloración e infravaloración';
$string['threshold_help'] = 'Límite a partir del cual la puntuación de un estudiante se considera sobrevaloración o infravaloración. Por ejemplo, si el umbral está establecido a 15 y la calificación del profesor (o en su defecto, la media de la evaluación entre iguales) es 50, entonces se considerará sobrevaloración cualquier puntuación del estudiante que sobrepase los 65 (50+15) puntos e infravaloración las inferiores a 35 (50-15) puntos. ';
$string['assessmentreporttotalAE'] = 'Total AE (Sobre 10)';
$string['assessmentreporttotalEI'] = 'Total EI (Sobre 10)';
$string['AE'] = 'AE';
$string['EI'] = 'EI';
$string['evaluationexporthelp'] = 'Informe resultados de discrepancia entre autoevaluación y evaluaciones entre iguales con respecto a criterio (umbral de sobrevaloración o infravaloración';
$string['evaluationandreports'] = 'Evaluación e informes';
$string['workteams'] = 'Equipos de trabajo';
$string['workteamsassessments'] = 'Evaluación de equipos de trabajo';
$string['assignteamcoordinators'] = 'Asignar coordinadores de equipo';
$string['workteamsassessments_help'] = 'Si activa esta opción, podrá nombrar un coordinador que represente al grupo.

Si hay **Evaluación del Profesorado - EP**, los profesores sólo podrán evaluar a los coordinadores y esa evaluación se le asignará a cada miembro de su grupo.

Si hay **Autoevaluación – AE**, sólo el coordinador podrá autoevaluarse y su evaluación se le asignará a cada miembro de su grupo.

Si hay **Evaluación entre Iguales – EI**, los alumnos solo podrán evaluar  a los coordinadores de cada grupo y cada evaluación se le asignará a cada miembro del grupo.

Los alumnos que no estén en ningún grupo no recibirán evaluación. Los grupos que no tengan asignado un coordinador no recibirán ninguna evaluación y tampoco podrán evaluar.';
$string['selectcoordinator'] = 'Elige coordinador';
$string['alertnogroup'] = 'Todavía no se han creado grupos en el curso. Para crearlos deberá acceder a la siguiente sección:';
$string['activityassessed'] = 'Deshabilitado debido a que ya se ha evaluado a algún/a estudiante';
$string['coordinatorassessed'] = 'Actualmente, hay coordinadores que ya han recibido alguna evaluación. Aquellos que hayan recibido alguna evaluación no podrán ser reemplazados. Si desea reemplazarlos, primero deberá eliminar las evaluaciones';
$string['confirmdisabledworkteams'] = 'En esta actividad ya se han realizado evaluaciones. Si desactiva esta opción y guarda los cambios, se eliminarán todas esas evaluaciones y no se podrán recuperar. ¿Confirma que desea desactivar la opción?';
$string['crontaskdevdata'] = 'Tarea para la descarga de datos de el informe de desarrollo';
$string['reporttimeleft'] = 'Se están descargando los datos del informe en segundo plano. Faltan {$a} para la descarga completa';
