<?php

require "PHPMailer_v5.1/class.phpmailer.php";
require_once('./function.php');
/*checkLogin();*/
session_start();

Connect($host, $username, $password, $dbname);

$now = time();
$day = date('d', $now);
$month_s = date('m', $now);
$year_s = date('Y', $now);
$year = $year_s;

$messaggio = new PHPmailer();

if($day == 01)
{

    $sql_base = 'SELECT cf_id, nome, cognome, visitamedica FROM osgb_anagrafica';
    // Mail per segreteria: sscadenza a tre mesi
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
    $sql = $sql_base . ' WHERE osgb_anagrafica.visitamedica <= \'' . $year . '-' . $month . '-31 00:00:00\'';
    $sql = $sql . ' AND osgb_anagrafica.visitamedica >= \'' . $year_s . '-' . $month_s . '-01 00:00:00\'';
    $sql = $sql . ' AND osgb_anagrafica.cf_id = osgb_relazione.anagrafica';
    $sql = $sql . ' AND osgb_relazione.squadra = osgb_squadre.id AND annosociale>3';
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
            $msg = $msg . "    ". $squadra . "\n";
            $msg = $msg . "\n";
        }
        $squadra_old = $squadra;
        $year = date('Y', $dataVisitaMedica);
        $visitaDate = $day . "/" . $month . "/" . $year;
        $msg = $msg . "        " . $visitaDate . "\t\t" . $details['cognome'] . " " . $details['nome'] . "\n";
    endwhile;



    $msg = $msg . "\n\n";
    $msg = $msg . "Ciao!\n\n";
    $msg = $msg . "P.S.\nQuesta mail stata generata automaticamente: per qualsiasi problema, contatta Matteo Ferrari!";

    $messaggio->From = 'info@osgbmerate.it';
    $messaggio->AddAddress('info@osgbmerate.it');
//    $messaggio->AddAddress('ferrmatt@gmail.com');
    $messaggio->AddReplyTo('info@osgbmerate.it');
    $messaggio->FromName = "OSGB Merate";
    $messaggio->Subject = 'Visite medica in scadenza';
    $messaggio->Body = stripslashes($msg);

    if (!$messaggio->Send()) {
        echo $messaggio->ErrorInfo;
    } else {
        echo 'Email inviata correttamente!';
    }

    unset($messaggio);
}
?> 
