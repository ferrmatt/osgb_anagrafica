<?php
require_once('./function.php');
Connect($host, $username, $password, $dbname);
?>
<html>
    <head>
        <?php css(); ?>       
    </head>
    <body>

        <form action='<?php echo $_SERVER['PHP_SELF']; ?>?login=ok' method='POST'>

            <b>Username</b><br>
            <input type='text' name='user'><br>
            <b>Password</b><br>
            <input type='password' name='pass'><br>
            <br></br>
            <input type='submit' value='Effettua il login'>
        </form>

        <?php
        if (isset($_POST['user'])) {
            $user = $_POST['user'];
            $pass = $_POST['pass'];

            $risp = "ok";
            $login = $_GET['login'];

            if ($login == $risp) {
                if ($user && $pass) {

                    $user = mysql_real_escape_string($user);
                    $pass = mysql_real_escape_string($pass);

                    $sql = "SELECT * FROM osgb_login WHERE username = '$user' AND password = MD5('$pass')";
                    $res = mysql_query($sql) or die(mysql_error());

                    if ($res == TRUE) {

                        while ($row = mysql_fetch_array($res)) {

                            ini_set("session.gc_maxlifetime","60");
                            ini_set("session.cookie_lifetime","60");
                            session_start();
                            $idutente = $row['id'];
                            $nomeutente = $row['username'];
                            $_SESSION['id_utente'] = $idutente;
                            $_SESSION['nome_utente'] = $nomeutente;
                            header("location: Index.php");
                        }
                    }
                }
                echo "Username o Password non corrette";
            } else {
                echo "Non sono stati compilati tutti i dati obbligatori";
            }
        }
        ?>


    </body>
</html>
