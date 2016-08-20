<?php

require_once('../function.php');
checkLogin();
session_start();
Connect($host,$username,$password,$dbname);

$tess_figc=pulisciCampi($_POST['tessera_figc']);
$tess_fipav=pulisciCampi($_POST['tessera_fipav']);
$tess_csi=pulisciCampi($_POST['tessera_csi']);
//
//INSERT INTO osgb_tesseramenti
//VALUES($_POST['anagraficaId'], $_POST['annoSociale'], "$tess_figc", '1', '1', '1', '1', '1', '1', '1', 'N')
//ON DUPLICATE KEY UPDATE col_1 = VALUES(col_1), col_2 = VALUES(col_2), col_3 = VALUES(col_3), col_4 = VALUES(col_4), col_5 = VALUES(col_5), col_6 = VALUES(col_6), unit = VALUES(unit), add_info = VALUES(add_info), fsar_lock = VALUES(fsar_lock)
//


 
$sql='INSERT INTO osgb_tesseramenti VALUES ('.$_POST['anagraficaId'].','.$_POST['annoSociale'].',"'.$tess_figc.'","'.$tess_fipav.'","'.$tess_csi.'") ON DUPLICATE KEY UPDATE tessera_figc="'.$tess_figc.'", tessera_fipav="'.$tess_fipav.'", tessera_csi="'.$tess_csi.'"';
$risultato = Query( $sql );	

header("Location: ../scheda_socio.php?anagrafica=".$_POST['anagraficaId']);


?>

