<?php
include("php/CMUHconndata.php");
include("php/fun.php");
//叫出資料
$MasterID=$_GET["id"];
$buildNo = $_GET["build"];
$sysNo= $_GET["sys"];
$rDate=$_GET["r_date"];

// $sql = "SELECT equipCheckID,ref,shiftID,r_member,remark,recordID,checkResult,floorID,rDate FROM FA.Water_System_Record_Detail WHERE recordID = $MasterID";
// $sql_query=$pdo->query($sql)->fetchAll();

$bname=sql_database('B_name','FA.Building','b_number',$buildNo);
//$equipname=sql_database('equipName','FA.Equipment_System','equipID',$equipID);
//$shiftname=sql_database('shiftName','FA.Shift_Table','shiftID',$shiftID);
$sysname=sql_database('sysName','FA.Equipment_System_Group','sysID',$sysNo);

$updata_qt=updata_num('FA.Water_System_Record_Detail',$MasterID);//迴圈數量
$updatainfo=updata_select('FA.Water_System_Record_Detail',$MasterID);//我要的東西

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
                <div class="col">
                    <p class="d-inline font-weight-bold">班別：</p>
                    <p class="d-inline text-primary"><?= $shiftname ?></p>
                </div>
                <div class="col text-center">
                    <p class="d-inline font-weight-bold">檢查者：</p>
                    <p class="d-inline text-primary"><?= '檢查者' ?></p>
                </div>
                <div class="col text-right">
                    <p class="d-inline font-weight-bold">檢查日期：</p>
                    <p class="d-inline text-primary"><?= $rDate ?></p>
                 </div>
            </div>
            <!-- <div class="my-3">
                <p class="d-inline font-weight-bold">
                    設備：
                </p>
                <p class="d-inline text-primary">
                    <?//= $equipname ?>
                </p>
            </div> -->
            <!--class="table my-5"-->
            <table class="table my-5">
                <thead>
                    <th>檢查項目</th>
                    <th>參考值</th>
                    <th>早班結果</th>
                    <th>中班結果</th>
                    <th>晚班結果</th>
                </thead>  -->
                <tbody class="text-primary">
                <td>
                    <table>
                       <!-- <thead>
                            <th>檢查項目</th>
                            <th>參考值</th>
                       </thead> -->
                       <?php
                        $updatainfo=updata_select('FA.Water_System_Record_Detail',$MasterID);//我要的東西
                        $itme=item("SELECT DISTINCT equipCheckID,ref FROM FA.Water_System_Record_Detail WHERE recordID=$MasterID");
                        $num=num("SELECT COUNT(equipCheckID) FROM FA.Water_System_Record_Detail WHERE recordID=$MasterID");
                        ?>                        
                        <!-- <tbody class="text-primary"> -->
                        <?php
                        //$itema=array();
                        for ($a=0; $a < ($num/3); $a++) {                            
                            $checkName=sql_database('equipCheckName','FA.Equipment_Check','equipCheckID',$itme[$a]["equipCheckID"]);
                            //$itema = array($a =>$checkName[$a]["equipCheckID"]);
                            ?>
                            <!-- <input type="hidden" name='<?//= $itema ?>' value='<?//= $checkName?>'> -->
                            <tr>
                                <td>
                                    <?= $checkName ?><!--檢查項目-->
                                </td>
                                <td>
                                    <?= $updatainfo[$a]["ref"]?><!--參考值-->
                                </td>
                            </tr>
                        <?php
                        }
                        for ($b=0; $b < $num; $b++) { 
                            $q=100+$b;
                        ?>                        
                            <input type="hidden" name='<?= $q ?>' value='<?= $updatainfo[$b]["recordDetailID"]?>'>
                        <?php
                        // print_r($itema);                            
                         }
                        ?>
                        <!-- </tbody> -->
                    </table>
                </td>
                <td>
                    <table>
                        <!-- <thead>
                            <th>早班結果</th>
                       </thead> -->
                            <?php
                            $ans1=item("SELECT equipCheckID,checkResult FROM FA.Water_System_Record_Detail WHERE recordID=$MasterID AND shiftID=1");
                            for ($a=0; $a < $num/3; $a++) {
                                if ($ans1[$a]["equipCheckID"]==$checkName[$a]["equipCheckID"]) {
                                        if ($updatainfo[$a]["ref"]=="V/X") { ?>
                                        <!-- <tbody> -->
                                            <tr>
                                                <td>
                                                    <input type='radio' name='<?= $a?>' value='true' <?PHP if( $ans1[$a]["checkResult"]=="true") echo "checked";?>>合格
                                                    <input type='radio' name='<?= $a?>' value='false' <?PHP if($ans1[$a]["checkResult"]=="false") echo "checked";?>>不合格
                                                </td>
                                            </tr>
                            <?php
                                        } else {
                            ?>              <tr>
                                                <td>
                                                    <input type="text" name='<?= $a?>' maxlength="20" value='<?= $ans1[$a]["checkResult"]?>'>
                                                </td>
                                            </tr>
                            <?php
                                        }
                                    }
                                }
                            ?>
                        <!-- </tbody> -->
                    </table>
                </td>
                <td>
                    <table>
                        <!-- <thead>
                            <th>中班結果</th>
                       </thead> -->
                        <?php
                                $ans1=item("SELECT equipCheckID,checkResult FROM FA.Water_System_Record_Detail WHERE recordID=$MasterID AND shiftID=2");
                                for ($a=0; $a < $num/3; $a++) {
                                    if ($ans1[$a]["equipCheckID"]==$checkName[$a]["equipCheckID"]) {
                                        if ($updatainfo[$a]["ref"]=="V/X") { ?>
                                        <!-- <tbody> -->
                                            <tr>
                                                <td>
                                                    <input type='radio' name='<?= $a?>' value='true' <?PHP if( $ans1[$a]["checkResult"]=="true") echo "checked";?>>合格
                                                    <input type='radio' name='<?= $a?>' value='false' <?PHP if($ans1[$a]["checkResult"]=="false") echo "checked";?>>不合格
                                                </td>
                                            </tr>
                            <?php
                                        } else {
                            ?>              <tr>
                                                <td>
                                                    <input type="text" name='<?= $a?>' maxlength="20" value='<?= $ans1[$a]["checkResult"]?>'>
                                                </td>
                                            </tr>
                            <?php
                                        }
                                    }
                                }
                            ?>
                        <!-- </tbody> -->
                    </table>
                </td>
                <td>
                    <table>
                        <!-- <thead>
                            <th>晚班結果</th>
                       </thead> -->
                       <?php
                                $ans1=item("SELECT equipCheckID,checkResult FROM FA.Water_System_Record_Detail WHERE recordID=$MasterID AND shiftID=3");
                                for ($a=0; $a < $num/3; $a++) {
                                    if ($ans1[$a]["equipCheckID"]==$checkName[$a]["equipCheckID"]) {
                                        if ($updatainfo[$a]["ref"]=="V/X") { ?>
                                        <!-- <tbody> -->
                                            <tr>
                                                <td>
                                                    <input type='radio' name='<?= $a?>' value='true' <?PHP if( $ans1[$a]["checkResult"]=="true") echo "checked";?>>合格
                                                    <input type='radio' name='<?= $a?>' value='false' <?PHP if($ans1[$a]["checkResult"]=="false") echo "checked";?>>不合格
                                                </td>
                                            </tr>
                            <?php
                                        } else {
                            ?>              
                                            <tr>
                                                <td>
                                                    <input type="text" name='<?= $a?>' maxlength="20" value='<?= $ans1[$a]["checkResult"]?>'>
                                                </td>
                                            </tr>
                            <?php
                                        }
                                    }
                                }
                            ?>
                        <!-- </tbody> -->
                    </table>
                </td>
            </tbody>
        </table>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">備註：</span>
                    </div>
                    <textarea class="form-control" name="remark" aria-label="With textarea"><?= $updatainfo[0]["remark"] ?></textarea>
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

