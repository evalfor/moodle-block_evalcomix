<?php

/**
 * Class for cron
 *
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>
 */

namespace block_evalcomix\task;

defined('MOODLE_INTERNAL') || die();

class cron_task extends \core\task\scheduled_task {      
    public function get_name() {
        return get_string('crontask', 'block_evalcomix');
    }
                                                                     
    public function execute() { 
        global $CFG;
        require_once($CFG->dirroot . '/blocks/evalcomix/assessment/lib.php');
        block_evalcomix_recalculate_grades();
    }                                                                                                                               
}