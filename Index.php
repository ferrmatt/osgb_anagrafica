<?php
require_once('./function.php');
checkLogin();
session_start();
$_SESSION['array_excel'] = 1;
$array_excel = array();
$_SESSION['array_excel'] = $array_excel;
?>
    
<html>
    <head>
<?php 
css(); ?>
    </head>
    <body>
    <center>
        <img src="Images/OSGB nero.jpg"
             alt="OSGB Merate" />
        <form action="soci_menu.php"><INPUT class="button" type=submit value="GESTIONE SOCI" style="position: relative; top: 80px;"></form>
        <form action="anagrafica_menu.php"><INPUT class="button" type=submit value="GESTIONE ANAGRAFICA" style="position: relative; top: 100px;"></form>
        <form action="visite_mediche.php"><INPUT class="button" type=submit value="VISITE MEDICHE" style="position: relative; top: 120px;"></a></form>
        <form action="magazzino_menu.php"><INPUT class="button" type=submit value="GESTIONE MAGAZZINO" style="position: relative; top: 140px;"></form>
        <form action="logout.php"><INPUT class="button" type=submit value="ESCI" style="position: relative; top: 160px;"></form>
    </center>
</body>
</html>

