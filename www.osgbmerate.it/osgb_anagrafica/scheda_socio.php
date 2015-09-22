<?php
require_once('calendar/calendar/classes/tc_calendar.php');
require_once('./function.php');
checkLogin();
session_start();

Connect($host, $username, $password, $dbname);

$sql = 'select osgb_relazione.anagrafica from osgb_relazione where osgb_relazione.id = ' . $_POST['myradio'];
$result = Query($sql);

WHILE ($details = mysql_fetch_array($result)) {
  $anagrafica = $details["anagrafica"];
}

$sql = 'select osgb_relazione.id as id, osgb_sezione.sezione, 
                 osgb_quota.quota, osgb_anno_sociale.stagione,          
                 osgb_anagrafica.cognome, osgb_anagrafica.nome, 
                 DATE(osgb_anagrafica.data_di_nascita) as data_di_nascita, osgb_squadre.squadra 
                from 
                 osgb_squadre, osgb_anagrafica, osgb_anno_sociale, 
                 osgb_quota, osgb_relazione, osgb_sezione 
                where 
                 osgb_relazione.anagrafica = osgb_anagrafica.cf_id AND
                 osgb_relazione.annosociale = osgb_anno_sociale.id AND
                 osgb_relazione.quota = osgb_quota.id AND
                 osgb_relazione.sezione = osgb_sezione.id AND
                 osgb_squadre.id = osgb_relazione.squadra AND
                 osgb_relazione.anagrafica = ' . $anagrafica
;

$result = Query($sql);

WHILE ($details = mysql_fetch_array($result)) {
    $savedSezione = $details["sezione"];
    $savedId = $details["id"];
    $savedNome = $details["nome"];
    $savedCognome = $details["cognome"];
    $savedDataDiNascita = strtotime($details["data_di_nascita"]);
    $anno_sociale = $details["stagione"];
    $sezione = $details["sezione"];
    $squadra = $details["squadra"];
    $quota = $details["quota"];
}

?>

<html>
    <head>
<?php css(); ?>
    </head>    
    <body>
        <form name="scheda_socio">
            <table border="0" cellspacing="5" cellpadding="5">                            
                <tr>
                <input type="hidden" name="id" value="<?php echo $savedId; ?>" >
                <td>Nome: </td><td><?php echo $savedNome; ?></td>
                <td>Cognome: </td><td><?php echo $savedCognome; ?></td></tr>
                <tr><td>Data di nascita: </td><td><?php echo $savedDataDiNascita; ?></td></tr>

            </table>
        </form>

        <br></br>
        <input type="button" value="Vai all'anagrafica" onClick="window.location.href = 'modifica_anagrafica.php?cf_id=<?php echo $savedCfId ?>'">            
        <input type="button" value="Torna al menu Soci" onClick="window.location.href = 'soci_menu.php'">            
    </body>
</html>            




