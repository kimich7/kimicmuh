<?php
    include("CMUHconndata.php");
    include("fun.php");    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- 連結自製化的Bootatrap.css -->
    <link rel="stylesheet" href="./css/bootstrap.css">
    <!-- 連結Normalize.min.css的網址使得網站在各個瀏覽器看起來相同 -->
    <link rel="stylesheet" href="./node_modules/normalize.css/normalize.css">
    <!-- 連結fontawesome的網址使得網站可以使用fontawesome的icon -->
    <link href="./node_modules/fontawesome-free-5.0.6/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet">
    <!-- 如果連結了客(自)製化的Bootstrap,原先連接的版本要註解掉 -->
    <!-- 連結Bootstrap的網址使得網站可以使用Bootstrap語法 -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> -->
    <!-- 連結animate的網址使得網站可以使用animate語法 -->
    <link rel="stylesheet" href="./node_modules/animate.css/animate.min.css">
    <!-- 連結自己的CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <!-- 連結Bootstrap jQuery的網址使得網站可以使用JS, Popper.js, and jQuery語法 -->
    <!-- 並把jQuery變更為完整的jQuery -->
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>被變更的 -->
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- 連結wow.js的網址使得網站可以使用WOW的滾動動畫(必須連接animate.css) -->
    <script src="./node_modules/wow.js/dist/wow.min.js"></script>
    <!-- 連結自己的JS -->
    <script src="./js/main.js"></script>
    <title>設備保養表單未簽核清單</title>
</head>

<body>
    <!-- 導覽列 -->
    <nav class="navbar fixed-top navbar-expand-sm navbar-light bg-light">
        <a class="navbar-brand" href="./index.php">
            <img src="./jpg/logo.png" alt="">
        </a>
        <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#collapsibleNavId" aria-controls="collapsibleNavId"
            aria-expanded="false" aria-label="Toggle navigation"></button>
        <div class="collapse navbar-collapse" id="collapsibleNavId">
            <!-- 使用者登入 -->
            <form action="tlogin.php" method="post" class="form-inline my-2 ml-auto my-lg-0">
                <span>使用者帳號:
                    <input class="form-control mr-sm-2" type="text" placeholder="UserName" name="username">
                </span>
                <span>使用者密碼:
                    <input type="password" class="form-control mr-sm-2" name="password" id="inputPassword3" placeholder="Password">
                </span>
                <button class="btn btn-outline-success  my-2 my-sm-0" type="submit">登入</button>
            </form>
        </div>
    </nav>
    <!-- header網頁標題 -->
    <header class="jumbotron text-center" id="header">
        <div class="container header-content">
            <h1 class="display-4 wow zoomIn data-wow-duration=" 5s " ">中國附醫工務系統</h1>
        </div>
        <div class="header-cover"></div>
    </header>
    <!-- section網頁系統部分 -->
    <!-- .container自動於網站上幫你留白 -->
    <section class="container-fluid">
        <!-- 表單 -->
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
        <!-- 換頁選單 -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">3</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    </section>

    <!-- 網頁尾頁部分 -->
    <!-- footer -->
    <footer id="footer" class="bg-secondary text-white pt-5">
        <!-- 設定以下四塊FooterList在電腦螢幕開啟時排成一個橫排 -->
        <div class="row">
            <div class="footer-list">
                <h4 class="container-fluid">聯絡我們</h4>

                <ul>
                    <li>
                        <a class="text-dark" href="mailto:service@brand.com">聯絡信箱: service@brand.com</a>
                    </li>
                    <li>
                        <a class="text-dark" href="#">About Us</a>
                    </li>
                    <li>
                        <a class="text-dark" href="#">Blog</a>
                    </li>
                    <li>
                        <a class="text-dark" href="#">Press</a>
                    </li>
                    <li>
                        <a class="text-dark" href="#">Bonus</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-end bg-dark mt-5 py-2">
            <p class="text-center">&copy; Copy right 2018</p>
        </div>
    </footer>
</body>

</html>