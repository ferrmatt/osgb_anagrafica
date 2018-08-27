<?php
$host = "62.149.150.122";
$username = "Sql376597";
$password = "1eb2205d";

#$host = "localhost";
#$username = "root";
#$password = "";

$dbname = "Sql376597_1";

function Connect($host, $user, $pass, $db) {
    global $index;
    if (!($connessione = mysql_connect(gethostbyname($host), $user, $pass))) {
        $errore = "<b> >> Connessione non riuscita: " . mysql_error() . "</b>";
        TornaMsg($index, $errore);
        exit;
    }
    if (!(mysql_select_db($db))) {
        $errore = "<b> >> Selezione del database non riuscita </b>";
        TornaMsg($index, $errore);
        exit;
    };
}

function Query($query) {
    global $acapo;

    $risultato = mysql_query($query);
    if (!$risultato) {
        $errore = 'Invalid query: ' . mysql_error() . $acapo;
        $errore .= 'Query: ' . $query;
        echo "Errore nella chiamata MySql:" . $acapo . $errore . $acapo;
        die();
    }
    return $risultato;
}

function pulisciCampi($string) {

//	$contenuto = trim(charset_decode_utf_8(fix_latin($contenuto)),'-');			

    $string = str_replace('"', "&quot;", $string);
    $string = str_replace("\r\n", "\n", $string);
    $string = str_replace("\n- ", "\n", $string);
    $string = str_replace("\n ", "\n", $string);
    $string = str_replace("\n\n", "\n", $string);
    $string = trim($string);

    return $string;
}

function css() {
    echo "<link rel=\"stylesheet\" href=\"./CSS/main.css\">";
    echo "<title>OSGB Anagrafica</title>";
}

function checkLogin() {
    //return;
    session_start();
    ini_set("session.gc_maxlifetime", "1200");
    ini_set("session.cookie_lifetime", "1200");

    if (isset($_SESSION['idutente'])) {

        //echo "Non hai le credenziali per visualizzare il contenuto di questa pagina.";
        ?>
        <meta http-equiv="refresh" content="1; url=login.php "/>
        <?php
        exit();
    }
}

function accentRemove($string) {
    $string = mb_strtolower(utf8_encode($string), 'UTF-8');
    $b = array("á", "à", "é", "è", "í", "ì", "ó", "ò", "ú", "ù");
    $c = array("a'", "a'","e'", "e'", "i'", "i'", "o'", "o'", "u'", "u'");
    $string = str_replace($b, $c, $string);
    return strtoupper($string);
}
?>