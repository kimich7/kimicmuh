<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    
    session_start();

    

    if (empty($_SESSION["login_member"])) {
        echo '<h2>'.'尚未登錄，無法進行設備抄表，請先登錄後再重新執行'.'</h2>';
        header("refresh:3;url= index.html");
    } else {
        $user=$_SESSION["login_member"];
        $userID=sql_database('e_number','FA.Employee','cname',$user);

        //mtinsert資料帶入
        if (empty($_GET["bday"])) {
            $check_date=date("Y-m-d");
        } else {
            @$check_date=$_GET["bday"];//日期
        }
        if (!empty($_GET["class"])) {
            $shiftNo=$_GET["class"];//班別
            $class=sql_database('shiftName','FA.Shift_Table','shiftID',$shiftNo);
        }else{            
            $shiftNo="";
        }
        if (isset($_GET["build"])&& $_GET["build"]!="" ) {
            $buildNo=$_GET["build"];//棟別
        } else {
            $buildNo=$_GET["build"];//棟別
        }
        if (isset($_GET["buildingfloor"])&& $_GET["buildingfloor"]!="" ) {
            $floorID=$_GET["buildingfloor"];//棟別
        } else {
            $floorID=$_GET["buildingfloor"];//棟別
        }
        if($shiftNo==1){
            $shitfNo_Pre=3;
            $lastday=Date("Y-m-d",strtotime($check_date."-1 day"));
        }else{
            $shitfNo_Pre=$shiftNo-1;
            $lastday=$check_date;
        }
        
        //篩選出棟別
        $build=sql_database('B_name','FA.Building','b_number',$buildNo);
        //篩選出系統別
        //$system=sql_database('sysName','FA.Equipment_System_Group','sysID',$sysNo);
        //篩選出樓層別
        $floorName=sql_database('floorName','FA.BuildingFloor','floorID',$floorID);
        //篩選出班別

        if (isset($_POST["action"])&&($_POST["action"]=="add")) {
            $rTime= date('Y-m-d H:i:s');
            if (empty($_POST["shift"])) {                
                $shift_no=$_POST["qr_class"];
            } else{
                $shift_no=$_POST["shift"];//點檢班別ID
            }
            
            //Master輸入
            $build_no=$_POST["build"];//棟別代號 
            $date_ch=$_POST["date_c"];//檢點日期
            //Detail輸入
            $remark=$_POST["remark"];//備註 
            //$equip_no=$_POST["equip"];//設備(鍋爐、給水、熱水......等等)ID
            $loop_count=$_POST["loop_num"];//迴圈數量
            $floorID = $_POST["floorID"];//樓層資訊
            $error_num=0;//不合格的數量          
            $err=0;//未填寫欄位的數量歸0
            for ($i=0; $i < $loop_count; $i++) {                                    
                $q=$i+200;
                $qf=$i+400;
                $qID=$i+600;
                $qMode=$i+800;
                $check=$i+900;

                @$ans_no=$_POST["$i"];
                $sys_no=$_POST["$q"];//系統ID
                $ref_no = $_POST["$qf"];//參考值
                $equip_check = $_POST["$qID"];//點檢項目ID
                $equip_ansMode= $_POST["$qMode"];//點檢資料型態
                @$check_ans = $_POST["$check"];

                if(!isset($check_ans) or $check_ans==""){
                    if($ans_no=='false'){
                        $check_insert='false';
                    }else{
                        $check_insert='true';
                    }
                }else{
                    $check_insert=$check_ans;
                }
                if($check_insert=='false'){
                    $error_num=$error_num+1;
                }

                if ($equip_ansMode=='plural') {
                    @$qu=$_POST["b"];
                    @$ans_no= implode(",", $qu);
                }
                if ($equip_ansMode=='plural_1') {
                    @$qc=$_POST["c"];
                    @$ans_no= implode(",", $qc);
                }

                $sql_master_check="SELECT COUNT(recordID) FROM FA.Water_System_Record_Master WHERE b_number='$build_no' AND rDate='$date_ch' AND sysID=$sys_no ";
                $master_check_query=Current($pdo->query($sql_master_check)->fetch());
                if (empty($ans_no)) {
                    $ans_no=null;
                    $err=$err+1;//判斷有多少欄位沒有填寫
                }
                if ($master_check_query ==0) {                    
                    $sql_insert_master="INSERT INTO FA.Water_System_Record_Master(b_number,rDate,sysID) VALUES ('$build_no','$date_ch',$sys_no) ";
                    $insert_master =$pdo->exec($sql_insert_master);       

                    $sql_select="SELECT recordID FROM FA.Water_System_Record_Master WHERE sysID=$sys_no AND rDate='$date_ch' AND b_number='$build_no'";
                    $select_master =$pdo->query($sql_select)->fetch();
                    $MasterID=$select_master['recordID'];

                    $sql_insert_detail="INSERT INTO FA.Water_System_Record_Detail(equipCheckID,ref,shiftID,r_member,remark,recordID,checkResult,floorID,rDate,rTime) VALUES ($equip_check,'$ref_no',$shift_no,'$userID','$remark',$MasterID,'$ans_no','$floorID','$date_ch','$rTime')";
                    $insert_detail =$pdo->exec($sql_insert_detail);

                    $sql_select_d="SELECT recordDetailID FROM FA.Water_System_Record_Detail WHERE equipCheckID=$equip_check and shiftID=$shift_no and r_member= '$userID' and recordID=$MasterID and checkResult ='$ans_no' and floorID = '$floorID' and rDate='$date_ch' AND rTime='$rTime'";
                    $select_d =$pdo->query($sql_select_d)->fetch();
                    $recordDetailID=$select_d['recordDetailID'];


                    $sql_insert_error="INSERT INTO FA.Water_System_Error(mid,rid,eid,ans) VALUES ($MasterID,$recordDetailID,$equip_check,'$check_insert')";
                    $insert_error =$pdo->exec($sql_insert_error);
                } else {
                    $sql_select="SELECT recordID FROM FA.Water_System_Record_Master WHERE sysID=$sys_no AND rDate='$date_ch' AND b_number='$build_no'";
                    $select_master =$pdo->query($sql_select)->fetch();
                    $MasterID=$select_master['recordID'];

                    $sql_insert_detail="INSERT INTO FA.Water_System_Record_Detail(equipCheckID,ref,shiftID,r_member,remark,recordID,checkResult,floorID,rDate,rTime) VALUES ($equip_check,'$ref_no',$shift_no,'$userID','$remark',$MasterID,'$ans_no','$floorID','$date_ch','$rTime')";
                    $insert_detail =$pdo->exec($sql_insert_detail);

                    $sql_select_d="SELECT recordDetailID FROM FA.Water_System_Record_Detail WHERE equipCheckID=$equip_check and shiftID=$shift_no and r_member= '$userID' and recordID=$MasterID and checkResult ='$ans_no' and floorID = '$floorID' and rDate='$date_ch' AND rTime='$rTime'";
                    $select_d =$pdo->query($sql_select_d)->fetch();
                    $recordDetailID=$select_d['recordDetailID'];


                    $sql_insert_error="INSERT INTO FA.Water_System_Error(mid,rid,eid,ans) VALUES ($MasterID,$recordDetailID,$equip_check,'$check_insert')";
                    $insert_error =$pdo->exec($sql_insert_error);
                } 
                

            }
            $pdo=null;
            if ($err>=1 and $error_num<1) {//如果欄位沒有填寫就做下面的處理
                echo "<script>alert('有部分項目未填寫就送出，下次要補上時請選擇修改的方式');Location: mtinsert.html</script>";                            
            }
            if ($err<1 and $error_num>=1) {//如果欄位沒有填寫就做下面的處理
                echo "<script>alert('有部分項目為不合格');Location: mtinsert.html</script>";                            
            }
            if ($err>=1 and $error_num>=1) {//如果欄位沒有填寫就做下面的處理
                echo "<script>alert('有部分項目為不合格及部分項目未填寫就送出，下次要補上時請選擇修改的方式');Location: mtinsert.html</script>";                            
            }

            if($err<1 and $error_num<1) {
                header("Location: mtinsert.html");
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
        <?PHP
        $tablecheck=tableCheck($check_date,$floorID,$shiftNo);
        if ($tablecheck!=0) {
            echo '<h2>'.'此樓層今天已經抄寫完成，如需修改請從修改表單進入，三秒後回到抄表選擇頁面'.'</h2>';
            header("refresh:3;url= mtinsert.html");
        } else {
            //檢查項目
            $sql_equip_check = "SELECT equipCheckID,equipCheckName,ref,answerMode,sysID  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$buildNo' ORDER BY sysID,equipCheckID";
            $query_equip=$pdo->query($sql_equip_check);

            $sql_equip_check_last = "SELECT a.checkResult FROM FA.Water_System_Record_Detail as a Left join FA.Equipment_Check as b on a.equipCheckID = b.equipCheckID WHERE a.rDate='$lastday' AND b.floorID='$floorID' AND b.b_number='$buildNo' AND shiftID=$shitfNo_Pre ORDER BY b.sysID,b.equipCheckID";
            $query_check_last=$pdo->query($sql_equip_check_last);
            $query_check_last_1=$pdo->query($sql_equip_check_last)->fetchAll();
            $lastAnsNum=COUNT($query_check_last_1);

            $equip_check_num="SELECT COUNT(equipCheckID)  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$buildNo'";
            $equip_check_no=Current($pdo->query($equip_check_num)->fetch());

            echo '<div class="container border border-info mt-5">';?>
                <form id="wa" action="" method="post" name="wa" onkeypress="if (event.keyCode==13) return false;">
                <?php
                    echo '<h2 class="text-center font-weight-bold">'.'中國醫藥大學附設醫院-'.$build.'</h2>';
                    //班別/檢查者/日期欄
                    echo '<div class="row my-3">';
                        echo '<div class="col">';
                            echo '<p class="d-inline font-weight-bold">班別：</p>';                    
                            if (empty($_GET["class"])) {
                                echo '<select class="form-control mb-3" name="qr_class" id="qr_Three_shifts">';
                                    echo '<option selected>請選擇班別</option>';
                                    echo '<option value="1">早班</option>';
                                    echo '<option value="2">中班</option>';
                                    echo '<option value="3">晚班</option>';
                                echo '</select>';
                            } else {
                                echo '<p class="d-inline text-primary">'.$class.'</p>';
                            }
                        echo '</div>';
                        echo '<div class="col text-center">';
                            echo '<p class="d-inline font-weight-bold">巡檢人員：</p>';
                            echo '<p class="d-inline text-primary">'.$user.'</p>';
                        echo '</div>';
                        echo '<div class="col text-right">';
                            echo '<p class="d-inline font-weight-bold">檢查日期：</p>';
                            echo '<p class="d-inline text-primary">'.$check_date.'</p>';
                        echo '</div>';
                    echo '</div>';
                    
                    //表格主體
                    echo '<table class="table my-5">';
                        echo '<thead>';
                            echo '<th>檢查項目</th>';
                            echo '<th>參考值</th>';
                            echo '<th>結果</th>';
                            echo '<th align="center" valign="middle">|判斷欄位|<br>請確認資料<br>是否合格</th>';
                            echo '<th align="center" valign="middle">|前一班的紀錄|<br>如無顯示資料表示<br>上一班無填寫紀錄</th>';
                        echo '</thead>';                
                        for ($i=0; $i < $equip_check_no; $i++) {
                        $q=$i+200;
                        $qf=$i+400;
                        $qID=$i+600;
                        $qMode=$i+800;
                        $check=$i+900;
                        $equipinfo=$query_equip->fetch(PDO::FETCH_ASSOC);
                        if ($lastAnsNum>0) {
                            $ansinfo=$query_check_last->fetch(PDO::FETCH_ASSOC);                        
                            $lastans=$ansinfo['checkResult'];
                        }
                        echo '<tbody class="text-primary">';
                        echo '<tr>';
                            echo '<td>'.$equipinfo['equipCheckName'].'</td>';
                            echo '<td>'.$equipinfo["ref"].'</td>';                    
                            switch ($equipinfo["answerMode"]) {
                                case 'choiceTF':
                                    echo '<td>';
                                        echo "<input type='radio' name=\"".$i."\" value='true' checked>合格";
                                        echo "<input type='radio' name=\"".$i."\" value='false'>不合格";
                                    echo '</td>';
                                    echo '<td></td>';
                                    echo '<td>';
                                    if ($lastAnsNum>0) {
                                        if ($lastans=='true' or $lastans==1){
                                            echo "<input type='radio'  checked DISABLED>合格";
                                            echo "<input type='radio'  DISABLED>不合格";
                                        }else{
                                            echo "<input type='radio'  DISABLED>合格";
                                            echo "<input type='radio'  checked DISABLED>不合格";
                                        }
                                    }                                    
                                    echo '</td>';
                                    break;
                                case 'choiceHA':
                                    echo '<td>';                                        
                                        echo "<input type='radio' name=\"".$i."\" value='auto' checked>自動";
                                        echo "<input type='radio' name=\"".$i."\" value='handle'>手動";
                                    echo '</td>';
                                    echo '<td></td>';
                                    echo '<td>';
                                    if ($lastAnsNum>0) {
                                        if ($lastans=='auto'){
                                            echo "<input type='radio'  checked DISABLED>自動";
                                            echo "<input type='radio'  DISABLED>手動";
                                        }
                                        if($lastans=='handle'){
                                            echo "<input type='radio'  DISABLED>自動";
                                            echo "<input type='radio'  checked DISABLED>手動";                                    
                                        }
                                    }
                                    echo '</td>';
                                    break;
                                case 'choiceFN':
                                    echo '<td>';
                                        echo "<input type='radio' name=\"".$i."\" value='ON' checked>ON";
                                        echo "<input type='radio' name=\"".$i."\" value='OFF'>OFF";                                        
                                    echo '</td>';
                                    echo '<td></td>';
                                    echo '<td>';
                                    if ($lastAnsNum>0) {
                                        if ($lastans=='ON'){
                                            echo "<input type='radio'  checked DISABLED>ON";
                                            echo "<input type='radio'  DISABLED>OFF";
                                        }
                                        if($lastans=='OFF'){
                                            echo "<input type='radio'  DISABLED>ON";
                                            echo "<input type='radio'  checked DISABLED>OFF";                                    
                                        }
                                    }
                                    echo '</td>';
                                    break;
                                case 'choiceRL':
                                    echo '<td>';
                                        echo "<input type='radio' name=\"".$i."\" value='remote' checked>遠端";                                        
                                        echo "<input type='radio' name=\"".$i."\" value='local'>本地";                                        
                                    echo '</td>';
                                    echo '<td></td>';
                                    echo '<td>';
                                    if ($lastAnsNum>0) {
                                        if ($lastans=='remote'){
                                            echo "<input type='radio'  checked DISABLED>遠端";
                                            echo "<input type='radio'  DISABLED>本地";
                                        }
                                        if($lastans=='local'){
                                            echo "<input type='radio'  DISABLED>遠端";
                                            echo "<input type='radio'  checked DISABLED>本地";                                    
                                        }
                                    }
                                    echo '</td>';
                                    break;
                                case 'choiceS12':
                                    echo '<td>';
                                        echo "<input type='radio' name=\"".$i."\" value='S1'>S1";
                                        echo "<input type='radio' name=\"".$i."\" value='S2'>S2";
                                    echo '</td>';
                                    echo '<td></td>';
                                    echo '<td>';
                                    if ($lastAnsNum>0) {
                                        if ($lastans=='S1'){
                                            echo "<input type='radio'  checked DISABLED>S1";
                                            echo "<input type='radio'  DISABLED>S2";
                                        }
                                        if($lastans=='S2'){
                                            echo "<input type='radio'  DISABLED>S1";
                                            echo "<input type='radio'  checked DISABLED>S2";                                    
                                        }
                                    }
                                    echo '</td>';
                                    break;
                                case 'choiceRG':
                                    echo '<td>';                                        
                                        echo "<input type='radio' name=\"".$i."\" value='green' checked>綠";
                                        echo "<input type='radio' name=\"".$i."\" value='red'>紅";
                                    echo '</td>';
                                    echo '<td></td>';
                                    echo '<td>';
                                    if ($lastAnsNum>0) {
                                        if ($lastans=='green'){
                                            echo "<input type='radio'  checked DISABLED>綠";
                                            echo "<input type='radio'  DISABLED>紅";
                                        }
                                        if($lastans=='red'){
                                            echo "<input type='radio'  DISABLED>綠";
                                            echo "<input type='radio'  checked DISABLED>紅";                                    
                                        }
                                    }
                                    echo '</td>';
                                    break;
                                case 'plural':
                                    echo '<td>';
                                    ?>                                
                                        <input type='checkbox' name="b[]" value='1'>1&nbsp&nbsp
                                        <input type='checkbox' name="b[]" value='2'>2
                                    <?php
                                    echo '</td>';
                                    break;
                                case 'plural_1':
                                    echo '<td>';
                                    ?>                                
                                        <input type='checkbox' name="c[]" value='1'>1&nbsp&nbsp
                                        <input type='checkbox' name="c[]" value='2'>2
                                    <?php
                                    echo '</td>';
                                    break;
                                default:
                                    echo '<td>'."<input type='text' name=\"".$i."\" maxlength='20'>".'</td>';
                                    echo '<td>';
                                    echo "<input type='radio' name=\"".$check."\" value='true' checked>合格";
                                    echo "<input type='radio' name=\"".$check."\" value='false'>不合格";
                                    echo '</td>';
                                    echo '<td>';
                                    if ($lastAnsNum>0) {
                                    echo "<input type='text'  maxlength='20' value=\"".$lastans."\"  Disabled>";
                                    }
                                    echo '</td>';
                                    break;
                            }
                            echo "<input type='hidden' name=\"".$q."\" value=\"".$equipinfo['sysID']."\">"; 
                            echo "<input type='hidden' name=\"".$qID."\" value=\"".$equipinfo['equipCheckID']."\">";
                            echo "<input type='hidden' name=\"".$qf."\" value=\"".$equipinfo['ref']."\">";
                            echo "<input type='hidden' name=\"".$qMode."\" value=\"".$equipinfo['answerMode']."\">"; 
                        echo '</tr>';                                  
                        }
                        echo '</tbody>';
                    echo '</table>';
        
            ?>
                <!-- 備註欄 -->
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">備註：</span>
                    </div>
                    <textarea class="form-control" name="remark" aria-label="With textarea"></textarea>
                </div>
                <!-- 傳送值到資料庫中 -->
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="build" value='<?= $buildNo ?>'>                        
                <input type="hidden" name="shift" value='<?= $shiftNo ?>'>
                <input type="hidden" name="date_c" value='<?= $check_date ?>'>
                <input type="hidden" name="loop_num" value='<?= $equip_check_no ?>'>
                <input type="hidden" name="floorID" value='<?= $floorID ?>'>
                <!-- 送出鈕 -->    
                <div class="d-flex justify-content-end">
                    <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit">送出</button>&nbsp&nbsp&nbsp
                    <a href='mtinsert.html' type='button' class="my-3 px-3 py-1 btn-outline-info text-dark">離開</a>
                </div> 
                                   
            </form><?php
        }
    }   ?></div>    
    </body>
    </html>