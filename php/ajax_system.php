<?PHP
    include("CMUHconndata.php");
    include("fun.php");
    //==========(接收資訊)===========//
    if (!empty($_REQUEST['system_eq'])) {
        $systemNo = $_REQUEST['system_eq'];
        $buildNo = $_REQUEST['build_eq'];
        $floorNo = $_REQUEST['floor_eq'];
        $rDate = $_REQUEST['rDate'];
        $choiceNo = $_REQUEST['choiceNo'];
        $class = $_REQUEST['now_class'];
    }
    //==========(作品分類)===========//
    $equipArray=array();
    $equipArray1=array();
    if ($choiceNo==1){
        $aID='a.zoneNo';
        $cID='c.zoneNo';
        $bID='b.zoneNo';
        $cName='c.zoneName';
        $ctable='FA.Zonefloor';
        $name='zoneName';
        $ID='zoneNo';
    }else {
        $aID='a.equipID';
        $cID='c.equipID';
        $bID='b.equipID';
        $cName='c.equipName';
        $ctable='FA.Equipment_System';
        $name='equipName';
        $ID='equipID';
    }
    $master_check_query=num("SELECT COUNT(recordID) FROM FA.Water_System_Record_Master WHERE b_number='$buildNo' AND rDate='$rDate' AND sysID=$systemNo ");
    if ($master_check_query!=0) {//如果今天已經有抄表紀錄了請走這邊
        $sql_select="SELECT recordID FROM FA.Water_System_Record_Master WHERE b_number='$buildNo' AND rDate='$rDate' AND sysID=$systemNo";
        $select_master =$pdo->query($sql_select)->fetch();
        $MasterID=$select_master['recordID'];

        /*判斷該班是否抄表過了*/
        $shift_seach=num("SELECT COUNT(DISTINCT shiftID) FROM FA.Water_System_Record_Detail WHERE  shiftID = $class AND recordID=$MasterID");

        /*找出已經抄表的項目*/
        $eq_seach=item("SELECT DISTINCT $aID,$cName FROM FA.Equipment_Check AS a INNER JOIN FA.Water_System_Record_Detail AS b ON a.equipCheckID=b.equipCheckID INNER JOIN $ctable AS c ON $aID=$cID  WHERE b.recordID = $MasterID");
        
        /*算出該班已經抄表的數量*/
        $eq_num=num("SELECT COUNT(DISTINCT $aID) FROM FA.Equipment_Check AS a INNER JOIN FA.Water_System_Record_Detail AS b ON a.equipCheckID=b.equipCheckID INNER JOIN $ctable AS c ON $aID=$cID  WHERE b.recordID = $MasterID");

        /*都沒有抄表過的話有哪些要抄的項目(該做的項目*/
        $basic_query=item("SELECT DISTINCT $aID,$name FROM $ctable AS a INNER JOIN FA.Equipment_Check as b ON $aID = $bID WHERE b.b_number ='$buildNo' AND b.sysID =$systemNo AND b.floorID='$floorNo'");
        
        /*都沒有抄表過的話抄的數量(該做的數量)*/
        $basic_num=num("SELECT COUNT(DISTINCT $aID) FROM $ctable AS a INNER JOIN FA.Equipment_Check as b ON $aID = $bID WHERE b.b_number ='$buildNo' AND b.sysID =$systemNo AND b.floorID='$floorNo'");
        
    }

    if ($master_check_query==0 or $shift_seach==0) {//如果今日還沒抄表過或是該班還沒抄表過往這走
        $str=item("SELECT DISTINCT $aID,$name FROM $ctable AS a INNER JOIN FA.Equipment_Check as b ON $aID = $bID WHERE b.b_number ='$buildNo' AND b.sysID =$systemNo AND b.floorID='$floorNo'");
        foreach ($str as $Info) {
        $equipArray["$ID"]=$Info["$ID"];
        $equipArray["$name"]=$Info["$name"];
        $equipArray2=array_push($equipArray1,$equipArray);
    }
    } else {//不然就走這(該班已有超過的項目)
        for ($i=0; $i <$basic_num ; $i++) {
            for ($j=0; $j <$eq_num ; ) { 
                if ($basic_query[$i]["$ID"]==$eq_seach[$j]["$ID"]) {
                    $equipArray["$ID"]=$basic_query[$i]["$ID"];
                    $equipArray["$name"]=$basic_query[$i]["$name"].'(已填寫過)';
                    break;
                } else {
                    $equipArray["$ID"]=$basic_query[$i]["$ID"];
                    $equipArray["$name"]=$basic_query[$i]["$name"];
                    $j++;
                }                
            }
            $equipArray2=array_push($equipArray1,$equipArray);
        }
    }
    echo json_encode($equipArray1,JSON_UNESCAPED_UNICODE);    
    
    
    // if ($choiceNo==0) {
    //     $str="SELECT DISTINCT a.equipID,equipName FROM FA.Equipment_System AS a INNER JOIN FA.Equipment_Check as b ON a.equipID = b.equipID WHERE b.b_number ='$buildNo' AND b.sysID =$systemNo AND b.floorID='$floorNo'";
    //     $query_equipment =$pdo->query($str)->fetchAll();
    //     foreach ($query_equipment as $equipInfo) {
    //         $equipArray['equipID']=$equipInfo['equipID'];
    //         $equipArray['equipName']=$equipInfo['equipName'];
    //         $equipArray2=array_push($equipArray1,$equipArray);
    //     }
    // } else {
    //     $str="SELECT DISTINCT a.zoneNo,zoneName FROM FA.Zonefloor AS a INNER JOIN FA.Equipment_Check as b ON a.zoneNo = b.zoneNo WHERE b.b_number ='$buildNo' AND b.sysID =$systemNo AND b.floorID='$floorNo'";
    //     $query_equipment =$pdo->query($str)->fetchAll();
    //     foreach ($query_equipment as $equipInfo) {
    //         $equipArray['zoneNo']=$equipInfo['zoneNo'];
    //         $equipArray['zoneName']=$equipInfo['zoneName'];
    //         $equipArray2=array_push($equipArray1,$equipArray);
    //     }
    // } 
    // echo json_encode($equipArray1,JSON_UNESCAPED_UNICODE);
?>