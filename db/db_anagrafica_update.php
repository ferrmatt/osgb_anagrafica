<?php

require_once('../function.php');
checkLogin();
session_start();
Connect($host,$username,$password,$dbname);

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
nome='.'"'.pulisciCampi($_POST['nome']).'",'.'
cognome='.'"'.pulisciCampi($_POST['cognome']).'",'.'
luogo_di_nascita='.'"'.pulisciCampi($_POST['luogo_di_nascita']).'",'.'
data_di_nascita="'.$data_di_nascita.'",'.'
paese_di_residenza='.'"'.pulisciCampi($_POST['paese_di_residenza']).'",'.'
via_piazza='.'"'.pulisciCampi($_POST['via_piazza']).'",'.'
codice_fiscale='.'"'.pulisciCampi($_POST['codice_fiscale']).'",'.'
tessera_sanitaria='.'"'.pulisciCampi($_POST['tessera_sanitaria']).'",'.'
telefono='.'"'.pulisciCampi($_POST['telefono']).'",'.'
cellulare='.'"'.pulisciCampi($_POST['cellulare']).'",'.'
mail='.'"'.pulisciCampi($_POST['mail']).'",'.' 
redditi_nome='.'"'.pulisciCampi($_POST['redditi_nome']).'",'.' 
redditi_cognome='.'"'.pulisciCampi($_POST['redditi_cognome']).'",'.' 
redditi_paese_residenza='.'"'.pulisciCampi($_POST['redditi_paese_residenza']).'",'.' 
redditi_via_residenza='.'"'.pulisciCampi($_POST['redditi_via_residenza']).'",'.' 
redditi_codice_fiscale='.'"'.pulisciCampi($_POST['redditi_codice_fiscale']).'"
WHERE
cf_id='.'"'.pulisciCampi($_POST['id']).'"';

$risultato = Query( $sql );	

header("Location: ../modifica_anagrafica.php?cf_id=".$_POST['id']."");

?>