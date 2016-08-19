<?php

require_once('../function.php');
checkLogin();
session_start();
Connect($host, $username, $password, $dbname);

$sezione = pulisciCampi($_GET['sezione']);
$squadra = pulisciCampi($_GET['squadra']);
$quota = pulisciCampi($_GET['quota']);
$quota_libera = pulisciCampi($_GET['quota_libera']);

if ($sezione == '')
    $sezione = 3;
if ($squadra == '')
    $squadra = 1;
if ($quota==13)
$sql = 'INSERT INTO osgb_relazione (anagrafica, sezione, squadra,annosociale,
ruolo,quota,quota_libera) VALUES ('
        . $_GET['anagrafica'] . ','
        . $sezione . ','
        . $squadra . ','
        . $_GET['annosociale'] . ','
        . $_GET['ruolo'] . ','
        . $_GET['quota'] . ','
        . $quota_libera .
        ')';

    else
$sql = 'INSERT INTO osgb_relazione (anagrafica, sezione, squadra,annosociale,
ruolo,quota) VALUES ('
        . $_GET['anagrafica'] . ','
        . $sezione . ','
        . $squadra . ','
        . $_GET['annosociale'] . ','
        . $_GET['ruolo'] . ','
        . $_GET['quota'] .
        ')';

$risultato = Query($sql);

header("Location: ../scheda_socio.php?anagrafica=" . $_GET['anagrafica'] . "");
?>