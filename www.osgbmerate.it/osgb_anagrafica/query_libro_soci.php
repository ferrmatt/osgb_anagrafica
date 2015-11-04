<?php
require_once('calendar/calendar/classes/tc_calendar.php');    
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

        $anno_sociale = pulisciCampi($_GET['anno_sociale']);
        $maggiorenni = pulisciCampi($_GET['maggiorenni']);
        if ($maggiorenni == "true") {
            $giorno = pulisciCampi($_GET['giorno']);
            $mese = pulisciCampi($_GET['mese']);
            if ($mese == 'Gennaio')
                $mese = 1;
            else if ($mese == 'Febbraio')
                $mese = 2;
            else if ($mese == 'Marzo')
                $mese = 3;
            else if ($mese == 'Aprile')
                $mese = 4;
            else if ($mese == 'Maggio')
                $mese = 5;
            else if ($mese == 'Giugno')
                $mese = 6;
            else if ($mese == 'Luglio')
                $mese = 7;
            else if ($mese == 'Agosto')
                $mese = 8;
            else if ($mese == 'Settembre')
                $mese = 9;
            else if ($mese == 'Ottobre')
                $mese = 10;
            else if ($mese == 'Novembre')
                $mese = 11;
            else if ($mese == 'Dicembre')
                $mese = 12;            
            $anno = pulisciCampi($_GET['anno']);
            $anno = $anno - 18;
        }


        $sql = 'select osgb_relazione.id, osgb_anagrafica.cf_id, osgb_sezione.sezione, 
                 osgb_quota.quota, osgb_anno_sociale.stagione, osgb_ruolo.ruolo,         
                 osgb_anagrafica.cognome, osgb_anagrafica.nome, DATE(osgb_anagrafica.data_di_nascita) as data_di_nascita, osgb_anagrafica.luogo_di_nascita,
                 osgb_anagrafica.paese_di_residenza, osgb_anagrafica.via_piazza, osgb_anagrafica.codice_fiscale, 
                 osgb_anagrafica.tessera_sanitaria, osgb_anagrafica.telefono, osgb_anagrafica.cellulare, 
                 osgb_anagrafica.mail, osgb_squadre.squadra 
                from 
                 osgb_squadre, osgb_anagrafica, osgb_anno_sociale, osgb_ruolo, 
                 osgb_quota, osgb_relazione, osgb_sezione 
                where 
                 osgb_relazione.anagrafica = osgb_anagrafica.cf_id AND
                 osgb_relazione.annosociale = osgb_anno_sociale.id AND
                 osgb_anno_sociale.stagione = \'' . $anno_sociale . '\' AND
                 osgb_relazione.ruolo = osgb_ruolo.id AND
                 osgb_relazione.quota = osgb_quota.id AND
                 osgb_relazione.sezione = osgb_sezione.id AND
                 osgb_relazione.squadra = osgb_squadre.id'; 
                if ($maggiorenni == "true") {
                  $sql = $sql . ' AND osgb_anagrafica.data_di_nascita <= \''.$anno.'-'.$mese.'-'.$giorno.' 00:00:00\'';
                }
                $sql = $sql . ' GROUP BY osgb_anagrafica.cf_id';
        

        $result = Query($sql);
        unset($_SESSION['array_excel']);
        $array_titoli = array('STAGIONE', 'COGNOME', 'NOME', 'SEZIONE', 'SQUADRA', 'RUOLO', 'NATO A', 'DATA DI NASCITA', 'E-MAIL', 'RESIDENZA', 'INDIRIZZO', 'COD. FISCALE', 'TESS. SANITARIA', 'CELLULARE', 'TELEFONO', 'QUOTA');
        
        $_SESSION['array_excel'] = array($array_titoli);
        echo "<table class=\"gradienttable\" >";
        echo "<tr>";
        foreach ($array_titoli as $i => $value) {
            echo "<th nowrap=\"nowrap\"><p>$value</p></th>";
        }
        echo "</tr>";
        
        WHILE ($details = mysql_fetch_array($result)):

            $dataDiNascita = strtotime($details["data_di_nascita"]);

            echo "<tr><td nowrap=\"nowrap\"><p>",
            $details['stagione'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['cognome'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['nome'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['sezione'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['squadra'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['ruolo'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['luogo_di_nascita'], "</p></td><td nowrap=\"nowrap\"><p>",
            date('d', $dataDiNascita), "/", date('m', $dataDiNascita), "/", date('Y', $dataDiNascita), "</p></td><td nowrap=\"nowrap\"><p>",
            $details['mail'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['paese_di_residenza'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['via_piazza'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['codice_fiscale'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['tessera_sanitaria'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['cellulare'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['telefono'], "</p></td><td nowrap=\"nowrap\"><p>",
            $details['quota'], "</p></td><tr>";

            $array_valori = array($details['stagione'],accentRemove($details['cognome']),accentRemove($details['nome']),
                $details['sezione'],$details['squadra'],$details['ruolo'],accentRemove($details['luogo_di_nascita']),
                $details["data_di_nascita"],$details['mail'],accentRemove($details['paese_di_residenza']),accentRemove($details['via_piazza']),
                $details['codice_fiscale'],$details['tessera_sanitaria'],$details['cellulare'],
                $details['telefono'],$details['quota']);
            array_push($_SESSION['array_excel'], $array_valori);             
        endwhile;
        echo "</table>";
        ?>
        <br></br>
      <input type="button" value="Esporta in Excel" onClick="window.location.href = 'excel_creator.php?type=libro_soci'">                    
      <input type="button" value="Torna al menu Soci" onClick="window.location.href = 'soci_menu.php'">            
    </body>
</html>
