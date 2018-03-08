<?php
    include("CMUHconndata.php");
    if (isset($_POST["action"])&&($_POST["action"]=="add")) {
        $sys_no=$_POST["sys"];//系統ID 
        
        //Master輸入
        $build_no=$_POST["build"];//棟別代號 
        $date_ch=$_POST["date_c"];//檢點日期 
        
        //Detail輸入
        $shift_no=$_POST["shift"];//點檢班別ID 
        $remark=$_POST["remark"];//備註 
        $equip_no=$_POST["equip"];//設備(鍋爐、給水、熱水......等等)ID 
        
        $sql_equip_id = "SELECT equipCheckID,ref  FROM FA.Equipment_Check WHERE equipID='$equip_no'AND b_number='$build_no'";
        $equip_ch=$pdo->query($sql_equip_id);
        $equipcheck_num="SELECT COUNT(equipID) FROM FA.Equipment_Check WHERE equipID='$equip_no' AND b_number='$build_no'";
        $equipcheck_total_num = Current($pdo->query($equipcheck_num)->fetch());        
        for ($i=0; $i < $equipcheck_total_num; $i++) {
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
                    $sql_select="SELECT recordID FROM FA.Water_System_Record_Master WHERE rDate='$date_ch'";
                    $select_master =$pdo->query($sql_select)->fetch();
                    $MasterID=$select_master['recordID'];                    
                    $sql_insert_detail="INSERT INTO FA.Water_System_Record_Detail(equipCheckID,ref,shiftID,r_member,remark,recordID,checkResult,equipID) VALUES ($equip_check,'$ref_no',$shift_no,3,'$remark',$MasterID,'$ans_no',$equip_no)";
                    $insert_detail =$pdo->exec($sql_insert_detail);
                } else {
                    $sql_select="SELECT recordID FROM FA.Water_System_Record_Master WHERE rDate='$date_ch'";
                    $select_master =$pdo->query($sql_select)->fetch();
                    $MasterID=$select_master['recordID'];
                    echo $MasterID."：Master表ID<br>";                    
                    $sql_insert_detail="INSERT INTO FA.Water_System_Record_Detail(equipCheckID,ref,shiftID,r_member,remark,recordID,checkResult,equipID) VALUES ($equip_check,'$ref_no',$shift_no,3,'$remark',$MasterID,'$ans_no',$equip_no)";
                    $insert_detail =$pdo->exec($sql_insert_detail);
                }
                break;
              case "2":
                  # code...
                  break;
              case "3":
                  # code...
                  break;
              default:
                  # code...
                  break;
          }  
        }
        $pdo=null;
       header("Location: mtinsert.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?PHP
//mtinsert資料帶入
    $check_date=$_POST["bday"];
    $buildNo=$_POST["build"];
    $sysNo=$_POST["system"];
    $equipNo=$_POST["equipment"];
    $shiftNo=$_POST["class"];
    //SQL Binding
    //篩選出棟別
    $sql_build="SELECT B_name FROM FA.Building WHERE b_number ='$buildNo' ";
    $building = $pdo->query($sql_build)->fetchall();
    foreach ($building as $build_value) {
        $build_value['B_name'];
        }
    $build=$build_value['B_name'];        
    //篩選出系統別
    $sql_sys = "SELECT sysName FROM FA.Equipment_System_Group WHERE sysID='$sysNo'";
    $sys = $pdo->query($sql_sys)->fetchall();
    foreach ($sys as $sys_value) {
        $sys_value['sysName'];
        }
    $system=$sys_value['sysName'];   
    //篩選出設備別
    $sql_equip="SELECT equipName FROM FA.Equipment_System WHERE equipID='$equipNo'";
    $equip= $pdo->query($sql_equip)->fetchall();
    foreach ($equip as $equip_value) {
        $equip_value['equipName'];
        }
    $equipment=$equip_value['equipName']; 
    //篩選出班別
    $sql_shift ="SELECT shiftName FROM FA.Shift_Table WHERE shiftID='$shiftNo'";
    $shift=$pdo->query($sql_shift)->fetchall();
    foreach ($shift as $shift_value) {
        $shift_value['shiftName'];
        }
        $class=$shift_value['shiftName'];
    //檢查項目
    $sql_equip_check = "SELECT equipCheckName,ref  FROM FA.Equipment_Check WHERE equipID='$equipNo'AND b_number='$buildNo'";
    $query_equip=$pdo->query($sql_equip_check);//->fetchall();
    $equip_check_num="SELECT COUNT(equipCheckID)  FROM FA.Equipment_Check WHERE equipID='$equipNo'AND b_number='$buildNo'";
    $equip_check_no=Current($pdo->query($equip_check_num)->fetch());
    
?>  
    <form action="" method="post" name="wa">
    <table width="80%" border=1>
        <tr>
            <td colspan="3" align="center">
                <h3>中國醫藥大學附設醫院-<?= $build ?>--<?= $system ?></h3>
            </td>
        </tr>
        <tr>
            <td align="center"><h4>班別：<?= $class ?></h4></td>
            <td align="center"><h4>檢查者：<?= '檢查者' ?></h4></td>
            <td align="center"><h4>檢查日期：<?= $check_date ?></h4></td>
        </tr>
        <tr>
            <td align="center"><h4>設備：</h4></td>
            <td align="center" colspan="2"><h4><?= $equipment ?></h4></td>        
        </tr>
        <tr>
            <td align="center"><h4>檢查項目</h4></td>
            <td align="center"><h4>參考值</h4></td>
            <td align="center"><h4>結果</h4></td>
        </tr>
        <?php
        for ($i=0; $i < $equip_check_no; $i++) { 
            $equipinfo=$query_equip->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr>
                <td align='center'><?= $equipinfo['equipCheckName']?></td>
                <td align='center'><?= $equipinfo["ref"]?></td>
                <?php
                
                if ($equipinfo["ref"]=="V/X") { ?>
                    <td align='center'><input type='radio' name='<?= $i?>' value='true' >合格<input type='radio' name='<?= $i?>' valee='false' >不合格</td>
                <?php                
                } else { ?>
                    <td align='center'><input type="text" name='<?= $i?>' maxlength="20"></td>                
                <?php
                }  
            echo"</tr>";
            }
        ?>          
        <tr>
            <td><h3>備註：</h3></td>
            <td colspan="2"><textarea name="remark" cols="70" rowa="50" >NULL</textarea></td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="build" value='<?= $buildNo ?>'>
                <input type="hidden" name="equip" value='<?= $equipNo ?>'>
                <input type="hidden" name="sys" value='<?= $sysNo ?>'>
                <input type="hidden" name="shift" value='<?= $shiftNo ?>'>
                <input type="hidden" name="date_c" value='<?= $check_date ?>'>
                <input type="submit" name="button" value="送出">
            </td>
        </tr>
    </table>    
    </form>
</body>
</html>