<?php 
include("php/CMUHconndata.php");
include("php/fun.php");
//include("page_1.php");
include("page_searchfilter.php");
session_start();
    $checkuser=$_SESSION["login_member"];
    $checkuserID=sql_database('e_number','FA.Employee','cname',$checkuser);
    $str_member="SELECT * FROM FA.Employee WHERE e_number='$checkuserID'";
    $member=$pdo->query($str_member)->fetch();
    
    //-----判斷登錄者的權限--------
    $securityNoStr="SELECT e.sid,e.e_number,k.sysID FROM FA.securityemp as e LEFT JOIN FA.securityKind as k on e.sid=k.id  WHERE e.e_number='$checkuserID'";// AND k.sysID='$sysNo'";
    $securityNo=$pdo->Query($securityNoStr)->fetch();
    if (isset($securityNo) and $securityNo!='') {
        $sNumber=$securityNo['sid'];//權限區域
        if ($sNumber>4 and $sNumber<9) {
            $checksum=1;//可簽核-身分主管
            $condition="WHERE (e.e_number='$checkuserID' and check_number=1) and(";                       
        } 
        if($sNumber>=1 and $sNumber<=4){
            $checksum=2;//可簽核-檢查者
            $condition="WHERE (e.e_number='$checkuserID') and(";// and (check_number=1) and (check_manager IS NULL or check_manager=0)';
        }        
    } else {
        $checksum=3;//只能看
    }
    /*
     * 理論上不會有$checksum=3的情況出現，因為在進這頁面的按鈕在之前的首頁index.html，
     * 我已經設定權限排除checksum=1與2之外的人無法按，所以不會有全縣相關以外的人進來此頁面
     */
    //------END---------
    

    $x=0;//判斷搜索條件

    //判斷是重新還是原本的搜索條件選下一頁
    @$key=$_GET["key"];
    @$key=str_replace("\"", "", $key);    
    if ($key=="next_page") {
        @$action=$key;
        //@$action=str_replace("\"", "", $action);
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
         header("Location: mtlistcheck.php");
    }
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
    if (isset($_POST["action"])&&($_POST["action"]=="check")) {
        $total_num=$_POST["total_num"];
        $checksum=$_POST["checksum"];
        for ($i=0; $i<$total_num ; $i++) { 
            $j=$i+1000;
            $k=$i+2000;                    
            $a=$i;
            if (isset($_POST["$j"])) {//檢查人確認
                $memberCheck=1;
            } else {
                $memberCheck=0;
            }
            if (isset($_POST["$a"])) {//主管確認
                $managerCheck=1;
            } else {
                $managerCheck=0;
            }
            if (isset($_POST["$k"])) {//id
                $rdID=$_POST["$k"];
            }else{
                break;
            }
            
            if ($checksum==1) {
                $sql="UPDATE FA.Water_System_Record_Master SET  check_manager=:check_manager,managerID=:managerID WHERE recordID=:ID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':check_manager',$managerCheck,PDO::PARAM_STR);
                $stmt->bindParam(':managerID',$checkuserID,PDO::PARAM_STR);
            } 
            if($checksum==2) {
                $sql="UPDATE FA.Water_System_Record_Master SET  check_number=:check_number,r_member=:r_member WHERE recordID=:ID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':check_number',$memberCheck,PDO::PARAM_STR);
                $stmt->bindParam(':r_member',$checkuserID,PDO::PARAM_STR);
            }
            $stmt->bindParam(':ID',$rdID,PDO::PARAM_INT);
            $stmt->execute();
            $j=$i+1000;
            $k=$i+2000;     
        }
        $pdo=null;
        header("Location:mtlistcheck.php");
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
    <!--新加入20190815表格排序-->
    <script src="./js/jquery.tablesorter.min.js" type="text/javascript"></script>    
    <!-- 連結自己的JS -->
    <script src="./js/main.js"></script>
    <title>抄表系統未簽核清單</title>
</head>
<body>
<!-- header網頁標題 -->
    <header>
       <div id="header"></div>
    </header>
    <form action="" method="post" name="checklistfilter">
    <section class="container-fluid">
    <h1 class="text-center">抄表系統未簽核清單</h1>
        <div class="d-flex ">
             <a href="mtlistcheck.php" type="button" id="backlistcheck" class="btn btn-primary mt-4 rounded d-block mr-3">清除條件</a>
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
                    $sqlstr_total="SELECT DISTINCT(a.recordID),a.b_number,a.rDate,a.r_member,a.managerID,a.check_number,a.check_manager,a.sysID,a.managerCheckdate,a.empCheckdate,a.status FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID LEFT JOIN FA.CheckStatus as p ON a.status=p.id left join FA.securityKind as k ON a.sysID=k.sysID left join FA.securityemp as e ON k.id=e.sid $condition b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%' or a.rDate like '%$keyword%' or p.checkName like '%$keyword%')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.recordID),a.b_number,a.rDate,a.r_member,a.managerID,a.check_number,a.check_manager,a.sysID,a.managerCheckdate,a.empCheckdate,a.status FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID LEFT JOIN FA.CheckStatus as p ON a.status=p.id left join FA.securityKind as k ON a.sysID=k.sysID left join FA.securityemp as e ON k.id=e.sid $condition b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%' or a.rDate like '%$keyword%' or p.checkName like '%$keyword%') ORDER BY recordID DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                   //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(recordID)) FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID LEFT JOIN FA.CheckStatus as p ON a.status=p.id left join FA.securityKind as k ON a.sysID=k.sysID left join FA.securityemp as e ON k.id=e.sid $condition b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%' or a.rDate like '%$keyword%' or p.checkName like '%$keyword%')";
                    break;
                case 2://只有日期區間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.recordID),a.b_number,a.rDate,a.r_member,a.managerID,a.check_number,a.check_manager,a.sysID,a.managerCheckdate,a.empCheckdate,a.status FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid   $condition rDate between '$start_date' and '$end_date')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.recordID),a.b_number,a.rDate,a.r_member,a.managerID,a.check_number,a.check_manager,a.sysID,a.managerCheckdate,a.empCheckdate,a.status FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid  $condition rDate between '$start_date' and '$end_date') ORDER BY recordID DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(recordID)) FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid   $condition rDate between '$start_date' and '$end_date' )";
                    break;
                case 3://只有開始時間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.recordID),a.b_number,a.rDate,a.r_member,a.managerID,a.check_number,a.check_manager,a.sysID,a.managerCheckdate,a.empCheckdate,a.status FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid   $condition rDate ='$start_date')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.recordID),a.b_number,a.rDate,a.r_member,a.managerID,a.check_number,a.check_manager,a.sysID,a.managerCheckdate,a.empCheckdate,a.status FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid  $condition rDate ='$start_date') ORDER BY recordID DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(recordID)) FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid   $condition rDate ='$start_date')";
                    break;
                case 4://只有結束時間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.recordID),a.b_number,a.rDate,a.r_member,a.managerID,a.check_number,a.check_manager,a.sysID,a.managerCheckdate,a.empCheckdate,a.status FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid   $condition rDate ='$end_date')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.recordID),a.b_number,a.rDate,a.r_member,a.managerID,a.check_number,a.check_manager,a.sysID,a.managerCheckdate,a.empCheckdate,a.status FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid  $condition rDate ='$end_date') ORDER BY recordID DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(recordID)) FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid $condition rDate ='$end_date')";
                    break;
                case 5://開始+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.recordID),a.b_number,a.rDate,a.r_member,a.managerID,a.check_number,a.check_manager,a.sysID,a.managerCheckdate,a.empCheckdate,a.status FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID LEFT JOIN FA.CheckStatus as p ON a.status=p.id  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid $condition rDate ='$start_date' and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%' or p.checkName like '%$keyword%'))";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.recordID),a.b_number,a.rDate,a.r_member,a.managerID,a.check_number,a.check_manager,a.sysID,a.managerCheckdate,a.empCheckdate,a.status FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID LEFT JOIN FA.CheckStatus as p ON a.status=p.id  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid $condition rDate ='$start_date' and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%' or p.checkName like '%$keyword%')) ORDER BY recordID DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(recordID)) FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID LEFT JOIN FA.CheckStatus as p ON a.status=p.id  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid $condition rDate ='$start_date' and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%' or p.checkName like '%$keyword%'))";
                    break;
                case 6://結束+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.recordID),a.b_number,a.rDate,a.r_member,a.managerID,a.check_number,a.check_manager,a.sysID,a.managerCheckdate,a.empCheckdate,a.status FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID LEFT JOIN FA.CheckStatus as p ON a.status=p.id  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid $condition rDate ='$end_date' and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%' or p.checkName like '%$keyword%'))";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.recordID),a.b_number,a.rDate,a.r_member,a.managerID,a.check_number,a.check_manager,a.sysID,a.managerCheckdate,a.empCheckdate,a.status FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID LEFT JOIN FA.CheckStatus as p ON a.status=p.id  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid $condition rDate ='$end_date' and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%' or p.checkName like '%$keyword%')) ORDER BY recordID DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(recordID)) FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID LEFT JOIN FA.CheckStatus as p ON a.status=p.id  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid $condition rDate ='$end_date' and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%' or p.checkName like '%$keyword%'))";
                    break;
                case 7://結束+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.recordID),a.b_number,a.rDate,a.r_member,a.managerID,a.check_number,a.check_manager,a.sysID,a.managerCheckdate,a.empCheckdate,a.status FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID LEFT JOIN FA.CheckStatus as p ON a.status=p.id  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid $condition (rDate between '$start_date' and '$end_date')and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%' or p.checkName like '%$keyword%'))";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.recordID),a.b_number,a.rDate,a.r_member,a.managerID,a.check_number,a.check_manager,a.sysID,a.managerCheckdate,a.empCheckdate,a.status FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID LEFT JOIN FA.CheckStatus as p ON a.status=p.id  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid $condition (rDate between '$start_date' and '$end_date')and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%' or p.checkName like '%$keyword%')) ORDER BY recordID DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(recordID)) FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID LEFT JOIN FA.CheckStatus as p ON a.status=p.id  left join FA.securityKind as k ON  a.sysID=k.sysID  left join FA.securityemp as e ON k.id=e.sid $condition (rDate between '$start_date' and '$end_date')and (b.cname like '%$keyword%' or c.B_name like '%$keyword%' or s.sysName like '%$keyword%' or p.checkName like '%$keyword%'))";
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
            echo '<th class="th-sm">項  目</th>';
            echo '<th class="th-sm">主  管</th>';
            echo '<th class="th-sm">檢查人</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $mgcheck="mgcheck";
            $mbcheck="mbcheck";
            while ($data_page = $sql_page->fetch()) {
                $a=$i;
                echo '<tr>';
                $buildName=sql_database('B_name','FA.Building','b_number',$data_page['b_number']);
                $sysName=sql_database('sysName','FA.Equipment_System_Group','sysID',$data_page['sysID']);
                    echo "<td width='80%'><a href='mtchecktable.php?id=\"".$data_page['recordID']."\"&build=\"".$data_page['b_number']."\"&r_date=\"".$data_page['rDate']."\"&member=\"".$data_page['r_member']."\"&manage=\"".$data_page['managerID']."\"&checkMember=\"".$data_page['check_number']."\"&checkManager=\"".$data_page['check_manager']."\"&sysID=\"".$data_page['sysID']."\"' class=\".list-group-item list-group-item-action.\">".$buildName."-".$sysName."-".$data_page['rDate'].'</a></td>';
                    echo '<td width="10%" align="center">';
                    if ($data_page['check_manager']==1) {
                        echo "<input type='checkbox'  name=\"".$a."\" value=\"".$mgcheck."\" checked disabled>";
                    } else {
                        echo "<input type='checkbox' class='managerCheck' name=\"".$a."\" value=\"".$mgcheck."\" disabled>";
                    }   
                    echo '</td>';
                    echo '<td width="10%" align="center">';
                    if ($data_page['check_number']==1) {
                        echo "<input type='checkbox'  class='employeeCheck' name=\"".$j."\" value=\"".$mbcheck."\" checked disabled>";
                    } else {
                        echo "<input type='checkbox' class='employeeCheck' name=\"".$j."\" value=\"".$mbcheck."\" disabled>";
                    }  
                    echo '</td>';
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
                        echo "<td><a href='mtcheckfilter.php?page=1\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".$keyword."\"&key=next_page'>"."[第一頁]".'</a></td>';
                        echo "<td><a href='mtcheckfilter.php?page={$prev}\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".$keyword."\"&key=next_page'>"."[<<<上一頁]".'</a></td>';
                        
                    }
                    if ($page_num<$total_page) {
                        @$next=$page_num+1;
                        echo "<td>"."<a href='mtcheckfilter.php?page={$next}\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".$keyword."\"&key=next_page'>"."[下一頁>>>]".'</a></td>';
                        echo "<td><a href='mtcheckfilter.php?page=$total_page\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".$keyword."\"&key=next_page'>".'[最末頁]'.'</a></td>';
                    }
                echo '</tr>';
            echo '</table>';
            
            //分頁按鈕一次七頁
            $phpfile = 'mtcheckfilter.php';
            $page= isset($_GET['page'])?$_GET['page']:1;        
            $getpageinfo = page($page,$total_num,$phpfile,$start_date,$end_date,$keyword,'next_page');

            echo '<div align="center">'; 
                echo @$getpageinfo['pagecode'];//顯示分頁的html語法
            echo '</div>';
            //分頁按鈕end
        ?>
        </div>
        <input type="hidden" name="action" value="new_page">
        <input type="hidden" name="total_num" value="<?= $total_num?>">
        <input type="hidden" name="action" value="check">
        <input type="hidden" name="checksum" value="<?= $checksum ?>">
        <!-- 送出鈕 -->
        <div class="d-flex justify-content-end">
            <div>
                <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit">送出</button>&nbsp&nbsp                            
            </div>
            <div>
                <a href='index.html' type='button' class="my-3 px-3 py-1 btn-outline-info text-dark">離開</a>
            </div>
        </div>
    </section>    
    </form>
    <form action="mtcheckfilter.php" method="post" name="mtlist"> 
        <div>
            <div align="left">
                日期區間搜索：</br>
                開始時間：<input type="date" name="start_date">&nbsp&nbsp結束時間：<input type="date" name="end_date">            
            </div>
            <div align="left">
                </br>關鍵字：<input type="text" name="keywordsearch">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="submit" value="查詢">
            </div>           
        </div> 
        <input type="hidden" name="action" value="new_page">           
    </form>
    <script>
        (function(document) {
            //表格排序
            $("#checklist").tablesorter();            
        })(document);
    </script> 
    <!-- footer網頁尾頁 -->
    <footer>
        <div id="footer"></div>
    </footer>
    
</body>
</html>
