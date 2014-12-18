<?php
//defined('MOODLE_INTERNAL') || die();
include_once('evalcomix_object.php');
include_once('evalcomix_modes.php');

/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
 
 class evalcomix_grades extends evalcomix_object{
	 public $table = 'block_evalcomix_grades';
	 
	 /**
     * Array of required table fields, must start with 'id'.
     * @var array $required_fields
     */
    public $required_fields = array('id', 'userid', 'cmid', 'finalgrade', 'courseid');

	/**
     * Array of optional table fields.
     * @var array $required_fields
     */
    public $optional_fields = array();
	
	/**
	* course_module ID 
	* @var int $cmid
	*/
	public $cmid;
	
	/**
	* student ID associated
	* @var int $userid
	*/
	public $userid;
	
	/**
	* Finalgrade
	* @var float $finalgrade
	*/
	public $finalgrade;
	
	/**
	* @var int $courseid
	*/
	public $courseid;
	
    /**
     * The last time this evalcomix_assessment was modified.
     * @var int $timemodified
     */
    //public $timemodified;
	
	/**
	* Constructor
	*
	* @param int $params[id] ID
	* @param int $params[cmid] //foreign key of table 'course_modules'
	* @param int $params[userid] //foreign key of table 'user'
	* @param float $params[finalgrade] Grade
	*/
	public function __construct($params = array()){
		if(isset($params['id'])){
			$this->id = intval($params['id']);
		}
		if(isset($params['finalgrade'])){
			$this->finalgrade = floatval($params['finalgrade']);
		}
		if(isset($params['courseid'])){
			$this->courseid = floatval($params['courseid']);
		}
		
		//Por si queremos crear una instancia vacía (para usar evalcomix_object::fetch_all_helper es necesario)
		if (isset($params['cmid']) && is_numeric($params['cmid']) && !is_float($params['cmid']) && (int)$params['cmid'] > 0) {
			$this->cmid = $params['cmid'];
		}
		else {
			$this->cmid = 0;
		}
		
		//Por si queremos crear una instancia vacía (para usar evalcomix_object::fetch_all_helper es necesario)
		if (isset($params['userid']) && is_numeric($params['userid']) && !is_float($params['userid']) && $params['userid'] > '0'){
			$this->userid = $params['userid'];
		}
		else {
			$this->userid = 0;
		}
		
	}
	
	 /**
     * Updates this object in the Database, based on its object variables. ID must be set.
     * @param string $source from where was the object updated (mod/forum, manual, etc.)
     * @return boolean success
     */
	public function update(){
		global $DB;

        if (empty($this->id)) {
            debugging('Can not update assessment object, no id!');
            return false;
        }
		$this->timemodified = time();
		
        $data = $this->get_record_data();

        $DB->update_record($this->table, $data);

        $this->notify_changed(false);
        return true;
	}
	
	 /**
     * Finds and returns all evalcomix_grades instances.
     * @static abstract
     * @param array $params
     * @return array array of evalcomix_assessments instances or false if none found.
     */
	public static function fetch_all($params){
		return evalcomix_object::fetch_all_helper('block_evalcomix_grades', 'evalcomix_grades', $params);
	}	
	
	/**
     * Finds and returns one evalcomix_grades instances.
     * @static abstract
     * @param array $params
     * @return an object instance or false if not found
     */
	public static function fetch($params){
		return evalcomix_object::fetch_helper('block_evalcomix_grades', 'evalcomix_grades', $params);
	}
	
	/**
     * Called immediately after the object data has been inserted, updated, or
     * deleted in the database. Default does nothing, can be overridden to
     * hook in special behaviour.
     *
     * @param bool $deleted
     */
    function notify_changed($deleted) {
    }
	
	/**
	* Calculates finalgrade for a student in a activity
	* @param int $params[cmid] course module id
	* @param int $params[userid] student id
	* @return the finalgrade or -1 if there is only peer-assessment but assessment period hasn't finished or -2 if there is not any assessment
	*/
	public static function get_finalgrade_user_task($params){
		if(!isset($params['cmid']) || !isset($params['userid']) || !isset($params['courseid'])){
			return null;
		}
		$cmid = $params['cmid'];
		$userid = $params['userid'];
		$courseid = $params['courseid'];
		//$coursecontext = get_context_instance(CONTEXT_COURSE, $courseid);
		$coursecontext = context_course::instance($courseid);
		
		$now = time();
			
		global $CFG, $DB;
		include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
		if(!$task = evalcomix_tasks::fetch(array('instanceid' => $cmid))){
			return null;
		}
		
		$result = null;
		
		$teacherweight = -1;
		$selfweight = -1;
		$peerweight = -1;		

		$params_modes = array('taskid' => $task->id);
		$modes = evalcomix_modes::fetch_all($params_modes);
		if($modes){
			//Obtains activity´s weights
			foreach($modes as $mode){
				switch($mode->modality){
					case 'teacher': $teacherweight = $mode->weighing; break;
					case 'self': $selfweight = $mode->weighing; break;
					case 'peer': $peerweight = $mode->weighing; break;
					default:
				}				
			}
			//echo $task->id . ': teacherweight-' . $teacherweight . ' selfweight-' .$selfweight . ' peerweight-' .$peerweight.'<br/>';
			$params2 = array('taskid' => $task->id, 'studentid' => $userid);
			$assessments = evalcomix_assessments::fetch_all($params2);
			$inperiod = false;
			if($assessments){
				//$selfgrade = 0;							
				$selfgrade = -1;
				$teachergrade = 0;
				$numteachers = 0;
				$peergrade = 0;
				$numpeers = 0;
				$grade = 0;
				foreach($assessments as $assessment){						
					//If it is a self assessment
					if ($assessment->studentid == $assessment->assessorid && $selfweight != -1){
						$selfgrade = $assessment->grade;
					}
					//If it is a teacher assessment
					//elseif (has_capability('block/evalcomix:edit',$coursecontext, $assessment->assessorid)){
					elseif (has_capability('moodle/grade:viewhidden',$coursecontext, $assessment->assessorid)){
						if($teacherweight != -1){
							$teachergrade += $assessment->grade;
							$numteachers++;
						}
					}
					elseif($assessment->studentid != $assessment->assessorid) { //If it is a peer assessment
						//Only gets grades when the assessment period in the task is finished
						
						if($modeEI = evalcomix_modes::fetch(array('taskid' => $assessment->taskid, 'modality' => 'peer'))){
							$modeEItime = evalcomix_modes_time::fetch(array('modeid' => $modeEI->id));
							if ($modeEItime && $now > $modeEItime->timedue) {
								$peergrade += $assessment->grade;
								$numpeers++;
							}
							elseif($now >= $modeEItime->timeavailable && $now <= $modeEItime->timedue){
								$inperiod = true;
							}
						}
					}								
				}
					
				//Calculates peergrade
				if($numpeers > 0){
					$peergrade = round($peergrade/$numpeers, 2);
				}
				//Calculates teachergrade
				if($numteachers > 0){
					$teachergrade = round($teachergrade/$numteachers, 2);
				}
				//Calcultes the total grade
				//if($teachergrade != 0 || $selfgrade != 0 || $peergrade != 0){
				if($numteachers > 0 || $numpeers > 0 || $selfgrade != -1){
					if($selfgrade == -1){
						$selfgrade = 0;
					}
					$result = $selfgrade * ($selfweight/100) + $teachergrade * ($teacherweight/100) + $peergrade * ($peerweight/100);
				}
				elseif($inperiod == true){
					//There is peer assessments but assessment period hasn't finished
					$result = -1;
				}
				else{
					$result = -2;
				}
				return $result;
			}
			else{
				return null;
			}
		}
		else{
			return -3;
		}
	}
	
	/**
	* Get EvalCOMIX finalgrades for the assessment table
	* @var int courseid
	* @return array with finalgrades by cmid and userid
	*/
	public static function get_grades($courseid){
		$result = array();
		if($finalgrades = evalcomix_grades::fetch_all(array('courseid' => $courseid))){
			foreach($finalgrades as $finalgrade){
				$userid = $finalgrade->userid;
				$cmid = $finalgrade->cmid;
				$result[$cmid][$userid] = $finalgrade->finalgrade;
			}
		}
		return $result;
	}
 }
?>

