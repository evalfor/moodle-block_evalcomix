<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
 
//Root directory of EvalCOMIX logo
if(!defined('EVXLOGOROOT')){
	define('EVXLOGOROOT', '/blocks/evalcomix/images/logoevalcomix.png');
}

global $CFG;
//Moodle instance name
if(!defined('MOODLE_NAME')){
	//define('MOODLE_NAME', 'Moodle243');
	define('MOODLE_NAME', $CFG->dbname);
}
	
if(isset($CFG->evalcomix_serverurl)){
	//URL base of EvalCOMIX application
	if(!defined('DIREvalCOMIX')){
		define('DIREvalCOMIX',$CFG->evalcomix_serverurl);
	}

	if(!defined('DIREvalCOMIXs')){
		define('DIREvalCOMIXs',$CFG->evalcomix_serverurl);
	}

	//EvalCOMIX API----------------------------------------------------------------------------
	if(!defined('MainAPI_EvalCOMIX')){
		define('MainAPI_EvalCOMIX', DIREvalCOMIX . '/webservice/get_list_tools_course.php');
	}

	if(!defined('Grade_EvalCOMIX')){
		define('Grade_EvalCOMIX', DIREvalCOMIX .'/webservice/get_grade.php');
	}

	if(!defined('DISPLAY_TOOL')){
		//define('DISPLAY_TOOL', DIREvalCOMIXs . '/webservice/print_tool_view.php');
		define('DISPLAY_TOOL', DIREvalCOMIXs . '/webservice/get_view_form.php');
	}

	if(!defined('FORM_ASSESS')){
		//define('FORM_ASSESS', DIREvalCOMIXs . '/webservice/print_tool_assess.php');
		define('FORM_ASSESS', DIREvalCOMIXs . '/webservice/get_assessment_form.php');
	}

	if(!defined('EVALCOMIX3')){
		define('EVALCOMIX3', DIREvalCOMIX .'/client/evalcomix3.php');
	}

	if(!defined('DELETE')){
		define('DELETE', DIREvalCOMIX .'/webservice/delete_tool.php');
	}

	if(!defined('GET_TOOL_ASSESSED')){
		define('GET_TOOL_ASSESSED', DIREvalCOMIX . '/webservice/get_tool.php');
	}

	if(!defined('DELETE_ASSESS')){
		define('DELETE_ASSESS', DIREvalCOMIX . '/webservice/delete_asessment.php');
	}

	if(!defined('DUPLICATE_COURSE')){
		define('DUPLICATE_COURSE', DIREvalCOMIX . '/webservice/duplicate_course.php');
	}

	if(!defined('DUPLICATE_COURSE2')){
		define('DUPLICATE_COURSE2', DIREvalCOMIX . '/webservice/duplicate_course2.php');
	}

	if(!defined('DUPLICATE_TOOL')){
		define('DUPLICATE_TOOL', DIREvalCOMIX . '/webservice/duplicate_tool.php');
	}
	if(!defined('GET_TOOLS')){
		define('GET_TOOLS', DIREvalCOMIX . '/webservice/get_tools.php');
	}

	if(!defined('VERIFY')){
		define('VERIFY', DIREvalCOMIX . '/webservice/verify.php');
	}
	if(!defined('GET_TOOLS2')){
		define('GET_TOOLS2', DIREvalCOMIX . '/webservice/get_tools2.php');
	}
	if(!defined('CREATE_TOOL')){
		define('CREATE_TOOL', DIREvalCOMIX . '/webservice/import_tool.php');
	}
	if(!defined('GET_ASSESSMENT_MODIFIED')){
		define('GET_ASSESSMENT_MODIFIED', DIREvalCOMIX . '/webservice/get_assessment_modified.php');
	}
	if(!defined('TOOL_MODIFIED')){
		define('TOOL_MODIFIED', DIREvalCOMIX . '/webservice/tool_modified.php');
	}
}
//Block name
if(!defined('blockname')){
	define('blockname', 'evalcomix');
}
?>
