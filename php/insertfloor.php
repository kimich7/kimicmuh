<?php
    include("CMUHconndata.php");
    include("fun.php");
    $floorArray=array();
    $floorArray1=array();

    // $date =$_REQUEST["rDate"];
    // $shift =$_REQUEST["shiftID"];
    $date =$_COOKIE["date"];
    $shift =$_COOKIE["shift"];

    if (!empty($_REQUEST["buildNo"])) {
        $buildNo=$_REQUEST["buildNo"];        
        //-----以下為新增程式碼------        
        $check_right=num("SELECT COUNT(recordID) FROM FA.Water_System_Record_Master WHERE b_number='$buildNo' AND rDate='$date'");            
        if ($check_right==0) {
            $build_seach=item("SELECT DISTINCT a.floorID,b.floorName FROM FA.Equipment_Check as a INNER JOIN FA.BuildingFloor as b ON a.floorID=b.floorID WHERE a.b_number='$buildNo'");
        } else {
            $floorCheck=array();
            $floorUnit=array();
            $MasterID=item("SELECT recordID FROM FA.Water_System_Record_Master WHERE b_number='$buildNo' AND rDate='$date'");
            for ($i=0; $i < $check_right; $i++) {                     
                $floorCheck[$i]=$MasterID[$i]["recordID"];
                $test=item("SELECT DISTINCT floorID FROM FA.Water_System_Record_Detail where recordID=$floorCheck[$i] AND shiftID=$shift");
                foreach ($test as $value) {
                    $test=$value["floorID"];
                    $a[]=$test;                        
                }                    
            }           
            $b=array_unique($a);
            $b=array_values($b);
            $n=Count($b);
            switch ($n) {
                case '1':
                    $build_seach=item("SELECT DISTINCT a.floorID,b.floorName FROM FA.Equipment_Check as a INNER JOIN FA.BuildingFloor as b ON a.floorID=b.floorID WHERE a.b_number='$buildNo' and a.floorID <> '$b[0]'");
                    break;
                case '2':
                    $build_seach=item("SELECT DISTINCT a.floorID,b.floorName FROM FA.Equipment_Check as a INNER JOIN FA.BuildingFloor as b ON a.floorID=b.floorID WHERE a.b_number='$buildNo' and a.floorID <> '$b[0]' and a.floorID <> '$b[1]'");
                    break;
                case '3':
                    $build_seach=item("SELECT DISTINCT a.floorID,b.floorName FROM FA.Equipment_Check as a INNER JOIN FA.BuildingFloor as b ON a.floorID=b.floorID WHERE a.b_number='$buildNo' and a.floorID <> '$b[0]' and a.floorID <> '$b[1]' and a.floorID <> '$b[2]'");
                    break;
                case '4':
                    $build_seach=item("SELECT DISTINCT a.floorID,b.floorName FROM FA.Equipment_Check as a INNER JOIN FA.BuildingFloor as b ON a.floorID=b.floorID WHERE a.b_number='$buildNo' and a.floorID <> '$b[0]' and a.floorID <> '$b[1]' and a.floorID <> '$b[2]' and a.floorID <> '$b[3]'");
                    break;
                case '5':
                    $build_seach=item("SELECT DISTINCT a.floorID,b.floorName FROM FA.Equipment_Check as a INNER JOIN FA.BuildingFloor as b ON a.floorID=b.floorID WHERE a.b_number='$buildNo' and a.floorID <> '$b[0]' and a.floorID <> '$b[1]' and a.floorID <> '$b[2]' and a.floorID <> '$b[3]' and a.floorID <> '$b[4]' ");
                    break;
                case '6':
                    $build_seach=item("SELECT DISTINCT a.floorID,b.floorName FROM FA.Equipment_Check as a INNER JOIN FA.BuildingFloor as b ON a.floorID=b.floorID WHERE a.b_number='$buildNo' and a.floorID <> '$b[0]' and a.floorID <> '$b[1]' and a.floorID <> '$b[2]' and a.floorID <> '$b[3]' and a.floorID <> '$b[4]' and a.floorID <> '$b[5]'");
                    break;
                case '7':
                    $build_seach=item("SELECT DISTINCT a.floorID,b.floorName FROM FA.Equipment_Check as a INNER JOIN FA.BuildingFloor as b ON a.floorID=b.floorID WHERE a.b_number='$buildNo' and a.floorID <> '$b[0]' and a.floorID <> '$b[1]' and a.floorID <> '$b[2]' and a.floorID <> '$b[3]' and a.floorID <> '$b[4]' and a.floorID <> '$b[5]' and a.floorID <> '$b[6]' ");
                    break;
                case '8':
                    $build_seach=item("SELECT DISTINCT a.floorID,b.floorName FROM FA.Equipment_Check as a INNER JOIN FA.BuildingFloor as b ON a.floorID=b.floorID WHERE a.b_number='$buildNo' and a.floorID <> '$b[0]' and a.floorID <> '$b[1]' and a.floorID <> '$b[2]' and a.floorID <> '$b[3]' and a.floorID <> '$b[4]' and a.floorID <> '$b[5]' and a.floorID <> '$b[6]' and a.floorID <> '$b[7]' ");
                    break;
                case '9':
                    $build_seach=item("SELECT DISTINCT a.floorID,b.floorName FROM FA.Equipment_Check as a INNER JOIN FA.BuildingFloor as b ON a.floorID=b.floorID WHERE a.b_number='$buildNo' and a.floorID <> '$b[0]' and a.floorID <> '$b[1]' and a.floorID <> '$b[2]' and a.floorID <> '$b[3]' and a.floorID <> '$b[4]' and a.floorID <> '$b[5]' and a.floorID <> '$b[6]' and a.floorID <> '$b[7]'and a.floorID <> '$b[8]' ");
                    break;
            }            
        }   
        //-------------------------        
        foreach ($build_seach as $info) {
            $floorArray["floorID"]=$info["floorID"];
            $floorArray["floorName"]=$info["floorName"];
            $floorArray2=array_push($floorArray1,$floorArray);
        }       
    }    
     echo json_encode($floorArray1,JSON_UNESCAPED_UNICODE);
?>