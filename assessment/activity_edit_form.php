<?php	
/**
* A page to configurate activity
*
* @package    block_evalcomix
* @author Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>, Claudia Ortega Gómez <claudia.ortega@uca.es>
* @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

require_once('../../../config.php');	
require_once('lib.php');
include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');

$courseid 	   = required_param('id', PARAM_INT);        // course id
$id = required_param('a', PARAM_INT);
require_login($courseid);

if ($id) {
	$cm = get_coursemodule_from_id('', $id, 0, false, MUST_EXIST);
	if (!$course = $DB->get_record('course', array('id' => $courseid))) {
		print_error('nocourseid');
	}
}

$tools = evalcomix_tool::get_tools($courseid);
$activity = get_activity_data($cm);
$datas = get_evalcomix_activity_data($courseid, $cm);
	
$toolEP = 0;
if(isset($datas['toolEP'])){
	$toolEP = $datas['toolEP'];
}
$weighingEP = 70;
if(isset($datas['weighingEP'])){
	$weighingEP = $datas['weighingEP'];
}
$disabledEP = '';
	
$toolAE = 0;
if(isset($datas['toolAE'])){
	$toolAE = $datas['toolAE'];
}
$weighingAE = 10;
if(isset($datas['weighingAE'])){
	$weighingAE = $datas['weighingAE'];
}
$disabledAE = '';
$availableAE = time();
if(isset($datas['availableAE'])){
	$availableAE = $datas['availableAE'];
}
$timedueAE = time() + 7*24*3600;
if(isset($datas['timedueAE'])){
	$timedueAE = $datas['timedueAE'];
}
	
$toolEI = 0;
if(isset($datas['toolEI'])){
	$toolEI = $datas['toolEI'];
}
$weighingEI = 20;
if(isset($datas['weighingEI'])){
	$weighingEI = $datas['weighingEI'];
}
$disabledEI = '';
$anonymousEI = 0;
if(isset($datas['anonymousEI'])){
	$anonymousEI = $datas['anonymousEI'];
}

$alwaysvisibleEI = 0;
if(isset($datas['alwaysvisibleEI'])){
	$alwaysvisibleEI = $datas['alwaysvisibleEI'];
}

$whoassessesEI = 0;
if(isset($datas['whoassessesEI'])){
	$whoassessesEI = $datas['whoassessesEI'];
}

$availableEI = time();
if(isset($datas['availableEI'])){
	$availableEI = $datas['availableEI'];
}
$timedueEI = time() + 7*24*3600;
if(isset($datas['timedueEI'])){
	$timedueEI = $datas['timedueEI'];
}
	
	
if(!isset($toolEP) || !$toolEP){
	$disabledEP = 'disabled';
}
if(!isset($toolAE) || !$toolAE){
	$disabledAE = 'disabled';
}
if(!isset($toolEI) || !$toolEI){
	$disabledEI = 'disabled';
}
//--------------------------------------------------------------------------------
	
global $OUTPUT;

$PAGE->set_url(new moodle_url('/blocks/evalcomix/assessment/activity_edit_form.php', array('id' => $courseid)));
$PAGE->navbar->add('evalcomix', new moodle_url('../assessment/index.php?id='.$courseid));

$context = context_course::instance($course->id);

//require_capability('block/evalcomix:edit', $context);
require_capability('moodle/block:edit', $context);

print_grade_page_head($COURSE->id, 'report', 'grader', null, false, '', false);

//Ln 199 antes era así
//<input type="hidden" id="maxgrade" name="maxgrade" value='.$activity->grade.'>

echo '			
		<center>
			<div><img src="'. $CFG->wwwroot . EVXLOGOROOT .'" width="230" alt="EvalCOMIX"/></div>
			<div><input type="button" style="color:#333333" value="'.get_string('designsection', 'block_evalcomix').'" onclick="location.href=\''. $CFG->wwwroot .'/blocks/evalcomix/tool/index.php?id='.$courseid .'\'"/></div>
		</center>
	';
	//-----------------------------------------------------------------------------------------------
	
echo '
		<script>
			function disabling(element, slavetag){
				if(element.value != 0)
					document.getElementById(slavetag).disabled = false;				
				else
					document.getElementById(slavetag).disabled = true;	
			}
			function disabling_group(element, slavetag){
				if(element.value != 0){
					document.getElementById("day_" + slavetag).disabled = false;				
					document.getElementById("month_" + slavetag).disabled = false;				
					document.getElementById("year_" + slavetag).disabled = false;				
					document.getElementById("hour_" + slavetag).disabled = false;				
					document.getElementById("minute_" + slavetag).disabled = false;				
				}
				else{
					document.getElementById("day_" + slavetag).disabled = true;				
					document.getElementById("month_" + slavetag).disabled = true;				
					document.getElementById("year_" + slavetag).disabled = true;				
					document.getElementById("hour_" + slavetag).disabled = true;				
					document.getElementById("minute_" + slavetag).disabled = true;				
				}
			}
			
			function check_weighing(element)
			{
				pon_EP = null;
				pon_AE = null;
				pon_EI = null;
				element_EP = document.getElementById("pon_EP");
				element_AE = document.getElementById("pon_AE");
				element_EI = document.getElementById("pon_EI");
				sum = 0;
				existanymode = false;
				
				if(document.getElementById("pon_EP").disabled == false){
					pon_EP = element_EP.value;
					sum += parseInt(pon_EP);
					existanymode = true;
				}
				if(document.getElementById("pon_AE").disabled == false){
					pon_AE = element_AE.value;
					sum += parseInt(pon_AE);
					existanymode = true;
				}
				if(document.getElementById("pon_EI").disabled == false){
					pon_EI = element_EI.value;
					sum += parseInt(pon_EI);
					existanymode = true;
				}
				//alert("pon_EP:"+pon_EP+" pon_AE:"+pon_AE+" pon_EI:"+pon_EI);
				//alert(sum);
				element_EI.style.color = "";
				element_AE.style.color = "";
				element_EP.style.color = "";
				element_EI.style.border = "";
				element_AE.style.border = "";
				element_EP.style.border = "";
				document.getElementById("error_weighing_greater").style.visibility = "hidden";
				document.getElementById("error_weighing_less").style.visibility = "hidden";
				document.getElementById("error_weighing_less").style.height = 0;
				document.getElementById("error_weighing_greater").style.height = "0";
				if(sum < 100 && existanymode == true){
					if(element){
						element.style.color =  "#ff0000";
						element.style.border = "1px solid #ff0000";					
					}
					document.getElementById("error_weighing_less").style.visibility = "visible";
					document.getElementById("error_weighing_less").style.height = "2em";
					return false;
				}
				else if(sum > 100){ 
					if(element){
						element.style.color =  "#ff0000";
						element.style.border = "1px solid #ff0000";
					}
					document.getElementById("error_weighing_greater").style.visibility = "visible";
					document.getElementById("error_weighing_less").style.height = "2em";
					return false;
				}
				return true;
			}  
  
		</script>
		<div style="text-align:center">
			<h1>'. $activity->name .'</h1>
			<form method="post" action="index.php?id='.$courseid.'">
				<input type="hidden" id="cmid" name="cmid" value='.$cm->id.'>
				<input type="hidden" id="maxgrade" name="maxgrade" value="100">
				<fieldset class="clearfix" id="Instrument" style="text-align:left; width:100%; border: 1px solid #d3d3d3; padding: 0em">
				<legend class="ftoggler" style="font-weight:bold">'. get_string('selinstrument', 'block_evalcomix'). $OUTPUT->help_icon('selinstrument', 'block_evalcomix').'</legend>
					<div style="padding: 1em">
						<table style="width:90%; background-color:inherit">';
if(empty($tools)){
	echo '
							<tr>
								<td colspan=2 style="text-align:center;color:#00658a">
									'. get_string('alertnotools', 'block_evalcomix').' 
									<br><a style="color:#00658a; font-weight:bold" href="'.$CFG->wwwroot.'/blocks/evalcomix/tool/index.php?id='.$courseid.'">'.get_string('designsection', 'block_evalcomix').'</a>";
								</td>
							</tr>';
}
echo '

<!---------------------------------------------------------------------------------------------------->							
<!--TEACHER-ASSESSMENT-------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------->

							<tr>
								<td style="text-align:right">' . get_string('teachermodality', 'block_evalcomix') . $OUTPUT->help_icon('teachermodality', 'block_evalcomix') . '</td>
								<td>
									<select style="width:100%" name="toolEP" id="id_toolEP" onchange="javascript:disabling(this, \'pon_EP\');">
										<option value="0">' . get_string('nuloption', 'block_evalcomix') . '</option>
	';
	
foreach($tools as $id_tool => $title_tool){
	if($title_tool == '-000_1'){
		continue;
	}
	$checkedEPtool = '';
	if($toolEP && $id_tool == $toolEP->id){
		$checkedEPtool = 'selected = "selected"';
	}
	echo '
										<option value="'. $id_tool .'" '. $checkedEPtool .'>'. $title_tool .'</option>
	';
}
	
echo '
									</select>
								</td>
							</tr>
	
							<tr>
								<td style="text-align:right">' . get_string('pon_EP', 'block_evalcomix') . $OUTPUT->help_icon('pon_EP', 'block_evalcomix') . '</td>
								<td>
';
$extraEP = $disabledEP . ' onchange="javascript:check_weighing(this);"';
echo add_select_range('pon_EP', 0, 100, $weighingEP, $extraEP);
	
echo '
		
								</td>
							</tr>
							
							<tr><td/><td/></tr>
<!---------------------------------------------------------------------------------------------------->							
<!--SELF-ASSESSMENT----------------------------------------------------------------------------------->							
<!---------------------------------------------------------------------------------------------------->
							<tr>
								<td style="text-align:right">' . get_string('selfmodality', 'block_evalcomix') .$OUTPUT->help_icon('selfmodality', 'block_evalcomix') . '</td>
								<td>
									<select style="width:100%" name="toolAE" id="id_toolAE" 
									onchange="javascript:disabling(this, \'pon_AE\');disabling_group(this, \'available_AE\');disabling_group(this, \'timedue_AE\');">
										<option value="0">' . get_string('nuloption', 'block_evalcomix') . '</option>
	';
	
foreach($tools as $id_tool => $title_tool){
	if($title_tool == '-000_1'){
		continue;
	}
	$checkedAEtool = '';
	if($toolAE && $id_tool == $toolAE->id){
		$checkedAEtool = 'selected = "selected"';
	}
	echo '
										<option value="'. $id_tool .'" '.$checkedAEtool.'>'. $title_tool .'</option>
	';
}
	
echo '
									</select>
								</td>
							</tr>
	
							<tr>
								<td style="text-align:right">' . get_string('pon_AE', 'block_evalcomix') . $OUTPUT->help_icon('pon_AE', 'block_evalcomix') . '</td>
								<td>
';
	
$extraAE = $disabledAE . ' onchange="javascript:check_weighing(this);"';
echo add_select_range('pon_AE', 0, 100, $weighingAE, $extraAE);
	
echo '
		
								</td>
							</tr>
							
							<tr>
								<td style="text-align:right">' . get_string('availabledate_AE', 'block_evalcomix') . $OUTPUT->help_icon('availabledate_AE', 'block_evalcomix') . '</td>
								<td>
';
	
echo add_date_time_selector('available_AE', $availableAE, $disabledAE) . '<br>';
	
echo '
								</td>
							</tr>
							
							<tr>
								<td style="text-align:right">' . get_string('timedue_AE', 'block_evalcomix') . $OUTPUT->help_icon('timedue_AE', 'block_evalcomix') . '</td>
								<td>
';
	
echo add_date_time_selector('timedue_AE', $timedueAE, $disabledAE) . '<br>';
	
echo '
								</td>
							</tr>
							
							<tr><td/><td/></tr>

<!---------------------------------------------------------------------------------------------------->							
<!--PEER-ASSESSMENT----------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------->
							
							<tr>
								<td style="text-align:right">' . get_string('peermodality', 'block_evalcomix') .$OUTPUT->help_icon('peermodality', 'block_evalcomix') . '</td>
								<td>
									<select style="width:100%" name="toolEI" id="toolEI"
									onchange="javascript:disabling(this, \'pon_EI\');disabling(this, \'anonymousEI\');disabling_group(this, \'available_EI\');disabling_group(this, \'timedue_EI\');disabling(this, \'alwaysvisibleEI\');disabling(this, \'anystudent_EI\');disabling(this, \'groups_EI\');disabling(this, \'specificstudents_EI\');">
										<option value="0">' . get_string('nuloption', 'block_evalcomix') . '</option>
	';
	
foreach($tools as $id_tool => $title_tool){
	if($title_tool == '-000_1'){
		continue;
	}
	$checkedEItool = '';
	if($toolEI && $id_tool == $toolEI->id){
		$checkedEItool = 'selected = "selected"';
	}
	echo '
										<option value="'. $id_tool .'" '.$checkedEItool.'>'. $title_tool .'</option>
	';
}
	
echo '
									</select>
								</td>
							</tr>
	
							<tr>
								<td style="text-align:right">' . get_string('pon_EI', 'block_evalcomix') .$OUTPUT->help_icon('pon_EI', 'block_evalcomix') . '</td>
								<td>
';
	
$extraEI = $disabledEI . ' onchange="javascript:check_weighing(this);"';
echo add_select_range('pon_EI', 0, 100, $weighingEI, $extraEI);
	
echo '
		
								</td>
							</tr>
		
							<tr>
								<td style="text-align:right">' . get_string('anonymous_EI', 'block_evalcomix') . $OUTPUT->help_icon('anonymous_EI', 'block_evalcomix') . '</td>
								<td>
';
$checked = '';
if($anonymousEI == 1)
	$checked = 'checked';
		
echo '
									<input type="checkbox" ' . $checked . ' '. $disabledEI . ' id="anonymousEI" name="anonymousEI">
								</td>
							</tr>
							
							
							<tr>
								<td style="text-align:right">' . get_string('availabledate_EI', 'block_evalcomix') . $OUTPUT->help_icon('availabledate_EI', 'block_evalcomix') . '</td>
								<td>
';
	
echo add_date_time_selector('available_EI', $availableEI, $disabledEI) . '<br>';
	
echo '
								</td>
							</tr>
							
							<tr>
								<td style="text-align:right">' . get_string('timedue_EI', 'block_evalcomix') . $OUTPUT->help_icon('timedue_EI', 'block_evalcomix') . '</td>
								<td>
';
	
echo add_date_time_selector('timedue_EI', $timedueEI, $disabledEI) . '<br>';
	
echo '
								</td>
							</tr>						
							
							<tr>
								<td style="text-align:right">' . get_string('alwaysvisible_EI', 'block_evalcomix') . $OUTPUT->help_icon('alwaysvisible_EI', 'block_evalcomix') . '</td>
								<td>
';
$checked = '';
if($alwaysvisibleEI == 1)
	$checked = 'checked';
echo '
									<input type="checkbox" ' . $checked . ' '. $disabledEI . ' id="alwaysvisibleEI" name="alwaysvisibleEI">
								</td>
							</tr>
							
							<tr>
								<td style="text-align:right; vertical-align:top;">' . get_string('whoassesses_EI', 'block_evalcomix') . $OUTPUT->help_icon('whoassesses_EI', 'block_evalcomix') . '</td>
								<td>
';

$checked0 = '';
$checked1 = '';
$checked2 = '';
$disabled2 = 'disabled';
switch($whoassessesEI){
	case '0': $checked0 = 'checked';break;
	case '1': $checked1 = 'checked';break;
	case '2': $checked2 = 'checked';$disabled2 = '';break;
}
echo '
									<div><input type="radio" '.$checked0.' id="anystudent_EI" '. $disabledEI .' name="whoassessesEI" value="0" onclick="document.getElementById(\'assignstudents\').disabled = true;">'.get_string('anystudent_EI','block_evalcomix').'</div>
									<div><input type="radio" '.$checked1.' id="groups_EI" '. $disabledEI .' name="whoassessesEI" value="1" onclick="document.getElementById(\'assignstudents\').disabled = true;">'.get_string('groups_EI','block_evalcomix').'</div>
									<div><input type="radio" '.$checked2.' id="specificstudents_EI" '. $disabledEI .' name="whoassessesEI" value="2" onclick="document.getElementById(\'assignstudents\').disabled = false;">'.get_string('specificstudents_EI','block_evalcomix').'</div>
									<div><input type="button" '.$disabled2.' id="assignstudents" onclick="window.open(\'users_form.php?id='.$courseid.'&a='.$cm->id.'\', \'popup\', \'scrollbars,resizable,width=780,height=500\'); return false;" value="' . get_string('assignstudents_EI', 'block_evalcomix') .'"></div>
								</td>
							</tr>

						</table>
					</div>
					<div id="error_weighing" style="text-align:center;color:#ff0000;">
						<div id="error_weighing_less" style="visibility:hidden; border:1px solid #ff0000;height:0">El porcentaje total es MENOR que 100%. La suma de porcentajes debe ser 100%</div>
						<div id="error_weighing_greater" style="visibility:hidden; border:1px solid #ff0000;height:0">El porcentaje total es MAYOR que 100%. La suma de porcentajes debe ser 100%</div>
					</div>
					<br>
					<div style="text-align:center">
						<input type="submit" id="save" name="save" value="'. get_string('save', 'block_evalcomix') .'" onclick="return check_weighing();">
						<input type="submit" id="cancel" name="cancel" value="'. get_string('cancel', 'block_evalcomix') .'">
					</div>
				</fieldset>
			</form>
		</div>
';

//-----------------------------------------------------------------------------------------------
echo $OUTPUT->footer();

	
?>
