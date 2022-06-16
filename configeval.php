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
    if (!defined('BLOCK_EVALCOMIX_GRADE_EVALCOMIX')) {
        define('BLOCK_EVALCOMIX_GRADE_EVALCOMIX', BLOCK_EVALCOMIX_DIREVALCOMIX .'/app.php/api/grade/');
    }

    if (!defined('BLOCK_EVALCOMIX_DISPLAY_TOOL')) {
        define('BLOCK_EVALCOMIX_DISPLAY_TOOL', BLOCK_EVALCOMIX_DIREVALCOMIXS . '/app.php/api/client/tool/');
    }

    if (!defined('BLOCK_EVALCOMIX_DISPLAY_TOOL_ASSESSED')) {
        define('BLOCK_EVALCOMIX_DISPLAY_TOOL_ASSESSED', BLOCK_EVALCOMIX_DIREVALCOMIXS . '/app.php/api/client/assessment/');
    }

    if (!defined('BLOCK_EVALCOMIX_FORM_ASSESS')) {
        define('BLOCK_EVALCOMIX_FORM_ASSESS', BLOCK_EVALCOMIX_DIREVALCOMIXS .
        '/app.php/api/client/assessment/:assessmentid/tool/:toolid/edit');
    }

    if (!defined('BLOCK_EVALCOMIX_CLIENT_NEW')) {
        define('BLOCK_EVALCOMIX_CLIENT_NEW', $CFG->wwwroot .
        '/blocks/evalcomix/tool/editor/selection.php?type=new&identifier=:toolid');
    }

    if (!defined('BLOCK_EVALCOMIX_CLIENT_EDIT')) {
        define('BLOCK_EVALCOMIX_CLIENT_EDIT', $CFG->wwwroot .
        '/blocks/evalcomix/tool/editor/selection.php?type=open&identifier=:toolid');
    }

    if (!defined('BLOCK_EVALCOMIX_DELETE')) {
        define('BLOCK_EVALCOMIX_DELETE', BLOCK_EVALCOMIX_DIREVALCOMIX .'/app.php/api/tool/');
    }

    if (!defined('BLOCK_EVALCOMIX_GET_TOOL')) {
        define('BLOCK_EVALCOMIX_GET_TOOL', BLOCK_EVALCOMIX_DIREVALCOMIX . '/app.php/api/tool/');
    }

    if (!defined('BLOCK_EVALCOMIX_DELETE_ASSESS')) {
        define('BLOCK_EVALCOMIX_DELETE_ASSESS', BLOCK_EVALCOMIX_DIREVALCOMIX . '/app.php/api/assessment/');
    }

    if (!defined('BLOCK_EVALCOMIX_DUPLICATE_COURSE')) {
        define('BLOCK_EVALCOMIX_DUPLICATE_COURSE', BLOCK_EVALCOMIX_DIREVALCOMIX . '/app.php/api/assessment/duplicate');
    }

    if (!defined('BLOCK_EVALCOMIX_DUPLICATE_TOOL')) {
        define('BLOCK_EVALCOMIX_DUPLICATE_TOOL', BLOCK_EVALCOMIX_DIREVALCOMIX .
        '/app.php/api/tool/:currenttool/duplicate/:newtool');
    }

    if (!defined('BLOCK_EVALCOMIX_GET_TOOLS')) {
        define('BLOCK_EVALCOMIX_GET_TOOLS', BLOCK_EVALCOMIX_DIREVALCOMIX . '/app.php/api/assessment');
    }

    if (!defined('BLOCK_EVALCOMIX_VERIFY')) {
        define('BLOCK_EVALCOMIX_VERIFY', BLOCK_EVALCOMIX_DIREVALCOMIX . '/app.php/api/check');
    }
    if (!defined('BLOCK_EVALCOMIX_GET_TOOLS2')) {
        define('BLOCK_EVALCOMIX_GET_TOOLS2', BLOCK_EVALCOMIX_DIREVALCOMIX . '/app.php/api/tool');
    }
    if (!defined('BLOCK_EVALCOMIX_CREATE_TOOL')) {
        define('BLOCK_EVALCOMIX_CREATE_TOOL', BLOCK_EVALCOMIX_DIREVALCOMIX . '/app.php/api/tool/');
    }
    if (!defined('BLOCK_EVALCOMIX_GET_ASSESSMENT_MODIFIED')) {
        define('BLOCK_EVALCOMIX_GET_ASSESSMENT_MODIFIED', BLOCK_EVALCOMIX_DIREVALCOMIX . '/app.php/api/grade');
    }
    if (!defined('BLOCK_EVALCOMIX_TOOL_MODIFIED')) {
        define('BLOCK_EVALCOMIX_TOOL_MODIFIED', BLOCK_EVALCOMIX_DIREVALCOMIX . '/app.php/api/tool/nomodified');
    }
    if (!defined('BLOCK_EVALCOMIX_GET_GRADE_SUBDIMENSION')) {
        define('BLOCK_EVALCOMIX_GET_GRADE_SUBDIMENSION', BLOCK_EVALCOMIX_DIREVALCOMIX . '/app.php/api/grade/subdimension');
    }
    if (!defined('BLOCK_EVALCOMIX_ASSESSMENT_COMMENTED')) {
        define('BLOCK_EVALCOMIX_ASSESSMENT_COMMENTED', BLOCK_EVALCOMIX_DIREVALCOMIX . '/app.php/api/assessment/commented');
    }
}
if (isset($config->token)) {
    if (!defined('BLOCK_EVALCOMIX_TOKEN')) {
        define('BLOCK_EVALCOMIX_TOKEN', $config->token);
    }
}
