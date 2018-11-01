<?php

require "PHPMailer_v5.1/class.phpmailer.php";
require_once('./function.php');
/* checkLogin(); */
session_start();

Connect($host, $username, $password, $dbname);

$now = time();
$day = date('d', $now);
$month_s = date('m', $now);
$year_s = date('Y', $now);
$year = $year_s;

function getMailDest($numDay) {
    $piuX = date('Y-m-d', strtotime("+" . $numDay . " day"));

    $time = strtotime($piuX);
    $day = date('d', $time);
    $month = date('m', $time);
    $year = date('Y', $time);

    $sql = 'select max(id) from osgb_anno_sociale';
    $result = Query($sql);
    WHILE ($details = mysql_fetch_array($result)):
        $maxAnnoSociale = $details["max(id)"];
    endwhile;

    $maxAnnoSociale = $maxAnnoSociale - 1;
    $sql_base = 'SELECT distinct osgb_anagrafica.cf_id, nome, cognome, visitamedica, mail FROM osgb_anagrafica, osgb_relazione';
    $sql = $sql_base . ' WHERE osgb_anagrafica.visitamedica = \'' . $year . '-' . $month . '-' . $day . ' 00:00:00\'';
    $sql = $sql . ' AND osgb_anagrafica.cf_id = osgb_relazione.anagrafica '; /* and cognome=\'FERRARI\' */
    $sql = $sql . ' AND annosociale > ' . $maxAnnoSociale;

    return Query($sql);
}

function sendMail($from, array $to, $replyTo, $object, $text, array $bcc) {
    $messaggio = new PHPmailer();
    $messaggio->From = $from;
    foreach ($to as $value) {
        $messaggio->AddAddress($value);
        echo "Sending mail to: " . $value . " <br>";
    }
    echo " <br>";
    foreach ($bcc as $value) {
        $messaggio->AddBCC($value);
        echo "Sending BCC mail to: " . $value . " <br>";
    }
    echo " <br>";
    $messaggio->AddReplyTo($replyTo);
    $messaggio->FromName = "OSGB Merate";
    $messaggio->Subject = $object;
    $messaggio->Body = stripslashes($text);

    echo $text;
    if (!$messaggio->Send()) {
        echo $messaggio->ErrorInfo;
    } else {
        echo 'Email inviata correttamente!';
    }

    unset($messaggio);
}

function sendMailReminder($dayNum) {


    $sql = 'select max(id) from osgb_anno_sociale';
    $result = Query($sql);
    WHILE ($details = mysql_fetch_array($result)):
        $maxAnnoSociale = $details["max(id)"];
    endwhile;

    $maxAnnoSociale = $maxAnnoSociale - 1;

    $result = getMailDest($dayNum);

    WHILE ($details = mysql_fetch_array($result)):
        $cognome = $details["cognome"];
        $nome = $details["nome"];
        $visitamedica = $details["visitamedica"];
        $mail = $details["mail"];
        $cf_id = $details["cf_id"];

        $msg = "Ciao " . $nome . " " . $cognome . ", \n"
                . "          l'OSGB Merate ti ricorda che la tua visita medica scadrà "
                . "il giorno " . date('d/m/Y', strtotime($visitamedica)) . ", cioè tra " . $dayNum . " giorni. \n\n"
                . "Ricorda che, qualora la tua visita medica scadesse, non ti sarà più possibile allenarti nè giocare con la tua squadra! \n"
                . "Per qualsiasi informazione in merito, puoi contattare la segreteria via mail "
                . "all'indirizzo info@osgbmerate.it oppure via telefono al 039 9905198. \n"
                . "\n"
                . "Ciao\n"
                . "Segreteria OSGB Merate\n\n";
        $to = array();

        $pattern = '/[a-z0-9_\-\+]+@[a-z0-9\-]+\.([a-z]{2,3})(?:\.[a-z]{2})?/i';
        $bcc = array();

        if ($mail == "") {
            array_push($to, 'info@osgbmerate.it', 'annavipera@alice.it', 'lella.matteoarianna@gmail.com', 'ferrmatt@gmail.com');
        } else if ($dayNum < 10) {
            array_push($bcc, 'emicolomb@gmail.com ', 'info@osgbmerate.it');
            preg_match_all($pattern, $mail, $to);
            $to = $to[0];
        } else {
            preg_match_all($pattern, $mail, $to);
            $to = $to[0];
        }
        /* DEBUGDEBUG */
//        $to = array();
//        array_push($to, 'ferrmatt@gmail.com', 'annavipera@alice.it', 'lella.matteoarianna@gmail.com');
//         $bcc = array();
        /* DEBUGDEBUG */

//        foreach ($to as $t) {
//            echo "<br />To: " . $t;
//        }
//
//        foreach ($bcc as $b) {
//            echo "<br />bcc: " . $b;
//        }

        echo "<br />msg: " . $msg;

        sendMail('info@osgbmerate.it', $to, 'info@osgbmerate.it', 'Visita medica in scadenza', $msg, $bcc);
    endwhile;
}

if ($day == 01) {
    $sql_base = 'SELECT cf_id, nome, cognome, visitamedica FROM osgb_anagrafica';
    // Mail per segreteria: scadenza a tre mesi
    $month = $month_s + 2;
    if ($month > 12) {
        $month = $month - 12;
        $year = $year_s + 1;
    }
    $sql = $sql_base . ' WHERE osgb_anagrafica.visitamedica <= \'' . $year . '-' . $month . '-31 00:00:00\'';
    $sql = $sql . ' AND osgb_anagrafica.visitamedica >= \'' . $year_s . '-' . $month_s . '-01 00:00:00\'';
    $sql = $sql . ' ORDER BY visitamedica, cognome';

    $result = Query($sql);
    $array = array('Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre');
    $month_str = $array[$month - 1];
    $msg = "Carissima segreteria,\n"
            . "     questo e' l'elenco delle visite mediche in scadenza nei prossimi tre mesi\n";

    $msg = $msg . "\n\n  ORDINATI PER DATA\n";
    $month_old = -1;
    WHILE ($details = mysql_fetch_array($result)):
        $dataVisitaMedica = strtotime($details["visitamedica"]);
        $day = date('d', $dataVisitaMedica);
        $month = date('m', $dataVisitaMedica);
        if ($month_old != $month) {
            $msg = $msg . "\n";
        }
        $month_old = $month;
        $year = date('Y', $dataVisitaMedica);
        $visitaDate = $day . "/" . $month . "/" . $year;
        $msg = $msg . "        " . $visitaDate . "\t\t" . $details['cognome'] . " " . $details['nome'] . "\n";
    endwhile;

    $sql_base = 'SELECT cf_id, nome, cognome, visitamedica, osgb_squadre.squadra FROM osgb_anagrafica, osgb_relazione, osgb_squadre';
    // Mail per segreteria: sscadenza a tre mesi
    $month = $month_s + 2;
    if ($month > 12) {
        $month = $month - 12;
        $year = $year_s + 1;
    }

    $sql = 'select max(id) from osgb_anno_sociale';
    $result = Query($sql);
    WHILE ($details = mysql_fetch_array($result)):
        $maxAnnoSociale = $details["max(id)"];
    endwhile;

    $maxAnnoSociale = $maxAnnoSociale - 1;
    $sql = $sql_base . ' WHERE osgb_anagrafica.visitamedica <= \'' . $year . '-' . $month . '-31 00:00:00\'';
    $sql = $sql . ' AND osgb_anagrafica.visitamedica >= \'' . $year_s . '-' . $month_s . '-01 00:00:00\'';
    $sql = $sql . ' AND osgb_anagrafica.cf_id = osgb_relazione.anagrafica';
    $sql = $sql . ' AND osgb_relazione.squadra = osgb_squadre.id AND annosociale > ' . $maxAnnoSociale;
    $sql = $sql . ' ORDER BY osgb_squadre.squadra, visitamedica, cognome';

    $result = Query($sql);
    $array = array('Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre');
    $month_str = $array[$month - 1];

    $msg = $msg . "\n\n  ORDINATI PER SQUADRA\n";
    $squadra_old = "";
    WHILE ($details = mysql_fetch_array($result)):
        $dataVisitaMedica = strtotime($details["visitamedica"]);
        $day = date('d', $dataVisitaMedica);
        $month = date('m', $dataVisitaMedica);
        $squadra = $details["squadra"];
        if ($squadra_old != $squadra) {
            $msg = $msg . "\n-------------------------------\n\n";
            $msg = $msg . "    " . $squadra . "\n";
            $msg = $msg . "\n";
        }
        $squadra_old = $squadra;
        $year = date('Y', $dataVisitaMedica);
        $visitaDate = $day . "/" . $month . "/" . $year;
        $msg = $msg . "        " . $visitaDate . "\t\t" . $details['cognome'] . " " . $details['nome'] . "\n";
    endwhile;



    $msg = $msg . "\n\n";
    $msg = $msg . "Ciao!\n\n";
    $msg = $msg . "P.S.\nQuesta mail e' stata generata automaticamente: per qualsiasi problema, contatta Matteo Ferrari!";

    $to = array('info@osgbmerate.it', 'damohanna@alice.it');
    $bcc = array('ferrmatt@gmail.com');
    sendMail('info@osgbmerate.it', $to, 'info@osgbmerate.it', 'Visite medica in scadenza', $msg, $bcc);
}

//    sendMailReminder(7);

sendMailReminder(30);

//    sendMailReminder(60);
?> 
