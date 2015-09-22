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
             alt="OSGB Merate - Gestione Soci" />
        <form action="filter_soci.php"><INPUT class="button" type=submit value="RICERCA SOCI" style="position: relative; top: 80px;"></a></form>
        <form action="libro_soci.php"><INPUT class="button" type=submit value="LIBRO SOCI" style="position: relative; top: 80px;"></a></form>
        <form action="visite_mediche.php"><INPUT class="button" type=submit value="VISITE MEDICHE" style="position: relative; top: 80px;"></a></form>
        <form action="Index.php"><INPUT class="button" type=submit value="MENU PRINCIPALE" style="position: relative; top: 120px;"></a></form>
    </center> 
</body>
</html>