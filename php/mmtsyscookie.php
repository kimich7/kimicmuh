<?php
include("CMUHconndata.php");
include("fun.php");
session_start();
setcookie('mmtsysNo',$_GET["mmtsysNo"]);//選擇保養系統編號進Cookie
if (isset($_COOKIE["mmtsysNo"]) and $_COOKIE["mmtsysNo"]!='' ) {
    $mmtsysNo=$_COOKIE["mmtsysNo"];    
} else {
    $mmtsysNo=$_GET["mmtsysNo"];    
}
$mmtsysName=sql_database('sName','FA.MMT_sys','id',$mmtsysNo);
setcookie('mmtsysName',$mmtsysName);//選擇保養系統名稱進Cookie
$result = array();
$result1 = array();
$result['mmtsysNo'] = $mmtsysNo;
$result['mmtsysName'] = $mmtsysName;
$result12=array_push($result1,$result);
echo json_encode($result1);

?>