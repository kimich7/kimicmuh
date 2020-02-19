<?php
include("php/CMUHconndata.php");
//get data
$id = $_REQUEST["ID"];

//get content
$selectprocess = "select content from FA.process where id='".$id."'";
$selectquery = $pdo -> query($selectprocess);
while ($a= $selectquery->fetch()) {
    $content=$a['content'];
}
ini_set('memory_limit', '-1');
echo $content;
?>