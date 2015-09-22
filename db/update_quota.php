<?php

require_once('../function.php');
checkLogin();
session_start();
Connect($host, $username, $password, $dbname);

$quota = $_GET["quota"] + 1;

$sql = 'UPDATE osgb_relazione SET quota=' . $quota . " WHERE id=" . $_GET["id"];

echo $sql;
$risultato = Query($sql);

header("Location: ../scheda_socio.php?anagrafica=" . $_GET['anagrafica'] . "");
?>

