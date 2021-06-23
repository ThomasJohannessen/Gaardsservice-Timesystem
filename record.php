<!DOCTYPE html>
<html lang="nb">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arbeidslogg</title>
    <script>
        function showHideHistory() {
            var x = document.getElementById("historikk");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
            }
    </script>

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
    <h1> Nytt arbeid: </h1>

    <form method="POST" autocomplete="off">
        <label for="gjort">Gjort:*</label><br>
        <input type="text" id="gjort" name="gjort" placeholder="Hva har du gjort?" required><br>

        <label for="hvor">Hvor:</label><br>
        <input type="text" id="hvor" name="hvor" placeholder="Hvor har du gjort det?"><br>

        <label for="starttid">Starttid:*</label><br>
        <input type="time" id="starttid" name="starttid" required><br>

        <label for="sluttid">Sluttid:*</label><br>
        <input type="time" id="sluttid" name="sluttid" required><br>

        <label for="dato">Dato:*</label><br>
        <input type="date" id="dato" name="dato" value="<?php echo date('Y-m-d'); ?>" required><br>
        
        <label for="Normallonn">Normal lønn</label><br>
        <input type="radio" id="Normallonn" value="0" name="timer" checked><br>

        <label for="overtid50">50% Overtid</label><br>
        <input type="radio" id="overtid50" value="50" name="timer"><br>

        <label for="overtid100">100% Overtid</label><br>
        <input type="radio" id="overtid100" value="100" name="timer"><br>
    
        <label for="kommentar">Kommentar:</label><br>
        <input type="text" id="kommentar" name="kommentar" placeholder="Kommentar"><br>

        <br>
        <input type="submit" id="sendBtn" value="Legg til arbeidet" name="sendBtn"><br>
    </form> 

    <?php

    session_start();

    if($_SESSION["logginnOk"] != "Y"){
        header("refresh:0.0; url=index.php");
    }

    include "database.php";

        $db = new Database();
        $conn = $db->get_Connection();

            if (isset($_POST['sendBtn'])){  

                $gjort = $_POST['gjort'];
                $hvor = $_POST['hvor'];
                $starttid = $_POST['starttid'];
                $sluttid = $_POST['sluttid'];
                $dato = $_POST['dato'];
                $timer = $_POST['timer'];
                $kommentar = $_POST['kommentar'];

                $start_t = new DateTime($starttid);
                $current_t = new DateTime($sluttid);
                $difference = $start_t ->diff($current_t );
                $return_time = $difference ->format('%H:%I');

                if($timer == 0){
                    $sql2 = "INSERT INTO `arbeidsoppforinger` (`Gjort`, `Hvor`, `Starttid`, `Sluttid`, `Dato`, `TimerNormal`, `Kommentar`) 
                    VALUES ('$gjort', '$hvor', '$starttid', '$sluttid', '$dato', '$return_time', '$kommentar');";
                }
                elseif($timer == 50){
                    $sql2 = "INSERT INTO `arbeidsoppforinger` (`Gjort`, `Hvor`, `Starttid`, `Sluttid`, `Dato`, `Timer50`, `Kommentar`) 
                    VALUES ('$gjort', '$hvor', '$starttid', '$sluttid', '$dato', '$return_time', '$kommentar');";
                }
                elseif($timer == 100){
                    $sql2 = "INSERT INTO `arbeidsoppforinger` (`Gjort`, `Hvor`, `Starttid`, `Sluttid`, `Dato`, `Timer100`, `Kommentar`) 
                    VALUES ('$gjort', '$hvor', '$starttid', '$sluttid', '$dato', '$return_time', '$kommentar');";
                }


                if (!mysqli_query($conn, $sql2)){
                    echo "Something went wrong!";
                }
                
                echo("Arbeid lagt til.");

                header("refresh:1.0; url=record.php");
                exit;
            }
        ?>

    <br>
    <h1> Arbeidet: </h1>
    <button onclick="showHideHistory()">Vis historikk</button>

    <div id="historikk" style="display:none">
    <?php

        $db = new Database();
        $conn = $db->get_Connection();

        $sql = "SELECT * FROM `arbeidsoppforinger` ORDER BY `arbeidsoppforinger`.`Arbeidsid` DESC";

        $result = $conn->query($sql);
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
            echo "0 results";
        }
        $conn->close();
    ?>
    <table>
    </div>
</body>
</html>