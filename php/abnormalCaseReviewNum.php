<?php
    include("CMUHconndata.php");
    include("fun.php");
    $num1=array();
    $num2=array();
    $num1=num("SELECT COUNT(a.case_id) FROM FA.Abnormal_Notification_System_Master AS a INNER JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id WHERE a.manage_status IS NULL AND b.Detail_End_Time IS NOT NULL");
    $num3=array_push($num2,$num1);
    echo json_encode($num2,JSON_UNESCAPED_UNICODE);

?>