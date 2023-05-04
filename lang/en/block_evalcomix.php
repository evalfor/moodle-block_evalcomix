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

$string['pluginname'] = 'EvalCOMIX - FLOASS';
$string['evalcomix'] = 'EvalCOMIX - FLOASS';
$string['blocksettings'] = 'Configuration';
$string['blockstring'] = 'Content of EvalCOMIX';
$string['instruments'] = 'Tool Management';
$string['evaluation'] = 'Assess Activities';
$string['evalcomix:view'] = 'View EvalCOMIX';
$string['evalcomix:edit'] = 'Edit EvalCOMIX';
$string['whatis'] = 'EvalCOMIX allows the design and management of assessment tools (rating scales, rubrics, etc) to be used to assess forum, glossaries, database, wiki and tasks.<br>The assessment with these tools can be carried out by teachers (teacher assessment), or students (self-assessment, peer-assessment). For more information, please consult the Manual.For more information you can consult the <a href="' . $CFG->wwwroot.'/lib/evalcomix/manual.pdf">Manual</a>';
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
$string['evalcomixgradesmart'] = 'EvalCOMIX grade';
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
$string['confirmdeletetool'] = 'Are you sure you want to delete the assessment tool?';
$string['finalgradecalculation'] = 'Calculation of the final grade';
$string['method'] = 'Method';
$string['weightedaveragewithallvalues'] = 'Weighted average of all grades';
$string['weightedaveragesmart'] = 'Weighted average of non-extreme grades';
$string['confirmdeleteassessment'] = 'Are you sure you want to delete the assessment?';
$string['mildoutlier'] = 'Mild outlier';
$string['extremeoutlier'] = 'Extreme outlier';
$string['overvaluation'] = 'Overvaluation';
$string['undervaluation'] = 'Undervaluation';

/* ----------------------------- HELP ----------------------------- */
$string['method_help'] = 'This option allows you to set how the final grade for the activity will be calculated. If the option "Weighted average of all grades" is selected, the final grade will be calculated by taking the weighted average of the grades awarded in each evaluation modality without ignoring any. On the other hand, if the "Weighted average of non-extreme values" option is selected, those ratings that are considered extreme will be eliminated from the calculation because they exceed certain levels. For more information read the EvalCOMIX manual';
$string['timeopen_help'] = 'Peer Assessment is not included actually in EvalCOMIX grade because the period of assessment has not ended yet.';
$string['evalcomixgrade_help'] = 'Weighted average of evalcomix assessments';
$string['evalcomixgradesmart_help'] = 'Weighted average of evalcomix assessments';
$string['moodlegrade_help'] = 'Grade assigned from Moodle';
$string['finalgrade_help'] = 'Arithmetic average of EvalCOMIX final grade and Moodle final grade';
$string['teachermodality_help'] = 'This tool will be the assessment tool used by teachers to grade students in this activity.';
$string['pon_EP_help'] = 'It is the percentage that the grade obtained by the teacher assessment tool on the final grade.';
$string['selfmodality_help'] = 'It is the self-assessment tool used by students to review and grade their own task.';
$string['pon_AE_help'] = 'It is the percentage of the mark obtained by the self-assessment tool has on the final grade.';
$string['peermodality_help'] = 'This is the assessment tool used by students to assess their peers’ activity.';
$string['pon_EI_help'] = 'It is the percentage of the mark obtained by the peer assessment tool on the final grade.';
$string['availabledate_AE_help'] = 'Date from which students can evaluate their activity.';
$string['timedue_AE_help'] = 'Deadline until which students can evaluate their activity.';
$string['availabledate_EI_help'] = 'Date from which students can evaluate the activity carried out by their classmates.';
$string['timedue_EI_help'] = 'Deadline until which students will be able to evaluate their classmates.';
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
$string['noconfigured'] = 'No configuration';
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
$string['serverurlinfo'] = 'Here you need to enter the URL for your EvalCOMIX server. Do not include a trailing slash. ie: http://localhost/evalcomix';
$string['validationheader'] = 'Settings validation';
$string['validationinfo'] = 'Please save the settings before clicking the validation button. If the validation is unsuccessful, recheck the url and token';
$string['validationbutton'] = 'Validate Settings';
$string['error_conection'] = 'Validation failed: please check that the settings you have entered match with the settings in EvalCOMIX';
$string['valid_conection'] = 'Successfully completed validation';
$string['simple_error_conection'] = 'Valid URL. But there is a error: ';
$string['token'] = 'Token';
$string['tokeninfo'] = 'Token generated by EvalCOMIX Server. To obtain it, access the EvalCOMIX Server control panel to register this Moodle as a LMS allowed.';

$string['alwaysvisible_EI_help'] = 'If it is not checked, students can only see peer-assessments after limit date. If it is checked, students can always see their peer-assessments';
$string['alwaysvisible_EI'] = 'Always visible';
$string['whoassesses_EI'] = 'Who assesses';
$string['anystudent_EI'] = 'Any student';
$string['groups_EI'] = 'Groups';
$string['specificstudents_EI'] = 'Specific students';
$string['whoassesses_EI_help'] = 'This option allows you to control which students will participate in the peer evaluation.

If the option "'. $string['anystudent_EI'].'" Is selected, each student will be able to evaluate any of their classmates.

If the option "'. $string['groups_EI'].'" Is selected, the configuration of groups and groupings of the activity will be respected.

If you select the option "'. $string['specificstudents_EI'].'" You can indicate who will evaluate and who will be evaluated. This option will be disabled if "Evaluation of work teams" is selected';
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

// Graphics.
$string['taskgraphic'] = 'Task Graphic';
$string['studentgraphic'] = 'Student Graphic';
$string['activity'] = 'Activity';
$string['selectactivity'] = 'Select an activity';
$string['selectstudent'] = 'Select a student';
$string['selectgroup'] = 'Select a group';
$string['studentmod'] = 'Student';
$string['groupmod'] = 'Group';
$string['classmod'] = 'Class';
$string['nostudents'] = 'No students with datas';
$string['nostudentsgroup'] = 'No students with datas in group';

$string['toolmanagerviewed'] = 'Tool manager viewed';
$string['activityassessorviewed'] = 'Assessment manager viewed';
$string['tooldeleted'] = 'Tool deleted';
$string['studentassessed'] = 'Student assessed';
$string['graphicsviewed'] = 'Graphics viewed';
$string['configurationviewed'] = 'Configuration viewed';

// Tool Editor.
$string['selecttool'] = 'Select tool type to create';
$string['accept'] = 'Accept';
$string['alertdimension'] = 'Al least, must exist a dimension';
$string['alertsubdimension'] = 'Al least, must exist a subdimension';
$string['alertatrib'] = 'At least, must exist an attribute';
$string['rubricremember'] = 'REMEMBER: REPEATED values should not exist';
$string['importfile'] = 'Import file';
$string['noatrib'] = 'Negative Attribute';
$string['yesatrib'] = 'Positive Attribute';

$string['comments'] = 'Comments';
$string['grade'] = 'Grade';
$string['nograde'] = 'No Grade';
$string['alertsave'] = "Assessment saved saccessfully. If you want, can close this windows";

$string['add_comments'] = 'Turn Comments';
$string['checklist'] = 'Check List';
$string['ratescale'] = 'Rating Scale';
$string['listrate'] = 'Check List + Rating Scale';
$string['rubric'] = 'Rubric';
$string['differentail'] = 'Semantic Differential';
$string['mix'] = 'Mixed Tool';
$string['argument'] = 'Argumentative Assessment';
$string['numdimensions'] = 'Nº Dimensions:';
$string['numvalues'] = 'Nº of Values:';
$string['totalvalue'] = 'Global Assessment';
$string['dimension'] = 'Dimension:';
$string['subdimension'] = 'Subdimension:';
$string['numsubdimension'] = 'Nº Subdimensions:';
$string['numattributes'] = 'Nº of Attributes:';
$string['attribute'] = 'Attributes';
$string['porvalue'] = 'Percentage Value:';
$string['value'] = 'Value';
$string['values'] = 'Values';
$string['globalvalue'] = 'DIMENSIÓN GLOBAL ASSESSMENT:';
$string['import'] = 'Import';
$string['novalue'] = 'Negative Value';
$string['yesvalue'] = 'Positive Value';
$string['idea'] = 'IDEA Y DIRECTION';
$string['design'] = 'DESIGN';
$string['develop'] = 'DEVELOPMENT';
$string['translation'] = 'TRANSLATION';
$string['colaboration'] = 'COOPERATE';
$string['license'] = 'LICENSE';
$string['addtool'] = 'Add Assessment Tool';
$string['title'] = 'Title';
$string['titledim'] = 'Dimension';
$string['titlesubdim'] = 'Subdimension';
$string['titleatrib'] = 'Attribute';
$string['titlevalue'] = 'Value';
$string['no'] = 'No';
$string['yes'] = 'Yes';
$string['observation'] = 'Comments';
$string['view'] = 'Close Previous View';

$string['windowselection'] = 'Selection Window';
$string['selectfile'] = 'Select the file';
$string['upfile'] = 'Upload file';
$string['cancel'] = 'Cancel';

$string['savedsaccessfully'] = 'This tool has been saved successfully';
$string['ADimension'] = 'This field cannot be void. \"Nº of Dimensions\" must be a number greater than 0 and \"Nº of Values\" a number greater than o equal to 2';
$string['ATotal'] = 'This field cannot be void. \"Nº of Values\" must be a number greater than or equal to  2';
$string['ASubdimension'] = 'This field cannot be void. \"Nº Subdimensions\" must be a number greater than 0 and \"Nº of Values\" greater than or equal to 2';
$string['AAttribute'] = 'This field cannot be void. Please, insert a number greater than 0';
$string['ADiferencial'] = '\"Nº of Attributes\" must be greater than 0. \"Nº of Values\" must be ODD';
$string['ErrorFormato'] = 'The file is void or the format is wrong"';
$string['ErrorAcceso'] = 'The file cannot be accessed';
$string['ErrorExtension'] = 'Wrong format. The extension must be \"evx\"';
$string['ErrorSaveTitle'] = 'Error: The Title cannot be void';
$string['ErrorSaveTools'] = 'Error: You must select at least one assessment tool';

$string['TSave'] = 'Save';
$string['TImport'] = 'Import';
$string['TExport'] = 'Export';
$string['TAumentar'] = 'Increase font size';
$string['TDisminuir'] = 'Reduce font size';
$string['TView'] = 'Previous view';
$string['TPrint'] = 'Print';
$string['THelp'] = 'Help';
$string['TAbout'] = 'About of';

$string['mixed_por'] = 'Weight in the final grade';

$string['handlerofco'] = 'Learning Outcome and Competency Management';
$string['competencies'] = 'Competencies';
$string['outcomes'] = 'Outcomes';
$string['compidnumber'] = 'Code';
$string['compshortname'] = 'Short name';
$string['compdescription'] = 'Description';
$string['comptypes'] = 'Types of competency';
$string['comptype'] = 'Type of competency';
$string['newcomp'] = 'Add Competency';
$string['newoutcome'] = 'Add Outcome';
$string['newcomptype'] = 'Add Type of Competency';
$string['compreport'] = 'Development report';
$string['compandout'] = 'Competencies and learning outcomes';
$string['uploadcompetencies'] = 'Import competencies and outcomes';
$string['uploadcompetencies_help'] = 'Competencies and outcomes may be uploaded via text file. The format of the file should be as follows:

* Each line of the file contains one record
* Each record is a series of data separated by commas (or other delimiters)
* The first record contains a list of fieldnames defining the format of the rest of the file
* Required fieldnames are idnumber, shortname, outcome';
$string['idnumberduplicate'] = 'Duplicate idnumber value';
$string['invalidoutcome'] = 'Invalid outcome value. It must be 0 or 1';
$string['invalididnumberupload'] = 'Invalid idnumber value. Size must be less than 100';
$string['missingidnumber'] = 'idnumber column is missing';
$string['missingshortname'] = 'shortname column is missing';
$string['missingoutcome'] = 'outcome column is missing';
$string['ignored'] = 'Ignored';
$string['errors'] = 'Errors';
$string['importresult'] = 'Import result';
$string['uploadcompetenciespreview'] = 'Upload competencies preview';
$string['choicecompetency'] = 'Choice a competency';
$string['choiceoutcome'] = 'Choice an outcome';
$string['associatecompandout'] = 'Associate competences and outcomes';
$string['allstudens'] = 'All students';
$string['onestudent'] = 'Specific student';
$string['onegroup'] = 'Specific group';
$string['selectcomptype'] = 'Choice type of competency';
$string['assessmentreport'] = 'Report of Assessments';
$string['unrealized'] = 'Unrealized';
$string['doneoutofrange'] = 'Has done the self-assessment or peer assessment but out of range';
$string['doneoutofrangecomments'] = 'Has performed the self-assessment or peer assessment, out of range and provides feedback';
$string['donewithinrange'] = 'Has performed the self-assessment or peer assessment within range';
$string['donewithinrangecomments'] = 'Has completed the self-assessment or peer assessment within range and provides feedback';
$string['threshold'] = 'Threshold';
$string['threshold_help'] = 'Limit from which a student\'s score is considered overevaluation or underevaluation. For example, if the threshold is set to 15 and the teacher\'s grade (or alternatively, the average of the peer evaluation) is 50, then any student score that exceeds 65 (50+15) points will be considered overevaluation and those below 35 points (50-15)  will be considered underevaluation.';
$string['assessmentreporttotalAE'] = 'Total SA (Over 10)';
$string['assessmentreporttotalEI'] = 'Total PA (Over 10)';
$string['AE'] = 'SA';
$string['EI'] = 'PA';
$string['evaluationexporthelp'] = 'Report discrepancy results between self-assessment and peer assessments with respect to criteria (threshold for over- or under-assessment)';
$string['evaluationandreports'] = 'Assessment and reports';
$string['workteams'] = 'Work teams';
$string['workteamsassessments'] = 'Assessment of work teams';
$string['assignteamcoordinators'] = 'Assign team coordinators';
$string['workteamsassessments_help'] = 'If you activate this option, a coordinator can be appointed to represent the group.

If there is a **Teacher Assessment - TA**, the teachers will only be able to evaluate the coordinators and that evaluation will be assigned to each member of their group.

If there is a **Self-Assessment – SA**, only the coordinator can self-assess and his evaluation will be assigned to each member of his group.

If there is **Peer Assessment – PA**, students will only be able to evaluate the coordinators of each group and each evaluation will be assigned to each member of the group.

Students who are not in any group will not receive an assessment. Groups that do not have a coordinator assigned will not receive any evaluation and will not be able to evaluate';
$string['selectcoordinator'] = 'Select coordinator';
$string['alertnogroup'] = 'No groups have been created in the course yet. To create them you must access the following section:';
$string['activityassessed'] = 'Disabled because some student has already been assessed';
$string['coordinatorassessed'] = 'Currently, there are coordinators who have already received some evaluation. Those who have received an evaluation cannot be replaced. If you want to replace them, you\'ll need to delete the assessments first.';
$string['confirmdisabledworkteams'] = 'Evaluations have already been carried out in this activity. If you disable this option and save your changes, all such assessments will be deleted and cannot be recovered. Are you sure you want to disable the option?';
$string['crontaskdevdata'] = 'Task to download data for development report';
$string['reporttimeleft'] = 'The report data is being downloaded in the background. {$a} left for full download';
$string['reporttimeleftdisabled'] = 'There is data waiting to be downloaded in the background, but the task is disabled. Contact the administrator.';
$string['inforeporttime'] = 'The report data is downloaded in the background. If when selecting a filter they are not yet available, please be patient.';
