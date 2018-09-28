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

// Root directory of EvalCOMIX logo.
if (!defined('EVXLOGOROOT')) {
    define('EVXLOGOROOT', '/blocks/evalcomix/images/logoevalcomix.png');
}

global $CFG;
// Moodle instance name.
if (!defined('MOODLE_NAME')) {
    define('MOODLE_NAME', $CFG->dbname);
}

if (isset($CFG->evalcomix_serverurl)) {
    // URL base of EvalCOMIX application.
    if (!defined('DIREVALCOMIX')) {
        define('DIREVALCOMIX', $CFG->evalcomix_serverurl);
    }

    if (!defined('DIREVALCOMIXS')) {
        define('DIREVALCOMIXS', $CFG->evalcomix_serverurl);
    }

    // EvalCOMIX API.
    if (!defined('MAINAPI_EVALCOMIX')) {
        define('MAINAPI_EVALCOMIX', DIREVALCOMIX . '/webservice/get_list_tools_course.php');
    }

    if (!defined('GRADE_EVALCOMIX')) {
        define('GRADE_EVALCOMIX', DIREVALCOMIX .'/webservice/get_grade.php');
    }

    if (!defined('DISPLAY_TOOL')) {
        define('DISPLAY_TOOL', DIREVALCOMIXS . '/webservice/get_view_form.php');
    }

    if (!defined('FORM_ASSESS')) {
        define('FORM_ASSESS', DIREVALCOMIXS . '/webservice/get_assessment_form.php');
    }

    if (!defined('EVALCOMIX3')) {
        define('EVALCOMIX3', DIREVALCOMIX .'/client/evalcomix3.php');
    }

    if (!defined('DELETE')) {
        define('DELETE', DIREVALCOMIX .'/webservice/delete_tool.php');
    }

    if (!defined('GET_TOOL_ASSESSED')) {
        define('GET_TOOL_ASSESSED', DIREVALCOMIX . '/webservice/get_tool.php');
    }

    if (!defined('DELETE_ASSESS')) {
        define('DELETE_ASSESS', DIREVALCOMIX . '/webservice/delete_asessment.php');
    }

    if (!defined('DUPLICATE_COURSE')) {
        define('DUPLICATE_COURSE', DIREVALCOMIX . '/webservice/duplicate_course.php');
    }

    if (!defined('DUPLICATE_COURSE2')) {
        define('DUPLICATE_COURSE2', DIREVALCOMIX . '/webservice/duplicate_course2.php');
    }

    if (!defined('DUPLICATE_TOOL')) {
        define('DUPLICATE_TOOL', DIREVALCOMIX . '/webservice/duplicate_tool.php');
    }
    if (!defined('GET_TOOLS')) {
        define('GET_TOOLS', DIREVALCOMIX . '/webservice/get_tools.php');
    }

    if (!defined('VERIFY')) {
        define('VERIFY', DIREVALCOMIX . '/webservice/verify.php');
    }
    if (!defined('GET_TOOLS2')) {
        define('GET_TOOLS2', DIREVALCOMIX . '/webservice/get_tools2.php');
    }
    if (!defined('CREATE_TOOL')) {
        define('CREATE_TOOL', DIREVALCOMIX . '/webservice/import_tool.php');
    }
    if (!defined('GET_ASSESSMENT_MODIFIED')) {
        define('GET_ASSESSMENT_MODIFIED', DIREVALCOMIX . '/webservice/get_assessment_modified.php');
    }
    if (!defined('TOOL_MODIFIED')) {
        define('TOOL_MODIFIED', DIREVALCOMIX . '/webservice/tool_modified.php');
    }
}
// Block name.
if (!defined('BLOCKNAME')) {
    define('BLOCKNAME', 'evalcomix');
}
