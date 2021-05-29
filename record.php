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

        <label for="overtid">Overtid?</label><br>
        <input type="checkbox" id="overtid" value="Ja" name="overtid"><br>

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
                $overtid = $_POST['overtid'];
                $kommentar = $_POST['kommentar'];

                if($overtid != "Ja"){
                    $overtid = "Nei";
                }

                $sql2 = "INSERT INTO `arbeidsoppforinger` (`Gjort`, `Hvor`, `Starttid`, `Sluttid`, `Dato`, `Overtid?`, `Kommentar`) 
                VALUES ('$gjort', '$hvor', '$starttid', '$sluttid', '$dato', '$overtid', '$kommentar');";

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

        $sql = "SELECT * FROM `arbeidsoppforinger`";

        $result = $conn->query($sql);
    ?>    
    <table>
        <tr>
            <th>Dato:</th>
            <th>Gjort:</th>
            <th>Hvor:</th>
            <th>Starttid:</th>
            <th>Sluttid:</th>
            <th>Varighet:</th>
            <th>Overtid?</th>
            <th>Kommentar:</th>
        </tr>

        <?php 

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {

                $start_t = new DateTime($row['Starttid']);
                $current_t = new DateTime($row['Sluttid']);
                $difference = $start_t ->diff($current_t );
                $return_time = $difference ->format('%H:%I');

                echo "<tr>";
                    echo "<td>" . $row['Dato'] . "</td>";    
                    echo "<td>" . $row['Gjort'] . "</td>";
                    echo "<td>" . $row['Hvor'] . "</td>";
                    echo "<td>" . substr($row['Starttid'],0,-10) . "</td>";
                    echo "<td>" . substr($row['Sluttid'],0,-10) . "</td>";
                    echo "<td>" . ($return_time) . "</td>";
                    echo "<td>" . $row['Overtid?'] . "</td>";
                    echo "<td>" . $row['Kommentar'] . "</td>";
                echo "</tr>";  
            } 
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