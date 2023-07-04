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
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/csvlib.class.php');
require_once($CFG->dirroot.'/'.$CFG->admin.'/tool/uploaduser/locallib.php');

/**
 * Display the preview of a CSV file
 *
 * @package     tool_uploaduser
 * @copyright   2020 Marina Glancy
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class preview extends \html_table {

    /** @var \csv_import_reader  */
    protected $cir;
    /** @var array */
    protected $filecolumns;
    /** @var int */
    protected $previewrows;
    /** @var bool */
    protected $noerror = true; // Keep status of any error.
    public $alldatas = array();
    /**
     * preview constructor.
     *
     * @param \csv_import_reader $cir
     * @param array $filecolumns
     * @param int $previewrows
     * @throws \coding_exception
     */
    public function __construct(\csv_import_reader $cir, array $filecolumns, int $previewrows) {
        parent::__construct();
        $this->cir = $cir;
        $this->filecolumns = $filecolumns;
        $this->previewrows = $previewrows;

        $this->id = "uupreview";
        $this->attributes['class'] = 'generaltable';
        $this->tablealign = 'center';
        $this->summary = get_string('uploaduserspreview', 'tool_uploaduser');
        $this->head = array();
        $this->data = $this->read_data();
        $this->alldatas = $this->read_data(false);

        $this->head[] = get_string('uucsvline', 'tool_uploaduser');
        foreach ($filecolumns as $column) {
            $this->head[] = $column;
        }
        $this->head[] = get_string('status');
    }

    /**
     * Read data
     *
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    protected function read_data($previewrows = true) {
        global $DB, $CFG, $COURSE;

        $data = array();
        $this->cir->init();
        $linenum = 1; // Column header is first line.
        while ($fields = $this->cir->next()) {
            if ($previewrows && $linenum > $this->previewrows) {
                continue;
            }
            $linenum++;
            $rowcols = array();
            $rowcols['line'] = $linenum;
            foreach ($fields as $key => $field) {
                $rowcols[$this->filecolumns[$key]] = s(trim($field));
            }
            $rowcols['status'] = array();

            $outcome = null;
            if (isset($rowcols['outcome'])) {
                if (!is_numeric($rowcols['outcome']) || ((int)$rowcols['outcome'] !== 0 && (int)$rowcols['outcome'] !== 1)) {
                    $rowcols['status'][] = '<span class="text-danger">'.get_string('invalidoutcome', 'block_evalcomix').'</span>';
                } else {
                    $outcome = $rowcols['outcome'];
                }
            } else {
                $rowcols['status'][] = '<span class="text-danger">'.get_string('missingoutcome', 'block_evalcomix').'</span>';
            }

            if (!empty($rowcols['idnumber'])) {
                if (mb_strlen($rowcols['idnumber']) > 100) {
                    $rowcols['status'][] = '<span class="text-danger">'.
                    get_string('invalididnumberupload', 'block_evalcomix').'</span>';
                }

                if (isset($outcome) && $item = $DB->get_record('block_evalcomix_competencies',
                        array('idnumber' => $rowcols['idnumber'], 'outcome' => $outcome, 'courseid' => $COURSE->id))) {
                    $rowcols['status'][] = '<span class="text-danger">'.
                    get_string('idnumberduplicate', 'block_evalcomix').'</span>';
                }
            } else {
                $rowcols['status'][] = '<span class="text-danger">'.get_string('missingidnumber', 'block_evalcomix').'</span>';
            }

            if (empty($rowcols['shortname'])) {
                $rowcols['status'][] = '<span class="text-danger">'.get_string('missingshortname', 'block_evalcomix').'</span>';
            }
            $rowcols['status'] = implode('<br />', $rowcols['status']);
            $data[] = $rowcols;
        }
        if ($fields = $this->cir->next()) {
            $data[] = array_fill(0, count($fields) + 2, '...');
        }
        $this->cir->close();

        return $data;
    }

    /**
     * Getter for noerror
     *
     * @return bool
     */
    public function get_no_error() {
        return $this->noerror;
    }
}
