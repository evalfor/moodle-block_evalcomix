<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
	/**
	* Interface of class calcultator
	*/
	interface icalculator{
		/**
		* It applies a mathematical operation to $elements1 and $elements2
		* $activities and $users to travel the element arrays
		* @param array $elements1
		* @param array $elements2
		* @param array $activities
		* @param array $users
		* @return array result of the operation
		*/
		public function calculate($elements1, $elements2, $activities, $users);
		
		/**
		* It applies a mathematical operation to $elements
		* @param array $elements
		* @return array result of the operation
		*/
		public static function calculate_one_array($elements);
	}
	
?>