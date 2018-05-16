<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    //接收資料
    $strDate=$_POST['datestr'];
    $endDate=$_POST['dateend'];
    $build_no=$_POST['build'];
    $system=$_POST['system'];
    // $equipt=$_POST['equipment'];
    // $shift=$_POST['class'];   
    
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
        switch ($system) {
        case '1':
            $sql_select="SELECT recordID,rDate FROM FA.Water_System_Record_Master WHERE b_number='$build_no' AND rDate BETWEEN '$strDate' AND '$endDate'";
            $select_master =$pdo->query($sql_select);                
            while($row = $select_master->fetch()){
                $MarsterID=$row['recordID'];
                $Date = $row['rDate'];
                $build_name=sql_database('B_name','FA.Building','b_number',$build_no);
                echo "<a href='mtupdatatable.php?sys=".$system."& id=".$MarsterID."& build=".$build_no."& r_date=".$Date."' class=\"list-group-item list-group-item-action\">".$Date.$build_name.'-水系統設備'.'</a>';
                }                        
            break;
        case '2':
            $sql_select="SELECT recordID,rDate FROM FA.Air_System_Record_Master WHERE b_number='$build_no' AND rDate BETWEEN '$strDate' AND '$endDate'";
            $select_master =$pdo->query($sql_select);                
            while($row = $select_master->fetch()){
                $MarsterID=$row['recordID'];
                $Date = $row['rDate'];
                $build_name=sql_database('B_name','FA.Building','b_number',$build_no);
                echo "<a href='mtupdatatable.php?sys=".$system."& id=".$MarsterID."& build=".$build_no."& r_date=".$Date."' class=\"list-group-item list-group-item-action\">".$Date.$build_name.'-醫療氣體設備'.'</a>';
                }                        
            break;
        case '3':
            $sql_select="SELECT recordID,rDate FROM FA.AirCond_System_Record_Master WHERE b_number='$build_no' AND rDate BETWEEN '$strDate' AND '$endDate'";
            $select_master =$pdo->query($sql_select);                
            while($row = $select_master->fetch()){
                $MarsterID=$row['recordID'];
                $Date = $row['rDate'];
                $build_name=sql_database('B_name','FA.Building','b_number',$build_no);
                echo "<a href='mtupdatatable.php?sys=".$system."& id=".$MarsterID."& build=".$build_no."& r_date=".$Date."' class=\"list-group-item list-group-item-action\">".$Date.$build_name.'-空調系統設備'.'</a>';
                }
            break;
        
        default:
            $sql_select="SELECT recordID,rDate FROM FA.HL_Vol_System_Record_Master WHERE b_number='$build_no' AND rDate BETWEEN '$strDate' AND '$endDate'";
            $select_master =$pdo->query($sql_select);                
            while($row = $select_master->fetch()){
                $MarsterID=$row['recordID'];
                $Date = $row['rDate'];
                $build_name=sql_database('B_name','FA.Building','b_number',$build_no);
                echo "<a href='mtupdatatable.php?sys=".$system."& id=".$MarsterID."& build=".$build_no."& r_date=".$Date."' class=\"list-group-item list-group-item-action\">".$Date.$build_name.'-高低壓電氣設備'.'</a>';
                }
            break;    
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