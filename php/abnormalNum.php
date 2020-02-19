<?php
    //判斷有多少還沒有指派的案件
    include("CMUHconndata.php");
    include("fun.php");
    $num1=array();
    $num2=array();
    $num1=num("SELECT COUNT(case_id) FROM FA.Abnormal_Notification_System_Master WHERE work_emp IS NULL");
    $num3=array_push($num2,$num1);
    echo json_encode($num2,JSON_UNESCAPED_UNICODE);

?>