<?php

require_once('../function.php');
checkLogin();
session_start();
Connect($host, $username, $password, $dbname);

if (isset($_GET['figc'])) {
    $sql = 'INSERT INTO osgb_tesseramenti VALUES (' . $_GET['anagraficaId'] . ',' . $_GET['annoSociale'] . ',' . $_GET['figc'] . ',0,0) ON DUPLICATE KEY UPDATE tessera_figc="' . $_GET['figc'] . '"';
} else if (isset($_GET['fipav'])) {
    $sql = 'INSERT INTO osgb_tesseramenti VALUES (' . $_GET['anagraficaId'] . ',' . $_GET['annoSociale'] . ',' . $_GET['fipav'] . ',0,0) ON DUPLICATE KEY UPDATE tessera_fipav="' . $_GET['fipav'] . '"';
} else if (isset($_GET['csi'])) {
    $sql = 'INSERT INTO osgb_tesseramenti VALUES (' . $_GET['anagraficaId'] . ',' . $_GET['annoSociale'] . ',' . $_GET['csi'] . ',0,0) ON DUPLICATE KEY UPDATE tessera_csi="' . $_GET['csi'] . '"';
}

$risultato = Query($sql);

header("Location: ../scheda_socio.php?anagrafica=" . $_GET['anagraficaId']);
?>

