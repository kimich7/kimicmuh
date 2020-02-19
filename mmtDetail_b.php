<?php 
include("php/CMUHconndata.php");
include("php/fun.php");    
session_start();
    
$userID=$_SESSION["login_number"];//登錄人員ID
$username=$_SESSION["login_member"] ;//登錄人員名稱
$MMT_AtableMid=$_GET['id'] ;//主表id

//主表的資料
$M_data_str="SELECT * FROM FA.MMT_BtableM WHERE id='$MMT_AtableMid'";
$M_data=$pdo->query($M_data_str);
while ($row = $M_data->fetch()) {
    $Mdata[]=array(
        'id'=>$row['id'],
        'bid'=>$row['bid'],
        'rdate'=>$row['rdate'],
        'datekind'=>$row['datekind'],
        'tid'=>$row['tid'],
        'remark'=>$row['remark'],
        'emp'=>$row['emp'],
        'sremp'=>$row['sremp'],
        'cemp'=>$row['cemp'],
        'status'=>$row['status']
    );
} 
    
    //$mmtsysName=sql_database('sName','FA.MMT_sys','id',$mmtsysNo);//系統名稱    
    $mmtbuildName=sql_database('bName','FA.MMT_build','id',$Mdata[0]['bid']);//大樓名稱
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
// $D_data_str="SELECT * FROM FA.MMT_BtableD WHERE mid='$MMT_AtableMid'";
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

$Q_A_str="SELECT a.checkName,a.checkKind,a.ref,d.ans FROM FA.MMT_A AS a LEFT JOIN FA.MMT_BtableD as d ON a.id=d.checkid WHERE d.mid='$MMT_AtableMid' ORDER BY a.id";
$Q_A=$pdo->query($Q_A_str);
while ($row = $Q_A->fetch()) {
    $Q_A_data[]=array(
        'checkName'=>$row['checkName'],
        'checkKind'=>$row['checkKind'],
        'ref'=>$row['ref'],
        'ans'=>$row['ans']
    ); 
}
$num=count($Q_A_data);

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
    <form action="" method="post" name="mmt_Detailtable_a">
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
            <div class="col text-right">
            <?php
                $datekind=$report[0]['datekind'] ;
                switch ($datekind) {
                    case 'M,Y':
                        if($Mdata[0]['datekind']=='M'){?>
                            <p class="d-inline font-weight-bold">保養區分:&nbsp&nbsp<input type='checkbox' name="b[]" value='M' checked disabled>&nbsp月&nbsp&nbsp
                            <input type='checkbox' name="b[]" value='Y' disabled>&nbsp年</p>
                        <?PHP }elseif($Mdata[0]['datekind']=='Y'){?>
                            <p class="d-inline font-weight-bold">保養區分:&nbsp&nbsp<input type='checkbox' name="b[]" value='M' disabled>&nbsp月&nbsp&nbsp
                            <input type='checkbox' name="b[]" value='Y' checked disabled>&nbsp年</p>
                        <?PHP }elseif($Mdata[0]['datekind']=='M,Y' or $Mdata[0]['datekind']=='Y,M'){?>
                            <p class="d-inline font-weight-bold">保養區分:&nbsp&nbsp<input type='checkbox' name="b[]" value='M' checked disabled>&nbsp月&nbsp&nbsp
                            <input type='checkbox' name="b[]" value='Y' checked disabled>&nbsp年</p>
                        <?PHP }else{?>
                            <p class="d-inline font-weight-bold">保養區分:&nbsp&nbsp<input type='checkbox' name="b[]" value='M' disabled>&nbsp月&nbsp&nbsp
                            <input type='checkbox' name="b[]" value='Y' disabled>&nbsp年</p>
                        <?php } 
                        break;
                    case 'Y':
                        if($Mdata[0]['datekind']=='Y'){?>
                            <p class="d-inline font-weight-bold">保養區分:&nbsp&nbsp<input type='checkbox' name="b[]" value='Y' checked disabled>&nbsp年&nbsp&nbsp</p>
                        <?php }else{?>
                            <p class="d-inline font-weight-bold">保養區分:&nbsp&nbsp<input type='checkbox' name="b[]" value='Y' disabled>&nbsp年&nbsp&nbsp</p>
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
                $checkid=$i+200;
                echo  '<td>'.$Q_A_data[$i]['checkName'].'</td>';
                if ($Q_A_data[$i]['checkKind']=='檢查項目') {
                    echo '<td>';
                    if ($Q_A_data[$i]['ans']=='true') {
                        echo "<input type='radio' name=\"".$i."\" value='true' checked disabled>是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$i."\" value='false' disabled>否";
                    } elseif($Q_A_data[$i]['ans']=='false') {
                        echo "<input type='radio' name=\"".$i."\" value='true' disabled>是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$i."\" value='false' checked disabled>否";
                    } else {
                        echo "<input type='radio' name=\"".$i."\" value='true' disabled>是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$i."\" value='false' disabled>否";
                    }  
                    echo '</td>'; 
                        
                } elseif ($Q_A_data[$i]['ref']=='單相/三相') {
                    echo '<td>';
                    if ($Q_A_data[$i]['ans']=='single') {
                        echo "<input type='radio' name=\"".$i."\" value='single' checked disabled>單相&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$i."\" value='three' disabled>三相";
                    } elseif($Q_A_data[$i]['ans']=='three') {
                        echo "<input type='radio' name=\"".$i."\" value='single' disabled>單相&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$i."\" value='three' checked disabled>三相";
                    } else {
                        echo "<input type='radio' name=\"".$i."\" value='single' disabled>單相&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$i."\" value='three' disabled>三相";
                    }
                    echo '</td>'; 
                }elseif ($Q_A_data[$i]['ref']=='單相') {
                    echo '<td>';
                    echo "<input type='radio' name=\"".$i."\" value='single' checked disabled>單相";
                    echo '</td>'; 
                }else{
                    echo '<td>'."<input type='text' name=\"".$i."\" maxlength='20' value=\"".$Q_A_data[$i]['ans']."\" disabled>".'</td>';
                }
                //echo "<input type='hidden' name=\"".$checkid."\" value=\"".$catarr[$i]['id']."\">";//檢查項目id                
            }            
            ?>
            </tbody>
        </table>
        <!-- 備註欄 -->
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">備註：</span>
            </div>
            <textarea class="form-control" name="remark" aria-label="With textarea" disabled><?= $Mdata[0]['remark'] ?></textarea>
        </div>
        <div class="row my-3">
            <div class="col text-left">
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
                <a href="mmt_list_b.php" type="button" class="btn btn-primary mt-4 rounded d-block mr-3">離開</a>
            </div>
    </form>
    </div>
</body>
</html>