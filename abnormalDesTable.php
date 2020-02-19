<?php
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
    $abmanagerID=$abnormalTableInfo["case_manageID"];
    
}
$abFindEmpName=sql_database('cname','FA.Employee','e_number',$abFindEmpID);

$selectD_str="SELECT * FROM FA.Abnormal_Notification_System_Detail WHERE case_id = $case_id";
$selectD_query=$pdo->query($selectD_str)->fetchAll();
foreach ($selectD_query as $abnormalTableInfoD) {
    $Detail_id=$abnormalTableInfoD["Detail_id"];
    $Detail_Start_Time=$abnormalTableInfoD["Detail_Start_Time"];
    $Detail_End_Time=$abnormalTableInfoD["Detail_End_Time"];
    @$work_emp=$abnormalTableInfoD["work_emp"];
    $Detail_description=$abnormalTableInfoD["Detail_description"];
    $Detail_url=@$abnormalTableInfoD["Detail_url"];    
}

if (empty($work_emp)) {
    echo '<h3>本案件尚未指派處理人員3秒後會回到總覽清單頁面</h3>';
    header("Refresh:3;url=abnormalOverview.php?filter=no");

}

if (isset($_POST["action"])&&($_POST["action"]=="check")) {    
        $case_id=$_POST["caseid"];//主表ID
        $endDate=date("Y-m-d");        
        $abmanagerID=$_POST["managerID"];
        if ($_POST["ans"]) {
            $ans=$_POST["ans"];
            $ans=str_replace(chr(13).chr(10), "<br />",$ans);
            // $qc=array();
            // $ans_no=array();
            // $qc=$_FILES['file'];
            // $ans_no= implode(",", $qc['name']);
            // print_r($ans_no);

            if($_FILES['file']['error']>0){
                $location=NULL;
            }else{                
                $location='abnormalPhoto/'.$_FILES['file']['name'];
                move_uploaded_file($_FILES['file']['tmp_name'], 'abnormalPhoto/'.$_FILES['file']['name']);
            }
            $sql="UPDATE FA.Abnormal_Notification_System_Detail SET Detail_description=:ans ,Detail_End_Time=:endDate,Detail_url=:url_location WHERE case_id=:ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':ans',$ans,PDO::PARAM_STR);
            $stmt->bindParam(':endDate',$endDate,PDO::PARAM_STR);
            $stmt->bindParam(':url_location',$location,PDO::PARAM_STR);
            $stmt->bindParam(':ID',$case_id,PDO::PARAM_INT);
            $stmt->execute();
            $pdo=null;
            header("Location:index.html");
        } else {
            echo '<h3>未填寫處理情形，案件不算完成，請重新填寫，或按返回建</h3>';
            header("Refresh:5;url=abnormalDesTable.php?id=$abMaster_id");
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
        echo '<form action="" method="post" name="abtable" enctype="multipart/form-data">';
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
                @$Detail_description=str_replace("<br />", "\n", $Detail_description);
                echo '<tr>';    
                    echo '<td>問題處理描述</td>';
                    
                    if (@$work_emp==$manageID) {
                        echo '<td><textarea class="form-control" name="ans" aria-label="With textarea" rows="5" required>'.$Detail_description.'</textarea></td>';
                    } else {
                        echo '<td><textarea class="form-control" name="ans" aria-label="With textarea" rows="5" required Disabled>'.str_replace("<br/>", "\n",'非該案件負責人無法修改<br/>').$Detail_description.'</textarea></td>';
                    }
                echo '</tr>';
                if (@$Detail_url) {
                    $filename='http://localhost/CMUH/'.$Detail_url;
                        echo '<tr>';
                            echo '<td>附件</td>';
                            echo '<td>檔案是否要開啟</BR></BR>'."<input type='button' value='開啟檔案' class='text-dark my-3 px-3 py-1 btn-outline-info' onclick = 'window.open(\"".$filename."\")'>".'</td>';        
                        echo"</tr>";
                }
                
                echo '<tr>';    
                    echo '<td><span>處理後檔案上傳(非必填)：</span></td>';
                    if (@$work_emp==$manageID) {
                        echo "<td><h4><input type='file' class='form-control-file' id='FormControlFile1' name='file'></h4></td>";
                    } else {
                        echo "<td><h4><input type='file' class='form-control-file' id='FormControlFile1' name='file' Disabled></h4></td>";
                    }
                echo '</tr>';
                echo '</tbody>';
            echo '</table>';
        ?>
        <!-- 傳送值到資料庫中 -->
            <input type="hidden" name="action" value="check">
            <input type="hidden" name="caseid" value='<?= $abMaster_id ?>'>
            <input type="hidden" name="managerID" value='<?= $abmanagerID ?>'>
            <!-- 送出鈕 -->    
            <div class="d-flex justify-content-end">
                <?php 
                if (@$work_emp==$manageID) {?>
                    <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit">送出</button>&nbsp
                <?php } else {?>
                    <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit" title="非負責人無法編輯，請由下方返回按鈕返回" Disabled>送出</button>&nbsp
                <?php } ?>
                <span class="billBoarderrL3" tabindex="0" data-toggle="tooltip" data-placement="bottom" title="非主管無法進入審核清單，請返回總覽頁面">
                <a type="button" class="text-dark my-3 px-3 py-1 btn-outline-info errL3" href="abnormalDesLink.php"  Disabled>返回未審核清單</a>&nbsp
                </span>
                <a type="button" class="text-dark my-3 px-3 py-1 btn-outline-info" href="abnormalOverview.php?filter=no">返回總覽清單</a>
        </form>
    </div>
</body>
</html>