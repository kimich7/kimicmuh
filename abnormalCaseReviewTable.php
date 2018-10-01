<?php
include("php/CMUHconndata.php");
include("php/fun.php");
session_start();
$manageID=$_SESSION["login_number"];
$manageName=sql_database('cname','FA.Employee','e_number',$manageID);
//取得資訊
$case_id=$_GET['id'];
$case_id=str_replace("\"", "", $case_id);
$detailID=$_GET['detailID'];
$detailID=str_replace("\"", "", $detailID);
//取得各項資料
$select_str="SELECT * FROM FA.Abnormal_Notification_System_Master WHERE case_id = $case_id";
$select_query=$pdo->query($select_str)->fetchAll();
$detail_str="SELECT * FROM FA.Abnormal_Notification_System_Detail WHERE Detail_id = $detailID";
$detail_query=$pdo->query($detail_str)->fetchAll();
foreach ($select_query as $abnormalTableInfo) {
    $abMaster_id=$abnormalTableInfo["case_id"];//主表ID
    $abTitle=$abnormalTableInfo["case_title"];//標題
    $abLocal=$abnormalTableInfo["case_location"];//發生地點
    $abFindDate=$abnormalTableInfo["case_time"];//發現時間
    $abFindEmpID=$abnormalTableInfo["case_userID"];//發現人ID
    $abDescription=$abnormalTableInfo["case_description"];//問題描述
    $abPhotourl=$abnormalTableInfo["case_url"];//問題照片
    $abmanagerID=$abnormalTableInfo["case_manageID"];//主管ID
    $abDesEmpID=$abnormalTableInfo["work_emp"];//被指派人ID
}
foreach ($detail_query as $detail_info) {
    $abDetail_id=$detail_info["Detail_id"];//明細表id
    $adDesDate=$detail_info["Detail_Start_Time"];//指派時間
    $abEndDate=$detail_info["Detail_End_Time"];//完成時間
    $adAns=$detail_info["Detail_description"];//處理方式描述
    $abAnsPhoto=$detail_info["Detail_url"];//處理後照片
}
$abFindEmpName=sql_database('cname','FA.Employee','e_number',$abFindEmpID);//發現人員姓名
$abDesEmpName=sql_database('cname','FA.Employee','e_number',$abDesEmpID);//被指派人員姓名

if (isset($_POST["action"])&&($_POST["action"]=="check")) {    
    $case_id=$_POST["caseid"];//主表ID
    $confirmDate=date("Y-m-d");
    if (isset($_POST["mgrCheck"])) {
        $managerCheck=True;
    } else {
        $managerCheck=NULL;
    }
    $sql="UPDATE FA.Abnormal_Notification_System_Master SET  manage_status=:manage_status,confirm_date=:confirm_date WHERE case_id=:ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':manage_status',$managerCheck,PDO::PARAM_STR);
    $stmt->bindParam(':confirm_date',$confirmDate,PDO::PARAM_STR);
    $stmt->bindParam(':ID',$case_id,PDO::PARAM_INT);
    $stmt->execute();
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
    <title>異常處理明細</title>
</head>

<body class="table_bg">
    <?PHP
    //檢查項目
    echo '<div class="container border border-info mt-5">';
        echo '<form action="" method="post" name="abtable" enctype="multipart/form-data">';
            echo '<h2 class="text-center font-weight-bold">異常處理明細</h2>';
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
                    echo '<p class="d-inline font-weight-bold">處理人員：</p>';
                    echo '<p class="d-inline text-primary">'.$abDesEmpName.'</p>';
                echo '</div>';
            echo '</div>';
            echo '<div class="row my-3">';
                echo '<div class="col">';
                    echo '<p class="d-inline font-weight-bold">發現日期：</p>';
                    echo '<p class="d-inline text-primary">'.$abFindDate.'</p>';
                echo '</div>';
                echo '<div class="col text-center">';
                    echo '<p class="d-inline font-weight-bold">指派日期：</p>';
                    echo '<p class="d-inline text-primary">'.$adDesDate.'</p>';
                echo '</div>';
                
                echo '<div class="col text-right">';
                    echo '<p class="d-inline font-weight-bold">處理完成日期：</p>';
                    echo '<p class="d-inline text-primary">'.$abEndDate.'</p>';
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
                        echo '<tr>';
                            echo '<td>照片</td>';
                            echo '<td>'."<IMG SRC=\"".$abPhotourl."\" ALT='圖面顯示失敗'ALIGN='MIDDLE' BORDER=0 HSPACE=2 VSPACE=2 HEIGHT=640 WIDTH=640>". '</td>';
                        echo"</tr>";
                }
                echo '<tr>';    
                    echo '<td>處理情形描述</td>';
                    echo '<td>'.$adAns.'</td>';
                echo '</tr>';
                if ($abAnsPhoto) {
                    echo '<tr>';
                        echo '<td>改善後照片</td>';
                        echo '<td>'."<IMG SRC=\"".$abAnsPhoto."\" ALT='圖面顯示失敗'ALIGN='MIDDLE' BORDER=0 HSPACE=2 VSPACE=2 HEIGHT=640 WIDTH=640>". '</td>';
                    echo"</tr>";
                }
                echo '</tbody>';
            echo '</table>';
            echo '<div class="row my-3">';
                echo '<div class="col text-left">';
                echo '<p class="d-inline font-weight-bold"><h4>主管審核：</h4></p>';
                echo '<p class="d-inline text-primary"><h4>'.$manageName.'    '.'<input type="checkbox" name="mgrCheck" value="mgcheck" required>  主管確認</h4></p>';
                echo '</div>';
            echo '</div>';
        ?>
        <!-- 傳送值到資料庫中 -->
            <input type="hidden" name="action" value="check">
            <input type="hidden" name="caseid" value='<?= $abMaster_id ?>'>
            <input type="hidden" name="managerID" value='<?= $abmanagerID ?>'>
            <!-- 送出鈕 -->    
            <div class="d-flex justify-content-end">
                <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit">送出</button>&nbsp
                <a type="button" class="text-dark my-3 px-3 py-1 btn-outline-info" href="abnormalCaseReviewLink.php">返回</a>            
        </form>
    </div>
</body>
</html>