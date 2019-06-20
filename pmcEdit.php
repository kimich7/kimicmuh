<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    session_start();
    $pmcid=$_GET['id'];
    $userID=$_SESSION["login_number"];//登錄人員ID
    $username=$_SESSION["login_member"] ;//登錄人員名稱
    $selectstr="SELECT d.id,d.e_number,d.category,d.title,d.createdOn,d.process,d.content,d.contract,d.building,d.endon,d.status,s.cname from FA.pmc as d LEFT JOIN FA.Employee as s ON d.e_number=s.e_number where d.id='$pmcid'";
    $select=$pdo->query($selectstr);
    while ($row=$select->fetcht()) {
        $pmc_id=$row['id'];
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
    <title>專案更新</title>
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