<?php
    $date =$_COOKIE["date"];
    $class =$_COOKIE["shift"];
    $shiftclass=$_COOKIE["className"];
    $courtyardID=$_COOKIE["courtyard"];
    $courtyardName=$_COOKIE["courtyardName"];
    $result = array();
    $result1 = array();
    if (isset($_COOKIE["floorID"])) {
        $buildID=$_COOKIE["buildID"];
        $buildName=$_COOKIE["buildName"];
        $floorID=$_COOKIE["floorID"];
        $floorName=$_COOKIE["floorName"];
        $result['buildID']=$buildID;
        $result['buildName']=$buildName;
        $result['floorID']=$floorID;
        $result['floorName']=$floorName;
    }
    $result['date'] = $date;
    $result['class'] = $class;
    $result['shiftclass'] = $shiftclass;
    $result['courtyardID']=$courtyardID;
    $result['courtyardName']=$courtyardName;
    $result12=array_push($result1,$result);
    echo json_encode($result1);
?>