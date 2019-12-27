<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    include("page_err_app.php");
    $filter=$_GET['filter'];
    session_start();
    $userID=$_SESSION["login_number"];
    
    $pageRow_record=10;//每頁的筆數
    $page_num=1;//預設的頁數
        //更新$page_num        
    if (isset($_GET['page'])) {
        $page_num=$_GET['page'];
        $page_num=str_replace("\"", "", $page_num);
    }
    $startRow_record=($page_num-1)*$pageRow_record;
    $i=0;
    $href="employee.php?filter=no";
    if ($filter=='no') {
        $sqlstr="SELECT e_number,cname FROM FA.Employee ORDER BY e_number ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
        $total_member="SELECT COUNT(e_number) FROM FA.Employee";
    } else {
        @$action=$_GET["action"];
        @$action=str_replace("\"", "", $action);            
        if ($action=='next_page') {
            @$keyword=$_GET["keywordsearch"];
            @$keyword=str_replace("\"", "", $keyword);
        }
        if (isset($_GET["action"])&&($_GET["action"]=="new_page")){//重新搜索
            $action=='new_page';            
            @$keyword=$_GET["keywordsearch"];
        }
        
        $sqlstr="SELECT e_number,cname FROM FA.Employee WHERE cname like'%$keyword%' OR e_number like'%$keyword%' ORDER BY e_number ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
        $total_member="SELECT COUNT(e_number) FROM FA.Employee WHERE cname like'%$keyword%' OR e_number like'%$keyword%'";
    }
    
    
    $result=$pdo->query($sqlstr);    
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
    <!--新加入20190815表格排序-->
    <script src="./js/jquery.tablesorter.min.js" type="text/javascript"></script>
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
    
    <p class="d-inline font-weight-bold">&nbsp&nbsp&nbsp&nbsp人員數量共：<?= $member_num ?>員：
        <span class="billBoard1" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="請登入相應權限帳號已解鎖">
        <a href="employeeAdd.php"  name="mmtsysabtn" class="btn btn-primary autho1" Disabled  >新增新增人員</a>
        </span>
    </p>
    
    <div class="col-lg-6 container" >
    <?php    
    echo '<table id="employee" class="display table table-striped table-bordered table-hover col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12 table-sm order-table"  aria-describedby="dataTables-example_info" data-sort-name="tid" data-sort-order="desc" data-sortable ="true">';
        echo '<thead class="thead-light">';
        echo '<tr align="center">';
        echo '<th scope="col" width="40%" name="tid" sortable="true">人員編號</th>';
        echo '<th scope="col" width="40%">人員姓名</th>';
        echo '<th scope="col" width="20%">功能</th>';
        echo '</tr>';     
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
        if ($filter=='no') {
            echo '<table border="0" align="center">';    
            echo '<tr>';
                echo '<td><h5>'.'人員總數共'.$member_num.'員(共'.$total_page.'頁)'.'</h5></td>';
            echo '</tr>';
        echo '</table>';
        echo '<table border="0" align="center">';
            echo '<tr>';
                if ($page_num>1) {
                    $prev=$page_num-1;
                    echo '<td><a href="employee.php?filter=no&page=1">'."[第一頁]".'</a></td>';
                    echo "<td><a href=\"employee.php?page={$prev}\">"."[<<<上一頁]".'</a></td>';
                }
                if ($page_num<$total_page) {
                    $next=$page_num+1;
                    echo "<td>"."<a href=\"employee.php?filter=no&page={$next}\">"."[下一頁>>>]".'</a></td>';
                    echo "<td><a href=\"employee.php?filter=no&page=$total_page\">".'[最末頁]'.'</a></td>';
                }
            echo '</tr>';
        echo '</table>';
         //分頁按鈕一次七頁
            $phpfile = 'employee.php';
            $page= isset($_GET['page'])?$_GET['page']:1;        
            $getpageinfo = page($page,$member_num,$phpfile);
            echo '<div align="center">'; 
            echo $getpageinfo['pagecode'];//顯示分頁的html語法
            echo '</div>';
        } 
        
        if ($filter=='yes') {
            echo '<table border="0" align="center">';    
            echo '<tr>';
                echo '<td><h5>'.'人員總數共'.$member_num.'員(共'.$total_page.'頁)'.'</h5></td>';
            echo '</tr>';
        echo '</table>';
        echo '<table border="0" align="center">';
            echo '<tr>';
                if ($page_num>1) {
                    $prev=$page_num-1;
                    echo "<td><a href='employee.php?filter=yes&page=1\"&keywordsearch=\"".$keyword."\"&action=next_page'>"."[第一頁]".'</a></td>';
                    echo "<td><a href='employee.php?filter=yes&page={$prev}\"&keywordsearch=\"".$keyword."\"&action=next_page'>"."[<<<上一頁]".'</a></td>';
                    
                }
                if ($page_num<$total_page) {
                    $next=$page_num+1;
                    echo "<td>"."<a href='employee.php?filter=yes&page={$next}\"&keywordsearch=\"".$keyword."\"&action=next_page'>"."[下一頁>>>]".'</a></td>';
                    echo "<td><a href='employee.php?filter=yes&page=$total_page\"&keywordsearch=\"".$keyword."\"&action=next_page'>".'[最末頁]'.'</a></td>';
                    
                }
            echo '</tr>';
        echo '</table>';
         //分頁按鈕一次七頁
            $phpfile = 'employee.php';
            $page= isset($_GET['page'])?$_GET['page']:1;        
            $getpageinfo = pageemp($page,$member_num,$phpfile,$keyword);
            echo '<div align="center">'; 
            echo @$getpageinfo['pagecode'];//顯示分頁的html語法
            echo '</div>';
        }  
    ?>
    </div>
    <form action="employee.php" method="get" name="mtlist"> 
        <div class="container" >         
            <div align="left">
                </br>搜尋：<input type="text" name="keywordsearch">&nbsp&nbsp&nbsp<input type="submit" value="查詢">
            </div>           
        </div>         
        <input type="hidden" name="filter" value="yes">
        <input type="hidden" name="action" value="new_page">
        <input type="hidden" name="total_num" value="<?= $member_num?>">           
    </form>
    <script>
        (function(document) {
            //表格排序
            $("#employee").tablesorter();            
        })(document);
    </script>  
        <!-- footer網頁尾頁 -->
    <footer>
        <div id="footer"></div>
    </footer>

</body>

</html>