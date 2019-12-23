<?php
/*本頁是異常事件Table*/
include("php/CMUHconndata.php");
include("php/fun.php");
session_start();
$manageID=$_SESSION["login_number"];
//取得資訊
$case_id=$_GET['id'];
$case_id=str_replace("\"", "", $case_id);
//取得各項資料
$select_str="SELECT * FROM FA.Abnormal_Notification_System_Master WHERE case_id = $case_id";
$select_query=$pdo->query($select_str)->fetchAll();
foreach ($select_query as $abnormalTableInfo) {
    $abMaster_id=$abnormalTableInfo["case_id"];
    $abTitle=$abnormalTableInfo["case_title"];
    $abLocal=$abnormalTableInfo["case_location"];
    $abFindDate=$abnormalTableInfo["case_time"];
    $abFindEmpID=$abnormalTableInfo["case_userID"];
    $abDescription=$abnormalTableInfo["case_description"];
    $abPhotourl=$abnormalTableInfo["case_url"];
}
$abFindEmpName=sql_database('cname','FA.Employee','e_number',$abFindEmpID);
if (isset($_POST["action"])&&($_POST["action"]=="check")) {
        $case_id=$_POST["caseid"];
        $desDate=date("Y-m-d");
        if ($_POST["desemp"]) {
            $desEpmID=$_POST["desemp"];
            $sql="UPDATE FA.Abnormal_Notification_System_Master SET manage_status='M' ,work_emp=:work_emp ,case_manageID=:case_manageID WHERE case_id=:ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':work_emp',$desEpmID,PDO::PARAM_STR);
            $stmt->bindParam(':case_manageID',$manageID,PDO::PARAM_STR);
            $stmt->bindParam(':ID',$case_id,PDO::PARAM_INT);
            $stmt->execute();
            $sqlinsert="INSERT INTO FA.Abnormal_Notification_System_Detail(Detail_Start_Time,work_emp,case_id)VALUES('$desDate','$desEpmID',$case_id)";
            $insert_detail =$pdo->exec($sqlinsert);
            $pdo=null;
            header("Location:index.html");
        } else {
            echo '<h3>未指派人員請重新填寫，或按返回建</h3>';
            header("Refresh:5;url=abnormalTable.php?id=$abMaster_id");
        }
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
    <title>異常案件表單</title>
</head>

<body class="table_bg">
    <?PHP
    //檢查項目
    echo '<div class="container border border-info mt-5">';
        echo '<form action="" method="post" name="abtable">';
            echo '<h2 class="text-center font-weight-bold">異常事件</h2>';
            //地點/檢查者/日期欄
            echo '<div class="row my-3">';
                echo '<div class="col">';
                    echo '<p class="d-inline font-weight-bold">發現地點：</p>';
                    echo '<p class="d-inline text-primary">'.$abLocal.'</p>';
                echo '</div>';
                echo '<div class="col text-center">';
                    echo '<p class="d-inline font-weight-bold">發現人員：</p>';
                    echo '<p class="d-inline text-primary">'.$abFindEmpName.'</p>';
                echo '</div>';
                echo '<div class="col text-right">';
                    echo '<p class="d-inline font-weight-bold">發現日期：</p>';
                    echo '<p class="d-inline text-primary">'.$abFindDate.'</p>';
                echo '</div>';
            echo '</div>';
            //表格主體
            echo '<table class="table my-5">';
                echo '<thead>';
                    echo '<th>項目</th>';                   
                    echo '<th>內容</th>';
                echo '</thead>';
                echo '<tbody class="text-primary">';
                echo '<tr>';    
                    echo '<td>標題</td>';
                    echo '<td>'.$abTitle.'</td>';
                echo '</tr>';
                echo '<tr>';    
                    echo '<td>問題描述</td>';
                    echo '<td>'.$abDescription.'</td>';
                echo '</tr>';
                if ($abPhotourl) {
                    $checkstr=substr($abPhotourl, -4);
                    $checkstr4=substr($abPhotourl, -5);
                    if ($checkstr=='.jpg' or $checkstr=='.gif' or $checkstr=='.png' or $checkstr4=='.jpeg') {
                        echo '<tr>';
                            echo '<td>附件</td>';
                            echo '<td>'."<IMG SRC=\"".$abPhotourl."\" ALT='圖面顯示失敗'ALIGN='MIDDLE' BORDER=0 HSPACE=2 VSPACE=2 HEIGHT=640 WIDTH=640>". '</td>';
                        echo"</tr>";
                    } else {
                        $filename='http://localhost/CMUH/'.$abPhotourl;
                        echo '<tr>';
                            echo '<td>附件</td>';
                            echo '<td>檔案非圖片檔，是否要開啟</BR></BR>'."<input type='button' value='開啟檔案' class='text-dark my-3 px-3 py-1 btn-outline-info' onclick = 'window.open(\"".$filename."\")'>".'</td>';        
                        echo"</tr>";
                    }                       
                }
                echo '</tbody>';
            echo '</table>';

            echo '<div class="input-group">';
                echo '<div class="input-group-prepend">';
                    echo '<span class="input-group-text"><h3>指派處理的人員：</h3></span>';
                echo '</div>';
                echo '<table class="table my-5">';
                    echo '<tr>';
                        echo '<td>請選人員：</td>';
                        echo '<td align="center">';
                        echo '<div class="form-group">';
                        echo "<select class='form-control mb-3 desemp' id='desemp' name='desemp'>";
                        echo '<option selected>請選擇人員</option>';
                        echo '</select>';
                        echo '</div>'; 
                    echo '</td>';
                    echo '</tr>';
                echo '</table>';
            echo '</div>';
        ?>
            <!-- 傳送值到資料庫中 -->
            <input type="hidden" name="action" value="check">
            <input type="hidden" name="caseid" value='<?= $abMaster_id ?>'>
            <!-- 送出鈕 -->    
            <div class="d-flex justify-content-end">
                <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit">送出</button>&nbsp
                <a type="button" class="text-dark my-3 px-3 py-1 btn-outline-info" href="abnormalLink.php">返回</a>            
        </form>
    </div>
</body>
</html>