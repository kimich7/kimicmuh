<?php
    include("CMUHconndata.php");
    include("fun.php");
    $systemArray=array();
    $systemArray1=array();
    if (!empty($_REQUEST["ufloorID"])) {
        $floorID=$_REQUEST["ufloorID"];
    }    
    $system_seach=item("SELECT DISTINCT a.sysID,b.sysName FROM FA.Equipment_Check as a INNER JOIN FA.Equipment_System_Group as b ON a.sysID=b.sysID WHERE a.floorID='$floorID'");
    foreach ($system_seach as $info) {
                $systemArray["sysID"]=$info["sysID"];
                $systemArray["sysName"]=$info["sysName"];
                $systemArray2=array_push($systemArray1,$systemArray);
            }
    echo json_encode($systemArray1,JSON_UNESCAPED_UNICODE);
?>