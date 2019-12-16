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
$pageRow_record=10;//每頁的筆數
$page_num=1;//預設的頁數

//更新$page_num        
if (isset($_GET['page'])) {
    $page_num=$_GET['page'];
}
$startRow_record=($page_num-1)*$pageRow_record;

//所有的資料
$ammtmstr="SELECT id,bid,title,rdate,datekind,tid,remark,emp,sremp,cemp,status FROM FA.MMT_EtableM ";//全部資料

//總資料數量
$ammtmnumstr="SELECT Count(id)FROM FA.MMT_EtableM ";
$ammtmnum=Current($pdo->query($ammtmnumstr)->fetch());//全部數量
$total_num=$ammtmnum;

//----還沒帶入----
//篩選後給每頁的筆數
$sqlstr_page="SELECT id,bid,title,rdate,datekind,tid,remark,emp,sremp,cemp,status FROM FA.MMT_EtableM  ORDER BY rdate DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
$sql_page=$pdo->query($sqlstr_page);


//本頁開始的筆數
$i=0;        
$k=$i+2000;
$ammtmQuery=$pdo->query($ammtmstr);
$ammtmAll=array();
//----還沒帶入END----

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
    <title>電力-分電盤</title>
    
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
    <form action="mmtCreate_e_choice.php" method="post" name="mmtca">
        <div class="panel-heading">
            <input type="hidden" name="mmtsysa" value='E'>
            <div class="row my-3">                
                <div class="col">                    
                    <p class="d-inline font-weight-bold">&nbsp&nbsp&nbsp&nbsp新增保養：
                        <span class="billBoardpowerL1 billBoardpowerL3" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="請登入相應權限帳號已解鎖">
                        <button type='submit' name="mmtsysabtn" class="btn btn-primary powerL1 powerL3" Disabled  >新增</button>
                        </span>
                    </p>                    
                </div>
                <div class="col text-right">
                    <p class="d-inline font-weight-bold">Search:&nbsp&nbsp<input type="search" class="light-table-filter" data-table="order-table" placeholder="請輸入關鍵字"></p>
                </div>
            </div>    
        </div>
    </form>
    
    <table id="mmt_e" class="display table table-striped table-bordered table-hover col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12 table-sm order-table"  aria-describedby="dataTables-example_info" data-sort-name="tid" data-sort-order="desc" data-sortable ="true"><!--表格樣式：條紋行、帶框表格、可滑入行-->
        <thead  class="thead-light">
            <tr align="center">
            <th scope="col" width="15%" name="tid" sortable="true">單號</th><!--id：棟代號+日期ex.B20191127-->
            <th scope="col" width="32%">標單名稱</th><!--title：棟別名稱+行動不便者設施設備檢查記錄表+日期 ex.2018-11-27第一醫療大樓-行動不便者設施設備檢查記錄表-->
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
                'id'=>$row["id"],//案件單號。id：棟代號+日期ex.B20191127                
                'title'=>$row["title"],//title
                'bid'=>$row["bid"],//棟別id
                'rdate'=>$row["rdate"],//紀錄日期
                'datekind'=>$row["datekind"],//保養週期
                'tableNo'=>$row["tid"],//報表編號。48~54
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
        ?>
            <tr align="center">
                <th scope="row"><?= $ammtm[$i]['id']?></th><!--表單編號-->                
                <td><a href="mmtDetail_e.php?id=<?= $ammtm[$i]['id']?>"><?= $ammtm[$i]['title'] ?></a></td><!--表單名稱-->
                <input type='hidden' name='<?= $k ?>' value='<?= $ammtm[$i]['id'] ?>'><!--傳遞主表id-->
                <td><?= $ammtm[$i]['rdate']?></td><!--保養日期-->  
                <td><?= $ammtmemp?></td><!--保養者-->
                <td><?= $ammtmsremp?></td><!--專責-->
                <td><?= $ammtmcemp?></td><!--審核者--> 
            <?php 
            if ($ammtmstatus!='審核完成') { ?>                
                <td>
                    <span class="billBoardpowerL2 billBoardpowerL3" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="請登入相應權限帳號已解鎖">
                    <a href="mmtcheck_e.php?id=<?= $ammtm[$i]['id']?>" class="powerL2 powerL3" Disabled><?= $ammtmstatus?></a>
                    </span>
                </td><!--狀態-->                
            <?php } else { ?>
                <td><?= $ammtmstatus?></td>
            <?php } ?>                
                <td>
                    <span class="billBoardpowerL2 billBoardpowerL3" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="請登入相應權限帳號已解鎖">
                    <a href="mmtEdit_e.php?id=<?= $ammtm[$i]['id']?>" class="powerL2 powerL3" Disabled>內容編輯與修改</a>
                    </span>
                </td>                
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
                    echo '<td><a href="mmt_list_e.php?page=1">'."[第一頁]".'</a></td>';
                    echo "<td><a href=\"mmt_list_e.php?page={$prev}\">"."[<<<上一頁]".'</a></td>';
                }
                if ($page_num<$total_page) {
                    $next=$page_num+1;
                    echo "<td>"."<a href=\"mmt_list_e.php?page={$next}\">"."[下一頁>>>]".'</a></td>';
                    echo "<td><a href=\"mmt_list_e.php?page=$total_page\">".'[最末頁]'.'</a></td>';
                }
            echo '</tr>';
        echo '</table>';
        //分頁按鈕一次七頁
            $phpfile = 'mmt_list_e.php';
            $page= isset($_GET['page'])?$_GET['page']:1;        
            $getpageinfo = page($page,$total_num,$phpfile);
            echo '<div align="center">'; 
            echo @$getpageinfo['pagecode'];//顯示分頁的html語法
            echo '</div>';
        //分頁按鈕end        
?>
<input type="hidden" name="total_num" value="<?= $total_num?>">;
<input type="hidden" name="action" value="new_page"> 
<script>
    (function(document) {
        'use strict';
        // 建立 LightTableFilter
        var LightTableFilter = (function(Arr) {
            var _input;
            // 資料輸入事件處理函數
            function _onInputEvent(e) {
                _input = e.target;
                var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
                Arr.forEach.call(tables, function(table) {
                    Arr.forEach.call(table.tBodies, function(tbody) {
                    Arr.forEach.call(tbody.rows, _filter);
                    });
                });
            }
            // 資料篩選函數，顯示包含關鍵字的列，其餘隱藏
            function _filter(row) {
                var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
                row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
            }
            return {
            // 初始化函數
                init: function() {
                    var inputs = document.getElementsByClassName('light-table-filter');
                    Arr.forEach.call(inputs, function(input) {
                    input.oninput = _onInputEvent;
                    });
                }
            };
        })(Array.prototype);
        // 網頁載入完成後，啟動 LightTableFilter
        document.addEventListener('readystatechange', function() {
            if (document.readyState === 'complete') {
                LightTableFilter.init();
            }
        });

        //表格排序
        $("#mmt_e").tablesorter();
    })(document);
</script>
</body>
</html>