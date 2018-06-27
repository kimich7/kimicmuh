<?php
    include("CMUHconndata.php");
    include("fun.php");
    //==========(接收資訊)===========//
    if (!empty($_REQUEST['system_eq'])) {
        $systemNo = $_REQUEST['system_eq'];
        $buildNo=$_REQUEST['build_eq'];
        $rDate = $_REQUEST['rDate'];
        $class = $_REQUEST['now_class'];
        // $revise=$_REQUEST['insert_revise'];
    }
    //==========(作品分類)===========//
    $dataArray=array();
    $dataArray1=array();

    /*判斷今天是否抄表過了*/
    $master_check_query=num("SELECT COUNT(recordID) FROM FA.Water_System_Record_Master WHERE b_number='$buildNo' AND rDate='$rDate' AND sysID=$systemNo ");

    if ($master_check_query !=0) {//如果今天已經有抄表紀錄了請走這邊
        $sql_select="SELECT recordID FROM FA.Water_System_Record_Master WHERE b_number='$buildNo' AND rDate='$rDate' AND sysID=$systemNo";
        $select_master =$pdo->query($sql_select)->fetch();
        $MasterID=$select_master['recordID'];
        
        /*判斷該班是否抄表過了*/
        $shift_seach=num("SELECT COUNT(DISTINCT shiftID) FROM FA.Water_System_Record_Detail WHERE  shiftID = $class AND recordID=$MasterID");

        /*找出已經抄表的項目*/
        $floor_seach=item("SELECT DISTINCT floorID FROM FA.Water_System_Record_Detail WHERE shiftID = $class AND recordID=$MasterID");

        /*算出該班已經抄表的數量*/
        $floor_num=num("SELECT COUNT(DISTINCT floorID) FROM FA.Water_System_Record_Detail WHERE shiftID = $class AND recordID=$MasterID");

        /*都沒有抄表過的話有哪些要抄的項目(該做的項目*/
        $basic_query=item("SELECT DISTINCT a.floorID,floorName FROM FA.BuildingFloor AS a INNER JOIN FA.Equipment_Check as b ON a.floorID = b.floorID WHERE b.b_number ='$buildNo' AND b.sysID =$systemNo");
        /*都沒有抄表過的話抄的數量(該做的數量)*/
        $basic_num=num("SELECT COUNT(DISTINCT a.floorID) FROM FA.BuildingFloor AS a INNER JOIN FA.Equipment_Check as b ON a.floorID = b.floorID WHERE b.b_number ='$buildNo' AND b.sysID =$systemNo");
    }        
    
    if ($master_check_query==0 or $shift_seach==0) {//如果今日還沒抄表過或是該班還沒抄表過往這走
        $str=item("SELECT DISTINCT a.floorID,floorName FROM FA.BuildingFloor AS a INNER JOIN FA.Equipment_Check as b ON a.floorID = b.floorID WHERE b.b_number ='$buildNo' AND b.sysID =$systemNo");
        foreach ($str as $Info) {
        $dataArray['floorID']=$Info['floorID'];
        $dataArray['floorName']=$Info['floorName'];
        $dataArray2=array_push($dataArray1,$dataArray);
    }
    } else {//不然就走這(該班已有超過的項目)
        for ($i=0; $i <$basic_num ; $i++) {
            for ($j=0; $j <$floor_num ; ) { 
                if ($basic_query[$i]["floorID"]==$floor_seach[$j]["floorID"]) {
                    $dataArray['floorID']=$basic_query[$i]["floorID"];
                    $dataArray['floorName']=$basic_query[$i]["floorName"].'(已填寫過)';
                    break;
                } else {
                    $dataArray['floorID']=$basic_query[$i]["floorID"];
                    $dataArray['floorName']=$basic_query[$i]["floorName"];
                    $j++;
                }                
            }
            $dataArray2=array_push($dataArray1,$dataArray);
        }
    }
    echo json_encode($dataArray1,JSON_UNESCAPED_UNICODE);
?>