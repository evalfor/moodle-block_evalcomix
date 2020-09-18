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
if (!defined('BLOCK_EVALCOMIX_EVXLOGOROOT')) {
    define('BLOCK_EVALCOMIX_EVXLOGOROOT', '/blocks/evalcomix/images/logoevalcomix.png');
}

global $CFG;
// Moodle instance name.
if (!defined('BLOCK_EVALCOMIX_MOODLE_NAME')) {
    define('BLOCK_EVALCOMIX_MOODLE_NAME', $CFG->dbname);
}

$config = get_config('block_evalcomix');
if (isset($config->serverurl)) {
    // URL base of EvalCOMIX application.
    if (!defined('BLOCK_EVALCOMIX_DIREVALCOMIX')) {
        define('BLOCK_EVALCOMIX_DIREVALCOMIX', $config->serverurl);
    }

    if (!defined('BLOCK_EVALCOMIX_DIREVALCOMIXS')) {
        define('BLOCK_EVALCOMIX_DIREVALCOMIXS', $config->serverurl);
    }

    // EvalCOMIX API.
    if (!defined('BLOCK_EVALCOMIX_MAINAPI_EVALCOMIX')) {
        define('BLOCK_EVALCOMIX_MAINAPI_EVALCOMIX', BLOCK_EVALCOMIX_DIREVALCOMIX . '/webservice/get_list_tools_course.php');
    }

    if (!defined('BLOCK_EVALCOMIX_GRADE_EVALCOMIX')) {
        define('BLOCK_EVALCOMIX_GRADE_EVALCOMIX', BLOCK_EVALCOMIX_DIREVALCOMIX .'/webservice/get_grade.php');
    }

    if (!defined('BLOCK_EVALCOMIX_DISPLAY_TOOL')) {
        define('BLOCK_EVALCOMIX_DISPLAY_TOOL', BLOCK_EVALCOMIX_DIREVALCOMIXS . '/webservice/get_view_form.php');
    }

    if (!defined('BLOCK_EVALCOMIX_FORM_ASSESS')) {
        define('BLOCK_EVALCOMIX_FORM_ASSESS', BLOCK_EVALCOMIX_DIREVALCOMIXS . '/webservice/get_assessment_form.php');
    }

    if (!defined('BLOCK_EVALCOMIX_EVALCOMIX3')) {
        define('BLOCK_EVALCOMIX_EVALCOMIX3', BLOCK_EVALCOMIX_DIREVALCOMIX .'/client/evalcomix3.php');
    }

    if (!defined('BLOCK_EVALCOMIX_DELETE')) {
        define('BLOCK_EVALCOMIX_DELETE', BLOCK_EVALCOMIX_DIREVALCOMIX .'/webservice/delete_tool.php');
    }

    if (!defined('BLOCK_EVALCOMIX_GET_TOOL_ASSESSED')) {
        define('BLOCK_EVALCOMIX_GET_TOOL_ASSESSED', BLOCK_EVALCOMIX_DIREVALCOMIX . '/webservice/get_tool.php');
    }

    if (!defined('BLOCK_EVALCOMIX_DELETE_ASSESS')) {
        define('BLOCK_EVALCOMIX_DELETE_ASSESS', BLOCK_EVALCOMIX_DIREVALCOMIX . '/webservice/delete_asessment.php');
    }

    if (!defined('BLOCK_EVALCOMIX_DUPLICATE_COURSE')) {
        define('BLOCK_EVALCOMIX_DUPLICATE_COURSE', BLOCK_EVALCOMIX_DIREVALCOMIX . '/webservice/duplicate_course.php');
    }

    if (!defined('BLOCK_EVALCOMIX_DUPLICATE_COURSE2')) {
        define('BLOCK_EVALCOMIX_DUPLICATE_COURSE2', BLOCK_EVALCOMIX_DIREVALCOMIX . '/webservice/duplicate_course2.php');
    }

    if (!defined('BLOCK_EVALCOMIX_DUPLICATE_TOOL')) {
        define('BLOCK_EVALCOMIX_DUPLICATE_TOOL', BLOCK_EVALCOMIX_DIREVALCOMIX . '/webservice/duplicate_tool.php');
    }
    if (!defined('BLOCK_EVALCOMIX_GET_TOOLS')) {
        define('BLOCK_EVALCOMIX_GET_TOOLS', BLOCK_EVALCOMIX_DIREVALCOMIX . '/webservice/get_tools.php');
    }

    if (!defined('BLOCK_EVALCOMIX_VERIFY')) {
        define('BLOCK_EVALCOMIX_VERIFY', BLOCK_EVALCOMIX_DIREVALCOMIX . '/webservice/verify.php');
    }
    if (!defined('BLOCK_EVALCOMIX_GET_TOOLS2')) {
        define('BLOCK_EVALCOMIX_GET_TOOLS2', BLOCK_EVALCOMIX_DIREVALCOMIX . '/webservice/get_tools2.php');
    }
    if (!defined('BLOCK_EVALCOMIX_CREATE_TOOL')) {
        define('BLOCK_EVALCOMIX_CREATE_TOOL', BLOCK_EVALCOMIX_DIREVALCOMIX . '/webservice/import_tool.php');
    }
    if (!defined('BLOCK_EVALCOMIX_GET_ASSESSMENT_MODIFIED')) {
        define('BLOCK_EVALCOMIX_GET_ASSESSMENT_MODIFIED', BLOCK_EVALCOMIX_DIREVALCOMIX . '/webservice/get_assessment_modified.php');
    }
    if (!defined('BLOCK_EVALCOMIX_TOOL_MODIFIED')) {
        define('BLOCK_EVALCOMIX_TOOL_MODIFIED', BLOCK_EVALCOMIX_DIREVALCOMIX . '/webservice/tool_modified.php');
    }
}
