<?php
/**
 * Define cron task.
 *
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>
 */

defined('MOODLE_INTERNAL') || die();

$tasks = array(                                                                                                                     
    array(                                                                                                                          
        'classname' => 'block_evalcomix\task\cron_task',                                                                            
        'blocking' => 0,                                                                                                            
        'minute' => '*/15',                                                                                                            
        'hour' => '*',                                                                                                              
        'day' => '*',                                                                                                               
        'dayofweek' => '*',                                                                                                         
        'month' => '*'                                                                                                              
    )
);