<?php

/*
	Autores:
		- Gonzalo Saavedra Postigo
		- David Mariscal Martínez
		- Aris Burgos Pintos
	Organización: ITelligent Information Technologies, S.L.
	Licencia: gpl-3.0
*/


	// Función encargada de recoger los datos que van a ser pintados en el gráfico
	// $tipo_grafico: 1 (perfil-tareas), 2 (perfil-alumnado), 3 (perfil-atributos)
	// $tipo_opcion_radiobutton: describe que opcion hemos seleccionado en el radiobutton.
	//	 - para las graficas perfil-tareas y perfil-atributos las opciones podrían ser: 1 (alumno), 2 (grupo), 3 (clase)
	//	 - para las graficas perfil-alumnado las opciones podrían ser: 1 (entre-profesores), 2 (entre-iguales)
	// $id_alumnos: ids de los alumno checkeados
	// $id_grupo: id del grupo por el que filtrar, en el caso de que el valor sea -1 quiere decir que es del tipo null
	// $id_alumno: id del alumno por el que filtrar, en el caso de que el valor sea -1 quiere decir que es del tipo null
	// $id_modalidad: id de la modalidad por la que filtrar, en el caso de que el valor sea -1 quiere decir que es del tipo null
	// $id_tarea: id de la asociada por la que filtrar, en el caso de que el valor sea -1 quiere decir que es del tipo null
	// $id_curso: id del curso sobre el que se mostrarán los datos de las gráficas
	require_once('../../../../config.php');	
	function query_grafica($tipo_grafico, $tipo_opcion_radiobutton, $id_tarea, $id_alumnos, $id_grupo, $id_alumno, $id_modalidad, $idCurso){		
		
			require_once('../../../../config.php');
			global $CFG;		
					
			switch ($tipo_grafico) {
			case 1:
				{switch ($tipo_opcion_radiobutton) {
					case 1: // perfil-tarea, alumno
						include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
						include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
						include_once($CFG->dirroot .'/blocks/evalcomix/classes/calculator_average.php');
						$assessments = evalcomix_assessments::get_assessments_by_modality($id_tarea, $id_alumno);
						if($assessments){
							//$teachergrade = evalcomix_assessments::calculate_gradearray($assessments->teacherassessments);
							$teachergrades = evalcomix_assessments::calculate_grades($assessments->teacherassessments);
							$peergrades = evalcomix_assessments::calculate_grades($assessments->peerassessments);
							
							$array_teacher = array(get_string('teachermod', 'block_evalcomix'));
							if($teachergrades){
								foreach($teachergrades as $teachergrade){
									$array_teacher[] = (int)$teachergrade;
								}							
							}
							
							$array_peer = array(get_string('peermod', 'block_evalcomix'));
							if($peergrades){
								foreach($peergrades as $peergrade){
									$array_peer[] = (int)$peergrade;
								}							
							}

							$selfassessment = $assessments->selfassessment;
							$array_self = array(get_string('selfmod', 'block_evalcomix'));
							if($selfassessment){
								$array_self[] = (int)$selfassessment->grade;
							}
							
							return array('tituloGrafico'=> array(get_string('profile_task_by_student', 'block_evalcomix')), 
								'min'=> 0, 
								'max'=> 100, 					
								'datos' => array(
									$array_teacher,
									$array_self,
									$array_peer,
								)
							);
						}
						else{
							return array('tituloGrafico'=> array(get_string('profile_task_by_student', 'block_evalcomix')), 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array(get_string('no_datas', 'block_evalcomix'), 0)									
									)
								);
						}
						/*return array('tituloGrafico'=> 'Gráfica tarea por alumno', 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array('profesor', 72.5),
										array('autoevaluacion', 70),
										array('entre iguales', 66.6),
									)
								);*/
						
						break;
						
					case 2:{ // perfil-tarea, grupo
						$id_students = explode(',', $id_alumnos);
						include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
						include_once($CFG->dirroot .'/blocks/evalcomix/classes/calculator_average.php');
						if($id_students != $id_alumnos){
							$t_grades[0] = get_string('teachermod', 'block_evalcomix');
							$p_grades[0] = get_string('peermod', 'block_evalcomix');
							$s_grades[0] = get_string('selfmod', 'block_evalcomix');
							foreach($id_students as $id_student){
								if($id_student == '-1'){
									continue;
								}
								$assessments = evalcomix_assessments::get_assessments_by_modality($id_tarea, (int)$id_student);
								if($assessments){
									if(!empty($assessments->teacherassessments)){
										$tgrade = evalcomix_assessments::calculate_gradearray($assessments->teacherassessments);
										array_push($t_grades, $tgrade);
									}
									if(!empty($assessments->peerassessments)){
										$pgrade = evalcomix_assessments::calculate_gradearray($assessments->peerassessments);
										array_push($p_grades, $pgrade);
									}
									if($assessments->selfassessment){
										$selfassessment = $assessments->selfassessment;
										$sgrade = (int)$selfassessment->grade;
										array_push($s_grades, $sgrade);
									}
								}
							}
							/*if(!isset($t_grades[1])){
								array_push($t_grades, 0);
							}
							if(!isset($p_grades[1])){
								array_push($p_grades, 0);
							}
							if(!isset($s_grades[1])){
								array_push($s_grades, 0);
							}*/
							return array('tituloGrafico'=> array(get_string('profile_task_by_group', 'block_evalcomix')), 
										'min'=> 0, 
										'max'=> 100, 					
										'datos' => array(
											$t_grades,
											$s_grades,
											$p_grades,
										)
									);
							/* PRUEBAS
							return array('tituloGrafico'=> array(get_string('profile_task_by_group', 'block_evalcomix')), 
								'min'=> 0, 
								'max'=> 100, 					
								'datos' => array(
									array("Evaluación del Profesor",100,75,50,25),
									array("Autoevaluación",0),
									array("Evaluación entre Iguales",0),
								)
							);*/

							/*return array('tituloGrafico'=> 'Perfil tarea por grupo', 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array('profesor', 67, 85, 99, 100), 
										array('autoevaluacion', 87, 70, 67, 100),
										array('entre iguales', 45, 70, 80, 100),
									)
								);*/
							
						}
						else{
							return array('tituloGrafico'=> array(get_string('profile_task_by_group', 'block_evalcomix')), 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array(get_string('no_datas', 'block_evalcomix'), 0)
									)
								);
						}
					/* DATOS DE ITELLIGENT	
					return array('tituloGrafico'=> 'Perfil tarea por grupo', 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array('profesor', 67, 85, 99),
										array('autoevaluacion', 87, 70, 67),
										array('entre iguales', 45, 70, 80),
									)
								);
						*/
					}break;
						
					case 3: // perfil-tarea, clase
						include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
						include_once($CFG->dirroot .'/blocks/evalcomix/classes/calculator_average.php');
						$students = evalcomix_assessments::get_students_assessed($id_tarea);
						if($students){
							$t_grades[0] = get_string('teachermod', 'block_evalcomix');
							$p_grades[0] = get_string('peermod', 'block_evalcomix');
							$s_grades[0] = get_string('selfmod', 'block_evalcomix');
							foreach($students as $student){
								$assessments = evalcomix_assessments::get_assessments_by_modality($id_tarea, $student);
								if(!empty($assessments->teacherassessments)){
									$tgrade = evalcomix_assessments::calculate_gradearray($assessments->teacherassessments);
									array_push($t_grades, $tgrade);
								}
								
								if(!empty($assessments->peerassessments)){
									$pgrade = evalcomix_assessments::calculate_gradearray($assessments->peerassessments);
									array_push($p_grades, $pgrade);
								}
								
								if($assessments->selfassessment){
									$selfassessment = $assessments->selfassessment;
									$sgrade = (int)$selfassessment->grade;
									array_push($s_grades, $sgrade);
								}
							}
							/*if(!isset($t_grades[1])){
								array_push($t_grades, 0);
							}
							if(!isset($p_grades[1])){
								array_push($p_grades, 0);
							}
							if(!isset($s_grades[1])){
								array_push($s_grades, 0);
							}*/
							return array('tituloGrafico'=> array(get_string('profile_task_by_course', 'block_evalcomix')), 
										'min'=> 0, 
										'max'=> 100, 					
										'datos' => array(
											$t_grades,
											$s_grades,
											$p_grades,
										)
									);
							
							/*return array('tituloGrafico'=> 'Perfil tarea por clase', 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array('profesor', 67, 85, 99),
										array('autoevaluacion', 87, 70, 67),
										array('entre iguales', 45, 70, 80),
									)
								);
						*/
						}
						else{
							return array('tituloGrafico'=> array(get_string('profile_task_by_course', 'block_evalcomix')), 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array(get_string('no_datas', 'block_evalcomix'), 0)
									)
								);
						}
						
						break;
						}}
				break;
				
			
			case 2:
				{switch ($tipo_opcion_radiobutton) {
					case 1: // perfil-alumnado, entre profesores
						global $DB;
						include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
						$assessments = evalcomix_assessments::get_assessments_by_modality($id_tarea, $id_alumno);
						$teachergrades = array();
						if(!empty($assessments->teacherassessments)){
							foreach($assessments->teacherassessments as $tassessment){
								$teacher = $DB->get_record('user', array('id' => $tassessment->assessorid));
								$teacher_label = $teacher->lastname . ', ' . $teacher->firstname;
								$item = array($teacher_label, $tassessment->grade);
								array_push($teachergrades, $item);
							}
							return array('tituloGrafico'=> array(get_string('profile_student_by_teacher', 'block_evalcomix')),
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => $teachergrades
									);
								
						}
						else{
							return array('tituloGrafico'=> array(get_string('profile_student_by_teacher', 'block_evalcomix')), 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(array(get_string('no_datas', 'block_evalcomix'), 0))
									);
						}
						/*return array('tituloGrafico'=> 'Perfil alumnado entre profesores', 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array('profesor1', 67),
										array('profesor2', 87),
										array('profesor3', 45),
										array('profesor4', 70),
										array('profesor5', 80)
									)
								);*/
						
						break;
						
					case 2: // perfil-alumnado, entre iguales
						global $DB;
						include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
						$assessments = evalcomix_assessments::get_assessments_by_modality($id_tarea, $id_alumno);
						$peergrades = array();
						if(!empty($assessments->peerassessments)){
							foreach($assessments->peerassessments as $passessment){
								$peer = $DB->get_record('user', array('id' => $passessment->assessorid));
								$peer_label = $peer->lastname . ', ' . $peer->firstname;
								$item = array($peer_label, (int)$passessment->grade);
								array_push($peergrades, $item);
							}
							return array('tituloGrafico'=> array(get_string('profile_student_by_group', 'block_evalcomix')), 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => $peergrades
								);
						}
						else{
							return array('tituloGrafico'=> array(get_string('profile_student_by_group', 'block_evalcomix')), 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(array(get_string('no_datas', 'block_evalcomix'), 0))
								);
						}
						/*return array('tituloGrafico'=> 'Perfil alumnado entre iguales', 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array('alumno1', 67),
										array('alumno2', 87),
										array('alumno3', 45),
										array('alumno4', 70),
										array('alumno5', 80)
									)
								);*/
						
						break;		
					}	}								
				break;
				
			/*case 3:
				{switch ($tipo_opcion_radiobutton) {
					case 1: // perfil-atributos, alumno
						return array('tituloGrafico'=> 'Perfil atributos por alumno', 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array('atributo1', 67),
										array('atributo2', 87),
										array('atributo3', 45),
										array('atributo4', 45)
									)
								);
						
						break;
						
					case 2: // perfil-atributos, grupo
						return array('tituloGrafico'=> 'Perfil atributos por grupo', 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array('atributo1', 67, 23, 43),
										array('atributo2', 87, 45, 76),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo3', 45, 34, 93),
										array('atributo4', 45, 54, 86)
									)
								);
						
						break;
						
					case 3: // perfil-atributos, clase
						return array('tituloGrafico'=> 'Perfil atributos por clase', 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array('atributo1', 67, 23, 43),
										array('atributo2', 87, 45, 76),
										array('atributo3', 45, 34, 93),
										array('atributo4', 45, 54, 86)
									)
								);
						
						break;
						}}
				break;*/
		}				
	}

	$tipo_grafico = $_GET['tipo_grafico'];
	$id_tarea = $_GET['id_tarea'];	
	$tipo_opcion_radiobutton = $_GET['tipo_opcion_radiobutton'];
	$id_alumnos = $_GET['id_alumnos'];
	$id_grupo = $_GET['id_grupo'];
	$id_alumno = $_GET['id_alumno'];
	$id_modalidad = $_GET['id_modalidad'];	
	$idCurso = $_GET['idCurso'];

	$resultado = query_grafica($tipo_grafico, $tipo_opcion_radiobutton, $id_tarea, $id_alumnos, $id_grupo, $id_alumno, $id_modalidad, $idCurso); 
	$jsondata['status'] = (count($resultado)>0);
	$jsondata['result'] = $resultado;  
	
	echo json_encode($jsondata); 
?>