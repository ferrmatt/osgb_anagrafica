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


        $sql = 'SELECT cf_id, nome, cognome, luogo_di_nascita, DATE(data_di_nascita) as data_di_nascita, paese_di_residenza, via_piazza, 
codice_fiscale, tessera_sanitaria, telefono, cellulare, mail FROM osgb_anagrafica ORDER BY cognome, nome';

        $nome = pulisciCampi($_POST['nome']);
        $cognome = pulisciCampi($_POST['cognome']);

        if (!empty($nome) or !empty($cognome)):
            $sql = $sql . " WHERE ";
            if (!empty($nome)):
                $sql = $sql . "nome like '%" . $nome . "%'";
            endif;

            if (!empty($cognome)):
                if (!empty($nome)):
                    $sql = $sql . " AND ";
                endif;
                $sql = $sql . "cognome like '%" . $cognome . "%'";
            endif;

        endif;

        $result = Query($sql);
        
        unset($_SESSION['array_excel']);
        $array_titoli = array('', 'COGNOME', 'NOME', 'NATO A', 'DATA DI NASCITA', 'E-MAIL', 'RESIDENZA', 'INDIRIZZO', 'COD. FISCALE', 'TESS. SANITARIA', 'CELLULARE', 'TELEFONO');
        $_SESSION['array_excel'] = array($array_titoli);
        echo "<table class=\"gradienttable\" >";
        echo "<tr>";
        foreach ($array_titoli as $i => $value) {
            echo "<th nowrap=\"nowrap\"><p>$value</p></th>";
        }
        echo "</tr>";

        WHILE ($details = mysql_fetch_array($result)):

            $dataDiNascita = strtotime($details["data_di_nascita"]);
            $day = date('d', $dataDiNascita);
                    $month = date('m', $dataDiNascita);
                    $year = date('Y', $dataDiNascita);
            $birthDate = $day."/".$month."/".$year;

            echo "<tr><td nowrap=\"nowrap\"><p>",
            "<a href=\"modifica_anagrafica.php/?cf_id=", $details['cf_id'], "\" color=�blue�>Modifica</a>", "</p></td><td nowrap=\"nowrap\"><p>",
            $details['cognome'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['nome'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['luogo_di_nascita'], "</p></td><td nowrap=\"nowrap\"><p>",
            $birthDate, "</p></td><td nowrap=\"nowrap\"><p>",
            $details['mail'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['paese_di_residenza'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['via_piazza'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['codice_fiscale'], "</p></td><td nowrap=\"nowrap\"><p>",
            //$details['tessera_sanitaria'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['cellulare'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['telefono'], "</p></td><tr>";

            $array_valori = array(accentRemove($details['cognome']),accentRemove($details['nome']),
                accentRemove($details['luogo_di_nascita']),
                $details["data_di_nascita"],$details['mail'],accentRemove($details['paese_di_residenza']),accentRemove($details['via_piazza']),
                $details['codice_fiscale']/*,$details['tessera_sanitaria']*/,$details['cellulare'],
                $details['telefono']);
            array_push($_SESSION['array_excel'], $array_valori);            
        endwhile;
        echo "</table>";
        ?>
        <br></br>
      <input type="button" value="Esporta in Excel" onClick="window.location.href = 'excel_creator.php?type=anagrafica'">            
      <input type="button" value="Torna al menu Anagrafica" onClick="window.location.href = 'anagrafica_menu.php'">            
    </body>
</html>


