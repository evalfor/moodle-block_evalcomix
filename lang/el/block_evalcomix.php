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
$string['blocksettings'] = 'Ρύθμιση παραμέτρων συστήματος';
$string['blockstring'] = 'Περιεχόμενο EvalCOMIX';
$string['instruments'] = 'Διαχείριση εργαλείου';
$string['evaluation'] = 'Αξιολόγηση Δραστηριοτήτων';
$string['evalcomix:view'] = 'Προβολή EvalCOMIX';
$string['evalcomix:edit'] = 'Επεξεργασία EvalCOMIX';
$string['whatis'] = 'Το EvalCOMIX επιτρέπει τον σχεδιασμό και τη διαχείριση εργαλείων αξιολόγησης (κλίμακες αξιολόγησης, ρουμπρίκες, κ.λπ.) που θα χρησιμοποιηθούν για την αξιολόγηση ομάδων συζήτησης (forum), για γλωσσάρια, βάσεις δεδομένων, wikis και δραστηριότητες.<br> Η αξιολόγηση με τη χρήση αυτών των εργαλείων μπορεί να λάβει χώρα από εκπαιδευτικούς (Αξιολόγηση εκπαιδευτικού), ή μαθητές (αυτοαξιολόγηση, αξιολόγηση από ομότιμους). Για περισσότερες πληροφορίες, παρακαλήστε να συμβουλευτείτε τον Οδηγό Χρήσης. Περισσότερες πληροφορίες για βρείτε στο <a href="' . $CFG->wwwroot.'/lib/evalcomix/manual.pdf">Manual</a>';
$string['selfmodality'] = 'Αυτοαξιολόγηση - ΑΑ ';
$string['peermodality'] = 'Αξιολόγηση από Ισότιμους - ΑΙ ';
$string['teachermodality'] = 'Αξιολόγηση από Εκπαιδευτικούς - ΑΕ';
$string['selfmod'] = 'Αυτοαξιολόγηση';
$string['peermod'] = 'Αξιολόγηση από ισότιμους';
$string['teachermod'] = 'Αξιολόγηση από εκπαιδευτικούς';
$string['nuloption'] = 'Επιλογή εργαλείου αξιολόγησης';
$string['grades'] = 'Βαθμολογία: ';
$string['selinstrument'] = 'Σχεδιασμός αξιολόγησης';
$string['pon_EP'] = 'Βαρύτητα - ΑΕ';
$string['pon_AE'] = 'Βαρύτητα - ΑΑ';
$string['pon_EI'] = 'Βαρύτητα - ΑΙ';
$string['availabledate_EI'] = 'ΑΙ - διαθέσιμη από: ';
$string['availabledate_AE'] = 'ΑΑ - διαθέσιμη από: ';
$string['ratingsforitem'] = 'Κατανομή της βαθμολογίας';
$string['modality'] = 'Μορφή';
$string['grade'] = 'Βαθμολογία';
$string['weighingfinalgrade'] = 'Βαρύτητα στον τελικό βαθμό';
$string['finalgrade'] = 'Τελικός βαθμός';
$string['nograde'] = 'Καμία βαθμολογία';
$string['timeopen'] = 'Η περίοδος της αξιολόγησης δεν τελείωσε.';
$string['designsection'] = 'Σχεδιασμός και διαχείριση του εργαλείου αξιολόγησης';
$string['assesssection'] = 'Αξιολόγηση δραστηριοτήτων';
$string['counttool'] = 'Αρίθμηση εργαλείων';
$string['newtool'] = 'Νέο εργαλείο';
$string['open'] = 'Άνοιγμα';
$string['view'] = 'Προβολή';
$string['delete'] = 'Διαγραφή';
$string['title'] = 'Τίτλος';
$string['type'] = 'Είδος';
$string['anonymous_EI'] = 'Ανώνυμο - ΑΙ';
$string['details'] = 'Λεπτομέρειες';
$string['assess'] = 'Αξιολογήστε';
$string['set'] = 'Καθορισμός';
$string['ratingsforitem'] = 'Βαθμολογίες';
$string['modality'] = 'Μορφή';
$string['grade'] = 'Βαθμολογία';
$string['weighingfinalgrade'] = 'Βαρύτητα της τελικής βαθμολογίας';
$string['evalcomixgrade'] = 'Βαθμολογία EvalCOMIX';
$string['moodlegrade'] = 'Βαθμολογία Moodle';
$string['graphics'] = 'Γραφήματα';
$string['timedue_AE'] = 'ΑΑ - καταληκτική ημερομηνία';
$string['timedue_EI'] = 'ΑΙ - καταληκτική ημερομηνία';
$string['january'] = 'Ιανουάριος';
$string['february'] = 'Φεβρουάριος';
$string['march'] = 'Μάρτιος';
$string['april'] = 'Απρίλιος';
$string['may'] = 'Μάιος';
$string['june'] = 'Ιούνιος';
$string['july'] = 'Ιούλιος';
$string['august'] = 'Αύγουστος';
$string['september'] = 'Σεπτέμβριος';
$string['october'] = 'Οκτώβριος';
$string['november'] = 'Νοέμβριος';
$string['december'] = 'Δεκέμβριος';
$string['save'] = 'Αποθήκευση';
$string['cancel'] = 'Ακύρωση';
$string['evaluate'] = 'Αξιολογήστε';
$string['scale'] = 'Κλίμακα';
$string['list'] = 'Λίστα ελέγχου';
$string['listscale'] = 'Κλίμακα λίστας';
$string['rubric'] = 'Ρουμπρίκα';
$string['mixed'] = 'Μικτή';
$string['differential'] = 'Σημασιολογική διαφοροποίηση';
$string['argumentset'] = 'Αξιολόγηση με κείμενα πειθούς';
$string['whatis'] = 'Διαχείριση εργαλείων αξιολόγησης';
$string['gradeof'] = 'Βαθμολογία του';
/* ----------------------------- ΒΟΗΘΕΙΑ ----------------------------- */
$string['timeopen_help'] = 'Η αξιολόγηση από ισότιμους δεν περιλαμβάνεται στο βαθμολόγιο του EvalCOMIX γιατί η περίοδος αξιολόγησης δεν τέλειωσε ακόμη.';
$string['evalcomixgrade_help'] = 'Μέσος όρος βαρύτητας από την αξιολόγηση του EVALCOMIX';
$string['moodlegrade_help'] = 'Βαθμολογία από το Moodle';
$string['finalgrade_help'] = 'Αριθμητικός μέσος όρος στον τελικό βαθμό του EvalCOMIX και τελικός βαθμός στο Moodle';
$string['teachermodality_help'] = 'Αυτό το εργαλείο θα είναι το εργαλείο αξιολόγησης που θα χρησιμοποιηθεί από τους εκπαιδευτικούς για να αξιολογήσουν τους μαθητές σε αυτήν τη δραστηριότητα.';
$string['pon_EP_help'] = 'Είναι το ποσοστό της βαθμολογίας του εργαλείου αξιολόγησης του εκπαιδευτικού στην τελική βαθμολογία.';
$string['selfmodality_help'] = 'Είναι το εργαλείο αυτοαξιολόγησης που χρησιμοποιείται από τους μαθητές για να ελέγξουν και να αξιολογήσουν τις δραστηριότητές τους.';
$string['pon_AE_help'] = 'Είναι το ποσοστό της βαθμολογίας από το εργαλείο αυτοαξιολόγησης στην τελική βαθμολογία.';
$string['peermodality_help'] = 'Είναι το εργαλείο αξιολόγησης που χρησιμοποιήθηκε από τους μαθητές για να αξιολογήσουν τη δραστηριότητα των συμμαθητών τους.';
$string['pon_EI_help'] = 'Είναι το ποσοστό της βαθμολογίας από το εργαλείο αξιολόγησης ισότιμων στην τελική βαθμολογία.';
$string['availabledate_AE_help'] = '';
$string['timedue_AE_help'] = '';
$string['availabledate_EI_help'] = '';
$string['timedue_EI_help'] = '';
$string['anonymous_EI_help'] = 'Δείχνει αν οι μαθητές θα γνωρίζουν ποιοι συμμαθητές τους, τους αξιολόγησαν.';
$string['whatis_help'] = 'Το EvalCOMIX επιτρέπει το σχεδιασμό και τη διαχείριση εργαλείων αξιολόγησης (βαθμολογικές κλίμακες, ρουμπρικές, κ.λπ.) που μπορούν να χρησιμοποηθούν για την πρόσβαση σε μία ομάδα συζήτησης (forum), για γλωσσάρια, βάσεις δεδομένων, wiki και δραστηριότητες.<br>Η αξιολόγηση με αυτά τα εργαλεία μπορεί να λάβει χώρα από εκπαιδευτικούς (αξιολόγηση από τον εκπαιδευτικό), ή τους μαθητές students (αυτοαξιολόγηση, αξιολόγηση από ισότιμους). Για περισσότερες πληροφορίες, συμβουλευτείτε το Εγχειρίδιο. Μπορείτε να το βρείτε εδώ <a href="../manual.pdf">Manual</a>';
$string['selinstrument_help'] = 'Δείτε το <a href="../manual.pdf">Manual</a> για περισσότερες πληροφορίες για τη δημιουργία μίας δραστηριότητας στο EvalCOMIX.';
/* --------------------------- ΤΕΛΟΣ ΒΟΗΘΕΙΑΣ --------------------------- */
$string['profile_task_by_student'] = 'Γράφημα δραστηριοτήτων ανά μαθητή';
$string['profile_task_by_group'] = 'Γράφημα δραστηριοτήτων ανά ομάδα';
$string['profile_task_by_course'] = 'Γράφημα δραστηριοτήτων ανά μάθημα';
$string['profile_student_by_teacher'] = 'Γράφημα ομάδας μαθητών από αξιολόγηση από τον εκπαιδευτικό';
$string['profile_student_by_group'] = 'Γράφημα ομάδας μαθητών από αξιολόγηση από ισότιμους';
$string['profile_attribute_by_student'] = 'Γράφημα χαρακτηριστικών ανά μαθητή';
$string['profile_attribute_by_group'] = 'Γράφημα χαρακτηριστικών ανά ομάδα';
$string['profile_attribute_by_course'] = 'Γράφημα χαρακτηριστικών ανά μάθημα';
$string['titlegraficbegin'] = 'Init';
$string['no_datas'] = 'Δεν υπάρχουν δεδομένα';

$string['linktoactivity'] = 'Πατήστε εδώ για να δείτε τη δραστηριότητα';
$string['sendgrades'] = 'Αποστολή της βαθμολογίας του EvalCOMIX στο βαθμολόγιο';
$string['deletegrades'] = 'Διαγραφή της βαθμολογίας του EvalCOMIX από το βαθμολόγιο';
$string['updategrades'] = 'Αποστολή τελικής βαθμολογίας του EvalCOMIX';
$string['gradessubmitted'] = 'Οι βαθμολογίες στο EvalCOMIX στο βαθμολόγιο';
$string['gradesdeleted'] = 'Οι βαθμολογίες στο EvalCOMIX διαγράφτηκαν από το βαθμολόγιο';

$string['confirm_add'] = 'Αυτή η λειτουργία θα τροποποιήσει το βαθμολόγιο.Θα συγχωνεύσει τον μέσο όσο μεταξύ των βαθμολογιών στο Moodle και στο EvalCOMIX. Επιθυμείτε να συνεχίσετε;';
$string['confirm_update'] = 'Το βαθμολόγιο τροποποιήθηκε προηγουμένως.\Αυτή η λειτουργία θα υποβάλει τους βαθμούς στο EvalCOMIX. Επιθιυμείτε να συνεχίσετε;';
$string['confirm_delete'] = 'Αυτή η λειτουργία θα τροποποιήσει το βαθμολόγιο στο Moodle.\nΘα διαγράψει τις βαθμολογίες του EvalCOMIX από το βαθμολόγιο του Moodle.\Επιθυμείτε να συνεχίσετε;';
$string['noconfigured'] = 'Καμία ρύθμιση';
$string['gradebook'] = 'Βαθμολόγιο';

$string['poweredby'] = 'Powered by:<br><span style="font-weight:bold; font-size: 10pt">EVALfor</span><br> Research Group';

$string['alertnotools'] = 'Τα εργαλεία αξιολόγησης δεν ενεργοποιήθηκαν ακόμη. Για να δημιουργήσετε ένα εργαλείο, θα πρέπει να αποκτήσετε πρόσβαση στο επόμενο μέρος';
$string['alertjavascript'] = 'Θα πρέπει να ενεργοποιήσετε το Javascript στον περιηγητή σας';
$string['studentwork1'] = 'Δραστηριότητα που ολοκληρώθηκε από τους μαθητές';
$string['studentwork2'] = ' στη δραστηριότητα ';
$string['evalcomix:addinstance'] = 'Προσθήκη νέου στοιχείου στο EvalCOMIX';
$string['evalcomix:myaddinstance'] = 'Προσθέστε ένα νέο στοιχείο στο EvalCOMIX';

$string['reportsection'] = 'Εκθέσεις';
$string['notaskconfigured'] = 'Δεν έχουν διαμορφωθεί δραστηριότητες με';
$string['studendtincluded'] = 'Η αυτοαξιολόγηση μαθητή να περιλαμβάνει';
$string['selfitemincluded'] = 'Στοιχεία αυτοαξιολόγησης που θα περιλαμβάνονται';
$string['selftask'] = 'Αναλυτικές εκθέσεις από την αυτοαξιολόγηση';
$string['format'] = 'Μορφή';
$string['export'] = 'Εξαγωγή';
$string['xls'] = 'Excel';
$string['excelexport'] = 'Εξαγωγή σε έγγραφο Excel';
$string['selectallany'] = 'Επιλέξτε όλα/κανένα';
$string['nostudentselfassessed'] = 'Δεν υπάρχει μαθητής που αξιολογείται';

// Settings.
$string['admindescription'] = 'Ρυθμίστε τον server του EvalCOMIX. Σιγουρευτείτε ότι <strong>sure</strong> οι μεταβλητές που εισαγάγατε είναι σωστές. Διαφορετικά η ενσωμάτωση δεν θα λειτουργήσει.';
$string['adminheader'] = 'Ρυθμίσεις του server του EvalCOMIX';
$string['serverurl'] = 'Το URL του EvalCOMIX:';
$string['serverurlinfo'] = 'Εδώ πρέπει να προσθέσετε το URL για τον server του EvalCOMIX. ie: http://localhost/evalcomix';
$string['validationheader'] = 'Ρυθμίσεις επιβεβαίωσης';
$string['validationinfo'] = 'Προτού αποθηκεύσετε τις ρυθμίσεις, πατήστε το κουμπί για να τις ενεργοποιήσετε στον server του EvalCOMIX . Αν η επαλήθευση είναι σωστή, αποθηκεύστε τις ρυθμίσεις. Αν όχι, παρακαλήστε να ελέγξετε αν οι ρυθμίσεις που έχετε προσθέσει ταιριάζουν με τις μεταβλητές στον server';
$string['validationbutton'] = 'Επικύρωση Ρυθμίσεων';
$string['error_conection'] = 'Η επιβεβαίωση απέτυχε: παρακαλήστε να ελέγξετε τις ρυθμίσεις που έχετε εισάγει, ώστε να ταιριάζουν με τις ρυθμίσεις του EvalCOMIX';
$string['valid_conection'] = 'Η επιβεβαίωση ολοκληρώθηκε με επιτυχία';
$string['simple_error_conection'] = 'Έγκυρο URL. Αλλά υπάρχει σφάλμα:';

$string['alwaysvisible_EI_help'] = 'Αν δεν έχει επιλεχθεί, οι μαθητές μπορούν μόνο να δουν τις αξιολογήσεις από ισότιμους ύστερα από προκαθορισμένη ημερομηνία. Αν έχει επιλεχθεί, οι μαθητές θα μπορούν πάντα να δουν τις αξιολογήσεις από ισότιμους';
$string['alwaysvisible_EI'] = 'Πάντα ορατός';
$string['whoassesses_EI'] = 'Ποιος αξιολογεί';
$string['anystudent_EI'] = 'Οποιοσδήποτε μαθητής';
$string['groups_EI'] = 'Ομάδες';
$string['specificstudents_EI'] = 'Συγκεκριμένοι μαθητές';
$string['whoassesses_EI_help'] = '';
$string['assignstudents_EI'] = 'Αναθέστε μαθητές';
$string['assess_students'] = 'Αξιολόγηση των μαθητών';
$string['studentstoassess'] = 'Αξιολόγηση από τους μαθητές';
$string['search'] = 'Αναζήτηση';
$string['add_delete_student'] = 'Προσθήκη/Διαγραφή μαθητών';
$string['back'] = 'Πίσω';
$string['potentialstudents'] = 'Πιθανοί μαθητές';

$string['settings'] = 'Ρυθμίσεις';
$string['activities'] = 'Δραστηριότητες';
$string['edition'] = 'Έκδοση';
$string['settings_description'] = 'Σε αυτό το μέρος θα διαμορφώσετε τον πίνακα αξιολόγησης';

$string['crontask'] = 'Δραστηριότητα ανανέωσης της βαθμολογίας του EvalCOMIX στο βαθμολόγιο';
$string['confirmdeleteassessment'] = 'Είστε βέβαιοι ότι θέλετε να διαγράψετε την αξιολόγηση;';
