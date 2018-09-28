<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/blocks/evalcomix/reports/export_object.php');

/**
 * @author Daniel Cabeza Sánchez
 */
class export_xls extends export_object{
    /**
     * @return string filename
     */
    public function export() {
        global $CFG;
        // PHPExcel.
        require($CFG->dirroot . '/blocks/evalcomix/classes/excel/PHPExcel.php');

        // PHPExcel_Writer_Excel2007.
        require($CFG->dirroot . '/blocks/evalcomix/classes/excel/PHPExcel/Writer/Excel2007.php');

        // Create new PHPExcel object.
        $objphpexcel = new PHPExcel();

        // Set properties.
        $objphpexcel->getProperties()->setCreator("EvalCOMIX");
        $objphpexcel->getProperties()->setLastModifiedBy("Moodle");
        $objphpexcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
        $objphpexcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
        $objphpexcel->getProperties()->setDescription("Report for EvalCOMIX_MD.");

        $row = 1; // 1-based index.
        foreach ($this->header as $col => $value) {
            $objphpexcel->getActiveSheet()->getStyle($row)->getFont()->setBold(true);
            $objphpexcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
        }

        $row = 2;
        foreach ($this->names as $key => $leftvalue) {
            $col = 0;
            $objphpexcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $leftvalue);
            $col = 1;
            $objphpexcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $this->surnames[$key]);
            $index = $row - 2;
            if (isset($this->rightrows[$index])) {
                foreach ($this->rightrows[$index] as $rightvalue) {
                    $col++;
                    $objphpexcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $rightvalue);
                }
            }
            $row++;
        }

        // Rename sheet.
        $objphpexcel->getActiveSheet()->setTitle('Report EvalCOMIX');

        // Save Excel 2007 file.
        $objwriter = new PHPExcel_Writer_Excel2007($objphpexcel);
        $filename = '/blocks/evalcomix/reports/tmp/report_evalcomix'.date('Ymd_H-i-s').'.xlsx';
        $objwriter->save($CFG->dirroot . $filename);

        return $filename;
    }

    public function print_continue() {
        global $OUTPUT, $CFG, $PAGE;

        $output = $PAGE->get_renderer('block_evalcomix');
        echo $output->logoheader();
        echo '<center><input type="button" style="color:#333333" value="'.
        get_string('assesssection', 'block_evalcomix').'" onclick="location.href=\''.
        $CFG->wwwroot .'/blocks/evalcomix/assessment/index.php?id='.$this->courseid .'\'"></center><br>';

        echo $OUTPUT->heading(get_string('excelexport', 'block_evalcomix'));

        echo $OUTPUT->container_start('gradeexportlink');

        $params['courseid'] = $this->courseid;
        $params['mode'] = $this->mode;
        $params['student_ids'] = $this->student_ids;
        $params['student_names'] = $this->student_names;
        $params['task'] = $this->task;
        $params['format'] = $this->format;
        if (isset($this->assessor_id)) {
            $params['assessor_id'] = $this->assessor_id;
        }
        $params['download'] = '1';

        $filename = '/blocks/evalcomix/reports/download_preview.php?id='.$this->courseid.'&mode='.$this->mode;
        echo $OUTPUT->single_button(new moodle_url($CFG->wwwroot . $filename, $params), get_string('download', 'admin'));

        echo $OUTPUT->container_end();
    }

    public function send_export($params) {
        global $CFG;
        $string = 'process_data';
        $this->$string($params);

        try {
            $filename = $this->export();
            $filename = $CFG->dirroot.$filename;

            $filenamedownload = 'report_'.$this->mode.date('Ymd').'.xlsx';
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$filenamedownload");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
            header("Pragma: public");
            readfile($filename);
        } catch (Exception $e) {
            die("Exportation file can't be created");
        }
    }
}

