<?php
global $CFG;
include_once($CFG->dirroot . '/blocks/evalcomix/reports/export_object.php');

/** 
 * @author Daniel Cabeza Sánchez
 * 
 */
class export_xls extends export_object{
	/**
	 * @return string filename
	 */
	public function export(){
		global $CFG;
		/** PHPExcel */
		include ($CFG->dirroot . '/blocks/evalcomix/classes/excel/PHPExcel.php');
		
		/** PHPExcel_Writer_Excel2007 */
		include ($CFG->dirroot . '/blocks/evalcomix/classes/excel/PHPExcel/Writer/Excel2007.php');
		
		// Create new PHPExcel object
		//echo date('H:i:s') . " Create new PHPExcel object\n";
		$objPHPExcel = new PHPExcel();
		
		// Set properties
		//echo date('H:i:s') . " Set properties\n";
		$objPHPExcel->getProperties()->setCreator("EvalCOMIX");
		$objPHPExcel->getProperties()->setLastModifiedBy("Moodle");
		$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
		$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
		$objPHPExcel->getProperties()->setDescription("Report for EvalCOMIX_MD.");
		
		
		$row = 1; // 1-based index
		foreach($this->header as $col => $value) {
			$objPHPExcel->getActiveSheet()->getStyle($row)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
		}
		
		$row = 2;
		foreach($this->names as $key => $left_value){
			$col = 0;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $left_value);
			$col = 1;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $this->surnames[$key]);
			$index = $row - 2;
			if(isset($this->right_rows[$index])){
				foreach($this->right_rows[$index] as $right_value){
					$col++;
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $right_value);
				}
			}
			$row++;
		}
		
		
		// Rename sheet
		$objPHPExcel->getActiveSheet()->setTitle('Report EvalCOMIX');
		
		
		// Save Excel 2007 file
		//echo date('H:i:s') . " Write to Excel2007 format\n";
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		//$dirname = dirname(__FILE__);
		//$filename = $dirname.'/tmp/report_evalcomix'.date('Ymd_H-i-s').'.xlsx';
		$filename = '/blocks/evalcomix/reports/tmp/report_evalcomix'.date('Ymd_H-i-s').'.xlsx';
		$objWriter->save($CFG->dirroot . $filename);
		
		return $filename;
	}

	public function print_continue(){
		global $OUTPUT, $CFG, $PAGE;
		
		$output = $PAGE->get_renderer('block_evalcomix');
		echo $output->logoheader();
		echo '<center><input type="button" style="color:#333333" value="'.get_string('assesssection', 'block_evalcomix').'" onclick="location.href=\''. $CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$this->courseid .'\'"></center><br>';
		
		echo $OUTPUT->heading(get_string('excelexport', 'block_evalcomix'));		
		
		echo $OUTPUT->container_start('gradeexportlink');
		
		$params['courseid'] = $this->courseid;
		$params['mode'] = $this->mode;
		$params['student_ids'] = $this->student_ids;
		$params['student_names'] = $this->student_names;
		$params['task'] = $this->task;
		$params['format'] = $this->format;
		if(isset($this->assessor_id)){
			$params['assessor_id'] = $this->assessor_id;
		}
		$params['download'] = '1';
		
		$filename = '/blocks/evalcomix/reports/download_preview.php?id='.$this->courseid.'&mode='.$this->mode;
		echo $OUTPUT->single_button(new moodle_url($CFG->wwwroot . $filename, $params), get_string('download', 'admin'));

		echo $OUTPUT->container_end();
	}
	
	function send_export($params){
		global $CFG;
		//$string = "process_data_".$this->mode;
		$string = 'process_data';
		$this->$string($params);
		
		try{
			$filename = $this->export();
			$filename = $CFG->dirroot.$filename;
		
			$filename_download = 'report_'.$this->mode.date('Ymd').'.xlsx';
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$filename_download");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
			readfile($filename);
			
			/*require_once($CFG->dirroot . '/lib/pear/Spreadsheet/Excel/Writer.php');
			$excel_obj = new Spreadsheet_Excel_Writer($filename);
			$excel_obj->send($filename);*/
		}
		catch(Exception $e){
			die("Exportation file can't be created");
		}
	}

}

