<?php
    $date =$_COOKIE["date"];//從cookie取出抄寫日期
    $class =$_COOKIE["shift"];//從cookie取出抄寫班別ID
    $shiftclass=$_COOKIE["className"];//從cookie取出抄寫邊別名稱
    $courtyardID=$_COOKIE["courtyard"];//從cookie取出院區id
    $courtyardName=$_COOKIE["courtyardName"];//從cookie取出院區名稱
    $result = array();
    $result1 = array();
    
    //下面這個IF是確認Cookie是否有樓層的資料，有的話才執行
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

    //日期，班別ID，班別名，院區ID，院區名
    $result['date'] = $date;
    $result['class'] = $class;
    $result['shiftclass'] = $shiftclass;
    $result['courtyardID']=$courtyardID;
    $result['courtyardName']=$courtyardName;
    $result12=array_push($result1,$result);
    echo json_encode($result1);
?>