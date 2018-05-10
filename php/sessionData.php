<?php
session_start();

//$suser=$_SESSION["loginMember"];
//$password=$_SESSION["loginPassword"];
$login_success=$_SESSION["login_success"];

    $result = array();

    $result['login_success'] = $login_success;
    
    echo json_encode($result);
?>
