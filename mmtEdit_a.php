<?php 
include("php/CMUHconndata.php");
include("php/fun.php");    
session_start();
    
$userID=$_SESSION["login_number"];//登錄人員ID
$username=$_SESSION["login_member"] ;//登錄人員名稱
$MMT_AtableMid=$_GET['id'] ;//主表id

//主表的資料
$M_data_str="SELECT * FROM FA.MMT_AtableM WHERE id='$MMT_AtableMid'";
$M_data=$pdo->query($M_data_str);
while ($row = $M_data->fetch()) {
    $Mdata[]=array(
        'id'=>$row['id'],
        'bid'=>$row['bid'],
        'fid'=>$row['fid'],
        'eid'=>$row['eid'],
        'rdate'=>$row['rdate'],
        'datekind'=>$row['datekind'],
        'tid'=>$row['tid'],
        'macNo'=>$row['macNo'],
        'remark'=>$row['remark'],
        'emp'=>$row['emp'],
        'sremp'=>$row['sremp'],
        'cemp'=>$row['cemp'],
        'status'=>$row['status']

    );
} 
   
    //$mmtsysName=sql_database('sName','FA.MMT_sys','id',$mmtsysNo);//系統名稱    
    $mmtbuildName=sql_database('bName','FA.MMT_build','id',$Mdata[0]['bid']);//大樓名稱    
    $mmtfloorName=sql_database('fName','FA.MMT_floor','fid',$Mdata[0]['fid']);//樓層名稱    
    $mmtequipName=sql_database('eName','FA.MMT_equip','id',$Mdata[0]['eid']);//設備名稱
    $remp=sql_database('cname','FA.Employee','e_number',$Mdata[0]['emp']);//保養人員
    (int)$tid=$Mdata[0]['tid'] ;
    
    if ($Mdata[0]['cemp']=='' or $Mdata[0]['cemp']==null) {
        $check_emp='';//確認主管
    } else {
        $check_emp=sql_database('cname','FA.Employee','e_number',$Mdata[0]['cemp']);//確認主管
    }
    if ($Mdata[0]['sremp']=='' or $Mdata[0]['sremp']==null) {
        $sremp='';//確認專責
    } else {
        $sremp=sql_database('cname','FA.Employee','e_number',$Mdata[0]['sremp']);//專責人員
    }
    $reportdatastr="SELECT * FROM FA.MMT_KIND WHERE id=$tid";
    $reportdata=$pdo->query($reportdatastr);
    while ($row = $reportdata->fetch()) {
        $report[]=array(
            'tid'=>$row['id'],
            'tName'=>$row['tableName'],
            'datekind'=>$row['dateKind'],
            'sid'=>$row['sid']
        );
    }
    

//明細表資料
// $D_data_str="SELECT * FROM FA.MMT_AtableD WHERE mid='$MMT_AtableMid'";
// $D_data=$pdo->query($D_data_str);
// while ($row = $D_data->fetch()) {
//     $Ddata[]=array(
//         'id'=>$row['id'],
//         'checkid'=>$row['checkid'],
//         'ans'=>$row['ans'],
//         'mid'=>$row['mid']
//     ); 
// }
// $num = count($Ddata);

$Q_A_str="SELECT d.id,a.checkName,a.checkKind,a.ref,d.ans FROM FA.MMT_A AS a LEFT JOIN FA.MMT_AtableD as d ON a.id=d.checkid WHERE d.mid='$MMT_AtableMid' ORDER BY a.id";
$Q_A=$pdo->query($Q_A_str);
while ($row = $Q_A->fetch()) {
    $Q_A_data[]=array(
        'id'=>$row['id'],
        'checkName'=>$row['checkName'],
        'checkKind'=>$row['checkKind'],
        'ref'=>$row['ref'],
        'ans'=>$row['ans']
    ); 
}

$num=count($Q_A_data);

if (isset($_POST["action"])&&($_POST["action"]=="Edit")) {
    $num=$_POST["num"];//迴圈數量
    $rmark=$_POST["remark"];//備註
    $rdatekind_Array=$_POST['b'];//保養週期答案    
    $rdatekind= implode(",", $rdatekind_Array);
    $MMT_AtableMid=$_POST['mid'] ;//主表id
    
    $MasterStr="UPDATE FA.MMT_AtableM SET datekind=:datekind,remark=:remark WHERE id=:mid";
    $stmtM = $pdo->prepare($MasterStr);
    $stmtM->bindParam(':datekind',$rdatekind,PDO::PARAM_STR);
    $stmtM->bindParam(':remark',$rmark,PDO::PARAM_STR);
    $stmtM->bindParam(':mid',$MMT_AtableMid,PDO::PARAM_STR);
    $stmtM->execute();

    for ($i=0; $i < $num; $i++) { 
        $did=$i+200;//保養項目id   
        $d_id = $_POST["$did"];//明細表ID 
        $ans=$_POST["$i"];//答案
        $sql="UPDATE FA.MMT_AtableD SET ans=:checkResult WHERE id=:ID";
        $stmt = $pdo->prepare($sql);        
        $stmt->bindParam(':checkResult',$ans,PDO::PARAM_STR);
        $stmt->bindParam(':ID',$d_id,PDO::PARAM_INT);
        $stmt->execute();
    }
    $pdo=null;
    header("Location: mmt_list_a.php");
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
    <title>設備保養表單</title>
</head>

<body class="table_bg">
    <div class="container border border-info mt-5">
    <form action="" method="post" name="mmt_Edittable_a">
        <h2 class="text-center font-weight-bold"><?= $report[0]['tName']; ?></h2>
        <div class="row my-3">
            <div class="col">
                <p class="d-inline font-weight-bold">棟別：<?= $mmtbuildName ?></p>
            </div>
            <div class="col text-right">
                <p class="d-inline font-weight-bold">保養日期：<?= $Mdata[0]['rdate'] ?></p>
            </div>
        </div>
        <div class="row my-3">
            <div class="col">  
                <p class="d-inline font-weight-bold">樓層：<?= $mmtfloorName ?></p>
            </div>
        </div>
        <div class="row my-3">
            <div class="col">  
                <p class="d-inline font-weight-bold">機器編號：<?= $Mdata[0]['macNo'] ?></p>
            </div>
            <div class="col text-right">
            <?php
                $datekind=$report[0]['datekind'] ;
                switch ($datekind) {
                    case 'M,Y':
                        if($Mdata[0]['datekind']=='M'){?>
                            <p class="d-inline font-weight-bold">保養區分:&nbsp&nbsp<input type='checkbox' name="b[]" value='M' checked >&nbsp月&nbsp&nbsp
                            <input type='checkbox' name="b[]" value='Y' >&nbsp年</p>
                        <?PHP }elseif($Mdata[0]['datekind']=='Y'){?>
                            <p class="d-inline font-weight-bold">保養區分:&nbsp&nbsp<input type='checkbox' name="b[]" value='M' >&nbsp月&nbsp&nbsp
                            <input type='checkbox' name="b[]" value='Y' checked >&nbsp年</p>
                        <?PHP }elseif($Mdata[0]['datekind']=='M,Y' or $Mdata[0]['datekind']=='Y,M'){?>
                            <p class="d-inline font-weight-bold">保養區分:&nbsp&nbsp<input type='checkbox' name="b[]" value='M' checked >&nbsp月&nbsp&nbsp
                            <input type='checkbox' name="b[]" value='Y' checked >&nbsp年</p>
                        <?PHP }else{?>
                            <p class="d-inline font-weight-bold">保養區分:&nbsp&nbsp<input type='checkbox' name="b[]" value='M' >&nbsp月&nbsp&nbsp
                            <input type='checkbox' name="b[]" value='Y' >&nbsp年</p>
                        <?php } 
                        break;
                    case 'Y':
                        if($Mdata[0]['datekind']=='Y'){?>
                            <p class="d-inline font-weight-bold">保養區分:&nbsp&nbsp<input type='checkbox' name="b[]" value='Y' checked >&nbsp年&nbsp&nbsp</p>
                        <?php }else{?>
                            <p class="d-inline font-weight-bold">保養區分:&nbsp&nbsp<input type='checkbox' name="b[]" value='Y' >&nbsp年&nbsp&nbsp</p>
            <?PHP        }
                        break;                    
                }
            ?>
            </div>
        </div>
        <!--表格主體-->
        <table class="table my-5">
            <thead>
                <th>項&nbsp&nbsp&nbsp&nbsp目</th>
                <th>結&nbsp&nbsp&nbsp&nbsp果</th>                
            </thead>
            <?php 
            for ($i=0; $i < $num; $i++) {?>
            <tbody class="text-primary">
            <?php 
                $did=$i+200;
                echo  '<td>'.$Q_A_data[$i]['checkName'].'</td>';
                if ($Q_A_data[$i]['checkKind']=='檢查項目') {
                    echo '<td>';
                    if ($Q_A_data[$i]['ans']=='true') {
                        echo "<input type='radio' name=\"".$i."\" value='true' checked >是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$i."\" value='false' >否";
                    } elseif($Q_A_data[$i]['ans']=='false') {
                        echo "<input type='radio' name=\"".$i."\" value='true' >是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$i."\" value='false' checked >否";
                    } else {
                        echo "<input type='radio' name=\"".$i."\" value='true' >是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$i."\" value='false' >否";
                    }  
                    echo '</td>'; 
                        
                } elseif ($Q_A_data[$i]['ref']=='單相/三相') {
                    echo '<td>';
                    if ($Q_A_data[$i]['ans']=='single') {
                        echo "<input type='radio' name=\"".$i."\" value='single' checked >單相&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$i."\" value='three' >三相";
                    } elseif($Q_A_data[$i]['ans']=='three') {
                        echo "<input type='radio' name=\"".$i."\" value='single' >單相&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$i."\" value='three' checked >三相";
                    } else {
                        echo "<input type='radio' name=\"".$i."\" value='single' >單相&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$i."\" value='three' >三相";
                    }
                    echo '</td>'; 
                }elseif ($Q_A_data[$i]['ref']=='單相') {
                    echo '<td>';
                    echo "<input type='radio' name=\"".$i."\" value='single' checked >單相";
                    echo '</td>'; 
                }else{
                    echo '<td>'."<input type='text' name=\"".$i."\" maxlength='20' value=\"".$Q_A_data[$i]['ans']."\" >".'</td>';
                }
                echo "<input type='hidden' name=\"".$did."\" value=\"".$Q_A_data[$i]['id']."\">";//檢查項目id                
            }
            echo "<input type='hidden' name='mid' value=\"".$Mdata[0]['id']."\">"; //傳回主表id
            echo "<input type='hidden' name='num' value=\"".$num."\">";//傳回資料數量(當迴圈用)   
            ?>
            </tbody>
        </table>
        <!-- 備註欄 -->
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">備註：</span>
            </div>
            <textarea class="form-control" name="remark" aria-label="With textarea" ><?= $Mdata[0]['remark'] ?></textarea>
        </div>
        <input type="hidden" name="action" value="Edit">
        <div class="row my-3">
            <div class="col">
                <p class="d-inline font-weight-bold">工務室：<?= $check_emp ?></p>
            </div>
            <div class="col text-center">
                <p class="d-inline font-weight-bold">專責人員：<?= $sremp ?></p>
            </div>
            <div class="col text-right">
                <p class="d-inline font-weight-bold">保養人員：<?= $remp ?></p>
            </div>
        </div>

        <!-- 送出鈕 -->    
            <div class="d-flex justify-content-end">
                <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit">送出</button>
            </div>
    </form>
    </div>
</body>
</html>