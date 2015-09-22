<?php
require_once('calendar/calendar/classes/tc_calendar.php');    
require_once('./function.php');
checkLogin();
session_start();
Connect($host, $username, $password, $dbname);
?>

<script language="javascript" src="calendar/calendar/calendar.js"></script>

<html>
    <head>
        <?php css(); ?>
    </head>    
    <body bgcolor=#008000>
        <form name="inserisci_magazzino" action="db/db_magazzino_insert.php" method="post">
            <table border="0" cellspacing="5" cellpadding="5">
                <tr>
                    <td> Articolo </td>
                    <td>
                        <select name = "id_articolo" >
                            <?php
                            $sql = 'SELECT Id, Descrizione FROM osgb_magazzino_articoli ORDER BY Descrizione DESC';
                            $result = Query($sql);
                            while ($riga = mysql_fetch_array($result)) {
                                $id_articolo = $riga['Id'];
                                $articolo = $riga['Descrizione'];
                                if (isset($_POST['articolo']) )
                                    echo "<option selected value=\"$articolo\">$articolo</option>";
                                else
                                    echo "<option value=\"$articolo\">$articolo</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td> Taglia </td>
                    <td>
                        <select name = "id_taglia" onchange = "run()" >
                            <?php
                            $sql = 'SELECT id, taglia FROM osgb_magazzino_taglie ORDER BY id ASC';
                            $result = Query($sql);
                            while ($riga = mysql_fetch_array($result)) {
                                $id_taglia = $riga['id'];
                                $taglia = $riga['taglia'];
                                if (isset($_POST['taglia']) )
                                    echo "<option selected value=\"$taglia\">$taglia</option>";
                                else
                                    echo "<option value=\"$taglia\">$taglia</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Quantita': </td><td><input type="number" name="quantita" size="20" onblur="this.value = this.value.toUpperCase()"></td>
                </tr>
                <tr>
                    <td>Data Carico: </td>
                    <td>

                        <?php
                        $myCalendar = new tc_calendar("data_carico", true);
                        $myCalendar->setDate(date('d'), date('n'), date('Y'));
                        $myCalendar->setPath("calendar/calendar/");
                        $myCalendar->setYearInterval(2014, 2020);
                        $myCalendar->dateAllow('2014-01-01', '2020-12-31');
                        $myCalendar->setOnChange("date = 111111");
                        $myCalendar->writeScript();
                        ?>
                    </td>	
                </tr>
            </table>
            <input type="submit" >
        </form>
    </body>
</html>


