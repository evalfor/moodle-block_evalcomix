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
	
	function query_grafica($tipo_grafico, $tipo_opcion_radiobutton, $id_tarea, $id_alumnos, $id_grupo, $id_alumno, $id_modalidad, $idCurso){		
		
					
					
			switch ($tipo_grafico) {
			case 1:
				{switch ($tipo_opcion_radiobutton) {
					case 1: // perfil-tarea, alumno
						return array('tituloGrafico'=> 'Perfil tarea por alumno', 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array('profesor', 72.5),
										array('autoevaluacion', 70),
										array('entre iguales', 10),
									)
								);
						
						break;
						
					case 2: // perfil-tarea, grupo
						return array('tituloGrafico'=> 'Perfil tarea por grupo', 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array('profesor', 67, 85, 99, 100),
										array('autoevaluacion', 87, 70, 67, 100),
										array('entre iguales', 45, 70, 80, 100),
									)
								);
						
						break;
						
					case 3: // perfil-tarea, clase
						return array('tituloGrafico'=> 'Perfil tarea por grupo', 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array('profesor',),
										array('autoevaluacion', 95),
										array('entre iguales', 100,70,76,80,100,76,81,70,94,93,95,99,97,98,62,98,94,97,99,96,91,91),
									)
								);
						
						break;
						}}
				break;
				
			
			case 2:
				{switch ($tipo_opcion_radiobutton) {
					case 1: // perfil-alumnado, entre profesores
						return array('tituloGrafico'=> 'Perfil alumnado entre profesores', 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array('profesor1', 67),
										array('profesor2', 87),
										array('profesor3', 45),
										array('profesor4', 70),
										array('profesor5', 80)
									)
								);
						
						break;
						
					case 2: // perfil-alumnado, entre iguales
						return array('tituloGrafico'=> 'Perfil alumnado entre iguales', 
									'min'=> 0, 
									'max'=> 100, 					
									'datos' => array(
										array('alumno1', 67),
										array('alumno2', 87),
										array('alumno3', 45),
										array('alumno4', 70),
										array('alumno5', 80)
									)
								);
						
						break;		
					}	}								
				break;
				
			case 3:
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
				break;
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