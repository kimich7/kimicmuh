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
    
    
    
    $check_date=date("Y-m-d");//準備寫入的保養時間
    $idnum=date("Ymd");//用來當主表id的時間戳記
    //$macnum=$mmtsysNo.'-'.$mmtbuildNo.'-';//設備編號
    $mmt_a_m_id=$mmtsysNo.$mmtbuildNo.$idnum;//主表id

    switch ($mmtbuildNo) {
        case 'A':
            $tid=55;
            break;
        case 'B':
            $tid=56;
            break;
        case 'C':
            $tid=57;
            break;
        case 'D':
            $tid=58;
            break;    
        case 'E':
            $tid=59;
            break;
        case 'G':
            $tid=60;
            break;
        case 'H':
            $tid=61;
            break;
        case 'I':
            $tid=62;
            break;
        case 'P':
            $tid=63;
            break;       
    }
    $reportName=item("SELECT k.id,k.tableName,k.dateKind FROM FA.MMT_KIND AS k LEFT JOIN FA.MMT_A AS a ON k.id=a.tid WHERE k.id=$tid ");
    //撈取檢查項目
     $str="SELECT a.id,a.checkName,a.ref,a.checkKind,a.tid FROM FA.MMT_A AS a LEFT JOIN FA.MMT_KIND as k ON a.tid=k.id WHERE k.id=$tid";      
     $catquery=$pdo->query($str) ;

    //寫入資料庫
    if (isset($_POST["action"])&&($_POST["action"]=="add")) {
        $rTime= date('Y-m-d H:i:s');
        $num=$_POST["num"];//迴圈數量
        $rmark=$_POST["remark"];//備註
        $tid=$_POST['tableid'];//報表id
        @$rdatekind_Array=$_POST['b'];//保養週期答案
        @$rdatekind= implode(",", $rdatekind_Array);
        $title=$_POST["title"];//Title
        $MMT_AtableMid=$_POST['mid'] ;//主表id
        $bid=$_POST['bid'] ;//大樓id        
        $rdate=$_POST['rdate'] ;//保養日期
        $remp=$_POST['emp'] ;//保養人員 
        
        
        $err=0;//未填寫欄位的數量歸0
        for ($i=0; $i < $num; $i++) {                                    
            
            $checkid=$i+200;//保養項目id   
            $check_id = $_POST["$checkid"];//點檢項目ID 
            $ans=$_POST["$i"];//答案            
            $causenum=$i+300;//故障原因id
            $cause=$_POST["$causenum"];
            $reportCheck="SELECT COUNT(id) FROM FA.MMT_HtableM WHERE id='$MMT_AtableMid' ";//id='$mmt_a_m_id' 
            $reportCheck_query=Current($pdo->query($reportCheck)->fetch());
            if (empty($ans)) {
                $ans_no=null;
                $err=$err+1;//判斷有多少欄位沒有填寫
            }
            if ($reportCheck_query ==0) {                    
                $insert_master_str="INSERT INTO FA.MMT_HtableM(id,bid,title,rdate,datekind,tid,remark,emp,status) VALUES ('$MMT_AtableMid','$bid','$title','$rTime','$rdatekind','$tid','$rmark','$remp','W') ";
                $insert_master =$pdo->exec($insert_master_str);                    
                
                $insert_detail_str="INSERT INTO FA.MMT_HtableD(checkid,ans,cause,improvement,mid) VALUES ($check_id,'$ans','$cause','','$MMT_AtableMid')";
                $insert_detail =$pdo->exec($insert_detail_str);
            } else {
                $insert_detail_str="INSERT INTO FA.MMT_HtableD(checkid,ans,cause,improvement,mid) VALUES ($check_id,'$ans','$cause','','$MMT_AtableMid')";
                $insert_detail =$pdo->exec($insert_detail_str);
            } 
        }
        $pdo=null;
        if ($err>=1) {//如果欄位沒有填寫就做下面的處理
            echo "<script>alert('有部分項目未填寫就送出，下次要補上時請選擇修改的方式')</script>";
            header("Location: mmt_list_h.php");
            //header("refresh:3;url= mtinsert.html");
            //echo "<script>window.close();</script>";
        } else {
            header("Location: mmt_list_h.php");
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
    <title>電梯對講機測試紀錄表單</title>
</head>

<body class="table_bg">
    <div class="container border border-info mt-5">
    <form action="" method="post" name="mmt_table_a">
        <h2 class="text-center font-weight-bold"><?= $reportName[0]['tableName']; ?></h2>
        <div class="row my-3">
            <div class="col">
                <p class="d-inline font-weight-bold">棟別：<?= $mmtbuildName ?></p>
            </div>
            <div class="col text-right">
                <p class="d-inline font-weight-bold">保養日期：<?= $check_date ?></p>
            </div>
        </div>       
        
        <!--表格主體-->
        <table class="table my-5">
            <thead>
                <th>項&nbsp&nbsp&nbsp&nbsp目</th>
                <th>結&nbsp&nbsp&nbsp&nbsp果</th>
                <th>故障原因及情形</th>              
            </thead>
            <?php //a.id,a.checkName,a.ref,a.checkKind,a.tid
            $title=$mmtbuildNo.'棟-'.$reportName[0]['tableName'].$idnum;
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
                $checkid=$i+200;//檢查項目ID
                $cause=$i+300;//故障原因id
                $improvement=$i+400;//故障修復結果追蹤id

                echo  '<td>'.$catarr[$i]['checkName'].'</td>';
                if ($catarr[$i]['checkKind']=='檢查項目') {
                    echo '<td>';
                    echo "<input type='radio' name=\"".$i."\" value='true' checked>是&nbsp&nbsp&nbsp&nbsp";
                    echo "<input type='radio' name=\"".$i."\" value='false'>否";  
                    echo '</td>';          
                } elseif ($catarr[$i]['ref']=='單相/三相') {
                    echo '<td>';
                    echo "<input type='radio' name=\"".$i."\" value='single'>單相&nbsp&nbsp&nbsp&nbsp";
                    echo "<input type='radio' name=\"".$i."\" value='three'>三相";
                    echo '</td>'; 
                }elseif ($catarr[$i]['ref']=='單相') {
                    echo '<td>';
                    echo "<input type='radio' name=\"".$i."\" value='single' checked>單相";
                    echo '</td>'; 
                }else{
                    echo '<td>'."<input type='text' name=\"".$i."\" maxlength='20'>".'</td>';
                }
                echo '<td>'."<input type='text' name=\"".$cause."\" maxlength='20'>".'</td>';//故障原因
                echo "<input type='hidden' name=\"".$checkid."\" value=\"".$catarr[$i]['id']."\">";//檢查項目id                
            }
            echo "<input type='hidden' name='mid' value=\"".$mmt_a_m_id."\">";//主表id
            echo "<input type='hidden' name='bid' value=\"".$mmtbuildNo."\">";//大樓id
            echo "<input type='hidden' name='tableid' value=\"".$tid."\">";//tableid
            echo "<input type='hidden' name='rdate' value=\"".$check_date."\">";//保養日期
            echo "<input type='hidden' name='emp' value=\"".$userID."\">";//保養人員
            echo "<input type='hidden' name='title' value=\"".$title."\">";//title
            //echo "<input type='hidden' name='macNo' value=\"".$macnum."\">";//設備完整編號
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
          <div class="row my-3">
            <div class="col">
                <p class="d-inline font-weight-bold">工務室：</p>
            </div>
            <div class="col text-center">
                <p class="d-inline font-weight-bold">專責人員：</p>
            </div>
            <div class="col text-right">
                <p class="d-inline font-weight-bold">保養人員：<?= $username ?></p>
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