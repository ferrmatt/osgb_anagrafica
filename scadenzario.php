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
                document.forms['scadenziario_form'].submit()
            }

        </script>
    </head>
    <body>

        <form name="scadenziario_form" method="post">
            <table border="0" cellspacing="5" cellpadding="5">
                <tr><td>Cerca visite in scadenza</td></tr>
                <tr>
                    <td>da: </td>                       
                    <td>
                        <?php
                        
                        $array = array('Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre');                        
                        
                        $now = time();
                        $day = date('d', $now);
                        $month = date('m', $now);
                        $month_str=$array[$month];
                        $year = date('Y', $now);

                        
                            echo "<td>";
                            echo "<select name=\"dagiorno\" onchange=\"run()\">";

                            for ($i = 1; $i < 32; $i++)
                                if (isset($_POST['dagiorno']) && $_POST['dagiorno'] == $i)
                                    echo "<option selected value=\"$i\">$i</option>";
                                else if (!isset($_POST['dagiorno']) && $day == $i)
                                    echo "<option selected value=\"$day\">$day</option>";
                                else
                                    echo "<option value=\"$i\">$i</option>";


                            echo "</select>";

                            echo "<select name=\"damese\" onchange=\"run()\">";
                            
                            foreach ($array as $i => $value) {
                                if (isset($_POST['damese']) && $_POST['damese'] == $array[$i])
                                    echo "<option selected value=\"$array[$i]\">$array[$i]</option>";
                                else if (!isset($_POST['damese']) && $month == ($i + 1))
                                    echo "<option selected value=\"$array[$i]\">$array[$i]</option>";
                                else
                                    echo "<option value=\"$array[$i]\">$array[$i]</option>";
                            }

                            echo "</select>";

                            echo "<select name=\"daanno\" onchange=\"run()\">";
                            for ($i = 2010; $i < 2021; $i++)
                                if (isset($_POST['daanno']) && $_POST['daanno'] == $i)
                                    echo "<option selected value=\"$i\">$i</option>";
                                else if (!isset($_POST['daanno']) && $year == $i)
                                    echo "<option selected value=\"$year\">$year</option>";
                                else
                                    echo "<option value=\"$i\">$i</option>";
                        

                        echo "</select>";
                        echo "</td>";
                        ?>
                </tr>
                <tr>
                    <td>a: </td>                       
                    <td>
                        <?php
                        
                        $array = array('Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre');                        
                        
                        $now = time();
                        $day = date('d', $now);
                        $month = date('m', $now);
                        $month_str=$array[$month];
                        $year = date('Y', $now);

                        
                            echo "<td>";
                            echo "<select name=\"agiorno\" onchange=\"run()\">";

                            for ($i = 1; $i < 32; $i++)
                                if (isset($_POST['agiorno']) && $_POST['agiorno'] == $i)
                                    echo "<option selected value=\"$i\">$i</option>";
                                else if (!isset($_POST['agiorno']) && $day == $i)
                                    echo "<option selected value=\"$day\">$day</option>";
                                else
                                    echo "<option value=\"$i\">$i</option>";


                            echo "</select>";

                            echo "<select name=\"amese\" onchange=\"run()\">";
                            
                            foreach ($array as $i => $value) {
                                if (isset($_POST['amese']) && $_POST['amese'] == $array[$i])
                                    echo "<option selected value=\"$array[$i]\">$array[$i]</option>";
                                else if (!isset($_POST['amese']) && $month == ($i + 1))
                                    echo "<option selected value=\"$array[$i]\">$array[$i]</option>";
                                else
                                    echo "<option value=\"$array[$i]\">$array[$i]</option>";
                            }

                            echo "</select>";

                            echo "<select name=\"aanno\" onchange=\"run()\">";
                            for ($i = 2010; $i < 2021; $i++)
                                if (isset($_POST['aanno']) && $_POST['aanno'] == $i)
                                    echo "<option selected value=\"$i\">$i</option>";
                                else if (!isset($_POST['aanno']) && $year == $i)
                                    echo "<option selected value=\"$year\">$year</option>";
                                else
                                    echo "<option value=\"$i\">$i</option>";
                        

                        echo "</select>";
                        echo "</td>";
                        ?>
                </tr>
                
            </table>
            <input type="button" value="Visualizza Scadenze" onClick="window.location.href = 'query_scadenze.php?agiorno=<?php if (isset($_POST['agiorno'])){echo $_POST['agiorno'];}else{echo $day;} ?>&amese=<?php if (isset($_POST['amese'])){echo $_POST['amese'];}else{echo $month_str;} ?>&aanno=<?php if (isset($_POST['aanno'])){echo $_POST['aanno'];}else{echo $year;} ?>&dagiorno=<?php if (isset($_POST['dagiorno'])){echo $_POST['dagiorno'];}else{echo $day;} ?>&damese=<?php if (isset($_POST['damese'])){echo $_POST['damese'];}else{echo $month_str;} ?>&daanno=<?php if (isset($_POST['daanno'])){echo $_POST['daanno'];}else{echo $year;} ?>'">            
            <input type="button" value="Torna al menu Visite Mediche" onClick="window.location.href = 'visite_mediche.php'">     
        </form>       
    </body>
</html>