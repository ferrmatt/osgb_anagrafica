<?php

require_once('../function.php');
checkLogin();
session_start();
Connect($host, $username, $password, $dbname);

$sezione = pulisciCampi($_GET['sezione']);
$squadra = pulisciCampi($_GET['squadra']);

if ($sezione == '')
    $sezione = 3;
if ($squadra == '')
    $squadra = 1;

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