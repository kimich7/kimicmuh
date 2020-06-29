<?php 
include("php/CMUHconndata.php");
include("php/fun.php");    
session_start();
    
$userID=$_SESSION["login_number"];//登錄人員ID
$username=$_SESSION["login_member"] ;//登錄人員名稱
$MMT_AtableMid=$_GET['id'] ;//主表id

//主表的資料
$M_data_str="SELECT * FROM FA.MMT_FtableM WHERE id='$MMT_AtableMid'";
$M_data=$pdo->query($M_data_str);
while ($row = $M_data->fetch()) {
    $Mdata[]=array(
        'id'=>$row['id'],
        'bid'=>$row['bid'],
        'fid'=>$row['fid'],
        'eid'=>$row['eid'],        
        'datekind'=>$row['datekind'],
        'tid'=>$row['tid'],
        'macNo'=>$row['macNo'],
        'remark'=>$row['remark'],
        'emp'=>$row['H1_emp'],
        'cemp'=>$row['H2_emp'],
        'status'=>$row['status'],
        'H1_vora'=>$row['H1_vora'],
        'H2_vora'=>$row['H2_vora'],
        'timestamp'=>$row['timestamp']
    );
} 
     list($year, $month, $day) = preg_split('/[-: ]/', $Mdata[0]['timestamp']);
    //$mmtsysName=sql_database('sName','FA.MMT_sys','id',$mmtsysNo);//系統名稱  

    
    $va1=explode(',',$Mdata[0]['H1_vora']);
    $va2=explode(',',$Mdata[0]['H2_vora']); 

    $equip=$Mdata[0]['eid'];
    $floor=$Mdata[0]['fid'];
    $building=$Mdata[0]['bid'];

    $choicedata=sql_database_int('choice','FA.MMT_KIND','id',$Mdata[0]['tid']);

    $eidName=sql_database('eName','FA.MMT_equip','id',$equip);
    $macnum= $Mdata[0]['macNo'];
    $checkYear=$year;

    $mmtbuildName=sql_database('bName','FA.MMT_build','id',$Mdata[0]['bid']);//大樓名稱    
    $mmtfloorName=sql_database('fName','FA.MMT_floor','fid',$Mdata[0]['fid']);//樓層名稱    
    $mmtequipName=sql_database('eName','FA.MMT_equip','id',$Mdata[0]['eid']);//設備名稱
    $remp=sql_database('cname','FA.Employee','e_number',$Mdata[0]['emp']);//保養人員
    (int)$tid=$Mdata[0]['tid'] ;
    //表單id,名稱(大標題用)
    $reportName=item("SELECT k.id,k.tableName,k.dateKind FROM FA.MMT_KIND AS k LEFT JOIN FA.MMT_equipNo AS n ON k.id=n.tid WHERE n.eid='$equip' AND n.fid='$floor' AND n.bid='$building' AND n.sid='F' ");



    
    if ($Mdata[0]['cemp']=='' or $Mdata[0]['cemp']==null) {
        $check_H1='';//確認主管
    } else {
        $check_H1=sql_database('cname','FA.Employee','e_number',$Mdata[0]['cemp']);//確認主管
    }
    
    if ($Mdata[0]['emp']=='' or $Mdata[0]['emp']==null) {
        $check_H2='';//確認者
    } else {
        $check_H2=sql_database('cname','FA.Employee','e_number',$Mdata[0]['cemp']);//確認主管
    }
    //明細表資料+點檢項目資料
    //S1答案
    $reportdatastrS1="select a.checkName,a.ref,d.ans,d.id,d.checkid,d.rDate,d.remark,a.checkKind,d.checkUserid,d.checkmanid FROM FA.MMT_A as a LEFT JOIN(SELECT * FROM FA.MMT_FtableD WHERE mid='$MMT_AtableMid' AND fseason='S1') AS d ON a.id=d.checkid WHERE a.id=d.checkid";
    $reportdataS1=$pdo->query($reportdatastrS1);
    while ($row = $reportdataS1->fetch()) {
        $reportS1[]=array(
            'id'=>['id'],
            'checkName'=>$row['checkName'],
            'ans'=>$row['ans'],
            'rDate'=>$row['rDate'],
            'remark'=>$row['remark'],
            'checkKind'=>$row['checkKind'],
            'checkUserid'=>$row['checkUserid'],
            'checkmanid'=>$row['checkmanid']
        );
    }
    //S2答案 
    $reportdatastrS2="SELECT a.checkid,d.ans,d.id,d.rDate,d.remark,d.checkUserid,d.checkmanid  FROM(SELECT * FROM FA.MMT_FtableD WHERE fseason='S1' AND   mid='$MMT_AtableMid') AS a  LEFT JOIN (SELECT * FROM FA.MMT_FtableD WHERE mid='$MMT_AtableMid' AND fseason='S2') AS d ON  a.checkid=d.checkid";
    $reportdataS2=$pdo->query($reportdatastrS2);
    while ($row = $reportdataS2->fetch()) {
        @$reportS2[]=array(
            'id'=>$row['id'],
            'checkid'=>$row['checkid'],
            'ans'=>$row['ans'],
            'rDate'=>$row['rDate'],
            'remark'=>$row['remark'],
            'checkUserid'=>$row['checkUserid'],
            'checkmanid'=>$row['checkmanid']
        );
    }
    //S3答案
    $reportdatastrS3="SELECT a.checkid,d.ans,d.id,d.rDate,d.remark,d.checkUserid,d.checkmanid  FROM(SELECT * FROM FA.MMT_FtableD WHERE fseason='S1' AND   mid='$MMT_AtableMid') AS a  LEFT JOIN (SELECT * FROM FA.MMT_FtableD WHERE mid='$MMT_AtableMid' AND fseason='S3') AS d ON  a.checkid=d.checkid";
    $reportdataS3=$pdo->query($reportdatastrS3);
    while ($row = $reportdataS3->fetch()) {
        @$reportS3[]=array(
            'id'=>$row['id'],
            'checkid'=>$row['checkid'],
            'ans'=>$row['ans'],
            'rDate'=>$row['rDate'],
            'remark'=>$row['remark'],
            'checkUserid'=>$row['checkUserid'],
            'checkmanid'=>$row['checkmanid']
        );
    }
    //S4答案
    $reportdatastrS4="SELECT a.checkid,d.ans,d.id,d.rDate,d.remark,d.checkUserid,d.checkmanid  FROM(SELECT * FROM FA.MMT_FtableD WHERE fseason='S1' AND   mid='$MMT_AtableMid') AS a  LEFT JOIN (SELECT * FROM FA.MMT_FtableD WHERE mid='$MMT_AtableMid' AND fseason='S4') AS d ON  a.checkid=d.checkid";
    $reportdataS4=$pdo->query($reportdatastrS4);
    while ($row = $reportdataS4->fetch()) {
        @$reportS4[]=array(
            'id'=>$row['id'],
            'checkid'=>$row['checkid'],
            'ans'=>$row['ans'],
            'rDate'=>$row['rDate'],
            'remark'=>$row['remark'],
            'checkUserid'=>$row['checkUserid'],
            'checkmanid'=>$row['checkmanid']
        );
    }

    
    @$checkUser1=checkName($reportS1[0]['checkUserid']);
    @$checkUser2=checkName($reportS2[0]['checkUserid']);
    @$checkUser3=checkName($reportS3[0]['checkUserid']);
    @$checkUser4=checkName($reportS4[0]['checkUserid']);
    @$checkDouble1=checkName($reportS1[0]['checkmanid']);
    @$checkDouble2=checkName($reportS2[0]['checkmanid']);
    @$checkDouble3=checkName($reportS3[0]['checkmanid']);
    @$checkDouble4=checkName($reportS4[0]['checkmanid']);
    
    

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
@$num = count($reportS1);

// $Q_A_str="SELECT a.checkName,a.checkKind,a.ref,d.ans FROM FA.MMT_A AS a LEFT JOIN FA.MMT_AtableD as d ON a.id=d.checkid WHERE d.mid='$MMT_AtableMid' ORDER BY a.id";
// $Q_A=$pdo->query($Q_A_str);
// while ($row = $Q_A->fetch()) {
//     $Q_A_data[]=array(
//         'checkName'=>$row['checkName'],
//         'checkKind'=>$row['checkKind'],
//         'ref'=>$row['ref'],
//         'ans'=>$row['ans']
//     ); 
// }
// $num=count($Q_A_data);

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
    <form action="" method="post" name="mmt_Detailtable_f">
        <h2 class="text-center font-weight-bold"><?= $reportName[0]['tableName']; ?></h2>
        <div class="row my-3">
            <div class="col">
                <p class="d-inline font-weight-bold">表格名稱：<?= $eidName ?></p>
            </div>
            <div class="col text-right">
                <p class="d-inline font-weight-bold">檢查週期：每3個月乙次(季檢查)</p>
            </div>
        </div>
        <div class="row my-3">
            <div class="col">
                <p class="d-inline font-weight-bold">表格編號：<?= $macnum ?></p>
            </div>
            <div class="col text-right">
                <p class="d-inline font-weight-bold">檢查年度：<?= $checkYear ?></p>
            </div>
        </div>
        <div class="row my-3">
            <div class="col">
                <p class="d-inline font-weight-bold">使用單位：工務室</p>
            </div>
            <div class="col text-right">
                <p class="d-inline font-weight-bold">設置場所：<?= $mmtfloorName ?></p>
            </div> 
        </div>
        <!--表格主體-->
        <div class="row container">
        <table class="table my-5">
            <thead>
                <th>檢&nbsp查&nbsp項&nbsp目</th>
                <th>第一季</th> 
                <th>第二季</th>
                <th>第三季</th>
                <th>第四季</th>               
            </thead>
            <tbody class="text-primary">
            <tr>
                <td>檢查日期</td>
                <td><?= @$reportS1[0]['rDate'] ?></td>
                <td><?= @$reportS2[0]['rDate'] ?></td>
                <td><?= @$reportS3[0]['rDate'] ?></td>
                <td><?= @$reportS4[0]['rDate'] ?></td>
            </tr>
            <?php 
            for ($i=0; $i < $num; $i++) {
                @$checkrefS2=sql_database_int('ref','FA.MMT_A','id',$reportS2[$i]['checkid']);
                @$checkrefS3=sql_database_int('ref','FA.MMT_A','id',$reportS3[$i]['checkid']);
                @$checkrefS4=sql_database_int('ref','FA.MMT_A','id',$reportS4[$i]['checkid']);
            
                $checkid=$i+1000;
                $j=$i+200;
                $k=$i+400;
                $l=$i+600;
                echo '<tr>';
                echo  '<td>'.$reportS1[$i]['checkName'].'</td>';
                if ($reportS1[$i]['checkKind']=='檢查項目') {
                    echo '<td>';
                    if ($reportS1[$i]['ans']=='true') {
                        echo "<input type='radio' name=\"".$i."\" value='true' checked disabled>是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$i."\" value='false' disabled>否";
                    } elseif($reportS1[$i]['ans']=='false') {
                        echo "<input type='radio' name=\"".$i."\" value='true' disabled>是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$i."\" value='false' checked disabled>否";
                    } else {
                        echo "<input type='radio' name=\"".$i."\" value='true' disabled>是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$i."\" value='false' disabled>否";
                    }  
                    echo '</td>';
                }else{
                    echo '<td>'."<input type='text' name=\"".$i."\" maxlength='20' value=\"".$reportS1[$i]['ans']."\" disabled>".'</td>';
                }
                //echo "<input type='hidden' name=\"".$checkid."\" value=\"".$catarr[$i]['id']."\">";//檢查項目id  
                if ($checkrefS2=='V/X') {
                    echo '<td>';
                    if($reportS2[$i]['ans']=='true') {
                        echo "<input type='radio' name=\"".$j."\" value='true' checked disabled>是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$j."\" value='false' disabled>否";
                    } elseif($reportS2[$i]['ans']=='false') {
                        echo "<input type='radio' name=\"".$j."\" value='true' disabled>是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$j."\" value='false' checked disabled>否";
                    } else {
                        echo "<input type='radio' name=\"".$j."\" value='true' disabled>是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$j."\" value='false' disabled>否";
                    }  
                    echo '</td>';
                } else {
                    echo '<td>'."<input type='text' name=\"".$j."\" maxlength='20' value=\"".$reportS2[$i]['ans']."\" disabled>".'</td>';
                }
                if ($checkrefS3=='V/X') {
                    echo '<td>';
                    if($reportS3[$i]['ans']=='true') {
                        echo "<input type='radio' name=\"".$k."\" value='true' checked disabled>是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$k."\" value='false' disabled>否";
                    } elseif($reportS3[$i]['ans']=='false') {
                        echo "<input type='radio' name=\"".$k."\" value='true' disabled>是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$k."\" value='false' checked disabled>否";
                    } else {
                        echo "<input type='radio' name=\"".$k."\" value='true' disabled>是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$k."\" value='false' disabled>否";
                    }  
                    echo '</td>';
                } else {
                    echo '<td>'."<input type='text' name=\"".$k."\" maxlength='20' value=\"".$reportS3[$i]['ans']."\" disabled>".'</td>';
                }
                if ($checkrefS4=='V/X') {
                    echo '<td>';
                    if($reportS4[$i]['ans']=='true') {
                        echo "<input type='radio' name=\"".$l."\" value='true' checked disabled>是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$l."\" value='false' disabled>否";
                    } elseif($reportS4[$i]['ans']=='false') {
                        echo "<input type='radio' name=\"".$l."\" value='true' disabled>是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$l."\" value='false' checked disabled>否";
                    } else {
                        echo "<input type='radio' name=\"".$l."\" value='true' disabled>是&nbsp&nbsp&nbsp&nbsp";
                        echo "<input type='radio' name=\"".$l."\" value='false' disabled>否";
                    }  
                    echo '</td>';
                } else {
                    echo '<td>'."<input type='text' name=\"".$l."\" maxlength='20' value=\"".$reportS4[$i]['ans']."\" disabled>".'</td>';
                }
                echo '</tr>';
                
            }                        
            ?>
            <tr>
                <td>檢查者：</td>
                <?php 
                //第一季
                if(!isset($reportS1[0]['rDate']) or $reportS1[0]['rDate']==''){//第一季沒有保養
                    echo '<td><div class="text-left d-inline font-weight-bold">尚未保養</td>';
                }else{//第一季已保養
                    echo '<td>'.$checkUser1.'</td>';
                }
                //第2季
                if(!isset($reportS2[0]['rDate']) or $reportS2[0]['rDate']==''){//第3季沒有保養
                    echo '<td><div class="text-left d-inline font-weight-bold">尚未保養</td>';
                }else{//第一季已保養
                    echo '<td>'.$checkUser2.'</td>';
                }
                //第3季
                if(!isset($reportS3[0]['rDate']) or $reportS3[0]['rDate']==''){//第3季沒有保養
                    echo '<td><div class="text-left d-inline font-weight-bold">尚未保養</td>';
                }else{//第3季已保養
                    echo '<td>'.$checkUser3.'</td>';
                }
                //第4季
                if(!isset($reportS4[0]['rDate']) or $reportS4[0]['rDate']==''){//第4季沒有保養
                    echo '<td><div class="text-left d-inline font-weight-bold">尚未保養</td>';
                }else{//第4季已保養
                    echo '<td>'.$checkUser4.'</td>';
                }
                ?>
            </tr>
            <tr>
                <td>複查者：</td>
                <?php                
                //判斷哪個季度還沒有保養
                if(!isset($reportS1[0]['rDate']) or $reportS1[0]['rDate']==''){//第一季沒有保養                   
                    echo '<td><div class="text-left d-inline font-weight-bold">尚未保養</div></td>';
                }elseif(!isset($checkDouble1) or $checkDouble1==''){//第一季有保養，未審核
                    echo '<td><div class="text-left d-inline font-weight-bold">尚未審核</div></td>';
                }else{//第一季完成審核
                    echo '<td>'.$checkDouble1.'</td>';
                }
                //第二季
                if(!isset($reportS2[0]['rDate']) or $reportS2[0]['rDate']==''){//第2季沒有保養                   
                    echo '<td><div class="text-left d-inline font-weight-bold">尚未保養</div></td>';
                }elseif(!isset($checkDouble2) or $checkDouble2==''){//第2季有保養，未審核
                    echo '<td><div class="text-left d-inline font-weight-bold">尚未審核</div></td>';
                }else{//第2季完成審核
                    echo '<td>'.$checkDouble2.'</td>';
                }
                //第三季
                if(!isset($reportS3[0]['rDate']) or $reportS3[0]['rDate']==''){//第3季沒有保養                   
                    echo '<td><div class="text-left d-inline font-weight-bold">尚未保養</div></td>';
                }elseif(!isset($checkDouble3) or $checkDouble3==''){//第3季有保養，未審核
                    echo '<td><div class="text-left d-inline font-weight-bold">尚未審核</div></td>';
                }else{//第3季完成審核
                    echo '<td>'.$checkDouble3.'</td>';
                }
                //第四季
                if(!isset($reportS4[0]['rDate']) or $reportS4[0]['rDate']==''){//第4季沒有保養                   
                    echo '<td><div class="text-left d-inline font-weight-bold">尚未保養</div></td>';
                }elseif(!isset($checkDouble4) or $checkDouble4==''){//第4季有保養，未審核
                    echo '<td><div class="text-left d-inline font-weight-bold">尚未審核</div></td>';
                }else{//第4季完成審核
                    echo '<td>'.$checkDouble4.'</td>';
                }                
                ?>  
            </tr>
            <tr>
                <td>上半年度消防檢查：<?= $year.'上半年度' ?></td>
                <!-- <td></td> -->
                <?php switch ($choicedata) {
                    case '1':?>
                        <td colspan='3'>斷電測試電壓：<input type='text' name='va[]' maxlength='20' value="<?= $va1[0] ?>" disabled>V</td>
                        <?PHP
                        break;
                    
                    case '2':?>
                        <td colspan='3'>運轉電流：<input type='text' name='va[]' maxlength='20' value="<?= $va1[0] ?>" disabled>A</td>
                        <?PHP
                        break;
                    
                    case '3':?>
                        <td colspan='3'>運轉電流：主<input type='text' name='va[1]' maxlength='20' value="<?= $va1[0] ?>" disabled>A&nbsp輔：<input type='text' name=$va[2] maxlength='20' value="<?= $va1[1] ?>" disabled></td>
                        <?PHP
                        break;
                }
                 ?>
                <td><?= '檢查者:'.$check_H1 ?></td>
                <!-- <td></td> -->
            </tr>
            <tr>
                <td>下半年度消防檢查：<?= $year.'下半年度' ?></td>
                <!-- <td></td> -->
                <?php switch ($choicedata) {
                    case '1':?>
                        <td colspan='3'>斷電測試電壓：<input type='text' name='va[]' maxlength='20' value="<?= $va2[0] ?>" disabled>V</td>
                        <?PHP
                        break;
                    
                    case '2':?>
                        <td colspan='3'>運轉電流：<input type='text' name='va[]' maxlength='20' value="<?= $va2[0] ?>" disabled>A</td>
                        <?PHP
                        break;
                    
                    case '3':?>
                        <td colspan='3'>運轉電流：主<input type='text' name='va[]' maxlength='20' value="<?= $va2[0] ?>" disabled>A&nbsp輔：<input type='text' name='va[]' maxlength='20' value="<?= $va2[1] ?>" disabled>A</td>
                        <?PHP
                        break;
                }
                 ?>
                <td><?= '檢查者:' ?><?= $check_H2 ?></td>
                <!-- <td></td> -->
            </tr>
            </tbody>
        </table>
        </div>
        <!-- 備註欄 -->
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">備註：</span>
            </div>
            <?php if(!isset($Mdata[0]['remark'])or $Mdata[0]['remark']==""){?>
            <textarea class="form-control" name="remark" aria-label="With textarea" ><?= @$reportS1[0]['remark'].'|'.@$reportS2[0]['remark'].'|'.@$reportS3[0]['remark'].'|'.@$reportS4[0]['remark'] ?></textarea>
            <?php }else{ ?>  
            <textarea class="form-control" name="remark" aria-label="With textarea" ><?= $Mdata[0]['remark'] ?></textarea> 
            <?php } ?>    
        </div>       

        <!-- 送出鈕 -->    
            <div class="d-flex justify-content-end">
                <a href="mmt_list_f.php" type="button" class="btn btn-primary mt-4 rounded d-block mr-3">離開</a>
            </div>
    </form>
    </div>
</body>
</html>