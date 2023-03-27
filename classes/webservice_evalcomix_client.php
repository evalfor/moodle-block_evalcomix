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
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->dirroot . '/webservice/rest/lib.php');
require_once($CFG->dirroot . '/blocks/evalcomix/locallib.php');
require_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
require_once($CFG->dirroot . '/lib/filelib.php');

// API for EvalCOMIX webservices.
class block_evalcomix_webservice_client {

    /**
     * @param string $lms name of instance of Moodle
     * @param $courseid
     * @param $language as 'es_es_utf8'
     * @param $extra parameters to add to URI
     */
    public static function get_ws_createtool($id = null, $lms = '', $courseid = 0, $language = 'es_es_utf8', $type = 'new') {
        defined('BLOCK_EVALCOMIX_CLIENT_NEW') || print_error('EvalCOMIX is not configured');
        defined('BLOCK_EVALCOMIX_CLIENT_EDIT') || print_error('EvalCOMIX is not configured');
        global $CFG, $DB;
        $token = self::get_token();
        $serverurl = BLOCK_EVALCOMIX_CLIENT_NEW;
        if (!$id && $type == 'new') {
            $id = self::generate_token();
        } else if ($id) {
            $serverurl = BLOCK_EVALCOMIX_CLIENT_EDIT;
        }
        $serverurl = str_replace(':toolid', $id, $serverurl);
        $get = '&lang='. $language . '&courseid='.$courseid;

        $serverurl .= $get;

        $environmentid = 0;
        if (!$environment = $DB->get_record('block_evalcomix', array('courseid' => $courseid))) {
            $environmentid = $DB->insert_record('block_evalcomix', array('courseid' => $courseid, 'viewmode' => 'evalcomix',
                'sendgradebook' => '0'));
        } else {
            $environmentid = $environment->id;
        }
        if (!empty($environmentid) && !$DB->get_record('block_evalcomix_tools', array('evxid' => $environmentid,
            'idtool' => $id))) {
            $now = time();
            $DB->insert_record('block_evalcomix_tools', array('evxid' => $environmentid, 'title' => '-000_1', 'type' => 'tmp',
                'idtool' => $id, 'timecreated' => $now, 'timemodified' => $now));
        }

        return $serverurl;
    }

    /**
     * @param $toolid Assessment tool ID
     * @return string URL validated to view tool
     */
    public static function get_ws_viewtool($toolid = 0, $assessment = null, $language = 'es_utf8', $title = '') {

        defined('BLOCK_EVALCOMIX_DISPLAY_TOOL') || die('EvalCOMIX is not configured properly');
        $token = self::get_token();
        $serverurl = BLOCK_EVALCOMIX_DISPLAY_TOOL . $toolid;
        if (!empty($assessment)) {
            defined('BLOCK_EVALCOMIX_DISPLAY_TOOL_ASSESSED') || die('EvalCOMIX is not configured properly');
            $assessmentid = block_evalcomix_update_assessmentid($assessment);
            $serverurl = BLOCK_EVALCOMIX_DISPLAY_TOOL_ASSESSED . $assessmentid;
        }

        $serverurl = $serverurl . '?title='.urlencode($title). '&lang=' . $language . '&token='.$token;

        if (self::check_url($serverurl)) {
            return $serverurl;
        } else {
            print_error('Evalcomix: invalid URL');
        }
    }

    /**
     * @param string $toolid
     * @return mixed false if request failed or content of the file as string if ok.
     *       True if file downloaded into $tofile successfully.
     */
    public static function get_ws_deletetool($toolid) {
        global $CFG;
        defined('BLOCK_EVALCOMIX_DELETE') || die('EvalCOMIX is not configured');
        $token = self::get_token();
        $serverurl = BLOCK_EVALCOMIX_DELETE;
        $serverurl .= $toolid . '?token='.$token;

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new block_evalcomix_curl();
        $response = $curl->delete($serverurl);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            return $response;
        } else {
            throw new Exception('SG: Bad Response');
        }
    }

    /**
     * @param string $toolid
     * @param string $courseid
     * @param string $module name
     * @param string $activity ID
     * @param string $student ID
     * @param string $assessor ID
     * @param string $mode [teacher | self | peer]
     * @param string $lms name
     * @return string URL of assessment form
     */
    public static function get_ws_assessment_form($toolid = 0, $assessment = null, $language = 'es_utf8', $title = '',
            $params = array()) {

        defined('BLOCK_EVALCOMIX_FORM_ASSESS') || die('EvalCOMIX is not configured properly');

        $assessmentid = '';
        if (!empty($assessment)) {
            $assessmentid = block_evalcomix_update_assessmentid($assessment);
        } else if (isset($params['courseid'], $params['module'], $params['cmid'], $params['studentid'],
                $params['assessorid'], $params['mode'])) {
            $assessmentid = block_evalcomix_get_assessmentid($params);
        } else {
            print_error('EvalCOMIX: invalid assessment');
        }

        $token = self::get_token();
        $serverurl = BLOCK_EVALCOMIX_FORM_ASSESS;
        $serverurl = str_replace(':assessmentid', $assessmentid, $serverurl);
        $serverurl = str_replace(':toolid', $toolid, $serverurl);
        $serverurl .= '?title='.urlencode($title).'&lang='.$language.'&token='.$token;

        if (self::check_url($serverurl)) {
            return $serverurl;
        } else {
            print_error('Evalcomix: invalid URL');
        }
    }

    /**
     * @param string $courseid
     * @param string $module name
     * @param string $activity ID
     * @param string $student ID
     * @param string $assessor ID
     * @param string $mode [teacher | self | peer]
     * @param string $lms name
     * @return string URL of delete assessment
     */
    public static function delete_ws_assessment($assessment = null, $courseid = 0, $module = 0, $activity = 0,
        $student = 0, $assessor = 0, $mode = 'teacher', $lms = 0) {

        global $CFG;
        defined('BLOCK_EVALCOMIX_DELETE_ASSESS') || die('EvalCOMIX is not configured');

        $assessmentid = block_evalcomix_update_assessmentid($assessment);

        $serverurl = BLOCK_EVALCOMIX_DELETE_ASSESS;
        $serverurl .= $assessmentid;

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new block_evalcomix_curl();
        $response = $curl->delete($serverurl);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            if ($xml = simplexml_load_string($response)) {
                if ((string)$xml->status == 'Success' ) {
                    return $serverurl;
                } else {
                    print_error('XML Document invalid');
                }
            } else {
                print_error('XML Document invalid');
            }
        } else {
            echo $assessementid . ': ';
            echo $str;
            throw new Exception('SG: Bad Response');
        }
    }


    /**
     * @param string $courseid
     * @param string $lms
     * @return string XML document with course tools
     */
    public static function get_ws_list_tool($courseid, $tool) {
        global $CFG, $DB;
        defined('BLOCK_EVALCOMIX_GET_TOOL') || die('EvalCOMIX is not configured');
        $token = self::get_token();
        $serverurlaux = BLOCK_EVALCOMIX_GET_TOOL;
        $serverurl = $serverurlaux . $tool . '?format=xml&token='.$token;

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');
        $curl = new block_evalcomix_curl();
        $response = $curl->get($serverurl);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            $result = null;
            require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');
            $environmentid = 0;
            if (!$environment = $DB->get_record('block_evalcomix', array('courseid' => $courseid))) {
                $environmentid = $DB->insert_record('block_evalcomix', array('courseid' => $courseid, 'viewmode' => 'evalcomix',
                    'sendgradebook' => '0'));
            } else {
                $environmentid = $environment->id;
            }

            if ($xml = simplexml_load_string($response)) {

                if (isset($xml['name'])) {
                    $tooltype = dom_import_simplexml($xml)->tagName;
                    $type = '';
                    switch($tooltype) {
                        case 'cl:ControlList': $type = 'list';
                        break;
                        case 'es:EvaluationSet': $type = 'scale';
                        break;
                        case 'ru:Rubric': $type = 'rubric';
                        break;
                        case 'ce:ControlListEvaluationSet': $type = 'listscale';
                        break;
                        case 'sd:SemanticDifferential': $type = 'differential';
                        break;
                        case 'mt:MixTool': $type = 'mixed';
                        break;
                        case 'ar:ArgumentSet': $type = 'argumentset';
                        break;
                    }
                    $title = htmlspecialchars($xml['name'], ENT_QUOTES);
                    $result = new block_evalcomix_tool('', $environmentid, $title, $type, $tool);
                } else {
                    return false;
                }

                return $result;
            } else {
                self::print_error('XML Document invalid');
            }
        } else {
            return false;
        }
    }

    /**
     * @param string $courseid
     * @param string $module name
     * @param string $activity ID
     * @param string $student ID
     * @param string $assessor ID
     * @param string $mode [teacher | self | peer]
     * @param string $lms name
     * @return object evalcomix_assessment object with the grade associated to the params
     */
    public static function get_ws_singlegrade($assessment = null, $params = array()) {

        global $DB, $CFG;
        defined('BLOCK_EVALCOMIX_GRADE_EVALCOMIX') || die('EvalCOMIX is not configured');

        $taskid = null;
        $assessor = null;
        $student = null;
        $assessmentid = '';
        if (!empty($assessment)) {
            $assessmentid = block_evalcomix_update_assessmentid($assessment);
            $taskid = $assessment->taskid;
            $assessor = $assessment->assessorid;
            $student = $assessment->studentid;
        } else if (isset($params['courseid'], $params['module'], $params['cmid'], $params['studentid'],
                $params['assessorid'], $params['mode'])) {
            $assessmentid = block_evalcomix_get_assessmentid($params);
            $task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $params['cmid']), '*', MUST_EXIST);
            $taskid = $task->id;
            $assessor = $params['assessorid'];
            $student = $params['studentid'];
        } else {
            var_dump($params);
            print_error('EvalCOMIX: invalid assessment');
        }
        $token = self::get_token();
        $serverurl = BLOCK_EVALCOMIX_GRADE_EVALCOMIX;
        $serverurl .= $assessmentid . '?token='.$token;

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new block_evalcomix_curl();
        $response = $curl->get($serverurl);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            $result = null;
            require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_assessments.php');

            if ($xml = simplexml_load_string($response)) {
                if (trim((string)$xml->finalAssessment) != '') {
                    $grade = (float)$xml->finalAssessment;
                } else {
                    $grade = -1;
                }

                $result = new block_evalcomix_assessments('', $taskid, $assessor, $student, $grade);
                return $result;
            } else {
                self::print_error('Invalid Link');
            }
        } else {
            throw new Exception('SG: Bad Response');
        }
    }

    public static function duplicate_course($assessments = array(), $tools = array()) {
        global $DB, $CFG;
        defined('BLOCK_EVALCOMIX_DUPLICATE_COURSE') || die('EvalCOMIX is not configured');

        $token = self::get_token();
        $serverurl = BLOCK_EVALCOMIX_DUPLICATE_COURSE . '?token='.$token;
        $xml = "<?xml version='1.0' encoding='utf-8'?>
<assessmentTools xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'
xsi:schemaLocation='https://circe.uca.es/evalcomixserver430/xsd/DuplicateAssessment.xsd'>
<toolIdentifiers>";

        foreach ($tools as $tool) {
            $xml .= '<toolIdentifier>
    <oldid>'.$tool->oldid.'</oldid>
    <newid>'.$tool->newid.'</newid>
</toolIdentifier>';
        }

        $xml .= '</toolIdentifiers>
<assessmentIdentifiers>';
        foreach ($assessments as $assessment) {
            $xml .= '<assessmentIdentifier>
        <oldid>'.$assessment->oldid.'</oldid>
        <newid>'.$assessment->newid.'</newid>
    </assessmentIdentifier>';
        }
        $xml .= '</assessmentIdentifiers>
</assessmentTools>';

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new block_evalcomix_curl();
        $response = $curl->post($serverurl, $xml);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            $result = simplexml_load_string($response);

            if (isset($result->status) && (string)$result->status != 'error') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * @param string $get parameters separated by '&'
     * @param string $key for encryption
     * @return string parameters encrypted
     */
    public static function encrypt_params($get, $key, $long = 0) {
        global $CFG;
        require_once($CFG->dirroot . '/blocks/'.blockname.'/classes/5cr.php');
        $variables = $get;
        $encript = new block_evalcomix_E5CR($key);
        $encript->encriptar($variables, 1); // OJO uno(1) es para encriptar variables para URL.
        $lash = '';
        if ($long == 1) {
            $lash = md5(microtime());
        }
        return $variables . $lash;
    }

    /**
     * @param string $url file url starting with http(s)://
     * @return int if it is OK return 1 in other case, 0
     */
    public static function check_url($url) {
        global $CFG;
        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new block_evalcomix_curl();
        $response = $curl->get($url);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function duplicate_tool($toolold) {
        global $CFG;
        defined('BLOCK_EVALCOMIX_DUPLICATE_TOOL') || die('EvalCOMIX is not configured');

        $newid = self::generate_token();
        $token = $token = self::get_token();
        $serverurl = BLOCK_EVALCOMIX_DUPLICATE_TOOL;
        $serverurl = str_replace(':currenttool', $toolold, $serverurl);
        $serverurl = str_replace(':newtool', $newid, $serverurl);
        $serverurl .= '?token='. $token;

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new block_evalcomix_curl();
        $response = $curl->post($serverurl, '');

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            if ($xml = simplexml_load_string($response)) {
                if ((string)$xml->status == 'Success' ) {
                    return $newid;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } else {
            throw new Exception('SG: Bad Response');
        }
    }

    public static function generate_token() {
        return md5(uniqid());
    }

    /**
     * @param string $params['courseid']
     * @param array $params['module'] module names
     * @param array $params['activity'] activity IDs
     * @param array $params['student'] student IDs
     * @param array $params['assessor'] assessor IDs
     * @param array $params['mode'] [teacher | self | peer]
     * @param string $params['lms'] lms name
     * @return xml document with data assessments
     */
    public static function get_ws_xml_tools($params = array()) {
        defined('BLOCK_EVALCOMIX_GET_TOOLS') || die('EvalCOMIX is not configured');
        global $CFG;

        $token = $token = self::get_token();
        $serverurl = BLOCK_EVALCOMIX_GET_TOOLS . '?token='.$token;

        if (!isset($params['courseid']) || !isset($params['module']) || !isset($params['activity'])
            || !isset($params['student']) || !isset($params['assessor']) || !isset($params['mode'])
            || !isset($params['lms'])) {

            throw new Exception('Missing Params');
        }
        $countmodules = count($params['module']);
        $countactivities = count($params['activity']);
        $countstudents = count($params['student']);
        $countassessors = count($params['mode']);
        if ($countmodules != $countactivities || $countstudents != $countassessors) {
            throw new Exception('Wrong Params');
        }

        $xml = '<assessments>';
        $courseid = $params['courseid'];
        $lms = $params['lms'];
        for ($i = 0; $i < $countmodules; ++$i) {
            $xml .= '<assessmentid>';
            $module = $params['module'][$i];
            $activity = $params['activity'][$i];
            $student = $params['student'][$i];
            $assessor = $params['assessor'][$i];
            $mode = $params['mode'][$i];
            $str = $courseid . '_' . $module . '_' . $activity . '_' . $student . '_' . $assessor . '_' . $mode . '_' . $lms;
            $assessmentid = md5($str);
            $xml .= $assessmentid;
            $xml .= '</assessmentid>';
        }
        $xml .= '</assessments>';

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new block_evalcomix_curl();
        $response = $curl->post($serverurl, $xml);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            $result = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NSCLEAN);
            return $result;
        } else {
            throw new Exception('Page: Bad Response');
        }
    }

    public static function verify($url) {
        defined('BLOCK_EVALCOMIX_VERIFY') || die('EvalCOMIX is not configured');

        $token = self::get_token();
        $serverurl = BLOCK_EVALCOMIX_VERIFY . '?token='.$token;
        global $CFG;

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new block_evalcomix_curl();
        $response = $curl->get($serverurl);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            $result = simplexml_load_string($response);
            if ((string)$result->status == 'Success' ) {
                return 1;
            } else {
                return (string)$result->description;
            }
        }
    }

    public static function print_error($message = null) {
        $output = '<div class="text-center text-danger border border-danger font-weight-bold m-3 p-3">';
        if (isset($message)) {
            $output .= $message;
        } else {
            $output .= 'EvalCOMIX Block is not configured correctly. Please, contact the system administrator';
        }
        $output .= '</div>';
        echo $output;
    }

    public static function get_tool($idtool) {
        global $DB, $CFG;
        defined('BLOCK_EVALCOMIX_GET_TOOLS2') || die('EvalCOMIX is not configured');

        if (!$DB->get_record('block_evalcomix_tools', array('idtool' => $idtool))) {
            return false;
        }
        $token = self::get_token();
        $serverurl = BLOCK_EVALCOMIX_GET_TOOLS2 . '/'.$idtool.'?token='.$token;

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new block_evalcomix_curl();
        $response = $curl->get($serverurl);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            return $response;
        }
        return false;
    }

    /**
     * @param string $params['courseid']
     * @return xml document with data assessments
     */
    public static function get_ws_xml_tools2($params = array()) {
        defined('BLOCK_EVALCOMIX_GET_TOOLS2') || die('EvalCOMIX is not configured');
        global $CFG;

        $token = self::get_token();
        $serverurl = BLOCK_EVALCOMIX_GET_TOOLS2 . '?token='.$token;

        if (!isset($params['courseid'])) {
            throw new Exception('Missing Params');
        }

        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tool.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix.php');
                $xml = '<?xml version="1.0" encoding="utf-8"?>
        <assessmenttools>';
        if ($block = block_evalcomix_class::fetch(array('courseid' => $params['courseid']))) {
            if ($tools = block_evalcomix_tool::fetch_all(array('evxid' => $block->id))) {
                foreach ($tools as $tool) {
                    $xml .= '<toolid>';
                    $xml .= $tool->idtool;
                    $xml .= '</toolid>';
                }
            }
        }

        $xml .= '</assessmenttools>';

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new block_evalcomix_curl();
        $response = $curl->post($serverurl, $xml);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            $result = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NSCLEAN);
            return $result;
        } else {
            throw new Exception('Page: Bad Response');
        }
    }

    /**
     * @param string $params['toolxml']
     * @return id of tool or false
     */
    public static function post_ws_xml_tools($params = array()) {
        defined('BLOCK_EVALCOMIX_CREATE_TOOL') || die('EvalCOMIX is not configured');
        global $CFG;

        $id = self::generate_token();
        if (isset($params['id'])) {
            $id = $params['id'];
        }
        $token = self::get_token();
        $serverurl = BLOCK_EVALCOMIX_CREATE_TOOL . $id . '?token=' . $token;
        $xml = $params['toolxml'];
        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new block_evalcomix_curl();
        $response = $curl->post($serverurl, $xml);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            $result = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NSCLEAN);
            if (isset($result->status) && (string)$result->status == 'Success' && isset($result->description)) {
                $xmlresult = current($result->description);

                return (string)$xmlresult['id'];
            } else {
                return false;
            }
        } else {
            throw new Exception('Page: Bad Response');
        }
    }

    /**
     * @param array params['tools'] array of tool objects
     * @return object with updated grades of assessments related with $params['tools']
     */
    public static function get_assessments_modified($params) {
        global $CFG;
        defined('BLOCK_EVALCOMIX_GET_ASSESSMENT_MODIFIED') || die('EvalCOMIX is not configured');

        if (!isset($params['tools']) || !is_array($params['tools'])) {
            return false;
        }
        $tools = $params['tools'];
        $result = array();

        if (!empty($tools)) {
            $hashtools = array();
            $xml = '<?xml version="1.0" encoding="utf-8"?>
            <assessmentTools>';
            foreach ($tools as $tool) {
                $idtool = $tool->idtool;
                $xml .= '<toolid>'.$idtool.'</toolid>';
                $hashtools[$idtool] = $tool;
            }
            $xml .= '</assessmentTools>';

            $token = self::get_token();
            $serverurlaux = BLOCK_EVALCOMIX_GET_ASSESSMENT_MODIFIED;
            $serverurl = $serverurlaux . '?token='.$token;

            require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');
            $curl = new block_evalcomix_curl();
            $response = $curl->post($serverurl, $xml);

            if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
                $xml = simplexml_load_string($response);
                if (isset($xml->status) && $xml->status == 'Success') {
                    $xmlresult = current($xml->description);
                    foreach ($xmlresult->assessment as $assessment) {
                        $assid = (string)$assessment['id'];
                        $newgrade = (string)$assessment->grade;
                        $maxgrade = (string)$assessment->maxgrade;
                        $object = new stdClass();
                        $object->grade = trim($newgrade);
                        $object->maxgrade = $maxgrade;
                        $object->toolid = (string)$assessment->toolid;
                        $result[$assid] = $object;
                    }
                    return $result;
                } else {
                    return false;
                }
            } else {
                throw new Exception('Tool Modified: Bad Response');
            }
        }
        return $result;
    }

    /**
     * It indicates to EvalCOMIX server that new grades of assessments has been modified correctly
     * @param array params['toolids'] array of tool objects
     * @return true if the indication was registered correctly
     */
    public static function set_assessments_modified($params) {
        global $CFG;
        defined('BLOCK_EVALCOMIX_TOOL_MODIFIED') || die('EvalCOMIX is not configured');

        if (!isset($params['toolids']) || !is_array($params['toolids'])) {
            return false;
        }
        $toolids = $params['toolids'];

        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <toolids>';
        foreach ($toolids as $toolid) {
            $idtool = $toolid;
            $xml .= '<toolid id="'.$idtool.'">false</toolid>';
        }
        $xml .= '</toolids>';

        $token = self::get_token();
        $serverurl = BLOCK_EVALCOMIX_TOOL_MODIFIED . '?token=' . $token;

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');
        $curl = new block_evalcomix_curl();
        $response = $curl->put($serverurl, $xml);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            $xml = simplexml_load_string($response);
            if (isset($xml->status) && $xml->status == 'Success') {
                return true;
            }
        }
        return false;
    }

    /**
     * @param int $params[subdimensionid][assessmentid]
     */
    public static function get_grade_subdimension($params = array()) {
        defined('BLOCK_EVALCOMIX_GET_GRADE_SUBDIMENSION') || die('EvalCOMIX is not configured');
        global $CFG, $DB;

        $token = self::get_token();
        $serverurl = BLOCK_EVALCOMIX_GET_GRADE_SUBDIMENSION . '?token='.$token;

        if (!empty($params)) {
            $xml = '<?xml version="1.0" encoding="utf-8"?>
<subdimensionassessments>';
            foreach ($params as $subdimensionid => $assessments) {
                $xml .= '<subdimass subid="'.$subdimensionid.'">';
                foreach ($assessments as $assessmentid => $assessment) {
                    $xml .= '<id>'.$assessmentid.'</id>';
                }
                $xml .= '</subdimass>';
            }
            $xml .= '</subdimensionassessments>';

            require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');
            $curl = new block_evalcomix_curl();
            $response = $curl->post($serverurl, $xml);
            if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
                $result = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NSCLEAN);
                $datas = array();
                foreach ($result as $subdimensiongrades) {
                    $subdimensionid = (string)$subdimensiongrades['subid'];
                    foreach ($subdimensiongrades as $assessment) {
                        $assessmentid = (string)$assessment['id'];
                        $grade = (int)$assessment;
                        if (isset($params[$subdimensionid][$assessmentid])) {
                            $cmid = $params[$subdimensionid][$assessmentid]->cmid;
                            $modeid = $params[$subdimensionid][$assessmentid]->modeid;
                            $datas[] = array('cmid' => (int)$cmid, 'idsubdimension' => $subdimensionid,
                                'idassessment' => $assessmentid, 'grade' => $grade, 'modeid' => $modeid);
                        }
                    }
                }
                return $datas;
            } else {
                throw new Exception('Page: Bad Response');
            }
        }
    }

    public static function get_commented_assessments($courseid, $assessments) {
        defined('BLOCK_EVALCOMIX_ASSESSMENT_COMMENTED') || die('EvalCOMIX is not correctly configured');
        global $CFG;
        $result = array();
        $hash = array();

        if (!empty($assessments)) {
            $xml = '<assessments>';
            foreach ($assessments as $assessment) {
                $assid = block_evalcomix_get_assessmentid($courseid, $assessment);
                $assid = ($assid == 0) ? '' : $assid;
                $assessmentid = $assessment->id;
                $hash[$assid] = $assessmentid;
                $xml .= '<assessment>'.$assid.'</assessment>';
            }
            $xml .= '</assessments>';

            $token = self::get_token();
            $serverurl = BLOCK_EVALCOMIX_ASSESSMENT_COMMENTED . '?token='.$token;
            require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');
            $curl = new block_evalcomix_curl();
            $response = $curl->post($serverurl, $xml);

            if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
                $xml = simplexml_load_string($response);
                foreach ($xml as $assessment) {
                    $assid = (string)$assessment['id'];
                    $assessmentid = $hash[$assid];
                    $result[$assessmentid] = (int)$assessment;
                }
            }
        }
        return $result;
    }

    public static function get_token() {
        defined('BLOCK_EVALCOMIX_TOKEN') || die('EvalCOMIX is not correctly configured');
        date_default_timezone_set('Europe/Madrid');
        $date = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
        return sha1(BLOCK_EVALCOMIX_TOKEN.$date);
    }
}
