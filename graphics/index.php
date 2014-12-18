<?php
require_once('../../../config.php');	
global $CFG;
require_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');	
require_once($CFG->dirroot . '/blocks/evalcomix/assessment/lib.php');

$courseid 	   = required_param('id', PARAM_INT);        // course id
$mode = optional_param('mode', '', PARAM_INT); 

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
	print_error('nocourseid');
}

global $OUTPUT;
	
$PAGE->set_url(new moodle_url('/blocks/evalcomix/graphics/index.php', array('id' => $courseid)));
$PAGE->set_pagelayout('incourse');
// Print the header
$PAGE->navbar->add('evalcomix', new moodle_url('../assessment/index.php?id='.$courseid));

$buttons = null;

require_login($course);
//$context = get_context_instance(CONTEXT_COURSE, $course->id);
$context = context_course::instance($course->id);

print_grade_page_head($course->id, 'report', 'grader', null, false, $buttons, false);

echo '				
	<center>
		<div><img src="'. $CFG->wwwroot . EVXLOGOROOT .'" width="230" alt="EvalCOMIX"/></div><br>
		<div><input type="button" style="color:#333333" value="'. get_string('assesssection', 'block_evalcomix').'" onclick="location.href=\''. $CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$courseid .'\'"/></div><br>
	</center>
	
	<div>
		<ul style="margin:5px">
			<li class="pestania" id="pstPT"><a href="?mode=1"><a class="enlacetitulografica" href="?mode=1&id='.$courseid.'">Gráfica Tarea</a></li>
			<li class="pestania" id="pstPA"><a class="enlacetitulografica" href="?mode=2&id='.$courseid.'">Gráfica Alumnado</a></li>
			<li class="pestania" id="pstPAtr" style="display:none;"><a class="enlacetitulografica" href="?mode=3">Gráfica Perfil Atributos</a></li>
			<li class="pestania" id="pstPAleat" style="display:none;"><a class="enlacetitulografica" href="?mode=33">Modo Aleatorio</a></li>
		</ul>
	</div>
								
	<div style="float: left; width: 100%; padding-top: 15px; border: solid 1px #C0C0C0">
';
 
$graphic = 'graphic';
include ('graphic_google.php');  

$idCurso = $courseid;
switch ($mode){
	case '1': {
		$graphic::draw_perfil_tarea($idCurso);          
	} break;
					  
	case '2': {
		$graphic::draw_perfil_alumnado($idCurso);          
	} break;
					  
	case '3': {
		$graphic::draw_perfil_atributos($idCurso);          
	} break;
					  
	default: {      
		$min_valor = 0;
		$max_valor = 100;
		$array_data =  array( 
			array("profesor", 75 , 80, 65),
			array("autoevaluacion", 55 , 25, 76),
			array("entre iguales", 67 , 65, 43) 
		); 
						
		$blnIncluirLimites = true;
		$blnIncluirDispersion = true;   

		$titulo = get_string('titlegraficbegin','block_evalcomix');
		$valor_limites = 2;
			
		$graphic::draw_perfil_tarea_sola($titulo, 
										 $min_valor, 
										 $max_valor, 
										 $array_data, 
										 $blnIncluirLimites, 
										 $blnIncluirDispersion,
										 $valor_limites);          
	} break;
					
}
echo '
		
	</div>';
	
echo $OUTPUT->footer();
