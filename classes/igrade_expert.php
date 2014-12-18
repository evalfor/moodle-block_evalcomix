<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
 
	/**
	* Interface of class grade_expert
	*/
	interface igrade_expert{
		/**
		* It gets grades of $users in $courseid of $platform
		* @param array $platform Moodle instance
		* @param array $courseid of Moodle
		* @param array $users of Moodle
		* @return array grades
		*/
		public function get_grades($courseid, $users, $platform);
	}
	
?>