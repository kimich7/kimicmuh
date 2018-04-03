<?php
$date = $_GET["rankdate"];
$rank = $_GET["rank"];
$class = "";
switch ($rank) {
    case '1':
    $class = "早班";
    break;
    case '2':
    $class = "午班";
    break;
    case '3':
    $class = "晚班";
    break;
    case '4':
    $class = "巡檢";
    break;
    }
echo json_encode($date.$class,JSON_UNESCAPED_UNICODE);
?>