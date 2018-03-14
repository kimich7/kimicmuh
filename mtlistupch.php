<?php
    include("CMUHconndata.php");
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
    <title>設備保養表單修改清單</title>
</head>

<body>
<!--接收資料php-->
    <?php
    //接收資料
    $strDate=$_POST['datestr'];
    $endDate=$_POST['dateend'];
    $building=$_POST['build'];
    $system=$_POST['system'];
    $equipt=$_POST['equipment'];
    $shift=$_POST['class'];
    
    switch ($system) {
        case '1':
            $num="SELECT COUNT(recordID) FROM FA.Water_System_Record_Master WHERE b_number='$build_no' AND rDate BETWEEN '$strDate' AND '$endDate'";
            $num_query=Current($pdo->query($num)->fetch());                
            for ($i=0; $i <$num_query ; $i++) { 
                $sql_select="SELECT recordID FROM FA.Water_System_Record_Master WHERE b_number='$build_no' AND rDate BETWEEN '$strDate' AND '$endDate'";
                $select_master =$pdo->query($sql_select)->fetch();            
                $MasterID=$select_master['recordID'];
                $Detail_num="SELECT COUNT(recordDetailID) FROM FA.Water_System_Record_Detail WHERE rDate BETWEEN '$strDate' AND '$endDate' AND recordID='$MasterID'";
                $Detail_num_query=Current($pdo->query($Detail_num)->fetch());
                for ($q=0; $q <$Detail_num_query ; $q++) { 
                    $Detail="SELECT recordDetailID,rDate,equipID FROM FA.Water_System_Record_Detail WHERE rDate BETWEEN '$strDate' AND '$endDate' AND recordID='$MasterID'";
                }
            }
            
            break;
        case '2':
            # code...
            break;
        case '3':
            # code...
            break;
        
        default:
            # code...
            break;
    }
    $select_list_query=$pdo->query($select_list)->fetchAll();
    ?>
    <!-- 導覽列 -->
    <div id="navbar"></div>
    <!-- header網頁標題 -->
    <div id="header"></div>
    <!-- section網頁系統部分 -->
    <!-- .container自動於網站上幫你留白 -->
    <section class="container-fluid">
    
        <!-- 表單 -->
        <h1 class="text-center">設備保養表單修改清單</h1>
        <div class="list-group mx-5 my-5">
        <?php
        foreach ($select_list_query as $list_info) {
            echo '<a href="#" class="list-group-item list-group-item-action">'.$list_info['rDate'].'</a>';
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
    <div id="footer"></div>
</body>

</html>