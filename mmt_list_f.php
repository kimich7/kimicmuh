<?php
include("php/CMUHconndata.php");
include("php/fun.php");
include("page_1.php");
//登入者
session_start();
$checkuser=$_SESSION["login_member"];
$checkuserID=sql_database('e_number','FA.Employee','cname',$checkuser);
$str_member="SELECT * FROM FA.Employee WHERE e_number='$checkuserID'";
$member=$pdo->query($str_member)->fetch();
//總資料
//$ammtmstr="SELECT * FROM FA.MMT_AtableM ";

$pageRow_record=10;//每頁的筆數
$page_num=1;//預設的頁數
//更新$page_num        
if (isset($_GET['page'])) {
    $page_num=$_GET['page'];
}
$startRow_record=($page_num-1)*$pageRow_record;

//篩選後給每頁的筆數
$sqlstr_page="SELECT id,bid,fid,eid,datekind,tid,macNo,remark,H1_emp,H2_emp,status,H1_vora,H2_vora,timestamp FROM FA.MMT_FtableM  ORDER BY timestamp DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
//總資料數量
$ammtmnumstr="SELECT Count(id)FROM FA.MMT_FtableM ";
$sql_page=$pdo->query($sqlstr_page);
$ammtmnum=Current($pdo->query($ammtmnumstr)->fetch());//全部數量
$total_num=$ammtmnum;
//本頁開始的筆數
$i=0;        
$k=$i+2000;


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

    <!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <link rel="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"> -->


    <!-- 連結自己的JS -->
    <script src="./js/main.js"></script>
    <title>消防設備</title>    
</head>
<body>
     <!-- header網頁標題 -->
    <header>
        <nav class="navbar navbar-expand-sm navbar-light bg-light">
            <a class="navbar-brand" href="index.html">
                <img src="./jpg/logo.png" alt="">
            </a>
        </nav>
    </header>
    <form action="mmtCreate_f_choice.php" method="post" name="mmtca">
        <div class="panel-heading">
            <input type="hidden" name="mmtsysf" value='F'>
            <div class="row my-3">
                <div class="col">
                <span class="billBoardfireL1 billBoardfireL3" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="請登入相應權限帳號已解鎖">
                    <p class="d-inline font-weight-bold">&nbsp&nbsp&nbsp&nbsp新增保養：<button type='submit' name="mmtsysfbtn" class="btn btn-primary fireL1 fireL3" Disabled>新增</button></p>
                </span>
                </div>
            </div>    
        </div>
    </form>
    
    <table id="mmt_f" class="display table table-striped table-bordered table-hover col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12 table-sm order-table" aria-describedby="dataTables-example_info" data-sort-name="tid" data-sort-order="desc" data-sortable ="true"><!--表格樣式：條紋行、帶框表格、可滑入行-->
        <thead  class="thead-light">
            <tr align="center">
            <th scope="col" width="15%" name="tid" sortable="true">單號</th>
            <th scope="col" width="40%">標單名稱</th>
            <th scope="col" width="10%">保養日期</th>
            <!-- <th scope="col" width="8%">保養人</th>
            <th scope="col" width="8%">工務室</th> -->
            <th scope="col" width="8%">狀態</th>
            <th scope="col">內容編輯與修改</th>
            <th scope="col">上、下年度消防檢查(廠商)</th>
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
                'rdate'=>$row["timestamp"],//時間戳記
                'datekind'=>$row["datekind"],//保養週期
                'remark'=>$row["remark"],//備註
                'emp'=>$row["H1_emp"],//檢查者
                'cemp'=>$row["H2_emp"],//複查者
                'status'=>$row["status"],//狀態(W/F/D 未審核/完成/作廢)
                'H1_vora'=>$row["H1_vora"],//上半年檢查的電壓電流
                'H2_vora'=>$row["H2_vora"]//下半年檢查的電壓電流
            );    
        }
        @$sql_page_num=count($ammtm);
            //for ($i=0; $i <$ammtmnum ; $i++) {    
            for ($i=0; $i <$sql_page_num ; $i++) {
                $k=$i+2000;
                //顯示資料的轉換 
                $ammtmemp=sql_database('cname','FA.Employee','e_number',$ammtm[$i]['emp']);//員編轉人名
                $ammtmcemp=sql_database('cname','FA.Employee','e_number',$ammtm[$i]['cemp']);//審核員編轉人名
                $ammtmtabletitle=sql_database('tableName','FA.MMT_KIND','id',$ammtm[$i]['tableNo']);//表單編號轉名稱
                switch ($ammtm[$i]['status']) {//(W/M/F/D 未審核/進行中/完成/作廢)}
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
                <td><a href="mmtDetail_f.php?id=<?= $ammtm[$i]['id']?>"><?= $ammtmtabletitle.'('.$macNo.')' ?></a></td><!--表單名稱-->
                <input type='hidden' name='<?= $k ?>' value='<?= $ammtm[$i]['id'] ?>'><!--傳遞主表id-->
                <td><?= $ammtm[$i]['rdate']?></td><!--保養日期-->  
                <!--<td><?//= $ammtmemp?></td>--><!--保養者-->                
                <!--<td><?//= $ammtmcemp?></td>--><!--審核者--> 
                <?php 
                if ($ammtmstatus!='審核完成') { ?>
                    <span class="billBoardfireL2 billBoardfireL3" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="請登入相應權限帳號已解鎖">
                    <td><a href="mmtcheck_f.php?id=<?= $ammtm[$i]['id']?>"class="fireL2 fireL3" Disabled><?= $ammtmstatus?></a></td><!--狀態-->
                    </span>
                <?php } else { ?>
                    <td><?= $ammtmstatus?></td>
                <?php } ?>
                <span class="billBoardfireL2 billBoardfireL3" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="請登入相應權限帳號已解鎖">
                <td><a href="mmtEdit_f.php?id=<?= $ammtm[$i]['id']?>" class="fireL2 fireL3" Disabled>內容編輯與修改</a></td>
                </span>
                <td><a href="mmth1check_f.php?id=<?= $ammtm[$i]['id']?>">上半年</a> || <a href="mmth2check_f.php?id=<?= $ammtm[$i]['id']?>">下半年</a></td>
            </tr>
        <?php }?>
        </tbody>
    </table> 
<?php 
 //計算總頁數
        $total_page=ceil($ammtmnum/$pageRow_record);
        echo '<table border="0" align="center">';    
            echo '<tr>';
                echo '<td><h5>'.'保養紀錄表單共計'.$ammtmnum.'筆(共'.$total_page.'頁)'.'</h5></td>';
            echo '</tr>';
        echo '</table>';
        echo '<table border="0" align="center">';
            echo '<tr>';
                if ($page_num>1) {
                    $prev=$page_num-1;
                    echo '<td><a href="mmt_list_f.php?page=1">'."[第一頁]".'</a></td>';
                    echo "<td><a href=\"mmt_list_f.php?page={$prev}\">"."[<<<上一頁]".'</a></td>';
                }
                if ($page_num<$total_page) {
                    $next=$page_num+1;
                    echo "<td>"."<a href=\"mmt_list_f.php?page={$next}\">"."[下一頁>>>]".'</a></td>';
                    echo "<td><a href=\"mmt_list_f.php?page=$total_page\">".'[最末頁]'.'</a></td>';
                }
            echo '</tr>';
        echo '</table>';
        //分頁按鈕一次七頁
            $phpfile = 'mmt_list_f.php';
            $page= isset($_GET['page'])?$_GET['page']:1;        
            $getpageinfo = page($page,$total_num,$phpfile);
            echo '<div align="center">'; 
            echo @$getpageinfo['pagecode'];//顯示分頁的html語法
            echo '</div>';
?>
<input type="hidden" name="total_num" value="<?= $total_num?>">;
<input type="hidden" name="action" value="new_page"> 
<!--filter-->
<form action="mmt_list_f_filter.php" method="post" name="mtlist"> 
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
    <input type="hidden" name="total_num" value="<?= $total_num?>">           
</form>
<!--filter end-->
<script>
    (function(document) {
        //表格排序
        $("#mmt_f").tablesorter();
    })(document);
</script>
</body>
</html>