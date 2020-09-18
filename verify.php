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

require_once('../../config.php');
require_login();
require_once($CFG->dirroot.'/blocks/evalcomix/classes/webservice_evalcomix_client.php');
$u = required_param('u', PARAM_URL);

$result = block_evalcomix_webservice_client::verify($u);
if ($result == 1) {
    echo get_string('valid_conection', 'block_evalcomix');
} else {
    echo get_string('error_conection', 'block_evalcomix');
    if (isset($result)) {
        echo '    '. get_string('simple_error_conection', 'block_evalcomix');
        var_dump($result);
    }
}
