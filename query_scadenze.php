<?php
require_once('./function.php');
checkLogin();
session_start();
?>

<html>
    <head>
        <?php css(); ?>        
    </head>    
    <body>
        <?php
        Connect($host, $username, $password, $dbname);

        $sql = 'select max(id) from osgb_anno_sociale';
        $result = Query($sql);
        WHILE ($details = mysql_fetch_array($result)):
            $maxAnnoSociale = $details["max(id)"];
        endwhile;

        $sql = 'SELECT cf_id, nome, cognome, DATE(data_di_nascita) as data_di_nascita, visitamedica FROM osgb_anagrafica, osgb_relazione';
        $agiorno = pulisciCampi($_GET['agiorno']);
        $amese = pulisciCampi($_GET['amese']);
        if ($amese == 'Gennaio')
            $amese = 1;
        else if ($amese == 'Febbraio')
            $amese = 2;
        else if ($amese == 'Marzo')
            $amese = 3;
        else if ($amese == 'Aprile')
            $amese = 4;
        else if ($amese == 'Maggio')
            $amese = 5;
        else if ($amese == 'Giugno')
            $amese = 6;
        else if ($amese == 'Luglio')
            $amese = 7;
        else if ($amese == 'Agosto')
            $amese = 8;
        else if ($amese == 'Settembre')
            $amese = 9;
        else if ($amese == 'Ottobre')
            $amese = 10;
        else if ($amese == 'Novembre')
            $amese = 11;
        else if ($amese == 'Dicembre')
            $amese = 12;
        $aanno = pulisciCampi($_GET['aanno']);
        $dagiorno = pulisciCampi($_GET['dagiorno']);
        $damese = pulisciCampi($_GET['damese']);
        if ($damese == 'Gennaio')
            $damese = 1;
        else if ($damese == 'Febbraio')
            $damese = 2;
        else if ($damese == 'Marzo')
            $damese = 3;
        else if ($damese == 'Aprile')
            $damese = 4;
        else if ($damese == 'Maggio')
            $damese = 5;
        else if ($damese == 'Giugno')
            $damese = 6;
        else if ($damese == 'Luglio')
            $damese = 7;
        else if ($damese == 'Agosto')
            $damese = 8;
        else if ($damese == 'Settembre')
            $damese = 9;
        else if ($damese == 'Ottobre')
            $damese = 10;
        else if ($damese == 'Novembre')
            $damese = 11;
        else if ($damese == 'Dicembre')
            $damese = 12;
        $daanno = pulisciCampi($_GET['daanno']);

        $maxAnnoSociale = $maxAnnoSociale - 1;
        $sql = $sql . ' WHERE osgb_anagrafica.visitamedica <= \'' . $aanno . '-' . $amese . '-' . $agiorno . ' 00:00:00\'';
        $sql = $sql . ' AND osgb_anagrafica.visitamedica >= \'' . $daanno . '-' . $damese . '-' . $dagiorno . ' 00:00:00\'';
        $sql = $sql . ' AND osgb_anagrafica.cf_id = osgb_relazione.anagrafica ';
        $sql = $sql . ' AND annosociale > ' . $maxAnnoSociale;

        $sql = $sql . ' ORDER BY osgb_anagrafica.visitamedica';

        $result = Query($sql);

        unset($_SESSION['array_excel']);
        $array_titoli = array('', 'COGNOME', 'NOME', 'DATA DI NASCITA', 'VISITA MEDICA');
        $_SESSION['array_excel'] = array($array_titoli);
        echo "<table class=\"gradienttable\" >";
        echo "<tr>";
        foreach ($array_titoli as $i => $value) {
            echo "<th nowrap=\"nowrap\"><p>$value</p></th>";
        }
        echo "</tr>";

        $i = 0;
        WHILE ($details = mysql_fetch_array($result)):
            $i = $i + 1;
            $dataDiNascita = strtotime($details["data_di_nascita"]);
            $day = date('d', $dataDiNascita);
            $month = date('m', $dataDiNascita);
            $year = date('Y', $dataDiNascita);
            $birthDate = $day . "/" . $month . "/" . $year;

            $dataVisitaMedica = strtotime($details["visitamedica"]);
            $day = date('d', $dataVisitaMedica);
            $month = date('m', $dataVisitaMedica);
            $year = date('Y', $dataVisitaMedica);
            $visitaDate = $day . "/" . $month . "/" . $year;

            echo "<tr><td nowrap=\"nowrap\"><p>",
            "<a href=\"modifica_visita.php?cf_id=", $details['cf_id'], "\" > <img src=\"Images/edit.png\" title=\"Modifica scadenza Visita Medica\"></a>", "</p></td><td nowrap=\"nowrap\"><p>",
            $details['cognome'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['nome'], "</p></td><td nowrap=\"nowrap\"><p>",
            $birthDate, "</p></td><td nowrap=\"nowrap\"><p>",
            $visitaDate, "</p></td><td nowrap=\"nowrap\"></td>",
            "<tr>";

        endwhile;
        echo "</table>";
        ?>
    <td> Numero elementi: <?php echo $i ?></td>
    <br></br>
    <input type="button" value="Torna al menu Visite Mediche" onClick="window.location.href = 'visite_mediche.php'">            
</body>
</html>
