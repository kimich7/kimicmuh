<?php
    include("fun.php");
    $dataArray=array();
    $dataArray1=array();
    if ($_REQUEST["seachNo"]=='1') {
        $courtyardID=$_COOKIE["courtyard"];
    } else {
        $courtyardID=$_REQUEST["cyID"];
    }
    if (!empty($_REQUEST['colID'])) {
        $colID=$_REQUEST['colID'];
        $colName=$_REQUEST['colName'];
    }
    $str="SELECT $colID,$colName FROM FA.Building WHERE c_number=$courtyardID";
    $query=item($str);
    foreach ($query as $datainfo) {
        $dataArray["$colID"] = $datainfo["$colID"];
        $dataArray["$colName"]=$datainfo["$colName"];
        $dataArray2=array_push($dataArray1,$dataArray);
    }
    echo json_encode($dataArray1,JSON_UNESCAPED_UNICODE);
?>