<!DOCTYPE html>
<html lang="nb">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
    <h1> Arbeidet: </h1>
    <?php
        include "database.php" ;

        $db = new Database();
        $conn = $db->get_Connection();

        $sql = "SELECT * FROM `arbeidsoppforinger`";

        $result = $conn->query($sql);
        

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "ID: " . $row["Arbeidsid"] . " - Gjort: " . $row["Gjort"]. " - Hvor: " . $row["Hvor"]. " - Startid: " . $row["Starttid"]. " - Sluttid: " . $row["Sluttid"]. " - Dato: " . $row["Dato"].  " - Overtid: " . $row["Overtid?"]. " - Kommentar: ". $row["Kommentar"]. "<br>";
            } 
        }
        else {
            echo "0 results";
        }
        $conn->close();
    ?>
   
</body>
</html>