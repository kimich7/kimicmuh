<?php 
include("php/CMUHconndata.php");
include("php/fun.php");
//include("page_1.php");
include("page_searchfilter_mmt.php");
session_start();
    $checkuser=$_SESSION["login_member"];
    $checkuserID=sql_database('e_number','FA.Employee','cname',$checkuser);
    $str_member="SELECT * FROM FA.Employee WHERE e_number='$checkuserID'";
    $member=$pdo->query($str_member)->fetch();
    @$sdid=(int)$_POST["sdid"];    
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
    @$action=$_GET["key"];
    @$action=str_replace("\"", "", $action);
    if ($action=='next_page') {
        @$start_date=$_GET["startdate"];
        @$start_date=str_replace("\"", "", $start_date);
        @$end_date=$_GET["enddate"];
        @$end_date=str_replace("\"", "", $end_date);
        @$keyword=$_GET["keyword"];
        @$keyword=str_replace("\"", "", $keyword);
        @$sdid=$_GET["sdid"]; 
        @$sdid=str_replace("\"", "", $sdid);
    }
    if (isset($_POST["action"])&&($_POST["action"]=="new_page")){//重新搜索
        @$start_date=$_POST["start_date"];
        @$end_date=$_POST["end_date"];
        @$keyword=$_POST["keywordsearch"];
        @$sdid=$_POST["sdid"]; 
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
        $sdid=$_POST['sdid'];
        // $checksum=$_POST["checksum"];
        for ($i=0; $i<$total_num ; $i++) {             
            $k=$i+2000;                    
            $a=$i;
            if (isset($_POST["$k"])&&$_POST["$k"]!=""){//id
                $rdID=$_POST["$k"];
                if(isset($_POST["$a"])&&$_POST["$a"]!=""){                    
                    $checkempId=$_POST["$a"];
                    $checkDate=date("Y-m-d");
                    $status='F';
                }else{
                    $checkempId=NULL;
                    $checkDate=NULL;
                    $status='W';
                }                
                $sql="UPDATE FA.MMA_getFile SET  checkempId=:checkempId,checkdate=:checkdate,status=:status WHERE fileNo=:ID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':checkempId',$checkempId,PDO::PARAM_STR);
                $stmt->bindParam(':checkdate',$checkDate,PDO::PARAM_STR);
                $stmt->bindParam(':status',$status,PDO::PARAM_STR);
                $stmt->bindParam(':ID',$rdID,PDO::PARAM_STR);
                $stmt->execute();
            }else{
                break;
            }    
        }
        $pdo=null;
        switch ($sdid) {
        case 1:
            header("Location: mmt_list_filepage.php?sdid=1");
            break;
        case 2:
            header("Location: mmt_list_filepage.php?sdid=2");
            break;
        case 3:
            header("Location: mmt_list_filepage.php?sdid=3");
            break;
        case 4:
            header("Location: mmt_list_filepage.php?sdid=4");
            break;
        case 5:
            header("Location: mmt_list_filepage.php?sdid=5");
            break;
        case 6:
            header("Location: mmt_list_filepage.php?sdid=6");
            break;
        case 7:
            header("Location: mmt_list_filepage.php?sdid=7");
            break;
        case 8:
            header("Location: mmt_list_filepage.php?sdid=8");
            break;
        case 9:
            header("Location: mmt_list_filepage.php?sdid=9");
            break;
        case 10:
            header("Location: mmt_list_filepage.php?sdid=10");
            break;
        case 11:
            header("Location: mmt_list_filepage.php?sdid=11");
            break;
        case 12:
            header("Location: mmt_list_filepage.php?sdid=12");
            break;
        case 13:
            header("Location: mmt_list_filepage.php?sdid=13");
            break;
        
    }
        // header("Location:mmt_list_b_other.php");
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
    <title>建築-其他清單</title>
</head>
<body>
<!-- header網頁標題 -->
    <header>
       <div id="header"></div>
    </header>
    <form action="" method="post" name="checklistfilter">
    <section class="container-fluid">
    <h1 class="text-center">建築-其他清單</h1>
        <div class="d-flex ">        
            <a href='mmt_list_filepage.php?sdid=<?= $sdid?>' type="button" id="backlistmmt" class="btn btn-primary mt-4 rounded d-block mr-3">清除條件</a>
        
             
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
                    $sqlstr_total="SELECT a.* FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number  LEFT JOIN FA.CheckStatus as p ON a.status=p.id LEFT JOIN FA.MMT_cycleTime as c ON a.fileCycle=c.cycleID where (sdid=$sdid)and( b.cname like '%$keyword%' or a.eqlocation like '%$keyword%' or a.fileDownloadDate like '%$keyword%' or a.fileInsertDate like '%$keyword%' or a.checkdate like '%$keyword%' or p.checkName like '%$keyword%' or a.fileNo like '%$keyword%' or a.remark like '%$keyword%' or c.cycName like '%$keyword%')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number  LEFT JOIN FA.CheckStatus as p ON a.status=p.id LEFT JOIN FA.MMT_cycleTime as c ON a.fileCycle=c.cycleID where (sdid=$sdid)and( b.cname like '%$keyword%' or a.eqlocation like '%$keyword%' or a.fileDownloadDate like '%$keyword%' or a.fileInsertDate like '%$keyword%' or a.checkdate like '%$keyword%' or p.checkName like '%$keyword%' or a.fileNo like '%$keyword%' or a.remark like '%$keyword%' or c.cycName like '%$keyword%') ORDER BY a.fileDownloadDate DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                   //總資料數量
                    $totalstr_num="SELECT COUNT(fileNo) FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number  LEFT JOIN FA.CheckStatus as p ON a.status=p.id LEFT JOIN FA.MMT_cycleTime as c ON a.fileCycle=c.cycleID where (sdid=$sdid)and( b.cname like '%$keyword%' or a.eqlocation like '%$keyword%' or a.fileDownloadDate like '%$keyword%' or a.fileInsertDate like '%$keyword%' or a.checkdate like '%$keyword%' or p.checkName like '%$keyword%' or a.fileNo like '%$keyword%' or a.remark like '%$keyword%' or c.cycName like '%$keyword%')";
                    break;
                case 2://只有日期區間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number where (sdid=$sdid)and(fileDownloadDate between '$start_date' and '$end_date' or fileInsertDate between '$start_date' and '$end_date' or checkdate between '$start_date' and '$end_date')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number where (sdid=$sdid)and(fileDownloadDate between '$start_date' and '$end_date' or fileInsertDate between '$start_date' and '$end_date' or checkdate between '$start_date' and '$end_date') ORDER BY a.fileDownloadDate DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(fileNo) FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number where (sdid=$sdid)and(fileDownloadDate between '$start_date' and '$end_date' or fileInsertDate between '$start_date' and '$end_date' or checkdate between '$start_date' and '$end_date')";
                    break;
                case 3://只有開始時間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number  where (sdid=$sdid)and(fileDownloadDate ='$start_date' or fileInsertDate ='$start_date' or checkdate ='$start_date')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number where (sdid=$sdid)and(fileDownloadDate ='$start_date' or fileInsertDate ='$start_date' or checkdate ='$start_date') ORDER BY a.fileDownloadDate DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(fileNo) FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number where (sdid=$sdid)and(fileDownloadDate ='$start_date' or fileInsertDate ='$start_date' or checkdate ='$start_date')";
                    break;
                case 4://只有結束時間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number where (sdid=$sdid)and(fileDownloadDate ='$end_date' or fileInsertDate ='$end_date' or checkdate ='$end_date')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number where (sdid=$sdid)and(fileDownloadDate ='$end_date' or fileInsertDate ='$end_date' or checkdate ='$end_date') ORDER BY a.fileDownloadDate DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(fileNo) FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number where (sdid=$sdid)and(fileDownloadDate ='$end_date' or fileInsertDate ='$end_date' or checkdate ='$end_date')";
                    break;
                case 5://開始+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number  LEFT JOIN FA.CheckStatus as p ON a.status=p.id LEFT JOIN FA.MMT_cycleTime as c ON a.fileCycle=c.cycleID  where (sdid=$sdid)and((a.fileDownloadDate ='$start_date'or a.fileInsertDate= '$start_date' or a.checkdate= '$start_date') and ( b.cname like '%$keyword%' or a.eqlocation like '%$keyword%' or p.checkName like '%$keyword%' or a.fileNo like '%$keyword%' or a.remark like '%$keyword%' or c.cycName like '%$keyword%'))";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number  LEFT JOIN FA.CheckStatus as p ON a.status=p.id LEFT JOIN FA.MMT_cycleTime as c ON a.fileCycle=c.cycleID  where (sdid=$sdid)and((a.fileDownloadDate ='$start_date'or a.fileInsertDate = '$start_date' or a.checkdate= '$start_date') and ( b.cname like '%$keyword%' or a.eqlocation like '%$keyword%' or p.checkName like '%$keyword%' or a.fileNo like '%$keyword%' or a.remark like '%$keyword%' or c.cycName like '%$keyword%')) ORDER BY a.fileDownloadDate DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(fileNo) FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number  LEFT JOIN FA.CheckStatus as p ON a.status=p.id LEFT JOIN FA.MMT_cycleTime as c ON a.fileCycle=c.cycleID  where (sdid=$sdid)and((a.fileDownloadDate ='$start_date'or a.fileInsertDate = '$start_date' or a.checkdate= '$start_date') and ( b.cname like '%$keyword%' or a.eqlocation like '%$keyword%' or p.checkName like '%$keyword%' or a.fileNo like '%$keyword%' or a.remark like '%$keyword%' or c.cycName like '%$keyword%'))";
                    break;
                case 6://結束+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number  LEFT JOIN FA.CheckStatus as p ON a.status=p.id LEFT JOIN FA.MMT_cycleTime as c ON a.fileCycle=c.cycleID  where (sdid=$sdid)and((a.fileDownloadDate='$end_date'or a.fileInsertDate = '$end_date' or a.checkdate = '$end_date')  and ( b.cname like '%$keyword%' or a.eqlocation like '%$keyword%' or p.checkName like '%$keyword%' or a.fileNo like '%$keyword%' or a.remark like '%$keyword%' or c.cycName like '%$keyword%'))";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number  LEFT JOIN FA.CheckStatus as p ON a.status=p.id LEFT JOIN FA.MMT_cycleTime as c ON a.fileCycle=c.cycleID  where (sdid=$sdid)and((a.fileDownloadDate='$end_date'or a.fileInsertDate = '$end_date' or a.checkdate = '$end_date')  and ( b.cname like '%$keyword%' or a.eqlocation like '%$keyword%' or p.checkName like '%$keyword%' or a.fileNo like '%$keyword%' or a.remark like '%$keyword%' or c.cycName like '%$keyword%')) ORDER BY a.fileDownloadDate DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(fileNo) FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number  LEFT JOIN FA.CheckStatus as p ON a.status=p.id LEFT JOIN FA.MMT_cycleTime as c ON a.fileCycle=c.cycleID  where (sdid=$sdid)and((a.fileDownloadDate='$end_date'or a.fileInsertDate = '$end_date' or a.checkdate = '$end_date')  and ( b.cname like '%$keyword%' or a.eqlocation like '%$keyword%' or p.checkName like '%$keyword%' or a.fileNo like '%$keyword%' or a.remark like '%$keyword%' or c.cycName like '%$keyword%'))";
                    break;
                case 7://日期區間+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT a.* FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number  LEFT JOIN FA.CheckStatus as p ON a.status=p.id LEFT JOIN FA.MMT_cycleTime as c ON a.fileCycle=c.cycleID  where (sdid=$sdid)and( (a.fileDownloadDate between '$start_date' and '$end_date' or a.fileInsertDate between '$start_date' and '$end_date' or a.checkdate between '$start_date' and '$end_date')and ( b.cname like '%$keyword%' or a.eqlocation like '%$keyword%' or p.checkName like '%$keyword%' or a.fileNo like '%$keyword%' or a.remark like '%$keyword%' or c.cycName like '%$keyword%'))";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT a.* FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number  LEFT JOIN FA.CheckStatus as p ON a.status=p.id LEFT JOIN FA.MMT_cycleTime as c ON a.fileCycle=c.cycleID  where (sdid=$sdid)and( (a.fileDownloadDate between '$start_date' and '$end_date' or a.fileInsertDate between '$start_date' and '$end_date' or a.checkdate between '$start_date' and '$end_date')and ( b.cname like '%$keyword%' or a.eqlocation like '%$keyword%' or p.checkName like '%$keyword%' or a.fileNo like '%$keyword%' or a.remark like '%$keyword%' or c.cycName like '%$keyword%')) ORDER BY a.fileDownloadDate DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(fileNo) FROM FA.MMA_getFile as a LEFT JOIN FA.Employee as b on a.fileDownloadUserID=b.e_number or a.checkempId=b.e_number  LEFT JOIN FA.CheckStatus as p ON a.status=p.id LEFT JOIN FA.MMT_cycleTime as c ON a.fileCycle=c.cycleID  where (sdid=$sdid)and( (a.fileDownloadDate between '$start_date' and '$end_date' or a.fileInsertDate between '$start_date' and '$end_date' or a.checkdate between '$start_date' and '$end_date')and ( b.cname like '%$keyword%' or a.eqlocation like '%$keyword%' or p.checkName like '%$keyword%' or a.fileNo like '%$keyword%' or a.remark like '%$keyword%' or c.cycName like '%$keyword%'))";
                    break;
                default:
                    # code...
                    break;
            }

            
            $sql_page=$pdo->query($sqlstr_page);
            $sql_total=$pdo->query($sqlstr_total);
            $total_num=CURRENT($pdo->query($totalstr_num)->fetch());
            
            //本業開始的筆數            
        ?>
        <table id="mmt_a" class="display table table-striped table-bordered table-hover col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12 table-sm order-table"  aria-describedby="dataTables-example_info" data-sort-name="tid" data-sort-order="desc" data-sortable ="true" width="80%"> <!--表格樣式：條紋行、帶框表格、可滑入行-->
            <thead  class="thead-light">
            <tr align="center">
                <th scope="col" width="13%" name="tid" sortable="true">單號</th>
                <th scope="col" width="8%">上傳者</th>
                <th scope="col" width="9%">審核者/</BR>審核日期</th>
                <th scope="col" width="9%">上傳日期</th>
                <th scope="col" width="9%">保養日期</th>
                <th scope="col" width="3%">審核</th>            
                <th scope="col" width="10%">設備位置</th>
                <th scope="col" width="8%">保養週期</th>
                <th scope="col" width="8%">狀態</th>  
                <th scope="col" width="8%">上傳內容</th>                      
                <th scope="col" width="15%">備註</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while ($row=$sql_page->fetch()) {
            $ammtm[]=array(
                'id'=>$row["fileNo"],//案件單號
                'userID'=>$row["fileDownloadUserID"],//上傳者id
                'checkempId'=>$row["checkempId"],//審核者id
                'downloadDate'=>$row["fileDownloadDate"],//上傳日期
                'servDate'=>$row["fileInsertDate"],//保養日期
                'checkdate'=>$row["checkdate"],//審核日期
                'eqlocation'=>$row["eqlocation"],//保養地點
                'datekind'=>$row["fileCycle"],//報表類別(保養週期)                
                'sdid'=>$row["sdid"],//系統別細項id
                'filePath'=>$row["filePath"],//表單路徑(上傳內容)
                'remark'=>$row["remark"],//備註
                'status'=>$row["status"]//表單狀態
            );    
        }
        @$sql_page_num=count($ammtm);
            //for ($i=0; $i <$ammtmnum ; $i++) {
            for ($i=0; $i <$sql_page_num ; $i++) {
                $k=$i+2000;
                $a=$i;
                //顯示資料的轉換 
                $ammtmemp=sql_database('cname','FA.Employee','e_number',$ammtm[$i]['userID']);//員編轉人名
                @$ammtmcheckempId=sql_database('cname','FA.Employee','e_number',$ammtm[$i]['checkempId']);//員編轉人名
                $ammtmfileName=sql_database('sName','FA.MMT_sysDetail','id',$ammtm[$i]['sdid']);//細部系統名稱
                
                switch ($ammtm[$i]['datekind']) {//(W/F/D 未審核/完成/作廢)}
                    case 'HM':
                        $ammtmdatekind='半月保養';
                        break;
                    case 'M':
                        $ammtmdatekind='月保養';
                        break;
                    case 'S':
                        $ammtmdatekind='季保養';
                        break;  
                    case 'HY':
                        $ammtmdatekind='半年保養';
                        break;              
                    case 'Y':
                        $ammtmdatekind='年保養';
                        break;
                }
                switch ($ammtm[$i]['status']) {//(W/F/D 未審核/完成/作廢)}
                    case 'W':
                        $ammtmstatus='未審核';
                        break;
                    case 'M':
                        $ammtmstatus='進行中';
                        break;
                    case 'F':
                        $ammtmstatus='審核完成';
                        break;                      
                }            
        ?> 
            <tr align="center">
                <th scope="row" valign="middle"><?= $ammtm[$i]['id']?></th><!--表單編號-->
                <input type='hidden' name='<?= $k ?>' value='<?= $ammtm[$i]['id'] ?>'> <!--傳遞主表id-->
                <td><?= $ammtmemp?></td><!--上傳者-->
                
                <!--審核者/審核日期-->
                <?php if($ammtm[$i]['checkdate']){?>
                <td valign="middle"><?= $ammtmcheckempId?>/</br><?= $ammtm[$i]['checkdate']?></td>
                <?php }else{echo '<td></td>';}?>
                <!--審核者/審核日期end-->
                
                <td valign='middle' ><?= $ammtm[$i]['downloadDate']?></td><!--上傳日期-->  
                <td valign="middle"><?= $ammtm[$i]['servDate']?></td><!--保養日期-->
                
                <!--審核欄位-->
                <td valign="middle"><?php
                if ($ammtm[$i]['status']=='F') {
                    echo "<input type='checkbox' class='buildL3' name=\"".$a."\" value=\"".$ammtm[$i]['checkempId']."\" checked Disabled>";
                } else {
                    echo "<input type='checkbox' class='buildL3' name=\"".$a."\" value=\"".$checkuserID."\" Disabled>";
                }?>
                </td>
                <!--審核欄位end-->

                <td valign="middle"><?= $ammtm[$i]['eqlocation']?></td><!--設備位置-->
                <td valign="middle"><?= @$ammtmdatekind?></td><!--報表類別(保養週期)-->
                <td valign="middle"><?= $ammtmstatus?></td><!--報表狀態-->
                <?PHP
                if ($ammtm[$i]['filePath']) {
                    $filename='http://localhost/CMUH/'.$ammtm[$i]['filePath'];
                    echo "<td valign='middle'>"."<input type='button' value='開啟檔案' class='text-dark my-3 px-3 py-1 btn-outline-info' onclick = 'window.open(\"".$filename."\")'>".'</td>';        
                }else{
                    echo '<td></td>';
                }
                // echo "<input type='hidden' name=\"".$k."\" value=\"".$ammtm[$i]['id']."\">";//回傳表單id
                ?>
                <td valign='middle'><?= $ammtm[$i]['remark']?></td><!--備註-->
            </tr><?php
            }?>
            </tbody>
        </table>
        <?php
            //計算總頁數
            $total_page=ceil($total_num/$pageRow_record);
            echo '<table border="0" align="center">';    
                echo '<tr>';
                    echo '<td><h5>'.'資料清單共計'.$total_num.'筆(共'.$total_page.'頁)'.'</h5></td>';
                echo '</tr>';
            echo '</table>';
            echo '<table border="0" align="center">';
                echo '<tr>';
                    if ($page_num>1) {
                        @$prev=$page_num-1;
                        echo "<td><a href=mmt_list_filter.php?page=1\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".$keyword."\"&sdid=\"".$sdid."\"&key=next_page'>"."[第一頁]".'</a></td>';
                        echo "<td><a href=mmt_list_filter.php?page={$prev}\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".$keyword."\"&sdid=\"".$sdid."\"&key=next_page'>"."[<<<上一頁]".'</a></td>';
                        
                    }
                    if ($page_num<$total_page) {
                        @$next=$page_num+1;
                        echo "<td>"."<a href=mmt_list_filter.php?page={$next}\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".$keyword."\"&sdid=\"".$sdid."\"&key=next_page'>"."[下一頁>>>]".'</a></td>';
                        echo "<td><a href=mmt_list_filter.php?page=$total_page\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".$keyword."\"&sdid=\"".$sdid."\"&key=next_page'>".'[最末頁]'.'</a></td>';
                    }
                echo '</tr>';
            echo '</table>';
            
            //分頁按鈕一次七頁
            $phpfile = 'mmt_list_filter.php';
            $page= isset($_GET['page'])?$_GET['page']:1;        
            $getpageinfo = page($page,$total_num,$phpfile,$start_date,$end_date,$keyword,$sdid,'next_page');

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
    <input type="hidden" name="sdid" value='<?= $sdid ?>'>    
    </form>
    <form action="mmt_list_filter.php" method="post" name="mtlist"> 
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
            <input type="hidden" name="sdid" value='<?= $sdid ?>'>           
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