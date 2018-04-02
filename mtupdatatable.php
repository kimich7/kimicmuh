<?php
include("php/CMUHconndata.php");
include("php/fun.php");
//叫出資料
$building=$_GET["building"];
$rDate=$_GET["rdate"];
$equipID=$_GET["equip"];
$shiftID=$_GET["shift"];
$MasterID=$_GET["id"];
$sysID=$_GET["sys"];

$bname=sql_database('B_name','FA.Building','b_number',$building);
$equipname=sql_database('equipName','FA.Equipment_System','equipID',$equipID);
$shiftname=sql_database('shiftName','FA.Shift_Table','shiftID',$shiftID);
$sysname=sql_database('sysName','FA.Equipment_System_Group','sysID',$sysID);

$updata_qt=updata_num('FA.Water_System_Record_Detail',$rDate,$equipID,$MasterID);
$updatainfo=updata_select('FA.Water_System_Record_Detail',$rDate,$equipID,$MasterID);

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
    <!-- 連結自製化的Bootatrap.css -->
        <link rel="stylesheet" href="./css/bootstrap.css">
        <!-- 連結Normalize.min.css的網址使得網站在各個瀏覽器看起來相同 -->
        <link rel="stylesheet" href="./node_modules/normalize.css/normalize.css">
        <!-- 連結fontawesome的網址使得網站可以使用fontawesome的icon -->
        <link href="./node_modules/fontawesome-free-5.0.6/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet">
        <!-- 如果連結了客(自)製化的Bootstrap,原先連接的版本要註解掉 -->
        <!-- 連結Bootstrap的網址使得網站可以使用Bootstrap語法 -->
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> -->
        <!-- 連結animate的網址使得網站可以使用animate語法 -->
        <link rel="stylesheet" href="./node_modules/animate.css/animate.min.css">
        <!-- 連結自己的CSS -->
        <link rel="stylesheet" href="./css/style.css">
        <!-- 連結Bootstrap jQuery的網址使得網站可以使用JS, Popper.js, and jQuery語法 -->
        <!-- 並把jQuery變更為完整的jQuery -->
        <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>被變更的 -->
        <script src="./node_modules/jquery/dist/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- 連結wow.js的網址使得網站可以使用WOW的滾動動畫(必須連接animate.css) -->
        <script src="./node_modules/wow.js/dist/wow.min.js"></script>
        <!-- 連結自己的JS -->
        <script src="./js/main.js"></script>
    <title>設備保養修改表單</title>
</head>
<body class="table_bg">
    <div class="container border border-info mt-5">
        <form action="" method="post" name="up">
            <h2 class="text-center font-weight-bold">
                中國醫藥大學附設醫院-
                <?= $bname ?>--
                    <?= $sysname ?>
            </h2>
            <div class="row my-3">
                <div class="col">
                    <p class="d-inline font-weight-bold">
                        班別：
                    </p>
                    <p class="d-inline text-primary">
                        <?= $shiftname ?>
                    </p>
                    </div>
                    <div class="col text-center">
                    <p class="d-inline font-weight-bold">
                        檢查者：
                    </p>
                    <p class="d-inline text-primary">
                        <?= '檢查者' ?>
                    </p>
                </div>
                <div class="col text-right">
                    <p class="d-inline font-weight-bold">
                        檢查日期：
                    </p>
                    <p class="d-inline text-primary">
                        <?= $rDate ?>
                    </p>

                </div>
            </div>
            <div class="my-3">
                <p class="d-inline font-weight-bold">
                    設備：
                </p>
                <p class="d-inline text-primary">
                    <?= $equipname ?>
                </p>
            </div>
            <table class="table my-5">
                <thead>
                    <th>檢查項目</th>
                    <th>參考值</th>
                    <th>結果</th>
                </thead>
                <?php                        
                    for ($i=0; $i < $updata_qt; $i++) {
                        $q=100+$i;
                        $updatainfo=updata_select('FA.Water_System_Record_Detail',$rDate,$equipID,$MasterID);
                        $checkName=sql_database('equipCheckName','FA.Equipment_Check','equipCheckID',$updatainfo[$i]["equipCheckID"]);
                ?>
                <input type="hidden" name='<?= $q ?>' value='<?= $updatainfo[$i]["recordDetailID"]?>'>
                <tbody class="text-primary">
                    <td>
                        <?= $checkName ?>
                    </td>
                    <td>
                        <?= $updatainfo[$i]["ref"]?>
                    </td>
                    <?php
                        if ($updatainfo[$i]["ref"]=="V/X") { 
                    ?>
                    <td>
                        <input type='radio' name='<?= $i?>' value='true' <?PHP if( $updatainfo[$i]["checkResult"]=="true") echo "checked";?>>合格
                        <input type='radio' name='<?= $i?>' value='false' <?PHP if($updatainfo[$i]["checkResult"]=="false") echo "checked";?>>不合格
                    </td>
                    <?php                
                        } else { 
                    ?>
                    <td>
                        <input type="text" name='<?= $i?>' maxlength="20" value='<?= $updatainfo[$i]["checkResult"]?>'>
                    </td>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">備註：</span>
                    </div>
                    <textarea class="form-control" name="remark" aria-label="With textarea"><?= $updatainfo[0]["remark"] ?></textarea>
                </div>
                    <input type="hidden" name="action" value="update">                                        
                    <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit">送出</button>
                </form>
    </div>
</body>
</html>

