<?php
    include("fun.php");
    $dataArray=array();
    $dataArray1=array();
    $str="SELECT id,sName FROM FA.MMT_sys";
    $query=item($str);
    foreach ($query as $datainfo) {
        $dataArray["id"] = $datainfo["id"];
        $dataArray["sName"]=$datainfo["sName"];
        $dataArray2=array_push($dataArray1,$dataArray);
    }
    echo json_encode($dataArray1,JSON_UNESCAPED_UNICODE);
?>