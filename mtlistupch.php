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
                $sql_select="SELECT recordID FROM FA.Water_System_Record_Master WHERE b_number='$build_no' AND rDate BETWEEN '$strDate' AND '$endDate'";
                $select_master =$pdo->query($sql_select);                
                while($row = $select_master->fetch()){
                    $MarsterID=$row['recordID'];
                    //$Detail="SELECT recordDetailID,equipCheckID,rDate,equipID FROM FA.Water_System_Record_Detail WHERE rDate BETWEEN '$strDate' AND '$endDate' AND recordID= $MarsterID ";
                    $Detail="SELECT DISTINCT equipID, rDate,shiftID FROM FA.Water_System_Record_Detail WHERE rDate BETWEEN '$strDate' AND '$endDate' AND recordID= $MarsterID ";
                    $Detail_query=$pdo->query($Detail);
                    while ($rowd = $Detail_query->fetch()) {
                        $equip_sys= sql_database('equipName','FA.Equipment_System','equipID',$rowd["equipID"]);
                        //$equip_che=sql_database('equipCheckName','FA.Equipment_Check','equipCheckID',$rowd["equipCheckID"]);
                        $build_name=sql_database('B_name','FA.Building','b_number',$build_no);
                        $shiftName=sql_database('shiftName','FA.Shift_Table','shiftID',$rowd["shiftID"]);
                        //echo '<a href="#" class="list-group-item list-group-item-action">'.$rowd['rDate'].$build_name.'-水系統設備-'.$equip_sys.'-'.$equip_che.'</a>';
                        echo "<a href='mtupdatatable.php?sys=".$system."& id=".$row['recordID']."& building=".$build_no."& rdate=".$rowd['rDate']."& equip=".$rowd["equipID"]."& shift=".$rowd["shiftID"]."' class=\"list-group-item list-group-item-action\">".$rowd['rDate'].$build_name.'-水系統設備-'.$equip_sys.'-'.$shiftName.'</a>';
                    }
                }            
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