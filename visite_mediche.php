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
    <center>
        <img src="Images/Soci.jpg"
             alt="OSGB Merate - Gestione Visite Mediche" />
        <form action="scadenzario.php"><INPUT class="button" type=submit value="SCADENZIARIO" style="position: relative; top: 80px;"></a></form>
        <form action="filter_visita.php"><INPUT class="button" type=submit value="INSERISCI SCADENZA" style="position: relative; top: 80px;"></a></form>
        <form action="soci_menu.php"><INPUT class="button" type=submit value="MENU SOCI" style="position: relative; top: 120px;"></a></form>
    </center> 
</body>
</html>

