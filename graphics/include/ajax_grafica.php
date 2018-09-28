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


// Función encargada de recoger los datos que van a ser pintados en el gráfico
// $tipografico: 1 (perfil-tareas), 2 (perfil-alumnado), 3 (perfil-atributos)
// $tipoopcionradiobutton: describe que opcion hemos seleccionado en el radiobutton.
// - para las graficas perfil-tareas y perfil-atributos las opciones podrían ser: 1 (alumno), 2 (grupo), 3 (clase)
// - para las graficas perfil-alumnado las opciones podrían ser: 1 (entre-profesores), 2 (entre-iguales)
// $idalumnos: ids de los alumno checkeados
// $idgrupo: id del grupo por el que filtrar, en el caso de que el valor sea -1 quiere decir que es del tipo null
// $idalumno: id del alumno por el que filtrar, en el caso de que el valor sea -1 quiere decir que es del tipo null
// $idmodalidad: id de la modalidad por la que filtrar, en el caso de que el valor sea -1 quiere decir que es del tipo null
// $idtarea: id de la asociada por la que filtrar, en el caso de que el valor sea -1 quiere decir que es del tipo null
// $id_curso: id del curso sobre el que se mostrarán los datos de las gráficas
require_once('../../../../config.php');
require_login();
function query_grafica($tipografico, $tipoopcionradiobutton, $idtarea, $idalumnos, $idgrupo, $idalumno, $idmodalidad, $idcurso) {
    global $CFG;

    switch ($tipografico) {
        case 1: {
            switch ($tipoopcionradiobutton) {
                case 1: // Perfil-tarea, alumno.
                    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
                    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
                    require_once($CFG->dirroot .'/blocks/evalcomix/classes/calculator_average.php');
                    $assessments = evalcomix_assessments::get_assessments_by_modality($idtarea, $idalumno);
                    if ($assessments) {
                        // $teachergrade = evalcomix_assessments::calculate_gradearray($assessments->teacherassessments);
                        $teachergrades = evalcomix_assessments::calculate_grades($assessments->teacherassessments);
                        $peergrades = evalcomix_assessments::calculate_grades($assessments->peerassessments);

                        $arrayteacher = array(get_string('teachermod', 'block_evalcomix'));
                        if ($teachergrades) {
                            foreach ($teachergrades as $teachergrade) {
                                $arrayteacher[] = (int)$teachergrade;
                            }
                        }

                        $arraypeer = array(get_string('peermod', 'block_evalcomix'));
                        if ($peergrades) {
                            foreach ($peergrades as $peergrade) {
                                $arraypeer[] = (int)$peergrade;
                            }
                        }

                        $selfassessment = $assessments->selfassessment;
                        $arrayself = array(get_string('selfmod', 'block_evalcomix'));
                        if ($selfassessment) {
                            $arrayself[] = (int)$selfassessment->grade;
                        }

                        return array('tituloGrafico' => array(get_string('profile_task_by_student', 'block_evalcomix')),
                            'min' => 0,
                            'max' => 100,
                            'datos' => array(
                                $arrayteacher,
                                $arrayself,
                                $arraypeer,
                            )
                        );
                    } else {
                        return array('tituloGrafico' => array(get_string('profile_task_by_student', 'block_evalcomix')),
                                'min' => 0,
                                'max' => 100,
                                'datos' => array(
                                    array(get_string('no_datas', 'block_evalcomix'), 0)
                                )
                            );
                    }
                    /*return array('tituloGrafico' => 'Gráfica tarea por alumno',
                        'min' => 0,
                        'max' => 100,
                        'datos' => array(
                            array('profesor', 72.5),
                            array('autoevaluacion', 70),
                            array('entre iguales', 66.6),
                        )
                    );*/

                break;
                case 2:{ // Perfil-tarea, grupo.
                    $idstudents = explode(',', $idalumnos);
                    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
                    require_once($CFG->dirroot .'/blocks/evalcomix/classes/calculator_average.php');
                    if ($idstudents != $idalumnos) {
                        $tgrades[0] = get_string('teachermod', 'block_evalcomix');
                        $pgrades[0] = get_string('peermod', 'block_evalcomix');
                        $sgrades[0] = get_string('selfmod', 'block_evalcomix');
                        foreach ($idstudents as $idstudent) {
                            if ($idstudent == '-1') {
                                continue;
                            }
                            $assessments = evalcomix_assessments::get_assessments_by_modality($idtarea, (int)$idstudent);
                            if ($assessments) {
                                if (!empty($assessments->teacherassessments)) {
                                    $tgrade = evalcomix_assessments::calculate_gradearray($assessments->teacherassessments);
                                    array_push($tgrades, $tgrade);
                                }
                                if (!empty($assessments->peerassessments)) {
                                    $pgrade = evalcomix_assessments::calculate_gradearray($assessments->peerassessments);
                                    array_push($pgrades, $pgrade);
                                }
                                if ($assessments->selfassessment) {
                                    $selfassessment = $assessments->selfassessment;
                                    $sgrade = (int)$selfassessment->grade;
                                    array_push($sgrades, $sgrade);
                                }
                            }
                        }
                        /*if (!isset($tgrades[1])) {
                            array_push($tgrades, 0);
                        }
                        if (!isset($pgrades[1])) {
                            array_push($pgrades, 0);
                        }
                        if (!isset($sgrades[1])) {
                            array_push($sgrades, 0);
                        }*/
                        return array('tituloGrafico' => array(get_string('profile_task_by_group', 'block_evalcomix')),
                                    'min' => 0,
                                    'max' => 100,
                                    'datos' => array(
                                        $tgrades,
                                        $sgrades,
                                        $pgrades,
                                    )
                                );
                        /* PRUEBAS
                        return array('tituloGrafico' => array(get_string('profile_task_by_group', 'block_evalcomix')),
                            'min' => 0,
                            'max' => 100,
                            'datos' => array(
                                array("Evaluación del Profesor",100,75,50,25),
                                array("Autoevaluación",0),
                                array("Evaluación entre Iguales",0),
                            )
                        );*/
                            /*return array('tituloGrafico' => 'Perfil tarea por grupo',
                                'min' => 0,
                                'max' => 100,
                                'datos' => array(
                                    array('profesor', 67, 85, 99, 100),
                                    array('autoevaluacion', 87, 70, 67, 100),
                                    array('entre iguales', 45, 70, 80, 100),
                                )
                            );*/

                    } else {
                        return array('tituloGrafico' => array(get_string('profile_task_by_group', 'block_evalcomix')),
                                'min' => 0,
                                'max' => 100,
                                'datos' => array(
                                    array(get_string('no_datas', 'block_evalcomix'), 0)
                                )
                            );
                    }
                    /* DATOS DE ITELLIGENT
                    return array('tituloGrafico' => 'Perfil tarea por grupo',
                                'min' => 0,
                                'max' => 100,
                                'datos' => array(
                                    array('profesor', 67, 85, 99),
                                    array('autoevaluacion', 87, 70, 67),
                                    array('entre iguales', 45, 70, 80),
                                )
                            );
                    */
                }break;

                case 3: // Perfil-tarea, clase.
                    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
                    require_once($CFG->dirroot .'/blocks/evalcomix/classes/calculator_average.php');
                    $students = evalcomix_assessments::get_students_assessed($idtarea);
                    if ($students) {
                        $tgrades[0] = get_string('teachermod', 'block_evalcomix');
                        $pgrades[0] = get_string('peermod', 'block_evalcomix');
                        $sgrades[0] = get_string('selfmod', 'block_evalcomix');
                        foreach ($students as $student) {
                            $assessments = evalcomix_assessments::get_assessments_by_modality($idtarea, $student);
                            if (!empty($assessments->teacherassessments)) {
                                $tgrade = evalcomix_assessments::calculate_gradearray($assessments->teacherassessments);
                                array_push($tgrades, $tgrade);
                            }

                            if (!empty($assessments->peerassessments)) {
                                $pgrade = evalcomix_assessments::calculate_gradearray($assessments->peerassessments);
                                array_push($pgrades, $pgrade);
                            }

                            if ($assessments->selfassessment) {
                                $selfassessment = $assessments->selfassessment;
                                $sgrade = (int)$selfassessment->grade;
                                array_push($sgrades, $sgrade);
                            }
                        }
                        /*if (!isset($tgrades[1])) {
                            array_push($tgrades, 0);
                        }
                        if (!isset($pgrades[1])) {
                            array_push($pgrades, 0);
                        }
                        if (!isset($sgrades[1])) {
                            array_push($sgrades, 0);
                        }*/
                        return array('tituloGrafico' => array(get_string('profile_task_by_course', 'block_evalcomix')),
                                    'min' => 0,
                                    'max' => 100,
                                    'datos' => array(
                                        $tgrades,
                                        $sgrades,
                                        $pgrades,
                                    )
                                );

                        /*return array('tituloGrafico' => 'Perfil tarea por clase',
                                'min' => 0,
                                'max' => 100,
                                'datos' => array(
                                    array('profesor', 67, 85, 99),
                                    array('autoevaluacion', 87, 70, 67),
                                    array('entre iguales', 45, 70, 80),
                                )
                            );
                        */
                    } else {
                        return array('tituloGrafico' => array(get_string('profile_task_by_course', 'block_evalcomix')),
                                'min' => 0,
                                'max' => 100,
                                'datos' => array(
                                    array(get_string('no_datas', 'block_evalcomix'), 0)
                                )
                            );
                    }

                    break;
            }
        }
        break;

        case 2:
            {switch ($tipoopcionradiobutton) {
                case 1: // Perfil-alumnado, entre profesores.
                    global $DB;
                    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
                    $assessments = evalcomix_assessments::get_assessments_by_modality($idtarea, $idalumno);
                    $teachergrades = array();
                    if (!empty($assessments->teacherassessments)) {
                        foreach ($assessments->teacherassessments as $tassessment) {
                            $teacher = $DB->get_record('user', array('id' => $tassessment->assessorid));
                            $teacherlabel = $teacher->lastname . ', ' . $teacher->firstname;
                            $item = array($teacherlabel, $tassessment->grade);
                            array_push($teachergrades, $item);
                        }
                        return array('tituloGrafico' => array(get_string('profile_student_by_teacher', 'block_evalcomix')),
                                'min' => 0,
                                'max' => 100,
                                'datos' => $teachergrades
                                );
                    } else {
                        return array('tituloGrafico' => array(get_string('profile_student_by_teacher', 'block_evalcomix')),
                                'min' => 0,
                                'max' => 100,
                                'datos' => array(array(get_string('no_datas', 'block_evalcomix'), 0))
                                );
                    }                       /*return array('tituloGrafico' => 'Perfil alumnado entre profesores',
                                    'min' => 0,
                                    'max' => 100,
                                    'datos' => array(
                                        array('profesor1', 67),
                                        array('profesor2', 87),
                                        array('profesor3', 45),
                                        array('profesor4', 70),
                                        array('profesor5', 80)
                                    )
                                );*/

                    break;

                case 2: // Perfil-alumnado, entre iguales.
                    global $DB;
                    require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');
                    $assessments = evalcomix_assessments::get_assessments_by_modality($idtarea, $idalumno);
                    $peergrades = array();
                    if (!empty($assessments->peerassessments)) {
                        foreach ($assessments->peerassessments as $passessment) {
                            $peer = $DB->get_record('user', array('id' => $passessment->assessorid));
                            $peerlabel = $peer->lastname . ', ' . $peer->firstname;
                            $item = array($peerlabel, (int)$passessment->grade);
                            array_push($peergrades, $item);
                        }
                        return array('tituloGrafico' => array(get_string('profile_student_by_group', 'block_evalcomix')),
                                    'min' => 0,
                                    'max' => 100,
                                    'datos' => $peergrades
                                );
                    } else {
                        return array('tituloGrafico' => array(get_string('profile_student_by_group', 'block_evalcomix')),
                                    'min' => 0,
                                    'max' => 100,
                                    'datos' => array(array(get_string('no_datas', 'block_evalcomix'), 0))
                                );
                    }
                    /*return array('tituloGrafico' => 'Perfil alumnado entre iguales',
                                    'min' => 0,
                                    'max' => 100,
                                    'datos' => array(
                                        array('alumno1', 67),
                                        array('alumno2', 87),
                                        array('alumno3', 45),
                                        array('alumno4', 70),
                                        array('alumno5', 80)
                                    )
                                );*/

                break;
            }
        }
            break;

            /*case 3:
                {switch ($tipoopcionradiobutton) {
                case 1: // perfil-atributos, alumno
                    return array('tituloGrafico' => 'Perfil atributos por alumno',
                                'min' => 0,
                                'max' => 100,
                                'datos' => array(
                                    array('atributo1', 67),
                                    array('atributo2', 87),
                                    array('atributo3', 45),
                                    array('atributo4', 45)
                                )
                            );

                    break;

                case 2: // perfil-atributos, grupo
                    return array('tituloGrafico' => 'Perfil atributos por grupo',
                                'min' => 0,
                            'max' => 100,
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
                    return array('tituloGrafico' => 'Perfil atributos por clase',
                                'min' => 0,
                                'max' => 100,
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
$tipografico = $_GET['tipo_grafico'];
$idtarea = $_GET['id_tarea'];
$tipoopcionradiobutton = $_GET['tipo_opcion_radiobutton'];
$idalumnos = $_GET['id_alumnos'];
$idgrupo = $_GET['id_grupo'];
$idalumno = $_GET['id_alumno'];
$idmodalidad = $_GET['id_modalidad'];
$idcurso = $_GET['idCurso'];

$resultado = query_grafica($tipografico, $tipoopcionradiobutton, $idtarea, $idalumnos, $idgrupo, $idalumno, $idmodalidad, $idcurso);
$jsondata['status'] = (count($resultado) > 0);
$jsondata['result'] = $resultado;

echo json_encode($jsondata);
