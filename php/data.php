<?php
    include("SQL_Database.php");
    $sysArray=array();
    $sysArray1=array();
    
    foreach ($query_system  as $systeminfo) {
       $sysArray['sysID'] = $systeminfo['sysID'];
       $sysArray['sysName']=$systeminfo['sysName'];
       $sysArray2=array_push($sysArray1,$sysArray);
    }
    echo json_encode($sysArray1,JSON_UNESCAPED_UNICODE);
?>