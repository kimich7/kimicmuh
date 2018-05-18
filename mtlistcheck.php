<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");    
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
    <title>設備保養表單未簽核清單</title>
</head>

<body>
    <!-- header網頁標題 -->
    <header>
       <div id="header"></div>
    </header>
    <!-- 未簽核清單 -->
    <section class="container-fluid">
        <h1 class="text-center">設備保養表單未簽核清單</h1>
        <div class="list-group mx-5 my-5">
        <?php
            $sys_water='FA.Water_System_Record_Master';
            $search_water=sys_search($sys_water);
            foreach ($search_water as $waterinfo) {
                $buildName=sql_database('B_name','FA.Building','b_number',$waterinfo["b_number"]);
                $rDate=$waterinfo["rDate"];
                echo '<a href="#" class="list-group-item list-group-item-action">'.$buildName."-"."水系統-".$rDate.'</a>';
            }            
        ?>    
        </div>
    </section>
    <!-- footer網頁尾頁 -->
    <footer>
        <div id="footer"></div>
    </footer>
</body>

</html>