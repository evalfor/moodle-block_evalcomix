<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */

$capabilities = array(
	'block/evalcomix:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'user' => CAP_ALLOW
        ),

        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),
	
    'block/evalcomix:view' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(            	    
	    'editingteacher' => CAP_ALLOW,
			'student' => CAP_ALLOW,
			'teacher' => CAP_ALLOW,
			'manager' => CAP_ALLOW,
			'coursecreator' => CAP_ALLOW
        )
    ),
	
	'block/evalcomix:assessed' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(            	    
			'student' => CAP_ALLOW
        )
    ),
	
	'block/evalcomix:edit' => array(
	'captype' => 'read',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(            	    
	    'editingteacher' => CAP_ALLOW,
			'teacher' => CAP_ALLOW,
			'manager' => CAP_ALLOW,
			'coursecreator' => CAP_ALLOW
        )
    ),
	
	'block/evalcomix:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,

        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),

        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),
	
);

?>
