<?php/** * @package    block_evalcomix * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/} * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es> */ global $CFG;include_once($CFG->dirroot . '/webservice/rest/lib.php');include_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');include_once($CFG->dirroot . '/lib/filelib.php');/**	API for EvalCOMIX webservices*/class webservice_evalcomix_client {			/**	* @param string $lms name of instance of Moodle	* @param $courseid	* @param $language as 'es_es_utf8'	* @param $extra parameters to add to URI	* @return int new ID Tool	*/	public static function get_ws_createtool($id = null, $lms = 'Moodle24', $courseid, $language = 'es_es_utf8', $type = 'new'){		defined('EVALCOMIX3') || print_error('EvalCOMIX is not configured');		global $CFG;				$serverurl_aux = EVALCOMIX3;		if(!$id && $type == 'new'){			//$id = 240912 * (time() + time());			$id = webservice_evalcomix_client::generate_token();		}									$get = 'identifier='. $id . '&lang='. $language .'&type='.$type;					$serverurl = $serverurl_aux . '?'. $get;					if(webservice_evalcomix_client::check_url($serverurl)){			include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');			include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix.php');			$environment = evalcomix::fetch(array('courseid' => $courseid));			if(!$environment){				$environment = new evalcomix('', $courseid, 'evalcomix');				$environment->insert();			}			if(!evalcomix_tool::fetch(array('evxid' => $environment->id, 'idtool' => $id))){				$newtool = new evalcomix_tool('', $environment->id, '-000_1', 'tmp', $id);				$newtool->insert();			}					return $serverurl;		}		else{			webservice_evalcomix_client::print_error();		}	}			/**	* @param $toolid Assessment tool ID	* @return string URL validated to view tool	*/										   	public static function get_ws_viewtool($toolid = 0, $language = 'es_utf8', $courseid = 0, $module = 0, $activity = 0, $student = 0, $assessor = 0, $mode = 'teacher', $lms = 0,  $title = ''){		defined('DISPLAY_TOOL') || die('EvalCOMIX is not configured properly');					$str = $courseid . '_' . $module . '_' . $activity . '_' . $student . '_' . $assessor . '_' . $mode . '_' . $lms;		$assessmentid = md5($str);				$serverurl_aux = DISPLAY_TOOL;		$serverurl = $serverurl_aux . '?ass='. $assessmentid .'&pla='. $toolid. '&tit='.urlencode($title). '&lang=' . $language;				if(webservice_evalcomix_client::check_url($serverurl)){			return $serverurl;		}		else{			print_error('Evalcomix: invalid URL');		}	}		/**	* @param string $toolid	* @return mixed false if request failed or content of the file as string if ok. True if file downloaded into $tofile successfully.	*/	public static function get_ws_deletetool($toolid){		global $CFG;		defined('DELETE') || die('EvalCOMIX is not configured');						$serverurl_aux = DELETE;		$get = 'id=' . $toolid;		$serverurl = $serverurl_aux . '?'. $get;		//$result = download_file_content($serverurl);		include_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');				$curl = new Curly();		$response = $curl->get($serverurl);		if ($response && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){		 return $response; 		}		else{		  throw new Exception('SG: Bad Response');		}	}			/**	* @param string $toolid	* @param string $courseid	* @param string $module name	* @param string $activity ID	* @param string $student ID	* @param string $assessor ID	* @param string $mode [teacher | self | peer]	* @param string $lms name	* @return string URL of assessment form	*/	public static function get_ws_assessment_form($toolid = 0, $language = 'es_utf8', $courseid = 0, $module = 0, $activity = 0, $student = 0, $assessor = 0, $mode = 'teacher', $lms = 0, $perspective = 'assess', $title = ''){		defined('FORM_ASSESS') || die('EvalCOMIX is not configured properly');				$str = $courseid . '_' . $module . '_' . $activity . '_' . $student . '_' . $assessor . '_' . $mode . '_' . $lms;		$assessmentid = md5($str);				$serverurl_aux = FORM_ASSESS;		$serverurl = $serverurl_aux . '?ass='. $assessmentid .'&pla='. $toolid .'&type=open&mode=' . $perspective. '&tit='.urlencode($title).'&lang='.$language;		return $serverurl;	//echo $serverurl;		/*if(webservice_evalcomix_client::check_url($serverurl)){			return $serverurl;		}		else{			print_error('Evalcomix: invalid URL');		}*/	}			/**	* @param string $courseid	* @param string $module name	* @param string $activity ID	* @param string $student ID	* @param string $assessor ID	* @param string $mode [teacher | self | peer]	* @param string $lms name	* @return string URL of delete assessment	*/	public static function delete_ws_assessment($courseid = 0, $module = 0, $activity = 0, $student = 0, $assessor = 0, $mode = 'teacher', $lms = 0){		global $CFG;		defined('DELETE_ASSESS') || die('EvalCOMIX is not configured');								$serverurl_aux = DELETE_ASSESS;					$str = $courseid . '_' . $module . '_' . $activity . '_' . $student . '_' . $assessor . '_' . $mode . '_' . $lms;				$assessmentid = md5($str);				$serverurl = $serverurl_aux . '?ass='. $assessmentid;		//echo $serverurl;			//$response = download_file_content($serverurl);		include_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');				$curl = new Curly();		$response = $curl->get($serverurl);		if ($response && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){		 if($xml = simplexml_load_string($response)){			if((string)$xml->status == 'success' ){				return $serverurl;			}			else {				print_error('XML Document invalid');			}		  }		  else{			print_error('XML Document invalid');		  }		}		else{		  throw new Exception('SG: Bad Response');		}			}				/**	* @param string $courseid	* @param string $lms	* @return string XML document with course tools	*/	public static function get_ws_list_tool($courseid, $tool){		global $CFG;		defined('GET_TOOL_ASSESSED') || die('EvalCOMIX is not configured');						$serverurl_aux = GET_TOOL_ASSESSED;		$serverurl = $serverurl_aux . '?tool='. $tool . '&format=xml';		//echo $serverurl;				include_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');		$curl = new Curly();		$response = $curl->get($serverurl);		if ($response && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){					$result = null;			include_once($CFG->dirroot .'/blocks/'. blockname .'/classes/evalcomix_tool.php');			include_once($CFG->dirroot .'/blocks/'. blockname .'/classes/evalcomix.php');			$environment = evalcomix::fetch(array('courseid' => $courseid));			if(!$environment){				$environment = new evalcomix('', $courseid, 'evalcomix');				$environment->insert();			}							if($xml = simplexml_load_string($response)){				//foreach($xml as $tool){					//if(isset($tool->type)){					if(isset($xml['name'])){						$tool_type = dom_import_simplexml($xml)->tagName;						$type = '';						switch($tool_type){							case 'cl:ControlList': $type = 'list';break;							case 'es:EvaluationSet': $type = 'scale';break;							case 'ru:Rubric': $type = 'rubric';break;							case 'ce:ControlListEvaluationSet': $type = 'listscale';break;							case 'sd:SemanticDifferential': $type = 'differential';break;							case 'mt:MixTool': $type = 'mixed';break;							case 'ar:ArgumentSet': $type = 'argumentset';break;						}						$title = htmlspecialchars($xml['name'], ENT_QUOTES);						$result = new evalcomix_tool('', $environment->id, $title, $type, $tool);					}					else{						return false;					}				//}				return $result;			}			else{				webservice_evalcomix_client::print_error('XML Document invalid');			}		}		else{			webservice_evalcomix_client::print_error('GetTool: Invalid URL, EvalCOMIX is not configured correctly');		}	}		/**	* @param string $courseid	* @param string $module name	* @param string $activity ID	* @param string $student ID	* @param string $assessor ID	* @param string $mode [teacher | self | peer]	* @param string $lms name	* @return object evalcomix_assessment object with the grade associated to the params	*/	public static function get_ws_singlegrade($toolid = 0, $courseid = 0, $module = 0, $activity = 0, $student = 0, $assessor = 0, $mode = 'teacher', $lms = 0){		global $DB, $CFG;		defined('Grade_EvalCOMIX') || die('EvalCOMIX is not configured');				$task = $DB->get_record('block_evalcomix_tasks', array('instanceid'=>$activity), '*', MUST_EXIST);				$serverurl_aux = Grade_EvalCOMIX;					$str = $courseid . '_' . $module . '_' . $activity . '_' . $student . '_' . $assessor . '_' . $mode . '_' . $lms;		$assessmentid = md5($str);				$get = 'pla=' . $toolid . '&ass=' . $assessmentid;		$serverurl = $serverurl_aux . '?' . $get;		//echo $serverurl;		//$response = download_file_content($serverurl);		include_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');				$curl = new Curly();		$response = $curl->get($serverurl);		if ($response && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){		  $result = null;		  include_once($CFG->dirroot .'/blocks/'. blockname .'/classes/evalcomix_assessments.php');		  if($xml = simplexml_load_string($response)){			if (trim((string)$xml->finalAssessment) != '') {				$grade = (float)$xml->finalAssessment;			}			else {				$grade = -1;			}						//$maxgrade = $xml->maxgrade;			$result = new evalcomix_assessments('', $task->id, $assessor, $student, $grade);			return $result;		  }		  else{			//print_error('XML Document invalid');			webservice_evalcomix_client::print_error('Invalid Link');		  }		}		else{		  throw new Exception('SG: Bad Response');		}	}			public static function duplicate_course($assessments = array(), $tools = array()){				global $DB, $CFG;		defined('DUPLICATE_COURSE') || die('EvalCOMIX is not configured');		defined('DUPLICATE_COURSE2') || die('EvalCOMIX is not configured');						//include_once($CFG->dirroot .'/blocks/evalcomix/post_xml.php');		$serverurl = DUPLICATE_COURSE;				$xml = '<?xml version="1.0" encoding="utf-8"?>				<backup>				<toolsid>';						foreach($tools as $tool){			$xml .= '<toolid>					<oldid>'.$tool->oldid.'</oldid>					<newid>'.$tool->newid.'</newid>				</toolid>';		}						$xml .= '</toolsid>				<assessmentsid>';		foreach($assessments as $assessment){			$xml .= '<assessmentid>				<oldid>'.$assessment->oldid.'</oldid>				<newid>'.$assessment->newid.'</newid>			</assessmentid>';		}		$xml .= '</assessmentsid>				</backup>';		//echo $xml;		//$response = xml_post($xml, $serverurl, '');		//$result = simplexml_load_string($response);		include_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');				$curl = new Curly();		$response = $curl->post($serverurl, $xml);		if ($response && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){			$result = simplexml_load_string($response);			/*$serverurl2 = DUPLICATE_COURSE2;			$response2 = xml_post($xml, $serverurl2, '');			$result2 = simplexml_load_string($response2);*/		//	echo $serverurl2;			if(isset($result->status) && (string)$result->status != '#error'){			//if(isset($result2->status) && (string)$result2->status != '#error'){				return true;			//}			}			else{				return false;			}		}		else{			return false;		}	}	/**	* @param string $get parameters separated by '&'	* @param string $key for encryption	* @return string parameters encrypted	*/	//TODO implementar estrategia para cambiar algoritmo de encriptación según conveniencia	public static function encrypt_params($get, $key, $long = 0){		global $CFG;		require_once($CFG->dirroot . '/blocks/'.blockname.'/classes/5cr.php');		$variables = $get;		$encript = new E5CR($key);		$encript->encriptar($variables,1); //OJO uno(1) es para encriptar variables para 	URL               		$lash = '';		if($long == 1){			$lash = md5(microtime());		}		return $variables . $lash;  	}				/**	* @param string $url file url starting with http(s)://	* @return int if it is OK return 1 in other case, 0	*/	public static function check_url($url){		global $CFG;		include_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');				$curl = new Curly();		$response = $curl->get($url);				if ($response && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){			return 1;		}		else{			return 0;		}		/*$user_agent = $_SERVER['HTTP_USER_AGENT'];		$ch = curl_init();    // initialize curl handle		curl_setopt($ch, CURLOPT_URL, $url); // set url to post to		curl_setopt($ch, CURLOPT_HEADER, TRUE);		curl_setopt($ch, CURLOPT_FAILONERROR, 1);              // Fail on errors		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);    // allow redirects		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable		//curl_setopt($ch, CURLOPT_PORT, $port);                  //Set the port number		curl_setopt($ch, CURLOPT_TIMEOUT, 15); // times out after 15s		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);		$contenido = curl_exec($ch);		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);		curl_close($ch);echo $httpCode;		if($httpCode >= 400){			return 0;		}				return 1;		*/	}			public static function duplicate_tool($tool_old){		global $CFG;		defined('DUPLICATE_TOOL') || die('EvalCOMIX is not configured');								$serverurl_aux = DUPLICATE_TOOL;					$newid = webservice_evalcomix_client::generate_token();		$serverurl = $serverurl_aux . '?oldid='. $tool_old . '&newid='. $newid;		//echo $serverurl;			//$response = download_file_content($serverurl);		include_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');				$curl = new Curly();		$response = $curl->get($serverurl);		if ($response && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){		  if($xml = simplexml_load_string($response)){			if((string)$xml->status == 'success' ){				return (string)$xml->description;						}			else {				return 0;			}		  }		  else{			return 0;		  }		}		else{		  throw new Exception('SG: Bad Response');		}	}		public static function generate_token(){		return md5(uniqid());	}		/**	* @param string $params['courseid']	* @param array $params['module'] module names	* @param array $params['activity'] activity IDs	* @param array $params['student'] student IDs	* @param array $params['assessor'] assessor IDs	* @param array $params['mode'] [teacher | self | peer]	* @param string $params['lms'] lms name	 * @return xml document with data assessments	 */	public static function get_ws_xml_tools($params = array()){		defined('GET_TOOLS') || die('EvalCOMIX is not configured');		global $CFG;				$serverurl = GET_TOOLS . '?format=xml';				if(!isset($params['courseid']) || !isset($params['module']) || !isset($params['activity']) 			|| !isset($params['student']) || !isset($params['assessor']) || !isset($params['mode'])			|| !isset($params['lms'])){						throw new Exception('Missing Params');		}		$countModules = count($params['module']);		$countActivities = count($params['activity']);		$countStudents = count($params['student']);		$countAssessors = count($params['mode']);		if($countModules != $countActivities || $countStudents != $countAssessors){			throw new Exception('Wrong Params');		}				$xml = '<assessments>';		$courseid = $params['courseid'];		$lms = $params['lms'];		for($i = 0; $i < $countModules; ++$i){			$xml .= '<assessment>';			$module = $params['module'][$i];			$activity = $params['activity'][$i];			$student = $params['student'][$i];			$assessor = $params['assessor'][$i];			$mode = $params['mode'][$i];			$str = $courseid . '_' . $module . '_' . $activity . '_' . $student . '_' . $assessor . '_' . $mode . '_' . $lms;			$assessmentid = md5($str);			$xml .= $assessmentid;			$xml .= '</assessment>';		}		$xml .= '</assessments>';						include_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');				$curl = new Curly();		$response = $curl->post($serverurl, $xml);				if ($response && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){			$result = simplexml_load_string($response,'SimpleXMLElement',LIBXML_NSCLEAN);			return $result;		}		else{			throw new Exception('Page: Bad Response');		}	}		public static function verify($url){		defined('VERIFY') || die('EvalCOMIX is not configured');				$serverurl = $url . '/webservice/verify.php';		global $CFG;				include_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');				$curl = new Curly();		$response = $curl->get($serverurl);		if ($response && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){			$result = simplexml_load_string($response);			if((string)$result->status == 'Success' ){				return 1;			}			else {				return (string)$result->description;			}		}	}		public static function print_error($message = null){		$output = '<div style="text-align:center;color:#f00;padding:1em;border:1px solid #f00;margin:1em;font-weight:bold">'; 		if(isset($message)){			$output .= $message;				}		else{			$output .= 'EvalCOMIX Block is not configured correctly. Please, contact the system administrator';		}		$output .= '</div>';		echo $output;	}		/**	* @param string $params['courseid']	* @return xml document with data assessments	*/	public static function get_ws_xml_tools2($params = array()){		defined('GET_TOOLS2') || die('EvalCOMIX is not configured');		global $CFG;				$serverurl = GET_TOOLS2 . '?format=xml';				if(!isset($params['courseid'])){			throw new Exception('Missing Params');		}				include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tool.php');		include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix.php');				$xml = '<?xml version="1.0" encoding="utf-8"?>		<assessments>';		if($block = evalcomix::fetch(array('courseid' => $params['courseid']))){			if($tools = evalcomix_tool::fetch_all(array('evxid' => $block->id))){				foreach($tools as $tool){					$xml .= '<tool>';					$xml .= $tool->idtool;					$xml .= '</tool>';				}			}		}				$xml .= '</assessments>';						include_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');				$curl = new Curly();		$response = $curl->post($serverurl, $xml);				if ($response && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){			$result = simplexml_load_string($response,'SimpleXMLElement',LIBXML_NSCLEAN);			return $result;		}		else{			throw new Exception('Page: Bad Response');		}	}		/**	* @param string $params['toolxml']	* @return id of tool or false 	*/	public static function post_ws_xml_tools($params = array()){		defined('CREATE_TOOL') || die('EvalCOMIX is not configured');		global $CFG;				$id = webservice_evalcomix_client::generate_token();		$serverurl = CREATE_TOOL . '?id='.$id;		$xml = $params['toolxml'];		include_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');				$curl = new Curly();		$response = $curl->post($serverurl, $xml);		//echo htmlentities($response);		if ($response && $curl->getHttpCode()>=200 && $curl->getHttpCode()<400){			$result = simplexml_load_string($response,'SimpleXMLElement',LIBXML_NSCLEAN);			if(isset($result->status) && (string)$result->status == 'success' && isset($result->description)){				$xml_result = current($result->description);				//if($result->id && (string)$result->id != '#error'){				return (string)$xml_result['id'];			}			else{				return false;			}		}		else{			throw new Exception('Page: Bad Response');		}	}		/**	* @param array params['tools'] array of tool objects	* @return object with updated grades of assessments related with $params['tools']	*/	public static function get_assessments_modified($params){		global $CFG;		defined('GET_ASSESSMENT_MODIFIED') || die('EvalCOMIX is not configured');				if(!isset($params['tools']) || !is_array($params['tools'])){			return false;		}		$tools = $params['tools'];		$hashtools = array();				$xml = '<?xml version="1.0" encoding="utf-8"?>		<tools>';		foreach($tools as $tool){			$idtool = $tool->idtool;			$xml.= '<toolid>'.$idtool.'</toolid>';			$hashtools[$idtool] = $tool;		}		$xml.='</tools>';				$serverurl_aux = GET_ASSESSMENT_MODIFIED;		$serverurl = $serverurl_aux . '?&e=1';		//echo $serverurl;				include_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');		$curl = new Curly();		$response = $curl->post($serverurl, $xml);				if ($response && $curl->getHttpCode() >= 200 && $curl->getHttpCode() < 400){			$xml = simplexml_load_string($response);			if(isset($xml->status) && $xml->status == 'success'){				$xml_result = current($xml->description);				$result = array();				foreach($xml_result->assessment as $assessment){					$assid = (string)$assessment['id'];					$newgrade = (string)$assessment->grade;					$maxgrade = (string)$assessment->maxgrade;					$object = new stdClass();					$object->grade = $newgrade;					$object->maxgrade = $maxgrade;					$object->toolid = (string)$assessment->toolid;					$result[$assid] = $object;				}				return $result;			}			else{				return false;			}		}		else{			throw new Exception('Tool Modified: Bad Response');		}	}		/**	* It indicates to EvalCOMIX server that new grades of assessments has been modified correctly	* @param array params['toolids'] array of tool objects	* @return true if the indication was registered correctly	*/	public static function set_assessments_modified($params){		global $CFG;		defined('TOOL_MODIFIED') || die('EvalCOMIX is not configured');				if(!isset($params['toolids']) || !is_array($params['toolids'])){			return false;		}		$toolids = $params['toolids'];				$xml = '<?xml version="1.0" encoding="utf-8"?>		<toolids>';		foreach($toolids as $toolid){			$idtool = $toolid;			$xml.= '<toolid id="'.$idtool.'">false</toolid>';		}		$xml.='</toolids>';				$serverurl_aux = TOOL_MODIFIED;		$serverurl = $serverurl_aux . '?&e=2';		//echo $serverurl;				include_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');		$curl = new Curly();		$response = $curl->post($serverurl, $xml);				if ($response && $curl->getHttpCode() >= 200 && $curl->getHttpCode() < 400){			//echo $response;			$xml = simplexml_load_string($response);			if(isset($xml->status) && $xml->status == 'success'){				return true;			}		}		return false;	}}