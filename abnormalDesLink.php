<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    include("php/errfun.php");
    include("page_err_app.php");
    $filter=$_GET['filter'];
    session_start();
    $userID=$_SESSION["login_number"];
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
    <title>尚未處理的異常事件清單</title>
</head>
<body>
    <!-- header網頁標題 -->
    <header>
       <div id="header"></div>
    </header>
    <form action="" method="post" name="abnormallist">
    <section class="container-fluid">
        <h1 class="text-center">尚未處理的異常事件清單</h1>
        <?php
        $href="abnormalDesLink.php?filter=no";
        if ($filter=='yes') {?>
         <div class="d-flex ">        
            <a href='abnormalDesLink.php?filter=no' type="button" id="backlistmmt" class="btn btn-primary mt-4 rounded d-block mr-3">清除條件</a>
        </div>
        <?php }?> 
        <div class="list-group mx-5 my-5">
        <?php
        $pageRow_record=10;//每頁的筆數
        $page_num=1;//預設的頁數
        //更新$page_num        
        if (isset($_GET['page'])) {
            $page_num=$_GET['page'];
            $page_num=str_replace("\"", "", $page_num);
        }
        $startRow_record=($page_num-1)*$pageRow_record;

        if ($filter=='no') {
        //所有的資料
        $sqlstr_total="SELECT case_id,case_title,case_time,case_userID,case_manageID FROM FA.Abnormal_Notification_System_Master WHERE work_emp ='$userID'ORDER BY case_time DESC";
        //篩選後給每頁的筆數
        $sqlstr_page="SELECT case_id,case_title,case_time,case_userID,case_manageID FROM FA.Abnormal_Notification_System_Master WHERE work_emp ='$userID' ORDER BY case_id DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
        //總資料數量
        $totalstr_num="SELECT COUNT(case_id) FROM FA.Abnormal_Notification_System_Master WHERE work_emp = '$userID'";        
        }else{
            @$action=$_GET["action"];
            @$action=str_replace("\"", "", $action);            
            if ($action=='next_page') {
                @$start_date=$_GET["start_date"];
                @$start_date=str_replace("\"", "", $start_date);
                @$end_date=$_GET["end_date"];
                @$end_date=str_replace("\"", "", $end_date);
                @$keyword=$_GET["keywordsearch"];
                @$keyword=str_replace("\"", "", $keyword);
            }
            if (isset($_GET["action"])&&($_GET["action"]=="new_page")){//重新搜索
                $action=='new_page';
                @$start_date=$_GET["start_date"];
                @$end_date=$_GET["end_date"];
                @$keyword=$_GET["keywordsearch"];
            }
            
            $x=errfilt($href,$start_date,$end_date,$keyword);
            switch ($x) {
                case 0://只有keyword
                    header("Location:$href");
                    break;
                case 1://只有keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total="SELECT a.* FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number LEFT JOIN FA.CheckStatus as p ON a.manage_status=p.id where ( b.cname like '%$keyword%' or a.case_title like '%$keyword%' or a.case_location like '%$keyword%' or a.case_time like '%$keyword%' or a.confirm_date like '%$keyword%'or p.checkName like '%$keyword%') AND work_emp ='$userID'";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number LEFT JOIN FA.CheckStatus as p ON a.manage_status=p.id where ( b.cname like '%$keyword%' or a.case_title like '%$keyword%' or a.case_location like '%$keyword%' or a.case_time like '%$keyword%' or a.confirm_date like '%$keyword%'or p.checkName like '%$keyword%') AND work_emp ='$userID' ORDER BY a.case_time DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                   //總資料數量
                    $totalstr_num="SELECT COUNT(case_id) FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number LEFT JOIN FA.CheckStatus as p ON a.manage_status=p.id where ( b.cname like '%$keyword%' or a.case_title like '%$keyword%' or a.case_location like '%$keyword%' or a.case_time like '%$keyword%' or a.confirm_date like '%$keyword%'or p.checkName like '%$keyword%') AND work_emp ='$userID'";
                    break;
                case 2://只有日期區間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number where (a.case_time between '$start_date' and '$end_date' or a.confirm_date between '$start_date' and '$end_date') AND work_emp ='$userID'";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number where (a.case_time between '$start_date' and '$end_date' or a.confirm_date between '$start_date' and '$end_date') AND work_emp ='$userID' ORDER BY a.case_time DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(case_id) FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number where (a.case_time between '$start_date' and '$end_date' or a.confirm_date between '$start_date' and '$end_date') AND work_emp ='$userID'";
                    break;
                case 3://只有開始時間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number  where (a.case_time ='$start_date' or a.confirm_date ='$start_date') AND work_emp ='$userID'";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number where (a.case_time ='$start_date' or a.confirm_date ='$start_date') AND work_emp ='$userID' ORDER BY a.case_time DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(case_id) FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number where (a.case_time ='$start_date' or a.confirm_date ='$start_date') AND work_emp ='$userID'";
                    break;
                case 4://只有結束時間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number where (a.case_time ='$end_date' or a.confirm_date ='$end_date') AND work_emp ='$userID'";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number where (a.case_time ='$end_date' or a.confirm_date ='$end_date') AND work_emp ='$userID' ORDER BY a.case_time DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(case_id) FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number where (a.case_time ='$end_date' or a.confirm_date ='$end_date') AND work_emp ='$userID'";
                    break;
                case 5://開始+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number LEFT JOIN FA.CheckStatus as p ON a.manage_status=p.id where ((a.case_time ='$start_date'or a.confirm_date= '$start_date') and ( b.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%'or p.checkName like '%$keyword%')) AND work_emp ='$userID'";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number LEFT JOIN FA.CheckStatus as p ON a.manage_status=p.id where ((a.case_time ='$start_date'or a.confirm_date = '$start_date') and ( b.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%'or p.checkName like '%$keyword%')) AND work_emp ='$userID' ORDER BY a.case_time DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(case_id) FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number LEFT JOIN FA.CheckStatus as p ON a.manage_status=p.id where ((a.case_time ='$start_date'or a.confirm_date = '$start_date') and ( b.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%'or p.checkName like '%$keyword%')) AND work_emp ='$userID'";
                    break;
                case 6://結束+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number LEFT JOIN FA.CheckStatus as p ON a.manage_status=p.id where ((a.case_time='$end_date'or a.confirm_date = '$end_date')  and ( b.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%'or p.checkName like '%$keyword%')) AND work_emp ='$userID'";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number LEFT JOIN FA.CheckStatus as p ON a.manage_status=p.id where ((a.case_time='$end_date'or a.confirm_date = '$end_date')  and ( b.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%'or p.checkName like '%$keyword%')) AND work_emp ='$userID' ORDER BY a.case_time DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(case_id) FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number LEFT JOIN FA.CheckStatus as p ON a.manage_status=p.id where ((a.case_time='$end_date'or a.confirm_date = '$end_date')  and ( b.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%'or p.checkName like '%$keyword%')) AND work_emp ='$userID'";
                    break;
                case 7://日期區間+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number LEFT JOIN FA.CheckStatus as p ON a.manage_status=p.id where ( (a.case_time between '$start_date' and '$end_date' or a.confirm_date between '$start_date' and '$end_date')and (b.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%'or p.checkName like '%$keyword%')) AND work_emp ='$userID'";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number LEFT JOIN FA.CheckStatus as p ON a.manage_status=p.id where ( (a.case_time between '$start_date' and '$end_date' or a.confirm_date between '$start_date' and '$end_date')and (b.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%'or p.checkName like '%$keyword%')) AND work_emp ='$userID' ORDER BY a.case_time DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(case_id) FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Employee as b on a.case_userID=b.e_number or a.case_manageID=b.e_number or a.work_emp=b.e_number LEFT JOIN FA.CheckStatus as p ON a.manage_status=p.id where ( (a.case_time between '$start_date' and '$end_date' or a.confirm_date between '$start_date' and '$end_date')and (b.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%'or p.checkName like '%$keyword%')) AND work_emp ='$userID'";
                    break;                
            }
        }
        $sql_page=$pdo->query($sqlstr_page);
        $sql_total=$pdo->query($sqlstr_total);
        $total_num=CURRENT($pdo->query($totalstr_num)->fetch());
        $pdo=null;
        //本頁開始的筆數
        $i=0;        
        $k=$i+2000;
        echo '<table id="abnormalD" class="display table table-striped table-bordered table-hover col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12 table-sm order-table"  aria-describedby="dataTables-example_info" data-sort-name="tid" data-sort-order="desc" data-sortable ="true">';
        echo '<thead class="thead-light">';
        echo '<tr align="center">';
        echo '<th scope="col" width="10%" name="tid" sortable="true">日  期</th>';
        echo '<th scope="col" width="45%">案件名稱</th>';
        echo '<th scope="col" width="10%">發現人員</th>';
        echo '<th scope="col" width="10%">指派主管</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        $mgcheck="mgcheck";
        $mbcheck="mbcheck";
        while ($data_page = $sql_page->fetch()) {
            $desemp=$i;
            $case_id=$data_page["case_id"];
            $case_title=$data_page["case_title"];
            $case_date=$data_page["case_time"];
            $csaeFindEmpID=$data_page["case_userID"];
            $manageID=$data_page["case_manageID"];
            $manageName=sql_database('cname','FA.Employee','e_number',$manageID);
            echo '<tr align="center">';
            $findEmpName=sql_database('cname','FA.Employee','e_number',$csaeFindEmpID);
                //顯示發現日期
                echo '<th scope="row" align="center">'.$case_date.'</th>';
                //顯示事件名稱，且需要的話可以點進去看詳細情形
                echo "<td align='center'><a href='abnormalDesTable.php?id=\"".$case_id."\"' class=\".list-group-item list-group-item-action.\">".$case_title.'</a></td>';
                //顯示發現異常事件人員
                echo '<td width="10%" align="center">';
                    echo $findEmpName;
                echo '</td>';
                echo '<td width="10%" align="center">';
                    echo $manageName;
                echo '</td>';
                $q=$case_id;
                echo "<input type='hidden' name=\"".$k."\" value=\"".$q."\">";//傳遞case_id
            $i++;
            $k++;
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        
        //計算總頁數
        $total_page=ceil($total_num/$pageRow_record);
        if ($filter=='no') {
            echo '<table border="0" align="center">';    
            echo '<tr>';
                echo '<td><h5>'.'您尚未完成的異常處理事件共'.$total_num.'件(共'.$total_page.'頁)'.'</h5></td>';
            echo '</tr>';
        echo '</table>';
        echo '<table border="0" align="center">';
            echo '<tr>';
                if ($page_num>1) {
                    $prev=$page_num-1;
                    echo '<td><a href="abnormalDesLink.php?filter=no&page=1">'."[第一頁]".'</a></td>';
                    echo "<td><a href=\"abnormalDesLink.php?page={$prev}\">"."[<<<上一頁]".'</a></td>';
                }
                if ($page_num<$total_page) {
                    $next=$page_num+1;
                    echo "<td>"."<a href=\"abnormalDesLink.php?filter=no&page={$next}\">"."[下一頁>>>]".'</a></td>';
                    echo "<td><a href=\"abnormalDesLink.php?filter=no&page=$total_page\">".'[最末頁]'.'</a></td>';
                }
            echo '</tr>';
        echo '</table>';
         //分頁按鈕一次七頁
            $phpfile = 'abnormalDesLink.php';
            $page= isset($_GET['page'])?$_GET['page']:1;        
            $getpageinfo = page($page,$total_num,$phpfile);
            echo '<div align="center">'; 
            echo $getpageinfo['pagecode'];//顯示分頁的html語法
            echo '</div>';
        } 
        
        if ($filter=='yes') {
            echo '<table border="0" align="center">';    
            echo '<tr>';
                echo '<td><h5>'.'您尚未完成的異常處理事件共'.$total_num.'筆(共'.$total_page.'頁)'.'</h5></td>';
            echo '</tr>';
        echo '</table>';
        echo '<table border="0" align="center">';
            echo '<tr>';
                if ($page_num>1) {
                    $prev=$page_num-1;
                    echo "<td><a href='abnormalDesLink.php?filter=yes&page=1\"&start_date=\"".$start_date."\"&end_date=\"".$end_date."\"&keywordsearch=\"".$keyword."\"&action=next_page'>"."[第一頁]".'</a></td>';
                    echo "<td><a href='abnormalDesLink.php?filter=yes&page={$prev}\"&start_date=\"".$start_date."\"&end_date=\"".$end_date."\"&keywordsearch=\"".$keyword."\"&action=next_page'>"."[<<<上一頁]".'</a></td>';
                    
                }
                if ($page_num<$total_page) {
                    $next=$page_num+1;
                    echo "<td>"."<a href='abnormalDesLink.php?filter=yes&page={$next}\"&start_date=\"".$start_date."\"&end_date=\"".$end_date."\"&keywordsearch=\"".$keyword."\"&action=next_page'>"."[下一頁>>>]".'</a></td>';
                    echo "<td><a href='abnormalDesLink.php?filter=yes&page=$total_page\"&start_date=\"".$start_date."\"&end_date=\"".$end_date."\"&keywordsearch=\"".$keyword."\"&action=next_page'>".'[最末頁]'.'</a></td>';
                    
                }
            echo '</tr>';
        echo '</table>';
         //分頁按鈕一次七頁
            $phpfile = 'abnormalDesLink.php';
            $page= isset($_GET['page'])?$_GET['page']:1;        
            $getpageinfo = pageerrapp($page,$total_num,$phpfile,$start_date,$end_date,$keyword);
            echo '<div align="center">'; 
            echo $getpageinfo['pagecode'];//顯示分頁的html語法
            echo '</div>';
        }
        ?>
        </div>
        <input type="hidden" name="total_num" value="<?= $total_num?>">;
        <input type="hidden" name="action" value="check">        
    </section>
    </form>
    <!--filter-->
    <form action="abnormalDesLink.php" method="get" name="mtlist"> 
        <div>
            <div align="left">
                日期區間搜索：</br>
                開始時間：<input type="date" name="start_date">&nbsp&nbsp結束時間：<input type="date" name="end_date">            
            </div>
            <div align="left">
                </br>關鍵字：<input type="text" name="keywordsearch">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="submit" value="查詢">
            </div>           
        </div>         
        <input type="hidden" name="filter" value="yes">
        <input type="hidden" name="action" value="new_page">
        <input type="hidden" name="total_num" value="<?= $total_num?>">           
    </form>
    <!--filter end-->
    <script>
        (function(document) {
            //表格排序
            $("#abnormalD").tablesorter();            
        })(document);
    </script>  
    <!-- footer網頁尾頁 -->
    <footer>
        <div id="footer"></div>
    </footer>
</body>
</html>