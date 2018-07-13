<?php
    include("CMUHconndata.php");
    include("fun.php");
    $floorArray=array();
    $floorArray1=array();
        if (!empty($_REQUEST["buildNo"])) {
            $buildNo=$_REQUEST["buildNo"];
            $build_seach=item("SELECT DISTINCT a.floorID,b.floorName FROM FA.Equipment_Check as a INNER JOIN FA.BuildingFloor as b ON a.floorID=b.floorID WHERE a.b_number='$buildNo'");
            foreach ($build_seach as $info) {
                $floorArray["floorID"]=$info["floorID"];
                $floorArray["floorName"]=$info["floorName"];
                $floorArray2=array_push($floorArray1,$floorArray);
            }
        }    
    echo json_encode($floorArray1,JSON_UNESCAPED_UNICODE);
?>