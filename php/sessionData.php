<?php
session_start();

//$suser=$_SESSION["loginMember"];
//$password=$_SESSION["loginPassword"];
$login_success=$_SESSION["login_success"];

    $result = array();
    // $result1 = array();

    $result['login_success'] = $login_success;
    // $result['class'] = $class;
    // $result['shiftclass'] = $shiftclass;
    // $result12=array_push($result1,$result);
    // echo $result;    
    echo json_encode($result);
?>