<?php
    include("CMUHconndata.php");
    include("fun.php");
    $build=sql_database('B_name','FA.Building','b_number',$_REQUEST["BuildID"]);
    $floorName=sql_database('floorName','FA.BuildingFloor','floorID',$_REQUEST["floorID"]);
    setcookie("buildID",$_REQUEST["BuildID"]);
    setcookie("buildName",$build);
    setcookie("floorID",$_REQUEST["floorID"]);
    setcookie("floorName",$floorName);

    $systemArray=array();
    $systemArray1=array();
    if (!empty($_REQUEST["floorID"])) {
        $floorID=$_REQUEST["floorID"];
        $shiftID=$_REQUEST["shiftID"];
        $rDate=$_REQUEST["rDate"];
    }    
    $check_query=num("SELECT COUNT(recordDetailID) FROM FA.Water_System_Record_Detail WHERE rDate='$rDate' AND shiftID=$shiftID AND floorID='$floorID'");
    if ($check_query =0) {//如果今天沒有抄表紀錄了請走這邊
        //$system_seach=item("SELECT DISTINCT a.sysID,b.sysName FROM FA.Equipment_Check as a INNER JOIN FA.Equipment_System_Group as b ON a.sysID=b.sysID WHERE a.floorID='$floorID'");
        echo '此樓層已沒有需要點檢的系統';
    }else{
        $system_seach=item("SELECT DISTINCT a.sysID,b.sysName FROM FA.Equipment_Check as a INNER JOIN FA.Equipment_System_Group as b ON a.sysID=b.sysID WHERE a.floorID='$floorID' and a.sysID NOT IN (SELECT DISTINCT c.sysID FROM FA.Equipment_Check as c INNER JOIN FA.Water_System_Record_Detail as d ON c.floorID=d.floorID and c.equipCheckID=d.equipCheckID where(d.floorID='$floorID' AND d.rDate='$rDate' AND d.shiftID=$shiftID))");
        foreach ($system_seach as $info) {
            $systemArray["sysID"]=$info["sysID"];
            $systemArray["sysName"]=$info["sysName"];
            $systemArray2=array_push($systemArray1,$systemArray);
        }
        echo json_encode($systemArray1,JSON_UNESCAPED_UNICODE);
    }
    // foreach ($system_seach as $info) {
    //             $systemArray["sysID"]=$info["sysID"];
    //             $systemArray["sysName"]=$info["sysName"];
    //             $systemArray2=array_push($systemArray1,$systemArray);
    //         }
    // echo json_encode($systemArray1,JSON_UNESCAPED_UNICODE);
    //     if (!empty($_REQUEST["floorID"])) {
    //         $floorID=$_REQUEST["floorID"];
    //         $system_seach=item("SELECT DISTINCT a.sysID,b.sysName FROM FA.Equipment_Check as a INNER JOIN FA.Equipment_System_Group as b ON a.sysID=b.sysID WHERE a.floorID='$floorID'");
    //         foreach ($system_seach as $info) {
    //             $systemArray["sysID"]=$info["sysID"];
    //             $systemArray["sysName"]=$info["sysName"];
    //             $systemArray2=array_push($systemArray1,$systemArray);
    //         }
    //     }    
    // echo json_encode($systemArray1,JSON_UNESCAPED_UNICODE);
?>