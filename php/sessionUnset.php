<?php
    session_start();
    $Value = $_GET["logOutValue"];
    if ($Value == 1) {
        setcookie("build[ID]",$buildNo,time()-3600);
        setcookie("build[Name]",$build,time()-3600);
        setcookie("floor[ID]",$floorID,time()-3600);
        setcookie("floor[Name]",$floorName,time()-3600);
        session_unset();
        echo 1000;
    } 
?>