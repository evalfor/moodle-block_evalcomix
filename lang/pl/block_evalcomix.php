<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
 
	$string['pluginname'] = 'EvalCOMIX';
	$string['evalcomix'] = 'EvalCOMIX';
	$string['blocksettings'] = 'Konfiguracja';
	$string['blockstring'] = 'Zawartość EvalCOMIX';
	$string['instruments'] = 'Narzędzia';
	$string['evaluation'] = 'Ocena Zajęć';
	$string['evalcomix:view'] = 'Podgląd EvalCOMIX';
	$string['evalcomix:edit'] = 'Edytuj EvalCOMIX';	
	$string['whatis'] = 'EvalCOMIX pozwala na projektowanie i zarządzanie narzędziami oceny (punktowanie, rubryki itd.) do oceniania forum, słowników, bazy danych, wiki i zadań. <br>
	Ocena za pomocą tych narzędzi może być dokonana przez nauczycieli (ocena nauczycieli) lub studentów (samo-ocena, ocena rówieśnicza). Więcej informacji dostępne w Manualu.
	Więcej informacji dostępne tutaj <a href="' . $CFG->wwwroot.'/lib/evalcomix/manual.pdf">Manual</a>';
	$string['selfmodality'] = 'Samo-ocena - SO ';
	$string['peermodality'] = 'Ocena rówieśnicza - OR ';
	$string['teachermodality'] = 'Ocena Nauczyciela - ON ';
	$string['selfmod'] = 'Samo-ocena';
	$string['peermod'] = 'Ocena rówieśnicza';
	$string['teachermod'] = 'Ocena nauczyciela';
	$string['nuloption'] = 'Wybór narzędzi oceny';
	$string['grades'] = 'Stopnie: ';
	$string['selinstrument'] = 'Planowanie oceny';
	$string['pon_EP'] = 'System ważenia - NA';
	$string['pon_AE'] = 'System ważenia - SO';
	$string['pon_EI'] = 'System ważenia - OR';
	$string['availabledate_EI'] = 'OR - dostępna od: ';
	$string['availabledate_AE'] = 'SO - dostępna od: ';
	$string['ratingsforitem'] = 'Podział ocen';
	$string['modality'] = 'Modalność';
	$string['grade'] = 'Oceny';
	$string['weighingfinalgrade'] = 'Ważenie w finalnej ocenie';
	$string['finalgrade'] = 'Finalna ocena';
	$string['nograde'] = 'Brak oceny';
	$string['timeopen'] = 'Okres oceny się nie zakończył';
	$string['designsection'] = 'Projektowanie i zarządzanie narzędziami oceny';
	$string['assesssection'] = 'Ocena zajęć';
	$string['counttool'] = 'Zbiór narzędzi';
	$string['newtool'] = 'Nowe narzędzie';
	$string['open'] = 'Otwórz';
	$string['view'] = 'Podgląd';
	$string['delete'] = 'Usuń';
	$string['title'] = 'Tytuł';
	$string['type'] = 'Typ';
	$string['anonymous_EI'] = 'Anonimowa ocena - OR';
	$string['details'] = 'Szczegóły';
	$string['assess'] = 'Ocena';
	$string['set'] = 'Ustaw';
	$string['ratingsforitem'] = 'Punktowanie';
	$string['modality'] = 'Modalność';
	$string['grade'] = 'Stopień';
	$string['weighingfinalgrade'] = 'Ważenie finalnej oceny';
	$string['evalcomixgrade'] = 'Ocena EvalCOMIX';
	$string['moodlegrade'] = 'Ocena Moodle';
	$string['graphics'] = 'Grafika';
	$string['timedue_AE'] = 'SO - deadline';
	$string['timedue_EI'] = 'OR - deadline';
	$string['january'] = 'Styczeń';
	$string['february'] = 'Luty';
	$string['march'] = 'Marzec';
	$string['april'] = 'Kwiecień';
	$string['may'] = 'Maj';
	$string['june'] = 'Czerwiec';
	$string['july'] = 'Lipiec';
	$string['august'] = 'Sierpień';
	$string['september'] = 'Wrzesień';
	$string['october'] = 'Październik';
	$string['november'] = 'Listopad';
	$string['december'] = 'Grudzień';
	$string['save'] = 'Zapisz';
	$string['cancel'] = 'Anuluj';
	$string['evaluate'] = 'Oceń';
	$string['scale'] = 'Skala';
	$string['list'] = 'Check-Lista';
	$string['listscale'] = 'Listscale';
	$string['rubric'] = 'Rubryka';
	$string['mixed'] = 'Mieszany';
	$string['differential'] = 'Różnicowy';
	$string['argumentset'] = 'Ocena argumentacyjna';
	$string['whatis'] = 'Zarządzanie narzędziami oceny';
	$string['gradeof'] = 'Ocena... ';
	/* ----------------------------- HELP ----------------------------- */
	$string['timeopen_help'] = 'Ocena rówieśnicza nie jest włączona w system ocen EvalCOMIX ponieważ okres oceny nie zakończył się jeszcze.';
	$string['evalcomixgrade_help'] = 'Średnia ważona oceny evalcomix';
	$string['moodlegrade_help'] = 'Ocena przepisana z Moodle';
	$string['finalgrade_help'] = 'Średnia arytmetyczna oceny końcowej EvalCOMIX i Moodle';
	$string['teachermodality_help'] = 'To narzędzie oceny będzie wykorzystywane przez nauczycilie do oceny uczniów na tych zajęciach.';
	$string['pon_EP_help'] = 'Jest to procent oceny przyznanej przez narzędzie oceny nauczyciela w ocenie końcowej.';
	$string['selfmodality_help'] = 'Jest to narzędzie samo-oseny wykorzystywane przez uczniów do oceny ich własnych zadań.';
	$string['pon_AE_help'] = 'Jest to procent oceny przyznanej przez narzędzie samo-oceny w ocenie końcowej.';
	$string['peermodality_help'] = 'Jest to narzędzie oceny wykorzystywane przez uczniów do oceny rówieśniczej.';
	$string['pon_EI_help'] = 'Jest to procent oceny przyznanen przez narzędzie oceny rówieśniczej w ocenie końcowej.';
	$string['availabledate_AE_help'] = '';
	$string['timedue_AE_help'] = '';
	$string['availabledate_EI_help'] = '';
	$string['timedue_EI_help'] = '';
	$string['anonymous_EI_help'] = 'To wskazuje czy ocena jest anonimowa.';
	$string['whatis_help'] = 'EvalCOMIX pozwala na projektowanie i zarządzanie narzędziami oceny (punktowanie, rubryki itd.) do oceniania forum, słowników, bazy danych, wiki i zadań. <br>
Ocena za pomocą tych narzędzi może być dokonana przez nauczycieli (ocena nauczycieli) lub studentów (samo-ocena, ocena rówieśnicza). Więcej informacji dostępne w Manualu.
	Więcej informacji dostępne tutaj <a href="' . $CFG->wwwroot.'/lib/evalcomix/manual.pdf">Manual</a>';
	$string['selinstrument_help'] = 'Refer to the <a href="../manual.pdf">Manual</a> aby uzyskać więcej informacji o ustawieniach zajć EvalCOMIX.';
	/* --------------------------- END HELP --------------------------- */
	$string['profile_task_by_student'] = 'Karta zadań - uczeń';
	$string['profile_task_by_group'] = 'Karta zadań - grupa';
	$string['profile_task_by_course'] = 'Karta zadań - kurs';
	$string['profile_student_by_teacher'] = 'Karta uczniów - nauczyciel';
	$string['profile_student_by_group'] = 'Karta uczniów - równieśnicy';
	$string['profile_attribute_by_student'] = 'Karta atrybutu - uczeń';
	$string['profile_attribute_by_group'] = 'Karta atrybutu - grupa';
	$string['profile_attribute_by_course'] = 'Karta atrybutu - kurs';
	$string['titlegraficbegin'] = 'Init';
	$string['no_datas'] = 'Brak danych';
	
	$string['linktoactivity'] = 'Podgląd zajęć';
	$string['sendgrades'] = 'Wyślij oceny EvalCOMIX do księgi ocen';
	$string['deletegrades'] = 'Usuń oceny EvalCOMIX z księgi ocen';
	$string['updategrades'] = 'Wyślij ostatnie oceny EvalCOMIX ';
	$string['gradessubmitted'] = 'Oceny EvalCOMIX wpisano do księgi ocen';
	$string['gradesdeleted'] = 'Oceny EvalCOMIX usunięte z księgi ocen';
	
	$string['confirm_add'] = 'Ta operacja zmodyfikuje księgę ocen.\nIt wygeneruje średnią pomiędzy oceną Moodle i EvalCOMIX. Chcesz kontynuować?';
	$string['confirm_update'] = 'Księga ocen została poprzednio zmodyfikowana.\nTa operacja wprowadzi ostatnie oceny EvalCOMIX. Chcesz kontynuować?';
	$string['confirm_delete'] = 'Ta operacja zmodyfikuje ksiegę ocen Moodle.\nSpowoduje to usunięcie ocen EvalCOMIX z księgi ocen Moodle.\nChcesz kontynuować?';
	$string['noconfigured'] = 'Brak konfiguracji';
	$string['gradebook'] = 'Księga ocen';
	
	$string['poweredby'] = 'Powered by:<br><span style="font-weight:bold; font-size: 10pt">EVALfor</span><br> Research Group';
	
	$string['alertnotools'] = 'Narzędzie oceny nie zostało jeszcze wygenerowane. Aby je wygenerować, przejdź do następnej sekcji';
	$string['alertjavascript'] = 'Włącz obsługę Javascript w Twojej przeglądarce';
	$string['studentwork1'] = 'Praca ucznia zakończona';
	$string['studentwork2'] = ' podczas zajęć ';
	$string['evalcomix:addinstance'] = 'Dodaj nową sekcję w EvalCOMIX';
	$string['evalcomix:myaddinstance'] = 'Dodaj nową sekcję w EvalCOMIX';
	
	$string['reportsection'] = 'Raporty';
	$string['notaskconfigured'] = 'Nie ma skonfigurowanych zajęć z';
	$string['studendtincluded'] = 'Samo-ocena ucznia do włączenia';
	$string['selfitemincluded'] = 'Punkty samo-oceny do włączenia';
	$string['selftask'] = 'Szczegółowe raporty samo-oceny';
	$string['format'] = 'Format';
	$string['export'] = 'Eksportuj';
	$string['xls'] = 'Excel';
	$string['excelexport'] = 'Eksport do Excel Spreadsheet';
	$string['selectallany'] = 'Zaznacz wszystko/nic';
	$string['nostudentselfassessed'] = 'Nie ma ocenionych uczniów';
	
	//settings
	$string['admindescription'] = 'Skonfiguruj ustawienia EvalCOMIX. Upewnij się <strong></strong> , że wartości, które wprowadzasz są prawidłowe. W przeciwnym razie integracja może nie zadziałać.';
	$string['adminheader'] = 'Konfiguracja serweraEvalCOMIX';
	$string['serverurl'] = 'URL serwera EvalCOMIX:';
	$string['serverurlinfo'] = 'Wprowadź URL serwera EvalCOMIX. ie: http://localhost/evalcomix';
	$string['validationheader'] = 'Potwierdzenie ustawień';
	$string['validationinfo'] = 'Zanim zapiszesz ustawienia, potwierdź je. Jeśli zmiany są prawidłowe, zapisz ustawenia. Jeśli nie, czy sprawdź ustawienia, które wprowadziłeś są zgodne z wartościami na serwerze.';
	$string['validationbutton'] = 'Zatwierdź ustawienia';
	$string['error_conection'] = 'Potwierdzenie nie powiodło się: sprawdź czy ustawienia, które wprowadziłeś są zgodne ustawieniami EvalCOMIX.';
	$string['valid_conection'] = 'Zatwierdzenie zakończone pomyślnie';
	$string['simple_error_conection'] = 'URL prawidłowy. Wystąpił błąd: ';
	
	$string['alwaysvisible_EI_help'] = 'Jeśli to pole nie jest zaznaczone, uczniowie mogą zobaczyć ocenę rówieśniczą tylko po określonym terminie. Jeśli pole jest jest zaznaczone, uczniowie zawsze mogą zobaczyć swoją ocenę rówieśniczą.';
	$string['alwaysvisible_EI'] = 'Zawsze widoczne';
	$string['whoassesses_EI'] = 'Osoba oceniająca';
	$string['anystudent_EI'] = 'Każdy uczeń';
	$string['groups_EI'] = 'Grupy';
	$string['specificstudents_EI'] = 'Określeni uczniowie';
	$string['whoassesses_EI_help'] = '';
	$string['assignstudents_EI'] = 'Przydziel uczniów';
	$string['assess_students'] = 'Oceń uczniów';
	$string['studentstoassess'] = 'Uczniowie do oceny';
	$string['search'] = 'Szukaj';
	$string['add_delete_student'] = 'Dodaj/Usuń uczniów';
	$string['back'] = 'Wstecz';
	$string['potentialstudents'] = 'Potencjalni uczniowie';
	
	$string['settings'] = 'Ustawienia';
	$string['activities'] = 'Zajęcia';
	$string['edition'] = 'Edycja';
	$string['settings_description'] = 'W tej sekcji zostanie skonfigurowana tabela oceny';
	
	$string['crontask'] = 'Zadanie aktualizacji oceny w księdze ocen EvalCOMIX';
?>