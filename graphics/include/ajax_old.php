<?php

/*
	Autores:
		- Gonzalo Saavedra Postigo
		- David Mariscal Martínez
		- Aris Burgos Pintos
	Organización: ITelligent Information Technologies, S.L.
	Licencia: gpl-3.0
*/

	function query($type, $id, $idCurso){		
					
		switch($type){
		
			// 1.- Listado de tareas
			case 1:
				return array(					
					array('id'=> 1, 'nombre'=> 'tarea1'),
					array('id'=> 2, 'nombre'=>'tarea2'),
					array('id'=> 3, 'nombre'=>'tarea3'),
					array('id'=> 4, 'nombre'=>'tarea4')
				);
				break;
				
			// 2.- Listado de grupos asociados a una tarea, el id correspondería a una tarea y 
			// habría que seleccionar los grupos asociados a dicho id			
			case 2:
			    return array(					
					array('id'=> 1, 'nombre'=> 'grupo1'),
					array('id'=> 2, 'nombre'=>'grupo2'),
					array('id'=> 3, 'nombre'=>'grupo3'),
					array('id'=> 4, 'nombre'=>'grupo4')
				);

				break;
				
			// 3.- Listado de modalidades disponibles
			case 3:
				return array(					
					array('id'=> 1, 'nombre'=> 'profesor'),
					array('id'=> 2, 'nombre'=>'autoevaluacion'),
					array('id'=> 3, 'nombre'=>'entre iguales'),					
				);
				break;
			
			// 4.- Listado de los alumnos asociados a una tarea
			// Llamada de ejemplo: Grafica perfil-tarea al seleccionar por alumno y tarea		
			// Llamada de ejemplo: Grafica perfil-tarea al seleccionar por grupo y a elegir un grupo			
			case 4:
				$pos = strpos($id,';');  
				if($pos==false){		//Entra si no estamos en grupo	
					return array(					
						array('id'=> 1, 'nombre'=> 'alumnoTarea1'),
						array('id'=> 2, 'nombre'=>'alumnoTarea2'),
						array('id'=> 3, 'nombre'=>'alumnoTarea3'),
						array('id'=> 4, 'nombre'=>'alumnoTarea4'),
						array('id'=> 4, 'nombre'=>'alumnoTarea4')
					);
				}else{   //Entra si estamos en un grupo
					$idtarea = substr($id,0,$pos);
					$idgrupo = substr($id,$pos+1);
					
					if($idgrupo == -2){   //Caso de sin grupo (Todos los alumnos, idCurso)
						return array(					
								array('id'=> 1, 'nombre'=> 'alumnoTareaGrupo1'),
								array('id'=> 2, 'nombre'=>'alumnoTareaGrupo2'),
								array('id'=> 3, 'nombre'=>'alumnoTareaGrupo3'),
								array('id'=> 4, 'nombre'=>'alumnoTareaGrupo4'),
								array('id'=> 5, 'nombre'=>'alumnoTareaGrupo5'),
								array('id'=> 6, 'nombre'=>'alumnoTareaGrupo6'),
								array('id'=> 7, 'nombre'=>'alumnoTareaGrupo4')
						);
					}else{  //Caso de un grupo (Alumnos de grupo idgrupo)
							return array(					
								array('id'=> 1, 'nombre'=> 'alumnoTareaGrupo1'),
								array('id'=> 2, 'nombre'=>'alumnoTareaGrupo2'),
								array('id'=> 3, 'nombre'=>'alumnoTareaGrupo3'),
								array('id'=> 4, 'nombre'=>'alumnoTareaGrupo4')
							);
					}
				}
				break;
			default:
				return -1;
				break;
		}
	}

	$type = $_GET['type']; 
	$id = $_GET['id'];
	$idCurso = $_GET['idCurso'];

	$resultado = query($type, $id, $idCurso); 
	$jsondata['status'] = ($resultado!=-1);
	$jsondata['result'] = $resultado;  
	
	echo json_encode($jsondata); 

	

?>