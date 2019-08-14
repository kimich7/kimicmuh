<?php //抄表的查詢功能
    include("php/CMUHconndata.php");
    include("php/fun.php");
    //include("page_1.php");
    include("page_searchfilter.php");
    session_start();
    $checkuser=$_SESSION["login_member"];
    $checkuserID=sql_database('e_number','FA.Employee','cname',$checkuser);
    $str_member="SELECT * FROM FA.Employee WHERE e_number='$checkuserID'";
    $member=$pdo->query($str_member)->fetch(); 
    $x=0;//判斷搜索條件

    //判斷是重新還是原本的搜索條件選下一頁
    @$action=$_GET["key"];
    @$action=str_replace("\"", "", $action);
    if ($action=='next_page') {
        @$start_date=$_GET["startdate"];
        @$start_date=str_replace("\"", "", $start_date);
        @$end_date=$_GET["enddate"];
        @$end_date=str_replace("\"", "", $end_date);
        @$keyword=$_GET["keyword"];
        @$keyword=str_replace("\"", "", $keyword);
    }
    if (isset($_POST["action"])&&($_POST["action"]=="new_page")){//重新搜索
        @$start_date=$_POST["start_date"];
        @$end_date=$_POST["end_date"];
        @$keyword=$_POST["keywordsearch"];  
    }    
    
    if ((isset($_POST["action"])&&($_POST["action"]=="new_page"))&&((!isset($start_date) or $start_date=='') && (!isset($end_date)or $end_date=='')&& (!isset($keyword) or $keyword==''))) {
         header("Location: mtlistsearch.php");
    } //else{        
    if ((!isset($start_date) or $start_date=='') && (!isset($end_date)or $end_date=='') && (isset($keyword)&&$keyword !='' )) {
        $x=1;//只有keyword沒有日期區間            
    }
    if ((isset($start_date) && $start_date!='') && (isset($end_date)&& $end_date!='') && (!isset($keyword) or $keyword =='' )) {
        $x=2;//只有日期區間沒有keyword
    }
    if ((isset($start_date) && $start_date!='')&&(!isset($end_date)or $end_date=='')&& (!isset($keyword) or $keyword=='')) {
        $x=3;//只有開始時間
    }
    if ((!isset($start_date) or $start_date=='') && (isset($end_date)&& $end_date!='') && (!isset($keyword) or $keyword =='' )) {
        $x=4;//只有結束時間
    }
    if ((isset($start_date) && $start_date!='')&&(!isset($end_date)or $end_date=='')&&(isset($keyword)&&$keyword !='' )) {
        $x=5;//只有開始時間+keyword
    }
    if ((!isset($start_date) or $start_date=='')&&(isset($end_date)&& $end_date!='')&&(isset($keyword)&&$keyword !='' )) {
        $x=6;//只有結束時間+keyword
    }
    if ((isset($start_date) && $start_date!='')&&(isset($end_date)&& $end_date!='')&&(isset($keyword)&&$keyword !='' )) {
        $x=7;//全都有
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
    <script src="./js/jquery.tablesorter.min.js" type="text/javascript"></script>
    
    <!-- 連結自己的JS -->
    <script src="./js/main.js"></script>
    <title>抄表系統查詢清單</title>
</head>

<body>
<form action="" method="post" name="mtlistfitle">
    <!-- header網頁標題 -->
    <header>
       <div id="header"></div>
    </header>    
    <!-- 未簽核清單 -->
    <section class="container-fluid">
        <h1 class="text-center">抄表系統查詢清單</h1>
         <div class="d-flex ">
             <a href="mtlistsearch.php" type="button" id="backlist" class="btn btn-primary mt-4 rounded d-block mr-3">清除條件</a>
            <!-- <p class="d-inline font-weight-bold">Search:&nbsp&nbsp<input type="search" class="light-table-filter" data-table="order-table" placeholder="請輸入關鍵字"></p> -->
        </div> 
        <div class="list-group mx-5 my-5">            
            <?php
            $pageRow_record=10;//每頁的筆數
            $page_num=1;//預設的頁數
            //更新$page_num        
            if (isset($_GET['page'])) {
                $page_num=$_GET['page'];
            }
            @$startRow_record=($page_num-1)*$pageRow_record;
            //篩選條件
            switch ($x) {
                case 1://只有keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  WHERE b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%' or a.rDate like '%$keyword%'";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID WHERE b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%'  or a.rDate like '%$keyword%' ORDER BY recordID ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(recordID) FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  WHERE b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%' or a.rDate like '%$keyword%' ";
                    break;
                case 2://只有日期區間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  WHERE rDate between '$start_date' and '$end_date'";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID WHERE rDate between '$start_date' and '$end_date' ORDER BY recordID ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(recordID) FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  WHERE rDate between '$start_date' and '$end_date' ";
                    break;
                case 3://只有開始時間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  WHERE rDate ='$start_date'";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID WHERE rDate ='$start_date' ORDER BY recordID ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(recordID) FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  WHERE rDate ='$start_date'";
                    break;
                case 4://只有結束時間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  WHERE rDate ='$end_date'";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID WHERE rDate ='$end_date' ORDER BY recordID ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(recordID) FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  WHERE rDate ='$end_date'";
                    break;
                case 5://開始+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  WHERE rDate ='$start_date' and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID WHERE rDate ='$start_date' and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%') ORDER BY recordID ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(recordID) FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  WHERE rDate ='$start_date' and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%')";
                    break;
                case 6://結束+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  WHERE rDate ='$end_date' and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID WHERE rDate ='$end_date' and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%') ORDER BY recordID ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(recordID) FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  WHERE rDate ='$end_date' and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%')";
                    break;
                case 7://結束+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  WHERE (rDate between '$start_date' and '$end_date')and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID WHERE (rDate between '$start_date' and '$end_date')and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%') ORDER BY recordID ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(recordID) FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  WHERE (rDate between '$start_date' and '$end_date')and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%')";
                    break;
                default:
                    # code...
                    break;
            }

            
            $sql_page=$pdo->query($sqlstr_page);
            $sql_total=$pdo->query($sqlstr_total);
            $total_num=CURRENT($pdo->query($totalstr_num)->fetch());
            
            //本業開始的筆數
            $i=0;
            $j=$i+1000;
            $k=$i+2000;
            $a=0;
            echo '<table id="searchlist" class="display tablesorter table table-striped table-bordered table-hover col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12 table-sm order-table" align="center" width="80%">';
            echo '<thead class="thead-light" >';
            echo '<tr align="center">';
            echo '<th class="th-sm">名   稱</th>';
            echo '<th class="th-sm">抄表日期</th>';
            echo '<th class="th-sm">狀   態</th>';
            echo '<th class="th-sm">檢 查 人</th>';
            echo '<th class="th-sm">主   管</th>';
            echo '</tr class="th-sm">';
            echo '</thead>';
            echo '<tbody>';
            $mgcheck="mgcheck";
            $mbcheck="mbcheck";
            while ($data_page = $sql_page->fetch()) {
                $a=$i;
                echo '<tr>';
                $buildName=sql_database('B_name','FA.Building','b_number',$data_page['b_number']);
                $sysName=sql_database('sysName','FA.Equipment_System_Group','sysID',$data_page['sysID']);
                    echo "<td width='50%'><a href='mtdetail.php?id=\"".$data_page['recordID']."\"&build=\"".$data_page['b_number']."\"&r_date=\"".$data_page['rDate']."\"&member=\"".$data_page['r_member']."\"&manage=\"".$data_page['managerID']."\"&checkMember=\"".$data_page['check_number']."\"&checkManager=\"".$data_page['check_manager']."\"&sysID=\"".$data_page['sysID']."\"' class=\".list-group-item list-group-item-action.\">".$buildName."-".$sysName."-".$data_page['rDate'].'</a></td>';
                    echo '<td width="10%" align="center">'.$data_page['rDate'].'</td>';
                    echo '<td width="10%" align="center">';
                    if ($data_page['check_manager']==1 and $data_page['check_number']==1) {
                        $status='審核完成';                    
                    } elseif($data_page['check_manager']==0 and $data_page['check_number']==1) {
                        $status='主管未審核';                    
                    } elseif($data_page['check_manager']==0 and $data_page['check_number']==0) {
                        $status='檢查者未審核';
                    }
                    echo $status;   
                    echo '</td>';
                    $r_member=sql_database('cname','FA.Employee','e_number',$data_page['r_member']);
                    $manager=sql_database('cname','FA.Employee','e_number',$data_page['managerID']);
                    echo '<td width="10%" align="center">'.$r_member.'</td>';
                    echo '<td width="10%" align="center">'.$manager.'</td>';
                    $q=$data_page['recordID'];
                    echo "<input type='hidden' name=\"".$k."\" value=\"".$q."\">";
                $i++;
                $j++;
                $k++;
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
                        
            //計算總頁數
            $total_page=ceil($total_num/$pageRow_record);
            echo '<table border="0" align="center">';    
                echo '<tr>';
                    echo '<td><h5>'.'未簽核清單共計'.$total_num.'筆(共'.$total_page.'頁)'.'</h5></td>';
                echo '</tr>';
            echo '</table>';
            echo '<table border="0" align="center">';
                echo '<tr>';
                    if ($page_num>1) {
                        @$prev=$page_num-1;
                        echo "<td><a href='mtlistfilter.php?page=1\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".$keyword."\"&key=next_page'>"."[第一頁]".'</a></td>';
                        echo "<td><a href='mtlistfilter.php?page={$prev}\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".$keyword."\"&key=next_page'>"."[<<<上一頁]".'</a></td>';
                        
                    }
                    if ($page_num<$total_page) {
                        @$next=$page_num+1;
                        echo "<td>"."<a href='mtlistfilter.php?page={$next}\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".$keyword."\"&key=next_page'>"."[下一頁>>>]".'</a></td>';
                        echo "<td><a href='mtlistfilter.php?page=$total_page\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".$keyword."\"&key=next_page'>".'[最末頁]'.'</a></td>';
                    }
                echo '</tr>';
            echo '</table>';
            
            //分頁按鈕一次七頁
            $phpfile = 'mtlistfilter.php';
            $page= isset($_GET['page'])?$_GET['page']:1;        
            $getpageinfo = page($page,$total_num,$phpfile,$start_date,$end_date,$keyword,'next_page');

            echo '<div align="center">'; 
                echo @$getpageinfo['pagecode'];//顯示分頁的html語法
            echo '</div>';
            //分頁按鈕end
            //------------帶到下一頁的資料-----------------            
            echo "<input type='hidden' name='action' value='next_page'>";
            echo "<input type='hidden' name='startDate' value='$start_date'>";
            echo "<input type='hidden' name='endDate' value='$end_date'>";
            echo "<input type='hidden' name='keyword' value='$keyword'>";
            //-----------帶到下一頁的資料完-------------------
            ?>

        </div>
</form>
        <form action="mtlistfilter.php" method="post" name="mtlist"> 
            <div>
                <div align="left">
                    日期區間搜索：</br>
                    開始時間：<input type="date" name="start_date">&nbsp&nbsp結束時間：<input type="date" name="end_date">            
            </div>
            <div>
                <div align="left">
                    </br>關鍵字：<input type="text" name="keywordsearch">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="submit" value="查詢">
                </div>           
            </div>
        </form>
        <input type="hidden" name="total_num" value="<?= $total_num?>">
        <input type="hidden" name="action" value="new_page">  
    <?php //}?>          
    </section>    
    <script>
        (function(document) {
            //表格排序
            $("#searchlist").tablesorter();
        //     'use strict';
        //     // 建立 LightTableFilter
        //     var LightTableFilter = (function(Arr) {
        //         var _input;
        //         // 資料輸入事件處理函數
        //         function _onInputEvent(e) {
        //             _input = e.target;
        //             var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
        //             Arr.forEach.call(tables, function(table) {
        //                 Arr.forEach.call(table.tBodies, function(tbody) {
        //                 Arr.forEach.call(tbody.rows, _filter);
        //                 });
        //             });
        //         }
        //         // 資料篩選函數，顯示包含關鍵字的列，其餘隱藏
        //         function _filter(row) {
        //             var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
        //             row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
        //         }
        //         return {
        //         // 初始化函數
        //             init: function() {
        //                 var inputs = document.getElementsByClassName('light-table-filter');
        //                 Arr.forEach.call(inputs, function(input) {
        //                 input.oninput = _onInputEvent;
        //                 });
        //             }
        //         };
        //     })(Array.prototype);
        //     // 網頁載入完成後，啟動 LightTableFilter
        //     document.addEventListener('readystatechange', function() {
        //         if (document.readyState === 'complete') {
        //             LightTableFilter.init();
        //         }
        //     });
        })(document);
    </script>    
    <!-- footer網頁尾頁 -->
    <footer>
        <div id="footer"></div>
    </footer>        
</body>
</html>