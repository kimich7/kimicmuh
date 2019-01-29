<?php
include("php/CMUHconndata.php");
include("php/fun.php");
session_start();
$user=$_SESSION["login_member"];
$userID=sql_database('e_number','FA.Employee','cname',$user);
$nowTime=date('Y-m-d H:i:s');
//取得表單資料
$sysID =(int)$_GET['systemID'];
$buildID =$_GET['build'];
$floorID =$_GET['floor'];
$rDate =$_GET['date'];
$shiftID =$_GET['shift'];
$detailTable='FA.Water_System_Record_Detail';
$masterTable='FA.Water_System_Record_Master';
$equipTable='FA.Equipment_Check';
//取得樓層系統要點檢的項目
$sql_equip_check = "SELECT equipCheckName,ref  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$buildID' AND sysID='$sysID' ORDER BY equipCheckName";
$query_equip=$pdo->query($sql_equip_check);
//取得要點檢的數量
$equip_check_num="SELECT COUNT(equipCheckID)  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$buildID' AND sysID='$sysID'";
$equip_check_no=Current($pdo->query($equip_check_num)->fetch());
//取得要修改的單號
$sql_str="SELECT recordID,rDate FROM $masterTable WHERE sysID=$sysID AND b_number='$buildID' AND rDate ='$rDate'";//篩選出需要的主表明細
$sql_query=$pdo->query($sql_str)->fetch();
$MasterID=$sql_query["recordID"];
if (!empty($MasterID)) {
    $buildName=sql_database('B_name','FA.Building','b_number',$buildID);
    //$equipName=sql_database('equipName','FA.Equipment_System','equipID',$equipID);
    $shiftName=sql_database('shiftName','FA.Shift_Table','shiftID',$shiftID);
    $sysName=sql_database('sysName','FA.Equipment_System_Group','sysID',$sysID);
    $updata_qt=num("SELECT COUNT(equipCheckID) FROM $detailTable WHERE recordID = $MasterID AND shiftID =$shiftID AND floorID = '$floorID'");
    $updatainfo=item("SELECT * FROM $detailTable WHERE recordID = $MasterID AND shiftID =$shiftID AND floorID = '$floorID' ");
    $user=sql_database('cname','FA.Employee','e_number',$updatainfo[0]['r_member']);
    $dataTime=$updatainfo[0]['rTime'];
    $r_member=$updatainfo[0]['r_member'];
    $allowTime=(strtotime($nowTime)-strtotime($dataTime))/3600;
}

if (isset($_POST["action"])&&($_POST["action"]=="update")) {
    for ($i=0; $i  <$updata_qt ; $i++) {
        $q=100+$i;
        $rdID=$_POST["$q"];        
        $ansStr=$updatainfo[$i]["ref"];
        $answerStr="SELECT a.answerMode FROM FA.Equipment_Check AS a INNER JOIN FA.Water_System_Record_Detail AS b ON a.ref = b.ref WHERE a.ref ='$ansStr'";
        $snswerQuery=$pdo->query($answerStr)->fetch();
        $answerMode=$snswerQuery["answerMode"];
        if ($answerMode=='plural') {
            $qu=$_POST["b"];
            $ans=implode(",", $qu) ;  
            print_r($ans);
            echo '</br>';         
            if ($ans=='1,1' or $ans=='1') {
                $ans= '1';
            }elseif($ans=='2,2' or $ans=='2'){
                $ans= '2';
            }else{
                $ans= '1,2';
            }                
        }elseif ($answerMode=='plural_1') {
            $qc=$_POST["c"];
            $ans=implode(",", $qc);
            if ($ans=='1,1'or $ans=='1') {
                $ans= '1';
            }elseif($ans=='2,2'or $ans=='2'){
                $ans= '2';
            }else{
                $ans= '1,2';
            }
        }else{
            $ans=$_POST["$i"];
        }
        if (empty($ans)) {
                $ans=null;
        }
        $sql="UPDATE $detailTable SET remark=:remark , checkResult=:checkResult WHERE recordDetailID=:ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':remark',$_POST["remark"],PDO::PARAM_STR);
        $stmt->bindParam(':checkResult',$ans,PDO::PARAM_STR);
        $stmt->bindParam(':ID',$rdID,PDO::PARAM_INT);
        $stmt->execute();      
    }
    $pdo=null;
    header("Location: mtinsert.html");    
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
    <title>Document</title>
</head>
<body>
    <div class="container border border-info mt-5">
        <?php
            if (empty($MasterID)) {
                echo '<h1>資料庫無此筆資料，請重新選取。</h1><hr>';
                echo '<h1> 網頁將導回抄表表單選擇頁面.....</h1>';
                header("Refresh:5;url=mtinsert.html");
            }elseif ($allowTime>8 || $r_member!=$userID ) {
            echo '<h1>已超過可修改時間，或您並非該表單巡檢人員，如愈修改此表單請聯絡具有權限之管理者。</h1><hr>';
            echo '<h1> 網頁將導回抄表表單選擇頁面.....</h1>';
            header("Refresh:5;url=mtinsert.html");
            } else{        
        ?>
        <form action="" method="post" name="up">
            <h2 class="text-center font-weight-bold">
                中國醫藥大學附設醫院-
                <?= $buildName ?>--
                    <?= $sysName ?>
            </h2>
            <div class="row my-3">
                <div class="col">
                    <p class="d-inline font-weight-bold">
                        班別：
                    </p>
                    <p class="d-inline text-primary">
                        <?= $shiftName ?>
                    </p>
                    </div>
                    <div class="col text-center">
                    <p class="d-inline font-weight-bold">
                        巡檢人員：
                    </p>
                    <p class="d-inline text-primary">
                        <?= $user ?>
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
            <!-- <div class="my-3">
                <p class="d-inline font-weight-bold">
                    設備：
                </p>
                <p class="d-inline text-primary">
                    <//?= $equipName ?>
                </p>
            </div> -->
            <table class="table my-5">
                <thead>
                    <th>檢查項目</th>
                    <th>參考值</th>
                    <th>結果</th>
                </thead>
                <?php                        
                    for ($i=0; $i < $updata_qt; $i++) {
                        $q=100+$i;
                        $checkName=sql_database('equipCheckName',$equipTable,'equipCheckID',$updatainfo[$i]["equipCheckID"]);
                        $ansStr=$updatainfo[$i]["ref"];
                        $answerStr="SELECT a.answerMode FROM FA.Equipment_Check AS a INNER JOIN FA.Water_System_Record_Detail AS b ON a.ref = b.ref WHERE a.ref ='$ansStr'";
                        $snswerQuery=$pdo->query($answerStr)->fetch();
                        $answerMode=$snswerQuery["answerMode"];
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
                    // $b=array();
                    // $b[0]="";
                    // $b[1]="";
                    // $c=array();
                    // $c[0]="";
                    // $c[1]="";
                        switch ($answerMode) {
                            case 'choiceTF':
                                echo '<td>';
                                    if( $updatainfo[$i]["checkResult"]=="true"){
                                        echo "<input type='radio' name=\"".$i."\" value='true' checked >合格";
                                        echo "<input type='radio' name=\"".$i."\" value='false'>不合格";
                                    } else {
                                        echo "<input type='radio' name=\"".$i."\" value='true' >合格";
                                        echo "<input type='radio' name=\"".$i."\" value='false' checked >不合格";
                                    }                                
                                echo '</td>';
                                break;
                            case 'choiceHA':
                                echo '<td>';
                                    if( $updatainfo[$i]["checkResult"]=="handle"){
                                        echo "<input type='radio' name=\"".$i."\" value='handle' checked>手動";
                                        echo "<input type='radio' name=\"".$i."\" value='auto'>自動";
                                    } else {
                                        echo "<input type='radio' name=\"".$i."\" value='handle'>手動";
                                        echo "<input type='radio' name=\"".$i."\" value='auto' checked>自動";
                                    }
                                echo '</td>';
                                break;
                            case 'choiceFN':
                                echo '<td>';
                                    if( $updatainfo[$i]["checkResult"]=="OFF"){
                                        echo "<input type='radio' name=\"".$i."\" value='OFF' checked>OFF";
                                        echo "<input type='radio' name=\"".$i."\" value='ON'>ON";
                                    } else {
                                        echo "<input type='radio' name=\"".$i."\" value='OFF'>OFF";
                                        echo "<input type='radio' name=\"".$i."\" value='ON' checked>ON";
                                    }
                                echo '</td>';
                                break;
                            case 'choiceRL':
                                echo '<td>';
                                    if( $updatainfo[$i]["checkResult"]=="remote"){
                                        echo "<input type='radio' name=\"".$i."\" value='remote' checked>遠端";
                                        echo "<input type='radio' name=\"".$i."\" value='local'>本地";
                                    } else {
                                        echo "<input type='radio' name=\"".$i."\" value='remote'>遠端";
                                        echo "<input type='radio' name=\"".$i."\" value='local' checked>本地";
                                    }
                                echo '</td>';
                                break;
                            case 'choiceS12':
                                echo '<td>';
                                    if( $updatainfo[$i]["checkResult"]=="S1"){
                                        echo "<input type='radio' name=\"".$i."\" value='S1' checked>S1";
                                        echo "<input type='radio' name=\"".$i."\" value='S2'>S2";
                                    } else {
                                        echo "<input type='radio' name=\"".$i."\" value='S1'>S1";
                                        echo "<input type='radio' name=\"".$i."\" value='S2' checked>S2";
                                    }
                                echo '</td>';
                                break;
                            case 'choiceRG':
                                echo '<td>';
                                    if( $updatainfo[$i]["checkResult"]=="red"){
                                        echo "<input type='radio' name=\"".$i."\" value='red' checked>紅";
                                        echo "<input type='radio' name=\"".$i."\" value='green'>綠";
                                    } else {
                                        echo "<input type='radio' name=\"".$i."\" value='red'>紅";
                                        echo "<input type='radio' name=\"".$i."\" value='green' checked>綠";
                                    }
                                echo '</td>';
                                break;
                            case 'plural':
                                echo '<td>';
                                if( $updatainfo[$i]["checkResult"]=="1"){ ?>
                                    <input type='checkbox' name="b[]" value='1' checked>1&nbsp&nbsp
                                    <input type='checkbox' name="b[]" value='2'>2
                                <?php } elseif($updatainfo[$i]["checkResult"]=="2") { ?>
                                    <input type='checkbox' name="b[]" value='1'>1&nbsp&nbsp
                                    <input type='checkbox' name="b[]" value='2' checked>2
                                <?php } elseif($updatainfo[$i]["checkResult"]=="1,2") { ?>
                                    <input type='checkbox' name="b[]" value='1' checked>1&nbsp&nbsp
                                    <input type='checkbox' name="b[]" value='2' checked>2
                                <?php } else { ?>
                                    <input type='checkbox' name="b[]" value='1'>1&nbsp&nbsp
                                    <input type='checkbox' name="b[]" value='2'>2                                
                                <?php }                            
                                echo '</td>';
                                break;
                            case 'plural_1':
                                echo '<td>';
                                if( $updatainfo[$i]["checkResult"]=="1"){ ?>
                                    <input type='checkbox' name="c[]" value='1' checked>1&nbsp&nbsp
                                    <input type='checkbox' name="c[]" value='2'>2
                                <?php } elseif($updatainfo[$i]["checkResult"]=="2") { ?>
                                    <input type='checkbox' name="c[]" value='1'>1&nbsp&nbsp
                                    <input type='checkbox' name="c[]" value='2' checked>2
                                <?php } elseif($updatainfo[$i]["checkResult"]=="1,2") { ?>
                                    <input type='checkbox' name="c[]" value='1' checked>1&nbsp&nbsp
                                    <input type='checkbox' name="c[]" value='2' checked>2
                                <?php } else { ?>
                                    <input type='checkbox' name="c[]" value='1'>1&nbsp&nbsp
                                    <input type='checkbox' name="c[]" value='2'>2                                
                                <?php }                            
                                echo '</td>';
                                break;
                            default:
                                echo '<td>'."<input type='text' name=\"".$i."\" maxlength='20' value=\"".$updatainfo[$i]["checkResult"]."\"></td>";
                                break;
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
                <div class="d-flex justify-content-end">                                       
                    <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit">送出</button>
                </div>
        </form>
        <?php } ?>    
    </div>
</body>
</html>