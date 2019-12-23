<?php
/**本頁是異常事件待審核清單**/
    include("php/CMUHconndata.php");
    include("php/fun.php");
    include("php/errfun.php");
    include("page_err_app.php");
    $filter=$_GET['filter'];
    session_start();
    $userID=$_SESSION["login_number"];

    if (isset($_POST["action"])&&($_POST["action"]=="check")) {
         $total_num=$_POST["total_num"];
         $confirmDate=date("Y-m-d");
        for ($i=0; $i<$total_num ; $i++) { 
            $j=$i+1000;
            $k=$i+2000;                    
            $a=$i;
            if (isset($_POST["$j"])) {
                $managerCheck=True;
            } else {
                $managerCheck=NULL;
            }            
            if (isset($_POST["$k"])) {
                $rdID=$_POST["$k"];
            }else{
                break;
            }            
            $sql="UPDATE FA.Abnormal_Notification_System_Master SET  manage_status=:manage_status,confirm_date=:confirm_date WHERE case_id=:ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':manage_status',$managerCheck,PDO::PARAM_STR);
            $stmt->bindParam(':confirm_date',$confirmDate,PDO::PARAM_STR);            
            $stmt->bindParam(':ID',$rdID,PDO::PARAM_INT);
            $stmt->execute();
            $j=$i+1000;
            $k=$i+2000;     
        }
        $pdo=null;
        header("Location:index.html");
    }
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
    <!-- 連結自己的JS -->
    <script src="./js/main.js"></script>
    <title>異常事件清單總覽</title>
</head>
<body>
    <!-- header網頁標題 -->
    <header>
       <div id="header"></div>
    </header>
    <form action="" method="post" name="abnormallist">
    <section class="container-fluid">
        <h1 class="text-center">異常事件待審核清單</h1>
        <?php
        $href="abnormalOverview.php?filter=no";
        if ($filter=='yes') {?>
         <div class="d-flex ">        
            <a href='abnormalOverview.php?filter=no' type="button" id="backlistmmt" class="btn btn-primary mt-4 rounded d-block mr-3">清除條件</a>
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
        $sqlstr_total="SELECT DISTINCT(a.case_id),a.case_time,a.case_location,a.case_title,a.case_userID,a.work_emp,a.manage_status,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master AS a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id";
        //篩選後給每頁的筆數
        $sqlstr_page="SELECT  DISTINCT(a.case_id),a.case_time,a.case_location,a.case_title,a.case_userID,a.work_emp,a.manage_status,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master AS a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id ORDER BY a.case_id DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
        //總資料數量
        $totalstr_num="SELECT COUNT(DISTINCT(a.case_id)) FROM FA.Abnormal_Notification_System_Master AS a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id";        
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
                    $sqlstr_total="SELECT DISTINCT(a.case_id), a.*,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where ( c.cname like '%$keyword%' or a.case_title like '%$keyword%' or a.case_location like '%$keyword%' or a.case_time like '%$keyword%' or a.confirm_date like '%$keyword%')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.case_id), a.*,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where ( c.cname like '%$keyword%' or a.case_title like '%$keyword%' or a.case_location like '%$keyword%' or a.case_time like '%$keyword%' or a.confirm_date like '%$keyword%') ORDER BY a.case_time DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                   //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.case_id)) FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where ( c.cname like '%$keyword%' or a.case_title like '%$keyword%' or a.case_location like '%$keyword%' or a.case_time like '%$keyword%' or a.confirm_date like '%$keyword%')";
                    break;
                case 2://只有日期區間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.case_id), a.*,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where (a.case_time between '$start_date' and '$end_date' or a.confirm_date between '$start_date' and '$end_date')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.case_id), a.*,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where (a.case_time between '$start_date' and '$end_date' or a.confirm_date between '$start_date' and '$end_date') ORDER BY a.case_time DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.case_id)) FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where (a.case_time between '$start_date' and '$end_date' or a.confirm_date between '$start_date' and '$end_date')";
                    break;
                case 3://只有開始時間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.case_id),a.*,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number  where (a.case_time ='$start_date' or a.confirm_date ='$start_date')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.case_id), a.*,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where (a.case_time ='$start_date' or a.confirm_date ='$start_date') ORDER BY a.case_time DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.case_id)) FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where (a.case_time ='$start_date' or a.confirm_date ='$start_date')";
                    break;
                case 4://只有結束時間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.case_id), a.*,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where (a.case_time ='$end_date' or a.confirm_date ='$end_date')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.case_id), a.*,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where (a.case_time ='$end_date' or a.confirm_date ='$end_date') ORDER BY a.case_time DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.case_id)) FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where (a.case_time ='$end_date' or a.confirm_date ='$end_date')";
                    break;
                case 5://開始+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.case_id), a.*,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where ((a.case_time ='$start_date'or a.confirm_date= '$start_date') and ( c.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%'))";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.case_id), a.*,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where ((a.case_time ='$start_date'or a.confirm_date = '$start_date') and ( c.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%')) ORDER BY a.case_time DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.case_id)) FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where ((a.case_time ='$start_date'or a.confirm_date = '$start_date') and ( c.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%'))";
                    break;
                case 6://結束+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.case_id), a.*,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where ((a.case_time='$end_date'or a.confirm_date = '$end_date')  and ( c.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%'))";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.case_id), a.*,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where ((a.case_time='$end_date'or a.confirm_date = '$end_date')  and ( c.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%')) ORDER BY a.case_time DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.case_id)) FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where ((a.case_time='$end_date'or a.confirm_date = '$end_date')  and ( c.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%'))";
                    break;
                case 7://日期區間+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.case_id), a.*,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where ( (a.case_time between '$start_date' and '$end_date' or a.confirm_date between '$start_date' and '$end_date')and (c.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%'))";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.case_id), a.*,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where ( (a.case_time between '$start_date' and '$end_date' or a.confirm_date between '$start_date' and '$end_date')and (c.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%')) ORDER BY a.case_time DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.case_id)) FROM FA.Abnormal_Notification_System_Master as a LEFT JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id LEFT JOIN FA.Employee as c on a.case_userID=c.e_number or a.case_manageID=c.e_number or a.work_emp=c.e_number where ( (a.case_time between '$start_date' and '$end_date' or a.confirm_date between '$start_date' and '$end_date')and (c.cname like '%$keyword%' or a.case_title like '%$keyword%'  or a.case_location like '%$keyword%'))";
                    break;                
            }
        }

        $sql_page=$pdo->query($sqlstr_page);
        $sql_total=$pdo->query($sqlstr_total);
        $total_num=CURRENT($pdo->query($totalstr_num)->fetch());
        $pdo=null;
        //本頁開始的筆數
        $i=0;  
        $j=$i+1000;      
        $k=$i+2000;
        echo '<table id="abnormaCRlD" class="display table table-striped table-bordered table-hover col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12 table-sm order-table"  aria-describedby="dataTables-example_info" data-sort-name="tid" data-sort-order="desc" data-sortable ="true">';
        echo '<thead class="thead-light">';
        echo '<tr align="center">';
        echo '<th scope="col" width="10%" name="tid" sortable="true">發現日期</th>';
        echo '<th scope="col" width="15%">發現地點</th>';
        echo '<th scope="col" width="36%">標   題</th>';
        echo '<th scope="col" width="7%">發現人員</th>';
        echo '<th scope="col" width="7%">指派人員</th>';
        echo '<th scope="col" width="10%">指派日期</th>';
        echo '<th scope="col" width="10%">回報完成日期</th>';
        echo '<th scope="col" width="5%">狀態</th>';
        echo '</tr>';     
        echo '</thead>';
        echo '<tbody>';
        $mgcheck="mgcheck";
        while ($data_page = $sql_page->fetch()) {
            $desemp=$i;
            $case_id=$data_page["case_id"];
            $case_date=$data_page["case_time"];//發現日期
            $case_Location=$data_page["case_location"];//發生地點
            $case_title=$data_page["case_title"];//標題            
            $csaeFindEmpID=$data_page["case_userID"];//發現人員
            $userDesID=$data_page["work_emp"];//指派人員
            $DesDate=$data_page["Detail_Start_Time"];//指派日期
            $EndDate=$data_page["Detail_End_Time"];//完成日期
            $detail=$data_page["Detail_id"];//明細表id
            $status=$data_page["manage_status"];//狀態
            $userFindName=sql_database('cname','FA.Employee','e_number',$csaeFindEmpID);//發現人員
            $userDesName=sql_database('cname','FA.Employee','e_number',$userDesID);//被指派人員
            echo '<tr align="center">';            
                //顯示發現日期
                echo '<th scope="row" align="center">'.$case_date.'</th>';
                //顯示發現地點
                echo '<td align="center">';
                    echo $case_Location;
                echo '</td>';
                //顯示事件名稱，且需要的話可以點進去看詳細情形
                echo "<td align='center'><a href='abnormalDesTable.php?id=\"".$case_id."\"&detailID=\"".$detail."\"' class=\".list-group-item list-group-item-action.\">".$case_title.'</a></td>';
                //顯示發現異常事件人員
                echo '<td align="center">';
                    echo $userFindName;
                echo '</td>';
                //顯示發現被指派人員
                echo '<td align="center">';
                    echo $userDesName;
                echo '</td>';
                //顯示指派日期
                echo '<td align="center">';
                    echo $DesDate;
                echo '</td>';
                //顯示完成日期
                echo '<td align="center">';
                    echo $EndDate;
                echo '</td>';
                //審核欄位
                switch ($status) {
                    case 'W':
                        $statustext='未審核';
                        break;
                    case '':
                        $statustext='未審核';
                        break;
                    case NULL:
                        $statustext='未審核';
                        break;
                    case 'M':
                        if($EndDate){
                            $statustext='待審核';
                        }else{
                            $statustext='進行中';
                        }
                        
                        break;
                    case 'F':
                        $statustext='審核完成';
                        break;                    
                }               
                if ($statustext=='待審核') {
                    echo '<td>';
                    echo '<span class="billBoarderrL3" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="請登入相應權限帳號已解鎖">';
                    echo "<a href='abnormalCaseReviewTable.php?id=\"".$case_id."\"&detailID=\"".$detail."\"' class='airconL3' Disabled>".$statustext.'</a>';
                    echo '</span>';
                    echo '</td>';
                } elseif(($statustext=='待審核')and(is_null($EndDate)or$EndDate=='')){                    
                    echo '<td>'.$statustext.'</td>';
                }else{ 
                    echo '<td>'.$statustext.'</td>';
                }
                $q=$case_id;
                echo "<input type='hidden' name=\"".$k."\" value=\"".$q."\">";//傳遞case_id
            $i++;
            $j++;
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
                echo '<td><h5>'.'尚未審核的異常處理事件共'.$total_num.'筆(共'.$total_page.'頁)'.'</h5></td>';
            echo '</tr>';
        echo '</table>';
        echo '<table border="0" align="center">';
            echo '<tr>';
                if ($page_num>1) {
                    $prev=$page_num-1;
                    echo '<td><a href="abnormalOverview.php?filter=no&page=1">'."[第一頁]".'</a></td>';
                    echo "<td><a href=\"abnormalOverview.php?page={$prev}\">"."[<<<上一頁]".'</a></td>';
                }
                if ($page_num<$total_page) {
                    $next=$page_num+1;
                    echo "<td>"."<a href=\"abnormalOverview.php?filter=no&page={$next}\">"."[下一頁>>>]".'</a></td>';
                    echo "<td><a href=\"abnormalOverview.php?filter=no&page=$total_page\">".'[最末頁]'.'</a></td>';
                }
            echo '</tr>';
        echo '</table>';
         //分頁按鈕一次七頁
            $phpfile = 'abnormalOverview.php';
            $page= isset($_GET['page'])?$_GET['page']:1;        
            $getpageinfo = page($page,$total_num,$phpfile);
            echo '<div align="center">'; 
            echo $getpageinfo['pagecode'];//顯示分頁的html語法
            echo '</div>';
        } 
        
        if ($filter=='yes') {
            echo '<table border="0" align="center">';    
            echo '<tr>';
                echo '<td><h5>'.'尚未審核的異常處理事件共'.$total_num.'筆(共'.$total_page.'頁)'.'</h5></td>';
            echo '</tr>';
        echo '</table>';
        echo '<table border="0" align="center">';
            echo '<tr>';
                if ($page_num>1) {
                    $prev=$page_num-1;
                    echo "<td><a href='abnormalOverview.php?filter=yes&page=1\"&start_date=\"".$start_date."\"&end_date=\"".$end_date."\"&keywordsearch=\"".$keyword."\"&action=next_page'>"."[第一頁]".'</a></td>';
                    echo "<td><a href='abnormalOverview.php?filter=yes&page={$prev}\"&start_date=\"".$start_date."\"&end_date=\"".$end_date."\"&keywordsearch=\"".$keyword."\"&action=next_page'>"."[<<<上一頁]".'</a></td>';
                    
                }
                if ($page_num<$total_page) {
                    $next=$page_num+1;
                    echo "<td>"."<a href='abnormalOverview.php?filter=yes&page={$next}\"&start_date=\"".$start_date."\"&end_date=\"".$end_date."\"&keywordsearch=\"".$keyword."\"&action=next_page'>"."[下一頁>>>]".'</a></td>';
                    echo "<td><a href='abnormalOverview.php?filter=yes&page=$total_page\"&start_date=\"".$start_date."\"&end_date=\"".$end_date."\"&keywordsearch=\"".$keyword."\"&action=next_page'>".'[最末頁]'.'</a></td>';
                    
                }
            echo '</tr>';
        echo '</table>';
         //分頁按鈕一次七頁
            $phpfile = 'abnormalOverview.php';
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
        <!-- 送出鈕 -->
        <div class="d-flex justify-content-end">            
            <a type="button" class="text-dark my-3 px-3 py-1 btn-outline-info" href="index.html">返回</a>
        </div>
    </section>
    </form>
    <!--filter-->
    <form action="abnormalOverview.php" method="get" name="mtlist"> 
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
            $("#abnormaCRlD").tablesorter();            
        })(document);
    </script>  
    <!-- footer網頁尾頁 -->
    <footer>
        <div id="footer"></div>
    </footer>
</body>
</html>