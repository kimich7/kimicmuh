<?php

// if (empty($_COOKIE['date'])) {
    setcookie('date',$_GET["rankdate"]);
    setcookie('shift',$_GET["rank"]);    
// }
$date = $_GET["rankdate"];
$rank = $_GET["rank"];
$class = "";
switch ($rank) {
    case '1':
    $class = "早班";
    break;
    case '2':
    $class = "中班";
    break;
    case '3':
    $class = "晚班";
    break;
    case '4':
    $class = "巡檢";
    break;
    }
// echo json_encode($date.$class,JSON_UNESCAPED_UNICODE);
setcookie('className',$class);
$result = array();
$result['date'] = $date;
$result['class'] = $class;
// echo $result;
echo json_encode($result['date'].$result['class']);
?>