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
    <title>中國附醫工務系統首頁</title>
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
            <!-- 將搜尋改為登入 -->
            <form class="form-inline my-2 ml-auto my-lg-0">
              <span>使用者:
                <input class="form-control mr-sm-2" type="text" placeholder="UserName">
              </span> 
              <span>密碼:
                <input type="password" class="form-control mr-sm-2" id="inputPassword3" placeholder="Password">
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
        <div class="body row">
            <!-- 工務設備運轉抄表文件及管理系統 -->
            <div class="col-lg-4 system">
                <h3 class="systitle  bg-success">工務設備運轉抄表文件及管理系統
                    <span>
                        <button type="button" data-toggle="collapse" data-target="#article1" class="btn btn-success">
                            <i class="far fa-1x fa-caret-square-down"></i>
                        </button>
                    </span>
                </h3>
                <article class="my-3 collapse show" id="article1">
                    <ul class="syslist list-group">
                        <li class="list-group-item list-group-item-success">
                            <a class="text-dark" href="#">工務設備運轉抄表文件新增表單</a>
                        </li>
                        <li class="list-group-item list-group-item-success">
                            <a class="text-dark" href="#">工務設備運轉抄表文件修改表單</a>
                        </li>
                        <li class="list-group-item list-group-item-success">
                            <a class="text-dark" href="#">工務設備運轉抄表文件未簽核清單</a>
                        </li>
                    </ul>
                </article>
            </div>
            <!-- 工務設備保養紀錄文件及管理系統 -->
            <div class="col-lg-4 system">
                <h3 class="systitle  bg-danger">工務設備保養紀錄文件及管理系統
                    <span>
                        <button type="button" data-toggle="collapse" data-target="#article2" class="btn btn-danger">
                            <i class="far fa-1x fa-caret-square-down"></i>
                        </button>
                    </span>
                </h3>
                <article class="my-3 collapse show" id="article2">
                    <ul class="syslist list-group">
                        <li class="list-group-item list-group-item-danger">
                            <a class="text-dark" href="./mtinsert.php">工務設備保養紀錄文件新增表單</a>
                        </li>
                        <li class="list-group-item list-group-item-danger">
                            <a class="text-dark" href="./mtupdata.php">工務設備保養紀錄文件修改表單</a>
                        </li>
                        <li class="list-group-item list-group-item-danger">
                            <a class="text-dark" href="./mtlistcheck.php">工務設備保養紀錄文件未簽核清單</a>
                        </li>
                        <li class="list-group-item list-group-item-danger">
                            <a class="text-dark" href="#">工務設備保養異常紀錄</a>
                        </li>
                    </ul>
                </article>
                <!-- 中央彙整系統 -->
            </div>
            <!-- 中央彙整系統 -->
            <div class="col-lg-4 system">
                <h3 class="systitle bg-primary">中央彙整系統
                    <span>
                        <button type="button" data-toggle="collapse" data-target="#article3" class="btn btn-primary">
                            <i class="far fa-1x fa-caret-square-down"></i>
                        </button>
                    </span>
                </h3>
                <article class="my-3 collapse show" id="article3">
                    <ul class="syslist list-group">
                        <li class="list-group-item list-group-item-primary">
                            <a class="text-dark" href="#">權限設定</a>
                        </li>
                        <li class="list-group-item list-group-item-primary">
                            <a class="text-dark" href="#">帳號設定</a>
                        </li>
                        <li class="list-group-item list-group-item-primary">
                            <a class="text-dark" href="#">密碼設定</a>
                        </li>
                    </ul>
                </article>

            </div>
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