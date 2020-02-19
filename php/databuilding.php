<?php
    include("SQL_Database.php");
    $buildArray=array();
    $buildArray1=array();
    
    foreach ($query_build  as $buildinfo) {
       $buildArray['sysID'] = $buildinfo['sysID'];
       $buildArray['sysName']=$buildinfo['sysName'];
       $buildArray2=array_push($buildArray1,$buildArray);
    }
    echo json_encode($buildArray1,JSON_UNESCAPED_UNICODE);
?>