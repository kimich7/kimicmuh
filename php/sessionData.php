<?php
session_start();
include("CMUHconndata.php");
$userID=$_SESSION["login_number"];
$user=$_SESSION["login_member"];
$loginAutho = $_SESSION["login_authority"];
$login_success=$_SESSION["login_success"];
$errSuperstr="SELECT * FROM FA.securityemp WHERE e_number='$userID' and sid=33";
$errSuper=$pdo->query($errSuperstr)->fetchall();
$num=Count($errSuper);
$notice = 0;

if ($num==1) {
    $notice=1;
}
    $result = array();
    $result1 = array();
    $result['notice'] = $notice;//判斷是不是異常處理系統主則長官
    $result['login_success'] = $login_success;
    $result['login_number'] = $userID;
    $result['login_member'] = $user;
    $result['login_authority'] = $loginAutho;
    $result12=array_push($result1,$result);
    
    echo json_encode($result1);
?>
