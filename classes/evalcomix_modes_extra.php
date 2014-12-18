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
 class evalcomix_modes_extra extends evalcomix_modes{
	 public $table = 'block_evalcomix_modes_extra';
	 
	 /**
     * Array of required table fields, must start with 'id'.
     * @var array $required_fields
     */
    public $required_fields = array('id', 'modeid', 'anonymous', 'visible', 'whoassesses');
	
	/**
     * Array of required table fields, must start with 'id'.
     * @var array $required_fields
     */
    public $optional_fields = array();

	/**
	* Mode ID associated
	* @var int $modeid
	*/
	public $modeid;

	 /**
     * If the assessor will be anonymous or not.
     * @var int $anonymous
     */
    public $anonymous;
	
	/**
     * If the modality will be visible before its limit date.
     * @var int $anonymous
     */
    public $visible;
	
	/**
     * It indicate who assesses who: '0' Any student, '1' Groups, '2' Specific students
     * @var int $whoassesses
     */
    public $whoassesses;

	/**
	* Constructor
	*
	* @param int $id ID
	* @param int $modeid foreign key of table 'block_evalcomix_modes'
	* @param bool $anonymous indicates if EI will be anonymous or not
	*/
	public function __construct($id = '', $modeid = '0', $anonymous = '0', $visible = '0', $whoassesses = '0'){
		if($modeid != '0'){
			global $DB;
			$this->id = intval($id);
			$this->anonymous = intval($anonymous);
			$this->visible = intval($visible);
			$modesObject = $DB->get_record('block_evalcomix_modes', array('id'=>$modeid), '*', MUST_EXIST);
			$this->modeid = $modesObject->id;
			$this->whoassesses = intval($whoassesses);
		}
	}
	
	/**
     * Finds and returns a evalcomix_modes_extra instance based on params.
     * @static
     *
     * @param array $params associative arrays varname=>value
     * @return object grade_item instance or false if none found.
     */
    public static function fetch($params) {
        return evalcomix_object::fetch_helper('block_evalcomix_modes_extra', 'evalcomix_modes_extra', $params);
    }
	
	 /**
     * Finds and returns all evalcomix_tool instances.
     * @static abstract
     *
     * @return array array of evalcomix_tool instances or false if none found.
     */
	public static function fetch_all($params){
		return evalcomix_tool::fetch_all_helper('block_evalcomix_modes_extra', 'evalcomix_modes_extra', $params);
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
	
	/**
     * Called immediately after the object data has been inserted, updated, or
     * deleted in the database. Default does nothing, can be overridden to
     * hook in special behaviour.
     *
     * @param bool $deleted
     */
    function notify_changed($deleted) {
    }
 }
?>