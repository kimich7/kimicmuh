<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");    
    session_start();
    
    $userID=$_SESSION["login_number"];//登錄人員ID
    $username=$_SESSION["login_member"] ;//登錄人員名稱
    $mmtsysNo=$_GET["mmtsysa"];//保養系統代號
    $mmtsysName=sql_database('sName','FA.MMT_sys','id',$mmtsysNo);//系統名稱
    $mmtbuildNo=$_GET["mmtbuilda"];//大樓代號
    $mmtbuildName=sql_database('bName','FA.MMT_build','id',$mmtbuildNo);//大樓名稱
    $mmtfloorNo=$_GET["mmtfloora"];//樓層代號
    $mmtfloorName=sql_database('fName','FA.MMT_floor','fid',$mmtfloorNo);//樓層名稱
    $mmtequipNo=$_GET["mmtequipa"];//設備代號
    $mmtequipName=sql_database('eName','FA.MMT_equip','id',$mmtequipNo);//設備名稱
    $mmtequipNum=$_GET["mmtequipNoa"];//設備編號
    $reportName=item("SELECT k.id,k.tableName,k.dateKind FROM FA.MMT_KIND AS k LEFT JOIN FA.MMT_equipNo AS n ON k.id=n.tid WHERE n.id='$mmtequipNum' AND n.eid='$mmtequipNo' AND n.fid='$mmtfloorNo' AND n.bid='$mmtbuildNo' AND n.sid='$mmtsysNo' ");
    
    $mmt_f_season=$_GET["mmtfseason"];//季別
    //$eidName=item("SELECT eName FROM FA.MMT_equip WHERE id='$mmtequipNo");
    $eidName=sql_database('eName','FA.MMT_equip','id',$mmtequipNo);

    $check_date=date("Y-m-d");//準備寫入的保養時間
    $idnum=date("Ymd");//用來當主表id的時間戳記

    $checkYear=date("Y");//檢查年度
    $checkm=date("m");//檢查月
    $checkd=date("d");//檢查日
    $checkmd=$checkm.'月'.$checkd.'日';//檢查月日

    $macnum=$mmtsysNo.'-'.$mmtbuildNo.'-'.$mmtfloorNo.'-'.$mmtequipNo.'-'.$mmtequipNum;//設備編號
    $mmt_a_m_id=$mmtsysNo.$mmtbuildNo.$mmtfloorNo.$mmtequipNo.$mmtequipNum.$checkYear;//主表id
    //撈取檢查項目
     $str="SELECT a.id,a.checkName,a.ref,a.checkKind,a.tid,k.choice FROM FA.MMT_A AS a LEFT JOIN FA.MMT_KIND as k ON a.tid=k.id LEFT JOIN  FA.MMT_equipNo as n ON n.tid=k.id WHERE n.id='$mmtequipNum' AND n.bid='$mmtbuildNo' AND n.fid='$mmtfloorNo' AND n.eid='$mmtequipNo' AND n.sid='$mmtsysNo'";      
     $catquery=$pdo->query($str) ;

    //寫入資料庫
    if (isset($_POST["action"])&&($_POST["action"]=="add")) {
        $rTime= date('Y-m-d H:i:s');
        $rDate= date('Y-m-d');
        $num=$_POST["num"];//迴圈數量
        $rmark=$_POST["remark"];//備註
        $tid=$_POST['tableid'];//報表id
        //$rdatekind_Array=$_POST['b'];//保養週期答案
        //$rdatekind= implode(",", $rdatekind_Array);
        $mmt_f_season=$_POST['fseason'] ;

        $MMT_FtableMid=$_POST['mid'] ;//主表id
        $bid=$_POST['bid'] ;//大樓id
        $fid=$_POST['fid'] ;//樓層id
        $eid=$_POST['eid'] ;//設備id
        $rdate=$_POST['rdate'] ;//保養日期
        $remp=$_POST['emp'] ;//保養人員
        $macNo=$_POST['macNo'] ;//設備完整編號

        
        $err=0;//未填寫欄位的數量歸0
        for ($i=0; $i < $num; $i++) {                                    
            
            $checkid=$i+200;//保養項目id   
            $check_id = $_POST["$checkid"];//點檢項目ID 
            $ans=$_POST["$i"];//答案            

            $reportCheck="SELECT COUNT(id) FROM FA.MMT_FtableM WHERE id='$mmt_a_m_id' ";
            $reportCheck_query=Current($pdo->query($reportCheck)->fetch());
            if (empty($ans)) {
                $ans_no=null;
                $err=$err+1;//判斷有多少欄位沒有填寫
            }
            if ($reportCheck_query ==0) {                    
                $insert_master_str="INSERT INTO FA.MMT_FtableM(id,bid,fid,eid,datekind,tid,macNo,status,timestamp) VALUES ('$MMT_FtableMid','$bid','$fid','$eid','S','$tid','$macNo','W','$rTime') ";
                $insert_master =$pdo->exec($insert_master_str);                    
                
                $insert_detail_str="INSERT INTO FA.MMT_FtableD(checkid,ans,checkUserid,rDate,remark,fseason,mid) VALUES ($check_id,'$ans','$remp','$rDate','$rmark','$mmt_f_season','$MMT_FtableMid')";
                $insert_detail =$pdo->exec($insert_detail_str);
            } else {
                $insert_detail_str="INSERT INTO FA.MMT_FtableD(checkid,ans,checkUserid,rDate,remark,fseason,mid) VALUES ($check_id,'$ans','$remp','$rDate','$rmark','$mmt_f_season','$MMT_FtableMid')";
                $insert_detail =$pdo->exec($insert_detail_str);
            } 
        }
        $pdo=null;
        if ($err>=1) {//如果欄位沒有填寫就做下面的處理
            echo "<script>alert('有部分項目未填寫就送出，下次要補上時請選擇修改的方式')</script>";
            header("Location: mmt_list_f.php");
            //header("refresh:3;url= mtinsert.html");
            //echo "<script>window.close();</script>";
        } else {
            header("Location: mmt_list_f.php");
            //echo "<script>window.close();</script>";
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
    <title>設備保養表單</title>
</head>

<body class="table_bg">
    <div class="container border border-info mt-5">
    <form action="" method="post" name="mmt_table_a">
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

        <div class="row my-3">
            <div class="col">
                <p class="d-inline font-weight-bold">檢查日期：<?= $checkmd ?></p>
            </div>
            <div class="col text-right">
                <p class="d-inline font-weight-bold">檢查者：<?= $username ?></p>
            </div>            
        </div>
        <?php
        echo "<input type='hidden' name='tableid' value=\"".$reportName[0]['id']."\">";//報表id 
        echo "<input type='hidden' name='fseason' value=\"".$mmt_f_season."\">";//檢查季
        ?>
        <!--表格主體-->
        <table class="table my-5">
            <thead>
                <th>項&nbsp&nbsp&nbsp&nbsp目</th>
                <th>結&nbsp&nbsp&nbsp&nbsp果</th>                
            </thead>
            <?php //a.id,a.checkName,a.ref,a.checkKind,a.tid
            while ($row =$catquery -> fetch()) {
                $catarr[] = array(
                    'id' => $row['id'],
                    'checkName' => $row['checkName'],
                    'ref' => $row['ref'],
                    'checkKind' => $row['checkKind'],
                    'tid' => $row['tid']
                );
            }
            $num = count($catarr);          
            
            for ($i=0; $i < $num; $i++) {?>
            <tbody class="text-primary">
            <?php 
                $checkid=$i+200;
                echo  '<td>'.$catarr[$i]['checkName'].'</td>';
                if ($catarr[$i]['checkKind']=='檢查項目') {
                    echo '<td>';
                    echo "<input type='radio' name=\"".$i."\" value='true' checked>是&nbsp&nbsp&nbsp&nbsp";
                    echo "<input type='radio' name=\"".$i."\" value='false'>否";  
                    echo '</td>';          
                } elseif ($catarr[$i]['ref']=='V') {
                    echo '<td>'."<input type='text' name=\"".$i."\" maxlength='20'>".'(V)</td>';                    
                }elseif ($catarr[$i]['ref']=='A') {
                    echo '<td>'."<input type='text' name=\"".$i."\" maxlength='20'>".'(A)</td>';
                }else{
                    echo '<td>'."<input type='text' name=\"".$i."\" maxlength='20'>".'</td>';
                }
                echo "<input type='hidden' name=\"".$checkid."\" value=\"".$catarr[$i]['id']."\">";//檢查項目id                
            }
            echo "<input type='hidden' name='mid' value=\"".$mmt_a_m_id."\">";//主表id
            echo "<input type='hidden' name='bid' value=\"".$mmtbuildNo."\">";//大樓id
            echo "<input type='hidden' name='fid' value=\"".$mmtfloorNo."\">";//樓層id
            echo "<input type='hidden' name='eid' value=\"".$mmtequipNo."\">";//設備id
            echo "<input type='hidden' name='rdate' value=\"".$check_date."\">";//保養日期
            echo "<input type='hidden' name='emp' value=\"".$userID."\">";//保養人員
            echo "<input type='hidden' name='macNo' value=\"".$macnum."\">";//設備完整編號
            ?>
            </tbody>
        </table>
        <!-- 備註欄 -->
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">備註：</span>
            </div>
            <textarea class="form-control" name="remark" aria-label="With textarea"></textarea>
        </div>
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="num" value='<?= $num ?>'>
        
        <!-- 送出鈕 -->    
            <div class="d-flex justify-content-end">
                <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit">送出</button>
            </div>
    </form>
    </div>
</body>
</html>