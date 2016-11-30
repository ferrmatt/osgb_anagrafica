<?php
require_once('./function.php');
checkLogin();
session_start();
Connect($host, $username, $password, $dbname);
?>

<html>
    <head>
        <?php css(); ?>
        <script language="javascript"  type="text/javascript">

            function run() {
                document.forms['myform'].submit()
            }

        </script>
    </head>
    <body>
        <form name = "myform" method = "post" >
            <table border = "0" cellspacing = "5" cellpadding = "5" >
                <tr> <td> Nome: </td><td><input type="text"  name="nome" <?php
                        if (isset($_POST['nome']) && $_POST['nome'] != '') {
                            echo "value=" . $_POST['nome'];
                        }
                        ?> onchange = "this.value = this.value.toUpperCase();
                                run()" size="20" ></td > </tr>
                <tr> <td> Cognome: </td><td><input type="text" name="cognome" <?php
                        if (isset($_POST['cognome']) && $_POST['cognome'] != '') {
                            echo "value=" . $_POST['cognome'];
                        }
                        ?> onchange = "this.value = this.value.toUpperCase();
                                run()" size="20"></td> </tr>

                <tr>
                    <td> Anno di Nascita: </td>
                    <td>
                        <select name = "anno_nascita" onchange = "run()" >
                            <?php
                            echo "<option>TUTTI</option>";
                            $sql = 'SELECT id, stagione FROM osgb_anno_sociale ORDER BY stagione DESC';
                            $anno = 2013;
                            while ($anno > 1930) {
                                if (isset($_POST['anno_nascita']) && $_POST['anno_nascita'] == $anno)
                                    echo "<option selected value=\"$anno\">$anno</option>";
                                else
                                    echo "<option value=\"$anno\">$anno</option>";

                                $anno = $anno - 1;
                            }
                            ?>
                        </select>
                    </td>
                </tr>


                <tr>
                    <td> Anno Sociale: </td>
                    <td>
                        <select name = "anno_sociale" onchange = "run()" >
                            <?php
                            $sql = 'SELECT id, stagione FROM osgb_anno_sociale ORDER BY stagione DESC';
                            $result = Query($sql);
                            while ($riga = mysql_fetch_array($result)) {
                                $id = $riga['id'];
                                $stagione = $riga['stagione'];
                                if (isset($_POST['anno_sociale']) && $_POST['anno_sociale'] == $stagione)
                                    echo "<option selected value=\"$stagione\">$stagione</option>";
                                else
                                    echo "<option value=\"$stagione\">$stagione</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr >
                    <td > Sezione: </td>
                    <td >
                        <select name = "sezione" onchange = "run()" >
                            <?php
                            if (isset($_POST['ruolo']) && $_POST['ruolo'] != "TUTTI" && $_POST['ruolo'] != "Atleta" && $_POST['ruolo'] != "Allenatore" && $_POST['ruolo'] != "Dirigente" && $_POST['ruolo'] != "Arbitro" && $_POST['ruolo'] != "Segnapunti") {
                                echo "<option>TUTTE</option>";
                            } else {
                                echo "<option>TUTTE</option>";
                                $sql = 'SELECT id, sezione FROM osgb_sezione ORDER BY sezione DESC';
                                $result = Query($sql);
                                while ($riga = mysql_fetch_array($result)) {
                                    $id = $riga['id'];
                                    $sezione = $riga['sezione'];
                                    if (isset($_POST['sezione']) && $_POST['sezione'] == $sezione)
                                        echo "<option selected value=\"$sezione\">$sezione</option>";
                                    else
                                        echo "<option value=\"$sezione\">$sezione</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr >
                    <td> Squadra: </td>
                    <td>
                        <select name = "squadra" onchange = "run()">
                            <?php
                            if (isset($_POST['ruolo']) && $_POST['ruolo'] != "TUTTI" && $_POST['ruolo'] != "Atleta" && $_POST['ruolo'] != "Allenatore" && $_POST['ruolo'] != "Dirigente" && $_POST['ruolo'] != "Arbitro" && $_POST['ruolo'] != "Segnapunti") {
                                echo "<option>NESSUNA</option>";
                            } else {
                                echo "<option>TUTTE</option>";
                                if (isset($_POST['anno_sociale']) && isset($_POST['sezione'])) {
                                    $sql = 'SELECT osgb_squadre.squadra FROM osgb_squadre, osgb_annosociale_squadre, osgb_anno_sociale';
                                    if ($_POST['sezione'] != "TUTTE") {
                                        $sql = $sql . ', osgb_sezione';
                                    }

                                    $sql = $sql . ' WHERE osgb_squadre.id = osgb_annosociale_squadre.squadra AND '
                                            . 'osgb_annosociale_squadre.anno_sociale = osgb_anno_sociale.id AND '
                                            . 'osgb_anno_sociale.stagione = \'' . $_POST['anno_sociale'] . '\'';

                                    if ($_POST['sezione'] != "TUTTE") {
                                        $sql = $sql . ' AND osgb_annosociale_squadre.sezione = osgb_sezione.id AND '
                                                . 'osgb_sezione.sezione = \'' . $_POST['sezione'] . '\'';
                                    }
                                    $sql = $sql . ' ORDER BY osgb_squadre.id DESC';

                                    $result = Query($sql);
                                    if (mysql_num_rows($result) > 0) {
                                        while ($riga = mysql_fetch_array($result)) {
                                            $squadra = $riga['squadra'];
                                            if (isset($_POST['squadra']) && $_POST['squadra'] == $squadra)
                                                echo "<option selected value=\"$squadra\">$squadra</option>";
                                            else
                                                echo "<option value=\"$squadra\">$squadra</option>";
                                        }
                                    }
                                }
                            }
                            ?>
                        </select>                                   
                    </td>
                </tr>

                <tr >
                    <td> Ruolo: </td>
                    <td>
                        <select name = "ruolo" onchange = "run()">
                            <?php
                            echo "<option>TUTTI</option>";

                            $sql = 'SELECT id, ruolo FROM osgb_ruolo ORDER BY id ASC';
                            $result = Query($sql);
                            while ($riga = mysql_fetch_array($result)) {
                                $id = $riga['id'];
                                $ruolo = $riga['ruolo'];
                                if (isset($_POST['ruolo']) && $_POST['ruolo'] == $ruolo)
                                    echo "<option selected value=\"$ruolo\">$ruolo</option>";
                                else
                                    echo "<option value=\"$ruolo\">$ruolo</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr >
                    <td > Quota Associativa: </td>
                    <td >
                        <select name = "quota" onchange = "run()" >
                            <?php
                            echo "<option>TUTTE</option>";
                            $sql = 'SELECT id, quota FROM osgb_quota ORDER BY id ASC';
                            $result = Query($sql);
                            while ($riga = mysql_fetch_array($result)) {
                                $id = $riga['id'];
                                $quota = $riga['quota'];
                                if (isset($_POST['quota']) && $_POST['quota'] == $quota)
                                    echo "<option selected value=\"$quota\">$quota</option>";
                                else
                                    echo "<option value=\"$quota\">$quota</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <HR WIDTH="100%">
            <table border = "0" cellspacing = "5" cellpadding = "5" >
                <tr>
                    <td > <b><em>     Puo' giocare?</em>     </b> </td>
                </tr>

                <tr>
                    <td> Data partita: </td>
                    <td>
                        <?php
                        $array = array('Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre');

                        $now = time();
                        $day = date('d', $now);
                        $month = date('m', $now);
                        $month_str = $array[$month];
                        $year = date('Y', $now);

                        echo "<select name=\"giorno\" onchange=\"run()\">";

                        for ($i = 1; $i < 32; $i++)
                            if (isset($_POST['giorno']) && $_POST['giorno'] == $i)
                                echo "<option selected value=\"$i\">$i</option>";
                            else if (!isset($_POST['giorno']) && $day == $i)
                                echo "<option selected value=\"$day\">$day</option>";
                            else
                                echo "<option value=\"$i\">$i</option>";


                        echo "</select>";

                        echo "<select name=\"mese\" onchange=\"run()\">";

                        foreach ($array as $i => $value) {
                            if (isset($_POST['mese']) && $_POST['mese'] == $array[$i])
                                echo "<option selected value=\"$array[$i]\">$array[$i]</option>";
                            else if (!isset($_POST['mese']) && $month == ($i + 1))
                                echo "<option selected value=\"$array[$i]\">$array[$i]</option>";
                            else
                                echo "<option value=\"$array[$i]\">$array[$i]</option>";
                        }

                        echo "</select>";

                        echo "<select name=\"anno\" onchange=\"run()\">";
                        for ($i = 2010; $i < 2021; $i++)
                            if (isset($_POST['anno']) && $_POST['anno'] == $i)
                                echo "<option selected value=\"$i\">$i</option>";
                            else if (!isset($_POST['anno']) && $year == $i)
                                echo "<option selected value=\"$year\">$year</option>";
                            else
                                echo "<option value=\"$i\">$i</option>";


                        echo "</select>";
                        ?>

                </tr>

                <tr>
                    <td > Federazione: </td>
                <td > 
                    <select name="federazione" onchange="run()">

                        <?php
                        $federazione = 'Tutte';
                        if (isset($_POST['federazione']) && $_POST['federazione'] == $federazione)
                                    echo "<option selected value=\"$federazione\">$federazione</option>";
                                else
                                    echo "<option value=\"$federazione\">$federazione</option>";
                        ?>
                        <?php
                        $federazione = 'FIGC';
                        if (isset($_POST['federazione']) && $_POST['federazione'] == $federazione)
                                    echo "<option selected value=\"$federazione\">$federazione</option>";
                                else
                                    echo "<option value=\"$federazione\">$federazione</option>";
                        ?>
                        <?php
                        $federazione = 'FIPAV';
                        if (isset($_POST['federazione']) && $_POST['federazione'] == $federazione)
                                    echo "<option selected value=\"$federazione\">$federazione</option>";
                                else
                                    echo "<option value=\"$federazione\">$federazione</option>";
                        ?>
                        <?php
                        $federazione = 'CSI';
                        if (isset($_POST['federazione']) && $_POST['federazione'] == $federazione)
                                    echo "<option selected value=\"$federazione\">$federazione</option>";
                                else
                                    echo "<option value=\"$federazione\">$federazione</option>";
                        ?>
                       
                    </select>
                </td>

                </tr><tr>
                </tr>


            </table>
            <input type="button" value="Cerca Soci" onClick = "window.location.href = 'query_soci.php?nome=<?php echo $_POST['nome']; ?>&cognome=<?php echo $_POST['cognome']; ?>&anno_sociale=<?php echo $_POST['anno_sociale']; ?>&sezione=<?php echo $_POST['sezione']; ?>&squadra=<?php echo $_POST['squadra']; ?>&ruolo=<?php echo $_POST['ruolo']; ?>&quota=<?php echo $_POST['quota']; ?>&anno_nascita=<?php echo $_POST['anno_nascita']; ?>&giorno=<?php echo $_POST['giorno']; ?>&mese=<?php echo $_POST['mese']; ?>&anno=<?php echo $_POST['anno']; ?>&federazione=<?php echo $_POST['federazione']; ?>'" >
            <input type="button" value="Torna al menu Soci" onClick="window.location.href = 'soci_menu.php'">     
        </form>       
    </body>
</html>