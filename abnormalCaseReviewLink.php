<?php
/**本頁是異常事件待審核清單**/
    include("php/CMUHconndata.php");
    include("php/fun.php");
    session_start();
    $userID=$_SESSION["login_number"];
    if (isset($_POST["action"])&&($_POST["action"]=="check")) {
         $total_num=$_POST["total_num"];
         $confirmDate=date("Y-m-d");
        for ($i=0; $i<$total_num ; $i++) { 
            $j=$i+1000;
            $k=$i+2000;                    
            $a=$i;
            if (isset($_POST["$j"])) {
                $managerCheck=True;
            } else {
                $managerCheck=NULL;
            }            
            if (isset($_POST["$k"])) {
                $rdID=$_POST["$k"];
            }else{
                break;
            }            
            $sql="UPDATE FA.Abnormal_Notification_System_Master SET  manage_status=:manage_status,confirm_date=:confirm_date WHERE case_id=:ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':manage_status',$managerCheck,PDO::PARAM_STR);
            $stmt->bindParam(':confirm_date',$confirmDate,PDO::PARAM_STR);            
            $stmt->bindParam(':ID',$rdID,PDO::PARAM_INT);
            $stmt->execute();
            $j=$i+1000;
            $k=$i+2000;     
        }
        $pdo=null;
        header("Location:index.html");
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
        <h1 class="text-center">異常事件待審核清單</h1>
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
        $sqlstr_total="SELECT a.case_id,a.case_time,a.case_location,a.case_title,a.case_userID,a.work_emp,a.manage_status,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master AS a INNER JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id  WHERE a.manage_status IS NULL AND b.Detail_End_Time IS NOT NULL";
        //篩選後給每頁的筆數
        $sqlstr_page="SELECT a.case_id,a.case_time,a.case_location,a.case_title,a.case_userID,a.work_emp,a.manage_status,b.Detail_id,b.Detail_Start_Time,b.Detail_End_Time FROM FA.Abnormal_Notification_System_Master AS a INNER JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id  WHERE a.manage_status IS NULL AND b.Detail_End_Time IS NOT NULL ORDER BY a.case_id ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
        //總資料數量
        $totalstr_num="SELECT COUNT(a.case_id) FROM FA.Abnormal_Notification_System_Master AS a INNER JOIN FA.Abnormal_Notification_System_Detail AS b ON a.case_id=b.case_id  WHERE a.manage_status IS NULL AND b.Detail_End_Time IS NOT NULL";        
    
        $sql_page=$pdo->query($sqlstr_page);
        $sql_total=$pdo->query($sqlstr_total);
        $total_num=CURRENT($pdo->query($totalstr_num)->fetch());
        $pdo=null;
        //本頁開始的筆數
        $i=0;  
        $j=$i+1000;      
        $k=$i+2000;
        echo '<table border="1" align="center" width="80%">';
        echo '<thead align="center">';
        echo '<th width="10%">發現日期</th>';
        echo '<th width="15%">發現地點</th>';
        echo '<th width="36%">標   題</th>';
        echo '<th width="7%">發現人員</th>';
        echo '<th width="7%">指派人員</th>';
        echo '<th width="10%">指派日期</th>';
        echo '<th width="10%">完成日期</th>';
        echo '<th width="5%">審   核</th>';     
        echo '</thead>';
        echo '<tbody>';
        $mgcheck="mgcheck";
        while ($data_page = $sql_page->fetch()) {
            $desemp=$i;
            $case_id=$data_page["case_id"];
            $case_date=$data_page["case_time"];//發現日期
            $case_Location=$data_page["case_location"];//發生地點
            $case_title=$data_page["case_title"];//標題            
            $csaeFindEmpID=$data_page["case_userID"];//發現人員
            $userDesID=$data_page["work_emp"];//指派人員
            $DesDate=$data_page["Detail_Start_Time"];//指派日期
            $EndDate=$data_page["Detail_End_Time"];//完成日期
            $detail=$data_page["Detail_id"];//明細表id
            $userFindName=sql_database('cname','FA.Employee','e_number',$csaeFindEmpID);//發現人員
            $userDesName=sql_database('cname','FA.Employee','e_number',$userDesID);//被指派人員
            echo '<tr>';            
                //顯示發現日期
                echo '<td align="center">';
                    echo $case_date;
                echo '</td>';
                //顯示發現地點
                echo '<td align="center">';
                    echo $case_Location;
                echo '</td>';
                //顯示事件名稱，且需要的話可以點進去看詳細情形
                echo "<td align='center'><a href='abnormalCaseReviewTable.php?id=\"".$case_id."\"&detailID=\"".$detail."\"' class=\".list-group-item list-group-item-action.\">".$case_title.'</a></td>';
                //顯示發現異常事件人員
                echo '<td align="center">';
                    echo $userFindName;
                echo '</td>';
                //顯示發現被指派人員
                echo '<td align="center">';
                    echo $userDesName;
                echo '</td>';
                //顯示指派日期
                echo '<td align="center">';
                    echo $DesDate;
                echo '</td>';
                //顯示完成日期
                echo '<td align="center">';
                    echo $EndDate;
                echo '</td>';
                //審核欄位
                echo '<td align="center">';
                    if ($data_page["manage_status"]) {
                        echo "<input type='checkbox' class='employeeCheck' name=\"".$j."\" value=\"".$mgcheck."\" CHECK>";
                    } else {
                        echo "<input type='checkbox' class='employeeCheck' name=\"".$j."\" value=\"".$mgcheck."\" required>";
                    }
                    
                echo '</td>';
                $q=$case_id;
                echo "<input type='hidden' name=\"".$k."\" value=\"".$q."\">";//傳遞case_id
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
            <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit">送出</button>&nbsp;
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