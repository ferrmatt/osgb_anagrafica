
<?php
require_once('calendar/calendar/classes/tc_calendar.php');    
require_once('./function.php');
checkLogin();
session_start();
?>

<script language="javascript" src="calendar/calendar/calendar.js"></script>

<html>
    <head>
        <?php css(); ?>
    </head>    
    <body bgcolor=#008000>

        <form name="inserisci_anagrafica" action="db/db_anagrafica_insert.php" method="post">
            <table border="0" cellspacing="5" cellpadding="5">

                <tr><td>Nome: </td><td><input type="text" name="nome" size="20" onblur="this.value = this.value.toUpperCase()"></td>
                    <td>Cognome: </td><td><input type="text" name="cognome" size="20" onblur="this.value = this.value.toUpperCase()"></td></tr>

                <tr><td>Luogo di Nascita: </td><td><input type="text" name="luogo_di_nascita" size="20" onblur="this.value = this.value.toUpperCase()"></td>
                    <td>Data di Nascita: </td>
                    <td>

                        <?php
                        $myCalendar = new tc_calendar("data_di_nascita", true);
                        $myCalendar->setDate(01, 01, 1960);
                        $myCalendar->setPath("calendar/calendar/");
                        $myCalendar->setYearInterval(1930, 2015);
                        $myCalendar->dateAllow('1930-01-01', '2015-12-31');
                        $myCalendar->setOnChange("date = 111111");
                        $myCalendar->writeScript();
                        ?>
                    </td>	
                </tr>
                <tr><td>Paese di Residenza: </td><td><input type="text" name="paese_di_residenza" size="20" onblur="this.value = this.value.toUpperCase()"></td>
                    <td>Via/Piazza: </td><td><input type="text" name="via_piazza" size="30" onblur="this.value = this.value.toUpperCase()"></td></tr>

                <tr><td>Cod. fiscale: </td><td><input type="text" name="codice_fiscale" size="16" onblur="this.value = this.value.toUpperCase()"></td>
                    <!--td>Tess. Sanitaria: </td><td><input type="text" name="tessera_sanitaria" size="16" onblur="this.value = this.value.toUpperCase()"></td--></tr>

                <tr><td>Telefono: </td><td><input type="text" name="telefono" size="16" onblur="this.value = this.value.toUpperCase()"></td>
                    <td>Cellulare: </td><td><input type="text" name="cellulare" size="16" onblur="this.value = this.value.toUpperCase()"></td></tr>
                <tr><table><td>Indirizzo E-mail: </td><td><input type="text" name="mail" size="40" onblur="this.value = this.value.toLowerCase()></td></table></tr>


            </table>
            <input type="submit" >
        </form>
    </body>
</html>

