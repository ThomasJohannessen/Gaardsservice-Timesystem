<!DOCTYPE html>
<html lang="nb">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arbeidsrapport</title>
 
    <style>
    table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    }
    </style>
</head>
<body>
    <h1> Generer skjematisk rapport: </h1>

    <form method="POST">
        <label for="fromdate">Fra dato:</label>
        <input type="date" id="fromdate" name="fromdate" value="<?php echo date('Y-m-d', strtotime(date('Y-m-d'). ' - 14 days')); ?>" required>
        
        <label for="todate">Til dato:</label>
        <input type="date" id="todate" name="todate" value="<?php echo date('Y-m-d'); ?>" required>

        <input type="submit" id="velgBtn" value="Vis arbeid" name="velgBtn">
    </form>

    <?php
        $fraDato = $_POST['fromdate'];
        $tilDato = $_POST['todate'];

        echo "<h2> Arbeidet fra: {$fraDato} til {$tilDato} </h3>";
    ?>
     
    <table>
        <tr>
            <th>Dato:</th>
            <th>Gjort:</th>
            <th>Hvor:</th>
            <th>Starttid:</th>
            <th>Sluttid:</th>
            <th>Timer Normallønn:</th>
            <th>Timer 50%</th>
            <th>Timer 100%</th>
        </tr>

    <?php 

    session_start();

    if($_SESSION["logginnOk"] != "Y"){
        header("refresh:0.0; url=index.php");
    }

    include "database.php";

    $db = new Database();
    $conn = $db->get_Connection();

    if (isset($_POST['velgBtn'])){  

        $fraDato = $_POST['fromdate'];
        $tilDato = $_POST['todate'];

        $sql = "SELECT * FROM `arbeidsoppforinger` WHERE Dato between '$fraDato' and '$tilDato' ORDER BY `arbeidsoppforinger`.`Arbeidsid` ASC";
        
        $result = $conn->query($sql);
        

        $totNorTimer = 0;
        $tot50Timer = 0;
        $tot100Timer = 0;

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {

                $date = date_create($row['Dato']);
            
                echo "<tr>";
                    echo "<td>" . date_format($date,"j.m - D") . "</td>";    
                    echo "<td>" . $row['Gjort'] . "</td>";
                    echo "<td>" . $row['Hvor'] . "</td>";
                    echo "<td>" . substr($row['Starttid'],0,-10) . "</td>";
                    echo "<td>" . substr($row['Sluttid'],0,-10) . "</td>";
                    echo "<td>" . ($row['TimerNormal'] != "00:00:00.000000" ? substr($row['TimerNormal'],0,-10) : " ") . "</td>";
                    echo "<td>" . ($row['Timer50'] != "00:00:00.000000" ? substr($row['Timer50'],0,-10) : " ") . "</td>";
                    echo "<td>" . ($row['Timer100'] != "00:00:00.000000" ? substr($row['Timer100'],0,-10) : " ") . "</td>";
                echo "</tr>";

               

                $timeNormal = $row['TimerNormal'];
                $time50 = $row['Timer50'];
                $time100 = $row['Timer100'];

                if($timeNormal != strtotime("00:00:00.000000")){
                    $addTimeSecNor = strtotime($timeNormal)-strtotime("00:00:00.000000");
                    $totNorTimer += $addTimeSecNor;
                }
                if($time50 != strtotime("00:00:00.000000")){
                    $addTimeSec50 = strtotime($time50)-strtotime("00:00:00.000000");
                    $tot50Timer += $addTimeSec50;
                }
                if($time100 != strtotime("00:00:00.000000")){
                    $addTimeSec100 = strtotime($time100)-strtotime("00:00:00.000000");
                    $tot100Timer += $addTimeSec100;
                }
            }
            
            $minsNor = $totNorTimer % 3600;
            $secsNor = $minsNor % 60;
            $hoursNor = ($totNorTimer - $minsNor) / 3600;
            $minsNor = ($minsNor - $secsNor) / 60;

            $mins50 = $tot50Timer % 3600;
            $secs50 = $mins50 % 60;
            $hours50 = ($tot50Timer - $mins50) / 3600;
            $mins50 = ($mins50 - $secs50) / 60;

            $mins100 = $tot100Timer % 3600;
            $secs100 = $mins100 % 60;
            $hours100 = ($tot100Timer - $mins100) / 3600;
            $mins100 = ($mins100 - $secs100) / 60;

            echo "<tr>";
                    echo "<th>" . "Sum timer:" . "</td>";    
                    echo "<th>" . " " . "</td>";
                    echo "<th>" . " " . "</td>";
                    echo "<th>" . " " . "</td>";
                    echo "<th>" . " " . "</td>";
                    echo "<th>" . "{$hoursNor}:{$minsNor}" . "</td>";
                    echo "<th>" . "{$hours50}:{$mins50}" . "</td>";
                    echo "<th>" . "{$hours100}:{$mins100}" . "</td>";
                echo "</tr>";  
        }
        else {
            echo "";
        }
    }
    $conn->close();
    ?>
    </table>
    </div>
</body>
</html>