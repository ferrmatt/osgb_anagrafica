<?php

require_once('../function.php');
checkLogin();
session_start();
Connect($host, $username, $password, $dbname);

if (isset($_GET["quota_libera"])) {
    $new_quota_libera = $_GET["quota_libera"];
    if ($new_quota_libera=='')
            $new_quota_libera='null';
    $sql = 'UPDATE osgb_relazione SET quota_libera=' . $new_quota_libera . " WHERE id=" . $_GET["id"];
    echo "aaaaa", $new_quota_libera;
} else {
    $quota = $_GET["quota"] + 1;
    $sql = 'UPDATE osgb_relazione SET quota=' . $quota . " WHERE id=" . $_GET["id"];
    echo "bbbbbbb", $quota;
}
echo $sql;
$risultato = Query($sql);

header("Location: ../scheda_socio.php?anagrafica=" . $_GET['anagrafica'] . "");
?>

