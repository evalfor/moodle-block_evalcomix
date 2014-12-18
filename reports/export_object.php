<?php

class export_object {
	
	public $valid_modes = array('selftask', 'teachertask');
	
	/**
	 * @var Int $courseid;
	 */
	public $courseid;
	
	/**
	 * Can be ['selftask' | 'teachertask']
	 * @var string $mode 
	 */
	public $mode;

	/**
	 * 
	 * @var string $format can be ['xls'] 
	 */
	public $format;
	
	/**
	 * Exportation valid formats
	 * @var array $valid_format
	 */
	public $valid_formats = array('xls');
	
	/**
	 * Array with values of report header
	 * @var array $header
	 */
	public $header;
	
	/**
	 * Array with values of first column of report
	 * @var array $names
	 */
	public $names;
	
	/**
	 * Array with values of second column of report
	 * @var array $surnames
	 */
	public $surnames;
	
	/**
	 * Array with values of the report
	 * @var array $right_rows
	 */
	public $right_rows;
	
	/**
	 * 
	 * @var string $student_ids
	 */
	public $student_ids;
	
	/**
	 * 
	 * @var string $student_names
	 */
	public $student_names;
	
	/**
	 * 
	 * @var string $task
	 */
	public $task;
	
	/**
	 * 
	 * @var string $assessorid
	 */
	public $assessor_id;
	
	
	/**
	 * $params['courseid']
	 * $params['mode'] indicates type of data to export
	 * @param array $params
	 */
	public function __construct($params){
		if(isset($params['courseid'])){
			$this->courseid = $params['courseid'];
		}
		if(isset($params['mode'])){
			$this->mode = $params['mode'];
		}
		if(isset($params['task'])){
			$this->task = $params['task'];
		}
		if(isset($params['student_ids'])){
			$this->student_ids = $params['student_ids'];
		}
		if(isset($params['assessor_id'])){
			$this->assessor_id = $params['assessor_id'];
		}
	}
	
	/**
	 * Processes $data depending on $this->mode 
	 * @param array $data contains all $_POST variables clean
	 * @return stdClass with header, first column and rows 
	 */
	public function preprocess_data($data){
		if(!isset($this->mode)){
			throw new Exception('Missing mode param');
		}

		if(!in_array($this->mode, $this->valid_modes)){
			throw new Exception('Wrong mode param');
		}
		
		if(isset($data['format'])){
			if (in_array($data['format'], $this->valid_formats)){
				$this->format = $data['format'];
			}
		}
		else{
			throw new Exception('Reports: Invalid format');
		}

		$function = 'preprocess_data_'.$this->mode;
		return $this->$function($data);
	}
	
	/**
	 * Processes $data por 'selftask' report. This report shows each value assigned to 
	 * attributes of selfassessmets of a task
	 *   
	 * @param array $data
	 * @return void
	 */
	//protected function process_data_selftask($data){
	protected function process_data($data){
		global $CFG;
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
		include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
		
		if(isset($data['student_ids'])){
			$user_aux = explode(',', $data['student_ids']);
			$data_num_users = count($user_aux);
		}
		
		if(isset($data['student_names'])){
			$username_aux = explode('-', $data['student_names']);
		}

		$assessorid = 0;
		if(isset($data['assessor_id'])){
			$assessorid = $data['assessor_id'];
		}
		
		switch($this->mode){
			case 'selftask':{
				$modality = 'self';
			}break;
			case 'teachertask':{
				$modality = 'teacher';
			}break;
		}	
		
		$params['courseid'] = $this->courseid;
		$params['lms'] = MOODLE_NAME;
		$k = 0;
		$hash_user = array();
		$hash_username = array();
		$attributes[0] = '';
		//$students = array();
		$studentsid = '';
		$values = array();		
		if(isset($data['task']) && is_numeric($data_num_users)){
			foreach($user_aux  as $key => $user){
				$params['module'][$k] = evalcomix_tasks::get_type_task($data['task']);
				$params['activity'][$k] = $data['task'];
				$params['student'][$k] = $user;
				if($assessorid != 0){
					$params['assessor'][$k] = $assessorid;
				}
				else{
					$params['assessor'][$k] = $user;
				}
				$params['mode'][$k] = $modality;
				$str = $this->courseid . '_' . $params['module'][$k] . '_' . $params['activity'][$k] . '_' . $params['student'][$k] . '_' . $params['assessor'][$k] . '_' . $params['mode'][$k] . '_' . $params['lms'];
				$assessmentid = md5($str);
				$hash_user[$assessmentid] = $user;
				$hash_username[$assessmentid] = $username_aux[$key];
				++$k;	
			}
			$this->task = substr($data['task'], 0, -1);
			
			//TODO:adaptar para rúbricas, argumentarios y mixtas
			if($xml = webservice_evalcomix_client::get_ws_xml_tools($params)){
				$row = 0;
				foreach($xml as $assessment){
					$id = (string)$assessment['id'];
					$userid = $hash_user[$id];
					$name = explode(',', $hash_username[$id]);
					$this->names[$row] = $name[0];
					$this->surnames[$row] = $name[1];
					foreach($assessment as $value){
						//echo (string)$value->GlobalAssessment->Attribute;
						$this->header[0] = 'Nombre';
						$this->header[1] = 'Apellido(s)';
						$l = 2;
						if(isset($value->Dimension)){
							foreach($value->Dimension as $dimension){
								foreach($dimension->Subdimension as $subdimension){
									foreach($subdimension->Attribute as $attribute){
									//echo (string)$attribute['name'];
										$this->header[$l] = (string)$attribute['name'];
										if(isset($attribute->selection->instance)){print_r($attribute->selection->instance);
											$this->right_rows[$row][$l] = (string)$attribute->selection->instance;
										}
										elseif(isset($attribute->selection)){
											$this->right_rows[$row][$l] = (string)$attribute->selection;
										}
										else{
											$this->right_rows[$row][$l] = (string)$attribute;
										}
										
										if((string)$attribute['comment'] != ''){
											++$l;
											$this->header[$l] = 'O';
											if((string)$attribute['comment'] != '1'){
												$this->right_rows[$row][$l] = (string)$attribute['comment'];
											}
											else{
												$this->right_rows[$row][$l] = '';
											}
										}
									++$l;
									}
								}
							}
						}
						elseif(isset($value->Attribute)){
							foreach($value->Attribute as $attribute){
								$this->header[$l] = (string)$attribute['nameN'].'/'.(string)$attribute['nameP'];
								$this->right_rows[$row][$l] = (string)$attribute;
								++$l;
							}
						}
					}
					++$row;
				}
			}exit;
		}
	}
	
	
	private function preprocess_data_selftask($data){
		global $CFG;
		//include_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
		//include_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
		
		
		if(isset($data['student'])){
			$data_num_activities = $data['na'];
		}
		if(isset($data['nu'])){
			$data_num_users = $data['nu'];
		}
			
		$params['courseid'] = $this->courseid;
		$params['lms'] = MOODLE_NAME;
		$k = 0;
		$hash_user = array();
		$hash_username = array();
		$attributes[0] = '';
		//$students = array();
		$studentsid = '';
		$values = array();
		if(isset($data['task']) && isset($data_num_users) && is_numeric($data_num_users)){
			for($j = 0; $j < $data_num_users; ++$j){
				if(isset($data['user_'.$j])){
				/*	$params['module'][$k] = evalcomix_tasks::get_type_task($data['task']);
					$params['activity'][$k] = $data['task'];
					$params['student'][$k] = $data['user_'.$j];
					$params['assessor'][$k] = $data['user_'.$j];
					$params['mode'][$k] = 'self';
					$str = $this->courseid . '_' . $params['module'][$k] . '_' . $params['activity'][$k] . '_' . $params['student'][$k] . '_' . $params['student'][$k] . '_' . $params['mode'][$k] . '_' . $params['lms'];
					$assessmentid = md5($str);
					$hash_user[$assessmentid] = $data['user_'.$j];
					$hash_username[$assessmentid] = $data['username_'.$j];
					++$k;
				*/
					$this->student_names .= $data['username_'.$j].','.$data['usersurname_'.$j].'-';
					$this->student_ids .= $data['user_'.$j] .','; 
				}
			}
			$this->task = $data['task'];
			$this->student_ids = substr($this->student_ids, 0, -1);
			$this->student_names = substr($this->student_names, 0, -1);
		
			/*if($xml = webservice_evalcomix_client::get_ws_xml_tools($params)){
				$row = 0;
				foreach($xml as $assessment){
					$id = (string)$assessment['id'];
					$userid = $hash_user[$id];
					$name = $hash_username[$id];
					$this->names[$row] = $name;
					foreach($assessment as $value){
						//echo (string)$value->GlobalAssessment->Attribute;
						$l = 1;
						foreach($value->Dimension as $dimension){
							foreach($dimension->Subdimension as $subdimension){
								foreach($subdimension->Attribute as $attribute){
									//echo (string)$attribute['name'];
									$this->header[$l] = (string)$attribute['name'];
									if(isset($attribute->selection)){
										$this->right_rows[$row][$l] = (string)$attribute->selection;
									}
									++$l;
								}
							}
						}
					}
					++$row;
				}
			}*/
		}
	}
	
	private function preprocess_data_teachertask($data){
		global $CFG;		
		
		if(isset($data['student'])){
			$data_num_activities = $data['na'];
		}
		if(isset($data['nu'])){
			$data_num_users = $data['nu'];
		}
			
		$params['courseid'] = $this->courseid;
		$params['lms'] = MOODLE_NAME;
		
		$k = 0;
		$hash_user = array();
		$hash_username = array();
		$attributes[0] = '';
		//$students = array();
		$studentsid = '';
		$values = array();
		if(isset($data['task']) && isset($data_num_users) && is_numeric($data_num_users)){
			for($j = 0; $j < $data_num_users; ++$j){
				if(isset($data['user_'.$j])){
					$this->student_names .= $data['username_'.$j].','.$data['usersurname_'.$j].'-';
					$this->student_ids .= $data['user_'.$j] .','; 
				}
			}
			$this->task = $data['task'];
			$this->student_ids = substr($this->student_ids, 0, -1);
			$this->student_names = substr($this->student_names, 0, -1);
			if(isset($data['assessors'])){
				$this->assessor_id = $data['assessors'];
			}
		}
	}
	
	/**
	 * Either prints a "Export" box, which will redirect the user to the download page,
	 * or prints the URL for the published data.
	 * @return void
	 */
	function print_continue(){}
	
	/**
	 * Prints preview of exported grades on screen as a feedback mechanism
	 */	
	function display_preview(){
					
	}
	
}