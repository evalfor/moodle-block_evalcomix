<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
 
require('../../../config.php');

$relativepath = get_file_argument();
$fs = get_file_storage();
if (!$file = $fs->get_file_by_hash(sha1($relativepath)) or $file->is_directory()) {
	return false;
}
send_stored_file($file, 0, 0, true); // download MUST be forced - security!