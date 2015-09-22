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
             alt="OSGB Merate - Magazzino" />
        <form action="carico_magazzino.php"><INPUT class="button" type=submit value="CARICO" style="position: relative; top: 80px;"></a></form>
        <form action="scarico_magazzino.php"><INPUT class="button" type=submit value="SCARICO" style="position: relative; top: 100px;"></a></form>
        <form action="inventario_menu.php"><INPUT class="button" type=submit value="INVENTARIO" style="position: relative; top: 120px;"></a></form>
        <form action="Index.php"><INPUT class="button" type=submit value="MENU PRINCIPALE" style="position: relative; top: 140px;"></a></form>
    </center> 
</body>
</html>