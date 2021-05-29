<!DOCTYPE html>
<html lang="nb">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
    <h2>Logg inn</h2>
    <?php
        session_start();
    ?>
        
    <form method="post" style=" width: 400px;">
        <label for="username"><b>Brukernavn: </b></label>
        <input name="username" type="text" placeholder="Enter Username" required><br><br>

        <label for="password"><b>Passord:</b></label>
        <input name="password" type="password" placeholder="Passord" required><br><br>
            
        <input type="submit" name="loginBtn" value="Logg inn"><br>
    </form>

    <?php

        if($_SESSION["logginOk"] == "Y"){
            header("refresh:0.1; url=record.php");
        }

        $real_password = "Detteeretbrapassord123";

        if (isset($_POST['loginBtn'])) {

            $username = $_POST['username'];
            $password = $_POST['password'];
            
            //Denne brukes for å sette nytt passord
            //echo password_hash($password, PASSWORD_DEFAULT);

            if($username == "Thomastj"){
                if ($password == $real_password) {

                    $_SESSION["logginOk"] = "Y";

                    header("refresh:0.0; url=record.php");
                    exit;

                }
                else{
                    echo "Something went wrong."; 
                }
            }
            else{
                echo "Something went wrong.";  
            }
        }     
    ?>
</body>
</html>
