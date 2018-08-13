<?php
setcookie('date',$_GET["rankdate"]);
setcookie('shift',$_GET["rank"]);
setcookie('courtyard',$_GET["courtyard"]); 
// setcookie('className',$class);
$date = $_GET["rankdate"];
$rank = $_GET["rank"];
$courtyardID = $_GET["courtyard"];
//ECHO $courtyardID;
$class = "";
$courtyardName="";
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
if ($courtyardID==1) {
    $courtyardName='學士院區';
} else {
    $courtyardName='五權院區';
}

setcookie('className',$class);
setcookie('courtyardName',$courtyardName);
$result = array();
$result['date'] = $date;
$result['class'] = $class;
echo json_encode($result['date'].$result['class']);
?>