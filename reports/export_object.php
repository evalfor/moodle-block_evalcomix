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

class export_object {

    public $validmodes = array('selftask', 'teachertask');

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
    public $validformats = array('xls');

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
     * @var array $rightrows
     */
    public $rightrows;

    /**
     *
     * @var string $studentids
     */
    public $studentids;

    /**
     *
     * @var string $studentnames
     */
    public $studentnames;

    /**
     *
     * @var string $task
     */
    public $task;

    /**
     *
     * @var string $assessorid
     */
    public $assessorid;


    /**
     * $params['courseid']
     * $params['mode'] indicates type of data to export
     * @param array $params
     */
    public function __construct($params) {
        if (isset($params['courseid'])) {
            $this->courseid = $params['courseid'];
        }
        if (isset($params['mode'])) {
            $this->mode = $params['mode'];
        }
        if (isset($params['task'])) {
            $this->task = $params['task'];
        }
        if (isset($params['student_ids'])) {
            $this->studentids = $params['student_ids'];
        }
        if (isset($params['assessor_id'])) {
            $this->assessorid = $params['assessor_id'];
        }
    }

    /**
     * Processes $data depending on $this->mode
     * @param array $data contains all $_POST variables clean
     * @return stdClass with header, first column and rows
     */
    public function preprocess_data($data) {
        if (!isset($this->mode)) {
            throw new Exception('Missing mode param');
        }

        if (!in_array($this->mode, $this->validmodes)) {
            throw new Exception('Wrong mode param');
        }

        if (isset($data['format'])) {
            if (in_array($data['format'], $this->validformats)) {
                $this->format = $data['format'];
            }
        } else {
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
    protected function process_data($data) {
        global $CFG;
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/webservice_evalcomix_client.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');

        if (isset($data['student_ids'])) {
            $useraux = explode(',', $data['student_ids']);
            $datanumusers = count($useraux);
        }

        if (isset($data['student_names'])) {
            $usernameaux = explode('-', $data['student_names']);
        }

        $assessorid = 0;
        if (isset($data['assessor_id'])) {
            $assessorid = $data['assessor_id'];
        }

        switch($this->mode) {
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
        $hashuser = array();
        $hashusername = array();
        $attributes[0] = '';

        $studentsid = '';
        $values = array();
        if (isset($data['task']) && is_numeric($datanumusers)) {
            foreach ($useraux as $key => $user) {
                $params['module'][$k] = evalcomix_tasks::get_type_task($data['task']);
                $params['activity'][$k] = $data['task'];
                $params['student'][$k] = $user;
                if ($assessorid != 0) {
                    $params['assessor'][$k] = $assessorid;
                } else {
                    $params['assessor'][$k] = $user;
                }
                $params['mode'][$k] = $modality;
                $str = $this->courseid . '_' . $params['module'][$k] . '_' . $params['activity'][$k] . '_' .
                $params['student'][$k] . '_' . $params['assessor'][$k] . '_' . $params['mode'][$k] . '_' . $params['lms'];
                $assessmentid = md5($str);
                $hashuser[$assessmentid] = $user;
                $hashusername[$assessmentid] = $usernameaux[$key];
                ++$k;
            }
            $this->task = substr($data['task'], 0, -1);

            if ($xml = webservice_evalcomix_client::get_ws_xml_tools($params)) {
                $row = 0;
                foreach ($xml as $assessment) {
                    $id = (string)$assessment['id'];
                    $userid = $hashuser[$id];
                    $name = explode(',', $hashusername[$id]);
                    $this->names[$row] = $name[0];
                    $this->surnames[$row] = $name[1];
                    foreach ($assessment as $value) {
                        $this->header[0] = 'Nombre';
                        $this->header[1] = 'Apellido(s)';
                        $l = 2;
                        if (isset($value->Dimension)) {
                            foreach ($value->Dimension as $dimension) {
                                foreach ($dimension->Subdimension as $subdimension) {
                                    foreach ($subdimension->Attribute as $attribute) {

                                        $this->header[$l] = (string)$attribute['name'];
                                        if (isset($attribute->selection->instance)) {
                                            $this->rightrows[$row][$l] = (string)$attribute->selection->instance;
                                        } else if (isset($attribute->selection)) {
                                            $this->rightrows[$row][$l] = (string)$attribute->selection;
                                        } else {
                                            $this->rightrows[$row][$l] = (string)$attribute;
                                        }

                                        if ((string)$attribute['comment'] != '') {
                                            ++$l;
                                            $this->header[$l] = 'O';
                                            if ((string)$attribute['comment'] != '1') {
                                                $this->rightrows[$row][$l] = (string)$attribute['comment'];
                                            } else {
                                                $this->rightrows[$row][$l] = '';
                                            }
                                        }
                                        ++$l;
                                    }
                                }
                            }
                        } else if (isset($value->Attribute)) {
                            foreach ($value->Attribute as $attribute) {
                                $this->header[$l] = (string)$attribute['nameN'].'/'.(string)$attribute['nameP'];
                                $this->rightrows[$row][$l] = (string)$attribute;
                                ++$l;
                            }
                        }
                    }
                    ++$row;
                }
            }exit;
        }
    }


    private function preprocess_data_selftask($data) {
        global $CFG;

        if (isset($data['student'])) {
            $datanumactivities = $data['na'];
        }
        if (isset($data['nu'])) {
            $datanumusers = $data['nu'];
        }

        $params['courseid'] = $this->courseid;
        $params['lms'] = MOODLE_NAME;
        $k = 0;
        $hashuser = array();
        $hashusername = array();
        $attributes[0] = '';

        $studentsid = '';
        $values = array();
        if (isset($data['task']) && isset($datanumusers) && is_numeric($datanumusers)) {
            for ($j = 0; $j < $datanumusers; ++$j) {
                if (isset($data['user_'.$j])) {
                    $this->studentnames .= $data['username_'.$j].','.$data['usersurname_'.$j].'-';
                    $this->studentids .= $data['user_'.$j] .',';
                }
            }
            $this->task = $data['task'];
            $this->studentids = substr($this->studentids, 0, -1);
            $this->studentnames = substr($this->studentnames, 0, -1);
        }
    }

    private function preprocess_data_teachertask($data) {
        global $CFG;

        if (isset($data['student'])) {
            $datanumactivities = $data['na'];
        }
        if (isset($data['nu'])) {
            $datanumusers = $data['nu'];
        }

        $params['courseid'] = $this->courseid;
        $params['lms'] = MOODLE_NAME;

        $k = 0;
        $hashuser = array();
        $hashusername = array();
        $attributes[0] = '';
        $studentsid = '';
        $values = array();
        if (isset($data['task']) && isset($datanumusers) && is_numeric($datanumusers)) {
            for ($j = 0; $j < $datanumusers; ++$j) {
                if (isset($data['user_'.$j])) {
                    $this->studentnames .= $data['username_'.$j].','.$data['usersurname_'.$j].'-';
                    $this->studentids .= $data['user_'.$j] .',';
                }
            }
            $this->task = $data['task'];
            $this->studentids = substr($this->studentids, 0, -1);
            $this->studentnames = substr($this->studentnames, 0, -1);
            if (isset($data['assessors'])) {
                $this->assessorid = $data['assessors'];
            }
        }
    }

    /**
     * Either prints a "Export" box, which will redirect the user to the download page,
     * or prints the URL for the published data.
     * @return void
     */
    public function print_continue() {
    }

    /**
     * Prints preview of exported grades on screen as a feedback mechanism
     */
    public function display_preview() {
    }
}