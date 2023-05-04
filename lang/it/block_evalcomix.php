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
$string['blocksettings'] = 'Configurazione';
$string['blockstring'] = 'EvalCOMIX contenuti';
$string['instruments'] = 'Gestione degli strumenti';
$string['evaluation'] = 'Valuta le Attività';
$string['evalcomix:view'] = 'Visualizzare EvalCOMIX';
$string['evalcomix:edit'] = 'Modificare EvalCOMIX';
$string['whatis'] = 'EvalCOMIX consente la progettazione e la gestione di strumenti di valutazione (scale di valutazione, rubriche, etc. da utilizzare per valutare forum, glossari, database, wiki, e attività.<br>La valutazione attraverso questi strumenti può essere effettuata dagli insegnanti (valutazione degli insegnanti) o dagli studenti (autovalutazione, valutazione fra pari). Per maggiori informazioni, consultare il manuale. Per maggiori informazioni è possibile consultare <a href="' . $CFG->wwwroot.'/lib/evalcomix/manual.pdf">Manual</a>';
$string['selfmodality'] = 'Autovalutazione - SA ';
$string['peermodality'] = 'Valutazione fra pari - PA ';
$string['teachermodality'] = 'Valutazione degli insegnanti - TA ';
$string['selfmod'] = 'Autovalutazione';
$string['peermod'] = 'Valutazione fra pari';
$string['teachermod'] = 'Valutazione degli insegnanti';
$string['nuloption'] = 'Selezione dello strumento di valutazione';
$string['grades'] = 'Voto: ';
$string['selinstrument'] = 'Piano di valutazione';
$string['pon_EP'] = 'Peso - TA';
$string['pon_AE'] = 'Peso - SA';
$string['pon_EI'] = 'Peso - PA';
$string['availabledate_EI'] = 'PA - disponibile da: ';
$string['availabledate_AE'] = 'SA - disponibile da: ';
$string['ratingsforitem'] = 'Ripartizione del voto';
$string['modality'] = 'Modalità';
$string['grade'] = 'Voto';
$string['weighingfinalgrade'] = 'Peso nella votazione finale';
$string['finalgrade'] = 'Voto finale';
$string['nograde'] = 'Nessun Voto';
$string['timeopen'] = 'Il periodo della valutazione non è terminato';
$string['designsection'] = 'Progettazione e gestione dello strumento di valutazione';
$string['assesssection'] = 'Valutazione attività';
$string['counttool'] = 'Conteggio degli strumenti';
$string['newtool'] = 'Nuovo strumento';
$string['open'] = 'Apri';
$string['view'] = 'Visualizza';
$string['delete'] = 'Cancella';
$string['title'] = 'Titolo';
$string['type'] = 'Genere';
$string['anonymous_EI'] = 'Anonimo - PA';
$string['details'] = 'Dettagli';
$string['assess'] = 'Valuta';
$string['set'] = 'Imposta';
$string['ratingsforitem'] = 'valutazione';
$string['modality'] = 'Modalità';
$string['grade'] = 'Voto';
$string['weighingfinalgrade'] = 'Peso nella votazione finale';
$string['evalcomixgrade'] = 'Voto EvalCOMIX';
$string['moodlegrade'] = 'Voto Moodle';
$string['graphics'] = 'Grafici';
$string['timedue_AE'] = 'SA - scadenza';
$string['timedue_EI'] = 'PA - scadenza';
$string['january'] = 'Gennaio';
$string['february'] = 'Febbraio';
$string['march'] = 'Marzo';
$string['april'] = 'Aprile';
$string['may'] = 'Maggio';
$string['june'] = 'Giugno';
$string['july'] = 'Luglio';
$string['august'] = 'Agosto';
$string['september'] = 'Settembre';
$string['october'] = 'Ottobre';
$string['november'] = 'Novembre';
$string['december'] = 'Dicembre';
$string['save'] = 'Salva';
$string['cancel'] = 'Cancella';
$string['evaluate'] = 'Valutazione';
$string['scale'] = 'Scala';
$string['list'] = 'Check list';
$string['listscale'] = 'Listscale';
$string['rubric'] = 'Rubrica';
$string['mixed'] = 'Valutazione mista';
$string['differential'] = 'Valutazione differenziale';
$string['argumentset'] = 'Valutazione argomentativa';
$string['whatis'] = 'Gestione strumenti di valutazione';
$string['gradeof'] = 'Voto di ';
/* ----------------------------- AIUTO ----------------------------- */
$string['timeopen_help'] = 'La valutazione fra pari non è inclusa nel voto EvalCOMIX perché il periodo di valutazione non è ancora terminato.';
$string['evalcomixgrade_help'] = 'Media ponderata delle valutazioni di evalcomix';
$string['moodlegrade_help'] = 'Voto asseganto da Moodle';
$string['finalgrade_help'] = 'Media aritmetica di EvalCOMIX voto finale e voto finale di Moodle';
$string['teachermodality_help'] = 'Questo strumento sarà lo strumento di valutazione usato dagli insegnanti per valutare gli studenti in questa attività.';
$string['pon_EP_help'] = 'Questa è la percentuale del voto ottenuto tramite lo strumento di valutazione degli insegnanti nel voto finale.';
$string['selfmodality_help'] = 'Questo è lo strumento di autovalutazione usato dagli studenti per valutare e dare un voto al loro compito.';
$string['pon_AE_help'] = 'Questa è la percentuale del voto ottenuto dallo strumento di autovalutazione nel voto finale.';
$string['peermodality_help'] = 'Questp è lo strumento di valutazione usato dagli studenti per valutare le attività dei loro compagni.';
$string['pon_EI_help'] = 'Questa è la percentuale del voto ottenuto dallo strumento di valutazione fra pari nel voto finale.';
$string['availabledate_AE_help'] = '';
$string['timedue_AE_help'] = '';
$string['availabledate_EI_help'] = '';
$string['timedue_EI_help'] = '';
$string['anonymous_EI_help'] = 'Indica se gli studenti potranno sapere quali compagni li hanno valutati.';
$string['whatis_help'] = 'EvalCOMIX consente di progettare e gestire strumenti di valutazione (scale di valutazione, rubriche, ecc.) Da utilizzare per valutare forum, glossari, database, wiki e attività.<br>La valutazione con questi strumenti può essere effettuata da insegnanti (valutazione degli insegnanti) o studenti (autovalutazione, valutazione fra pari). Per maggiori informazioni, consultare il manuale. Per maggiori informazioni consultare  <a href="../manual.pdf">Manual</a>';
$string['selinstrument_help'] = 'Riferimento a <a href="../manual.pdf">Manual</a> per ulteriori informazioni su come impostare delle attività con EvalCOMIX.';
/* --------------------------- END HELP --------------------------- */
$string['profile_task_by_student'] = 'Grafico delle attività degli studenti';
$string['profile_task_by_group'] = 'Grafico delle attività di grppo';
$string['profile_task_by_course'] = 'Grafico delle attività del corso';
$string['profile_student_by_teacher'] = 'Chart student body by teacher';
$string['profile_student_by_group'] = 'Chart student body by peer';
$string['profile_attribute_by_student'] = 'Attrobuto grafico degli studenti';
$string['profile_attribute_by_group'] = 'Attributo grafico di gruppo';
$string['profile_attribute_by_course'] = 'Attrobuto grafico del corso';
$string['titlegraficbegin'] = 'Inizio';
$string['no_datas'] = 'Nessun dato';

$string['linktoactivity'] = 'Clicca per vedere le attività';
$string['sendgrades'] = 'Invia il voto EvalCOMIX nel registro';
$string['deletegrades'] = 'cancella la valutazione EvalCOMIX dal registro';
$string['updategrades'] = 'Invia i voti finali di EvalCOMIX';
$string['gradessubmitted'] = 'punteggi di EvalCOMIX inviati al registo';
$string['gradesdeleted'] = 'punteggi di EvalCOMIX cancellati dal registro';

$string['confirm_add'] = 'Questa operazione modificherà il registro.\nEffettuerà la media tra i voti di Moodle e quello di EvalCOMIX. Confermi di voler continuare?';
$string['confirm_update'] = 'Il registro è stato precendentemente modificato.\nQuesta operazione invierà gli ultimi voti EvalCOMIX. Confermi di voler continuare?';
$string['confirm_delete'] = 'Questa operazione modificherà il registro Moodle.\nCancellerà i voti EvalCOMIX dal registro Moodle.\nConfermi di voler continuare?';
$string['noconfigured'] = 'Nessuna congurazione';
$string['gradebook'] = 'Registro';

$string['poweredby'] = 'Powered by:<br><span style="font-weight:bold; font-size: 10pt">EVALfor</span><br> Research Group';

$string['alertnotools'] = 'Lo strumento di valutazione non è stato ancora generato. Per crearlo, accedi alle sessione successiva';
$string['alertjavascript'] = 'Devi abilitare Javascript sul tuo browser';
$string['studentwork1'] = 'Lavoro completato dallo studente';
$string['studentwork2'] = ' nella attività ';
$string['evalcomix:addinstance'] = 'Aggiungi un nuovo blocco EvalCOMIX';
$string['evalcomix:myaddinstance'] = 'Aggiungi un nuovo blocco EvalCOMIX';

$string['reportsection'] = 'Reports';
$string['notaskconfigured'] = 'Non ci sono attività configurate con';
$string['studendtincluded'] = 'Studente avutovalutato da includere';
$string['selfitemincluded'] = 'Elementi di autovalutazione da incleudere';
$string['selftask'] = 'Rapporti dettagliati di autovalutazione';
$string['format'] = 'Formato';
$string['export'] = 'Esportare';
$string['xls'] = 'Excel';
$string['excelexport'] = 'Esportare su foglio di calcolo Excel';
$string['selectallany'] = 'Seleziona tutto/niente';
$string['nostudentselfassessed'] = 'Non ci sono studenti valutati';

$string['admindescription'] = 'Configura le tue impostazioni sul server EvalCOMIX.  <strong>assicurati</strong> che i valori che hai inserito siano corretti. Altrimenti le integrazioni potrebbero non funzionare.';
$string['adminheader'] = 'Configurazione del server EvalCOMIX';
$string['serverurl'] = 'URL del server EvalCOMIX:';
$string['serverurlinfo'] = 'Inserire qui URL del server EvalCOMIX. ie: http://localhost/evalcomix';
$string['validationheader'] = 'validazione impostazioni';
$string['validationinfo'] = 'Prima di salvare le impostazioni, premi il tasto per validarle col server EvalCOMIX. Se la validazione  è corretta, salvare le impostazioni. Se no, verificare che le impostazioni inserite combacino con i valori del server';
$string['validationbutton'] = 'Convalidare impostazioni';
$string['error_conection'] = 'Validazione non riuscita: si prega di verificare che le impostazioni immesse corrispondano alle impostazioni in EvalCOMIX';
$string['valid_conection'] = 'validazione completata con successo';
$string['simple_error_conection'] = 'URL valido. ma si riscontra un errore: ';

$string['alwaysvisible_EI_help'] = 'Se non sono selezionati, gli studenti possono vedere solo valutazioni fra pari dopo la data limite. Se sono selezionati, gli studenti possono sempre vedere le loro valutazioni tra pari';
$string['alwaysvisible_EI'] = 'Sempre visibile';
$string['whoassesses_EI'] = 'Chi valuta';
$string['anystudent_EI'] = 'Qualisiasi studente';
$string['groups_EI'] = 'Gruppi';
$string['specificstudents_EI'] = 'Studente specifico';
$string['whoassesses_EI_help'] = '';
$string['assignstudents_EI'] = 'Attività studenti';
$string['assess_students'] = 'Valuta studenti';
$string['studentstoassess'] = 'Studenti da valutare';
$string['search'] = 'Cerca';
$string['add_delete_student'] = 'Aggiungi/Cancella Studenti';
$string['back'] = 'Indietro';
$string['potentialstudents'] = 'Potenziali studenti';

$string['settings'] = 'Impostazioni';
$string['activities'] = 'Attività';
$string['edition'] = 'Edizione';
$string['settings_description'] = 'In questa sezione sarà configurata la tabella di valutazione';

$string['crontask'] = 'Compito per aggiornare i punteggi sul registro EvalCOMIX';
$string['confirmdeleteassessment'] = 'Sei sicuro di voler eliminare la valutazione?';

// Tool editor.
$string['selecttool'] = 'Seleziona il tipo di strumento da creare';
$string['accept'] = 'Accetta';
$string['alertdimension'] = 'Deve esistere almeno una dimensione';
$string['alertsubdimension'] = 'Deve esistere almeno una sottodimensione';
$string['alertatrib'] = 'Deve esistere almeno un attributo';
$string['rubricremember'] = 'RICORDA: non devono esistere valori RIPETUTI';
$string['importfile'] = 'Importa file';
$string['noatrib'] = 'Attributo negativo';
$string['yesatrib'] = 'Attributo positivo';

$string['comments'] = 'Commenti';
$string['grade'] = 'Voto';
$string['nograde'] = 'Nessun voto';
$string['alertsave'] = "Valutazione salvata con successo. Se vuoi, puoi chiudere questa finestra";

$string['add_comments'] = 'Cambia commento';
$string['checklist'] = 'Lista di controllo';
$string['ratescale'] = 'Scala di valutazione';
$string['listrate'] = 'Lista di controllo + Scala di valutazione';
$string['rubric'] = 'Rubrica';
$string['differentail'] = 'Differenziale semantico';
$string['mix'] = 'Strumento misto';
$string['argument'] = 'Valutazione argomentativa';
$string['numdimensions'] = 'Nº delle Dimensioni:';
$string['numvalues'] = 'Nº dei valori:';
$string['totalvalue'] = 'Valutazione globale';
$string['dimension'] = 'Dimensione:';
$string['subdimension'] = 'Sottodimensione:';
$string['numsubdimension'] = 'Nº Sottodimensioni:';
$string['numattributes'] = 'Nº di attributi:';
$string['attribute'] = 'Attributi';
$string['porvalue'] = 'Valore percentuale:';
$string['value'] = 'Valore';
$string['values'] = 'Valori';
$string['globalvalue'] = 'DIMENSIONE DELLA VALUTAZIONE GLOBALE:';
$string['import'] = 'Importa';
$string['novalue'] = 'Valore negativo';
$string['yesvalue'] = 'Valore positivo';
$string['idea'] = 'IDEA & DIREZIONE';
$string['design'] = 'PROGETTAZIONE';
$string['develop'] = 'SVILUPPO';
$string['translation'] = 'TRADUZIONE';
$string['colaboration'] = 'COOPERARE';
$string['license'] = 'LICENZA';
$string['addtool'] = 'Aggiungi strumento di valutazione';
$string['title'] = 'Titolo';
$string['titledim'] = 'Dimensione';
$string['titlesubdim'] = 'Sottodimensione';
$string['titleatrib'] = 'Attributo';
$string['titlevalue'] = 'Valore';
$string['no'] = 'No';
$string['yes'] = 'Sì';
$string['observation'] = 'Commenti';
$string['view'] = 'Chiudi visualizzazione precedente';

$string['windowselection'] = 'Seleziona finestra';
$string['selectfile'] = 'Seleziona file';
$string['upfile'] = 'Carica file';
$string['cancel'] = 'Cancella';

$string['savedsaccessfully'] = 'Questo strumento è stato salvato con successo';
$string['ADimension'] = 'Questo campo non può essere lasciato vuoto. \"Nº delle Dimensioni\" Deve essere un numero maggiore di 0 e \"Nº dei Valori\" un numero maggiore o uguale a 2';
$string['ATotal'] = 'Questo campo non può essere lasciato vuoto. \"Nº dei valori\" deve essere un numero maggiore o uguale a 2';
$string['ASubdimension'] = 'Questo campo non può essere lasciato vuoto. \"Nº Delle Sottodimensioni\" deve essere un numero maggiore di 0 e \"Nº dei Valori\" maggiore o uguale a 2';
$string['AAttribute'] = 'Questo campo non può essere lasciato vuoto. Per favore, inserisci un numero maggiore di 0';
$string['ADiferencial'] = '\"Nº di Attributi\" deve essere maggiore di 0. \"Nº dei Valori\" Deve essere ODD';
$string['ErrorFormato'] = 'Il file è vuoto o il formato è sbagliato"';
$string['ErrorAcceso'] = 'Il file non è accessibile';
$string['ErrorExtension'] = 'Formato sbagliato. Estensione deve essere \"evx\"';
$string['ErrorSaveTitle'] = 'Errore: il titolo non può essere omesso';
$string['ErrorSaveTools'] = 'Errore: deve essere selezionato almeno uno strumento di valutazione';

$string['TSave'] = 'Salva';
$string['TImport'] = 'Importa';
$string['TExport'] = 'Esporta';
$string['TAumentar'] = 'Aumenta dimensione carattere';
$string['TDisminuir'] = 'Riduci dimensione carattere';
$string['TView'] = 'visualizzazione precedente';
$string['TPrint'] = 'Stampa';
$string['THelp'] = 'Aiuto';
$string['TAbout'] = 'A proposito di';

$string['mixed_por'] = 'Peso nella votazione finale';

$string['handlerofco'] = 'Gestione delle competenze e risultati di apprendimento';
$string['competencies'] = 'Competenze';
$string['outcomes'] = 'Risultati di apprendimento';
$string['compidnumber'] = 'Codice';
$string['compshortname'] = 'Nome corto';
$string['compdescription'] = 'Descrizione';
$string['comptypes'] = 'Tipi di competizione';
$string['competizione'] = 'Tipo di competizione';
$string['newcomp'] = 'Nuovo comp';
$string['newoutcome'] = 'Nuovo risultato di apprendimento';
$string['newcomptype'] = 'Nuovo tipo di composizione';
$string['compreport'] = 'Rapporto di sviluppo';
$string['compandout'] = 'Competenze e risultati di apprendimento';
$string['uploadcompetencies'] = 'Importa competenze e risultati';
$string['uploadcompetencies_help'] = 'Le competenze ei risultati di apprendimento possono essere caricati tramite un file di testo. Il formato del file dovrebbe essere il seguente:

* Ogni riga del file contiene un record
* Ogni record è una serie di dati separati da virgole (o altri delimitatori)
* Il primo record contiene un elenco di nomi di campo che definiscono il formato del resto del file
* I nomi dei campi obbligatori sono idnumber, shortname, result';
$string['idnumberduplicate'] = 'Numero ID duplicato';
$string['invalidoutcome'] = 'Valore risultato non valido. Deve essere 0 o 1';
$string['invalididnumberupload'] = 'Valore idnumber non valido. La dimensione deve essere inferiore a 100';
$string['missingidnumber'] = 'Numero ID colonna mancante';
$string['missingshortname'] = 'Manca il nome breve della colonna';
$string['missingoutcome'] = 'Manca la colonna del risultato';
$string['ignored'] = 'Ignorato';
$string['errors'] = 'Errori';
$string['importresult'] = 'Importa risultati';
$string['uploadcompetenciespreview'] = 'Anteprima delle competenze caricate';
$string['choicecompetency'] = 'Scegli un concorso';
$string['choiceoutcome'] = 'Scegli un risultato';
$string['associatecompandout'] = 'Associa competenze e risultati';
$string['allstudens'] = 'Tutti gli studenti';
$string['onestudent'] = 'Studente specifico';
$string['onegroup'] = 'Gruppo specifico';
$string['evaluationandreports'] = 'Valutazione e rapporti';
$string['workteams'] = 'Squadre di lavoro';
$string['workteamsassessments'] = 'Valutazione dei gruppi di lavoro';
$string['assignteamcoordinators'] = 'Assegnare i coordinatori del team';
$string['workteamsassessments_help'] = 'Se attivi questa opzione, potrai nominare un coordinatore per rappresentare il gruppo.

Se c\'è **Teacher Evaluation - EP**, gli insegnanti potranno valutare solo i coordinatori e tale valutazione sarà assegnata a ciascun membro del loro gruppo.

Se c\'è **Autovalutazione – ​​AE**, solo il coordinatore può effettuare l\'autovalutazione e la sua valutazione sarà assegnata a ciascun membro del suo gruppo.

In presenza di **Peer Assessment – ​​​​EI**, gli studenti potranno valutare solo i coordinatori di ciascun gruppo e ogni valutazione sarà assegnata a ciascun componente del gruppo.

Gli studenti che non fanno parte di alcun gruppo non riceveranno una valutazione. I gruppi che non hanno un coordinatore assegnato non riceveranno alcuna valutazione e non potranno valutare.';
$string['selectcoordinator'] = 'scegli il coordinatore';
$string['alertnogroup'] = 'Nessun gruppo è stato ancora creato nel corso. Per crearli è necessario accedere alla seguente sezione:';
$string['activityassessed'] = 'Disabilitato perché uno studente è già stato testato';
$string['coordinatorassessed'] = 'Attualmente, ci sono coordinatori che hanno già ricevuto una valutazione. Coloro che hanno ricevuto una valutazione non possono essere sostituiti. Se desideri sostituirli, dovrai prima eliminare le recensioni.';
$string['confirmdisabledworkteams'] = 'Le valutazioni sono già state effettuate in questa attività. Se disabiliti questa opzione e salvi le modifiche, tutte queste valutazioni verranno eliminate e non potranno essere recuperate. Sei sicuro di voler disabilitare l\'opzione?';
