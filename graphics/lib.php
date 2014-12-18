<?php
	require_once('../../../../config.php');
	function get_datas_profile_attribute($idCurso, $id_tarea, $id_modality, $id_students){
		global $CFG;
		include_once($CFG->dirroot .'/blocks/evalcomix/configeval.php');
		include_once($CFG->dirroot .'/blocks/evalcomix/classes/webservice_evalcomix_client.php');
		include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tasks.php');
		include_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');
		$task = evalcomix_tasks::fetch(array('id' => $id_tarea));
		$module = evalcomix_tasks::get_type_task($task->instanceid);
		$modality = null;
		switch($id_modality){
			case 1: $modality = 'teacher';break;
			case 2: $modality = 'self';break;
			case 3: $modality = 'peer';break;
		}
		
		$xml = webservice_evalcomix_client::get_ws_tool_assessed($idCurso, $module, $task->instanceid, $id_students, $modality, MOODLE_NAME);
		$attributes_grade = evalcomix_tool::get_attributes_grade($xml);
		return $attributes_grade;
	}
?>