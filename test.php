<?php
include("php/fun.php");
// $updatainfo=updata_select('FA.Water_System_Record_Detail',24);
// foreach ($updatainfo as $key => $info) {
//     $equipCheckID= $info["equipCheckID"];
//     $ref=$info["ref"];
//     $equipCheckID_1=$info[$key][$key];
//     $ref_1=$info[$key][$key];
//     echo $equipCheckID.'下一個'.$ref.'<br>'.'這是key'.$key.'開始key'.$equipCheckID_1.'下一個'.$ref_1;
// }
// print_r($updatainfo);
// $updatainfo=updata_select('FA.Water_System_Record_Detail',24);
// $updata_qt=updata_num('FA.Water_System_Record_Detail',24);
// echo $updata_qt;
$itme=num("SELECT DISTINCT count(equipCheckID) FROM FA.Water_System_Record_Detail WHERE recordID=24");
$updata_qt=updata_num('FA.Water_System_Record_Detail',24);
echo $itme;
?>