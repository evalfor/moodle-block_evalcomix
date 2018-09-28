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
require_once($CFG->dirroot . '/blocks/evalcomix/configeval.php');
require_once($CFG->dirroot . '/lib/filelib.php');

// API for EvalCOMIX webservices.
class webservice_evalcomix_client {

    /**
     * @param string $lms name of instance of Moodle
     * @param $courseid
     * @param $language as 'es_es_utf8'
     * @param $extra parameters to add to URI
     * @return int new ID Tool
     */
    public static function get_ws_createtool($id = null, $lms = 'Moodle24', $courseid, $language = 'es_es_utf8', $type = 'new') {
        defined('EVALCOMIX3') || print_error('EvalCOMIX is not configured');
        global $CFG;

        $serverurlaux = EVALCOMIX3;
        if (!$id && $type == 'new') {

            $id = self::generate_token();
        }

        $get = 'identifier='. $id . '&lang='. $language .'&type='.$type;

        $serverurl = $serverurlaux . '?'. $get;

        if (self::check_url($serverurl)) {
            require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');
            require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix.php');
            $environment = evalcomix::fetch(array('courseid' => $courseid));
            if (!$environment) {
                $environment = new evalcomix('', $courseid, 'evalcomix');
                $environment->insert();
            }
            if (!evalcomix_tool::fetch(array('evxid' => $environment->id, 'idtool' => $id))) {
                $newtool = new evalcomix_tool('', $environment->id, '-000_1', 'tmp', $id);
                $newtool->insert();
            }
            return $serverurl;
        } else {
            self::print_error();
        }
    }

    /**
     * @param $toolid Assessment tool ID
     * @return string URL validated to view tool
     */
    public static function get_ws_viewtool($toolid = 0, $language = 'es_utf8', $courseid = 0, $module = 0,
        $activity = 0, $student = 0, $assessor = 0, $mode = 'teacher', $lms = 0,  $title = '') {

        defined('DISPLAY_TOOL') || die('EvalCOMIX is not configured properly');

        $str = $courseid . '_' . $module . '_' . $activity . '_' . $student . '_' . $assessor . '_' . $mode . '_' . $lms;
        $assessmentid = md5($str);

        $serverurlaux = DISPLAY_TOOL;
        $serverurl = $serverurlaux . '?ass='. $assessmentid .'&pla='. $toolid. '&tit='.urlencode($title). '&lang=' . $language;

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
        defined('DELETE') || die('EvalCOMIX is not configured');

        $serverurlaux = DELETE;
        $get = 'id=' . $toolid;
        $serverurl = $serverurlaux . '?'. $get;

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new Curly();
        $response = $curl->get($serverurl);

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
    public static function get_ws_assessment_form($toolid = 0, $language = 'es_utf8', $courseid = 0,
        $module = 0, $activity = 0, $student = 0, $assessor = 0, $mode = 'teacher', $lms = 0,
        $perspective = 'assess', $title = '') {

        defined('FORM_ASSESS') || die('EvalCOMIX is not configured properly');

        $str = $courseid . '_' . $module . '_' . $activity . '_' . $student . '_' . $assessor . '_' . $mode . '_' . $lms;
        $assessmentid = md5($str);

        $serverurlaux = FORM_ASSESS;
        $serverurl = $serverurlaux . '?ass='. $assessmentid .'&pla='. $toolid .'&type=open&mode=' . $perspective.
            '&tit='.urlencode($title).'&lang='.$language;

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
    public static function delete_ws_assessment($courseid = 0, $module = 0, $activity = 0,
        $student = 0, $assessor = 0, $mode = 'teacher', $lms = 0) {

        global $CFG;
        defined('DELETE_ASSESS') || die('EvalCOMIX is not configured');

        $serverurlaux = DELETE_ASSESS;

        $str = $courseid . '_' . $module . '_' . $activity . '_' . $student . '_' . $assessor . '_' . $mode . '_' . $lms;

        $assessmentid = md5($str);

        $serverurl = $serverurlaux . '?ass='. $assessmentid;

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new Curly();
        $response = $curl->get($serverurl);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            if ($xml = simplexml_load_string($response)) {
                if ((string)$xml->status == 'success' ) {
                    return $serverurl;
                } else {
                    print_error('XML Document invalid');
                }
            } else {
                print_error('XML Document invalid');
            }
        } else {
            throw new Exception('SG: Bad Response');
        }
    }


    /**
     * @param string $courseid
     * @param string $lms
     * @return string XML document with course tools
     */
    public static function get_ws_list_tool($courseid, $tool) {
        global $CFG;
        defined('GET_TOOL_ASSESSED') || die('EvalCOMIX is not configured');

        $serverurlaux = GET_TOOL_ASSESSED;
        $serverurl = $serverurlaux . '?tool='. $tool . '&format=xml';

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');
        $curl = new Curly();
        $response = $curl->get($serverurl);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            $result = null;
            require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix_tool.php');
            require_once($CFG->dirroot .'/blocks/evalcomix/classes/evalcomix.php');
            $environment = evalcomix::fetch(array('courseid' => $courseid));
            if (!$environment) {
                $environment = new evalcomix('', $courseid, 'evalcomix');
                $environment->insert();
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
                    $result = new evalcomix_tool('', $environment->id, $title, $type, $tool);
                } else {
                    return false;
                }

                return $result;
            } else {
                self::print_error('XML Document invalid');
            }
        } else {
            self::print_error('GetTool: Invalid URL, EvalCOMIX is not configured correctly');
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
    public static function get_ws_singlegrade($toolid = 0, $courseid = 0, $module = 0, $activity = 0,
        $student = 0, $assessor = 0, $mode = 'teacher', $lms = 0) {

        global $DB, $CFG;
        defined('GRADE_EVALCOMIX') || die('EvalCOMIX is not configured');

        $task = $DB->get_record('block_evalcomix_tasks', array('instanceid' => $activity), '*', MUST_EXIST);

        $serverurlaux = GRADE_EVALCOMIX;

        $str = $courseid . '_' . $module . '_' . $activity . '_' . $student . '_' . $assessor . '_' . $mode . '_' . $lms;
        $assessmentid = md5($str);

        $get = 'pla=' . $toolid . '&ass=' . $assessmentid;
        $serverurl = $serverurlaux . '?' . $get;

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new Curly();
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

                $result = new evalcomix_assessments('', $task->id, $assessor, $student, $grade);
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
        defined('DUPLICATE_COURSE') || die('EvalCOMIX is not configured');
        defined('DUPLICATE_COURSE2') || die('EvalCOMIX is not configured');

        $serverurl = DUPLICATE_COURSE;
        $xml = '<?xml version="1.0" encoding="utf-8"?>
                <backup>
                <toolsid>';

        foreach ($tools as $tool) {
            $xml .= '<toolid>
                    <oldid>'.$tool->oldid.'</oldid>
                    <newid>'.$tool->newid.'</newid>
                </toolid>';
        }

        $xml .= '</toolsid>
                <assessmentsid>';
        foreach ($assessments as $assessment) {
            $xml .= '<assessmentid>
                <oldid>'.$assessment->oldid.'</oldid>
                <newid>'.$assessment->newid.'</newid>
            </assessmentid>';
        }
        $xml .= '</assessmentsid>
                </backup>';

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new Curly();
        $response = $curl->post($serverurl, $xml);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            $result = simplexml_load_string($response);

            if (isset($result->status) && (string)$result->status != '#error') {
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
        $encript = new E5CR($key);
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

        $curl = new Curly();
        $response = $curl->get($url);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function duplicate_tool($toolold) {
        global $CFG;
        defined('DUPLICATE_TOOL') || die('EvalCOMIX is not configured');

        $serverurlaux = DUPLICATE_TOOL;

        $newid = self::generate_token();
        $serverurl = $serverurlaux . '?oldid='. $toolold . '&newid='. $newid;

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new Curly();
        $response = $curl->get($serverurl);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            if ($xml = simplexml_load_string($response)) {
                if ((string)$xml->status == 'success' ) {
                    return (string)$xml->description;
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
        defined('GET_TOOLS') || die('EvalCOMIX is not configured');
        global $CFG;

        $serverurl = GET_TOOLS . '?format=xml';

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
            $xml .= '<assessment>';
            $module = $params['module'][$i];
            $activity = $params['activity'][$i];
            $student = $params['student'][$i];
            $assessor = $params['assessor'][$i];
            $mode = $params['mode'][$i];
            $str = $courseid . '_' . $module . '_' . $activity . '_' . $student . '_' . $assessor . '_' . $mode . '_' . $lms;
            $assessmentid = md5($str);
            $xml .= $assessmentid;
            $xml .= '</assessment>';
        }
        $xml .= '</assessments>';

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new Curly();
        $response = $curl->post($serverurl, $xml);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            $result = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NSCLEAN);
            return $result;
        } else {
            throw new Exception('Page: Bad Response');
        }
    }

    public static function verify($url) {
        defined('VERIFY') || die('EvalCOMIX is not configured');

        $serverurl = $url . '/webservice/verify.php';
        global $CFG;

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new Curly();
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
        $output = '<div style="text-align:center;color:#f00;padding:1em;border:1px solid #f00;margin:1em;font-weight:bold">';
        if (isset($message)) {
            $output .= $message;
        } else {
            $output .= 'EvalCOMIX Block is not configured correctly. Please, contact the system administrator';
        }
        $output .= '</div>';
        echo $output;
    }

    /**
     * @param string $params['courseid']
     * @return xml document with data assessments
     */
    public static function get_ws_xml_tools2($params = array()) {
        defined('GET_TOOLS2') || die('EvalCOMIX is not configured');
        global $CFG;

        $serverurl = GET_TOOLS2 . '?format=xml';

        if (!isset($params['courseid'])) {
            throw new Exception('Missing Params');
        }

        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tool.php');
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix.php');
                $xml = '<?xml version="1.0" encoding="utf-8"?>
        <assessments>';
        if ($block = evalcomix::fetch(array('courseid' => $params['courseid']))) {
            if ($tools = evalcomix_tool::fetch_all(array('evxid' => $block->id))) {
                foreach ($tools as $tool) {
                    $xml .= '<tool>';
                    $xml .= $tool->idtool;
                    $xml .= '</tool>';
                }
            }
        }

        $xml .= '</assessments>';

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new Curly();
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
        defined('CREATE_TOOL') || die('EvalCOMIX is not configured');
        global $CFG;

        $id = self::generate_token();
        $serverurl = CREATE_TOOL . '?id='.$id;
        $xml = $params['toolxml'];
        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');

        $curl = new Curly();
        $response = $curl->post($serverurl, $xml);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            $result = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NSCLEAN);
            if (isset($result->status) && (string)$result->status == 'success' && isset($result->description)) {
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
        defined('GET_ASSESSMENT_MODIFIED') || die('EvalCOMIX is not configured');

        if (!isset($params['tools']) || !is_array($params['tools'])) {
            return false;
        }
        $tools = $params['tools'];
        $hashtools = array();

        $xml = '<?xml version="1.0" encoding="utf-8"?>
        <tools>';
        foreach ($tools as $tool) {
            $idtool = $tool->idtool;
            $xml .= '<toolid>'.$idtool.'</toolid>';
            $hashtools[$idtool] = $tool;
        }
        $xml .= '</tools>';

        $serverurlaux = GET_ASSESSMENT_MODIFIED;
        $serverurl = $serverurlaux . '?&e=1';

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');
        $curl = new Curly();
        $response = $curl->post($serverurl, $xml);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            $xml = simplexml_load_string($response);
            if (isset($xml->status) && $xml->status == 'success') {
                $xmlresult = current($xml->description);
                $result = array();
                foreach ($xmlresult->assessment as $assessment) {
                    $assid = (string)$assessment['id'];
                    $newgrade = (string)$assessment->grade;
                    $maxgrade = (string)$assessment->maxgrade;
                    $object = new stdClass();
                    $object->grade = $newgrade;
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

    /**
     * It indicates to EvalCOMIX server that new grades of assessments has been modified correctly
     * @param array params['toolids'] array of tool objects
     * @return true if the indication was registered correctly
     */
    public static function set_assessments_modified($params) {
        global $CFG;
        defined('TOOL_MODIFIED') || die('EvalCOMIX is not configured');

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

        $serverurlaux = TOOL_MODIFIED;
        $serverurl = $serverurlaux . '?&e=2';

        require_once($CFG->dirroot .'/blocks/evalcomix/classes/curl.class.php');
        $curl = new Curly();
        $response = $curl->post($serverurl, $xml);

        if ($response && $curl->get_http_code() >= 200 && $curl->get_http_code() < 400) {
            $xml = simplexml_load_string($response);
            if (isset($xml->status) && $xml->status == 'success') {
                return true;
            }
        }
        return false;
    }
}