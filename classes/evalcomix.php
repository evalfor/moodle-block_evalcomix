<?php
include_once('evalcomix_object.php');

/**
 * Definitions of EvalCOMIX object class
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
 
 class evalcomix extends evalcomix_object {
	 public $table = 'block_evalcomix';
	 
	  /**
     * Array of required table fields, must start with 'id'.
     * @var array $required_fields
     */
    public $required_fields = array('id', 'courseid', 'viewmode', 'sendgradebook');
	
	/**
     * Array of optional table fields, must start with 'id'.
     * @var array $required_fields
     */
    public $optional_fields = array();
	
	/**
	* Course ID associated
	* @var int $evxid
	*/
	public $courseid;
	
	/**
	* View Mode
	* @var string $viewmode
	*/
	public $viewmode;
	
	/**
	* 1 or 0 if grades are sended to grade book
	* @var smallint $sendgradebook
	*/
	public $sendgradebook;
	
	/**
	* Constructor
	*
	* @param int $id ID
	* @param int $courseid foreign key of table 'course'
	* @param string $viewmode view mode. It can be: 'evalcomix' or 'evalmoodle'
	*/
	public function __construct($id = '', $courseid = '0', $viewmode = 'evalcomix', $sendgradebook = '0'){
		if($courseid != 0){
			global $DB;
			$this->id = intval($id);
			$this->courseid = intval($courseid);
			$this->viewmode = $viewmode;
			$this->sendgradebook = $sendgradebook;
			$course = $DB->get_record('course', array('id'=>$this->courseid), '*', MUST_EXIST);
			//Adding to control viewmode
			//$this->viewmode = addslashes($viewmode);
			if($this->viewmode != 'evalcomix' && $this->viewmode != 'evalmoodle'){
				print_error('The view mode is wrong');
			}
		}
	}

	/**
     * Finds and returns a evalcomix instance based on params.
     * @static
     *
     * @param array $params associative arrays varname=>value
     * @return object grade_item instance or false if none found.
     */
    public static function fetch($params) {
        return evalcomix_object::fetch_helper('block_evalcomix', 'evalcomix', $params);
    }
	
	 /**
     * Finds and returns all evalcomix_tool instances.
     * @static abstract
     *
     * @return array array of evalcomix_tool instances or false if none found.
     */
	public static function fetch_all($params){
		return evalcomix::fetch_all_helper('block_evalcomix', 'evalcomix', $params);
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
