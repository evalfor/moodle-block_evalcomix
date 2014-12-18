<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
 
//defined('MOODLE_INTERNAL') || die();
include_once('evalcomix_object.php');

/**
 * Definitions of EvalCOMIX tool object class
 *
 * @package    block-evalcomix
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 */
 class evalcomix_tasks extends evalcomix_object{
	 public $table = 'block_evalcomix_tasks';
	 
	 /**
     * Array of required table fields, must start with 'id'.
     * @var array $required_fields
     */
    public $required_fields = array('id', 'instanceid', 'maxgrade', 'weighing', 'timemodified', 'visible');

	/**
     * Array of optional table fields, must start with 'id'.
     * @var array $required_fields
     */
    public $optional_fields = array();
	
	/**
	* course_module ID associated
	* @var int $instanceid
	*/
	public $instanceid;
	
	/**
	* Maximum grade for this task
	* @var int $maxgrade
	*/
	public $maxgrade;
	
	 /**
     * Weighing for this task
     * @var int $weighing
     */
    public $weighing;

    /**
     * The last time this evalcomix_task was modified.
     * @var int $timemodified
     */
    public $timemodified;
	
	/**
     * It indicates the visibility into assessment table
     * @var int $visible
     */
    public $visible;
	
	/**
	* Constructor
	*
	* @param int $id ID
	* @param int $instanceid foreign key of table 'course_modules'
	* @param float $maxgrade
	* @param int $weighing of EvalCOMIX grade respect Moodle grade
	* @param int $timemodified
	*/
	public function __construct($id = '', $instanceid = '0', $maxgrade = '100', $weighing = '50', $timemodified = '0', $visible = '1'){
		if($instanceid != '0'){
			global $DB;
			$this->id = intval($id);
			$cm = $DB->get_record('course_modules', array('id'=>$instanceid), '*', MUST_EXIST);
			$this->instanceid = $cm->id;
			$this->maxgrade = intval($maxgrade);
			$this->weighing = intval($weighing);
			$this->timemodified = $timemodified;
			$this->visible = intval($visible);			
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
            debugging('Can not update tool object, no id!');
            return false;
        }
		$this->timemodified = time();
		
        $data = $this->get_record_data();

        $DB->update_record($this->table, $data);

        $this->notify_changed(false);
        return true;
	}
	
	/**
	* @return bool|int if exist return ID else return 0
	*/
	public function exist(){
		global $DB;
		if (!$data = $DB->get_record($this->table, array('instanceid' => $this->instanceid))){
			return 0;
		}
		return $data->id;
	}
	
	 /**
     * Finds and returns all evalcomix_task instances.
     * @static abstract
     *
     * @return array array of evalcomix_tool instances or false if none found.
     */
	public static function fetch_all($params){
		return evalcomix_object::fetch_all_helper('block_evalcomix_tasks', 'evalcomix_tasks', $params);
	}	
	
	 /**
     * Finds and returns a evalcomix_task instance based on params.
     * @static
     *
     * @param array $params associative arrays varname=>value
     * @return object grade_item instance or false if none found.
     */
    public static function fetch($params) {
        return evalcomix_object::fetch_helper('block_evalcomix_tasks', 'evalcomix_tasks', $params);
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
	 * Obtains every id tasks for each course and returns them
	 * @param int $courseid
	 * @return array $ids with id tasks
	 */
	public static function get_tasks_by_courseid($courseid){
		global $DB;
		$tasks = array();
		$task = null;
		$i = 0;
		
		$cm = $DB->get_records('course_modules', array('course' => $courseid));
		foreach($cm as $value){
			$params = array('instanceid' => $value->id);
			$task = evalcomix_tasks::fetch($params);
			if ($task){
				$tasks[] = $task;
			}
		}
		return $tasks; 
	}
	
	/**
	* @param int $courseid
	* @return mixed array of evalcomix_task objects of the course
	*/
	/*public static function get_course_tasks($courseid){
		global $DB;
		$cm = $DB->get_records('course_modules', array('course' => $courseid));
		$result = array();
		foreach($cm as $task){
			$evalcomix_task = evalcomix_tasks::fetch(array('instanceid' => $task->id));
			if($evalcomix_task){
				$result[] = $evalcomix_task;
			}
		}
		return $result;
	}*/ //Desactivada porque parece la misma función que la anterior
	
	/**
	* @param int $courseid
	* @return mixed array of moodle activities configurated by evalcomix. 
	* Array key is evalcomix_tasks ID and Array value is activity name
	*/
	public static function get_moodle_course_tasks($courseid){
		global $DB;
		$evalcomix_tasks = evalcomix_tasks::get_tasks_by_courseid($courseid);//get_course_tasks($courseid); lo he cambiado por el tema de la duplicacion de funciones, probar si va bien
		$result = array();
		foreach($evalcomix_tasks as $task){ 
			$cm = $DB->get_record('course_modules', array('id' => $task->instanceid));
			if($cm){
				$module = evalcomix_tasks::get_type_task($cm->id);
				$task_moodle = $DB->get_record($module, array('id' => $cm->instance));
				$result[] = array('id' => $task->id, 'nombre' => $task_moodle->name);
			}
		}
		return $result;
	}

	/**
	* @param int $instanceid
	* @return string with the task's type. 
	*/	
	public static function get_type_task($instanceid){
		global $DB;
		$cm = $DB->get_record('course_modules', array('id' => $instanceid));
		$module = $DB->get_record('modules', array('id' => $cm->module));
		return $module->name;
	}
 }
?>