<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    session_start();
    $checkuser=$_SESSION["login_member"];
    $checkuserID=sql_database('e_number','FA.Employee','cname',$checkuser);
    //叫出資料
    $MasterID=$_GET["id"];
    $MasterID=str_replace("\"", "", $MasterID);    
    $buildNo = $_GET["build"];
    $buildNo=str_replace("\"", "", $buildNo);
    $sysNo= $_GET["sysID"];
    $sysNo=str_replace("\"", "", $sysNo);
    $rDate=$_GET["r_date"];
    $rDate=str_replace("\"", "", $rDate);
    $sysMaster='FA.Water_System_Record_Master';
    $systemTable='FA.Water_System_Record_Detail';
    $equipTable='FA.Equipment_Check';

    $userRank=rank($checkuserID);//登入者的等級
    if ($userRank<3) {//主管登錄
        $tatus=rankStatus($MasterID);
            $eeID=$tatus['employeeID'];
        $eeName=sql_database('cname','FA.Employee','e_number',$eeID);        
    } else {//檢查者登錄
        $managerID = '';        
    }
    $bname=sql_database('B_name','FA.Building','b_number',$buildNo);
    $sysname=sql_database('sysName','FA.Equipment_System_Group','sysID',$sysNo);
    $updata_qt=updata_num($systemTable,$MasterID);//迴圈數量
    $updatainfo=updata_select($systemTable,$MasterID);//我要的東西
    
    if (isset($_POST["action"])&&($_POST["action"]=="update")) {
        $recordID=$_POST['MasterID'];        
        if ($userRank<3) {//主管登錄
            if (isset($_POST["mgrCheck"])) {
                $check_manager=1;           
            }
            $sql="UPDATE $sysMaster SET managerID=:managerID , check_manager=:check_manager WHERE recordID=:ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':managerID',$checkuserID,PDO::PARAM_STR);
            $stmt->bindParam(':check_manager',$check_manager,PDO::PARAM_STR);
        } else {//檢查者登錄
            if (isset($_POST["eeCheck"])) {
                $check_employee=1;           
            }
            $sql="UPDATE $sysMaster SET r_member=:r_member , check_number=:check_number WHERE recordID=:ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':r_member',$checkuserID,PDO::PARAM_STR);
            $stmt->bindParam(':check_number',$check_employee,PDO::PARAM_STR);
        }
            $stmt->bindParam(':ID',$recordID,PDO::PARAM_INT);
            $stmt->execute();      
        $pdo=null;
        header("Location: mtlistcheck.php");    
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
    <title>設備保養修改表單</title>
</head>

<body class="table_bg">
    <div class="container border border-info mt-5">
        <form action="" method="post" name="up">
            <h2 class="text-center font-weight-bold">中國醫藥大學附設醫院-<?= $bname ?>--<?= $sysname ?></h2>
            <!-- 班別/檢查者/日期欄 -->
            <div class="row my-3">
                <div class="col text-right">
                <p class="d-inline font-weight-bold">巡檢日期：</p>
                <p class="d-inline text-primary"><?= $rDate ?></p>
                </div>
            </div>
            <div class="d-flex justify-content-end">
            <button class="btn btn-primary my-2 mr-2 d-none" id="tfbtn1">早班結果</button>
            <button class="btn btn-primary my-2 mr-2 d-none" id="tfbtn2">中班結果</button>
            <button class="btn btn-primary my-2 mr-2 d-none" id="tfbtn3">晚班結果</button>
            </div>
            <!-- 表格主體 -->
            <div class="row container">
                <!-- 檢查項目/參考值 -->
                <table class="table col-xl-4 col-lg-4 col-md-8 col-sm-12 col-12">
                    <thead>
                        <th>檢查項目</th>
                        <th>參考值</th>
                    </thead>
                    <tbody class="text-primary">
                    <?php 
                        $itme=item("SELECT DISTINCT equipCheckID,ref FROM $systemTable WHERE recordID=$MasterID");
                        $num=num("SELECT COUNT(equipCheckID) FROM $systemTable WHERE recordID=$MasterID");
                        $checkName=array();
                        for ($a=0; $a < ($num/3); $a++) {                            
                        $checkName[$a]=sql_database('equipCheckName',$equipTable,'equipCheckID',$itme[$a]["equipCheckID"]);                        
                    ?>
                    <tr>
                        <!--檢查項目-->
                        <td><?= $checkName[$a]?></td>
                        <!--參考值-->
                        <td><?= $itme[$a]["ref"]?></td>
                    </tr>
                    <?php                           
                        }
                    ?>
                    </tbody>
                    <tfoot class="text-primary">
                        <td>巡檢人員</td>
                    </tfoot>
                </table> 
                <!-- 早班結果 -->
                <table id="tfresult1" class="table col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">
                    <thead>
                        <th>早班結果</th>
                    </thead>
                    <tbody class="text-primary">
                        <?php                            
                        $ans1=item("SELECT recordDetailID,equipCheckID,checkResult,r_member FROM $systemTable WHERE recordID=$MasterID AND shiftID=1");
                        $user_1=sql_database('cname','FA.Employee','e_number',$ans1[0]['r_member']);
                        for ($a=0; $a < $num/3; $a++) {
                            $q=$a+200;
                            $an=$a;//結果答案
                            for ($i=0; $i < $num/3 ;) { 
                                if ($itme[$a]["equipCheckID"] == $ans1[$i]["equipCheckID"]) {
                                    if ($itme[$a]["ref"]=="V/X") {
                        ?>
                        <tr>
                            <td>
                                <input type='radio' name='<?= $an?>' value='true' <?PHP if($ans1[$i]["checkResult"]=="true") echo "checked";?> disabled>合格
                                <input type='radio' name='<?= $an?>' value='false' <?PHP if($ans1[$i]["checkResult"]=="false") echo "checked";?> disabled>不合格
                            </td> 
                        </tr>               
                        <?php
                                    } else {
                        ?>
                        <tr>
                            <td><input type="text" name='<?= $an?>' maxlength="20" value='<?= $ans1[$i]["checkResult"]?>' disabled></td>
                        </tr>
                        <?php
                                    } ?>
                                    <input type='hidden' name='<?= $q?>' value='<?= $ans1[$i]['recordDetailID']?>'>
                            <?php
                                break;                                            
                                } else {
                                    $i++;                                               
                                }
                            }
                        }    
                        ?>
                    </tbody>
                    <tfoot class="text-primary">
                        <td><?= $user_1?></td>
                    </tfoot>
                </table>
                <!-- 中班結果 -->
                <table id="tfresult2" class="table col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">
                    <thead>
                        <th>中班結果</th>
                    </thead>
                    <tbody class="text-primary">
                        <?php
                            $ans2=item("SELECT recordDetailID,equipCheckID,checkResult,r_member FROM $systemTable WHERE recordID=$MasterID AND shiftID=2");
                            $user_2=sql_database('cname','FA.Employee','e_number',$ans2[0]['r_member']);
                            for ($a=0; $a < $num/3; $a++) {
                                $an=$a+($num/3);
                                $q=$a+200+($num/3);
                                for ($i=0; $i < $num/3 ;) { 
                                    if ($itme[$a]["equipCheckID"] == $ans2[$i]["equipCheckID"]) {
                                    if ($itme[$a]["ref"]=="V/X") {
                        ?>
                        <tr>
                            <td>
                                <input type='radio' name='<?= $an?>' value='true' <?PHP if($ans2[$i]["checkResult"]=="true") echo "checked";?> disabled>合格
                                <input type='radio' name='<?= $an?>' value='false' <?PHP if($ans2[$i]["checkResult"]=="false") echo "checked";?> disabled>不合格
                            </td>
                        </tr>
                        
                        <?php
                                    } else {
                        ?>
                        <tr>
                            <td><input type="text" name='<?= $an?>' maxlength="20" value='<?= $ans2[$i]["checkResult"]?>' disabled></td>
                        </tr>
                        <?php
                                    } ?>
                                <input type='hidden' name='<?= $q?>' value='<?= $ans2[$i]['recordDetailID']?>'>
                        <?php
                                    break;                                            
                                    } else {
                                $i++;                                               
                                    }
                                }
                            }    
                        ?>
                    </tbody>
                    <tfoot class="text-primary">
                        <td><?= $user_2?></td>
                    </tfoot>
                </table>
                 <!-- 晚班結果 -->    
                <table id="tfresult3" class="table col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">
                    <thead>
                        <th>晚班結果</th>
                    </thead>
                    <tbody class="text-primary">
                    <?php
                        $ans3=item("SELECT recordDetailID,equipCheckID,checkResult,r_member FROM $systemTable WHERE recordID=$MasterID AND shiftID=3");
                        $user_3=sql_database('cname','FA.Employee','e_number',$ans3[0]['r_member']);
                        for ($a=0; $a < $num/3; $a++) {
                            $an=$a+(($num/3)+($num/3));
                            $q=$a+200+(($num/3)+($num/3));
                            for ($i=0; $i < $num/3 ;) { 
                                if ($itme[$a]["equipCheckID"] == $ans3[$i]["equipCheckID"]) {
                                    if ($itme[$a]["ref"]=="V/X") {
                    ?>
                    <tr>
                        <td>
                            <input type='radio' name='<?= $an?>' value='true' <?PHP if($ans3[$i]["checkResult"]=="true") echo "checked";?> disabled>合格
                            <input type='radio' name='<?= $an?>' value='false' <?PHP if($ans3[$i]["checkResult"]=="false") echo "checked";?> disabled>不合格
                        </td>
                    </tr>
                    <?php
                                    } else {
                    ?>
                    <tr>
                        <td><input type="text" name='<?= $an?>' maxlength="20" value='<?= $ans3[$i]["checkResult"]?>' disabled></td>
                    </tr>
                    <?php
                                    }?>
                        <input type='hidden' name='<?= $q?>' value='<?= $ans3[$i]['recordDetailID']?>'>
                        <?php
                                    break;                                            
                                } else {
                                    $i++;                                               
                                }
                            }
                        }    
                    ?>
                    </tbody>
                    <tfoot class="text-primary">
                        <td><?= $user_3?></td>
                    </tfoot>
                </table>
            </div>
                 
            <!-- 備註欄 -->
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">備註：</span>
                </div>
                <textarea class="form-control" name="remark" aria-label="With textarea" ><?= $updatainfo[0]["remark"] ?></textarea>
            </div>
            <input type='hidden' name='MasterID' value='<?= $MasterID?>'>
            <?php
                if ($userRank<3) {//主管等級
                echo '<div class="row my-3">';
                    echo '<div class="col text-left">';
                    echo '<p class="d-inline font-weight-bold">主管：</p>';
                    echo '<p class="d-inline text-primary">'.$checkuser.'</p>';
                    echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="mgrCheck" value="mgcheck">主管確認</p>';
                    echo '</div>';
                    echo '<div class="col text-right">';
                    echo '<p class="d-inline font-weight-bold">檢查者：</p>';
                    echo '<p class="d-inline text-primary" name="reMumber">'.$eeName.'</p>';
                    echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="eeCheck" value="mbcheck" checked disabled >檢查人確認</p>';
                    echo '</div>';
                echo '</div>' ;
                } else {//檢查者登錄
                echo '<div class="row my-3">';
                    echo '<div class="col text-left">';
                    echo '<p class="d-inline font-weight-bold">主管：</p>';
                    echo '<p class="d-inline text-primary">'.$managerID.'</p>';
                    echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="mgrCheck" value="mgcheck" disabled>主管確認</p>';
                    echo '</div>';
                    echo '<div class="col text-right">';
                    echo '<p class="d-inline font-weight-bold">檢查者：</p>';
                    echo '<p class="d-inline text-primary" name="reMumber">'.$checkuser.'</p>';
                    echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="eeCheck" value="mbcheck">檢查人確認</p>';
                    echo '</div>';
                echo '</div>' ;  
                }                
            ?>
            
            <!-- 傳送值到資料庫中 -->
            <input type="hidden" name="action" value="update">
            <!-- 送出鈕 -->
            <div class="d-flex justify-content-end">
                <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit">送出</button>
            </div>
        </form>
    </div>
</body>

</html>