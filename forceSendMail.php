<?php

    $recivers_email = "Thomas.johannessen577@gmail.com";

    $secondweek = idate('W', $timestamp);
    $firstweek = $secondweek - 1;
    $subject = "Timeliste for Thomas J uke: $firstweek og $secondweek";

    session_start();

    if($_SESSION["logginOk"] != "Y"){
        header("refresh:0.0; url=index.php");
    }

    
    // the message
    $msg = "First line of text\nSecond line of text";

    // use wordwrap() if lines are longer than 70 characters
    $msg = wordwrap($msg,70);

    echo $subject;
    //mail($recivers_email,"My subject",$msg);

    //header("refresh:0.0; url=record.php");

?>