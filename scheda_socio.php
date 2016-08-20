<?php
require_once('calendar/calendar/classes/tc_calendar.php');
require_once('./function.php');
checkLogin();
session_start();

Connect($host, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $sql = 'select osgb_relazione.anagrafica, '
            . 'osgb_anagrafica.nome, '
            . 'osgb_anagrafica.cognome, '
            . 'DATE(osgb_anagrafica.data_di_nascita) as data_di_nascita'
            . ' from osgb_relazione, osgb_anagrafica where '
            . 'osgb_anagrafica.cf_id = osgb_relazione.anagrafica and '
            . 'osgb_relazione.id = '
            . $_POST['myradio'];

    $result = Query($sql);

    WHILE ($details = mysql_fetch_array($result)) {
        $anagrafica = $details["anagrafica"];
        $savedNome = $details["nome"];
        $savedCognome = $details["cognome"];
        $savedDataDiNascita = strtotime($details["data_di_nascita"]);
        $day = date('d', $savedDataDiNascita);
        $month = date('m', $savedDataDiNascita);
        $year = date('Y', $savedDataDiNascita);
        $birthDate = $day . "/" . $month . "/" . $year;
    }
} else {

    $sql = 'select osgb_anagrafica.nome, '
            . 'osgb_anagrafica.cognome, '
            . 'DATE(osgb_anagrafica.data_di_nascita) as data_di_nascita'
            . ' from osgb_anagrafica where '
            . 'osgb_anagrafica.cf_id = '
            . $_GET['anagrafica'];

    $result = Query($sql);

    WHILE ($details = mysql_fetch_array($result)) {
        $anagrafica = $_GET['anagrafica'];
        $savedNome = $details["nome"];
        $savedCognome = $details["cognome"];
        $savedDataDiNascita = strtotime($details["data_di_nascita"]);
        $day = date('d', $savedDataDiNascita);
        $month = date('m', $savedDataDiNascita);
        $year = date('Y', $savedDataDiNascita);
        $birthDate = $day . "/" . $month . "/" . $year;
    }
}

$sql = 'select osgb_relazione.id as id, osgb_sezione.sezione,
                 osgb_quota.quota, osgb_anno_sociale.stagione, osgb_anno_sociale.id as anno_sociale_id,         
                 osgb_squadre.squadra, osgb_ruolo.ruolo, DATE(osgb_anagrafica.visitamedica) as pippo,
                 osgb_tesseramenti.tessera_figc, osgb_tesseramenti.tessera_fipav, 
                 osgb_tesseramenti.tessera_csi
                from 
                 osgb_squadre,
                 osgb_relazione LEFT OUTER JOIN osgb_tesseramenti ON osgb_relazione.anagrafica=osgb_tesseramenti.anagrafica AND 
                 osgb_relazione.annosociale=osgb_tesseramenti.anno_sociale, 
                 osgb_anno_sociale, 
                 osgb_quota, 
                 osgb_anagrafica,
                 osgb_sezione, osgb_ruolo 
                where 
                 osgb_relazione.anagrafica = osgb_anagrafica.cf_id AND
                 osgb_relazione.annosociale = osgb_anno_sociale.id AND
                 osgb_relazione.quota = osgb_quota.id AND
                 osgb_relazione.sezione = osgb_sezione.id AND
                 osgb_squadre.id = osgb_relazione.squadra AND
                 osgb_relazione.ruolo = osgb_ruolo.id AND
                 osgb_relazione.anagrafica = ' . $anagrafica . ' ORDER BY osgb_relazione.annosociale DESC'
;

$result = Query($sql);

$sql2 = 'select DATE(osgb_anagrafica.visitamedica) as visita_medica from osgb_anagrafica where cf_id = ' . $anagrafica;
$result2 = Query($sql2);
WHILE ($details2 = mysql_fetch_array($result2)) {
    $savedVisitaMedica = strtotime($details2["visita_medica"]);
    $day = date('d', $savedVisitaMedica);
    $month = date('m', $savedVisitaMedica);
    $year = date('Y', $savedVisitaMedica);
    $visitaMedica = $day . "/" . $month . "/" . $year;
};

$sql3 = 'select count(*) as num from osgb_relazione, osgb_ruolo where osgb_relazione.ruolo = osgb_ruolo.id and osgb_relazione.anagrafica = ' . $anagrafica . ' and osgb_ruolo.ruolo = \'Atleta\'';
$result3 = Query($sql3);
WHILE ($details = mysql_fetch_array($result3)) {
    $is_atleta = $details["num"];
};
?>

<html>
    <head>
        <?php css(); ?>
       
    </head>    
    <body>
        <form name="scheda_socio">
            <table border="0" cellspacing="5" cellpadding="5">                            
                <tr>
                <input type="hidden" name="id" value="<?php echo $savedId; ?>" >
                <input type="hidden" name="cfdid" value="<?php echo $savedCfId; ?>" >
                <td>Nome: </td><td></td><td><?php echo $savedNome; ?></td></tr>
                <tr><td>Cognome: </td><td></td><td><?php echo $savedCognome; ?></td></tr>
                <tr><td>Data di nascita: </td><td></td><td><?php echo $birthDate; ?></td></tr>
                <?php
                if ($is_atleta)
                    echo "<tr><td>Scadenza visita medica: </td><td></td><td>$visitaMedica</td></tr>";
                ?>
            </table>
             <script language="javascript"  type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js">

            $(document).ready(function ()
            {
                $("#update_tessere").click(function ()
                {
                    var textboxvalue = $('input[name=tessera_csi]').val();

                    $.ajax(
                            {
                                type: "POST",
                                url: 'db_relazione_update.php',
                                data: {csi: textboxvalue},
                                
                            });
                });
            });
            ​

        </script>
            <br></br>
            <input type="button" value="Aggiungi ruolo" onClick="window.location.href = 'aggiungi_ruolo.php?cf_id=<?php echo $anagrafica ?>&nome=<?php echo addslashes($savedNome) ?>&cognome=<?php echo addslashes($savedCognome) ?>&data_di_nascita=<?php echo $birthDate ?>'">        

            <?php
            unset($_SESSION['array_excel']);
            $array_titoli = array('ANNO SOCIALE', 'SEZIONE', 'RUOLO', 'SQUADRA', 'QUOTA', 'TESSERA_FIGC', 'TESSERA_FIPAV', 'TESSERA_CSI');
            $_SESSION['array_excel'] = array($array_titoli);
            echo "<table class=\"gradienttable\" >";
            echo "<tr>";
            foreach ($array_titoli as $i => $value) {
                echo "<th nowrap=\"nowrap\"><p>$value</p></th>";
            }
            echo "</tr>";

            WHILE ($details = mysql_fetch_array($result)):


                echo "<tr><td nowrap=\"nowrap\"><p>",
                $details['stagione'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['sezione'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['ruolo'], "</p></td><td nowrap=\"nowrap\"><p>",
                $details['squadra'], "</p></td><td nowrap=\"nowrap\"><p>";
                ?>
                <select onChange="window.location.href = 'db/update_quota.php?anagrafica=<?php echo $anagrafica ?>&id=<?php echo $details['id'] ?>&quota=' + this.selectedIndex" > 
                    <?php
                    $sql = 'SELECT id, quota FROM osgb_quota ORDER BY id ASC';
                    $result2 = Query($sql);
                    while ($riga = mysql_fetch_array($result2)) {
                        $quota = $riga['quota'];
                        if ($details['quota'] == $quota)
                            echo "<option selected value=\"$quota\">$quota</option>";
                        else
                            echo "<option value=\"$quota\">$quota</option>";
                    }
                    $tesseraFigc = $details['tessera_figc'];
                    $tesseraFipav = $details['tessera_fipav'];
                    $tesseraCsi = $details['tessera_csi'];
                    echo "</select></p></td><td nowrap=\"nowrap\"><p>", 
                    $details['tessera_figc'], "</p></td><td nowrap=\"nowrap\"><p>", 
                            $details['tessera_fipav'], "</p></td><td nowrap=\"nowrap\"><p>", 
                            $details['tessera_csi'], "</p></td><td nowrap=\"nowrap\"><p>";
                            
                    echo "<a href=\"dati_tesseramento.php?id=", $details['id'], "\" >  <img src=\"Images/edit.png\" title=\"Modifica dati tesseramenti\"</a>", "</p></td><td nowrap=\"nowrap\"><p>";
                    echo "<a onclick=\"return confirm('Stai per rimuovere il ruolo! Sei sicuro????\\nSe hai dubbi, annulla... e chiedi a Matteo Ferrari!')\" href=\"db/db_relazione_delete.php?id=", $details['id'], "&anagrafica=", $anagrafica, "\" >  <img src=\"Images/delete.png\" title=\"Rimuovi ruolo\"</a>", "</p></td>",
                    "<tr>";

                    $array_valori = array(accentRemove($details['cognome']), accentRemove($details['nome']),
                        accentRemove($details['luogo_di_nascita']),
                        $details["data_di_nascita"], $details['mail'], accentRemove($details['paese_di_residenza']), accentRemove($details['via_piazza']),
                        $details['codice_fiscale'], $details['tessera_sanitaria'], $details['cellulare'],
                        $details['telefono']);
                    array_push($_SESSION['array_excel'], $array_valori);
                endwhile;
                echo "</table>";
                ?>

        </form>

        <br></br>
        <input type="button" value="Vai all'anagrafica" onClick="window.location.href = 'modifica_anagrafica.php?cf_id=<?php echo $anagrafica ?>'">            
        <input type="button" value="Torna al menu Soci" onClick="window.location.href = 'soci_menu.php'">            
    </body>
</html>            




