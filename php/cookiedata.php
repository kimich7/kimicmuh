<?php
    //setcookie('shiftclass',$shiftclass);
    $date =$_COOKIE["date"];
    $class =$_COOKIE["shift"];
    $shiftclass=$_COOKIE["className"];
    $result = array();
    $result1 = array();
    // $shiftclass= "";
    // switch ($class) {
    //     case '1':
    //     $shiftclass = "早班";
    //     break;
    //     case '2':
    //     $shiftclass = "中班";
    //     break;
    //     case '3':
    //     $shiftclass = "晚班";
    //     break;
    //     case '4':
    //     $shiftclass = "巡檢";
    //     break;
    //     }
    $result['date'] = $date;
    $result['class'] = $class;
    $result['shiftclass'] = $shiftclass;
    $result12=array_push($result1,$result);
    // echo $result;    
    echo json_encode($result1);
?>