<?php
require_once('calendar/calendar/classes/tc_calendar.php');
require_once('./function.php');
checkLogin();
session_start();
?>

<html>
    <head>
        <?php css(); ?>
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.17/jquery-ui.min.js"></script>
        <script type="text/javascript">
            $(function() {
                var $join = $("input[name=scheda]");
                var processJoin = function(element) {
                    $join.removeAttr("disabled")
                };

                $(":radio[name=myradio]").click(function() {
                    processJoin(this);
                }).filter(":checked").each(function() {
                    processJoin(this);
                });
            });
        </script>        
    </head>    
    <body>
        <form name="query_soci" action="scheda_socio.php" method="post">
            <?php
            $nome = pulisciCampi($_GET['nome']);
            $cognome = pulisciCampi($_GET['cognome']);
            $anno_nascita = pulisciCampi($_GET['anno_nascita']);
            $anno_sociale = pulisciCampi($_GET['anno_sociale']);
            $sezione = pulisciCampi($_GET['sezione']);
            $squadra = pulisciCampi($_GET['squadra']);
            $ruolo = pulisciCampi($_GET['ruolo']);
            $quota = pulisciCampi($_GET['quota']);

            Connect($host, $username, $password, $dbname);


            $sql = 'select osgb_relazione.id as id, osgb_anagrafica.cf_id, osgb_sezione.sezione, 
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
                 osgb_relazione.ruolo = osgb_ruolo.id AND
                 osgb_relazione.quota = osgb_quota.id AND
                 osgb_relazione.sezione = osgb_sezione.id
                ';

            if ($squadra == 'TUTTE')
                $sql = $sql . ' AND osgb_relazione.squadra = osgb_squadre.id';

            if ($nome != '')
                $sql = $sql . ' AND osgb_anagrafica.nome like \'%' . $nome . '%\'';

            if ($cognome != '')
                $sql = $sql . ' AND osgb_anagrafica.cognome like \'%' . $cognome . '%\'';

            if ($anno_nascita != 'TUTTI')
                $sql = $sql . ' AND osgb_anagrafica.data_di_nascita like \'%' . $anno_nascita . '%\'';

            if ($anno_sociale != 'TUTTI')
                $sql = $sql . ' AND osgb_anno_sociale.stagione = \'' . $anno_sociale . '\'';

            if ($sezione != 'TUTTE')
                $sql = $sql . ' AND osgb_sezione.sezione = \'' . $sezione . '\'';

            if ($squadra != 'TUTTE')
                $sql = $sql . ' AND osgb_squadre.squadra = \'' . $squadra . '\'';

            if ($ruolo != 'TUTTI')
                $sql = $sql . ' AND osgb_ruolo.ruolo = \'' . $ruolo . '\'';

            if ($quota != 'TUTTE')
                $sql = $sql . ' AND osgb_quota.quota = \'' . $quota . '\'';

            $sql = $sql . ' ORDER BY cognome, nome';

            #echo $sql;

            $result = Query($sql);

            if (isset($_SESSION['array_excel']))
                unset($_SESSION['array_excel']);
            $array_titoli = array('STAGIONE', 'COGNOME', 'NOME', 'SEZIONE', 'SQUADRA', 'RUOLO', 'NATO A', 'DATA DI NASCITA', 'E-MAIL', 'RESIDENZA', 'INDIRIZZO', 'COD. FISCALE'/*, 'TESS. SANITARIA'*/, 'CELLULARE', 'TELEFONO', 'QUOTA');

            $_SESSION['array_excel'] = array($array_titoli);
            echo "<table class=\"gradienttable\" >";
            echo "<tr>";
            echo "<th nowrap=\"nowrap\"><p></p></th>";
            foreach ($array_titoli as $i => $value) {
                echo "<th nowrap=\"nowrap\"><p>$value</p></th>";
            }
            echo "</tr>";
            WHILE ($details = mysql_fetch_array($result)):

                $dataDiNascita = strtotime($details["data_di_nascita"]);

                echo "<tr><td><input type=\"radio\" name=\"myradio\" class=\"radio\" value=" . $details['id'] . "></td>";
                echo "<td nowrap=\"nowrap\"><p>",
                $details['stagione'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['cognome'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['nome'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['sezione'], "</p></td><td nowrap=\"nowrap\"><p>";
                if ($squadra != 'NESSUNA')
                    echo $details['squadra'], "</p></td><td nowrap=\"nowrap\"><p>";

                echo $details['ruolo'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['luogo_di_nascita'], "</p></td><td nowrap=\"nowrap\"><p>",
                date('d', $dataDiNascita), "/", date('m', $dataDiNascita), "/", date('Y', $dataDiNascita), "</p></td><td nowrap=\"nowrap\"><p>",
                $details['mail'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['paese_di_residenza'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['via_piazza'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['codice_fiscale'], "</p></td><td nowrap=\"nowrap\"><p>",
               // $details['tessera_sanitaria'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['cellulare'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['telefono'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['quota'], "</p></td><tr>";

                $array_valori = array($details['stagione'], accentRemove($details['cognome']), accentRemove($details['nome']),
                    $details['sezione'], $details['squadra'], $details['ruolo'], accentRemove($details['luogo_di_nascita']),
                    $details["data_di_nascita"], $details['mail'], accentRemove($details['paese_di_residenza']), accentRemove($details['via_piazza']),
                    $details['codice_fiscale']/*, $details['tessera_sanitaria']*/, $details['cellulare'],
                    $details['telefono'], $details['quota']);
                array_push($_SESSION['array_excel'], $array_valori);
            endwhile;


            echo "</table>";
            ?>
            <br></br>     
            <input type="submit" name="scheda" value="Scheda Socio" disabled>
        </form>

        <br></br>
        <input type="button" value="Esporta in Excel" onClick="window.location.href = 'excel_creator.php?type=soci'">            
        <input type="button" value="Torna al menu Soci" onClick="window.location.href = 'soci_menu.php'">            
    </body>
</html>


