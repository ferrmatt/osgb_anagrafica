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
            $(function () {
                var $join = $("input[name=scheda]");
                var processJoin = function (element) {
                    $join.removeAttr("disabled")
                };

                $(":radio[name=myradio]").click(function () {
                    processJoin(this);
                }).filter(":checked").each(function () {
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
            $giorno_partita = pulisciCampi($_GET['giorno']);
            $federazione = pulisciCampi($_GET['federazione']);

            if ($squadra == '')
                $squadra = 'TUTTE';
            if ($anno_nascita == '')
                $anno_nascita = 'TUTTI';
            if ($anno_sociale == '')
                $anno_sociale = 'TUTTI';
            if ($sezione == '')
                $sezione = 'TUTTE';
            if ($squadra == '')
                $squadra = 'TUTTE';
            if ($ruolo == '')
                $ruolo = 'TUTTI';
            if ($quota == '')
                $quota = 'TUTTE';

            Connect($host, $username, $password, $dbname);

            $sql = 'select osgb_relazione.id as id, osgb_anagrafica.cf_id, osgb_sezione.sezione, 
                 IF(osgb_quota.quota=\'Altro\',osgb_relazione.quota_libera,osgb_quota.quota) as quota, osgb_anno_sociale.stagione, osgb_ruolo.ruolo,         
                 osgb_anagrafica.cognome, osgb_anagrafica.nome, DATE(osgb_anagrafica.data_di_nascita) as data_di_nascita, osgb_anagrafica.luogo_di_nascita,
                 osgb_anagrafica.paese_di_residenza, osgb_anagrafica.via_piazza, osgb_anagrafica.codice_fiscale, 
                 osgb_anagrafica.tessera_sanitaria, osgb_anagrafica.telefono, osgb_anagrafica.cellulare, 
                 osgb_anagrafica.mail, osgb_squadre.squadra, DATE(osgb_anagrafica.visitamedica) as visita_medica,
                 osgb_tesseramenti.tessera_figc, osgb_tesseramenti.tessera_fipav, 
                 osgb_tesseramenti.tessera_csi
                from 
                 osgb_squadre, osgb_anagrafica, osgb_anno_sociale, osgb_ruolo, 
                 osgb_quota, 
                 osgb_sezione,
                 osgb_relazione LEFT OUTER JOIN osgb_tesseramenti ON osgb_relazione.anagrafica=osgb_tesseramenti.anagrafica AND 
                 osgb_relazione.annosociale=osgb_tesseramenti.anno_sociale
                where 
                 osgb_relazione.anagrafica = osgb_anagrafica.cf_id AND
                 osgb_relazione.annosociale = osgb_anno_sociale.id AND
                 osgb_relazione.ruolo = osgb_ruolo.id AND
                 osgb_relazione.quota = osgb_quota.id AND
                 osgb_relazione.sezione = osgb_sezione.id AND
                 osgb_relazione.squadra = osgb_squadre.id
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

            //echo $sql;

            $result = Query($sql);

            if (isset($_SESSION['array_excel']))
                unset($_SESSION['array_excel']);
            $array_titoli = array('STATO ATLETA', 'STAGIONE', 'COGNOME', 'NOME', 'SEZIONE', 'SQUADRA', 'RUOLO', 'NATO A', 'DATA DI NASCITA', 'E-MAIL', 'RESIDENZA', 'INDIRIZZO', 'COD. FISCALE'/* , 'TESS. SANITARIA' */, 'CELLULARE', 'TELEFONO', 'QUOTA', 'SCADENZA VISITA MEDICA', 'TESSERA FIGC', 'TESSERA FIPAV', 'TESSERA CSI');

            $_SESSION['array_excel'] = array($array_titoli);
            echo "<table class=\"gradienttable\" >";
            echo "<tr>";
            echo "<th nowrap=\"nowrap\"><p></p></th>";
            foreach ($array_titoli as $i => $value) {
                echo "<th nowrap=\"nowrap\"><p>$value</p></th>";
            }
            echo "</tr>";
            
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
            
            
            $i = 0;
            WHILE ($details = mysql_fetch_array($result)):
                $i = $i + 1;
                $dataDiNascita = strtotime($details["data_di_nascita"]);
                $tesseraFigc = $details['tessera_figc'];
                $tesseraFipav = $details['tessera_fipav'];
                $tesseraCsi = $details['tessera_csi'];

                $visitaMedicaOk = true;
                $federazioneOk = true;
                $quotaOk = true;

                if ($year == 1970 || $year == -0001) {
                    $visitaMedica = "-";
                    $visitaMedicaOk = false;
                } else {
                    $visitaMedica = $day . "-" . $month . "-" . $year;
                    $giorno_gara = $giorno . "-" . $mese . "-" . $anno;
                    $visitaMedicaNew = strtotime($visitaMedica);
                    $giorno_gara_new = strtotime($giorno_gara);
                  
                    if ($giorno_gara_new > $visitaMedicaNew)
                        $visitaMedicaOk = false;
                }

                if ($federazione != 'Tutte') {
                    if ($federazione == 'FIGC' && !$tesseraFigc)
                        $federazioneOk = false;
                    if ($federazione == 'FIPAV' && !$tesseraFipav)
                        $federazioneOk = false;
                    if ($federazione == 'CSI' && !$tesseraCsi)
                        $federazioneOk = false;
                }
                else {
                    if (!$tesseraFigc && !$tesseraFipav && !$tesseraCsi)
                        $federazioneOk = false;
                }

                if ($details['quota'] == 'Attesa Quota')
                    $quotaOk = false;
                
                $puogiocare = "";

                echo "<tr><td style=\"text-align:center\" >&nbsp;&nbsp;<a href=\"scheda_socio.php?anagrafica=", $details['cf_id'], "\"><img src=\"Images/socio.gif\" title=\"Scheda Socio\"</a></td>";
                echo "<td style=\"text-align:center\" nowrap=\"nowrap\"><p>";
                if ($details['ruolo'] == 'Atleta') {
                    if (!$quotaOk)
                        echo "<img src=\"Images/dollaro.png\" title=\"Quota non in regola\"</a>&nbsp;&nbsp;";
                    if (!$federazioneOk)
                        echo "<img src=\"Images/federazione.png\" title=\"Tesseramento non in regola\"</a>&nbsp;&nbsp;";
                    if (!$visitaMedicaOk)
                        echo "<img src=\"Images/visita.png\" title=\"Visita medica non in regola\"</a>&nbsp;&nbsp;";

                    if ($quotaOk && $federazioneOk && $visitaMedicaOk)
                    {
                        echo "<img src=\"Images/ok.png\" title=\"Puo' giocare!!!\"</a>&nbsp;&nbsp;";
                        $puogiocare = "OK";
                    }
                    else
                    {
                        $puogiocare = "NON puo' giocare!";
                    }
                }
                else {
                    
                }
                echo "</p></td><td nowrap=\"nowrap\"><p>",
                $details['stagione'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['cognome'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['nome'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['sezione'], "</p></td><td nowrap=\"nowrap\"><p>";
                if ($squadra != 'NESSUNA')
                    echo $details['squadra'], "</p></td><td nowrap=\"nowrap\"><p>";

                $savedVisitaMedica = strtotime($details["visita_medica"]);
                $day = date('d', $savedVisitaMedica);
                $month = date('m', $savedVisitaMedica);
                $year = date('Y', $savedVisitaMedica);

                echo $details['ruolo'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['luogo_di_nascita'], "</p></td><td nowrap=\"nowrap\"><p>",
                date('d', $dataDiNascita), "/", date('m', $dataDiNascita), "/", date('Y', $dataDiNascita), "</p></td><td nowrap=\"nowrap\"><p>",
                $details['mail'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['paese_di_residenza'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['via_piazza'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['codice_fiscale'], "</p></td><td nowrap=\"nowrap\"><p>",
                //  $details['tessera_sanitaria'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['cellulare'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['telefono'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['quota'], "</p></td><td nowrap=\"nowrap\"><p>",
                $visitaMedica, "</p></td><td nowrap=\"nowrap\" style=\"text-align: center;\"><p>";
                if ($tesseraFigc == 1) {
                    echo "<input disabled=\"true\" onChange=\"window.location.href = 'db/db_tesseramenti_update.php?anagraficaId=", $anagrafica, "&annoSociale=", $details['anno_sociale_id'], "&figc=0' \" type=\"checkbox\" checked name=\"tessera_figc\" value=\"tessera_figc\" onchange=\"run()\"/>";
                } else {
                    echo "<input disabled=\"true\" onChange=\"window.location.href = 'db/db_tesseramenti_update.php?anagraficaId=", $anagrafica, "&annoSociale=", $details['anno_sociale_id'], "&figc=1' \" type=\"checkbox\" name=\"tessera_figc\" value=\"tessera_figc\" onchange=\"run()\"/>";
                }

                echo "</select></p></td><td nowrap=\"nowrap\" style=\"text-align: center;\"><p>";

                if ($tesseraFipav == 1) {
                    echo "<input disabled=\"true\" onChange=\"window.location.href = 'db/db_tesseramenti_update.php?anagraficaId=", $anagrafica, "&annoSociale=", $details['anno_sociale_id'], "&fipav=0' \" type=\"checkbox\" checked name=\"tessera_fipav\" value=\"tessera_fipav\" onchange=\"run()\"/>";
                } else {
                    echo "<input disabled=\"true\" onChange=\"window.location.href = 'db/db_tesseramenti_update.php?anagraficaId=", $anagrafica, "&annoSociale=", $details['anno_sociale_id'], "&fipav=1' \" type=\"checkbox\" name=\"tessera_fipav\" value=\"tessera_fipav\" onchange=\"run()\"/>";
                }

                echo "</select></p></td><td nowrap=\"nowrap\" style=\"text-align: center;\"><p>";

                if ($tesseraCsi == 1) {
                    echo "<input disabled=\"true\" onChange=\"window.location.href = 'db/db_tesseramenti_update.php?anagraficaId=", $anagrafica, "&annoSociale=", $details['anno_sociale_id'], "&csi=0' \" type=\"checkbox\" checked name=\"tessera_csi\" value=\"tessera_csi\" onchange=\"run()\"/>";
                } else {
                    echo "<input disabled=\"true\" onChange=\"window.location.href = 'db/db_tesseramenti_update.php?anagraficaId=", $anagrafica, "&annoSociale=", $details['anno_sociale_id'], "&csi=1' \" type=\"checkbox\" name=\"tessera_csi\" value=\"tessera_csi\" onchange=\"run()\"/>";
                }

                echo "</p></td>";

                $valFigc = "";
                $valFipav = "";
                $valCsi = "";

                if ($tesseraFigc == 1) {
                    $valFigc = "X";
                }
                if ($tesseraFipav == 1) {
                    $valFipav = "X";
                }
                if ($tesseraCsi == 1) {
                    $valCsi = "X";
                }


                $array_valori = array($puogiocare, $details['stagione'], accentRemove($details['cognome']), accentRemove($details['nome']),
                    $details['sezione'], $details['squadra'], $details['ruolo'], accentRemove($details['luogo_di_nascita']),
                    $details["data_di_nascita"], $details['mail'], $details['paese_di_residenza'], $details['via_piazza'],
                    $details['codice_fiscale']/* , $details['tessera_sanitaria'] */, $details['cellulare'],
                    $details['telefono'], $details['quota'], $visitaMedica, $valFigc,
                    $valFipav, $valCsi);
                array_push($_SESSION['array_excel'], $array_valori);
            endwhile;


            echo "</table>";
            ?>
            <td> Numero elementi: <?php echo $i ?></td>
            <br></br>
            <!--input type="submit" name="scheda" value="Scheda Socio" disabled-->
        </form>

        <input type="button" value="Esporta in Excel" onClick="window.location.href = 'excel_creator.php?type=soci'">            
        <input type="button" value="Torna al menu Soci" onClick="window.location.href = 'soci_menu.php'">            
    </body>
</html>


