<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
 
	include_once($CFG->dirroot . '/lib/dmllib.php');
	//Comprobar si existe el evalcomixAdd para el usuario e item correspondiente
	global $DB;
	if($rst = $DB->get_records('grade_grades_history', array('userid' => $userid, 'itemid' => $grade->grade_item->id, 'source' => 'evalcomixAdd')))
	{
		$sql2 = "SELECT id, finalgrade FROM " . $CFG->prefix . "grade_grades_history 
					WHERE userid=$userid AND itemid=".$grade->grade_item->id." AND source like 'mod/%'
					ORDER BY id DESC";
		$gradeEvalcomix2 = $DB->get_records_sql($sql2);
		if($gradeEvalcomix2){//Si existe reestablecemos el valor
			foreach($gradeEvalcomix2 as $grEval){
				$gradeval = $grEval->finalgrade;
				break;
			}
		}
		else{
			//Si existe, comprobar si existe una tupla con source=evalcomixDelete para ese usuario e item
			$sql = "SELECT id, finalgrade 
				FROM " . $CFG->prefix . "grade_grades_history 
				WHERE id IN 
						(SELECT MAX(id) 
						FROM 
							(SELECT id, finalgrade 
							FROM " . $CFG->prefix . "grade_grades_history 
							WHERE action = 2 AND source='evalcomixDelete' 
								  AND userid=$userid AND itemid=".$grade->grade_item->id.") AS t)";
	
			$gradeEvalcomix = $DB->get_records_sql($sql);
			if($gradeEvalcomix){
				//Si existe reestablecemos el valor
				foreach($gradeEvalcomix as $grEval){
					$gradeval = $grEval->finalgrade;
				}
			}
			else{//Si no existe, obtener el valor anterior a EvalcomixAdd	
				$sql = "SELECT source, finalgrade FROM " . $CFG->prefix . "grade_grades_history 
					WHERE userid=$userid AND itemid=".$grade->grade_item->id." 
					ORDER BY id ASC";
				$gradeEvalcomix = $DB->get_records_sql($sql);
				if($gradeEvalcomix){//Si existe reestablecemos el valor
					foreach($gradeEvalcomix as $grEval){
						if($grEval->source == 'evalcomixAdd'){
							$gradeval = null;
						}
						else{
							$gradeval = $grEval->finalgrade;
						}
						break;
					}//foreach($gradeEvalcomix as $grEval)
				}//if($gradeEvalcomix)
			}//else
		}//else
	}//if($rst = get_record('grade_grades_history', 'userid', $userid, 'itemid', $grade->grade_item->id, 'source', 'evalcomixAdd'))
	
	//Comprobamos que el item actual no es un "resultado"
	if($grade->grade_item->itemnumber == 0){
		$modulo = $grade->grade_item->itemmodule;
		$instancia = $grade->grade_item->iteminstance;
		$maxgrade = $grade->grade_item->grademax;
		$student = $userid;	
		$courseid = $courseid;	
		if (!$grade_item = grade_item::fetch(array('iteminstance'=>$instancia, 'itemmodule'=>$modulo, 'courseid' => $courseid, 'itemnumber' => 0))) {
	   			error('Can not find grade_item 2');
		}
		$grade_old = new grade_grade(array('itemid'=>$grade_item->id, 'userid'=>$student));
		$overridden = $grade_old->overridden;
		$grade_item->update_final_grade($student, $gradeval, 'evalcomixDelete');
		$grade1 = new grade_grade(array('itemid'=>$grade_item->id, 'userid'=>$student));
		$grade1->overridden = $overridden;
		$grade1->update();
	}
