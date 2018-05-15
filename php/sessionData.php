<?php
session_start();

$user=$_SESSION["loginMember"];
$loginAutho = $_SESSION["login_authority"];
//$password=$_SESSION["loginPassword"];
$login_success=$_SESSION["login_success"];

    $result = array();
    $result1 = array();

    $result['login_success'] = $login_success;
    $result['loginMember'] = $user;
    $result['login_authority'] = $loginAutho;
    $result12=array_push($result1,$result);
    
    echo json_encode($result1);
?>
