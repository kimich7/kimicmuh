<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    session_start();
    $user=$_SESSION["login_member"];
    $userID=sql_database('e_number','FA.Employee','cname',$user);

    //mtinsert資料帶入
    $check_date=$_POST["bday"];
    $buildNo=$_POST["build"];
    $sysNo=$_POST["system"];
    //$equipNo=$_POST["equipment"];
    $shiftNo=$_POST["class"];
    $floorID=$_POST["buildingfloor"];
    
    //SQL Binding
    //篩選出棟別
    $build=sql_database('B_name','FA.Building','b_number',$buildNo);         
    //篩選出系統別
    $system=sql_database('sysName','FA.Equipment_System_Group','sysID',$sysNo);
    //篩選出樓層別
    $floorName=sql_database('floorName','FA.BuildingFloor','floorID',$floorID);
    //篩選出班別
    $class=sql_database('shiftName','FA.Shift_Table','shiftID',$shiftNo);

    

    if (isset($_POST["action"])&&($_POST["action"]=="add")) {
        $rTime= date('Y-m-d H:i:s');
        $sys_no=$_POST["sys"];//系統ID 
        //Master輸入
        $build_no=$_POST["build"];//棟別代號 
        $date_ch=$_POST["date_c"];//檢點日期
        //Detail輸入
        $shift_no=$_POST["shift"];//點檢班別ID 
        $remark=$_POST["remark"];//備註 
        $equip_no=$_POST["equip"];//設備(鍋爐、給水、熱水......等等)ID
        $loop_count=$_POST["loop_num"];//迴圈數量
        $floorID = $_POST["floorID"];//樓層資訊
        
        $sql_equip_check = "SELECT equipCheckID,ref  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$build_no' AND sysID='$sys_no'";
        $equip_ch=$pdo->query($sql_equip_check);
        // if (empty($equip_no)) {
        //     $sql_equip_check = "SELECT equipCheckID,ref  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$build_no' AND sysID='$sys_no'";
        //     $equip_ch=$pdo->query($sql_equip_check);
        // } else {
        //     $sql_equip_check = "SELECT equipCheckID,ref  FROM FA.Equipment_Check WHERE floorID='$floorID'AND equipID='$equip_no'AND b_number='$build_no' AND sysID='$sys_no'";
        //     $equip_ch=$pdo->query($sql_equip_check);
        // }
        $err=0;
        for ($i=0; $i < $loop_count; $i++) {
            $equip_id=$equip_ch->fetch();//求出點檢項目id及參考值
            $ref_no = $equip_id['ref'];
            $equip_check = $equip_id['equipCheckID'];
            $ans_no=$_POST["$i"];
            $sql_master_check="SELECT COUNT(recordID) FROM FA.Water_System_Record_Master WHERE b_number='$build_no' AND rDate='$date_ch' AND sysID=$sys_no ";
            $master_check_query=Current($pdo->query($sql_master_check)->fetch());
            if (empty($ans_no)) {
                $ans_no=null;
                $err=$err+1;
            }
            if ($master_check_query ==0) {                    
                $sql_insert_master="INSERT INTO FA.Water_System_Record_Master(b_number,rDate,sysID) VALUES ('$build_no','$date_ch',$sys_no) ";
                $insert_master =$pdo->exec($sql_insert_master);                    
                $sql_select="SELECT recordID FROM FA.Water_System_Record_Master WHERE sysID=$sys_no AND rDate='$date_ch' AND b_number='$build_no'";
                $select_master =$pdo->query($sql_select)->fetch();
                $MasterID=$select_master['recordID'];                   
                $sql_insert_detail="INSERT INTO FA.Water_System_Record_Detail(equipCheckID,ref,shiftID,r_member,remark,recordID,checkResult,floorID,rDate,rTime) VALUES ($equip_check,'$ref_no',$shift_no,'$userID','$remark',$MasterID,'$ans_no','$floorID','$date_ch','$rTime')";
                $insert_detail =$pdo->exec($sql_insert_detail);
            } else {
                $sql_select="SELECT recordID FROM FA.Water_System_Record_Master WHERE sysID=$sys_no AND rDate='$date_ch' AND b_number='$build_no'";
                $select_master =$pdo->query($sql_select)->fetch();
                $MasterID=$select_master['recordID'];
                $sql_insert_detail="INSERT INTO FA.Water_System_Record_Detail(equipCheckID,ref,shiftID,r_member,remark,recordID,checkResult,floorID,rDate,rTime) VALUES ($equip_check,'$ref_no',$shift_no,'$userID','$remark',$MasterID,'$ans_no','$floorID','$date_ch','$rTime')";
                $insert_detail =$pdo->exec($sql_insert_detail);
            } 
        }
        $pdo=null;
        if ($err>=1) {
            echo "<script>alert('有部分項目未填寫就送出，下次要補上時請選擇修改的方式')</script>";
            header("Location: mtinsert.html");
            //echo "<script>window.close();</script>";
        } else {
            header("Location: mtinsert.html");
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
    <?PHP
    //檢查項目
        switch ($sysNo) {
            case "4":
                $sql_equip_check = "SELECT equipCheckName,ref  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$buildNo' AND sysID=$sysNo";
                $query_equip=$pdo->query($sql_equip_check);
                $equip_check_num="SELECT COUNT(equipCheckID)  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$buildNo' AND sysID=$sysNo";
                $equip_check_no=Current($pdo->query($equip_check_num)->fetch());
                // if (empty($equipNo)) {
                //     $sql_equip_check = "SELECT equipCheckName,ref  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$buildNo' AND sysID=$sysNo";
                //     $query_equip=$pdo->query($sql_equip_check);
                //     $equip_check_num="SELECT COUNT(equipCheckID)  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$buildNo' AND sysID=$sysNo";
                //     $equip_check_no=Current($pdo->query($equip_check_num)->fetch());
                //     //$equipment=sql_database('zoneName','FA.Zonefloor','floorID',$equipNo);
                // } else {
                //     $sql_equip_check = "SELECT equipCheckName,ref  FROM FA.Equipment_Check WHERE floorID='$floorID'AND zoneNo=$equipNo AND b_number='$buildNo' AND sysID=$sysNo";
                //     $query_equip=$pdo->query($sql_equip_check);
                //     $equip_check_num="SELECT COUNT(equipCheckID)  FROM FA.Equipment_Check WHERE floorID='$floorID'AND zoneNo=$equipNo AND b_number='$buildNo' AND sysID=$sysNo";
                //     $equip_check_no=Current($pdo->query($equip_check_num)->fetch());
                // }
                break;
            default:
                $sql_equip_check = "SELECT equipCheckName,ref  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$buildNo' AND sysID=$sysNo";
                $query_equip=$pdo->query($sql_equip_check);
                $equip_check_num="SELECT COUNT(equipCheckID)  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$buildNo' AND sysID=$sysNo";
                $equip_check_no=Current($pdo->query($equip_check_num)->fetch());
                // if (empty($equipNo)) {
                //     $sql_equip_check = "SELECT equipCheckName,ref  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$buildNo' AND sysID=$sysNo";
                //     $query_equip=$pdo->query($sql_equip_check);
                //     $equip_check_num="SELECT COUNT(equipCheckID)  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$buildNo' AND sysID=$sysNo";
                //     $equip_check_no=Current($pdo->query($equip_check_num)->fetch());
                //     //$equipment=sql_database('equipName','FA.Equipment_System','equipID',$equipNo);
                // } else {
                //     $sql_equip_check = "SELECT equipCheckName,ref  FROM FA.Equipment_Check WHERE floorID='$floorID'AND equipID=$equipNo AND b_number='$buildNo' AND sysID=$sysNo";
                //     $query_equip=$pdo->query($sql_equip_check);
                //     $equip_check_num="SELECT COUNT(equipCheckID)  FROM FA.Equipment_Check WHERE floorID='$floorID'AND equipID=$equipNo AND b_number='$buildNo' AND sysID=$sysNo";
                //     $equip_check_no=Current($pdo->query($equip_check_num)->fetch());
                // }
                break;
        }
    echo '<div class="container border border-info mt-5">';
        echo '<form action="" method="post" name="wa">';
            echo '<h2 class="text-center font-weight-bold">'.'中國醫藥大學附設醫院-'.$build.'--'.$system.'</h2>';
            //班別/檢查者/日期欄
            echo '<div class="row my-3">';
                echo '<div class="col">';
                    echo '<p class="d-inline font-weight-bold">班別：</p>';
                    echo '<p class="d-inline text-primary">'.$class.'</p>';
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
                echo '</thead>';                
                for ($i=0; $i < $equip_check_no; $i++) { 
                $equipinfo=$query_equip->fetch(PDO::FETCH_ASSOC);
                echo '<tbody class="text-primary">';
                    echo '<td>'.$equipinfo['equipCheckName'].'</td>';
                    echo '<td>'.$equipinfo["ref"].'</td>';
                    if ($equipinfo["ref"]=="V/X") {
                        echo '<td>';
                            echo "<input type='radio' name=\"".$i."\" value='true'>合格";
                            echo "<input type='radio' name=\"".$i."\" value='false'>不合格";
                        echo '</td>';
                    } else {                 
                        echo '<td>'."<input type='text' name=\"".$i."\" maxlength='20'>".'</td>';                
                    }  
                    //echo"</tr>";
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
            <!-- <input type="hidden" name="equip" value='<?//= $equipNo ?>'> -->
            <input type="hidden" name="sys" value='<?= $sysNo ?>'>
            <input type="hidden" name="shift" value='<?= $shiftNo ?>'>
            <input type="hidden" name="date_c" value='<?= $check_date ?>'>
            <input type="hidden" name="loop_num" value='<?= $equip_check_no ?>'>
            <input type="hidden" name="floorID" value='<?= $floorID ?>'>
            <!-- 送出鈕 -->    
            <div class="d-flex justify-content-end">
                <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit">送出</button>
            </div>                           
        </form>
    </div>
</body>

</html>