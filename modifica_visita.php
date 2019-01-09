<?php
require_once('calendar/calendar/classes/tc_calendar.php');
require_once('function.php');
checkLogin();
session_start();
Connect($host, $username, $password, $dbname);

$sql = 'SELECT count(*) as counter FROM osgb_relazione where 
anagrafica = ' . $_GET['cf_id'] . ' AND annosociale in (select max(id) from osgb_anno_sociale)';

$result = Query($sql);

$details = mysql_fetch_array($result);

if ( $details["counter"] == 0 ) 
{
    echo "<td>";
    echo "ATTENZIONE: l'atleta non risulta iscritto per l'anno corrente!";
    echo "</td>";
}

$sql = 'SELECT cf_id, nome, cognome, luogo_di_nascita, data_di_nascita,
paese_di_residenza,via_piazza,codice_fiscale,
tessera_sanitaria,telefono,cellulare,mail, visitamedica FROM osgb_anagrafica where 
cf_id = ' . $_GET['cf_id'];

$result = Query($sql);

$details = mysql_fetch_array($result);

$savedId = $details["cf_id"];
$savedNome = $details["nome"];
$savedCognome = $details["cognome"];
$savedLuogoDiNascita = $details["luogo_di_nascita"];
$dataDiNascita = strtotime($details["data_di_nascita"]);
$savedPaeseDiResidenza = $details["paese_di_residenza"];
$savedViaPiazza = $details["via_piazza"];
$savedCodiceFiscale = $details["codice_fiscale"];
$savedTesseraSanitaria = $details["tessera_sanitaria"];
$savedTelefono = $details["telefono"];
$savedCellulare = $details["cellulare"];
$savedMail = $details["mail"];
$savedVisitaMedica = strtotime($details["visitamedica"]);
$day = date('d', $dataDiNascita);
$month = date('m', $dataDiNascita);
$year = date('Y', $dataDiNascita);
$birthDate = $day."/".$month."/".$year;

?>

<html>
    <head>
        <?php css(); ?>
    </head>    
    <body  bgcolor=#008000>
        <form name="modifica_visita" action="./db/db_visita_update.php" method="post">
            <table border="0" cellspacing="5" cellpadding="5">                            
                <tr>
                <input type="hidden" name="id" value="<?php echo $savedId; ?>" >
                <td>Nome: </td><td><?php echo $savedNome; ?></td></tr>
                <tr><td>Cognome: </td><td><?php echo $savedCognome; ?></td></tr>
                <tr><td>Data di nascita: </td><td><?php echo $birthDate; ?></td></tr>
                <td>Scadenza Visita Medica: </td>

                <?php
                    echo "<td>";
                    echo "<select name=\"giorno\" >";

                    for ($i = 1; $i < 32; $i++)
                        if (date('d', $savedVisitaMedica) == $i)
                            echo "<option selected value=\"$i\">$i</option>";
                        else
                            echo "<option value=\"$i\">$i</option>";

                    echo "</select>";

                    echo "<select name=\"mese\"";

                    $array = array('', 'Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre');
                    foreach ($array as $i => $value) {
                        if ((date('m', $savedVisitaMedica)) == $i)
                            echo "<option selected value=\"$array[$i]\">$array[$i]</option>";
                        else
                            echo "<option value=\"$array[$i]\">$array[$i]</option>";
                    }

                    echo "</select>";

                    echo "<select name=\"anno\" ";
                    for ($i = 2018; $i < 2025; $i++)
                        if (date('Y', $savedVisitaMedica) == $i)
                            echo "<option selected value=\"$i\">$i</option>";
                        else
                            echo "<option value=\"$i\">$i</option>";


                    echo "</select>";
                    echo "</td>";
                ?>

                </tr>
                
            </table>
            <br></br>
            <input type="submit" name = "modifica" value="Modifica" >
        </form>
        <form action="./Index.php">
            <input type="submit" value="Menu Principale">
        </form>
        <form action="./filter_visita.php">
            <input type="submit" value="Inserisci nuova scadenza">
        </form>
        <input type="button" value="  Vai alla Scheda socio  " onClick="window.location.href = 'scheda_socio.php?anagrafica='+ <?php echo $savedId; ?>">      
        <input type="button" value="  Vai all'Anagrafica  " onClick="window.location.href = 'modifica_anagrafica.php?cf_id='+ <?php echo $savedId; ?>">      
    </body>
</html>
