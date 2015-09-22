<?php

require_once('../function.php');
checkLogin();
session_start();
Connect($host,$username,$password,$dbname);
 
$sql='INSERT INTO osgb_magazzino_carico (articolo, data_carico, quantita, taglia) VALUES ('.
	'"'.pulisciCampi($_POST['id_articolo']).'",'.
	'"'.pulisciCampi($_POST['data_carico']).'",'.
	'"'.pulisciCampi($_POST['quantita']).'",'.
	'"'.pulisciCampi($_POST['id_taglia']).'"'.
	')';
	
$risultato = Query( $sql );	

header("Location: ../carico_magazzino.php");


?>

