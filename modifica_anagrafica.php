<?php
require_once('calendar/calendar/classes/tc_calendar.php');
require_once('function.php');
checkLogin();
session_start();
Connect($host, $username, $password, $dbname);

$sql = 'SELECT cf_id, nome, cognome, luogo_di_nascita, data_di_nascita,
paese_di_residenza,via_piazza,codice_fiscale,
tessera_sanitaria,telefono,cellulare,mail, redditi_nome, redditi_cognome,
redditi_paese_residenza, redditi_via_residenza, redditi_codice_fiscale
FROM osgb_anagrafica where 
cf_id = ' . $_GET['cf_id'];

$result = Query($sql);

$details = mysql_fetch_array($result);

$savedId = $details["cf_id"];
$savedNome = $details["nome"];
$savedCognome = $details["cognome"];
$savedLuogoDiNascita = $details["luogo_di_nascita"];
$savedDataDiNascita = strtotime($details["data_di_nascita"]);
$savedPaeseDiResidenza = $details["paese_di_residenza"];
$savedViaPiazza = $details["via_piazza"];
$savedCodiceFiscale = $details["codice_fiscale"];
$savedTesseraSanitaria = $details["tessera_sanitaria"];
$savedTelefono = $details["telefono"];
$savedCellulare = $details["cellulare"];
$savedMail = $details["mail"];
$savedRedditiNome = $details["redditi_nome"];
$savedRedditiCognome = $details["redditi_cognome"];
$savedRedditiPaeseDiResidenza = $details["redditi_paese_residenza"];
$savedRedditiViaPiazza = $details["redditi_via_residenza"];
$savedRedditiCodiceFiscale = $details["redditi_codice_fiscale"];
?>

<html>
    <head>
        <?php css(); ?>
        <script type="text/javascript">
            var JSName = "<?php Print($savedNome); ?>";
            function doSomething()
            {
                if (document.getElementsById("nomeee").value === "MATTEO"){
                   document.getElementById("modifica_bottone").disabled = true;
               }
               else
               { 
                   document.getElementById("modifica_bottone").disabled = false;
               }
            }
        </script>
    </head>    
    <body  bgcolor=#008000>
        <form name="modifica_anagrafica" action="./db/db_anagrafica_update.php" method="post">
            <table border="0" cellspacing="5" cellpadding="5">                            
                <tr>
                <input type="hidden" name="id" value="<?php echo $savedId; ?>" >
                <h2>SCHEDA ANAGRAFICA </h2>
                <td>Nome: </td><td><input type="text" onchange="doSomething()" id="nomeee" name="nome" size="20" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedNome; ?>" ></td>
                <td>Cognome: </td><td><input type="text" name="cognome" size="20" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedCognome; ?>" ></td></tr>

                <tr><td>Luogo di Nascita: </td><td><input type="text" name="luogo_di_nascita" size="20" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedLuogoDiNascita; ?>" ></td>
                    <td>Data di Nascita: </td>

                <?php
                    echo "<td>";
                    echo "<select name=\"giorno\" >";

                    for ($i = 1; $i < 32; $i++)
                        if (date('d', $savedDataDiNascita) == $i)
                            echo "<option selected value=\"$i\">$i</option>";
                        else
                            echo "<option value=\"$i\">$i</option>";

                    echo "</select>";

                    echo "<select name=\"mese\"";

                    $array = array('', 'Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre');
                    foreach ($array as $i => $value) {
                        if ((date('m', $savedDataDiNascita)) == $i)
                            echo "<option selected value=\"$array[$i]\">$array[$i]</option>";
                        else
                            echo "<option value=\"$array[$i]\">$array[$i]</option>";
                    }

                    echo "</select>";

                    echo "<select name=\"anno\" ";
                    for ($i = 1930; $i < 2020; $i++)
                        if (date('Y', $savedDataDiNascita) == $i)
                            echo "<option selected value=\"$i\">$i</option>";
                        else
                            echo "<option value=\"$i\">$i</option>";


                    echo "</select>";
                    echo "</td>";
                ?>

                </tr>
                <tr><td>Paese di Residenza: </td><td><input type="text" name="paese_di_residenza" size="20" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedPaeseDiResidenza; ?>" ></td>
                    <td>Via/Piazza: </td><td><input type="text" name="via_piazza" size="30" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedViaPiazza; ?>" ></td></tr>

                <tr><td>Cod. fiscale: </td><td><input type="text" name="codice_fiscale" size="16" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedCodiceFiscale; ?>" ></td>
                    <td>Tess. Sanitaria: </td><td><input type="text" name="tessera_sanitaria" size="16" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedTesseraSanitaria; ?>" ></td></tr>

                <tr><td>Telefono: </td><td><input type="text" name="telefono" size="16" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedTelefono; ?>" ></td>
                    <td>Cellulare: </td><td><input type="text" name="cellulare" size="16" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedCellulare; ?>" ></td></tr>
                <tr><td>Indirizzo E-mail: </td><td><input type="text" name="mail" size="40" onblur="this.value = this.value.toLowerCase()" value="<?php echo $savedMail; ?>" ></td></tr>
            </table>
            <HR WIDTH="100%">
            <h2>DATI DICHIARANTE 730</h2>
            <table border="0" cellspacing="5" cellpadding="5">                            
                <tr>
                <td>Nome: </td><td><input type="text" name="redditi_nome" size="20" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedRedditiNome; ?>" ></td>
                <td>Cognome: </td><td><input type="text" name="redditi_cognome" size="20" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedRedditiCognome; ?>" ></td></tr>
                <tr><td>Paese di Residenza: </td><td><input type="text" name="redditi_paese_residenza" size="20" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedRedditiPaeseDiResidenza; ?>" ></td>
                <td>Via/Piazza: </td><td><input type="text" name="redditi_via_residenza" size="30" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedRedditiViaPiazza; ?>" ></td></tr>
                <tr><td>Cod. fiscale: </td><td><input type="text" name="redditi_codice_fiscale" size="16" onblur="this.value = this.value.toUpperCase()" value="<?php echo $savedRedditiCodiceFiscale; ?>" ></td>
            </table>
           <HR WIDTH="100%">
            <input type="submit" id = "modifica_bottone" name = "modifica_bottone" value="Salva Modifiche" onchange="doSomething(this.value)" >
        </form>
        <form action="./Index.php">
            <input type="submit" value="Menu Principale">
        </form>
        <input type="button" value="  Scheda socio  " onClick="window.location.href = 'scheda_socio.php?anagrafica='+ <?php echo $savedId; ?>">      
    </body>
</html>
