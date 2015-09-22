<?php

require_once('../function.php');
checkLogin();
session_start();
Connect($host, $username, $password, $dbname);

$giorno=pulisciCampi($_POST['giorno']);
$mese=pulisciCampi($_POST['mese']);
$anno=pulisciCampi($_POST['anno']);

            if ($mese == 'Gennaio')
                $mese = 1;
            else if ($mese == 'Febbraio')
                $mese = 2;
            else if ($mese == 'Marzo')
                $mese = 3;
            else if ($mese == 'Aprile')
                $mese = 4;
            else if ($mese == 'Maggio')
                $mese = 5;
            else if ($mese == 'Giugno')
                $mese = 6;
            else if ($mese == 'Luglio')
                $mese = 7;
            else if ($mese == 'Agosto')
                $mese = 8;
            else if ($mese == 'Settembre')
                $mese = 9;
            else if ($mese == 'Ottobre')
                $mese = 10;
            else if ($mese == 'Novembre')
                $mese = 11;
            else if ($mese == 'Dicembre')
                $mese = 12;            


$data_di_nascita=$anno."-".$mese."-".$giorno;

$sql='UPDATE osgb_anagrafica SET 

visitamedica="'.$data_di_nascita.'" WHERE
cf_id='.pulisciCampi($_POST['id']);

$risultato = Query( $sql );	

header("Location: ../modifica_visita.php?cf_id=".$_POST['id']."");
?>