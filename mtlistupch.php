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
    <!-- 連結外部的CSS -->
    <link rel="stylesheet" href="./css/bootstrap.css">
    <link href="./node_modules/fontawesome-free-5.0.6/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet">
    <!-- 連結自己的CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <!-- 連結外部的JS -->
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script src="./node_modules/popper.js/dist/umd/popper.min.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- 連結自己的JS -->
    <script src="./js/main.js"></script>
    <title>設備保養表單修改清單</title>
</head>

<body>
    <!-- header網頁標題 -->
    <header>
        <div id="header"></div>
    </header>
    <!-- 表單 -->
    <section class="container-fluid">
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
    </section>
    <!-- footer網頁尾頁 -->
    <footer> 
        <div id="footer"></div>
    </footer>
</body>

</html>