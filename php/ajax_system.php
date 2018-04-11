<?PHP
        include("CMUHconndata.php");
        //==========(接收資訊)===========//
        if (!empty($_REQUEST['system_eq'])) {
            $systemNo = $_REQUEST['system_eq'];
        }
        //==========(作品分類)===========//
        $equipArray=array();
        $equipArray1=array();
        $sql_equipment="SELECT equipID,equipName FROM FA.Equipment_System WHERE sysID = $systemNo";
        $query_equipment =$pdo->query($sql_equipment)->fetchAll();
        foreach ($query_equipment as $equipInfo) {
            $equipArray['equipID']=$equipInfo['equipID'];
            $equipArray['equipName']=$equipInfo['equipName'];
            $equipArray2=array_push($equipArray1,$equipArray);
        }
        echo json_encode($equipArray1,JSON_UNESCAPED_UNICODE);
?>