<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
 
require_once('../../config.php');
require_once($CFG->dirroot.'/blocks/evalcomix/classes/webservice_evalcomix_client.php');
$u = required_param('u', PARAM_URL);

$result = webservice_evalcomix_client::verify($u);
if($result == 1){
	echo get_string('valid_conection','block_evalcomix');
}
else{
	echo get_string('error_conection', 'block_evalcomix');
	if(isset($result)){
		echo '    '. get_string('simple_error_conection','block_evalcomix');
		print_r($result);
	}
}