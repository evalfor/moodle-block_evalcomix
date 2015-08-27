<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>
 */
 
include_once($CFG->dirroot."/blocks/evalcomix/configeval.php");
include_once($CFG->dirroot."/blocks/evalcomix/classes/evalcomix_tasks.php");
include_once($CFG->dirroot."/blocks/evalcomix/classes/evalcomix_assessments.php");
include_once($CFG->dirroot."/blocks/evalcomix/classes/evalcomix_modes.php");
global $DB;
		
		
if(isset($grade->grade_item)){
	//$block = $DB->get_record('modules', array('name' => $grade->grade_item->itemmodule));
	
	//$cm = $DB->get_record('course_modules', array('course' => $courseid, 'module' => $block->id, 'instance' => $grade->grade_item->iteminstance));
	//preparing variables	    
			
	$module = $grade->grade_item->itemmodule;
	//$instance = $cm->id;
	$inst = $grade->grade_item->iteminstance;
	$instance = $cms[$module][$inst];
	$maxgrade = $grade->grade_item->grademax;
	$student = $userid;	
	$grade_old = null;
			
	$evalcomixable = false;
	if(!empty($tasks[$instance]->visible) && isset($finalgrades[$instance][$student])){
		$finalgrade = $finalgrades[$instance][$student];
		if ($finalgrades[$instance][$student] >= 0){
			$evalcomixable = true;
		}
		
		if (!$grade_item = grade_item::fetch(array('iteminstance'=>$grade->grade_item->iteminstance, 'itemmodule'=>$module, 'courseid' => $courseid, 'itemnumber'=>'0'))) {
			error('Can not find grade_item 1');
		}
		$multfactor = $grade_item->multfactor;
		$plusfactor = $grade_item->plusfactor;
		
		if($evalcomixable){
			$finalgrade = $finalgrade * $maxgrade / 100;		
					
			if($gradeval != '-' && $gradeval != '' && is_numeric($gradeval)){
				$grade_old = new grade_grade(array('itemid'=>$grade_item->id, 'userid'=>$student));
				$grade_original = $grade_old->rawgrade;
				$gradeval = ($finalgrade + $grade_original) / 2;
				$gradeval *= $multfactor;
				$gradeval += $plusfactor;
				$grademax = $grade_item->grademax;

				$maxcoef = isset($CFG->gradeoverhundredprocentmax) ? $CFG->gradeoverhundredprocentmax : 10; // 1000% max by default

				if (!empty($CFG->unlimitedgrades)) {
					$grademax = $grademax * $maxcoef;
				} else if ($grade_item->is_category_item() or $grade_item->is_course_item()) {
					$category = $grade_item->load_item_category();
					if ($category->aggregation >= 100) {
						// grade >100% hack
						$grademax = $grademax * $maxcoef;
					}
				}
				if($gradeval > $grademax){
					$gradeval = $grademax;
				}
				if($gradeval < $grade_item->grademin){
					$gradeval = $grade_item->grademin;
				}
			}
			elseif(($gradeval == '-' || $gradeval == '')){
				$grade_old = new grade_grade(array('itemid'=>$grade_item->id, 'userid'=>$student));
				$gradeval = $finalgrade;
				$gradeval *= $multfactor;
				$gradeval += $plusfactor;
				if($gradeval > 100){
					$gradeval = 100;
				}
			}
				
			if(!$grade_item->scaleid && $grade->grade_item->itemnumber == 0){
				$overridden = $grade_old->overridden;
				$grade_item->update_final_grade($student, $gradeval, 'evalcomixAdd');
				$grade = new grade_grade(array('itemid'=>$grade_item->id, 'userid'=>$student));
				$grade->overridden = $overridden;
				$grade->update('EvalcomixUpdate');
			}
		}
	}
}				
