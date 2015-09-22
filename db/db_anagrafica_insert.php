<?php

require_once('../function.php');
checkLogin();
session_start();
Connect($host,$username,$password,$dbname);
 
$sql='INSERT INTO osgb_anagrafica (nome, cognome, luogo_di_nascita,data_di_nascita,
paese_di_residenza,via_piazza,codice_fiscale,
tessera_sanitaria,telefono,cellulare,mail) VALUES ('.
	'"'.pulisciCampi($_POST['nome']).'",'.
	'"'.pulisciCampi($_POST['cognome']).'",'.
	'"'.pulisciCampi($_POST['luogo_di_nascita']).'",'.
	'"'.pulisciCampi($_POST['data_di_nascita']).'",'.
	'"'.pulisciCampi($_POST['paese_di_residenza']).'",'.
	'"'.pulisciCampi($_POST['via_piazza']).'",'.
	'"'.pulisciCampi($_POST['codice_fiscale']).'",'.
	'"'.pulisciCampi($_POST['tessera_sanitaria']).'",'.
	'"'.pulisciCampi($_POST['telefono']).'",'.
	'"'.pulisciCampi($_POST['cellulare']).'",'.
	'"'.pulisciCampi($_POST['mail']).'"'.
	')';
	
$risultato = Query( $sql );	

header("Location: ../anagrafica_menu.php");
?>