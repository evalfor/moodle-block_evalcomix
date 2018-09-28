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
require_once('../../../../config.php');
require_login();
/*
    Autores:
        - Gonzalo Saavedra Postigo
        - David Mariscal Martínez
        - Aris Burgos Pintos
    Organización: ITelligent Information Technologies, S.L.
    Licencia: gpl-3.0
*/

function query($type, $id, $idcurso) {
    switch($type) {

        // 1.- Listado de tareas.
        case 1:{
            global $DB;

            require_once('../../classes/evalcomix_tasks.php');
            $tasks = evalcomix_tasks::get_moodle_course_tasks($idcurso);
            return $tasks;
            /*  return array(
                array('id'=> 1, 'nombre'=> 'tarea1'),
                array('id'=> 2, 'nombre'=>'tarea2'),
                array('id'=> 3, 'nombre'=>'tarea3'),
                array('id'=> 4, 'nombre'=>'tarea4')
            );*/
        }break;

        // 2.- Listado de grupos asociados a una tarea, el id correspondería a una tarea y
        // habría que seleccionar los grupos asociados a dicho id.
        case 2:{
            global $DB;
            $groups = $DB->get_records('groups', array('courseid' => $idcurso));
            $arraygroups = array();
            foreach ($groups as $group) {
                $arraygroups[] = array('id' => $group->id, 'nombre' => $group->name);
            }
            return $arraygroups;
            /*return array(
                array('id'=> 1, 'nombre'=> 'grupo1'),
                array('id'=> 2, 'nombre'=>'grupo2'),
                array('id'=> 3, 'nombre'=>'grupo3'),
                array('id'=> 4, 'nombre'=>'grupo4')
            );*/
        }break;

        // 3.- Listado de modalidades disponibles.
        case 3:
            return array(
                array('id' => 1, 'nombre' => 'profesor'),
                array('id' => 2, 'nombre' => 'autoevaluacion'),
                array('id' => 3, 'nombre' => 'entre iguales'),
            );
        break;

        // 4.- Listado de los alumnos asociados a una tarea
        // Llamada de ejemplo: Grafica perfil-tarea al seleccionar por alumno y tarea
        // Llamada de ejemplo: Grafica perfil-tarea al seleccionar por grupo y a elegir un grupo            .
        case 4:
            $pos = strpos($id, ';');
            if ($pos == false) {      // Entra si no estamos en grupo.
                global $DB, $CFG;
                require_once('../../classes/evalcomix_assessments.php');
                $users = evalcomix_assessments::get_students_assessed($id);
                $arraystudents = array();
                foreach ($users as $user) {
                    $student = $DB->get_record('user', array('id' => $user));
                    $arraystudents[] = array('id' => $user, 'nombre' => ($student->lastname . ', ' . $student->firstname));
                }
                return $arraystudents;
                /*return array(
                    array('id'=> 1, 'nombre'=> 'alumnoTarea1'),
                    array('id'=> 2, 'nombre'=>'alumnoTarea2'),
                    array('id'=> 3, 'nombre'=>'alumnoTarea3'),
                    array('id'=> 4, 'nombre'=>'alumnoTarea4'),
                    array('id'=> 4, 'nombre'=>'alumnoTarea4')
                );*/
            } else {   // Entra si estamos en un grupo.
                $idtarea = substr($id, 0, $pos);
                $idgrupo = substr($id, $pos + 1);

                if ($idgrupo == -2) {   // Caso de sin grupo (Todos los alumnos, idcurso).
                    /*return array(
                            array('id'=> 1, 'nombre'=> 'alumnoTareaGrupo1'),
                            array('id'=> 2, 'nombre'=>'alumnoTareaGrupo2'),
                            array('id'=> 3, 'nombre'=>'alumnoTareaGrupo3'),
                            array('id'=> 4, 'nombre'=>'alumnoTareaGrupo4'),
                            array('id'=> 5, 'nombre'=>'alumnoTareaGrupo5'),
                            array('id'=> 6, 'nombre'=>'alumnoTareaGrupo6'),
                            array('id'=> 7, 'nombre'=>'alumnoTareaGrupo4')
                    );*/
                    global $DB, $CFG;
                    require_once('../../classes/evalcomix_assessments.php');
                    $users = evalcomix_assessments::get_students_assessed($idtarea);
                    $arraystudents = array();
                    foreach ($users as $user) {
                        $student = $DB->get_record('user', array('id' => $user));
                        $arraystudents[] = array('id' => $user, 'nombre' => ($student->lastname . ', ' . $student->firstname));
                    }
                    return $arraystudents;
                } else {  // Caso de un grupo (Alumnos de grupo idgrupo).
                    $idtarea = substr($id, 0, $pos);
                    $idgrupo = substr($id, $pos + 1);
                    global $DB, $CFG;
                    require_once('../../classes/evalcomix_assessments.php');
                    $users = evalcomix_assessments::get_students_assessed($idtarea);
                    $groupsmember = $DB->get_records('groups_members', array('groupid' => $idgrupo));
                    $membersids = array();
                    // Vamos comprobando para cada miembro del grupo si ha sido evaluado en la actividad.
                    foreach ($groupsmember as $member) {
                        // $membersids[] = $member->userid;
                        if (in_array($member->userid, $users)) {
                            $membersids[] = $member->userid;
                        }
                    }

                    // $array_intersection = array_intersect_assoc($users, $membersids);

                    $arraystudents = array();
                    // foreach ($array_intersection as $student) {
                    foreach ($membersids as $student) {
                        $user = $DB->get_record('user', array('id' => $student));
                        $arraystudents[] = array('id' => $user->id, 'nombre' => ($user->lastname.', '.$user->firstname));
                    }

                    /*global $DB;
                    $groupsmember = $DB->get_records('groups_members', array('groupid' => $idgrupo));
                    $arraystudents = array();
                    foreach ($groupsmember as $student) {
                        $user = $DB->get_record('user', array('id' => $student->userid));
                        $arraystudents[] = array('id' => $user->id, 'nombre' =>($user->lastname.', '.$user->firstname));
                    }*/
                    return $arraystudents;
                        /*return array(
                            array('id'=> 1, 'nombre'=> 'alumnoTareaGrupo1'),
                            array('id'=> 2, 'nombre'=>'alumnoTareaGrupo2'),
                            array('id'=> 3, 'nombre'=>'alumnoTareaGrupo3'),
                            array('id'=> 4, 'nombre'=>'alumnoTareaGrupo4')
                        );*/
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
$idcurso = $_GET['idCurso'];

$resultado = query($type, $id, $idcurso);
$jsondata['status'] = ($resultado != -1);
$jsondata['result'] = $resultado;

echo json_encode($jsondata);
