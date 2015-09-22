<?php
require_once('./function.php');
checkLogin();
session_start();
Connect($host, $username, $password, $dbname);
?>

<html>
    <head>
        <?php css(); ?>
    </head>    
    <body>
    <center>
        <img src="Images/Anagrafe.jpg"
             alt="OSGB Merate - Anagrafe" />
        <form action="filter_anagrafica.php"><INPUT class="button" type=submit value="CERCA ANAGRAFICA" style="position: relative; top: 80px;"></a></form>
        <form action="inserisci_anagrafica.php"><INPUT class="button" type=submit value="NUOVA ANAGRAFICA" style="position: relative; top: 100px;"></a></form>
        <form action="Index.php"><INPUT class="button" type=submit value="MENU PRINCIPALE" style="position: relative; top: 120px;"></a></form>
    </center> 
</body>
</html>
