<?php
    include("fun.php");
    session_start();    
    $Value = $_GET["logOutValue"];
    if ($Value == 1) {
        session_unset();
        cookieout();
        echo 1000;
    } 
?>