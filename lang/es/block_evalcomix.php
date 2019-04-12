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
$string['whatis'] = 'EvalCOMIX permite la creación y gestión de instrumentos de evaluación (listas de control, escalas de valoración, diferencial semántico,  y rúbricas) que pueden ser utilizados para evaluar Foros, Glosarios, Base de Datos, Wiki y Tareas.<br>
La evaluación con estos instrumentos creados puede ser realizada por parte del profesor (evaluación del profesor), el propio estudiante (autoevaluación) o entre estudiantes (evaluación entre iguales).
Para una mayor información se puede consultar el <a href="' . $CFG->wwwroot.'/lib/evalcomix/manual.pdf">Manual</a>';
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
$string['argumentset'] = 'Argumentario	';

$string['whatis'] = 'Gestión de instrumentos de evaluación';
$string['gradeof'] = 'Nota de ';
/* ----------------------------- HELP ----------------------------- */
$string['timeopen_help'] = 'La Evaluación entre Iguales no se incluye en la nota actual de EvalCOMIX ya que aún se encuentra en período de evaluación.';
$string['evalcomixgrade_help'] = 'Media ponderada de las calificaciones de EvalCOMIX';
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
$string['whatis_help'] = 'EvalCOMIX permite la creación y gestión de instrumentos de evaluación (listas de control, escalas de valoración, diferencial semántico,  y rúbricas) que pueden ser utilizados para evaluar Foros, Glosarios, Base de Datos, Wiki y Tareas.<br>
La evaluación con estos instrumentos creados puede ser realizada por parte del profesor (evaluación del profesor), el propio estudiante (autoevaluación) o entre estudiantes (evaluación entre iguales).
Para una mayor información se puede consultar el <a href="../manual.pdf">Manual</a>';
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
$string['selectallany'] = 'Seleccionar todos/ninguno';
$string['nostudentselfassessed'] = 'No hay estudiantes autoevaluados';

// Settings.
$string['admindescription'] = 'Configura las opciones del servidor de EvalCOMIX. Asegúrate de que los datos sean correctos. En otro caso la integración no funcionará';
$string['adminheader'] = 'Configuración de EvalCOMIX';
$string['serverurl'] = 'URL del servidor de EvalCOMIX:';
$string['serverurlinfo'] = 'Introduce la URL de tu servidor EvalCOMIX. Ej: http://localhost/evalcomix';
$string['validationheader'] = 'Ajustes de validación';
$string['validationinfo'] = 'Por favor, antes de guardar la configuración, haga clic en el botón de validación. Si la validación es correcta, guarde los ajustes. En otro caso, por favor, vuelva a comprobar la URL';
$string['validationbutton'] = 'Validar URL';
$string['error_conection'] = 'La validación falló: por favor, compruebe que la URL se corresponde con la URL del servicio web EvalCOMIX';
$string['valid_conection'] = 'Validación llevada a cabo con éxito';
$string['simple_error_conection'] = 'URL válida. Pero se encontró el siguiente error:';

$string['alwaysvisible_EI_help'] = 'Si está desmarcado, los estudiantes sólo podrán ver las evaluaciones de sus compañeros una vez que finalice la Fecha Límite. Si está marcado, los estudiantes podrán consultar en todo momento las evaluaciones de sus compañeros';
$string['alwaysvisible_EI'] = 'Siempre visible';
$string['whoassesses_EI'] = 'Quién evalúa';
$string['anystudent_EI'] = 'Cualquier compañero';
$string['groups_EI'] = 'Grupos';
$string['specificstudents_EI'] = 'Estudiantes específicos';
$string['whoassesses_EI_help'] = 'Esta opción permite controlar qué estudiantes participarán en la evaluación entre iguales.
Si se selecciona la opción "'.$string['anystudent_EI'].'" cada estudiante podrá evaluar a cualquiera de sus compañeros.
Si se selecciona la opción "'.$string['groups_EI'].'", se respetará la configuración de grupos y agrupamientos de la actividad.
Si se selecciona la opción "'.$string['specificstudents_EI'].'" se podrá indicar quiénes evaluarán y quiénes serán evaluados';
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