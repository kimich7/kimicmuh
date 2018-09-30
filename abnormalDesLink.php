<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    session_start();
    $userID=$_SESSION["login_number"];
   if (isset($_POST["action"])&&($_POST["action"]=="check")) {

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
    <title>尚未指派人員異常事件清單</title>
</head>
<body>
    <!-- header網頁標題 -->
    <header>
       <div id="header"></div>
    </header>
    <form action="" method="post" name="abnormallist">
    <section class="container-fluid">
        <h1 class="text-center">尚未指派處理人員的異常事件清單</h1>
        <div class="list-group mx-5 my-5">
        <?php
        $pageRow_record=10;//每頁的筆數
        $page_num=1;//預設的頁數
        //更新$page_num        
        if (isset($_GET['page'])) {
            $page_num=$_GET['page'];
        }
        $startRow_record=($page_num-1)*$pageRow_record;
        //所有的資料
        $sqlstr_total="SELECT case_id,case_title,case_time,case_userID,case_manageID FROM FA.Abnormal_Notification_System_Master WHERE work_emp ='$userID'";
        //篩選後給每頁的筆數
        $sqlstr_page="SELECT case_id,case_title,case_time,case_userID,case_manageID FROM FA.Abnormal_Notification_System_Master WHERE work_emp ='$userID' ORDER BY case_id ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
        //總資料數量
        $totalstr_num="SELECT COUNT(case_id) FROM FA.Abnormal_Notification_System_Master WHERE work_emp = '$userID'";        
    
        $sql_page=$pdo->query($sqlstr_page);
        $sql_total=$pdo->query($sqlstr_total);
        $total_num=CURRENT($pdo->query($totalstr_num)->fetch());
        $pdo=null;
        //本頁開始的筆數
        $i=0;        
        $k=$i+2000;
        echo '<table border="1" align="center" width="80%">';
        echo '<thead align="center">';
        echo '<th width="10%">日  期</th>';
        echo '<th width="45%">案件名稱</th>';
        echo '<th width="10%">發現人員</th>';
        echo '<th width="10%">指派主管</th>';
       
        echo '</thead>';
        echo '<tbody>';
        $mgcheck="mgcheck";
        $mbcheck="mbcheck";
        while ($data_page = $sql_page->fetch()) {
            $desemp=$i;
            $case_id=$data_page["case_id"];
            $case_title=$data_page["case_title"];
            $case_date=$data_page["case_time"];
            $csaeFindEmpID=$data_page["case_userID"];
            $manageID=$data_page["case_manageID"];
            $manageName=sql_database('cname','FA.Employee','e_number',$manageID);
            echo '<tr>';
            $findEmpName=sql_database('cname','FA.Employee','e_number',$csaeFindEmpID);
                //顯示發現日期
                echo '<td width="10%" align="center">';
                    echo $case_date;
                echo '</td>';
                //顯示事件名稱，且需要的話可以點進去看詳細情形
                echo "<td align='center'><a href='abnormalDesTable.php?id=\"".$case_id."\"' class=\".list-group-item list-group-item-action.\">".$case_title.'</a></td>';
                //顯示發現異常事件人員
                echo '<td width="10%" align="center">';
                    echo $findEmpName;
                echo '</td>';
                echo '<td width="10%" align="center">';
                    echo $manageName;
                echo '</td>';
                $q=$case_id;
                echo "<input type='hidden' name=\"".$k."\" value=\"".$q."\">";//傳遞case_id
            $i++;
            $k++;
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        
        //計算總頁數
        $total_page=ceil($total_num/$pageRow_record);
        echo '<table border="0" align="center">';    
            echo '<tr>';
                echo '<td><h5>'.'您尚未完成的異常處理事件共'.$total_num.'件(共'.$total_page.'頁)'.'</h5></td>';
            echo '</tr>';
        echo '</table>';
        echo '<table border="0" align="center">';
            echo '<tr>';
                if ($page_num>1) {
                    $prev=$page_num-1;
                    echo '<td><a href="abnormalLink.php?page=1">'."[第一頁]".'</a></td>';
                    echo "<td><a href=\"abnormalLink.php?page={$prev}\">"."[<<<上一頁]".'</a></td>';
                }
                if ($page_num<$total_page) {
                    $next=$page_num+1;
                    echo "<td>"."<a href=\"abnormalLink.php?page={$next}\">"."[下一頁>>>]".'</a></td>';
                    echo "<td><a href=\"abnormalLink.php?page=$total_page\">".'[最末頁]'.'</a></td>';
                }
            echo '</tr>';
        echo '</table>';
        echo '<nav aria-label="Page navigation example" >';
                echo '<ul class="pagination">';
                        for ($i=1; $i <= $total_page; $i++) {
                            if ($i==$page_num) {
                                echo "<li class=\"page-item\"><span class='page-link text-danger' href=#><b>{$i}</b></span></li>";
                            } else {
                                echo "<li class=\"page-item\"><a class='page-link' href=\"abnormalLink.php?page={$i}\">{$i}</a></li>";
                            }
                        }
                echo '</ul>';
        echo '</nav>';
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
    <!-- footer網頁尾頁 -->
    <footer>
        <div id="footer"></div>
    </footer>
</body>
</html>