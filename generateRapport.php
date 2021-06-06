<?php
    $secondweek = date("W"); 
    $firstweek = $secondweek - 1;
    $subject = "Timeliste for Thomas J uke: $firstweek og $secondweek";

    session_start();

    if($_SESSION["logginnOk"] != "Y"){
        header("refresh:0.0; url=index.php");
    }

    echo $subject;


    include "database.php";

    $db = new Database();
    $conn = $db->get_Connection();

    $sql = "SELECT * FROM `arbeidsoppforinger`";

    $result = $conn->query($sql);

    // the message
    $msg = "Hei!\n Under følger timelisten min for de to siste ukene.\n (Tabellen er automatisk generert. Er det noen spørsmål er det bare å ta kontakt.)\n\n Mvh \n Thomas T. Johannessen \n 40318062";
/*
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
    ";



    // use wordwrap() if lines are longer than 70 characters
    //$msg = wordwrap($msg,70);
*/
   
//<?php

    echo (mail($recivers_email,$subject,$msg));

    //header("refresh:0.0; url=record.php");

?>