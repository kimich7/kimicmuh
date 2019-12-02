<?php
    include("CMUHconndata.php");
    include("fun.php");
    $dataArray=array();
    $dataArray1=array();    
    $str="SELECT id,bName FROM FA.MMT_build WHERE sid ='B'";
    $query=item($str);
    foreach ($query as $datainfo) {
        $dataArray["id"] = $datainfo["id"];
        $dataArray["bName"]=$datainfo["bName"];
        $dataArray2=array_push($dataArray1,$dataArray);
    }
    echo json_encode($dataArray1,JSON_UNESCAPED_UNICODE);
?>