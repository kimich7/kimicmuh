<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    //叫出資料
    $MasterID=$_GET["id"];
    $buildNo = $_GET["build"];
    $sysNo= $_GET["sys"];
    $rDate=$_GET["r_date"];
    $bname=sql_database('B_name','FA.Building','b_number',$buildNo);
    $sysname=sql_database('sysName','FA.Equipment_System_Group','sysID',$sysNo);
    $updata_qt=updata_num('FA.Water_System_Record_Detail',$MasterID);//迴圈數量
    $updatainfo=updata_select('FA.Water_System_Record_Detail',$MasterID);//我要的東西
    switch ($sysNo) {
        case '1':
            $systemTable='FA.Water_System_Record_Detail';
            break;
        case '2':
            $systemTable='FA.Air_System_Record_Detail';
            break;
        case '3':
            $systemTable='FA.AirCond_System_Record_Detail';
            break;
        case '4':
            $systemTable='FA.HL_Vol_System_Record_Detail';
            break;
    }
    if (isset($_POST["action"])&&($_POST["action"]=="update")) {
        for ($i=0; $i  <$updata_qt ; $i++) {
            $q=100+$i;
            $rdID=$_POST["$q"];
            $ans=$_POST["$i"];
            $sql="UPDATE FA.Water_System_Record_Detail SET remark=:remark , checkResult=:checkResult WHERE recordDetailID=:ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':remark',$_POST["remark"],PDO::PARAM_STR);
            $stmt->bindParam(':checkResult',$ans,PDO::PARAM_STR);
            $stmt->bindParam(':ID',$rdID,PDO::PARAM_INT);
            $stmt->execute();      
        }
        $pdo=null;
        header("Location: mtupdata.php");    
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
                <div class="col text-left">
                <p class="d-inline font-weight-bold">檢查者：</p>
                <p class="d-inline text-primary"><?= '檢查者' ?></p>
                </div>
                <div class="col text-right">
                <p class="d-inline font-weight-bold">檢查日期：</p>
                <p class="d-inline text-primary"><?= $rDate ?></p>
                </div>
            </div>
            <!-- 表格主體 -->
            <table class="table my-5">
                <thead>
                    <th>檢查項目</th>
                    <th>參考值</th>
                    <th>早班結果</th>
                    <th>中班結果</th>
                    <th>晚班結果</th>
                </thead>
                <tbody class="text-primary row">
                    <!-- 檢查項目/參考值 -->
                    <td class="col-ml-6">
                        <table>
                            <?php 
                                //$updatainfo=updata_select($systemTable,$MasterID);//我要的東西
                                $itme=item("SELECT DISTINCT equipCheckID,ref FROM $systemTable WHERE recordID=$MasterID");
                                $num=num("SELECT COUNT(equipCheckID) FROM $systemTable WHERE recordID=$MasterID");
                                $checkName=array();
                                for ($a=0; $a < ($num/3); $a++) {                            
                                $checkName[$a]=sql_database('equipCheckName','FA.Equipment_Check','equipCheckID',$itme[$a]["equipCheckID"]);
                                //PRINT_R($checkName);
                            ?>
                            <tr>
                                <!--檢查項目-->
                                <td><?= $checkName[$a]?></td>
                                <!--參考值-->
                                <td><?= $itme[$a]["ref"]?></td>
                            </tr>
                            <?php
                                }
                                for ($b=0; $b < $num; $b++) { 
                                $q=100+$b;
                            ?>                        
                            <input type="hidden" name='<?= $q ?>' value='<?= $updatainfo[$b]["recordDetailID"]?>'>
                            <?php                           
                                }
                            ?>
                        </table>
                    </td>
                    <!-- 早班結果 -->
                    <td class="col-ml-2">
                        <table>
                            <?php                            
                                $ans1=item("SELECT equipCheckID,checkResult FROM $systemTable WHERE recordID=$MasterID AND shiftID=1");
                                for ($a=0; $a < $num/3; $a++) {
                                    for ($i=0; $i < $num/3 ;) { 
                                        if ($itme[$a]["equipCheckID"] == $ans1[$i]["equipCheckID"]) {
                                            if ($itme[$a]["ref"]=="V/X") {
                            ?>
                            <tr>
                                <td>
                                <input type='radio' name='<?= $a."A"?>' value='true' <?PHP if($ans1[$i]["checkResult"]=="true") echo "checked";?>>合格
                                <input type='radio' name='<?= $a."A"?>' value='false' <?PHP if($ans1[$i]["checkResult"]=="false") echo "checked";?>>不合格
                                </td>
                            </tr>
                            <?php
                                            } else {
                            ?>
                            <tr>
                                <td><input type="text" name='<?= $a?>' maxlength="20" value='<?= $ans1[$i]["checkResult"]?>'></td>
                            </tr>
                            <?php
                                            }
                                            break;                                            
                                        } else {
                                            $i++;                                               
                                        }
                                    }
                                }    
                                    // if ($ans1[$a]["equipCheckID"]==$itme[$i]["equipCheckID"]) {
                                    //     if ($itme[$a]["ref"]=="V/X") { 
                            ?>
                            <!-- 斷點 -->
                        </table>
                    </td>
                    <!-- 中班結果 -->
                    <td  class="col-ml-2">
                        <table>
                            <?php
                                $ans2=item("SELECT equipCheckID,checkResult FROM $systemTable WHERE recordID=$MasterID AND shiftID=2");
                                for ($a=0; $a < $num/3; $a++) {
                                    for ($i=0; $i < $num/3 ;) { 
                                        if ($itme[$a]["equipCheckID"] == $ans2[$i]["equipCheckID"]) {
                                            if ($itme[$a]["ref"]=="V/X") {
                            ?>
                            <tr>
                                <td>
                                <input type='radio' name='<?= $a."B"?>' value='true' <?PHP if($ans2[$i]["checkResult"]=="true") echo "checked";?>>合格
                                <input type='radio' name='<?= $a."B"?>' value='false' <?PHP if($ans2[$i]["checkResult"]=="false") echo "checked";?>>不合格
                                </td>
                            </tr>
                            <?php
                                            } else {
                            ?>
                            <tr>
                                <td><input type="text" name='<?= $a?>' maxlength="20" value='<?= $ans2[$i]["checkResult"]?>'></td>
                            </tr>
                            <?php
                                            }
                                            break;                                            
                                        } else {
                                            $i++;                                               
                                        }
                                    }
                                }    
                            ?>
                        </table>
                    </td>
                    <!-- 晚班結果 -->
                    <td  class="col-ml-2">
                        <table>
                            <?php
                                $ans3=item("SELECT equipCheckID,checkResult FROM $systemTable WHERE recordID=$MasterID AND shiftID=3");
                                for ($a=0; $a < $num/3; $a++) {
                                    for ($i=0; $i < $num/3 ;) { 
                                        if ($itme[$a]["equipCheckID"] == $ans3[$i]["equipCheckID"]) {
                                            if ($itme[$a]["ref"]=="V/X") {
                            ?>
                            <tr>
                                <td>
                                <input type='radio' name='<?= $a."C"?>' value='true' <?PHP if($ans3[$i]["checkResult"]=="true") echo "checked";?>>合格
                                <input type='radio' name='<?= $a."C"?>' value='false' <?PHP if($ans3[$i]["checkResult"]=="false") echo "checked";?>>不合格
                                </td>
                            </tr>
                            <?php
                                            } else {
                            ?>
                            <tr>
                                <td><input type="text" name='<?= $a?>' maxlength="20" value='<?= $ans3[$i]["checkResult"]?>'></td>
                            </tr>
                            <?php
                                            }
                                            break;                                            
                                        } else {
                                            $i++;                                               
                                        }
                                    }
                                }    
                            ?>
                        </table>
                    </td>
                </tbody>
            </table>
            <!-- 備註欄 -->
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">備註：</span>
                </div>
                <textarea class="form-control" name="remark" aria-label="With textarea"><?= $updatainfo[0]["remark"] ?></textarea>
            </div>
            <!-- 傳送值到資料庫中 -->
            <input type="hidden" name="action" value="update">
            <!-- 送出鈕 -->
            <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit">送出</button>
        </form>
    </div>
</body>

</html>