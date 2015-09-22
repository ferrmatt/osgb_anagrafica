<?php
require_once('./function.php');
checkLogin();
session_start();
Connect($host, $username, $password, $dbname);

$id_anagrafica=$_GET['cf_id'];
?>

<html>
    <head>
        <?php css(); ?>
        <script language="javascript"  type="text/javascript">

            function run() {
                document.forms['aggiungi_ruolo'].submit()
            }

        </script>
    </head>    
    <body>
        <form name="aggiungi_ruolo" method = "post" >
            <table border="0" cellspacing="5" cellpadding="5">                            
                <tr><td>Nome: </td><td></td><td><?php echo stripslashes($_GET['nome']); ?></td></tr>
                <tr><td>Cognome: </td><td></td><td><?php echo stripslashes($_GET['cognome']); ?></td></tr>
                <tr><td>Data di nascita: </td><td></td><td><?php echo $_GET['data_di_nascita']; ?></td></tr>
                                <tr>
                    <td> Anno Sociale: </td><td></td>
                    <td>
                        <select name = "anno_sociale" onchange = "run()" >
                            <?php
                            $sql = 'SELECT id, stagione FROM osgb_anno_sociale ORDER BY stagione DESC';
                            $result = Query($sql);
                            while ($riga = mysql_fetch_array($result)) {
                                $id = $riga['id'];
                                $stagione = $riga['stagione'];
                                if (isset($_POST['anno_sociale']) && $_POST['anno_sociale'] == $stagione)
                                    {
                                        $id_annosociale = $riga['id'];
                                    echo "<option selected value=\"$stagione\">$stagione</option>";
                                    }
                                else
                                    echo "<option value=\"$stagione\">$stagione</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr >
                    <td > Sezione: </td><td></td>
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
                                        {
                                        $id_sezione = $riga['id'];
                                        echo "<option selected value=\"$sezione\">$sezione</option>";
                                        }
                                    else
                                        echo "<option value=\"$sezione\">$sezione</option>";
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr >
                    <td> Squadra: </td><td></td>
                    <td>
                        <select name = "squadra" onchange = "run()">
                            <?php
                            if (isset($_POST['ruolo']) && $_POST['ruolo'] != "TUTTI" && $_POST['ruolo'] != "Atleta" && $_POST['ruolo'] != "Allenatore" && $_POST['ruolo'] != "Dirigente" && $_POST['ruolo'] != "Arbitro" && $_POST['ruolo'] != "Segnapunti") {
                                echo "<option>NESSUNA</option>";
                            } else {
                                echo "<option>TUTTE</option>";
                                if (isset($_POST['anno_sociale']) && isset($_POST['sezione'])) {
                                    $sql = 'SELECT osgb_squadre.id, osgb_squadre.squadra FROM osgb_squadre, osgb_annosociale_squadre, osgb_anno_sociale';
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
                                                {
                                                $id_squadra = $riga['id'];
                                                echo "<option selected value=\"$squadra\">$squadra</option>";
                                                 }
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
                    <td> Ruolo: </td><td></td>
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
                                     {
                                        $id_ruolo = $riga['id'];
                                    echo "<option selected value=\"$ruolo\">$ruolo</option>";
                                    }
                                else
                                    echo "<option value=\"$ruolo\">$ruolo</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr >
                    <td > Quota Associativa: </td><td></td>
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
                                     {
                                        $id_quota = $riga['id'];
                                    echo "<option selected value=\"$quota\">$quota</option>";
                                    }
                                else
                                    echo "<option value=\"$quota\">$quota</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>

            </table>
        </form>

        <br></br>
        <input type="button" value="Inserisci" onClick="window.location.href = './db/db_ruolo_insert.php?anagrafica=<?php echo $id_anagrafica ?>&sezione=<?php echo $id_sezione ?>&ruolo=<?php echo $id_ruolo ?>&annosociale=<?php echo $id_annosociale ?>&squadra=<?php echo $id_squadra ?>&quota=<?php echo $id_quota ?>'">
        <input type="button" value="Torna al menu Soci" onClick="window.location.href = 'soci_menu.php'">            
    </body>
</html>        