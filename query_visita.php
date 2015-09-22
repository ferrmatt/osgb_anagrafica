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


        $sql = 'SELECT cf_id, nome, cognome, DATE(data_di_nascita) as data_di_nascita, visitamedica FROM osgb_anagrafica';

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
        
        $sql = $sql .  ' ORDER BY cognome, nome';

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
            $birthDate = $day."/".$month."/".$year;

            $dataVisitaMedica = strtotime($details["visitamedica"]);
            $day = date('d', $dataVisitaMedica);
                    $month = date('m', $dataVisitaMedica);
                    $year = date('Y', $dataVisitaMedica);
            $visitaDate = $day."/".$month."/".$year;

            echo "<tr><td nowrap=\"nowrap\"><p>",
            "<a href=\"modifica_visita.php?cf_id=", $details['cf_id'], "\" color=�blue�>Modifica</a>", "</p></td><td nowrap=\"nowrap\"><p>",
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


