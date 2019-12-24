<?php
include("php/CMUHconndata.php");
include("php/fun.php");
include("php/errfun.php");
include("page_err_app.php");
$filter=$_GET['filter'];
session_start();
$userID=$_SESSION["login_number"];

$pageRow_record=10;//每頁的筆數
$page_num=1;//預設的頁數

$href="pmc.php?filter=no";
//更新$page_num        
if (isset($_GET['page'])) {
    $page_num=$_GET['page'];
    $page_num=str_replace("\"", "", $page_num);
}
$startRow_record=($page_num-1)*$pageRow_record;
if ($filter=='no') {
    //總資料
    $sqlstr_total="SELECT * FROM FA.pmc order by createdOn DESC ";
    //篩選後給每頁的筆數
    $sqlstr_page="SELECT * FROM FA.pmc ORDER BY createdOn DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
    //總資料數量
    $totalstr_num="SELECT COUNT(id) FROM FA.pmc";     
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
                    $sqlstr_total="SELECT DISTINCT(a.id), a.* FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id  where ( c.cname like '%$keyword%' or a.category like '%$keyword%' or a.title like '%$keyword%' or a.createdOn like '%$keyword%' or a.process like '%$keyword%' or a.content like '%$keyword%' or a.contract like '%$keyword%' or a.building like '%$keyword%' or a.endon like '%$keyword%' or s.checkName like '%$keyword%')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.id), a.* FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id   where ( c.cname like '%$keyword%' or a.category like '%$keyword%' or a.title like '%$keyword%' or a.createdOn like '%$keyword%' or a.process like '%$keyword%' or a.content like '%$keyword%' or a.contract like '%$keyword%' or a.building like '%$keyword%' or a.endon like '%$keyword%' or s.checkName like '%$keyword%') ORDER BY a.createdOn DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                   //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.id)) FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id   where ( c.cname like '%$keyword%' or a.category like '%$keyword%' or a.title like '%$keyword%' or a.createdOn like '%$keyword%' or a.process like '%$keyword%' or a.content like '%$keyword%' or a.contract like '%$keyword%' or a.building like '%$keyword%' or a.endon like '%$keyword%' or s.checkName like '%$keyword%')";
                    break;
                case 2://只有日期區間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.id), a.* FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id   where (a.createdOn between '$start_date' and '$end_date' or a.endon between '$start_date' and '$end_date')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.id), a.* FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id   where (a.createdOn between '$start_date' and '$end_date' or a.endon between '$start_date' and '$end_date') ORDER BY a.createdOn DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.id)) FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id   where (a.createdOn between '$start_date' and '$end_date' or a.endon between '$start_date' and '$end_date')";
                    break;
                case 3://只有開始時間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.id),a.* FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id   where (a.createdOn ='$start_date' or a.endon ='$start_date')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.id), a.* FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id  where (a.createdOn ='$start_date' or a.endon ='$start_date') ORDER BY a.createdOn DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.id)) FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id  where (a.createdOn ='$start_date' or a.endon ='$start_date')";
                    break;
                case 4://只有結束時間
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.id), a.* FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id  where (a.createdOn ='$end_date' or a.endon ='$end_date')";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.id), a.* FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id  where (a.createdOn ='$end_date' or a.endon ='$end_date') ORDER BY a.createdOn DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.id)) FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id  where (a.createdOn ='$end_date' or a.endon ='$end_date')";
                    break;
                case 5://開始+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.id), a.* FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id  where ((a.createdOn ='$start_date'or a.endon= '$start_date') and (c.cname like '%$keyword%' or a.category like '%$keyword%' or a.title like '%$keyword%' or a.process like '%$keyword%' or a.content like '%$keyword%' or a.contract like '%$keyword%' or a.building like '%$keyword%' or s.checkName like '%$keyword%'))";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.id), a.* FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id  where ((a.createdOn ='$start_date'or a.endon = '$start_date') and (c.cname like '%$keyword%' or a.category like '%$keyword%' or a.title like '%$keyword%' or a.process like '%$keyword%' or a.content like '%$keyword%' or a.contract like '%$keyword%' or a.building like '%$keyword%' or s.checkName like '%$keyword%')) ORDER BY a.createdOn DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.id)) FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id  where ((a.createdOn ='$start_date'or a.endon = '$start_date') and (c.cname like '%$keyword%' or a.category like '%$keyword%' or a.title like '%$keyword%' or a.process like '%$keyword%' or a.content like '%$keyword%' or a.contract like '%$keyword%' or a.building like '%$keyword%' or s.checkName like '%$keyword%'))";
                    break;
                case 6://結束+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.id), a.* FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id  where ((a.createdOn='$end_date'or a.endon = '$end_date')  and (c.cname like '%$keyword%' or a.category like '%$keyword%' or a.title like '%$keyword%' or a.process like '%$keyword%' or a.content like '%$keyword%' or a.contract like '%$keyword%' or a.building like '%$keyword%' or s.checkName like '%$keyword%'))";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.id), a.* FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id  where ((a.createdOn='$end_date'or a.endon = '$end_date')  and (c.cname like '%$keyword%' or a.category like '%$keyword%' or a.title like '%$keyword%' or a.process like '%$keyword%' or a.content like '%$keyword%' or a.contract like '%$keyword%' or a.building like '%$keyword%' or s.checkName like '%$keyword%')) ORDER BY a.createdOn DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.id)) FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id  where ((a.createdOn='$end_date'or a.endon = '$end_date')  and (c.cname like '%$keyword%' or a.category like '%$keyword%' or a.title like '%$keyword%' or a.process like '%$keyword%' or a.content like '%$keyword%' or a.contract like '%$keyword%' or a.building like '%$keyword%' or s.checkName like '%$keyword%'))";
                    break;
                case 7://日期區間+keyword
                    //所有的資料 (check_manager IS NULL or check_manager=0) and
                    $sqlstr_total=" SELECT DISTINCT(a.id), a.* FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id  where ( (a.createdOn between '$start_date' and '$end_date' or a.endon between '$start_date' and '$end_date')and (c.cname like '%$keyword%' or a.category like '%$keyword%' or a.title like '%$keyword%' or a.process like '%$keyword%' or a.content like '%$keyword%' or a.contract like '%$keyword%' or a.building like '%$keyword%' or s.checkName like '%$keyword%'))";
                    //篩選後給每頁的筆數
                    $sqlstr_page="SELECT DISTINCT(a.id), a.* FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id  where ( (a.createdOn between '$start_date' and '$end_date' or a.endon between '$start_date' and '$end_date')and (c.cname like '%$keyword%' or a.category like '%$keyword%' or a.title like '%$keyword%' or a.process like '%$keyword%' or a.content like '%$keyword%' or a.contract like '%$keyword%' or a.building like '%$keyword%' or s.checkName like '%$keyword%')) ORDER BY a.createdOn DESC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
                    //總資料數量
                    $totalstr_num="SELECT COUNT(DISTINCT(a.id)) FROM FA.pmc as a LEFT JOIN FA.Employee as c on a.e_number=c.e_number LEFT JOIN FA.CheckStatus as s on a.status=s.id  where ( (a.createdOn between '$start_date' and '$end_date' or a.endon between '$start_date' and '$end_date')and (c.cname like '%$keyword%' or a.category like '%$keyword%' or a.title like '%$keyword%' or a.process like '%$keyword%' or a.content like '%$keyword%' or a.contract like '%$keyword%' or a.building like '%$keyword%' or s.checkName like '%$keyword%'))";
                    break;                
            }
}

//$pmcQuery=$pdo->query($sqlstr_total);
//$pmcAll=array();
//$total_numstr="SELECT Count(id)FROM FA.pmc ";
//$total_num=Current($pdo->query($totalstr_num)->fetch());

$sql_page=$pdo->query($sqlstr_page);
$sql_total=$pdo->query($sqlstr_total);
$total_num=CURRENT($pdo->query($totalstr_num)->fetch());
$pdo=null;


while ($row=$sql_page->fetch()) {
    $pmc[]=array(
        'id'=>$row["id"],//案件單號
        'e_number'=>$row["e_number"],//(承辦人/指派人員)id
        'category'=>$row["category"],//案件類型
        'title'=>$row["title"],//案件名稱(標題)
        'createdOn'=>$row["createdOn"],//交派日期
        'process'=>$row["process"],//流程進度日期及金額
        'content'=>$row["content"],//工程進度/異常說明
        'contract'=>$row["contract"],//發包日期.決包廠商.金額
        'building'=>$row["building"],//案件大樓
        'endon'=>$row["endon"],//結束日期
        'status'=>$row["status"]//狀態(W/P/H/F/D 尚未受理/處理中/部分完成/完成/作廢)
    );    
}
$pmcnum=Count($pmc);
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
    <div class="panel-heading">
            <h5>&nbsp&nbsp&nbsp&nbsp新增專案：<a class="btn btn-primary" href="pmcCreate.php" class="text-dark">新增</a></h5> 
    </div>
    <?php        
    
    if ($filter=='yes') {?>
    <div class="d-flex ">        
        <a href='pmc.php?filter=no' type="button" id="backlistmmt" class="btn btn-primary mt-4 rounded d-block mr-3">清除條件</a>
    </div>
    <?php }?>
    <div id="right_area">
    <div class="row">
        <div class="col-lg-12">
        <table id="pmclist" class="table table-striped table-bordered table-hover col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12 table-sm"><!--表格樣式：條紋行、帶框表格、可滑入行-->
        <thead  class="thead-light">
            <tr align="center">
            <th scope="col" width="10%">單號</th>
            <th scope="col" width="5%">承辦人</th>
            <th scope="col">工程名稱</th>
            <th scope="col">交辦日期</th>
            <th scope="col">棟別</th>
            <th scope="col">狀態</th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        <?php for ($i=0; $i <$pmcnum ; $i++) {
            //顯示資料的轉換 
            $pmcemp=sql_database('cname','FA.Employee','e_number',$pmc[$i]['e_number']);
            switch ($pmc[$i]['status']) {
                case 'W':
                    $pmcstatus='尚未處理';
                    break;
                case NULL:
                    $pmcstatus='尚未處理';
                    break;
                case '':
                    $pmcstatus='尚未處理';
                    break;
                case 'P':
                    $pmcstatus='處理中';
                    break;
                case 'F':
                    $pmcstatus='完成/結案';
                    break;
            }
            ?>
            <tr align="center">
                <th scope="row"><?= $pmc[$i]['id']?></th>                
                <td><?= $pmcemp?></td>
                <td><a href="pmcDetail.php?id=<?= $pmc[$i]['id']?>"><?= $pmc[$i]['title']?></a></td>
                <td><?= $pmc[$i]['createdOn']?></td>
                <td><?= $pmc[$i]['building']?></td>
                <td><?= $pmcstatus?></td>
                <td><a href="pmcEdit.php?id=<?= $pmc[$i]['id']?>">內容編輯與修改</a></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php
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
                    echo '<td><a href="pmc.php?filter=no&page=1">'."[第一頁]".'</a></td>';
                    echo "<td><a href=\"pmc.php?page={$prev}\">"."[<<<上一頁]".'</a></td>';
                }
                if ($page_num<$total_page) {
                    $next=$page_num+1;
                    echo "<td>"."<a href=\"pmc.php?filter=no&page={$next}\">"."[下一頁>>>]".'</a></td>';
                    echo "<td><a href=\"pmc.php?filter=no&page=$total_page\">".'[最末頁]'.'</a></td>';
                }
            echo '</tr>';
        echo '</table>';
         //分頁按鈕一次七頁
            $phpfile = 'pmc.php';
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
                    echo "<td><a href='pmc.php?filter=yes&page=1\"&start_date=\"".$start_date."\"&end_date=\"".$end_date."\"&keywordsearch=\"".$keyword."\"&action=next_page'>"."[第一頁]".'</a></td>';
                    echo "<td><a href='pmc.php?filter=yes&page={$prev}\"&start_date=\"".$start_date."\"&end_date=\"".$end_date."\"&keywordsearch=\"".$keyword."\"&action=next_page'>"."[<<<上一頁]".'</a></td>';
                    
                }
                if ($page_num<$total_page) {
                    $next=$page_num+1;
                    echo "<td>"."<a href='pmc.php?filter=yes&page={$next}\"&start_date=\"".$start_date."\"&end_date=\"".$end_date."\"&keywordsearch=\"".$keyword."\"&action=next_page'>"."[下一頁>>>]".'</a></td>';
                    echo "<td><a href='pmc.php?filter=yes&page=$total_page\"&start_date=\"".$start_date."\"&end_date=\"".$end_date."\"&keywordsearch=\"".$keyword."\"&action=next_page'>".'[最末頁]'.'</a></td>';
                    
                }
            echo '</tr>';
        echo '</table>';
         //分頁按鈕一次七頁
            $phpfile = 'pmc.php';
            $page= isset($_GET['page'])?$_GET['page']:1;        
            $getpageinfo = pageerrapp($page,$total_num,$phpfile,$start_date,$end_date,$keyword);
            echo '<div align="center">'; 
            echo $getpageinfo['pagecode'];//顯示分頁的html語法
            echo '</div>';
        }?>
        </div>
    </div>
    </div>
    <form action="pmc.php" method="get" name="mtlist"> 
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
            $("#pmclist").tablesorter();            
        })(document);
    </script>  
    <!-- footer網頁尾頁 -->
    <footer>
        <div id="footer"></div>
    </footer>
</body>
</html>