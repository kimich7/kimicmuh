<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    $pageRow_record=10;//每頁的筆數
    $page_num=1;//預設的頁數
        //更新$page_num        
    if (isset($_GET['page'])) {
        $page_num=$_GET['page'];
    }
    $startRow_record=($page_num-1)*$pageRow_record;
    $i=0;    
    $sqlstr="SELECT e_number,cname FROM FA.Employee ORDER BY e_number ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
    $result=$pdo->query($sqlstr);
    $total_member="SELECT COUNT(e_number) FROM FA.Employee";
    $member_num=CURRENT($pdo->query($total_member)->fetch());
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- 連結外部的CSS -->
    <!-- 新設 -->
    <link href="./css/jquery-ui.min.css" rel="stylesheet">
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
    <!-- 新設 -->
    <script src="./js/jquery-ui.min.js"></script>
    <title>工務所人員管理系統</title>
</head>

<body>
    <!-- header網頁標題 -->
    <header>
        <div id="header"></div>
    </header>
    <!-- 表格內容 -->
    <h2 align="center">工務所人員管理系統</h2>
    <p align="center">人員數量共：<?= $member_num ?>員，<a href="employeeAdd.php">新增人員</a>。</p>
    <?php    
    echo '<table border="2" align="center" cellpadding="6">';
        echo '<thead align="center" height="30">';
            echo '<th width="40%">人員編號</th>';
            echo '<th width="40%">人員姓名</th>';
            echo '<th width="20%">功能</th>';
        echo '</thead>';
        echo '<tbody>';    
    while ($employeeData=$result->fetch()) {
        echo '<tr height="30">';
            echo '<td align="center">'.$employeeData["e_number"].'</td>';
            echo '<td align="center">'.$employeeData["cname"].'</td>';
            echo '<td align="center">'.'<a href="employeeUpdata.php?id='.$employeeData['e_number'].'" >'.'修改</a>'."&nbsp ".'<a href="employeeDelete.php?id='.$employeeData['e_number'].'" >'.'刪除</a></td>';
        echo '<tr>';
    }
        echo '</tbody>';
    echo '</table>';
    //計算總頁數
    $total_page=ceil($member_num/$pageRow_record);
    echo '<table border="0" align="center">';    
            echo '<tr>';
                echo '<td><h5>'.'人員總數共'.$member_num.'員(共'.$total_page.'頁)'.'</h5></td>';
            echo '</tr>';
        echo '</table>';
        echo '<table border="0" align="center">';
            echo '<tr>';
                if ($page_num>1) {
                    $prev=$page_num-1;
                    echo '<td><a href="employee.php?page=1">'."[第一頁]".'</a></td>';
                    echo "<td><a href=\"employee.php?page={$prev}\">"."[<<<上一頁]".'</a></td>';
                }
                if ($page_num<$total_page) {
                    $next=$page_num+1;
                    echo "<td>"."<a href=\"employee.php?page={$next}\">"."[下一頁>>>]".'</a></td>';
                    echo "<td><a href=\"employee.php?page=$total_page\">".'[最末頁]'.'</a></td>';
                }
            echo '</tr>';
        echo '</table>';
        echo '<nav aria-label="Page navigation example">';
                echo '<ul class="pagination justify-content-center">';
                        for ($i=1; $i <= $total_page; $i++) {
                            if ($i==$page_num) {
                                echo "<li class=\"page-item\"><span class='page-link text-danger' href=#><b>{$i}</b></span></li>";
                            } else {
                                echo "<li class=\"page-item\"><a class='page-link' href=\"employee.php?page={$i}\">{$i}</a></li>";
                            }
                        }
                echo '</ul>';
        echo '</nav>';
    ?>
        <!-- footer網頁尾頁 -->
    <footer>
        <div id="footer"></div>
    </footer>

</body>

</html>