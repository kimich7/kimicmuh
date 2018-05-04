<?php
            include("CMUHconndata.php");
        //==========(接收資訊)===========//
        if (!empty($_REQUEST['system_eq'])) {
            $systemNo = $_REQUEST['system_eq'];
            $buildNo=$_REQUEST['build_eq'];
            $choiceNo = $_REQUEST['choiceNo'];
        }
        //==========(作品分類)===========//
        $dataArray=array();
        $dataArray1=array();
        
        if ($choiceNo==0) {
            $str="SELECT DISTINCT a.floorID,floorName FROM FA.BuildingFloor AS a INNER JOIN FA.Equipment_Check as b ON a.floorID = b.floorID WHERE b.b_number ='$buildNo' AND b.sysID =$systemNo";
        } else {
            $str="SELECT DISTINCT a.floorID,floorName FROM FA.BuildingFloor AS a INNER JOIN FA.Equipment_Check_elec as b ON a.floorID = b.floorID WHERE b.b_number ='$buildNo' AND b.sysID =$systemNo";
        }
        $query =$pdo->query($str)->fetchAll();
        foreach ($query as $Info) {
            $dataArray['floorID']=$Info['floorID'];
            $dataArray['floorName']=$Info['floorName'];
            $dataArray2=array_push($dataArray1,$dataArray);
        }
        echo json_encode($dataArray1,JSON_UNESCAPED_UNICODE);


        
?>