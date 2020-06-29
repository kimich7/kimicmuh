<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    //接收資料
    $strDate=$_POST['datestr'];
    $endDate=$_POST['dateend'];
    $build_no=$_POST['build'];
    $system=$_POST['system'];
    $sysName=sql_database('sysName','FA.Equipment_System_Group','sysID',$system);
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
    <link rel="stylesheet" href="./node_modules/normalize.css/normalize.css">
    <!-- 連結自己的CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <!-- 連結外部的JS -->
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script src="./node_modules/popper.js/dist/umd/popper.min.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!--新加入20190815表格排序-->
    <script src="./js/jquery.tablesorter.min.js" type="text/javascript"></script>
    <!-- 連結自己的JS -->
    <script src="./js/main.js"></script>
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
            echo '<table  id="checklist" class="display tablesorter table table-striped table-bordered table-hover col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12 table-sm order-table" align="center" width="50%">';
            echo '<thead align="center" class="thead-light">';
            echo '<th class="th-sm">項  目</th>';
            echo '<th class="th-sm">不合格數量</th>';
            echo '</thead>';
            echo '<tbody>';
            $sql_select="SELECT recordID,rDate FROM FA.Water_System_Record_Master WHERE sysID=$system AND b_number='$build_no' AND rDate BETWEEN '$strDate' AND '$endDate'";
            $select_master =$pdo->query($sql_select);                
            while($row = $select_master->fetch()){
                $MarsterID=$row['recordID'];
                $error = "SELECT COUNT(id) FROM FA.Water_System_Error WHERE mid = $MarsterID and ans = 'FALSE' ";
                $error_num=CURRENT($pdo->query($error)->fetch());                
                $Date = $row['rDate'];
                $build_name=sql_database('B_name','FA.Building','b_number',$build_no);
                echo '<tr>';
                    echo "<td width='70%'><a href='mtupdatatable.php?sys=".$system."& id=".$MarsterID."& build=".$build_no."& r_date=".$Date."'>".$Date.$build_name.'-'.$sysName.'</a></td>';
                    echo '<td width="30%" align="center">';
                    echo $error_num;
                    echo '</td>';
                echo '</tr>';
                }  
                echo '</tbody>';
                echo '</table>';      
        ?>          
        </div>
    </section>
    <script>
        (function(document) {
            //表格排序
            $("#checklist").tablesorter();            
        })(document);
    </script> 
    <!-- footer網頁尾頁 -->
    <!-- footer網頁尾頁 -->
    <footer> 
        <div id="footer"></div>
    </footer>
</body>

</html>