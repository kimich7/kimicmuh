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
$sqlstr_page="SELECT id,bid,fid,eid,rdate,datekind,tid,macNo,remark,emp,sremp,cemp,status FROM FA.MMT_AtableM  ORDER BY rdate DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
//總資料數量
$ammtmnumstr="SELECT Count(id)FROM FA.MMT_AtableM ";
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
    <!-- 連結自己的JS -->
    <script src="./js/main.js"></script>
    <title>工程專案清單</title>
    
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
    <form action="mmtCreate_a_choice.php" method="post" name="mmtca">
        <div class="panel-heading">
            <input type="hidden" name="mmtsysa" value='A'>
            <div class="row my-3">
                
                <div class="col">
                    
                    <p class="d-inline font-weight-bold">&nbsp&nbsp&nbsp&nbsp新增保養：
                        <span class="billBoardairconL1 billBoardairconL3" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="請登入相應權限帳號已解鎖">
                        <button type='submit' name="mmtsysabtn" class="btn btn-primary airconL1 airconL3" Disabled  >新增</button>
                        </span>
                    </p>
                    
                </div>
                
                    <!-- <h4>&nbsp&nbsp&nbsp&nbsp新增保養：<a class="btn btn-primary" href="mmtCreate_a_choice.php" class="text-dark">新增</a></h4>    -->
                <div class="col text-right">
                    <p class="d-inline font-weight-bold">Search:&nbsp&nbsp<input type="search" class="light-table-filter" data-table="order-table" placeholder="請輸入關鍵字"></p>
                </div>
            </div>    
        </div>
    </form>
    
    <table id="mmt_a" class="display table table-striped table-bordered table-hover col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12 table-sm order-table"  aria-describedby="dataTables-example_info" data-sort-name="tid" data-sort-order="desc" data-sortable ="true"><!--表格樣式：條紋行、帶框表格、可滑入行-->
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
                <td><a href="mmtDetail_a.php?id=<?= $ammtm[$i]['id']?>"><?= $ammtmtabletitle.'('.$macNo.')' ?></a></td><!--表單名稱-->
                <input type='hidden' name='<?= $k ?>' value='<?= $ammtm[$i]['id'] ?>'><!--傳遞主表id-->
                <td><?= $ammtm[$i]['rdate']?></td><!--保養日期-->  
                <td><?= $ammtmemp?></td><!--保養者-->
                <td><?= $ammtmsremp?></td><!--專責-->
                <td><?= $ammtmcemp?></td><!--審核者--> 
                <?php 
                if ($ammtmstatus!='審核完成') { ?>
                
                    <td>
                        <span class="billBoardairconL2 billBoardairconL3" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="請登入相應權限帳號已解鎖">
                        <a href="mmtcheck_a.php?id=<?= $ammtm[$i]['id']?>" class="airconL2 airconL3" Disabled><?= $ammtmstatus?></a>
                        </span>
                    </td><!--狀態-->
                
                <?php } else { ?>
                    <td><?= $ammtmstatus?></td>
                <?php } ?>
                
                <td>
                    <span class="billBoardairconL2 billBoardairconL3" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="請登入相應權限帳號已解鎖">
                    <a href="mmtEdit_a.php?id=<?= $ammtm[$i]['id']?>" class="airconL2 airconL3" Disabled>內容編輯與修改</a>
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
                    echo '<td><a href="mmt_list_a.php?page=1">'."[第一頁]".'</a></td>';
                    echo "<td><a href=\"mmt_list_a.php?page={$prev}\">"."[<<<上一頁]".'</a></td>';
                }
                if ($page_num<$total_page) {
                    $next=$page_num+1;
                    echo "<td>"."<a href=\"mmt_list_a.php?page={$next}\">"."[下一頁>>>]".'</a></td>';
                    echo "<td><a href=\"mmt_list_a.php?page=$total_page\">".'[最末頁]'.'</a></td>';
                }
            echo '</tr>';
        echo '</table>';
        //分頁按鈕一次七頁
            $phpfile = 'mmt_list_a.php';
            $page= isset($_GET['page'])?$_GET['page']:1;        
            $getpageinfo = page($page,$total_num,$phpfile);
            echo '<div align="center">'; 
            echo @$getpageinfo['pagecode'];//顯示分頁的html語法
            echo '</div>';
        //分頁按鈕end
        // echo '<div class="container">';
        //     echo '<nav aria-label="Page navigation example" >';
        //             echo '<ul class="pagination justify-content-center">';
        //                     for ($i=1; $i <= $total_page; $i++) {
        //                         if ($i==$page_num) {
        //                             echo "<li class=\"page-item\"><span class='page-link text-danger' href=#><b>{$i}</b></span></li>";
        //                         } else {
        //                             echo "<li class=\"page-item\"><a class='page-link' href=\"mmt_list_a.php?page={$i}\">{$i}</a></li>";
        //                         }
        //                     }
        //             echo '</ul>';
        //     echo '</nav>';
        // echo '</div>'
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
        $("#mmt_a").tablesorter();
    })(document);
</script>
</body>
</html>