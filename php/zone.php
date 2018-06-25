<?php
            include("CMUHconndata.php");
        //==========(接收資訊)===========//
        if (!empty($_REQUEST['system_eq'])) {
            $systemNo = $_REQUEST['system_eq'];
            $buildNo=$_REQUEST['build_eq'];
            $rDate = $_REQUEST['rDate'];
            $class = $_REQUEST['now_class'];
            $revise=$_REQUEST['insert_revise'];
        }
        //==========(作品分類)===========//
        $dataArray=array();
        $dataArray1=array();

        $sql_master_check="SELECT COUNT(recordID) FROM FA.Water_System_Record_Master WHERE b_number='$buildNo' AND rDate='$rDate' AND sysID=$systemNo ";
        $master_check_query=Current($pdo->query($sql_master_check)->fetch());
        
        if ($master_check_query !=0) {
            $sql_select="SELECT recordID FROM FA.Water_System_Record_Master WHERE b_number='$buildNo' AND rDate='$rDate' AND sysID=$systemNo";
            $select_master =$pdo->query($sql_select)->fetch();
            $MasterID=$select_master['recordID'];
            
            $seach="SELECT COUNT(DISTINCT shiftID) FROM FA.Water_System_Record_Detail WHERE  shiftID = $class AND recordID=$MasterID";
            $seach_check=Current($pdo->query($seach)->fetch());
        }        
        
        if ($master_check_query==0 or $seach_check==0 or $revise==1) {
            $str="SELECT DISTINCT a.floorID,floorName FROM FA.BuildingFloor AS a INNER JOIN FA.Equipment_Check as b ON a.floorID = b.floorID WHERE b.b_number ='$buildNo' AND b.sysID =$systemNo";
        } else {
            // $sql_select="SELECT recordID FROM FA.Water_System_Record_Master WHERE b_number='$buildNo' AND rDate='$rDate' AND sysID=$systemNo";
            // $select_master =$pdo->query($sql_select)->fetch();
            // $MasterID=$select_master['recordID'];
            $num="SELECT COUNT( DISTINCT floorID) FROM FA.Water_System_Record_Detail WHERE  recordID = $MasterID";
            $str="SELECT DISTINCT a.floorID,floorName FROM FA.BuildingFloor AS a INNER JOIN FA.Equipment_Check as b ON a.floorID = b.floorID WHERE b.b_number ='$buildNo' AND b.sysID =$systemNo AND a.floorID NOT IN  (SELECT DISTINCT floorID FROM FA.Water_System_Record_Detail WHERE recordID=$MasterID)";
        }
        $query =$pdo->query($str)->fetchAll();
        foreach ($query as $Info) {
            $dataArray['floorID']=$Info['floorID'];
            $dataArray['floorName']=$Info['floorName'];
            $dataArray2=array_push($dataArray1,$dataArray);
        }
        echo json_encode($dataArray1,JSON_UNESCAPED_UNICODE);


        
?>