<?php
require_once('./function.php');
checkLogin();
session_start();
require_once('calendar/calendar/classes/tc_calendar.php');
Connect($host, $username, $password, $dbname);
?>

<html>
    <head>
        <?php css(); ?>
        <script language="javascript"  type="text/javascript">

            function run() {
                document.forms['libro_soci_form'].submit()
            }

        </script>
    </head>
    <body>

        <form name="libro_soci_form" method="post">
            <table border="0" cellspacing="5" cellpadding="5">

                <tr>
                    <td>Anno Sociale:</td>
                    <td></td>
                    <td>
                        <select name="anno_sociale" onchange="run()">
                            <?php
                            $sql = 'SELECT id, stagione FROM osgb_anno_sociale ORDER BY stagione ASC';
                            $result = Query($sql);
                            $first = true;
                            while ($riga = mysql_fetch_array($result)) {
                                $id = $riga['id'];
                                $stagione = $riga['stagione'];
                                
                                if (!isset($_POST['anno_sociale']) && $first == true)
                                {
                                    $stagione_saved = $stagione;
                                    $first = false;
                                }
                                
                                if (isset($_POST['anno_sociale']) && $_POST['anno_sociale'] == $stagione) {
                                    echo "<option selected value=\"$stagione\">$stagione</option>";                                    
                                } else
                                    echo "<option value=\"$stagione\">$stagione</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>  
                        <?php
                        if (isset($_POST['maggiorenni']))
                            echo "<input type=\"checkbox\" checked name=\"maggiorenni\" value=\"maggiorenni\" onchange=\"run()\"/>  Solo maggiorenni alla data... ";
                        else {
                            echo "<input type=\"checkbox\" name=\"maggiorenni\" value=\"maggiorenni\" onchange=\"run()\"/>  Solo maggiorenni alla data... ";
                        }
                        ?>
                    </td>                        
                    <td>
                        <?php
                        
                        $array = array('Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre');                        
                        
                        $now = time();
                        $day = date('d', $now);
                        $month = date('m', $now);
                        $month_str=$array[$month];
                        $year = date('Y', $now);

                        if (isset($_POST['maggiorenni'])) {
                            echo "<td>";
                            echo "<select name=\"giorno\" onchange=\"run()\">";

                            for ($i = 1; $i < 32; $i++)
                                if (isset($_POST['giorno']) && $_POST['giorno'] == $i)
                                    echo "<option selected value=\"$i\">$i</option>";
                                else if (!isset($_POST['giorno']) && $day == $i)
                                    echo "<option selected value=\"$day\">$day</option>";
                                else
                                    echo "<option value=\"$i\">$i</option>";


                            echo "</select>";

                            echo "<select name=\"mese\" onchange=\"run()\">";
                            
                            foreach ($array as $i => $value) {
                                if (isset($_POST['mese']) && $_POST['mese'] == $array[$i])
                                    echo "<option selected value=\"$array[$i]\">$array[$i]</option>";
                                else if (!isset($_POST['mese']) && $month == ($i + 1))
                                    echo "<option selected value=\"$array[$i]\">$array[$i]</option>";
                                else
                                    echo "<option value=\"$array[$i]\">$array[$i]</option>";
                            }

                            echo "</select>";

                            echo "<select name=\"anno\" onchange=\"run()\">";
                            for ($i = 2010; $i < 2021; $i++)
                                if (isset($_POST['anno']) && $_POST['anno'] == $i)
                                    echo "<option selected value=\"$i\">$i</option>";
                                else if (!isset($_POST['anno']) && $year == $i)
                                    echo "<option selected value=\"$year\">$year</option>";
                                else
                                    echo "<option value=\"$i\">$i</option>";
                        }

                        echo "</select>";
                        echo "</td>";
                        ?>
                </tr>
            </table>
            <input type="button" value="Visualizza Libro Soci" onClick="window.location.href = 'query_libro_soci.php?anno_sociale=<?php if (isset($_POST['maggiorenni'])){echo $_POST['anno_sociale'];} else { echo $stagione_saved;} ?>&maggiorenni=<?php if (isset($_POST['maggiorenni'])){echo "true";}else{echo "false";}  ?>&giorno=<?php if (isset($_POST['giorno'])){echo $_POST['giorno'];}else{echo $day;} ?>&mese=<?php if (isset($_POST['mese'])){echo $_POST['mese'];}else{echo $month_str;} ?>&anno=<?php if (isset($_POST['anno'])){echo $_POST['anno'];}else{echo $year;} ?>'">            
        </form>       
    </body>
</html>