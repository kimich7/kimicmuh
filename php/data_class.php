<?php
setcookie('date',$_GET["rankdate"]);//抄寫日期進Cookie
setcookie('shift',$_GET["rank"]);//班別ID進Cookie
setcookie('courtyard',$_GET["courtyard"]);//院區 進Cookie
// setcookie('className',$class);
$date = $_GET["rankdate"];
$rank = $_GET["rank"];//班別
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

setcookie('className',$class);//班別名稱進cookie
setcookie('courtyardName',$courtyardName);//院區名稱進cookie
$result = array();
$result['date'] = $date;
$result['class'] = $class;
echo json_encode($result['date'].$result['class']);//送出去的json資料為，班別名稱與抄寫日期
?>