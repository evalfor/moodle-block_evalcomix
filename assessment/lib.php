<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */	
	
	require_once($CFG->dirroot . '/grade/report/lib.php');
	
	require_once('../configeval.php');
	include_once($CFG->dirroot . '/blocks/evalcomix/classes/calculator_average.php');
	include_once($CFG->dirroot . '/blocks/evalcomix/classes/grade_expert_db_block.php');
	include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes.php');
	include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes_time.php');
	require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
	
	require_once($CFG->libdir . '/gradelib.php');
	require_once($CFG->dirroot . '/grade/lib.php');
	require_once($CFG->libdir . '/tablelib.php');
	
	require_once($CFG->libdir . '/grade/grade_item.php');
	
	
	/**
	 * Class providing an API for the grader report building and displaying.
	 *
	 * This class is used to create the assessment table of a course
	 * It uses the course identificator, users, activities and grades.
	 *
	 * @uses grade_report
	 * @package gradebook
	 *
	 * @author Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>, Claudia Ortega Gómez <claudia.ortega@uca.es>
	 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v2
	 */
	class grade_report_evalcomix extends grade_report {
		
		/**
		* Object responsible for working out the operation between Moodle and EvalCOMIX grades
		* @var mixed $calculator
		*/
		public $calculator;
		
		/**
		* The EvalCOMIX final grades.
		* @var mixed $grade_viewer
		*/
		public $grade_viewer;
	
		/**
		 * The id of the grade_item by which this report will be sorted.
		 * The id of the grade_item by which this report will be sorted.
		 * @var int $sortitemid
		 */
		public $sortitemid;

		/**
		 * Sortorder used in the SQL selections.
		 * @var int $sortorder
		 */
		public $sortorder;
		
		/**
		 * An SQL fragment affecting the search for users.
		 * @var string $userselect
		 */
		public $userselect;

		/**
		 * The bound params for $userselect
		 * @var array $userselectparams
		 */
		public $userselectparams = array();
		
		/**
		 * cm_ids and names of the table activities
		 * @var array $activities of two dimensions ['id']['name']
		 */
		public $activities = array();
		
		/**
		* Indicates if $cmid es configured with evalcomix
		* @var array $activities_configured [$cmid]
		*/
		public $activities_configured = array();
		
		/**
		* Array of course_modules
		* @var array $cm [$cmid]
		*/
		public $cm = array();
		
		/**
		* @var int $studentsperpage
		*/
		public $studentsperpage = 50;
		
		/**
		* Capability check caching
		* */
		public $canviewhidden;
		
		/**
		* Array of Course Groups
		*/
		public $coursegroups = array();
		
		/**
		* Array of Course Groupings
		*/
		public $coursegroupings = array();
		
		public $groupwheresql;
	
		/**
		 * Constructor. Sets local copies of user preferences and initialises grade_tree.
		 * @param int $courseid
		 * @param object $gpr grade plugin return tracking object
		 * @param string $context
		 * @param int $page The current page being viewed (when report is paged)
		 * @param int $sortitemid The id of the grade_item by which to sort the table
		 */
		public function __construct($courseid, $gpr, $context, $page=null, $sortitemid=null){
			global $CFG;
			parent::__construct($courseid, $gpr, $context, $page);
			
			//Basado en el constructor de la clase grade_report_grader
			
			// Grab the grade_tree for this course
			$this->gtree = new grade_tree($this->courseid, false, false);			
			$this->add_activities_to_gtree();
			
			
			$this->grade_viewer = new grade_expert_db_block();
			$this->calculator = new calculator_average();
			
			$this->sortitemid = $sortitemid;

			// base url for sorting by first/last name

			$this->baseurl = new moodle_url('index.php', array('id' => $this->courseid));

			$this->studentsperpage = $this->get_pref('studentsperpage');
			$studentsperpage = $this->studentsperpage;
			if (!empty($studentsperpage)) {
				$this->baseurl->params(array('perpage' => $studentsperpage, 'page' => $this->page));
			}

			$this->pbarurl = new moodle_url('/blocks/evalcomix/assessment/index.php', array('id' => $this->courseid, 'perpage' => $studentsperpage));
			
			$this->setup_groups();
			
			$this->setup_sortitemid();
			
			$this->canviewhidden = has_capability('moodle/grade:viewhidden', context_course::instance($this->course->id));
		}
		
		/**
		 * Add activities that are not in mdl_grade_item table to gtree
		 */
		private function add_activities_to_gtree(){			
			global $DB;
			
			$itemmodules = array('forum', 'glossary', 'data', 'wiki');
			$levels = $this->gtree->get_levels();
			//Obtiene las actividades que hay en la tabla mdl_grade_items del curso en el que estemos			
			foreach($itemmodules as $itemmodule){
			
				//Para que se salte los foros de novedades
				if($itemmodule == 'forum'){
					$activities = $DB->get_records($itemmodule, array('course' => $this->courseid, 'type' => 'general'));
				}
				else{
					$activities = $DB->get_records($itemmodule, array('course' => $this->courseid));
				}
				
				foreach($activities as $activity){
					
					$exists = false;
			/*		$i = 0;					
					//Sin esta comprobación podrían repetirse actividades (por ejemplo, un foro que también se evalúe desde Moodle
					//ya que ya se encuentra dentro del árbol por estar en la tabla mdl_grade_item
					while (!$exists && $i < count($this->gtree->levels[1])){
						if (isset($this->gtree->levels[1][$i]['object']->iteminstance) && ($activity->id == $this->gtree->levels[1][$i]['object']->iteminstance) &&
							($itemmodule == $this->gtree->levels[1][$i]['object']->itemmodule)){
							$exists = true;
						}
						$i++;
					}
				*/
					foreach ($levels as $row) {				
						foreach ($row as $element) {
							if (isset($element['object']->iteminstance) && ($activity->id == $element['object']->iteminstance) &&
							($itemmodule == $element['object']->itemmodule)){
								$exists = true;
							}
						}
					}
					
					if(!$exists){
						//Parámetros del nuevo grade_item a introducir 'categoryid' => $activity->id, 
						$activity_params = array ('courseid' => $this->courseid, 'itemname' => $activity->name, 'itemtype' => 'mod', 
											'itemmodule' => $itemmodule, 'iteminstance' => $activity->id, 'itemnumber' => 0);
											//'timecreated' => $activity->timecreated, 'timemodified' => $activity->timemodified);
						
						//Creación del objeto
						$grade_item = new grade_item($activity_params, false);
						
						//Para llevar la cuenta del números de items
						$numitem = count($this->gtree->levels[1]);
						
						//Parámetros para rellenar el array
						$activity_params2 = array ('object'=> $grade_item, 'type' => 'item', 'depth' => 1, 'prev' => $numitem, 'next' => 0);
						
						//Indicamos que el que estaba anterior ya no va a ser el último elemento del array
						$this->gtree->levels[1][$numitem-1]['next'] = $numitem+1;
						//Añadimos el item al array
						$this->gtree->levels[1][] = $activity_params2;		
					}
				
				}			
			}
		}
		
		
		/**
		 * Setting the sort order, this depends on last state
		 * all this should be in the new table class that we might need to use
		 * for displaying grades.
		 */
		private function setup_sortitemid() {

			global $SESSION;

			if ($this->sortitemid) {
				if (!isset($SESSION->gradeuserreport->sort)) {
					if ($this->sortitemid == 'firstname' || $this->sortitemid == 'lastname') {
						$this->sortorder = $SESSION->gradeuserreport->sort = 'ASC';
					} else {
						$this->sortorder = $SESSION->gradeuserreport->sort = 'DESC';
					}
				} else {
					// this is the first sort, i.e. by last name
					if (!isset($SESSION->gradeuserreport->sortitemid)) {
						if ($this->sortitemid == 'firstname' || $this->sortitemid == 'lastname') {
							$this->sortorder = $SESSION->gradeuserreport->sort = 'ASC';
						} else {
							$this->sortorder = $SESSION->gradeuserreport->sort = 'DESC';
						}
					} else if ($SESSION->gradeuserreport->sortitemid == $this->sortitemid) {
						// same as last sort
						if ($SESSION->gradeuserreport->sort == 'ASC') {
							$this->sortorder = $SESSION->gradeuserreport->sort = 'DESC';
						} else {
							$this->sortorder = $SESSION->gradeuserreport->sort = 'ASC';
						}
					} else {
						if ($this->sortitemid == 'firstname' || $this->sortitemid == 'lastname') {
							$this->sortorder = $SESSION->gradeuserreport->sort = 'ASC';
						} else {
							$this->sortorder = $SESSION->gradeuserreport->sort = 'DESC';
						}
					}
				}
				$SESSION->gradeuserreport->sortitemid = $this->sortitemid;
			} else {
				// not requesting sort, use last setting (for paging)

				if (isset($SESSION->gradeuserreport->sortitemid)) {
					$this->sortitemid = $SESSION->gradeuserreport->sortitemid;
				}else{
					$this->sortitemid = 'lastname';
				}

				if (isset($SESSION->gradeuserreport->sort)) {
					$this->sortorder = $SESSION->gradeuserreport->sort;
				} else {
					$this->sortorder = 'ASC';
				}
			}
		}
		
		
		/**
		 * Processes the data sent by the form (grades and feedbacks).
		 * Caller is responsible for all access control checks
		 * @param array $data form submission (with magic quotes)
		 * @return array empty array if success, array of warnings if something fails.
		 */
		public function process_data($data) {
			global $CFG, $DB, $COURSE, $USER;
			
			//ESto es lo que acabo de añadir
			// Para guardar la actividad en la bd de evalcomix
			include_once($CFG->dirroot .'/blocks/evalcomix/classes/webservice_evalcomix_client.php');
			include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
			include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');
			
			//para quitar notice
			$save_conf_act = 0;
			
			if(isset($data['cmid'])){
				$activity = $data['cmid'];
				$module = evalcomix_tasks::get_type_task($activity);
				$save_conf_act = 1;
			}
			$timeavailableAE = 0;
			$timedueAE = 0;
			$idtoolAE = 0;
			$pon_AE = 0;
			$timeavailableEI = 0;
			$timedueEI = 0;
			$idtoolEI = 0;
			$pon_EI = 0;
			$idtoolEP = 0;
			$pon_EP = 0;
			
			if(isset($data['toolEP']) && $data['toolEP'] != 0){
				$toolEP = evalcomix_tool::fetch(array('id' => $data['toolEP']));
				$idtoolEP = $toolEP->idtool;
				$pon_EP = $data['pon_EP'];
			}
			if(isset($data['toolAE']) && $data['toolAE'] != 0){
				$timeavailableAE = mktime($data['hour_available_AE'], $data['minute_available_AE'], 0, $data['month_available_AE'], $data['day_available_AE'], $data['year_available_AE']);
				$timedueAE = mktime($data['hour_timedue_AE'], $data['minute_timedue_AE'], 0, $data['month_timedue_AE'], $data['day_timedue_AE'], $data['year_timedue_AE']);
				$toolAE = evalcomix_tool::fetch(array('id' => $data['toolAE']));
				$idtoolAE = $toolAE->idtool;
				$pon_AE = $data['pon_AE'];
			}
			if(isset($data['toolEI']) && $data['toolEI'] != 0){
				$timeavailableEI = mktime($data['hour_available_EI'], $data['minute_available_EI'], 0, $data['month_available_EI'], $data['day_available_EI'], $data['year_available_EI']);
				$timedueEI = mktime($data['hour_timedue_EI'], $data['minute_timedue_EI'], 0, $data['month_timedue_EI'], $data['day_timedue_EI'], $data['year_timedue_EI']);
				$toolEI = evalcomix_tool::fetch(array('id' => $data['toolEI']));
				$idtoolEI = $toolEI->idtool;
				$pon_EI = $data['pon_EI'];
			}
			
			/*if($save_conf_act == 1){
				$result = webservice_evalcomix_client::put_ws_savetask($COURSE->id, $module, $activity, $idtoolEP, $idtoolAE, $idtoolEI, $pon_EP, $pon_AE, $pon_EI, $timeavailableAE, $timedueAE, $timeavailableEI, $timedueEI, MOODLE_NAME);				
			}*/
			//Hasta aquí---------------------
			
			if(isset($data['save']) && $data['save'] == get_string('save', 'block_evalcomix')){
				include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
				include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');
				include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes_time.php');
				include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes_extra.php');
				$data_exists = false;
				$task_exists = false;
				$modality_delete = false;
				$taskid = null;
				if((isset($data['toolEP']) && $data['toolEP'] != '0') ||
					(isset($data['toolAE']) && $data['toolAE'] != '0') ||
					(isset($data['toolEI']) && $data['toolEI'] != '0') ){
					$data_exists= true;
				} 
				
				$task = new evalcomix_tasks('', $data['cmid'], $data['maxgrade'], '50', time());
				if($taskid = $task->exist()){
					$task_exists = true;
					$params = array('id' => $taskid);
					evalcomix_tasks::set_properties($task, $params);
					$task->update();
				}
				elseif($data_exists == true){
					$taskid = $task->insert();
				}	
				
				if($data['toolEP'] != 0){
					$modeEP = new evalcomix_modes('', $taskid, $data['toolEP'], 'teacher', $data['pon_EP']);
					if($modeid = $modeEP->exist()){
						$params = array('id' => $modeid);
						evalcomix_modes::set_properties($modeEP, $params);
						$modeEP->update();
					}
					else{
						$modeid = $modeEP->insert();
					}
				}
				elseif($task_exists == true){
					$modeEP = new evalcomix_modes('', $taskid, '', 'teacher');
					if($modeid = $modeEP->exist()){
						$modeEP->delete();
						$modality_delete = true;
					}
				}
				
				if($data['toolAE'] != 0){
					$modeAE = new evalcomix_modes('', $taskid, $data['toolAE'], 'self', $data['pon_AE']);
					if($modeid = $modeAE->exist()){
						$params = array('id' => $modeid);
						evalcomix_modes::set_properties($modeAE, $params);
						$modeAE->update();
						$timeavailable = mktime($data['hour_available_AE'], $data['minute_available_AE'], 0, $data['month_available_AE'], $data['day_available_AE'], $data['year_available_AE']);
						$timedue = mktime($data['hour_timedue_AE'], $data['minute_timedue_AE'], 0, $data['month_timedue_AE'], $data['day_timedue_AE'], $data['year_timedue_AE']);
						$modeAE_time = new evalcomix_modes_time('', $modeid, $timeavailable, $timedue);
						if($modeAE_timeid = $modeAE_time->exist()){
							$params = array('id' => $modeAE_timeid);
							evalcomix_modes::set_properties($modeAE_time, $params);
							$modeAE_time->update();
						}
					}
					else{
						$modeid = $modeAE->insert();
						$timeavailable = mktime($data['hour_available_AE'], $data['minute_available_AE'], 0, $data['month_available_AE'], $data['day_available_AE'], $data['year_available_AE']);
						$timedue = mktime($data['hour_timedue_AE'], $data['minute_timedue_AE'], 0, $data['month_timedue_AE'], $data['day_timedue_AE'], $data['year_timedue_AE']);
						$modeAE_time = new evalcomix_modes_time('', $modeid, $timeavailable, $timedue);
						$modeAE_time->insert();
					}
				}
				elseif($task_exists == true){
					$modeAE = new evalcomix_modes('', $taskid, '', 'self');
					if($modeid = $modeAE->exist()){
						$modeAE_time = new evalcomix_modes_time('', $modeid);
						if($modeAE_time->exist()){
							$modeAE_time->delete();
						}
						$modeAE->delete();
						$modality_delete = true;
					}
				}
				
				if($data['toolEI'] != 0){
					$anonymous = 0;
					if(isset($data['anonymousEI']) && $data['anonymousEI'] == 'on'){
						$anonymous = 1;
					}
					$alwaysvisible = 0;
					if(isset($data['alwaysvisibleEI']) && $data['alwaysvisibleEI']){
						$alwaysvisible = 1;
					}
					$whoassesses = 0;
					if(isset($data['whoassessesEI']) && $data['whoassessesEI']){
						$whoassesses = $data['whoassessesEI'];
					}
					
					$modeEI = new evalcomix_modes('', $taskid, $data['toolEI'], 'peer', $data['pon_EI']);
					if($modeid = $modeEI->exist()){
						$params = array('id' => $modeid);
						evalcomix_modes::set_properties($modeEI, $params);
						$modeEI->update();
						$timeavailable = mktime($data['hour_available_EI'], $data['minute_available_EI'], 0, $data['month_available_EI'], $data['day_available_EI'], $data['year_available_EI']);
						$timedue = mktime($data['hour_timedue_EI'], $data['minute_timedue_EI'], 0, $data['month_timedue_EI'], $data['day_timedue_EI'], $data['year_timedue_EI']);
						
						$modeEI_time = new evalcomix_modes_time('', $modeid, $timeavailable, $timedue);
						if($modeEI_timeid = $modeEI_time->exist()){
							$params = array('id' => $modeEI_timeid);
							evalcomix_modes_time::set_properties($modeEI_time, $params);
							$modeEI_time->update();
						}
			
						$modeEI_extra = new evalcomix_modes_extra('', $modeid, $anonymous, $alwaysvisible, $whoassesses);
						$modeEI_extraObject = $DB->get_record('block_evalcomix_modes_extra', array('modeid' => $modeid));
						$modeEI_extraid = $modeEI_extraObject->id;
						$params = array('id' => $modeEI_extraid, 'anonymous' => $anonymous, 'visible' => $alwaysvisible, 'whoassesses' => $whoassesses);
						evalcomix_modes_extra::set_properties($modeEI_extra, $params);
						$modeEI_extra->update();
					}
					else{
						$modeid = $modeEI->insert();
						$timeavailable = mktime($data['hour_available_EI'], $data['minute_available_EI'], 0, $data['month_available_EI'], $data['day_available_EI'], $data['year_available_EI']);
						$timedue = mktime($data['hour_timedue_EI'], $data['minute_timedue_EI'], 0, $data['month_timedue_EI'], $data['day_timedue_EI'], $data['year_timedue_EI']);
						$modeEI_time = new evalcomix_modes_time('', $modeid, $timeavailable, $timedue);
						$modeEI_time->insert();
						$modeEI_extra = new evalcomix_modes_extra('', $modeid, $anonymous, $alwaysvisible, $whoassesses);
						$modeEI_extra->insert();
					}
				}
				elseif($task_exists == true){
					$modeEI = new evalcomix_modes('', $taskid, '', 'peer');
					if($modeid = $modeEI->exist()){
						$modeEI_extra = new evalcomix_modes_extra('', $modeid);
						if($modeEI_extra->exist()){
							$modeEI_extra->delete();
						}
						$modeEI_time = new evalcomix_modes_time('', $modeid);
						if($modeEI_time->exist()){
							$modeEI_time->delete();
						}
						$modeEI->delete();
						$modality_delete = true;
					}
				}
				
				if($task_exists == true && $data_exists == true){
					include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_grades.php');
				
					if($grades = evalcomix_grades::fetch_all(array('courseid' => $this->courseid, 'cmid' => $task->instanceid))){
						foreach($grades as $grade){
							$user = $grade->userid;
												
							$params = array('cmid' => $task->instanceid, 'userid' => $user, 'courseid' => $this->courseid);
							$finalgrade = evalcomix_grades::get_finalgrade_user_task($params);
							if($finalgrade !== null){
								$grade->finalgrade = $finalgrade;
								$grade->update();
							}			
						}
					}
				}
				/*elseif($data_exists != true){
					include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_grades.php');
				
					if($grades = evalcomix_grades::fetch_all(array('courseid' => $this->courseid, 'cmid' => $task->instanceid))){
						foreach($grades as $grade){
							$grade->finalgrade = -3;
							$grade->update();						
						}
					}
				}*/
				
				//Recalculamos en cualquier caso las notas
				include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_grades.php');
				if($grades = evalcomix_grades::fetch_all(array('courseid' => $this->courseid, 'cmid' => $task->instanceid))){
					foreach($grades as $grade){
						$user = $grade->userid;
											
						$params = array('cmid' => $task->instanceid, 'userid' => $user, 'courseid' => $this->courseid);
						$finalgrade = evalcomix_grades::get_finalgrade_user_task($params);
						if($finalgrade !== null){
							$grade->finalgrade = $finalgrade;
							$grade->update();
						}
					}
				}
				
				
			}elseif(isset($data['cancel']) && $data['cancel'] == 'cancel'){
			
			}
			
			// Comprobar si hay datos de alguna evaluación realizada con evalcomix para guardarla en la base de datos de Moodle
			// Se hace aquí ya que ese método se procesa cada vez que se recarga la página
			if (isset($data['stu']) && $data['stu'] != 0 && $data['cma'] != 0) {
				$activity = $data['cma'];
				$module = evalcomix_tasks::get_type_task($activity);				
				//$mode = $this->get_type_evaluation($data['stu']);
				$mode = grade_report_evalcomix::get_type_evaluation($data['stu'], $this->courseid);
				
				/*echo $COURSE->id . '<br>';
				echo $module . '<br>';
				echo $activity . '<br>';
				echo $data['stu'] . '<br>';
				echo $USER->id . '<br>';
				echo $mode . '<br>';
				echo MOODLE_NAME . '<br>';*/
				
				
				
				$task = new evalcomix_tasks('', $data['cma']);
				if($taskid = $task->exist()){
					$tool = get_evalcomix_modality_tool($this->courseid, $taskid, $mode);
					$evalcomix_assessment = webservice_evalcomix_client::get_ws_singlegrade($tool->idtool, $this->courseid, $module, $activity, $data['stu'], $USER->id, $mode, MOODLE_NAME);								
				}
				
				//if $evalcomix_assessment->grade == -1  means that the grade is empty
				if ($evalcomix_assessment != null) {
				
					//Checks if the assessment exists in the table mdl_blocks_evalcomix_assessments
					$params = array('taskid' => $evalcomix_assessment->taskid, 'assessorid' => $evalcomix_assessment->assessorid, 'studentid' => $evalcomix_assessment->studentid);

					$evx_assessment_object = evalcomix_assessments::fetch($params);

					//print_r($evx_assessment_object);
					//echo $evalcomix_assessment->grade;
					
//					if ($evx_assessment_object != null) {
					if ($evx_assessment_object != false) {
						if ($evalcomix_assessment->grade != -1) { //If the grade is not null
							$evx_assessment_object->grade = $evalcomix_assessment->grade;
							$evx_assessment_object->update();
						}
						else { //If the grade is null
							$evx_assessment_object->delete();
						}
					}
					elseif ($evalcomix_assessment->grade != -1) { //if it does not exist and the grade is not null inserts it					
						$evalcomix_assessment->insert();
					}
				}		
				//Save the finalgrade
				include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_grades.php');
				$params = array('cmid' => $data['cma'], 'userid' => $data['stu'], 'courseid' => $this->courseid);
				$finalgrade = evalcomix_grades::get_finalgrade_user_task($params);
				if($finalgrade !== null){
					if($gradeObject = evalcomix_grades::fetch($params)){
						$gradeObject->finalgrade = $finalgrade;
						$gradeObject->update();
					}
					else{
						$params['finalgrade'] = $finalgrade;
						$gradeObject = new evalcomix_grades($params);
						$gradeObject->insert();
					}
				}
				else{
					if($gradeObject = evalcomix_grades::fetch($params)){
						$gradeObject->delete();
					}
				}
			}
			
		}
		
		/**
		 * Processes a single action against a category, grade_item or grade.
		 * @param string $target eid ({type}{id}, e.g. c4 for category4)
		 * @param string $action Which action to take (edit, delete etc...)
		 * @return
		 */
		public function process_action($target, $action) {
			//Véase método grade_report_grader::process_action();
		}
		
		/**
		 * Returns whether or not to display fixed students column.
		 * Includes a browser check, because IE6 doesn't support the scrollbar.
		 *
		 * @return bool
		 */
		public function is_fixed_students() {
			/*global $USER, $CFG;
			return empty($USER->screenreader) && $CFG->grade_report_fixedstudents &&
				(check_browser_version('MSIE', '7.0') ||
				 check_browser_version('Firefox', '2.0') ||
				 check_browser_version('Gecko', '2006010100') ||
				 check_browser_version('Camino', '1.0') ||
				 check_browser_version('Opera', '6.0') ||
				 check_browser_version('Chrome', '6') ||
				 check_browser_version('Safari', '300'));*/
		}
		
		
		/**
		 * Checks if the $USER is an editing permits user (admin or teacher)
		 * @return bool
		 */
		private function editing_permits_user() {
			global $USER, $COURSE;
			
			$coursecontext = context_course::instance($COURSE->id);
			$permits = false;
			
			if(is_siteadmin($USER)){
				$permits = true;
			}
			
			if(has_capability('moodle/block:edit',$coursecontext)){
			//if(has_capability('block/evalcomix:edit',$coursecontext)){
				$permits = true;
			}
			
			return $permits;		
		}
		
		
		/**
		 * Checks if an activity is configured in evalcomix
		 * @param int $cmid id of a course_module
		 * @return bool
		 */
		public function configured_activity($cmid) {
			global $DB;
			
			//Checks if exists one row in table mdl_block_evalcomix_tasks for that course module id
			$task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $cmid));
			if ($task){
				//Checks if exists at least one row in table mdl_block_evalcomix_modes for that $task
				$modes = $DB->get_records('block_evalcomix_modes', array('taskid' => $task->id));
				if ($modes){
					return true;
				}
			}			
			return false;
		}
		
		/**
		* Builds the grade table and returns it in HTML
		* @return string HTML
		*/
		public function create_grade_table(){	
			global $CFG, $OUTPUT, $USER;
			include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
			include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_assessments.php');
			include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes_time.php');
			include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes_extra.php');
			include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_modes.php');
			include_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
			include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_grades.php');
			include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_allowedusers.php');
				
			$context = context_course::instance($this->courseid);
			
			$table = '
			<style type="text/css">
					.wrapper1, .wrapper2{width: 100%; border: none 0px RED;
					overflow-x: scroll; overflow-y:hidden;}
					.wrapper1{height: 20px; }
					.wrapper2{}
					#div1 {width:500px; height: 10px; }
					#div2 {width:500px;
					overflow: auto;}
					#div {width:100%}

				</style>
			<div id="div"></div>
			<div class="wrapper1">
				<div class="div1" id="div1" style="width:100%">
				</div>
			</div>
			<div class="wrapper2">
				<div class="div2" id="div2" style="width:100%;">
				
			<!-- <div id="wrapper2" style="overflow:auto;overflow-y:hidden;"> -->
			<table border=1 style="font-size:0.8em;text-align:right;" id="user-grades" class="gradestable flexible boxaligncenter generaltable">';
			
			//Obtains course´s users
			$users = $this->load_users();
			
			//if(!has_capability('block/evalcomix:edit',$context, $USER->id)){
			if(!has_capability('moodle/grade:viewhidden',$context, $USER->id)){
				$coursegroups = $this->load_groups();
				$coursegroupings = $this->load_groupings();
			}
			
			$table .= $this->get_headers();

			$table .= "
				<script type=\"text/javascript\" src=\"../ajax.js\"></script>
				<script type=\"text/javascript\">
					var recarga = 0;
				
					 function relocate(page,params)
					 {
						  var body = document.body;
						  form=document.createElement('form'); 
						  form.method = 'POST'; 
						  form.action = page;
						  form.name = 'jsform';
						  for (index in params)
						  {
								var input = document.createElement('input');
								input.type='hidden';
								input.name=index;
								input.id=index;
								input.value=params[index];
								form.appendChild(input);
						  }	  		  			  
						  body.appendChild(form);
						  form.submit();
					 }
					 
					function change_recarga(){
						recarga = 1;
					}
				
					function urlDetalles(u){
						win2 = window.open(u, 0, \"menubar=0,location=1,scrollbars,resizable,width=780,height=500\");
						checkChildDetalles();
					}
					
					function url(u, alu, cmidassign, page, courseid, nombre){
						win2 = window.open(u, nombre, \"menubar=0,location=0,scrollbars,resizable,width=780,height=500\");
						checkChild(alu, cmidassign, page, courseid);						
					}
					
					function checkChild(alu, cmidassign, page, course) {
						if (win2.closed) {
							/*relocate(window.location, {'stu':alu, 'cma':cmidassign});	*/	
							doWork('evalcomixtablegrade_'+alu+'_'+cmidassign, 'servidor.php?id=".$this->courseid."&eva=".$USER->id."', 'courseid='+course+'&page='+page+'&stu='+alu+'&cma='+cmidassign);
						}
						else setTimeout(\"checkChild(\"+alu+\",\"+cmidassign+\")\",1);
					}
					
					
					
					function checkChildDetalles() {
						if (win2.closed) {
							if (recarga == 1) {
								window.location.reload(true);
							}
							recarga = 0;
						}
						else {
							setTimeout(\"checkChildDetalles()\",1);
						}
					}

					function checkChild_old(alu) {						
						if (win2.closed) {
							var url = String(window.location);
							var long = url.length;
							if (url.substring(long-6, long-1) != \"&stu=\") {
								location.href = window.location + \"&stu=\"+alu;
							}
							else window.location.reload(true);
							
						}
						else setTimeout(\"checkChild_old(\"+alu+\")\",1);
					}
					
				</script>
				
  <script type='text/javascript' src='".$CFG->wwwroot."/blocks/evalcomix/javascript/jquery-1.4.2.js'></script>
				<script type='text/javascript'>//<![CDATA[ 
					var table = document.getElementById('user-grades');
					
					var div11 = document.getElementById('div1');
					var div22 = document.getElementById('div2');
					var div = document.getElementById('div');
					var documentwidth = $(div).width();
					
					if((table.offsetWidth+100) < documentwidth){
						div11.style.width = (documentwidth - 50) +'px';
						div22.style.width = (documentwidth - 50)+'px';
						$(div22).css('text-align','center');
						$(table).css('margin','0 auto');
					}
					else{
						div11.style.width = (table.offsetWidth+100)+'px';
						div22.style.width = (table.offsetWidth+100)+'px';
					}
					
					$(window).load(function(){
					$(function(){
						$(\".wrapper1\").scroll(function(){
							$(\".wrapper2\")
								.scrollLeft($(\".wrapper1\").scrollLeft());
						});
						$(\".wrapper2\").scroll(function(){
							$(\".wrapper1\")
								.scrollLeft($(\".wrapper2\").scrollLeft());
						});
					});
					});//]]>  

					</script>

				<noscript>
					<div style='color: #f00;'>".get_string('alertjavascript', 'block_evalcomix')."</div>
				</noscript>\n";
			
			$tools = $this->load_tools();
			$lang = current_language();
			//array $finalgrades with two dimensions [$taskinstance][$userid] that contains the finalgrades.
			$finalgrades = evalcomix_grades::get_grades($this->courseid);
			//$finalgrades = $this->get_grades('moodleevx');

			//print_r($this->activities_ids);
			$index = 0;
			if(isset($this->activities['id'])){
				$num_activities = count($this->activities['id']);
			}
			else{
				$num_activities = 0;
			}
			//$configured = array();
			$tasks_array = array();
			$groupmodes = array();
			$type_instrument = array();
			$cm = array();
			$whoassesses = array();
			global $DB;
			
			for($i = 0; $i < $num_activities; $i++){
				$cmid = $this->activities['id'][$i];
				//$cm[$cmid] = $this->get_cm($cmid);
				if(!$this->canviewhidden && $this->cm[$cmid]->visible == 0){
					continue;
				}
				//$configured[$cmid] = $this->configured_activity($cmid);
				$groupmodes[$cmid] = $this->cm[$cmid]->groupmode;
				//$type_instrument[$cmid] = evalcomix_tasks::get_type_task($cmid);
				$type_instrument[$cmid] = $this->activities['modulename'][$i];
				
				
				//$mode_time[$cmid]['teacher'] = $this->get_modes_time($cmid, 'teacher');
				if ($tasks_array[$cmid] = evalcomix_tasks::fetch(array('instanceid' => $cmid))){
					$taskid = $tasks_array[$cmid]->id;
					$mode_teacher[$cmid] = evalcomix_modes::fetch(array('taskid' =>$taskid, 'modality' => 'teacher'));
					$mode_time[$cmid]['self'] = $this->get_modestime($tasks_array[$cmid]->id, 'self');
					$mode_time[$cmid]['peer'] = $this->get_modestime($tasks_array[$cmid]->id, 'peer');
					if($mode_peer[$cmid] = evalcomix_modes::fetch(array('taskid' =>$taskid, 'modality' => 'peer'))){
						if($mode_peer_extra = evalcomix_modes_extra::fetch(array('modeid' => $mode_peer[$cmid]->id))){
							$whoassesses[$cmid] = $mode_peer_extra->whoassesses;
						}	
					}
				}				
				//$mode_time[$cmid]['self'] = $this->get_modes_time($cmid, 'self');
				//$mode_time[$cmid]['peer'] = $this->get_modes_time($cmid, 'peer');
			}
			
			//To know if the documents uploaded by the students must be shown
			$assessments = $this->load_assessments($tasks_array);
			$now = getdate();
			$now_timestamp = mktime($now["hours"], $now["minutes"], $now["seconds"], $now["mon"], $now["mday"], $now["year"]);
			
			foreach($users as $user){
				//$mode = $this->get_type_evaluation($user->id);
				$mode = grade_report_evalcomix::get_type_evaluation($user->id, $this->courseid);
				if($mode == 'self' || $mode == 'peer'){
					$gid_loginuser = $this->get_groupids($USER->id);
					$gid_user = $this->get_groupids($user->id);
				}
				
				$background = 'background-color:#ffffff';
				if($index % 2 == 0){
					$background = 'background-color:#ededed';
				}
				$index++;
				$aux = array();
				$table .= '
					<tr style="border:1px solid #146C84;'. $background .'">
					<td style="width:35px;padding:3px;"><div class="userpic">'. $OUTPUT->user_picture($user) .'</div></td>
					<td style="border:1px solid #146C84;"><a href="'.$CFG->wwwroot.'/user/view.php?id='.$user->id.'&course='.$this->courseid.'">'. fullname($user) .'</a></td>';					
				
				for($i = 0; $i < $num_activities; $i++){
					$allowedusers = array();
					$evaluate = '';
					$cmid = $this->activities['id'][$i];
					if(!$this->canviewhidden && $this->cm[$cmid]->visible == 0){
						continue;
					}
					//$configured = $this->configured_activity($this->activities['id'][$i]);
					//boolean, if there is not a grade it will not show the details link
					$showdetails = true;

					$table .= '<td style="border:1px solid #146C84;" title="'.htmlentities(fullname($user)."\n".$this->activities['name'][$i],ENT_QUOTES,"UTF-8").'">';
					$table .= '<div id="evalcomixtablegrade_'.$user->id.'_'.$cmid.'">';
					//Only show the user´s grade or all grades if the USER is a teacher or admin
					if($this->cm[$cmid]->visible == 0){
						$table .= '<span style="font-style:italic;color:#838383">Actividad Oculta</span>';
					}
					//elseif ((has_capability('block/evalcomix:edit',$context, $USER->id) || $user->id == $USER->id) && isset($finalgrades[$this->activities['id'][$i]][$user->id]) && $finalgrades[$this->activities['id'][$i]][$user->id] > -3){
					elseif ((has_capability('moodle/grade:viewhidden',$context, $USER->id) || $user->id == $USER->id) && isset($finalgrades[$this->activities['id'][$i]][$user->id]) && $finalgrades[$this->activities['id'][$i]][$user->id] > -3){
						if ($finalgrades[$cmid][$user->id] > -1){
							$table .= format_float($finalgrades[$cmid][$user->id], 2);
							$aux[] = $finalgrades[$cmid][$user->id];
						}
						else{
							$table .= '-';
						}
						//if there is not anything to assess
						if($finalgrades[$cmid][$user->id] == -2){
							$showdetails = false;
						}
					}
					else { //there is not grade
						//configured
						//if($configured[$cmid]){ 
						if($this->activities_configured[$cmid]){
							$table .= '-';
						}
						//not configured
						else{ 
							$table .= '<span style="font-style:italic;color:#f54927">'.get_string('noconfigured', 'block_evalcomix').'</span>';
						}
						$showdetails = false;
					}
					
					//Checks if $this->activities['id'] is configured in evalcomix
					//if ($configured[$cmid]){
					if($this->activities_configured[$cmid]){
						if($this->cm[$cmid]->visible == 0){
							$table .= '<div><span style="font-style:italic;color:#838383;font-weight:bold">Configurada</span></div>';
							continue;
						}
						
						//if($tool = get_evalcomix_modality_tool($this->courseid, $tasks_array[$cmid]->id, $mode)){
						$taskid = $tasks_array[$cmid]->id;
						if(isset($tools[$taskid][$mode])){
							$tool = $tools[$taskid][$mode];
							//$url_instrument = webservice_evalcomix_client::get_ws_assessment_form($tool->idtool, $this->courseid, $type_instrument[$cmid], $this->activities['id'][$i], $user->id, $USER->id, $mode, MOODLE_NAME, 'assess', $lang.'_utf8');
							$url_instrument = 'assessment_form.php?id='.$this->courseid.'&a='.$cmid.'&t='.$tool->idtool.'&s='.$user->id.'&mode=assess';
							$evaluate = '<input type="image" value="'.get_string('evaluate', 'block_evalcomix').'" title="'.get_string('evaluate', 'block_evalcomix').'" style="background-color:transparent;width:16px;" src="../images/evaluar.png" onclick="javascript:url(\'' . $url_instrument . '\',\'' . $user->id . '\',\'' . $this->activities['id'][$i] . '\',\'' . $this->page . '\',\'' . $this->courseid . '\');"/>';
							//if($assessmentgrade = evalcomix_assessments::fetch(array('taskid'=>$tasks_array[$cmid]->id, 'assessorid'=>$USER->id, 'studentid'=>$user->id))){
							$assessorid = $USER->id;
							$studentid = $user->id;
							if(isset($assessments[$taskid][$assessorid][$studentid])){
								$evaluate = '<input type="image" value="'.get_string('evaluate', 'block_evalcomix').'" title="'.get_string('evaluate', 'block_evalcomix').'" style="background-color:transparent;width:16px;" src="../images/evaluar2.png" onclick="javascript:url(\'' . $url_instrument . '\',\'' . $user->id . '\',\'' . $this->activities['id'][$i] . '\', \'' . $this->page . '\',\'' . $this->courseid . '\');"/>';
							}					
						//}
						}
						
						if ($showdetails) {
							$details = '<input  type="image" value="'.get_string('details', 'block_evalcomix').'" style="width:16px;background-color:transparent;" title='.get_string('details', 'block_evalcomix').' src="../images/lupa.png" onclick="javascript:urlDetalles(\''. $CFG->wwwroot. '/blocks/evalcomix/assessment/details.php?cid=' . $context->id . '&itemid=' . $tasks_array[$cmid]->id . '&userid=' . $user->id . '&popup=1\');"/>';
						}
						else {
							$details = '';
						}
						
						//Show user´s documents
						//echo $showdocuments;
						if ($mode == 'teacher'){
							$title = get_string('studentwork1','block_evalcomix').get_string('studentwork2','block_evalcomix'). $this->activities['name'][$i];
							$table .= ' <input type="image" value="'.$title.'" style="background-color:transparent;width:13px" title="'.$title.'" src="../images/task.png" 
							onclick="javascript:urlDetalles(\''. $CFG->wwwroot. '/blocks/evalcomix/assessment/user_activity.php?id='.$user->id.'&course='.$this->courseid.'&mod='.$cmid.'\');"/>';
						} 
						
						//If the $USER isn´t a teacher or admin evaluate if it should show Evaluate and Details buttons
						if($mode == 'self' || $mode == 'peer'){
							//echo $finalgrades[$this->activities['id'][$i]][$user->id];
							//Obtains the groupmode of the activity
							//$groupmode = $this->get_groupmode($this->activities['id'][$i]);
							$groupmode = $groupmodes[$cmid];
							
							$groupmembersonly = 0;
							if(isset($this->cm[$cmid]->groupmembersonly)){
								$groupmembersonly = $this->cm[$cmid]->groupmembersonly;
							}
							
							$groupingid = $this->cm[$cmid]->groupingid;
							$same_grouping = false;							
							$same_group = $this->same_group($USER->id, $user->id);
							
							if($groupingid != 0){							
								$same_grouping = $this->same_grouping_by_users($USER->id, $user->id, $this->cm[$cmid]);
							}
							/*Groupmode == 1 -> Separated Groups */
							$condition = true;
							if(isset($whoassesses[$cmid])){
								switch($whoassesses[$cmid]){
									case 0: $condition = true; break;
									case 1: $condition = ((!$groupmembersonly && (
															($same_grouping && (
																	($groupmode != 1 
																	|| $same_group))
															) 
															|| 
															(!$groupingid && 
																(($groupmode != 1 
																	|| $same_group)))
															)
														)
														|| ($groupmembersonly  && (
															(!$groupingid && 
																((($groupmode != 1 && $gid_loginuser != -1 && $gid_user != -1) 
																	|| $same_group)))
															||
															($same_grouping && (
																($groupmode != 1 
																	|| $same_group)))
															)
														));
									
									break;
									case 2: {
										if($evalcomixallowedusers = evalcomix_allowedusers::fetch_all(array('cmid' => $cmid, 'assessorid' => $USER->id))){
											foreach($evalcomixallowedusers as $auser){
												$indexuser = $auser->studentid;
												$allowedusers[$indexuser] = true;
											}
										}
										$userid = $user->id;
										$condition = false;
										if(isset($allowedusers[$userid])){
											$condition = $allowedusers[$userid];
										}
										elseif($USER->id == $user->id){
											$condition = true;
										}
									}
								}
							}	
							/*if((!$groupmembersonly && (
								($same_grouping && (
										($groupmode != 1 
										|| $same_group))
								) 
								|| 
								(!$groupingid && 
									(($groupmode != 1 
										|| $same_group)))
								)
							)
							|| ($groupmembersonly  && (
//								(!$this->cm[$cmid]->groupingid && $gid_loginuser != -1 && $gid_user != -1)
								(!$groupingid && 
									((($groupmode != 1 && $gid_loginuser != -1 && $gid_user != -1) 
										|| $same_group)))
								||
								($same_grouping && (
									($groupmode != 1 
										|| $same_group)))
								)
							)
							)*/
							if($condition){
								//($this->cm[$cmid]->groupmembersonly && $same_grouping && $groupmode == 1) ||	
								//($groupmode == 1 && ($gid_loginuser == $gid_user || $this->same_grouping($gid_loginuser, $gid_user, $this->activities['id'][$i])))){
								
								//$mode_time = $this->get_modes_time($this->activities['id'][$i], $mode);
								if($mode == 'self') { //Details always are shown in selfassessment
									$table .= $details;
								}
								if($mode_time[$cmid][$mode] != false){		
									
									$available = $mode_time[$cmid][$mode]->timeavailable;
									$due = $mode_time[$cmid][$mode]->timedue;
									
									//If the availability of the activity started
									if($now_timestamp >= $available){
										$title = get_string('studentwork1','block_evalcomix').get_string('studentwork2','block_evalcomix'). $this->activities['name'][$i];
										$table .= ' <input type="image" value="'.$title.'" style="background-color:transparent;width:13px" title="'.$title.'" src="../images/task.png" 
										onclick="javascript:urlDetalles(\''. $CFG->wwwroot. '/blocks/evalcomix/assessment/user_activity.php?id='.$user->id.'&course='.$this->courseid.'&mod='.$cmid.'\');"/>';
									}
									
									/*if($mode == 'self') { //Details always are shown in selfassessment
										$table .= $details;
									}*/
									
									if($now_timestamp >= $due && $mode == 'peer' && $showdetails == true) {	
										$url_peer_instrument = webservice_evalcomix_client::get_ws_view_assessment($this->courseid, $type_instrument[$cmid], $this->activities['id'][$i], $USER->id, $user->id, 'peer', MOODLE_NAME);
										$table .= '<input type="image" value="'.get_string('details', 'block_evalcomix').'" style="width:16px" title='.get_string('details', 'block_evalcomix').' src="../images/lupa.png" onclick="javascript:urlDetalles(\''. $url_peer_instrument .'\');"/>';
									}
									//Show the buttons if they must be availables
									if($now_timestamp >= $available && $now_timestamp < $due){
										$table .= $evaluate;
									}
								}
							}								
						}
						else{ //if $mode == 'teacher'
							
							//$table .= $details.$evaluate;
							$table .= $details;
							if($mode_teacher[$cmid] != null && $mode_teacher[$cmid]->modality == 'teacher'){
								$table .= $evaluate;
							}
						}							
					}	
					$table .= '</div>';
				}
										
				$table .= '</td>';					
			//	}
				
				//Calculates average
				if (count($aux) == 0){
					$average = 0;
				}
				else{
					$average = $this->calculator->calculate_one_array($aux);
					$average = round($average, 2, PHP_ROUND_HALF_UP);
					unset($aux);
				}
				
				$table .= '<td style="border:1px solid #146C84;">' . $average . '</td></tr>';	
			}
			
			$table .= '</table></div>';
			$table .= '</div>';
			
			return $table;
		}
		
		/**
		 * Return a modes_time object
		 * @param $cmid
		 * @param $modality
		 * @return object evalcomix_modes_time
		 */
		public function get_modes_time($cmid, $modality){
			
			if($task = evalcomix_tasks::fetch(array('instanceid' => $cmid))){
				if($mode = evalcomix_modes::fetch(array('taskid' => $task->id, 'modality' => $modality))){
					if($mode_time = evalcomix_modes_time::fetch(array('modeid' => $mode->id))){
						return $mode_time;
					}
				}
			}
			return false;
		}
		
		/**
		 * Return a modes_time object
		 * @param $taskid
		 * @param $modality
		 * @return object evalcomix_modes_time
		 */
		public function get_modestime($taskid, $modality){
			if($mode = evalcomix_modes::fetch(array('taskid' => $taskid, 'modality' => $modality))){
				if($mode_time = evalcomix_modes_time::fetch(array('modeid' => $mode->id))){
					return $mode_time;
				}
			}
			
			return false;
		}
		/**
		 * Return the groupmode of a course module
		 * 0: No groups
		 * 1: Separated groups
		 * 2: Visible groups
		 * @param $cmid
		 * @return int groupmode
		 */
		public function get_groupmode($cmid){
			global $DB;
			
			$cm = $DB->get_record('course_modules', array('id' => $cmid));
			
			if($cm){
				return $cm->groupmode;
			}
			else{
				return 0;
			}			
		}		
		
		/**
		 * Return the groupmode of a course module
		 * 0: No groups
		 * 1: Separated groups
		 * 2: Visible groups
		 * @param $cmid
		 * @return int groupmode
		 */
		public function get_cm($cmid){
			global $DB;
			
			$cm = $DB->get_record('course_modules', array('id' => $cmid));
			
			if($cm){
				return $cm;
			}
			else{
				return 0;
			}			
		}
		/**
		 * Return the groupids of an user or -1 if doesn´t exist a group for that user
		 * @param $userid 
		 * @return array groupids of false if there is not any group for $userid
		 */
		public function get_groupids($userid){			
			if(!empty($this->coursegroups)){
				$result = array();
				foreach($this->coursegroups as $groupid => $group){
					if(in_array($userid, $group)){
						$result[] = $groupid;
					}
				}
				if(!empty($result)){
					return $result;
				}
			}
			/*$groups = $DB->get_records('groups', array('courseid' => $this->courseid));

			if($groups){
				foreach($groups as $group){
					$groups_member = $DB->get_record('groups_members', array('groupid' => $group->id, 'userid' => $userid));
					if($groups_member){
						return $group->id;		
					}					
				}						
			}*/
			
			return -1;
		}
		
		/**
		* Load groupings with their group ids
		*/
		public function load_groupings(){
			global $DB;
			if($groupings = $DB->get_records('groupings', array('courseid' => $this->courseid))){			
				foreach($groupings as $grouping){
					$groupingid = $grouping->id;
					if($groupings_groups = $DB->get_records('groupings_groups', array('groupingid' => $groupingid))){
						foreach($groupings_groups as $gg){
							$this->coursegroupings[$groupingid][] = $gg->groupid;
						}
					}
				}
			}
			return $this->coursegroupings;
		}
		
		/**
		* Load groups with their user ids
		*/
		public function load_groups(){
			global $DB;
			if($groups = $DB->get_records('groups', array('courseid' => $this->courseid))){			
				foreach($groups as $group){
					$groupid = $group->id;
					if($groups_members = $DB->get_records('groups_members', array('groupid' => $groupid))){
						foreach($groups_members as $gm){
							$this->coursegroups[$groupid][] = $gm->userid;
						}
					}
				}
			}
			return $this->coursegroups;
		}
		/**
		 * Return true if there is a grouping between two groups in one activity
		 * @param $gid1
		 * @param $gid2
		 * @param $cmid
		 * @return bool
		 */
		public function same_grouping($gid1, $gid2, $cm){
			global $DB;
			
			//$cm = $DB->get_record('course_modules', array('id' => $cmid));
			
			//if it exists
			if($cm){
				//if that activity has a grouping
				if($cm->groupingid != 0){
					$grouping1 = $DB->get_record('groupings_groups', array('groupingid' => $cm->groupingid, 'groupid' => $gid1));
					$grouping2 = $DB->get_record('groupings_groups', array('groupingid' => $cm->groupingid, 'groupid' => $gid2));
				
					//if those groups are in the same grouping of the activity
					if($grouping1 && $grouping2){
						return true;
					}				
				}
			}
			
			//if they exists
			/*if($groupings1 != false && $groupings2 != false){
				
				//it compares if those groups are in some grouping
				foreach($groupings1 as $grouping1){
					foreach($groupings2 as $grouping2){
						if($grouping1->groupingid == $grouping2->groupingid){
							return true;
						}
					}
				}	
			}*/

			return false;
		}
		
		public function same_group($uid1, $uid2){
			if(!empty($this->coursegroups)){
				foreach($this->coursegroups as $group){
					if(in_array($uid1, $group) && in_array($uid2, $group)){
						return true;
					}
				}
			}
			return false;
			/*global $DB, $CFG;
			
			$sql = "SELECT gm.id
					FROM {groups_members} gm
					WHERE gm.userid = :uid1 AND gm.groupid IN (SELECT gm2.groupid
																FROM {groups_members} gm2
																WHERE gm2.userid = :uid2)";
			$group_members = $DB->get_records_sql($sql, array('uid1' => $uid1, 'uid2' => $uid2));
			if(!empty($group_members)){
				return true;
			}
			
			return false;
			*/
		}
		/**
		 * Return true if there is a grouping between two groups in one activity
		 * @param $uid1
		 * @param $uid2
		 * @param $cm
		 * @return bool
		 */
		public function same_grouping_by_users($uid1, $uid2, $cm){
			if($cm->groupingid){
				$groupingid = $cm->groupingid;
				$grouping_groups = $this->coursegroupings[$groupingid];
				$groups1 = $this->get_groupids($uid1);
				$groups2 = $this->get_groupids($uid2);
				if(is_array($groups1) && is_array($groups2)){
					$intersect1 = array_intersect($groups1, $grouping_groups);
					$intersect2 = array_intersect($groups2, $grouping_groups);
					if(!empty($intersect1) && !empty($intersect2)){
						return true;
					}
				}
				return false;
			}
			
			/*global $DB, $CFG;
			
			if($cm->groupingid){
				$sql = "SELECT gm.id, gm.groupid, gm.userid, ggr.groupingid
						FROM {groupings_groups} ggr, {groups_members} gm
						WHERE ggr.groupingid = :groupingid
							AND gm.groupid = ggr.groupid AND (gm.userid = :uid1 OR gm.userid = :uid2)";
				$group_members = $DB->get_records_sql($sql, array('groupingid' => $cm->groupingid, 'uid1' => $uid1, 'uid2' => $uid2));	
				
				
				$flag_uid1 = false;
				$flag_uid2 = false;
				foreach($group_members as $group_member){
					if($group_member->userid == $uid1){
						$flag_uid1 = true;
					}
					elseif($group_member->userid == $uid2){
						$flag_uid2 = true;
					}
				}
				$result = false;
				if($uid1 == $uid2){
					$result = $flag_uid1;
				}
				else{
					$result = $flag_uid1 && $flag_uid2;
				}
				return $result;
			}
			return false;
			*/
		}
		
		
		/**
		 * Return teacher, self or peer, according to the user id received
		 * @param $userid
		 * @param $courseid 
		 * @param $assessorid
		 * @return string $mode
		 */
		public static function get_type_evaluation($userid, $courseid, $assessorid = '0') {
			global $USER;	
			
			$context = context_course::instance($courseid);
			if($assessorid != '0'){
			    $evaluatorid = $assessorid;
			}
			else{
			    $evaluatorid = $USER->id;
			}
			
			if ($evaluatorid == $userid){
				$mode = 'self';
			}
			else{
				//if (has_capability('block/evalcomix:edit',$context, $USER->id)){
				if (has_capability('moodle/grade:viewhidden',$context, $evaluatorid)){
					$mode = 'teacher';
				}
				else{
					$mode = 'peer';
				}
			}
			return $mode;
		}
		
		
		/**
		 * Builds and returns the headers of the table
		 * @return string HTML
		 */
		public function get_headers() {
			global $USER, $DB;
			if (!$course = $DB->get_record('course', array('id' => $this->courseid))) {
				print_error('nocourseid');
			}
			$total = '';
			//To print  Lastname / Firstname
			$arrows = $this->get_sort_arrows();
			
			$header = '<tr style="border:1px solid #146C84;">					
						<th colspan="2">'.$arrows['studentname'].'</th>';
						
			//print_r($this->gtree->levels[1]);
			$levels = $this->gtree->get_levels();
			foreach ($levels as $row) {				
				foreach ($row as $element) {
					if($element['object']->hidden == 1 && !$this->canviewhidden){
						continue;
					}
					
					if(isset($element['object']->itemnumber) && $element['object']->itemnumber == 0 && $element['object']->itemtype != 'manual'){
						// Checks if it is an activity
						if ($element['type'] == 'item'){
							if ($cm = get_coursemodule_from_instance($element['object']->itemmodule, $element['object']->iteminstance, $this->courseid)) {
								$cmid = $cm->id;
								if(!$task = evalcomix_tasks::fetch(array('instanceid' => $cmid))){
									$task = new evalcomix_tasks('', $cmid, 100, 50, time(), '1');
									$task->insert();
								}
								elseif($task->visible == 0){
									continue;									
								}
								$this->activities_configured[$cmid] = $this->configured_activity($cmid);
								if(!$this->canviewhidden && !$this->activities_configured[$cmid]){
									continue;
								}
								
								if(property_exists($cm, 'availability')){
									$grey = false;
									$hide = false;
									global $USER;
									$this->modinfo = new course_modinfo($course, $USER->id);
									$instances = $this->modinfo->get_instances_of($element['object']->itemmodule);
									if (!empty($instances[$element['object']->iteminstance])) {
										$cm_info = $instances[$element['object']->iteminstance];
										if (!$cm_info->uservisible) {
											// If there is 'availableinfo' text then it is only greyed
											// out and not entirely hidden.
											if (!$cm_info->availableinfo) {
												$hide = true;
											}
											$grey = true;
										}
									}
								}
								
								if(!$this->canviewhidden){
									if(!empty($hide) || !empty($grey)){
										continue;
									}
									
									$gm = true;
									if(property_exists($cm, 'groupmembersonly') && !$cm->groupmembersonly){
										$gm = false;
									}
									
									if($gm){
										$groupingid = $cm->groupingid;
										$intersect1 = array();
										$groups = $this->get_groupids($USER->id);
										if(isset($this->coursegroupings[$groupingid]) && is_array($groups)){
											$grouping_groups = $this->coursegroupings[$groupingid];
											$intersect1 = array_intersect($groups, $grouping_groups);
										}
										
										if($cm->groupingid && empty($intersect1)){
											continue;
										}
										/*elseif(!$cm->groupingid && $groups == -1){
											continue;
										}*/								
									}
								}
								if($cm->visible == 0 && !$this->canviewhidden){
								//if($cm->visible == 0){
									continue;
								}
							}
							$header .= '<th style="width:10em;border:1px solid #146C84;">'.$this->print_header_element($element, true);
							
							$header .= '</th>';
						}
						// Checks if it is the total grade of the course
						if ($element['type'] == 'courseitem'){
							$total = '<th style="border:1px solid #146C84;">
								'.$this->print_header_element($element, true).'</th>';
						}
					}
				}
			}
		
			$header .= $total.'</tr>';
			
			return $header;		
		}
		
		/**
		 * Builds and return icon and name of the header element
		 * @return string HTML
		 */
		public function print_header_element(&$element, $withlink=false, $spacerifnone=false) {
			global $CFG;

			$header = $this->gtree->get_element_icon($element, $spacerifnone);
			
			$name = $element['object']->get_name();
			$dots = '';
			if(strlen($name) > 24){
				$dots = '... ';
			}
			
			$header .= substr($element['object']->get_name(),0,24) . $dots;

			if ($element['type'] != 'item' and $element['type'] != 'categoryitem' and
				$element['type'] != 'courseitem') {
				return $header;
			}

			$itemtype     = $element['object']->itemtype;
			$itemmodule   = $element['object']->itemmodule;
			$iteminstance = $element['object']->iteminstance;
			
			global $DB;
			if (!$course = $DB->get_record('course', array('id' => $this->courseid))) {
				print_error('nocourseid');
			}
			if ($withlink and $itemtype=='mod' and $iteminstance and $itemmodule) {
				if ($cm = get_coursemodule_from_instance($itemmodule, $iteminstance, $this->courseid)) {

					//Insert id and name in $this->activities[] to know the activities order
					$this->activities['id'][] = $cm->id;
					$this->activities['name'][] = $element['object']->itemname;
					$this->activities['modulename'][] = $itemmodule;
					$cmid = $cm->id;
					$this->cm[$cmid] = $cm;
					
					$a = new stdClass();
					$a->name = get_string('modulename', $element['object']->itemmodule);
					//$title = get_string('linktoactivity', 'grades', $a);
					$title = get_string('linktoactivity', 'block_evalcomix');
					$dir = $CFG->dirroot.'/mod/'.$itemmodule;

					if (file_exists($dir.'/grade.php')) {
						$url = $CFG->wwwroot.'/mod/'.$itemmodule.'/grade.php?id='.$cm->id;
					} else {
						$url = $CFG->wwwroot.'/mod/'.$itemmodule.'/view.php?id='.$cm->id;
					}
					
					$grey = false;
					if(property_exists($cm, 'availability')){
						global $USER;
						$this->modinfo = new course_modinfo($course, $USER->id);
						$instances = $this->modinfo->get_instances_of($element['object']->itemmodule);
						if (!empty($instances[$element['object']->iteminstance])) {
							$cm_info = $instances[$element['object']->iteminstance];
							if (!$cm_info->uservisible) {
								// If there is 'availableinfo' text then it is only greyed
								// out and not entirely hidden.
								/*if (!$cm_info->availableinfo) {
									$hide = true;
								}*/
								$grey = true;
							}
						}
					}
					
					$style = '';
					if($cm->visible == 0 || $grey == true){
						$style = 'color:#737373';
					}
					
					$header = '<a style="'.$style.'" href="'.$url.'" title="'.s($title).'" target="v"
					onclick="window.open(\'\', \'v\', \'scrollbars,resizable,width=1000,height=600\');"
					>'.$header.'</a>';
					
					$width = 'width:17px';
					//if(!$this->configured_activity($cm->id)){
					if($this->activities_configured[$cmid] == false){
						$width = 'width:25px;';
					}
					//If $USER has editing permits						
					if($this->editing_permits_user()){
						$header .= '<input type="image" style="border:0; '.$width.'" src="../images/edit.png" title='.get_string('set', 'block_evalcomix').' alt='.get_string('set', 'block_evalcomix').' onclick="location.href=\'activity_edit_form.php?id=' . $this->courseid . '&a=' . $cm->id . '\'">';
					}					
				}
			}

			return $header;
		}

		
		/*
		 * Returns the an array of two dimensions with ids and names of activities
		 * To obtain the data: $this->activities['id'][] / $this->activities['name'][]
		 * @return array activities of the object
		 */
		public static function get_activities(){
			return $this->activities;
		}
		
		
		/**
		* It gets grades involved in the report. 
		* @param string $mode indicates source of grades: Only EvalCOMIX ('evalcomix') or a combination of Moodle and EvalCOMIX grades ('moodleevx').
		* @return array|object Grades of the report.
		*/
		public function get_grades($mode = 'evalcomix'){
			//$grades = [new stdclass() || array()];
			$evalcomix_grades = $this->get_evalcomix_grades();
			if($mode == 'evalcomix'){
				$grades = $evalcomix_grades;
			}
			elseif($mode == 'moodleevx'){
				$moodle_grades = $this->get_moodle_grades();	
				$grades = $this->calculator->calculate($moodle_grades, $evalcomix_grades, $this->activities, $this->users);
			}
			
			return $grades;
		}
		
		/**
		* It gets EvalCOMIX grades
		* @return array grades
		*/
		public function get_evalcomix_grades(){
			return $this->grade_viewer->get_grades($this->courseid, $this->users);
		}
		
		/**
		 * we supply the userids in this query, and get all the grades
		 * pulls out all the grades, this does not need to worry about paging
		 */
		public function get_moodle_grades(){
			global $DB;			
			$moodlegrades = array();
			
			//Looking for activities that have been evaluated
			$items = $DB->get_records('grade_items', array('courseid' => $this->courseid, 'itemtype' => 'mod'));

			foreach($items as $item){			
				//Looking for grades of the activities that have been evaluated
				$grades = $DB->get_records('grade_grades', array('itemid' => $item->id));				
				foreach($grades as $grade){
					//Looking for cmid of the activities that have been evaluated
					$cm = $DB->get_record('course_modules', array('course' => $this->courseid, 'instance' => $item->iteminstance));
					if(isset($cm)){
						$moodlegrades[$cm->id][$grade->userid] = $grade->finalgrade;
					}
				}
			}
			
			return $moodlegrades;
			// please note that we must fetch all grade_grades fields if we want to construct grade_grade object from it!
			/*$params = array_merge(array('courseid'=>$this->courseid), $this->userselect_params);
			$sql = "SELECT g.*
					  FROM {grade_items} gi,
						   {grade_grades} g
					 WHERE g.itemid = gi.id AND gi.courseid = :courseid {$this->userselect}";

			$userids = array_keys($this->users);


			if ($grades = $DB->get_records_sql($sql, $params)) {
				foreach ($grades as $graderec) {
					if (in_array($graderec->userid, $userids) and array_key_exists($graderec->itemid, $this->gtree->get_items())) { // some items may not be present!!
						$moodlegrades[$graderec->userid][$graderec->itemid] = new grade_grade($graderec, false);
						$moodlegrades[$graderec->userid][$graderec->itemid]->grade_item =& $this->gtree->get_item($graderec->itemid); // db caching
					}
				}
			}

			// prefil grades that do not exist yet
			foreach ($userids as $userid) {
				foreach ($this->gtree->get_items() as $itemid=>$unused) {
					if (!isset($moodlegrades[$userid][$itemid])) {
						$moodlegrades[$userid][$itemid] = new grade_grade();
						$moodlegrades[$userid][$itemid]->itemid = $itemid;
						$moodlegrades[$userid][$itemid]->userid = $userid;
						$moodlegrades[$userid][$itemid]->grade_item =& $this->gtree->get_item($itemid); // db caching
					}
				}
			}*/
		}

		/**
		* @return array of course tools by [taskid][modality]
		*/
		function load_tools(){
			$result = array();
			if($evalcomix = evalcomix::fetch(array('courseid' => $this->courseid))){
				if($tools = evalcomix_tool::fetch_all(array('evxid' => $evalcomix->id))){
					foreach($tools as $tool){
						if($modes = evalcomix_modes::fetch_all(array('toolid' => $tool->id))){
							foreach($modes as $mode){
								$taskid = $mode->taskid;
								$modality = $mode->modality;
								$result[$taskid][$modality] = $tool;
							}
						}
					}
				}
			}
			return $result;
		}
		
		/**
		* @param array tasks
		* @return array of course assessments by [taskid][assessor][student]
		*/
		function load_assessments($tasks){
			$result = array();
			if(is_array($tasks)){
				foreach($tasks as $task){
					if(isset($task->id)){
						if($assessments = evalcomix_assessments::fetch_all(array('taskid' => $task->id))){
							$taskid = $task->id;
							foreach($assessments as $assessment){								
								$assessorid = $assessment->assessorid;	
								$studentid = $assessment->studentid;
								$result[$taskid][$assessorid][$studentid] = $assessment;
							}
						}
					}
				}
			}
			return $result;
		}
		
		
		/**
		 * pulls out the userids of the users to be display, and sorts them
		 */
		public function load_users($page = true) {
			global $CFG, $DB;

			//limit to users with a gradeable role
			list($gradebookrolessql, $gradebookrolesparams) = $DB->get_in_or_equal(explode(',', $this->gradebookroles), SQL_PARAMS_NAMED, 'grbr0');

			//limit to users with an active enrollment
			list($enrolledsql, $enrolledparams) = get_enrolled_sql($this->context);

			//fields we need from the user table
			$userfields = user_picture::fields('u', array('idnumber'));

			$sortjoin = $sort = $params = null;

			//if the user has clicked one of the sort asc/desc arrows
			if (is_numeric($this->sortitemid)) {
				$params = array_merge(array('gitemid'=>$this->sortitemid), $gradebookrolesparams, $this->groupwheresql_params, $enrolledparams);

				$sortjoin = "LEFT JOIN {grade_grades} g ON g.userid = u.id AND g.itemid = $this->sortitemid";
				$sort = "g.finalgrade $this->sortorder";

			} else {
				$sortjoin = '';
				switch($this->sortitemid) {
					case 'lastname':
						$sort = "u.lastname $this->sortorder, u.firstname $this->sortorder";
						break;
					case 'firstname':
						$sort = "u.firstname $this->sortorder, u.lastname $this->sortorder";
						break;
					case 'idnumber':
					default:
						$sort = "u.idnumber $this->sortorder";
						break;
				}

				$params = array_merge($gradebookrolesparams, $this->groupwheresql_params, $enrolledparams);
			}
			
			$sqlcontext = '';
			if ($parents = $this->context->get_parent_context_ids()) {
				$sqlcontext = (' IN ('.$this->context->id.','.implode(',', $parents).')');
			} else {
				$sqlcontext = (' ='.$this->context->id);
			}
			$sql = "SELECT $userfields
					  FROM {user} u
					  JOIN ($enrolledsql) je ON je.id = u.id
						   $this->groupsql
						   $sortjoin
					  JOIN (
							   SELECT DISTINCT ra.userid
								 FROM {role_assignments} ra
								WHERE ra.roleid IN ($this->gradebookroles)
								  AND ra.contextid " . $sqlcontext . "
						   ) rainner ON rainner.userid = u.id
					   AND u.deleted = 0
					   $this->groupwheresql
				  ORDER BY $sort";

			//$this->users = $DB->get_records_sql($sql, $params, $this->get_pref('studentsperpage') * $this->page, $this->get_pref('studentsperpage'));
			if($page == true){
				$this->users = $DB->get_records_sql($sql, $params, $this->studentsperpage * $this->page, $this->studentsperpage);
			}
			else{
				$this->users = $DB->get_records_sql($sql, $params);
			}
			
			if (empty($this->users)) {
				$this->userselect = '';
				$this->users = array();
				$this->userselect_params = array();
			} else {
				list($usql, $uparams) = $DB->get_in_or_equal(array_keys($this->users), SQL_PARAMS_NAMED, 'usid0');
				$this->userselect = "AND g.userid $usql";
				$this->userselect_params = $uparams;

				//add a flag to each user indicating whether their enrolment is active
				$sql = "SELECT ue.userid
						  FROM {user_enrolments} ue
						  JOIN {enrol} e ON e.id = ue.enrolid
						 WHERE ue.userid $usql
							   AND ue.status = :uestatus
							   AND e.status = :estatus
							   AND e.courseid = :courseid
					  GROUP BY ue.userid";
				$coursecontext = context_course::instance($this->courseid);
				$params = array_merge($uparams, array('estatus'=>ENROL_INSTANCE_ENABLED, 'uestatus'=>ENROL_USER_ACTIVE, 'courseid'=>$coursecontext->instanceid));
				$useractiveenrolments = $DB->get_records_sql($sql, $params);

				foreach ($this->users as $user) {
					$this->users[$user->id]->suspendedenrolment = !array_key_exists($user->id, $useractiveenrolments);
				}
			}

			return $this->users;
		}
		
		/**
		 * Refactored function for generating HTML of sorting links with matching arrows.
		 * Returns an array with 'studentname' and 'idnumber' as keys, with HTML ready
		 * to inject into a table header cell.
		 * @return array An associative array of HTML sorting links+arrows
		 */
		public function get_sort_arrows() {
			global $OUTPUT;
			$arrows = array();

			$strsortasc   = $this->get_lang_string('sortasc', 'grades');
			$strsortdesc  = $this->get_lang_string('sortdesc', 'grades');
			$strfirstname = $this->get_lang_string('firstname');
			$strlastname  = $this->get_lang_string('lastname');

			$firstlink = html_writer::link(new moodle_url($this->baseurl, array('sortitemid'=>'firstname')), $strfirstname);
			$lastlink = html_writer::link(new moodle_url($this->baseurl, array('sortitemid'=>'lastname')), $strlastname);
			$idnumberlink = html_writer::link(new moodle_url($this->baseurl, array('sortitemid'=>'idnumber')), get_string('idnumber'));

			$arrows['studentname'] = $lastlink;

			if ($this->sortitemid === 'lastname') {
				if ($this->sortorder == 'ASC') {
					///$arrows['studentname'] .= print_arrow('up', $strsortasc, true);
					$arrows['studentname'] .= '<img src="'.$OUTPUT->pix_url('t/' . 'up') . '" alt="'.$strsortasc.'" /> ';
				} else {
					//$arrows['studentname'] .= print_arrow('down', $strsortdesc, true);
					$arrows['studentname'] .= '<img src="'.$OUTPUT->pix_url('t/' . 'down') . '" alt="'.$strsortasc.'" /> ';
				}
			}

			$arrows['studentname'] .= ' ' . $firstlink;

			if ($this->sortitemid === 'firstname') {
				if ($this->sortorder == 'ASC') {
					//$arrows['studentname'] .= print_arrow('up', $strsortasc, true);
					$arrows['studentname'] .= '<img src="'.$OUTPUT->pix_url('t/' . 'up') . '" alt="'.$strsortdesc.'" /> ';
				} else {
					//$arrows['studentname'] .= print_arrow('down', $strsortdesc, true);
					$arrows['studentname'] .= '<img src="'.$OUTPUT->pix_url('t/' . 'down') . '" alt="'.$strsortdesc.'" /> ';
				}
			}

			$arrows['idnumber'] = $idnumberlink;

			if ('idnumber' == $this->sortitemid) {
				if ($this->sortorder == 'ASC') {
					//$arrows['idnumber'] .= print_arrow('up', $strsortasc, true);
					$arrows['idnumber'] .= '<img src="'.$OUTPUT->pix_url('t/' . 'up') . '" alt="'.$strsortasc.'" /> ';
				} else {
					//$arrows['idnumber'] .= print_arrow('down', $strsortdesc, true);
					$arrows['idnumber'] .= '<img src="'.$OUTPUT->pix_url('t/' . 'down') . '" alt="'.$strsortasc.'" /> ';
				}
			}

			return $arrows;
		}
		
	}	
	
	function add_select_range($name, $ibegin, $iend, $iselected, $extra){
		$ibegin = intval($ibegin);
		$iend = intval($iend);
		$iselected = intval($iselected);
		
		$result = '<select id = "'. $name . '" name = "'. $name . '" '. $extra. '>';
		for($i = $ibegin; $i <= $iend; $i++){
			$selected = '';
			if($i == $iselected)
				$selected = 'selected="selected"';
			$result .= '
					<option value="'. $i . '" ' . $selected .'>'. $i . '</option>
			';
		}
		$result .= '
			</select>
		';
		return $result;
	}
	
	function add_select($name, $options, $iselected, $extra){
		$tam = sizeof($options);
		$result = '<select id = "'. $name . '" name = "'. $name . '" '. $extra. '>';
		foreach($options as $key => $value){
			$selected = '';
			if($key == $iselected)
				$selected = 'selected="selected"';
			$result .= '
					<option value="'. $key . '" ' . $selected .'>'. $value . '</option>
			';
		}
		$result .= '
			</select>
		';
		return $result;
	}
	
	function add_date_time_selector($name, $timestamp, $extra){
		if(!isset($timestamp) && !is_number($timestamp))
			$timestamp = time();
		$day = date('j', $timestamp); 
		$month = date('n', $timestamp); 
		$year = date('Y', $timestamp); 
		$hour = date('G', $timestamp);
		$minute = date('i', $timestamp);
		if($min = $minute % 5 != 0)
			$minute -= $min;
		
		$months = array(1 => '"'.get_string('january', 'block_evalcomix').'"', 2 => '"'.get_string('february', 'block_evalcomix').'"', 3 => '"'.get_string('march', 'block_evalcomix').'"', 4 => '"'.get_string('april', 'block_evalcomix').'"',
		5 => '"'.get_string('may', 'block_evalcomix').'"', 6 => '"'.get_string('june', 'block_evalcomix').'"', 7 => '"'.get_string('july', 'block_evalcomix').'"', 8 => '"'.get_string('august', 'block_evalcomix').'"', 9 => '"'.get_string('september', 'block_evalcomix').'"',
		10 => '"'.get_string('october', 'block_evalcomix').'"', 11 => '"'.get_string('november', 'block_evalcomix').'"', 12 => '"'.get_string('december', 'block_evalcomix') . '"');
		$result = add_select_range('day_'. $name, 1, 31, $day, $extra);
		$result.= add_select('month_'. $name, $months, $month, $extra);
		$result.= add_select_range('year_'. $name, 1970, 2020, $year, $extra);
		$result.= add_select_range('hour_'. $name, 0, 23, $hour, $extra);
		$minutes = array(0 => '00', 5 => '05', 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 35 => 35, 40 => 40, 45 => 45, 50 => 50, 55 => 55);
		$result.= add_select('minute_'. $name, $minutes, $minute, $extra);
		return $result;
	}
	
	
	/**
	* Get activity datas
	* @param object $cm  we pass it to save db access
	* @return object activity
	*/
	function get_activity_data($cm){
		global $DB;
		$modname = $cm->modname;
		$activity = null;
		if (! $activity = $DB->get_record($modname, array('id'=>$cm->instance))) {
			print_error('invalidid', $modname);
		}
		return $activity;
	}
	
	/**
	* @param int $courseid
	* @param object $cm course module ID
	* @return array datas of assessment modalities
	*/
	function get_evalcomix_activity_data($courseid, $cm){
		global $DB, $CFG;
			
		include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
		$result = array();
		if($task = evalcomix_tasks::fetch(array('instanceid' => $cm->id))){
			$result['toolEP'] = get_evalcomix_modality_tool($courseid, $task->id, 'teacher');
			$result['toolAE'] = get_evalcomix_modality_tool($courseid, $task->id, 'self');
			$result['toolEI'] = get_evalcomix_modality_tool($courseid, $task->id, 'peer');
			$result['weighingEP'] = get_evalcomix_modality_weighing($courseid, $task->id, 'teacher');
			$result['weighingAE'] = get_evalcomix_modality_weighing($courseid, $task->id, 'self');
			$result['weighingEI'] = get_evalcomix_modality_weighing($courseid, $task->id, 'peer');
			$timeAE = get_evalcomix_modality_time($courseid, $task->id, 'self');
			$result['availableAE'] = $timeAE['available'];
			$result['timedueAE'] = $timeAE['timedue'];
			$timeEI = get_evalcomix_modality_time($courseid, $task->id, 'peer');
			$result['availableEI'] = $timeEI['available'];
			$result['timedueEI'] = $timeEI['timedue'];
			$extraEI = get_evalcomix_modality_extra($courseid, $task->id, 'peer');
			$result['anonymousEI'] = $extraEI['anonymous'];
			$result['alwaysvisibleEI'] = $extraEI['visible'];
			$result['whoassessesEI'] = $extraEI['whoassesses'];
		}
		return $result;
	}

	/**
	* @param int $courseid
	* @param int $taskid evalcomix task ID
	* @return array datas of assessment modalities time
	*/
	function get_evalcomix_modality_extra($courseid, $taskid, $modality){
		global $DB, $CFG;
		include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');
		include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes_extra.php');
		if (!$course = $DB->get_record('course', array('id' => $courseid))) {
			print_error('nocourseid');
		}
		
		if (!$task = $DB->get_record('block_evalcomix_tasks', array('id' => $taskid))) {
			print_error('noevalcomixtaskid');
		}
		
		if($modality != 'teacher' && $modality != 'self' && $modality != 'peer'){
			print_error('No modality');
		}
		
		$result = array();
		if($mode = evalcomix_modes::fetch(array('taskid' => $task->id, 'modality' => $modality))){		
			$extra = evalcomix_modes_extra::fetch(array('modeid' => $mode->id));
			$result['anonymous'] = $extra->anonymous;
			$result['visible'] = $extra->visible;
			$result['whoassesses'] = $extra->whoassesses;
			return $result;
		}
		return false;
		
	}
	
	/**
	* @param int $courseid
	* @param int $taskid evalcomix task ID
	* @return array datas of assessment modalities time
	*/
	function get_evalcomix_modality_time($courseid, $taskid, $modality){
		global $DB, $CFG;
		include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');
		include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes_time.php');
		if (!$course = $DB->get_record('course', array('id' => $courseid))) {
			print_error('nocourseid');
		}
		
		if (!$task = $DB->get_record('block_evalcomix_tasks', array('id' => $taskid))) {
			print_error('noevalcomixtaskid');
		}
		
		if($modality != 'teacher' && $modality != 'self' && $modality != 'peer'){
			print_error('No modality');
		}
		
		$result = array();
		if($mode = evalcomix_modes::fetch(array('taskid' => $task->id, 'modality' => $modality))){		
			$time = evalcomix_modes_time::fetch(array('modeid' => $mode->id));
			$result['available'] = $time->timeavailable;
			$result['timedue'] = $time->timedue;
			return $result;
		}
		return false;
		
	}
	
	/**
	* @param int $courseid
	* @param int $taskid evalcomix task ID
	* @return array datas of assessment modalities weighing
	*/
	function get_evalcomix_modality_weighing($courseid, $taskid, $modality){
		global $DB, $CFG;
		include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');
		if (!$course = $DB->get_record('course', array('id' => $courseid))) {
			print_error('nocourseid');
		}
		
		if (!$task = $DB->get_record('block_evalcomix_tasks', array('id' => $taskid))) {
			print_error('noevalcomixtaskid');
		}
		
		if($modality != 'teacher' && $modality != 'self' && $modality != 'peer'){
			print_error('No modality');
		}
		
		$result = array();
		if($mode = evalcomix_modes::fetch(array('taskid' => $task->id, 'modality' => $modality))){		
			return $mode->weighing;
		}
		return false;
		
	}
	
	
	function get_evalcomix_modality_tool($courseid, $taskid, $modality){
		global $DB;
		/*if (!$course = $DB->get_record('course', array('id' => $courseid))) {
			print_error('nocourseid');
		}
		
		if (!$task = $DB->get_record('block_evalcomix_tasks', array('id' => $taskid))) {
			print_error('noevalcomixtaskid');
		}*/
		
		if($modality != 'teacher' && $modality != 'self' && $modality != 'peer'){
			print_error('No modality');
		}
		
		$result = array();
		if($mode = evalcomix_modes::fetch(array('taskid' => $taskid, 'modality' => $modality))){		
			if($tool = evalcomix_tool::fetch(array('id' => $mode->toolid))){
				return $tool;
			}
		}
		return false;
	}
	
	/**
	* Update database tools with Web Services tools
	* @param int $evxid primary key of evalcomix table
	* @param mixed $webtools tools of Web Service
	*/
	function update_tool_list($evxid, $webtools){
		global $CFG;
		include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');
		foreach($webtools as $tool){;
			if(!$toolupdate = evalcomix_tool::fetch(array('evxid' => $evxid, 'idtool' => $tool->idtool))){
				$newtool = new evalcomix_tool('', $evxid, $tool->title, $tool->type, (string)$tool->idtool);
				$newtool->insert();
			}			
			else{
				evalcomix_tool::set_properties($toolupdate, array('title' => $tool->title));
				$toolupdate->update();
			}
		}
		//Si hay instrumentos duplicados de una restauración de eliminan y se configuran las actividades correctamente
		if ($oldtools_restored = evalcomix_tool::fetch_all(array('evxid' => $evxid, 'timemodified' => '-1'))){
			global $COURSE,$DB;
			include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
			include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_modes.php');
			
			$activities = $DB->get_records('course_modules', array('course' => $COURSE->id));
			
			//Bucle recorriendo las actividades del curso y llamando a webservice_evalcomix_client::get_instrument($courseid, $module, $activity, $lms)
			//De ahí sacar el idtool de evalcomix y actualizar la tabla modes en moodle
			foreach($activities as $activity){ 
				if ($task = evalcomix_tasks::fetch(array('instanceid' => $activity->id))){
					$module = evalcomix_tasks::get_type_task($task->instanceid);
					$xml = webservice_evalcomix_client::get_instrument($COURSE->id, $module, $task->instanceid, MOODLE_NAME);
					//echo $COURSE->id . ' - ' .$module . ' - ' . $task->instanceid . ' - ' . MOODLE_NAME . '<br>';//
					//print_r($xml->evaluacion);
					//echo '<br>';
					if (isset($xml->evaluacion->teacher) && $xml->evaluacion->teacher != null){
						$tool = evalcomix_tool::fetch(array('evxid' => $evxid, 'idtool' => $xml->evaluacion->teacher));
						$modes = evalcomix_modes::fetch(array('taskid' => $task->id, 'modality' => 'teacher'));
						//Actualizamos el toolid de modes al toolid correcto
						evalcomix_modes::set_properties($modes, array('toolid' => $tool->id));
						$modes->update();
					}
					if (isset($xml->evaluacion->self) && $xml->evaluacion->self != null){
						$tool = evalcomix_tool::fetch(array('evxid' => $evxid, 'idtool' => $xml->evaluacion->self));
						$modes = evalcomix_modes::fetch(array('taskid' => $task->id, 'modality' => 'self'));
						//Actualizamos el toolid de modes al toolid correcto
						evalcomix_modes::set_properties($modes, array('toolid' => $tool->id));
						$modes->update();
					}
					if (isset($xml->evaluacion->peer) && $xml->evaluacion->peer != null){
						$tool = evalcomix_tool::fetch(array('evxid' => $evxid, 'idtool' => $xml->evaluacion->peer));
						$modes = evalcomix_modes::fetch(array('taskid' => $task->id, 'modality' => 'peer'));
						//Actualizamos el toolid de modes al toolid correcto
						evalcomix_modes::set_properties($modes, array('toolid' => $tool->id));
						$modes->update();
					}
				}				
			}			
			//Delete old restored tools
			foreach($oldtools_restored as $tool){				
				$tool->delete();
			}
			
			//$DB->get_record('block_evalcomix_tasks', array('instanceid' => $activity->id))
			/*print_r($old_new_instruments);
			print_r($oldtools_restored);*/
			//$xml_olds = array(); 
			/*foreach($oldtools_restored as $tool){				
				//Almacenamos los instrumentos antiguos antes de eliminarlos
				//$xml_olds[] = webservice_evalcomix_client::get_ws_tool_assessed('', '', '', '', '', '', '', (string)$tool->idtool);
				//$tool->delete(); DESCOMENTAR
			}*/
			/*$newtools =  evalcomix_tool::fetch_all(array('evxid' => $evxid));
			evalcomix_modes::fetch_all(array('toolid' => ));
			foreach($xml_olds as $xml_old_tool){
				foreach($newtools as $newtool){
					$new_xml = webservice_evalcomix_client::get_ws_tool_assessed('', '', '', '', '', '', '', $newtool->idtool);
					if ($xml_old_tool === $new_xml){
						
						//$mode = evalcomix_modes::fetch(array('taskid' => $xml_old_tool->taskid, 'toolid' => $xml_old_tool->idtool));
						
					}
				}			
			}*/
			
		}
	}

	/**
	* @param object $a tool object
	* @param object $b tool object
	* return relative position between objects depending on title tool
	*/
	function cmp_title_tool($a, $b){
		return strcmp(strtolower($a->title), strtolower($b->title));
	}
	
	/**
	* @param object $a tool object
	* @param object $b tool object
	* return relative position between objects depending on type tool
	*/
	function cmp_type_tool($a, $b){
		$value = strcmp(strtolower(get_string($a->type,'block_evalcomix')), strtolower(get_string($b->type,'block_evalcomix')));
		return $value;
	}

	/**
	 * It gets course activities
	 * @param array $params['courseid']
	 * @param array $params['context']
	 * @param array $params['modality'] ['teacher' | 'self' | 'peer']
	 * @return array $elements[cmid] or false
	 */
	function get_elements_course($params){
		if(isset($params['courseid']) && isset($params['context']) && isset($params['modality'])){
			$courseid = $params['courseid'];
			$context = $params['context'];
			$modality = $params['modality'];
		}
		else{
			return false;
		}	
		
		$report_evalcomix = new grade_report_evalcomix($courseid, null, $context);
		
		$levels = $report_evalcomix->gtree->get_levels();
		$self_users = array();
		$elements = array();
		foreach ($levels as $row) {
			foreach ($row as $element) {
				if ($element['type'] == 'item' or $element['type'] == 'categoryitem') {
					$itemtype     = $element['object']->itemtype;
					$itemmodule   = $element['object']->itemmodule;
					$iteminstance = $element['object']->iteminstance;
						
					if ($itemtype=='mod' and $iteminstance and $itemmodule) {
						if ($cm = get_coursemodule_from_instance($itemmodule, $iteminstance, $courseid)) {							
							if($task = evalcomix_tasks::fetch(array('instanceid' => $cm->id))){
								if($mode = evalcomix_modes::fetch(array('taskid' => $task->id, 'modality' => $modality))){
									$cm_id = $cm->id;
									$elements[$cm_id] = $element;
								}
							}
						}
					}
				}
			}
		}
		return $elements;
	}
	
