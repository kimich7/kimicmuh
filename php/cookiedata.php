<?php
    $date =$_COOKIE["date"];
    $class =$_COOKIE["shift"];
    $shiftclass=$_COOKIE["className"];
    $result = array();
    $result1 = array();
    $result['date'] = $date;
    $result['class'] = $class;
    $result['shiftclass'] = $shiftclass;
    $result12=array_push($result1,$result);
    echo json_encode($result1);
?>