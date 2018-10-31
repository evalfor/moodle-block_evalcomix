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

$string['pluginname'] = 'EvalCOMIX';
$string['evalcomix'] = 'EvalCOMIX';
$string['blocksettings'] = 'Configuration';
$string['blockstring'] = 'Content of EvalCOMIX';
$string['instruments'] = 'Tool Management';
$string['evaluation'] = 'Assess Activities';
$string['evalcomix:view'] = 'View EvalCOMIX';
$string['evalcomix:edit'] = 'Edit EvalCOMIX';
$string['whatis'] = 'EvalCOMIX allows the design and management of assessment tools (rating scales, rubrics, etc) to be used to assess forum, glossaries, database, wiki and tasks.<br>
The assessment with these tools can be carried out by teachers (teacher assessment), or students (self-assessment, peer-assessment). For more information, please consult the Manual.
For more information you can consult the <a href="' . $CFG->wwwroot.'/lib/evalcomix/manual.pdf">Manual</a>';
$string['selfmodality'] = 'Self Assessment - SA ';
$string['peermodality'] = 'Peer Assessment - PA ';
$string['teachermodality'] = 'Teacher Assessment - TA ';
$string['selfmod'] = 'Self Assessment';
$string['peermod'] = 'Peer Assessment';
$string['teachermod'] = 'Teacher Assessment';
$string['nuloption'] = 'Selection of Assessment Tool';
$string['grades'] = 'Grades: ';
$string['selinstrument'] = 'Assessment Planning';
$string['pon_EP'] = 'Weigthting - TA';
$string['pon_AE'] = 'Weigthting - SA';
$string['pon_EI'] = 'Weigthting - PA';
$string['availabledate_EI'] = 'PA - available from: ';
$string['availabledate_AE'] = 'SA - available from: ';
$string['ratingsforitem'] = 'Breakdown of calification';
$string['modality'] = 'Modality';
$string['grade'] = 'Calification';
$string['weighingfinalgrade'] = 'Weight in the final grade';
$string['finalgrade'] = 'Final grade';
$string['nograde'] = 'No Grade';
$string['timeopen'] = 'Period of assessment has not ended';
$string['designsection'] = 'Design and management of assessment tool';
$string['assesssection'] = 'Assess Activities';
$string['counttool'] = 'Count of tools';
$string['newtool'] = 'New Tool';
$string['open'] = 'Open';
$string['view'] = 'View';
$string['delete'] = 'Delete';
$string['title'] = 'Title';
$string['type'] = 'Type';
$string['anonymous_EI'] = 'Anonymous - PA';
$string['details'] = 'Details';
$string['assess'] = 'Assess';
$string['set'] = 'Set';
$string['ratingsforitem'] = 'Ratings';
$string['modality'] = 'Modality';
$string['grade'] = 'Grade';
$string['weighingfinalgrade'] = 'Weighing on final grade';
$string['evalcomixgrade'] = 'EvalCOMIX grade';
$string['moodlegrade'] = 'Moodle grade';
$string['graphics'] = 'Graphics';
$string['timedue_AE'] = 'SA - deadline';
$string['timedue_EI'] = 'PA - deadline';
$string['january'] = 'January';
$string['february'] = 'February';
$string['march'] = 'March';
$string['april'] = 'April';
$string['may'] = 'May';
$string['june'] = 'June';
$string['july'] = 'July';
$string['august'] = 'August';
$string['september'] = 'September';
$string['october'] = 'October';
$string['november'] = 'November';
$string['december'] = 'December';
$string['save'] = 'Save';
$string['cancel'] = 'Cancel';
$string['evaluate'] = 'Assess';
$string['scale'] = 'Scale';
$string['list'] = 'CheckList';
$string['listscale'] = 'Listscale';
$string['rubric'] = 'Rubric';
$string['mixed'] = 'Mixed';
$string['differential'] = 'Differential';
$string['argumentset'] = 'Argumentative Assessment';
$string['whatis'] = 'Management of assessment tools';
$string['gradeof'] = 'Grade of ';
/* ----------------------------- HELP ----------------------------- */
$string['timeopen_help'] = 'Peer Assessment is not included actually in EvalCOMIX grade because the period of assessment has not ended yet.';
$string['evalcomixgrade_help'] = 'Weighted average of evalcomix assessments';
$string['moodlegrade_help'] = 'Grade assigned from Moodle';
$string['finalgrade_help'] = 'Arithmetic average of EvalCOMIX final grade and Moodle final grade';
$string['teachermodality_help'] = 'This tool will be the assessment tool used by teachers to grade students in this activity.';
$string['pon_EP_help'] = 'It is the percentage that the grade obtained by the teacher assessment tool on the final grade.';
$string['selfmodality_help'] = 'It is the self-assessment tool used by students to review and grade their own task.';
$string['pon_AE_help'] = 'It is the percentage of the mark obtained by the self-assessment tool has on the final grade.';
$string['peermodality_help'] = 'This is the assessment tool used by students to assess their peers’ activity.';
$string['pon_EI_help'] = 'It is the percentage of the mark obtained by the peer assessment tool on the final grade.';
$string['availabledate_AE_help'] = '';
$string['timedue_AE_help'] = '';
$string['availabledate_EI_help'] = '';
$string['timedue_EI_help'] = '';
$string['anonymous_EI_help'] = 'It indicates if the students will can know which peers have assess them.';
$string['whatis_help'] = 'EvalCOMIX allows the design and management of assessment tools (rating scales, rubrics, etc) to be used to assess forum, glossaries, database, wiki and tasks.<br>
The assessment with these tools can be carried out by teachers (teacher assessment), or students (self-assessment, peer-assessment). For more information, please consult the Manual.
For more information you can consult the <a href="../manual.pdf">Manual</a>';
$string['selinstrument_help'] = 'Refer to the <a href="../manual.pdf">Manual</a> for more information about setting an EvalCOMIX activity.';
/* --------------------------- END HELP --------------------------- */
$string['profile_task_by_student'] = 'Chart task by student';
$string['profile_task_by_group'] = 'Chart task by group';
$string['profile_task_by_course'] = 'Chart task by course';
$string['profile_student_by_teacher'] = 'Chart student body by teacher';
$string['profile_student_by_group'] = 'Chart student body by peer';
$string['profile_attribute_by_student'] = 'Chart attribute by studen';
$string['profile_attribute_by_group'] = 'Chart attribute by group';
$string['profile_attribute_by_course'] = 'Chart attribute by course';
$string['titlegraficbegin'] = 'Init';
$string['no_datas'] = 'No datas';

$string['linktoactivity'] = 'Clic to see the activity';
$string['sendgrades'] = 'Send EvalCOMIX Grades to the gradebook';
$string['deletegrades'] = 'Delete EvalCOMIX scores from the gradebook';
$string['updategrades'] = 'Send EvalCOMIX last Grades';
$string['gradessubmitted'] = 'EvalCOMIX scores submitted to gradebook';
$string['gradesdeleted'] = 'EvalCOMIX scores deleted from gradebook';

$string['confirm_add'] = 'This operation will modificate the gradebook.\nIt will carry out the average between Moodle grades and EvalCOMIX one. Do you confirm that you wish to continue?';
$string['confirm_update'] = 'The gradebook has been modificated previously.\nThis operation will submit last EvalCOMIX grades. Do you confirm that you wish to continue?';
$string['confirm_delete'] = 'This operation will modificate the gradebook of Moodle.\nIt will delete EvalCOMIX grades from gradebook of Moodle.\nDo you confirm that you wish to continue?';
$string['noconfigured'] = 'No conguration';
$string['gradebook'] = 'Grade Book';

$string['poweredby'] = 'Powered by:<br><span style="font-weight:bold; font-size: 10pt">EVALfor</span><br> Research Group';

$string['alertnotools'] = 'Assessment tools has not been generated yet. To create it, have to access to the next section';
$string['alertjavascript'] = 'Must enable Javascript in your browser';
$string['studentwork1'] = 'Work completed by the student';
$string['studentwork2'] = ' in the activity ';
$string['evalcomix:addinstance'] = 'Add a new EvalCOMIX block';
$string['evalcomix:myaddinstance'] = 'Add a new EvalCOMIX block';

$string['reportsection'] = 'Reports';
$string['notaskconfigured'] = 'There is not configured activities with';
$string['studendtincluded'] = 'Selfassessed Student to include';
$string['selfitemincluded'] = 'Items of Autoevaluación to include';
$string['selftask'] = 'Detailed Reports of Selfassessmens';
$string['format'] = 'Format';
$string['export'] = 'Export';
$string['xls'] = 'Excel';
$string['excelexport'] = 'Export to Excel Spreadsheet';
$string['selectallany'] = 'Select all/none';
$string['nostudentselfassessed'] = 'There is not assessed student';

// Settings.
$string['admindescription'] = 'Configure your EvalCOMIX server settings. Make <strong>sure</strong> that the values you enter here are right. Otherwise the integration might not work.';
$string['adminheader'] = 'EvalCOMIX server configuration';
$string['serverurl'] = 'EvalCOMIX server URL:';
$string['serverurlinfo'] = 'Here you need to enter the URL for your EvalCOMIX server. ie: http://localhost/evalcomix';
$string['validationheader'] = 'Settings validation';
$string['validationinfo'] = 'Before you save your settings, please press the button to validate them with the EvalCOMIX server. If the validation is correct, save these settings. If not, please check that the settings you have entered match with the values in the server';
$string['validationbutton'] = 'Validate Settings';
$string['error_conection'] = 'Validation failed: please check that the settings you have entered match with the settings in EvalCOMIX';
$string['valid_conection'] = 'Successfully completed validation';
$string['simple_error_conection'] = 'Valid URL. But there is a error: ';

$string['alwaysvisible_EI_help'] = 'If it is not checked, students can only see peer-assessments after limit date. If it is checked, students can always see their peer-assessments';
$string['alwaysvisible_EI'] = 'Always visible';
$string['whoassesses_EI'] = 'Who assesses';
$string['anystudent_EI'] = 'Any student';
$string['groups_EI'] = 'Groups';
$string['specificstudents_EI'] = 'Specific students';
$string['whoassesses_EI_help'] = '';
$string['assignstudents_EI'] = 'Assign students';
$string['assess_students'] = 'Assess students';
$string['studentstoassess'] = 'Students to assess';
$string['search'] = 'Search';
$string['add_delete_student'] = 'Add/Delete Students';
$string['back'] = 'Back';
$string['potentialstudents'] = 'Potential Students';

$string['settings'] = 'Settings';
$string['activities'] = 'Activities';
$string['edition'] = 'Edition';
$string['settings_description'] = 'In this section will be configured the assessment table';

$string['crontask'] = 'Task for updating scores in EvalCOMIX gradebook';

// Privacy.
$string['privacy:metadata:block_evalcomix_allowedusers'] = 'Information about the Specific Students option in the Peer Evaluation mode. It stores what student is the evaluator and which is the evaluated.';
$string['privacy:metadata:block_evalcomix_allowedusers:assessorid'] = 'The ID of the evaluating user.';
$string['privacy:metadata:block_evalcomix_allowedusers:studentid'] = 'The ID of the user evaluated.';
$string['privacy:metadata:block_evalcomix_assessments'] = 'Information about the grade of each student in each evaluation modality.';
$string['privacy:metadata:block_evalcomix_assessments:assessorid'] = 'The ID of the evaluating user.';
$string['privacy:metadata:block_evalcomix_assessments:studentid'] = 'The ID of the student evaluated.';
$string['privacy:metadata:block_evalcomix_assessments:grade'] = 'Grade.';
$string['privacy:metadata:block_evalcomix_assessments:timemodified'] = 'Time at which the grade was made.';
$string['privacy:metadata:block_evalcomix_grades'] = 'Information about the average of all the ratings given to a user in each evaluation modality';
$string['privacy:metadata:block_evalcomix_grades:userid'] = 'The Id of the user evaluated';
$string['privacy:metadata:block_evalcomix_grades:finalgrade'] = 'Average of all the ratings given to a user in each evaluation modality';
$string['privacy:metadata:block_evalcomix:tableexplanation'] = 'EvalCOMIX block information is stored here.';