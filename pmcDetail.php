<?php
include("php/CMUHconndata.php");
include("php/fun.php");
session_start();
if(isset($_GET["id"]) and $_GET["id"] != ''){
    $id = $_GET["id"];
}
$userID=$_SESSION["login_number"];//登錄人員ID
$username=$_SESSION["login_member"] ;//登錄人員名稱
$detail="SELECT d.id,d.e_number,d.category,d.title,d.createdOn,d.process,d.content,d.contract,d.building,d.endon,d.status s.cname from FA.pmc as d LEFT JOIN FA.Employee as s ON d.e_number=s.e_number where d.id='".$id."'";
$pmcdetailstr=$pdo->query($detail);
while ($row=$pmcdetailstr -> fetch()) {
    $id=$row['id'];
    $pmcemp=$row['e_number'];
    $category=$row['category'];
    $title=$row['title'];
    $createdOn=$row['createdOn'];
    $process=$row['process'];
    $content=$row['content'];
    $contract=$row['contract'];
    $building=$row['building'];
    $endon=$row['endon'];
    $status=$row['status'];
    $cname=$row['cname'];
}

$selectprocess = "select p.id,p.content,p.createdOn,p.createdUser,p.updatedOn,p.updatedUser,p.deletedOn,p.deletedUser,p.status,s.cname from FA.process as p LEFT JOIN FA.Employee as s ON p.createdUser=s.e_number where p.pmc_id='".$id."' and p.related_id='' and p.status='Y' order by p.createdOn asc";
$processquery = $pdo-> query($selectprocess);
while($prorow=$processquery -> fetch()){
    $pro_arr[] = array(
        'id' => $prorow['id'],
        'content' => $prorow['content'],
        'createdOn' => $prorow['createdOn'],
        'createdUser' => $prorow['createdUser'],
        'updatedOn' => $prorow['updatedOn'],
        'updatedUser' => $prorow['updatedUser'],
        'deletedOn' => $prorow['deletedOn'],
        'deletedUser' => $prorow['deletedUser'],
        'status' => $prorow['status'],
        'cname' => $prorow['cname']
    );
};
$pro_num = count($pro_arr);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- 連結外部的CSS -->
    <link rel="stylesheet" href="./css/bootstrap.css">
    <link href="./node_modules/fontawesome-free-5.0.6/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./node_modules/normalize.css/normalize.css">
    <!-- 連結自己的CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <!-- 連結外部的JS -->
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script src="./node_modules/popper.js/dist/umd/popper.min.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- 連結自己的JS -->
    <script src="./js/main.js"></script>    
    <title>工程專案內容</title>
</head>
<body >
     <!-- header網頁標題 -->
    <header>
        <nav class="navbar navbar-expand-sm navbar-light bg-light">
            <a class="navbar-brand" href="index.html">
                <img src="./jpg/logo.png" alt="">
            </a>
        </nav>
    </header>
    
</body>
</html>