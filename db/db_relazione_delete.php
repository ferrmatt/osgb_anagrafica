<?php

require_once('../function.php');
checkLogin();
session_start();
Connect($host,$username,$password,$dbname);
 
$sql='DELETE from osgb_relazione WHERE id = '.$_GET['id'];
	
$risultato = Query( $sql );	

header("Location: ../scheda_socio.php?anagrafica=".$_GET['anagrafica']);


?>

