<?php
    include("CMUHconndata.php");
    include("fun.php");
    $empArray=array();
    $empArray1=array();
    $emp=item("SELECT e_number,cname FROM FA.Employee");
    foreach ($emp as $empinfo) {
        $empArray["e_number"]=$empinfo["e_number"];
        $empArray["cname"]=$empinfo["cname"];
        $empArray2=array_push($empArray1,$empArray);
    }
    echo json_encode($empArray1,JSON_UNESCAPED_UNICODE);
?>