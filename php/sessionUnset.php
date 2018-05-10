<?php
session_start();
$Value = $_GET["logOutValue"];
    if ($Value == 1) {
        session_unset();
        echo 1000;
    } 
?>