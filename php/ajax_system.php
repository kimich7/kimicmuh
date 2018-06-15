<?PHP
        include("CMUHconndata.php");
        //==========(接收資訊)===========//
        if (!empty($_REQUEST['system_eq'])) {
            $systemNo = $_REQUEST['system_eq'];
            $buildNo = $_REQUEST['build_eq'];
            $floorNo = $_REQUEST['floor_eq'];
            $choiceNo = $_REQUEST['choiceNo'];
        }
        //==========(作品分類)===========//
        $equipArray=array();
        $equipArray1=array();
        if ($choiceNo==0) {
            $str="SELECT DISTINCT a.equipID,equipName FROM FA.Equipment_System AS a INNER JOIN FA.Equipment_Check as b ON a.equipID = b.equipID WHERE b.b_number ='$buildNo' AND b.sysID =$systemNo AND b.floorID='$floorNo'";
            $query_equipment =$pdo->query($str)->fetchAll();
            foreach ($query_equipment as $equipInfo) {
                $equipArray['equipID']=$equipInfo['equipID'];
                $equipArray['equipName']=$equipInfo['equipName'];
                $equipArray2=array_push($equipArray1,$equipArray);
            }
        } else {
            $str="SELECT DISTINCT a.zoneNo,zoneName FROM FA.Zonefloor AS a INNER JOIN FA.Equipment_Check as b ON a.zoneNo = b.zoneNo WHERE b.b_number ='$buildNo' AND b.sysID =$systemNo AND b.floorID='$floorNo'";
            $query_equipment =$pdo->query($str)->fetchAll();
            foreach ($query_equipment as $equipInfo) {
                $equipArray['zoneNo']=$equipInfo['zoneNo'];
                $equipArray['zoneName']=$equipInfo['zoneName'];
                $equipArray2=array_push($equipArray1,$equipArray);
            }
        } 
        echo json_encode($equipArray1,JSON_UNESCAPED_UNICODE);
?>