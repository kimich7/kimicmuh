<?php 
include("php/CMUHconndata.php");
include("php/fun.php");
//include("page_1.php");
include("page_searchfilter.php");
session_start();
    $checkuser=$_SESSION["login_member"];
    $checkuserID=sql_database('e_number','FA.Employee','cname',$checkuser);
    $stemp="SELECT * FROM FA.Employee WHERE e_number='$checkuserID'";
    $member=$pdo->query($stemp)->fetch();
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
    }
    if (isset($_POST["action"])&&($_POST["action"]=="new_page")){//重新搜索
        @$start_date=$_POST["start_date"];
        @$end_date=$_POST["end_date"];
        @$keyword=$_POST["keywordsearch"];
    }
    
    if ((isset($_POST["action"])&&($_POST["action"]=="new_page"))&&((!isset($start_date) or $start_date=='') && (!isset($end_date)or $end_date=='')&& (!isset($keyword) or $keyword==''))) {
         header("Location: mmt_list_p.php");
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
    <title>水系統-給水(電壓電流)</title>
</head>
<body>
<!-- header網頁標題 -->
    <header>
       <div id="header"></div>
    </header>
    <form action="" method="post" name="checklistfilter">
    <section class="container-fluid">
    <h1 class="text-center">水系統-給水(電壓電流)</h1>
        <div class="d-flex ">        
            <a href='mmt_list_p.php' type="button" id="backlistmmt" class="btn btn-primary mt-4 rounded d-block mr-3">清除條件</a>
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
            //本業開始的筆數    
            switch ($x) {
                case 1://只有keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.id),a.bid,a.cemp,a.datekind,a.eid,a.emp,a.fid,a.macNo,a.rdate,a.remark,a.sremp,a.status,a.tid FROM FA.MMT_PtableM as a LEFT JOIN FA.Employee as b on a.emp=b.e_number or a.sremp=b.e_number or a.cemp=b.e_number left join FA.MMT_build as c on a.bid=c.id left join FA.MMT_equip as s on a.eid=s.id left join FA.MMT_KIND as k on a.tid=k.id LEFT JOIN FA.CheckStatus as p ON a.status=p.id WHERE b.cname like '%$keyword%' or c.bName like '%$keyword%' or s.eName like '%$keyword%' or k.tableName like '%$keyword%' or p.checkName like '%$keyword%' or a.remark like '%$keyword%' or a.rdate like '%$keyword%'";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.id),a.bid,a.cemp,a.datekind,a.eid,a.emp,a.fid,a.macNo,a.rdate,a.remark,a.sremp,a.status,a.tid FROM FA.MMT_PtableM as a LEFT JOIN FA.Employee as b on a.emp=b.e_number or a.sremp=b.e_number or a.cemp=b.e_number left join FA.MMT_build as c on a.bid=c.id left join FA.MMT_equip as s on a.eid=s.id left join FA.MMT_KIND as k on a.tid=k.id LEFT JOIN FA.CheckStatus as p ON a.status=p.id WHERE b.cname like '%$keyword%' or c.bName like '%$keyword%' or s.eName like '%$keyword%' or k.tableName like '%$keyword%' or p.checkName like '%$keyword%' or a.remark like '%$keyword%' or a.rdate like '%$keyword%' ORDER BY a.id DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.id)) FROM FA.MMT_PtableM as a LEFT JOIN FA.Employee as b on a.emp=b.e_number or a.sremp=b.e_number or a.cemp=b.e_number left join FA.MMT_build as c on a.bid=c.id left join FA.MMT_equip as s on a.eid=s.id left join FA.MMT_KIND as k on a.tid=k.id LEFT JOIN FA.CheckStatus as p ON a.status=p.id WHERE b.cname like '%$keyword%' or c.bName like '%$keyword%' or s.eName like '%$keyword%' or k.tableName like '%$keyword%' or p.checkName like '%$keyword%' or a.remark like '%$keyword%' or a.rdate like '%$keyword%' ";
                    break;
                case 2://只有日期區間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT * FROM FA.MMT_PtableM WHERE rDate between '$start_date' and '$end_date'";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT * FROM FA.MMT_PtableM WHERE rDate between '$start_date' and '$end_date' ORDER BY id DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(id) FROM FA.MMT_PtableM WHERE rDate between '$start_date' and '$end_date' ";
                    break;
                case 3://只有開始時間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT * FROM FA.MMT_PtableM WHERE rDate ='$start_date'";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT * FROM FA.MMT_PtableM WHERE rDate ='$start_date' ORDER BY id DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(id) FROM FA.MMT_PtableM WHERE rDate ='$start_date'";
                    break;
                case 4://只有結束時間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT * FROM FA.MMT_PtableM WHERE rDate ='$end_date'";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT * FROM FA.MMT_PtableM WHERE rDate ='$end_date' ORDER BY id DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(id) FROM FA.MMT_PtableM WHERE rDate ='$end_date'";
                    break;
                case 5://開始+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.id),a.bid,a.cemp,a.datekind,a.eid,a.emp,a.fid,a.macNo,a.rdate,a.remark,a.sremp,a.status,a.tid FROM FA.MMT_PtableM as a LEFT JOIN FA.Employee as b on a.emp=b.e_number or a.sremp=b.e_number or a.cemp=b.e_number left join FA.MMT_build as c on a.bid=c.id left join FA.MMT_equip as s on a.eid=s.id left join FA.MMT_KIND as k on a.tid=k.id LEFT JOIN FA.CheckStatus as p ON a.status=p.id WHERE rDate ='$start_date' and (b.cname like '%$keyword%' or c.bName like '%$keyword%' or s.eName like '%$keyword%' or k.tableName like '%$keyword%' or p.checkName like '%$keyword%' or a.remark like '%$keyword%')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.id),a.bid,a.cemp,a.datekind,a.eid,a.emp,a.fid,a.macNo,a.rdate,a.remark,a.sremp,a.status,a.tid FROM FA.MMT_PtableM as a LEFT JOIN FA.Employee as b on a.emp=b.e_number or a.sremp=b.e_number or a.cemp=b.e_number left join FA.MMT_build as c on a.bid=c.id left join FA.MMT_equip as s on a.eid=s.id left join FA.MMT_KIND as k on a.tid=k.id LEFT JOIN FA.CheckStatus as p ON a.status=p.id WHERE rDate ='$start_date' and (b.cname like '%$keyword%' or c.bName like '%$keyword%' or s.eName like '%$keyword%' or k.tableName like '%$keyword%' or p.checkName like '%$keyword%' or a.remark like '%$keyword%') ORDER BY a.id DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.id)) FROM FA.MMT_PtableM as a LEFT JOIN FA.Employee as b on a.emp=b.e_number or a.sremp=b.e_number or a.cemp=b.e_number left join FA.MMT_build as c on a.bid=c.id left join FA.MMT_equip as s on a.eid=s.id left join FA.MMT_KIND as k on a.tid=k.id  LEFT JOIN FA.CheckStatus as p ON a.status=p.id WHERE rDate ='$start_date' and (b.cname like '%$keyword%' or c.bName like '%$keyword%' or s.eName like '%$keyword%' or k.tableName like '%$keyword%' or p.checkName like '%$keyword%' or a.remark like '%$keyword%')";
                    break;
                case 6://結束+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.id),a.bid,a.cemp,a.datekind,a.eid,a.emp,a.fid,a.macNo,a.rdate,a.remark,a.sremp,a.status,a.tid FROM FA.MMT_PtableM as a LEFT JOIN FA.Employee as b on a.emp=b.e_number or a.sremp=b.e_number or a.cemp=b.e_number left join FA.MMT_build as c on a.bid=c.id left join FA.MMT_equip as s on a.eid=s.id left join FA.MMT_KIND as k on a.tid=k.id LEFT JOIN FA.CheckStatus as p ON a.status=p.id WHERE rDate ='$end_date' and (b.cname like '%$keyword%' or c.bName like '%$keyword%' or s.eName like '%$keyword%' or k.tableName like '%$keyword%' or p.checkName like '%$keyword%' or a.remark like '%$keyword%')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.id),a.bid,a.cemp,a.datekind,a.eid,a.emp,a.fid,a.macNo,a.rdate,a.remark,a.sremp,a.status,a.tid FROM FA.MMT_PtableM as a LEFT JOIN FA.Employee as b on a.emp=b.e_number or a.sremp=b.e_number or a.cemp=b.e_number left join FA.MMT_build as c on a.bid=c.id left join FA.MMT_equip as s on a.eid=s.id left join FA.MMT_KIND as k on a.tid=k.id LEFT JOIN FA.CheckStatus as p ON a.status=p.id WHERE rDate ='$end_date' and (b.cname like '%$keyword%' or c.bName like '%$keyword%' or s.eName like '%$keyword%' or k.tableName like '%$keyword%' or p.checkName like '%$keyword%' or a.remark like '%$keyword%') ORDER BY a.id DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.id)) FROM FA.MMT_PtableM as a LEFT JOIN FA.Employee as b on a.emp=b.e_number or a.sremp=b.e_number or a.cemp=b.e_number left join FA.MMT_build as c on a.bid=c.id left join FA.MMT_equip as s on a.eid=s.id left join FA.MMT_KIND as k on a.tid=k.id  LEFT JOIN FA.CheckStatus as p ON a.status=p.id WHERE rDate ='$end_date' and (b.cname like '%$keyword%' or c.bName like '%$keyword%' or s.eName like '%$keyword%' or k.tableName like '%$keyword%' or p.checkName like '%$keyword%' or a.remark like '%$keyword%')";
                    break;
                case 7://結束+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.id),a.bid,a.cemp,a.datekind,a.eid,a.emp,a.fid,a.macNo,a.rdate,a.remark,a.sremp,a.status,a.tid FROM FA.MMT_PtableM as a LEFT JOIN FA.Employee as b on a.emp=b.e_number or a.sremp=b.e_number or a.cemp=b.e_number left join FA.MMT_build as c on a.bid=c.id left join FA.MMT_equip as s on a.eid=s.id left join FA.MMT_KIND as k on a.tid=k.id LEFT JOIN FA.CheckStatus as p ON a.status=p.id WHERE (rDate between '$start_date' and '$end_date')and (b.cname like '%$keyword%' or c.bName like '%$keyword%' or s.eName like '%$keyword%' or k.tableName like '%$keyword%' or p.checkName like '%$keyword%' or a.remark like '%$keyword%')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.id),a.bid,a.cemp,a.datekind,a.eid,a.emp,a.fid,a.macNo,a.rdate,a.remark,a.sremp,a.status,a.tid FROM FA.MMT_PtableM as a LEFT JOIN FA.Employee as b on a.emp=b.e_number or a.sremp=b.e_number or a.cemp=b.e_number left join FA.MMT_build as c on a.bid=c.id left join FA.MMT_equip as s on a.eid=s.id left join FA.MMT_KIND as k on a.tid=k.id LEFT JOIN FA.CheckStatus as p ON a.status=p.id WHERE (rDate between '$start_date' and '$end_date')and (b.cname like '%$keyword%' or c.bName like '%$keyword%' or s.eName like '%$keyword%' or k.tableName like '%$keyword%' or p.checkName like '%$keyword%' or a.remark like '%$keyword%') ORDER BY a.id DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.id)) FROM FA.MMT_PtableM as a LEFT JOIN FA.Employee as b on a.emp=b.e_number or a.sremp=b.e_number or a.cemp=b.e_number left join FA.MMT_build as c on a.bid=c.id left join FA.MMT_equip as s on a.eid=s.id left join FA.MMT_KIND as k on a.tid=k.id  LEFT JOIN FA.CheckStatus as p ON a.status=p.id WHERE (rDate between '$start_date' and '$end_date')and (b.cname like '%$keyword%' or c.bName like '%$keyword%' or s.eName like '%$keyword%' or k.tableName like '%$keyword%' or p.checkName like '%$keyword%' or a.remark like '%$keyword%')";
                    break;
                default:
                    # code...
                    break;
            }

            
            $sql_page=$pdo->query($sqlstr_page);
            $sql_total=$pdo->query($sqlstr_total);
            $total_num=CURRENT($pdo->query($totalstr_num)->fetch());
        ?>
        <table id="mmt_p" class="display table table-striped table-bordered table-hover col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12 table-sm order-table"  aria-describedby="dataTables-example_info" data-sort-name="tid" data-sort-order="desc" data-sortable ="true"><!--表格樣式：條紋行、帶框表格、可滑入行-->
        <thead  class="thead-light">
            <tr align="center">
            <th scope="col" width="15%" name="tid" sortable="true">單號</th>
            <th scope="col" width="32%">標單名稱</th>
            <th scope="col" width="10%">保養日期</th>
            <th scope="col" width="8%">保養人</th>
            <th scope="col" width="8%">專責</th>
            <th scope="col" width="8%">工務室</th>
            <th scope="col" width="8%">狀態</th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        <?php
        //while ($row=$ammtmQuery->fetch()) {
        while ($row=$sql_page->fetch()) {
            $ammtm[]=array(
                'id'=>$row["id"],//案件單號
                'tableNo'=>$row["tid"],//報表編號
                'macNo'=>$row["macNo"],//設備機台編號
                'bid'=>$row["bid"],//棟別id
                'fid'=>$row["fid"],//樓層id
                'eid'=>$row["eid"],//設備編號
                'rdate'=>$row["rdate"],//紀錄日期
                'datekind'=>$row["datekind"],//保養週期
                'remark'=>$row["remark"],//備註
                'emp'=>$row["emp"],//保養者員工編號
                'sremp'=>$row['sremp'],//專責員工編號
                'cemp'=>$row["cemp"],//工務室審核員工編號
                'status'=>$row["status"]//狀態(W/F/D 未審核/完成/作廢)
            );    
        }
        @$sql_page_num=count($ammtm);
            //for ($i=0; $i <$ammtmnum ; $i++) {    
            for ($i=0; $i <$sql_page_num ; $i++) {
                $k=$i+2000;
                //顯示資料的轉換 
                $ammtmemp=sql_database('cname','FA.Employee','e_number',$ammtm[$i]['emp']);//員編轉人名
                $ammtmsremp=sql_database('cname','FA.Employee','e_number',$ammtm[$i]['sremp']);//員編轉人名
                $ammtmcemp=sql_database('cname','FA.Employee','e_number',$ammtm[$i]['cemp']);//審核員編轉人名
                $ammtmtabletitle=sql_database('tableName','FA.MMT_KIND','id',$ammtm[$i]['tableNo']);//表單編號轉名稱
                switch ($ammtm[$i]['status']) {//(W/F/D 未審核/完成/作廢)}
                    case 'W':
                        $ammtmstatus='未審核';
                        break;
                    case NULL:
                        $ammtmstatus='未審核';
                        break;
                    case '':
                        $ammtmstatus='未審核';
                        break;  
                    case 'M':
                        $ammtmstatus='進行中';
                        break;              
                    case 'F':
                        $ammtmstatus='審核完成';
                        break;
                }
            $macNo=$ammtm[$i]['macNo'];
        ?>
            <tr align="center">
                <th scope="row"><?= $ammtm[$i]['id']?></th><!--表單編號-->                
                <td><a href="mmtDetail_p.php?id=<?= $ammtm[$i]['id']?>"><?= $ammtmtabletitle.'('.$macNo.')' ?></a></td><!--表單名稱-->
                <input type='hidden' name='<?= $k ?>' value='<?= $ammtm[$i]['id'] ?>'><!--傳遞主表id-->
                <td><?= $ammtm[$i]['rdate']?></td><!--保養日期-->  
                <td><?= $ammtmemp?></td><!--保養者-->
                <td><?= $ammtmsremp?></td><!--專責-->
                <td><?= $ammtmcemp?></td><!--審核者--> 
                <?php 
                if ($ammtmstatus!='審核完成') { ?>                
                    <td>
                        <span class="billBoardwaterL2 billBoardwaterL3" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="請登入相應權限帳號已解鎖">
                        <a href="mmtcheck_p.php?id=<?= $ammtm[$i]['id']?>" class="waterL2 waterL3" Disabled><?= $ammtmstatus?></a>
                        </span>
                    </td><!--狀態-->
                
                <?php } else { ?>
                    <td><?= $ammtmstatus?></td>
                <?php } ?>                
                <td>
                    <span class="billBoardwaterL2 billBoardwaterL3" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="請登入相應權限帳號已解鎖">
                    <a href="mmtEdit_p.php?id=<?= $ammtm[$i]['id']?>" class="waterL2 waterL3" Disabled>內容編輯與修改</a>
                    </span>
                </td>                
            </tr>
        <?php }?>
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
                        echo "<td><a href='mmt_list_p_filter.php?page=1\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".$keyword."\"&key=next_page'>"."[第一頁]".'</a></td>';
                        echo "<td><a href='mmt_list_p_filter.php?page={$prev}\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".$keyword."\"&key=next_page'>"."[<<<上一頁]".'</a></td>';
                        
                    }
                    if ($page_num<$total_page) {
                        @$next=$page_num+1;
                        echo "<td>"."<a href='mmt_list_p_filter.php?page={$next}\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".$keyword."\"&key=next_page'>"."[下一頁>>>]".'</a></td>';
                        echo "<td><a href='mmt_list_p_filter.php?page=$total_page\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".$keyword."\"&key=next_page'>".'[最末頁]'.'</a></td>';
                    }
                echo '</tr>';
            echo '</table>';
            
            //分頁按鈕一次七頁
            $phpfile = 'mmt_list_p_filter.php';
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
                <a href='index.html' type='button' class="my-3 px-3 py-1 btn-outline-info text-dark">離開</a>
            </div>
        </div>
    </section>
    </form>
    <form action="mmt_list_p_filter.php" method="post" name="mtlist"> 
            <div>
                <div align="left">
                    日期區間搜索：</br>
                    開始時間：<input type="date" name="start_date">&nbsp&nbsp結束時間：<input type="date" name="end_date">            
                </div>
                <div align="left">
                    </br>關鍵字：<input type="text" name="keywordsearch" placeholder="請輸入關鍵字">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<input type="submit" value="查詢">
                </div>           
            </div> 
            <input type="hidden" name="action" value="new_page">        
        </form>
    <script>
        (function(document) {
            //表格排序
            $("#mmt_p").tablesorter();            
        })(document);
    </script> 
    <!-- footer網頁尾頁 -->
    <footer>
        <div id="footer"></div>
    </footer>
    
</body>
</html>