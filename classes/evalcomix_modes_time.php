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
 class evalcomix_modes_time extends evalcomix_modes{
	 public $table = 'block_evalcomix_modes_time';
	 
	 /**
     * Array of required table fields, must start with 'id'.
     * @var array $required_fields
     */
    public $required_fields = array('id', 'modeid', 'timeavailable', 'timedue');
	
	 /**
     * Array of optional table fields.
     * @var array $optional_fields
     */
    public $optional_fields = array();

	/**
	* Mode ID associated
	* @var int $modeid
	*/
	public $modeid;

	 /**
     * The time this task is available.
     * @var int $timeavailable
     */
    public $timeavailable;

    /**
     * The time this task is not available.
     * @var int $timedue
     */
    public $timedue;
	
	/**
	* Constructor
	*
	* @param int $id ID
	* @param int $modeid foreign key of table 'block_evalcomix_modes'
	*/
	public function __construct($id = '', $modeid = '0', $timeavailable = '0', $timedue = '0'){
		if($modeid != '0'){
			global $DB;
			$this->id = intval($id);
			//Añadido comprobación
			$modesObject = $DB->get_record('block_evalcomix_modes', array('id'=>$modeid), '*', MUST_EXIST);
			$this->modeid = $modesObject->id;
			//Fin añadido, si no funciona, comentar lo anterior y descomentar siguiente línea
			//$this->modeid = intval($modeid);
			$this->timeavailable = intval($timeavailable); 
			$this->timedue = intval($timedue);  
		}
	}
	
	/**
     * Finds and returns a evalcomix_modes_time instance based on params.
     * @static
     *
     * @param array $params associative arrays varname=>value
     * @return object grade_item instance or false if none found.
     */
    public static function fetch($params) {
        return evalcomix_object::fetch_helper('block_evalcomix_modes_time', 'evalcomix_modes_time', $params);
    }
	
	 /**
     * Finds and returns all evalcomix_tool instances.
     * @static abstract
     *
     * @return array array of evalcomix_tool instances or false if none found.
     */
	public static function fetch_all($params){
		return evalcomix_tool::fetch_all_helper('block_evalcomix_modes_time', 'evalcomix_modes_time', $params);
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
	* @return bool|int if exist return ID else return 0
	*/
	public function exist(){
		global $DB;
		if (!$data = $DB->get_record($this->table, array('modeid' => $this->modeid))){
			return 0;
		}
		$this->id = $data->id;
		return $data->id;
	}
 }
?>