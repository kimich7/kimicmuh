<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    // setcookie('date',$_POST["bday"]);
    // setcookie('shift',$_POST["class"]);
    
    if (isset($_POST["action"])&&($_POST["action"]=="add")) {
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

        switch ($sys_no) {
            case '4':
                if (empty($equip_no)) {
                    $sql_equip_check = "SELECT equipCheckID,ref  FROM FA.Equipment_Check_elec WHERE floorID='$floorID'AND b_number='$build_no'";
                    $equip_ch=$pdo->query($sql_equip_check);
                } else {
                    $sql_equip_check = "SELECT equipCheckID,ref  FROM FA.Equipment_Check_elec WHERE floorID='$floorID'AND zoneNo='$equip_no'AND b_number='$build_no'";
                    $equip_ch=$pdo->query($sql_equip_check);
                }
                break;            
            default:
                if (empty($equip_no)) {
                    $sql_equip_check = "SELECT equipCheckID,ref  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$build_no'";
                    $equip_ch=$pdo->query($sql_equip_check);
                } else {
                    $sql_equip_check = "SELECT equipCheckID,ref  FROM FA.Equipment_Check WHERE floorID='$floorID'AND equipID='$equip_no'AND b_number='$build_no'";
                    $equip_ch=$pdo->query($sql_equip_check);
                }
                break;
        }
        
       
        for ($i=0; $i < $loop_count; $i++) {
            $equip_id=$equip_ch->fetch();
            $ref_no = $equip_id['ref'];
            $equip_check = $equip_id['equipCheckID'];
            $ans_no=$_POST["$i"];
          switch ($sys_no) {
              case "1":
                $sql_master_check="SELECT COUNT(recordID) FROM FA.Water_System_Record_Master WHERE b_number='$build_no' AND rDate='$date_ch' ";
                $master_check_query=Current($pdo->query($sql_master_check)->fetch());;
                if ($master_check_query ==0) {                    
                    $sql_insert_master="INSERT INTO FA.Water_System_Record_Master(b_number,rDate) VALUES ('$build_no','$date_ch') ";
                    $insert_master =$pdo->exec($sql_insert_master);                    
                    $sql_select="SELECT recordID FROM FA.Water_System_Record_Master WHERE rDate='$date_ch' AND b_number='$build_no'";
                    $select_master =$pdo->query($sql_select)->fetch();
                    $MasterID=$select_master['recordID'];                    
                    $sql_insert_detail="INSERT INTO FA.Water_System_Record_Detail(equipCheckID,ref,shiftID,r_member,remark,recordID,checkResult,floorID,rDate) VALUES ($equip_check,'$ref_no',$shift_no,3,'$remark',$MasterID,'$ans_no','$floorID','$date_ch')";
                    $insert_detail =$pdo->exec($sql_insert_detail);
                } else {
                    $sql_select="SELECT recordID FROM FA.Water_System_Record_Master WHERE rDate='$date_ch'AND b_number='$build_no'";
                    $select_master =$pdo->query($sql_select)->fetch();
                    $MasterID=$select_master['recordID'];                  
                    $sql_insert_detail="INSERT INTO FA.Water_System_Record_Detail(equipCheckID,ref,shiftID,r_member,remark,recordID,checkResult,floorID,rDate) VALUES ($equip_check,'$ref_no',$shift_no,3,'$remark',$MasterID,'$ans_no','$floorID','$date_ch')";
                    $insert_detail =$pdo->exec($sql_insert_detail);
                }
                break;
              case "2":
                $sql_master_check="SELECT COUNT(recordID) FROM FA.Air_System_Record_Master WHERE b_number='$build_no' AND rDate='$date_ch' ";
                $master_check_query=Current($pdo->query($sql_master_check)->fetch());;
                if ($master_check_query ==0) {                    
                    $sql_insert_master="INSERT INTO FA.Air_System_Record_Master(b_number,rDate) VALUES ('$build_no','$date_ch') ";
                    $insert_master =$pdo->exec($sql_insert_master);                    
                    $sql_select="SELECT recordID FROM FA.Air_System_Record_Master WHERE rDate='$date_ch' AND b_number='$build_no'";
                    $select_master =$pdo->query($sql_select)->fetch();
                    $MasterID=$select_master['recordID'];                    
                    $sql_insert_detail="INSERT INTO FA.Air_System_Record_Detail(equipCheckID,ref,shiftID,r_member,remark,recordID,checkResult,floorID,rDate) VALUES ($equip_check,'$ref_no',$shift_no,3,'$remark',$MasterID,'$ans_no','$floorID','$date_ch')";
                    $insert_detail =$pdo->exec($sql_insert_detail);
                } else {
                    $sql_select="SELECT recordID FROM FA.Air_System_Record_Master WHERE rDate='$date_ch'AND b_number='$build_no'";
                    $select_master =$pdo->query($sql_select)->fetch();
                    $MasterID=$select_master['recordID'];                    
                    $sql_insert_detail="INSERT INTO FA.Air_System_Record_Detail(equipCheckID,ref,shiftID,r_member,remark,recordID,checkResult,floorID,rDate) VALUES ($equip_check,'$ref_no',$shift_no,3,'$remark',$MasterID,'$ans_no','$floorID','$date_ch')";
                    $insert_detail =$pdo->exec($sql_insert_detail);
                }
                  break;
              case "3":
                $sql_master_check="SELECT COUNT(recordID) FROM FA.AirCond_System_Record_Master WHERE b_number='$build_no' AND rDate='$date_ch' ";
                $master_check_query=Current($pdo->query($sql_master_check)->fetch());;
                if ($master_check_query ==0) {                    
                    $sql_insert_master="INSERT INTO FA.AirCond_System_Record_Master(b_number,rDate) VALUES ('$build_no','$date_ch') ";
                    $insert_master =$pdo->exec($sql_insert_master);                    
                    $sql_select="SELECT recordID FROM FA.AirCond_System_Record_Master WHERE rDate='$date_ch' AND b_number='$build_no'";
                    $select_master =$pdo->query($sql_select)->fetch();
                    $MasterID=$select_master['recordID'];                    
                    $sql_insert_detail="INSERT INTO FA.AirCond_System_Record_Detail(equipCheckID,ref,shiftID,r_member,remark,recordID,checkResult,floorID,rDate) VALUES ($equip_check,'$ref_no',$shift_no,3,'$remark',$MasterID,'$ans_no','$floorID','$date_ch')";
                    $insert_detail =$pdo->exec($sql_insert_detail);
                } else {
                    $sql_select="SELECT recordID FROM FA.AirCond_System_Record_Master WHERE rDate='$date_ch'AND b_number='$build_no'";
                    $select_master =$pdo->query($sql_select)->fetch();
                    $MasterID=$select_master['recordID'];                    
                    $sql_insert_detail="INSERT INTO FA.AirCond_System_Record_Detail(equipCheckID,ref,shiftID,r_member,remark,recordID,checkResult,floorID,rDate) VALUES ($equip_check,'$ref_no',$shift_no,3,'$remark',$MasterID,'$ans_no','$floorID','$date_ch')";
                    $insert_detail =$pdo->exec($sql_insert_detail);
                }                  
                  break;
              case "4":
                $sql_master_check="SELECT COUNT(recordID) FROM FA.HL_Vol_System_Record_Master WHERE b_number='$build_no' AND rDate='$date_ch' ";
                $master_check_query=Current($pdo->query($sql_master_check)->fetch());;
                if ($master_check_query ==0) {                    
                    $sql_insert_master="INSERT INTO FA.HL_Vol_System_Record_Master(b_number,rDate) VALUES ('$build_no','$date_ch') ";
                    $insert_master =$pdo->exec($sql_insert_master);                    
                    $sql_select="SELECT recordID FROM FA.HL_Vol_System_Record_Master WHERE rDate='$date_ch' AND b_number='$build_no'";
                    $select_master =$pdo->query($sql_select)->fetch();
                    $MasterID=$select_master['recordID'];                   
                    $sql_insert_detail="INSERT INTO FA.HL_Vol_System_Record_Detail(equipCheckID,ref,shiftID,r_member,remark,recordID,checkResult,zoneNo,floorID,rDate) VALUES ($equip_check,'$ref_no',$shift_no,3,'$remark',$MasterID,'$ans_no',$equip_no,'$floorID','$date_ch')";
                    $insert_detail =$pdo->exec($sql_insert_detail);
                } else {
                    $sql_select="SELECT recordID FROM FA.HL_Vol_System_Record_Master WHERE rDate='$date_ch'AND b_number='$build_no'";
                    $select_master =$pdo->query($sql_select)->fetch();
                    $MasterID=$select_master['recordID'];
                    $sql_insert_detail="INSERT INTO FA.HL_Vol_System_Record_Detail(equipCheckID,ref,shiftID,r_member,remark,recordID,checkResult,zoneNo,floorID,rDate) VALUES ($equip_check,'$ref_no',$shift_no,3,'$remark',$MasterID,'$ans_no',$equip_no,'$floorID','$date_ch')";
                    $insert_detail =$pdo->exec($sql_insert_detail);
                }                  
                  break;
          }  
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
        <title>設備保養表單</title>
    </head>

    <body class="table_bg">
        <?PHP
            //mtinsert資料帶入
            $check_date=$_POST["bday"];
            $buildNo=$_POST["build"];
            $sysNo=$_POST["system"];
            $equipNo=$_POST["equipment"];
            $shiftNo=$_POST["class"];
            $floorID=$_POST["buildingfloor"];
            //SQL Binding
            //篩選出棟別
            $build=sql_database('B_name','FA.Building','b_number',$buildNo);         
            //篩選出系統別
            $system=sql_database('sysName','FA.Equipment_System_Group','sysID',$sysNo);
            //篩選出樓層別
            $system=sql_database('floorName','FA.BuildingFloor','floorID',$floorID);
            //篩選出設備別
            if ($sysNo==4) {
                $equipment=sql_database('zoneName','FA.Zonefloor','floorID',$equipNo);
            } else {
                $equipment=sql_database('equipName','FA.Equipment_System','equipID',$equipNo);
            }
            //篩選出班別
            $class=sql_database('shiftName','FA.Shift_Table','shiftID',$shiftNo);
            setcookie('className',$class);    
            //檢查項目
            switch ($sysNo) {
                case "4":
                    if (empty($equipNo)) {
                        $sql_equip_check = "SELECT equipCheckName,ref  FROM FA.Equipment_Check_elec WHERE floorID='$floorID'AND b_number='$buildNo'";
                        $query_equip=$pdo->query($sql_equip_check);
                        $equip_check_num="SELECT COUNT(equipCheckID)  FROM FA.Equipment_Check_elec WHERE floorID='$floorID'AND b_number='$buildNo'";
                        $equip_check_no=Current($pdo->query($equip_check_num)->fetch());
                    } else {
                        $sql_equip_check = "SELECT equipCheckName,ref  FROM FA.Equipment_Check_elec WHERE floorID='$floorID'AND zoneNo='$equipNo'AND b_number='$buildNo'";
                        $query_equip=$pdo->query($sql_equip_check);
                        $equip_check_num="SELECT COUNT(equipCheckID)  FROM FA.Equipment_Check_elec WHERE floorID='$floorID'AND zoneNo='$equipNo'AND b_number='$buildNo'";
                        $equip_check_no=Current($pdo->query($equip_check_num)->fetch());
                    }
                    break;
                default:
                    if (empty($equipNo)) {
                        $sql_equip_check = "SELECT equipCheckName,ref  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$buildNo'";
                        $query_equip=$pdo->query($sql_equip_check);
                        $equip_check_num="SELECT COUNT(equipCheckID)  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$buildNo'";
                        $equip_check_no=Current($pdo->query($equip_check_num)->fetch());
                    } else {
                        $sql_equip_check = "SELECT equipCheckName,ref  FROM FA.Equipment_Check WHERE floorID='$floorID'AND equipID='$equipNo'AND b_number='$buildNo'";
                        $query_equip=$pdo->query($sql_equip_check);
                        $equip_check_num="SELECT COUNT(equipCheckID)  FROM FA.Equipment_Check WHERE floorID='$floorID'AND equipID='$equipNo'AND b_number='$buildNo'";
                        $equip_check_no=Current($pdo->query($equip_check_num)->fetch());
                    }
                    break;
            }     
        ?>
            <div class="container border border-info mt-5">
                <form action="" method="post" name="wa">
                    <h2 class="text-center font-weight-bold">
                        中國醫藥大學附設醫院-
                        <?= $build ?>--
                            <?= $system ?>
                    </h2>
                    <div class="row my-3">
                        <div class="col">
                            <p class="d-inline font-weight-bold">
                                班別：
                            </p>
                            <p class="d-inline text-primary">
                                <?= $class ?>
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
                                <?= $check_date ?>
                            </p>

                        </div>
                    </div>
                    <!-- <div class="my-3">
                        <p class="d-inline font-weight-bold">
                            設備：
                        </p>
                        <p class="d-inline text-primary">
                            <?//= $equipment ?>
                        </p>
                    </div> -->
                    <table class="table my-5">
                        <thead>
                            <th>檢查項目</th>
                            <th>參考值</th>
                            <th>結果</th>
                        </thead>
                        <?php
                        for ($i=0; $i < $equip_check_no; $i++) { 
                            $equipinfo=$query_equip->fetch(PDO::FETCH_ASSOC);
                        ?>
                            <tbody class="text-primary">
                                <td>
                                    <?= $equipinfo['equipCheckName']?>
                                </td>
                                <td>
                                    <?= $equipinfo["ref"]?>
                                </td>
                                <?php
                            if ($equipinfo["ref"]=="V/X") { 
                            ?>
                                    <td>
                                        <input type='radio' name='<?= $i?>' value='true'>合格
                                        <input type='radio' name='<?= $i?>' value='false'>不合格
                                    </td>
                                    <?php                
                            } else { 
                            ?>
                                    <td>
                                        <input type="text" name='<?= $i?>' maxlength="20">
                                    </td>
                                    <?php
                            }  
                            echo"</tr>";
                            }
                            ?>
                            </tbody>
                    </table>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">備註：</span>
                        </div>
                        <textarea class="form-control" name="remark" aria-label="With textarea"></textarea>
                    </div>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="build" value='<?= $buildNo ?>'>
                    <input type="hidden" name="equip" value='<?= $equipNo ?>'>
                    <input type="hidden" name="sys" value='<?= $sysNo ?>'>
                    <input type="hidden" name="shift" value='<?= $shiftNo ?>'>
                    <input type="hidden" name="date_c" value='<?= $check_date ?>'>
                    <input type="hidden" name="loop_num" value='<?= $equip_check_no ?>'>
                    <input type="hidden" name="floorID" value='<?= $floorID ?>'>
                                        
                    <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit">送出</button>
                </form>
            </div>

    </body>

    </html>