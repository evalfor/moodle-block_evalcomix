<?php

class export {
	public $object;
	
	function __construct($params){
		if(isset($params['format'])){
			$format = $params['format'];
			if(file_exists($format . '/export_'.$format . '.php')){
				include_once($format . '/export_'.$format . '.php');
				$class = 'export_'.$format;
				$this->object = new $class($params);
			}
			else{
				throw new Exception('Export: Bad format');
			}
		}
	}
	
	function preprocess_data($data){
		return $this->object->preprocess_data($data);
	}
	
	function print_continue(){
		return $this->object->print_continue();
	}
	
	function display_preview(){
		return $this->object->display_preview();
	}
}