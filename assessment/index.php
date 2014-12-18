<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
 
//////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// Archivos a incluir /////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
	
	require_once('../../../config.php');	
	require_once('lib.php');
	require_once($CFG->dirroot . '/grade/report/grader/lib.php');
	require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix.php');
	require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tool.php');
	include_once($CFG->dirroot .'/blocks/evalcomix/classes/webservice_evalcomix_client.php');
	

/////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// Comprobación de parámetros que se reciben /////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$courseid 	   = required_param('id', PARAM_INT);        // course id
	$page          = optional_param('page', 0, PARAM_INT);   // active page
	$perpageurl    = optional_param('perpage', 0, PARAM_INT);
	$sortitemid    = optional_param('sortitemid', 0, PARAM_ALPHANUM); // sort by which grade item
	$stu 		   = optional_param('stu', 0, PARAM_INT);	// evaluated student id
	$cma 		   = optional_param('cma', 0, PARAM_INT);	// cm id of evaluated activity
	$grd 		   = optional_param('grd', 0, PARAM_INT);	// 1 if the system must pass grades to Moodle´s Grades Report
	
	//$selection     = optional_param('select', 0, PARAM_INT);
	if (function_exists('clean_param_array')) {
		$data = clean_param_array($_POST, PARAM_ALPHANUM);
	}
	elseif(function_exists('clean_param')){
		$data = clean_param($_POST, PARAM_ALPHANUM);
	}
	else{
		$data = $_POST;
	}
	
	if($stu){
		$data['stu'] = $stu;
	}
	if($cma){
		$data['cma'] = $cma;
	}
	
	
	global $OUTPUT, $DB;
	
	if (!$course = $DB->get_record('course', array('id' => $courseid))) {
		print_error('nocourseid');
	}

////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// Declaración de variables /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////

	$PAGE->set_url(new moodle_url('/blocks/evalcomix/assessment/index.php', array('id' => $courseid)));
	$buttons = false;


////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// Comprobaciones de tipo de acceso /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////

	require_login($course);
	$context = context_course::instance($courseid);
	//require_capability(); //utilizar nuestras capabilities definidas en /db/access.php
	
	

/////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// Imprimir cabecera /////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
	$PAGE->set_pagelayout('incourse');
	//print_grade_page_head($courseid, 'report', 'grader', null, false, $buttons, false);
//	print_grade_page_head($courseid, 'report', null, false, $buttons, false, false);
	// Print the header
	$strplural = 'evalcomix';
	$PAGE->navbar->add($strplural);
	$PAGE->set_title($strplural);
	$PAGE->set_pagelayout('report');
	$PAGE->set_heading($course->fullname);
	echo $OUTPUT->header();
	
	
//////////////////////////////////////////////////////////////////////////////////////////////
//Se comprueba que el curso no está recién restaurado, en tal caso actualiza los instrumentos/
//////////////////////////////////////////////////////////////////////////////////////////////
	
	$environment = evalcomix::fetch(array('courseid' => $courseid));
	//Si hay instrumentos duplicados (con timemodified a -1)
	if (isset($environment->id) && $webtools = evalcomix_tool::fetch_all(array('evxid' => $environment->id, 'timemodified' => '-1'))){
		//$webtools = webservice_evalcomix_client::get_ws_list_tool($courseid, MOODLE_NAME);
		$tools = array();
		if(!empty($webtools) && $environment){
			update_tool_list($environment->id, $webtools);
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// Obtención de los objetos necesarios /////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////

	//Initialise the evalcomix report object that produces the table
	$report_evalcomix = new grade_report_evalcomix($courseid, null, $context, $page, $sortitemid);
	if(!empty($data)){
		$report_evalcomix->process_data($data);
	}

	// Array 
	// Almacenará los estudiantes que se mostrarán en la tabla de calificaciones
	// Los estudiantes que almacenará variará en función de los privilegios del usuario y de la configuración 
	// de la actividad (grupos, agrupamientos, modalidades de evaluación)
	$users = array();

	// Array
	// Almacenará las actividades que se mostrará en la tabla de calificacions.
	// Las instancias que almacenará dependerá de la configuración de la actividad (agrupamientos)
	$activities = array();
	
	
	// Array de dos dimensiones [usuario][actividad];
	// Contendrá código HTML que se mostrará en las celdas de la tabla de calificaciones.
	// Su contenido dependerá de los privilegios del usuario.
	$content_cells = array();

	$evalcomix = evalcomix::fetch(array('courseid' => $courseid));
	$showmessage = false;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// Se comprueba si hay que pasar notas al libro /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// prints paging bar at top for large pages
	$studentsperpage = $report_evalcomix->studentsperpage;
	$numusers = $report_evalcomix->get_numusers();
	
	//if (has_capability('block/evalcomix:edit',$context, $USER->id) && ($grd == 1 || $grd == 2 || $grd == 3)){
	if (has_capability('moodle/block:edit',$context, $USER->id) && ($grd == 1 || $grd == 2 || $grd == 3)){
		//$users = $report_evalcomix->load_users();
		//$finalgrades = $report_evalcomix->get_grades();
		$numpages = (int)($numusers / $studentsperpage);
		if($numusers % $studentsperpage > 0){
			$numpages += 1;
		}
		for($ipage = 0; $ipage < $numpages; ++$ipage){
			$report_grader = new grade_report_grader($courseid, null, $context, $ipage, $sortitemid);
			$report_grader->load_users();
			$report_grader->load_final_grades();

			foreach ($report_grader->users as $userid => $user) {
				if ($report_grader->canviewhidden) {
					$altered = array();
					$unknown = array();
				} else {
					$hidingaffected = grade_grade::get_hiding_affected($report_grader->grades[$userid], $report_grader->gtree->get_items());
					$altered = $hidingaffected['altered'];
					$unknown = $hidingaffected['unknown'];
					unset($hidingaffected);
				}
				
				foreach ($report_grader->gtree->items as $itemid=>$unused) {
					$item =& $report_grader->gtree->items[$itemid];
					$grade = $report_grader->grades[$userid][$item->id];

					// Get the decimal points preference for this item
					$decimalpoints = $item->get_decimals();

					 if (in_array($itemid, $unknown)) {
						$gradeval = null;
					} else if (array_key_exists($itemid, $altered)) {
						$gradeval = $altered[$itemid];
					} else {
						$gradeval = $grade->finalgrade;
					}
		
					//echo $userid .' ';
					//echo $item->id . '-';
					//echo $gradeval;
					//Evalcomix////////////////////////////////			
					if($grade->grade_item->is_external_item()){
						if ($grd == 1 && $evalcomix->sendgradebook == 0){
							include($CFG->dirroot. '/blocks/evalcomix/assessment/gradeevx.php');
							$showmessage = true;
						}
						if ($grd == 2 && isset($gradeval) && $evalcomix->sendgradebook == 1){
							include($CFG->dirroot . '/blocks/evalcomix/assessment/undone_evx.php');					
							$showmessage = true;
						}
						if($grd == 3){
							if(isset($gradeval)){
								include($CFG->dirroot . '/blocks/evalcomix/assessment/undone_evx.php');
							}
							
							include($CFG->dirroot. '/blocks/evalcomix/assessment/gradeevx.php');
							$showmessage = true;
						}
					}
					/////////////////////////////////////
				}
			}	
		}
		if($grd == 1){
			$evalcomix->sendgradebook = 1;
			$evalcomix->update();
		}
		elseif($grd == 2){
			$evalcomix->sendgradebook = 0;
			$evalcomix->update();
		}
	}
	

	



/////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// Desplegable de tipos de notas /////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////

	//If $USER has editing permits			
	/*if(is_siteadmin($USER) || has_capability('block/evalcomix:edit',$context)){
		echo '		
			<form name="formulario" method="get" action="'. $CFG->wwwroot.'/blocks/evalcomix/assessment/index.php">
				<input type=hidden name=id value=' . $COURSE->id .'>
				<SELECT NAME=select onchange="submit()">		
						<option value="0">Notas de EvalCOMIX con notas de Moodle
						<option value="1">Sólo notas de EvalCOMIX
				</SELECT>
			</form>
			<script language=javascript>document.formulario.select.options['.$selection.'].selected = true;</script>				
		';
	}*/

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// Comprobamos si existen instrumentos modificados con evaluaciones asociadas /////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($toollist = evalcomix_tool::fetch_all(array('evxid' => $environment->id))){
	$newgrades = webservice_evalcomix_client::get_assessments_modified(array('tools' => $toollist));
	if(!empty($newgrades)){
		include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
		include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
		include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_grades.php');
		$tasks = evalcomix_tasks::get_tasks_by_courseid($courseid);
		$toolids = array();
		foreach($tasks as $task){
			if($assessments = evalcomix_assessments::fetch_all(array('taskid' => $task->id))){
				foreach($assessments as $assessment){
					$activity = $task->instanceid;
					$module = evalcomix_tasks::get_type_task($activity);				
					$mode = grade_report_evalcomix::get_type_evaluation($assessment->studentid, $courseid);
					$str = $courseid . '_' . $module . '_' . $activity . '_' . $assessment->studentid . '_' . $assessment->assessorid . '_' . $mode . '_' . MOODLE_NAME;
					$assessmentid = md5($str);
					if(isset($newgrades[$assessmentid])){
						$grade = $newgrades[$assessmentid]->grade;
						$toolids[] = $newgrades[$assessmentid]->toolid;
						$assessment->grade = $grade;
						$assessment->update();
						if($evalcomix_grade = evalcomix_grades::fetch(array('courseid' => $courseid, 'cmid' => $task->instanceid, 'userid' => $assessment->studentid))){
							$params = array('cmid' => $task->instanceid, 'userid' => $assessment->studentid, 'courseid' => $courseid);
							$finalgrade = evalcomix_grades::get_finalgrade_user_task($params);
							if($finalgrade !== null){
								$evalcomix_grade->finalgrade = $finalgrade;
								$evalcomix_grade->update();
							}			
						}
					}
				}
			}
		}	
		webservice_evalcomix_client::set_assessments_modified(array('toolids' => $toolids));
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// Logo y enlace a la gestión de instrumentos /////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

	echo '				
		<center>
			<div><img src="'. $CFG->wwwroot . EVXLOGOROOT .'" width="230" alt="EvalCOMIX"/></div><br>';
		
	//If $USER has editing permits			
	//if(is_siteadmin($USER) || has_capability('block/evalcomix:edit',$context)){
	if(is_siteadmin($USER) || has_capability('moodle/grade:viewhidden',$context)){
		echo '<div><input type="button" style="color:#333333" value="'.get_string('designsection', 'block_evalcomix').'" onclick="location.href=\''. $CFG->wwwroot .'/blocks/evalcomix/tool/index.php?id='.$courseid .'\'"/></div>';		
		echo '<div><input type="button" style="color:#333333" value="'.get_string('graphics','block_evalcomix').'" onclick="location.href=\''. $CFG->wwwroot .'/blocks/evalcomix/graphics/index.php?mode=1&id='.$courseid .'\'"/></div>';
		if(has_capability('moodle/block:edit',$context)){
			echo '<div><input type="button" style="color:#333333" value="'.get_string('settings','block_evalcomix').'" onclick="location.href=\''. $CFG->wwwroot .'/blocks/evalcomix/assessment/configuration.php?id='.$courseid .'\'"/></div>';
		
/*		echo '<div>
				<select onClick="javascript:index=this.selectedIndex;location.href=\'../reports/index.php?id='.$courseid.'&mode=\'+this.options[index].value">
					<option value="0">--'.get_string('reportsection', 'block_evalcomix').'--</option>
					<option value="selftask">Informe detallado de Autoevaluaciones</option>
				</select>
			</div><br>';
*/
			echo '<fieldset style="border:1px solid #c3c3c3; width:35%; padding:0.3em">
			<legend style="color:#333333;text-align:left"><a href='.$CFG->wwwroot.'/grade/report/index.php?id='.$courseid.'>'.get_string('gradebook', 'block_evalcomix').'</a></legend>';
			//To show the correct button
			if (isset($evalcomix->sendgradebook) && $evalcomix->sendgradebook == 0){
				echo '<div><input type="button" style="color:#333333" value="'.get_string('sendgrades', 'block_evalcomix').'" onclick="if(confirm(\'' . get_string('confirm_add', 'block_evalcomix') . '\'))location.href=\''. $CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'&page='.$page.'&grd=1\'"/></div>';
			}
			elseif (isset($evalcomix->sendgradebook) && $evalcomix->sendgradebook == 1){
				echo '<div><input type="button" style="color:#333333" value="'.get_string('updategrades', 'block_evalcomix') .'" onclick="if(confirm(\'' . get_string('confirm_update', 'block_evalcomix') . '\'))location.href=\''. $CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'&page='.$page.'&grd=3\'"/>
				<input type="button" style="color:#333333" value="'.get_string('deletegrades', 'block_evalcomix') .'" onclick="if(confirm(\'' . get_string('confirm_delete', 'block_evalcomix') . '\'))location.href=\''. $CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'&page='.$page.'&grd=2\'"/></div>';		
			}
			echo '</fieldset>';
		}
	}
	
	echo '</center>';


//////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// Insertar tabla /////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
	if (!empty($studentsperpage) && $studentsperpage >= 20) {
		echo $OUTPUT->paging_bar($numusers, $report_evalcomix->page, $studentsperpage, $report_evalcomix->pbarurl);
	}

	echo '<div style="background-color:#fff">';
	echo $report_evalcomix->create_grade_table();
	echo '</div>';
	

	//$table->create_table($users, $activities, $content_cells);

// Bucle que va obteniendo las tareas y sus datos
	// Imprimir cabeceras
	// Bucle que va obteniendo los usuarios
		// Imprimir celdas con los nombres de los usuarios
		// Bucle que va creando cada tupla usuario-tarea
			// Imprimir celdas con las notas y los enlaces adecuados




	if($grd == 1 && $showmessage == true){
		echo "<script type='text/javascript' language='javascript'>alert('".get_string('gradessubmitted', 'block_evalcomix')."');</script>";
	}
	elseif($grd == 2 && $showmessage == true){
		echo "<script type='text/javascript' language='javascript'>alert('".get_string('gradesdeleted', 'block_evalcomix')."');</script>";
	}
	elseif($grd == 3 && $showmessage == true){
		echo "<script type='text/javascript' language='javascript'>alert('".get_string('gradessubmitted', 'block_evalcomix')."');</script>";
	}	
		
		
////////////////////////////////////////////////////////////////////////////////
///////////////////////////////// Imprimir pie /////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
	// prints paging bar at bottom for large pages
	if (!empty($studentsperpage) && $studentsperpage >= 20) {
		echo $OUTPUT->paging_bar($numusers, $report_evalcomix->page, $studentsperpage, $report_evalcomix->pbarurl);
	}
	
	echo $OUTPUT->footer();
	
	

?>