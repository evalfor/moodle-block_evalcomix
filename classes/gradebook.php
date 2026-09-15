<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * class for evalcomix_gradebook_manager
 *
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>
 */
class evalcomix_gradebook_manager {
    /** @var context */
    private $context;

    /** @var stdClass */
    private $user;

    /** @var int */
    private $courseid;

    /** @var int */
    private $grd;
    /** @var stdClass */
    private $evalcomix;
    /** @var stdClass */
    private $reportevalcomix;
    /** @var array */
    private $finalgrades;
    /** @var array */
    private $blocks = [];
    /** @var array */
    private $cms = [];

    /**
     * Constructor. Sets local copies of user preferences and initialises grade_tree.
     * @param object $context
     * @param object $user
     * @param int $courseid
     * @param int $grd
     * @param object $evalcomix
     * @param object $reportevalcomix
     */
    public function __construct(
        context $context,
        stdClass $user,
        int $courseid,
        int $grd,
        stdClass $evalcomix,
        $reportevalcomix
    ) {
        $this->context = $context;
        $this->user = $user;
        $this->courseid = $courseid;
        $this->grd = $grd;
        $this->evalcomix = $evalcomix;
        $this->reportevalcomix = $reportevalcomix;
    }

    /**
     * Execute
     */
    public function execute(): bool {
        $this->load_modules();
        $this->load_evalcomix_grades();

        $reportgrader = $this->create_grade_report();
        $updatedcount = 0;
        foreach ($this->reportevalcomix->users as $userid => $user) {
            $updated = $this->process_user($userid, $reportgrader);
            if ($updated) {
                $updatedcount++;
            }
        }

        $this->update_sendgradebook_status();
        return ($updatedcount > 0);
    }

    /**
     * Load modules
     */
    private function load_modules(): void {
        global $DB;
        $modules = $DB->get_records('modules', []);
        $coursemodules = $DB->get_records(
            'course_modules',
            ['course' => $this->courseid]
        );

        foreach ($modules as $module) {
            $this->blocks[$module->id] = $module->name;
        }

        foreach ($coursemodules as $cm) {
            $modulename = $this->blocks[$cm->module];
            $this->cms[$modulename][$cm->instance] = $cm->id;
        }
    }

    /**
     * Load evalcomix grades
     */
    private function load_evalcomix_grades(): void {
        global $CFG;
        require_once(
            $CFG->dirroot .
            '/blocks/evalcomix/classes/evalcomix_grades.php'
        );

        $this->finalgrades = block_evalcomix_grades::get_grades($this->courseid);
    }

    /**
     * Create grade report
     */
    private function create_grade_report(): grade_report_grader {
        global $CFG;
        require_once($CFG->dirroot . '/grade/report/grader/lib.php');
        $this->reportevalcomix->load_users(false);

        $reportgrader = new grade_report_grader(
            $this->courseid,
            null,
            $this->context
        );

        $reportgrader->load_users(true);
        $reportgrader->load_final_grades();

        return $reportgrader;
    }

    /**
     * Process user
     */
    private function process_user(
        int $userid,
        grade_report_grader $reportgrader
    ): bool {

        [$altered, $unknown] = $this->get_grade_visibility_data(
            $userid,
            $reportgrader
        );
        $result = false;
        foreach ($reportgrader->gtree->items as $itemid => $unused) {
            $item = $reportgrader->gtree->items[$itemid];

            if (!isset($reportgrader->grades[$userid][$item->id])) {
                continue;
            }

            $grade = $reportgrader->grades[$userid][$item->id];
            $gradeval = $this->resolve_grade_value(
                $itemid,
                $grade,
                $altered,
                $unknown
            );

            if ($grade->grade_item->is_external_item()) {
                $updated = $this->process_external_item($gradeval, $grade, $userid);
                if ($updated) {
                    $result = true;
                }
            }
        }
        return $result;
    }

    /**
     * get_grade_visibility_data
     */
    private function get_grade_visibility_data(
        int $userid,
        grade_report_grader $reportgrader
    ): array {

        if ($reportgrader->canviewhidden) {
            return [[], []];
        }

        $affected = grade_grade::get_hiding_affected(
            $reportgrader->grades[$userid],
            $reportgrader->gtree->get_items()
        );

        return [
            $affected['altered'],
            $affected['unknown'],
        ];
    }

    /**
     * resolve grade value
     */
    private function resolve_grade_value(
        int $itemid,
        $grade,
        array $altered,
        array $unknown
    ) {
        if (in_array($itemid, $unknown)) {
            return null;
        }

        if (array_key_exists($itemid, $altered)) {
            return $altered[$itemid];
        }

        return $grade->finalgrade;
    }

    /**
     * process external item
     */
    private function process_external_item($gradeval, $grade, $userid): bool {
        global $CFG;
        require_once($CFG->dirroot . '/blocks/evalcomix/classes/evalcomix_tasks.php');
        $finalgrades = $this->finalgrades;
        $courseid = $this->courseid;
        $cms = $this->cms;
        $tasks = block_evalcomix_tasks::get_tasks_by_courseid($courseid);
        $updated = false;
        switch ($this->grd) {
            case 1:
                if ($this->evalcomix->sendgradebook == 0) {
                    require(
                        $CFG->dirroot .
                        '/blocks/evalcomix/assessment/gradeevx.php'
                    );
                }
                break;

            case 2:
                if (
                    isset($gradeval) &&
                    $this->evalcomix->sendgradebook == 1
                ) {
                    include(
                        $CFG->dirroot .
                        '/blocks/evalcomix/assessment/undone_evx.php'
                    );
                }
                break;

            case 3:
                if (isset($gradeval)) {
                    include(
                        $CFG->dirroot .
                        '/blocks/evalcomix/assessment/undone_evx.php'
                    );
                }

                include(
                    $CFG->dirroot .
                    '/blocks/evalcomix/assessment/gradeevx.php'
                );
                break;
        }
        return $updated;
    }

    /**
     * update
     */
    private function update_sendgradebook_status(): void {
        global $DB;
        $record = [
            'id' => $this->evalcomix->id,
            'courseid' => $this->evalcomix->courseid,
            'viewmode' => $this->evalcomix->viewmode,
        ];

        if ($this->grd == 1) {
            $record['sendgradebook'] = 1;
        } else if ($this->grd == 2) {
            $record['sendgradebook'] = 0;
        } else {
            return;
        }

        $DB->update_record(
            'block_evalcomix',
            (object)$record
        );
    }
}
