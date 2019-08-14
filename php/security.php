<?php
session_start();
include("CMUHconndata.php");
$userID=$_SESSION["login_number"];
$user=$_SESSION["login_member"];

$security=array();
$security1=array();
$STR="SELECT sid FROM FA.securityemp WHERE e_number='$userID'";
$query=$pdo->query($STR)->fetchall();
foreach ($query as $variable) {
    $security['sid']=$variable['sid'];
    $security12=array_push($security1,$security);
}

// // while ($row=$query->fetch()) {
// //     $security=$row["sid"];//案件單號
        
// // }

// print_r($security1);

//     $result = array();
//     $result1 = array();

   
//     //$result['security'] = $security;
    
//     $result12=array_push($result1,$result);
    
    echo json_encode($security1);
?>