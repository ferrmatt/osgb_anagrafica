<?php
require_once('./function.php');
checkLogin();
session_start();
?>

<html>
    <head>
<?php css(); ?>
    </head>    
    <form name="filter_anagrafica" action="query_anagrafica.php" method="post">
        <table border="0" cellspacing="5" cellpadding="5">

            <tr><td>Nome: </td><td><input type="text" name="nome" size="20" onblur="this.value = this.value.toUpperCase()"></td></tr>
            <tr><td>Cognome: </td><td><input type="text" name="cognome" size="20" onblur="this.value = this.value.toUpperCase()"></td></tr>

        </table>
        <input type="submit" name = "cerca" value="Cerca" >
    </form>
</html>
