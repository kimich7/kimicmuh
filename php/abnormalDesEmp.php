<?php
    include("CMUHconndata.php");
    include("fun.php");
    session_start();
    $userID=$_SESSION["login_number"];
    $user=$_SESSION["login_member"];
    $check=array();
    $check1=array();

    $check=num("  SELECT COUNT(a.case_id) FROM FA.Abnormal_Notification_System_Master AS a INNER JOIN FA.Abnormal_Notification_System_Detail as b ON  a.case_id=b.case_id   WHERE a.work_emp ='$userID'  and b.Detail_End_Time IS NULL");

    $check2=array_push($check1,$check);

    echo json_encode($check1,JSON_UNESCAPED_UNICODE);
?>