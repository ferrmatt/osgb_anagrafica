<?php
require_once('function.php');
checkLogin();
session_start();
Connect($host, $username, $password, $dbname);

$relazione = $_GET['id'];
$sql = 'SELECT osgb_relazione.id, osgb_anagrafica.cf_id, osgb_anno_sociale.stagione, osgb_anno_sociale.id as anno_sociale_id, osgb_anagrafica.cognome, osgb_anagrafica.nome, osgb_anno_sociale.stagione, osgb_tesseramenti.tessera_figc, osgb_tesseramenti.tessera_fipav, osgb_tesseramenti.tessera_csi
FROM osgb_relazione
LEFT OUTER JOIN osgb_tesseramenti ON osgb_relazione.anagrafica = osgb_tesseramenti.anagrafica
AND osgb_relazione.annosociale = osgb_tesseramenti.anno_sociale, osgb_anagrafica, osgb_anno_sociale
WHERE osgb_anagrafica.cf_id = osgb_relazione.anagrafica
AND osgb_relazione.annosociale = osgb_anno_sociale.id
AND osgb_relazione.id =' . $relazione;

$result = Query($sql);

$details = mysql_fetch_array($result);

$savedAnagraficaId = $details["cf_id"];
$savedNome = $details["nome"];
$savedCognome = $details["cognome"];
$savedStagione = $details["stagione"];
$savedAnnoSociale = $details["anno_sociale_id"];
$savedTesseraFigc = $details["tessera_figc"];
$savedTesseraFipav = $details["tessera_fipav"];
$savedTesseraCsi = $details["tessera_csi"];
?>

<html>
    <head>
        <?php css(); ?>
    </head>    
    <body>
        <form name="dati_tesseramento" action="./db/db_tesseramenti_update.php" method="post">
            <table border="0" cellspacing="5" cellpadding="5">                            
                <tr>
                    <input type="hidden" name="relazioneId" value="<?php echo $relazione; ?>" >
                <input type="hidden" name="anagraficaId" value="<?php echo $savedAnagraficaId; ?>" >
                <input type="hidden" name="annoSociale" value="<?php echo $savedAnnoSociale; ?>" >
                <td>Nome: </td><td><?php echo $savedNome; ?></td></tr>
                <tr><td>Cognome: </td><td><?php echo $savedCognome; ?></td></tr>
                <tr><td>Stagione: </td><td><?php echo $savedStagione; ?></td></tr>
                <tr><td>Tessera FIGC: </td><td><input type="text" name="tessera_figc" size="30" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedTesseraFigc; ?>" ></td></tr>
                <tr><td>Tessera FIPAV: </td><td><input type="text" name="tessera_fipav" size="30" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedTesseraFipav; ?>" ></td></tr>
                <tr><td>Tessera CSI: </td><td><input type="text" name="tessera_csi" size="30" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedTesseraCsi; ?>" ></td></tr>

            </table>
            <br></br>
            <input type="submit" id = "modifica_tess_bottone" name = "modifica_tess_bottone" value="Salva Modifiche">
        </form>
        <input type="button" value="  Torna a Scheda socio senza salvare " onClick="window.location.href = 'scheda_socio.php?anagrafica='+ <?php echo $savedAnagraficaId; ?>">      


    </body>
</html>   