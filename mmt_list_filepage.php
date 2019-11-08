<?php
include("php/CMUHconndata.php");
include("php/fun.php");
include("paging.php");
//登入者
session_start();
$checkuser=$_SESSION["login_member"];
$checkuserID=sql_database('e_number','FA.Employee','cname',$checkuser);
$str_member="SELECT * FROM FA.Employee WHERE e_number='$checkuserID'";
$member=$pdo->query($str_member)->fetch();
$sdid=(int)$_GET['sdid'];
//總資料
//$ammtmstr="SELECT * FROM FA.MMT_AtableM ";
switch ($sdid) {
    case 1:
        $title='建築-其他清單';
        break;
    case 2:
        $title='電力-高低壓';
        break;
    case 3:
        $title='電力-UPS';
        break;
    case 4:
        $title='電力-發電機系統';
        break;
    case 5:
        $title='消防-滅火器';
        break;
    case 6:
        $title='氣體-年度檢查';
        break;
    case 7:
        $title='傳送-電梯半月保養';
        break;
    case 8:
        $title='傳送-小藥梯';
        break;
    case 9:
        $title='傳送-氣送';
        break;
    case 10:
        $title='監控系統';
        break;
    case 11:
        $title='水系統-排水設備';
        break;
    case 12:
        $title='水系統-汙水處理設備';
        break;
    case 13:
        $title='水系統-鍋爐年月保養';
        break;
    
}

$pageRow_record=10;//每頁的筆數
$page_num=1;//預設的頁數
//更新$page_num        
if (isset($_GET['page'])) {
    $page_num=$_GET['page'];
}
$startRow_record=($page_num-1)*$pageRow_record;
//所有的資料
$ammtmstr="SELECT fileNo,sdid,fileDownloadUserID,checkempId,fileDownloadDate,fileInsertDate,checkdate,eqlocation,fileCycle,filePath,remark,status FROM FA.MMA_getFile WHERE sdid=$sdid";//全部資料
//總資料數量
$ammtmnumstr="SELECT Count(fileNo)FROM FA.MMA_getFile WHERE sdid=$sdid ";
$ammtmnum=Current($pdo->query($ammtmnumstr)->fetch());//全部數量
$total_num=$ammtmnum;

//----還沒帶入----
//篩選後給每頁的筆數
$sqlstr_page="SELECT fileNo,sdid,fileDownloadUserID,checkempId,fileDownloadDate,fileInsertDate,checkdate,eqlocation,fileCycle,filePath,remark,status FROM FA.MMA_getFile WHERE sdid=$sdid ORDER BY fileInsertDate ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
$sql_page=$pdo->query($sqlstr_page);
// $sql_total=$pdo->query($sqlstr_total);
//$total_num=CURRENT($pdo->query($totalstr_num)->fetch());

//本頁開始的筆數
// $i=0;        
// $k=$i+2000;
// $ammtmQuery=$pdo->query($ammtmstr);
// $ammtmAll=array();
//----還沒帶入END----
if (isset($_POST["action"])&&($_POST["action"]=="check")) {
        // $total_num=$_POST["total_num"];
        // $checksum=$_POST["checksum"];
        $sdid=$_POST['sdid'];
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

    <!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <link rel="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"> -->


    <!-- 連結自己的JS -->
    <script src="./js/main.js"></script>
    <title><?= $title ?></title>
    
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

    <!--新增表單-->
    <form action="mmtCreate_filepage_choice.php" method="post" name="mmtca">
        <div class="panel-heading">
            <input type="hidden" name="mmtsysa" value='<?= $sdid ?>'>
            <div class="row my-3">                
                <div class="col">                    
                    <p class="d-inline font-weight-bold">&nbsp&nbsp&nbsp&nbsp新增上傳資料：
                        <span class="billBoardbuildL1 billBoardbuildL3" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="請登入相應權限帳號已解鎖">
                        <button type='submit' name="mmtsysabtn" class="btn btn-primary buildL1 buildL3" Disabled  >上傳</button>
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
    <!--新增表單end-->
    <form action="" method="post" name="mmtbuildother">
    <table id="mmt_a" class="display table table-striped table-bordered table-hover col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12 table-sm order-table"  aria-describedby="dataTables-example_info" data-sort-name="tid" data-sort-order="desc" data-sortable ="true"><!--表格樣式：條紋行、帶框表格、可滑入行-->
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
        //while ($row=$ammtmQuery->fetch()) {fileNo,fileTitle,fileDownloadDate,fileInsertDate,fileCycle,fileDownloadUserID,sdid,filePath,remark        
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
                        // echo '<td><a href="mmt_list_a.php?page=1">'."[第一頁]".'</a></td>';
                        // echo "<td><a href=\"mmt_list_a.php?page={$prev}\">"."[<<<上一頁]".'</a></td>';
                        echo "<td><a href='mmt_list_filepage.php?page=1\"&sdid=\"".$sdid."\"'>"."[第一頁]".'</a></td>';
                        echo "<td><a href='mmt_list_filepage.php?page={$prev}\"&sdid=\"".$sdid."\"'>"."[<<<上一頁]".'</a></td>';
                    }
                    if ($page_num<$total_page) {
                        $next=$page_num+1;
                        // echo "<td>"."<a href=\"mmt_list_a.php?page={$next}\">"."[下一頁>>>]".'</a></td>';
                        // echo "<td><a href=\"mmt_list_a.php?page=$total_page\">".'[最末頁]'.'</a></td>';
                        echo "<td>"."<a href='mmt_list_filepage.php?page={$next}\"&sdid=\"".$sdid."\"'>"."[下一頁>>>]".'</a></td>';
                        echo "<td><a href='mmt_list_filepage.php?page=$total_page\"&sdid=\"".$sdid."\"'>".'[最末頁]'.'</a></td>';
                    }
                echo '</tr>';
            echo '</table>';
            //分頁按鈕一次七頁                
                $phpfile = 'mmt_list_filepage.php';
                $page= isset($_GET['page'])?$_GET['page']:1;        
                $getpageinfo = page($page,$total_num,$phpfile,$sdid);
                echo '<div align="center">'; 
                echo @$getpageinfo['pagecode'];//顯示分頁的html語法
                echo '</div>';
            //分頁按鈕end       
    ?>
    <!-- 送出鈕 -->
        <div class="d-flex justify-content-end">
            <div>
                <button class="my-3 px-3 py-1 btn-outline-info text-dark buildL3" type="submit" Disabled>送出</button>&nbsp&nbsp                            
            </div>
            <div>
                <a href='index.html' type='button' class="my-3 px-3 py-1 btn-outline-info text-dark">離開</a>
            </div>
        </div>
        <input type="hidden" name="action" value="check">
        <input type="hidden" name="sdid" value='<?= $sdid ?>'>
    </form> 

    <!--filter-->
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
        <input type="hidden" name="sdid" value='<?= $sdid ?>'>
        <input type="hidden" name="action" value="new_page">
        <input type="hidden" name="total_num" value="<?= $total_num?>">           
    </form>
    <!--filter end-->

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