<?php
    include("CMUHconndata.php");

    if (isset($_POST["action"])&&($_POST["action"]=="add")) {
        $ref=$equipinfo["ref"];
        $remark=$_POST["remark"];
        $asn=$equipinfo["asn"];
        for ($i=0; $i < $equipcheck_total_num; $i++) { 
          switch ($sysNo) {
              case "1":
                $sql_master_check="SELECT COUNT(b_number,rDate) FROM FA.Water_System_Record_Master WHERE b_number=$buildNo AND rDate=$check_date ";
                $master_check_query=Current($pdo->query($sql_master_check)->fetch());;
                if ($master_check_query ==0) {
                    try{
                        $sql_insert_master="INSERT INTO FA.Water_System_Record_Master(b_number,rDate) VALUES '$buildNo','$check_date' ";
                        $insert_master =$pdo->exce($sql_insert_master);
                    }catch(PDOException $q){
                        print "Couldn't insert a row:".$q->getMessage();
                    }                    
                    $sql_select="SELECT scope_identity(FA.Water_System_Record_Master)";
                    $select_master =$pdo->query($sql_select)->fetch();
                    $sql_insert_detail="INSERT INTO FA.Water_System_Record_Detail(equipCheckID,ref,shiftID,r_member,remark,recordID,checkResult,equipID) VALUES ('$equipID','$ref','$shiftNo','檢查者','$remark','$select_master','','$equipNo')";
                } else {
                    $sql_insert_detail="INSERT INTO FA.Water_System_Record_Detail()";
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
    $sql_equip_check = "SELECT equipCheckID,equipCheckName,ref  FROM FA.Equipment_Check WHERE equipID='$equipNo'AND b_number='$buildNo'";
    $query_equip=$pdo->query($sql_equip_check)->fetchall();
    $equipcheck_num="SELECT COUNT(equipID) FROM FA.Equipment_Check WHERE equipID='$equipNo'AND b_number='$buildNo'";
    $equipcheck_total_num =Current($pdo->query($equipcheck_num)->fetch());
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

        foreach ($query_equip as $equipinfo) {
            $equipID=$equipinfo['equipCheckID'];
            echo"<tr>";
                echo "<td align='center'>".$equipinfo['equipCheckName']."</td>";
                echo "<td align='center'>".$equipinfo["ref"]."</td>";
                if ($equipinfo["ref"]=="V/X") {
                    echo "<td align='center'>"."<input type='radio' name='ans' value='true' >"."合格"."<input type='radio' name='ans' valee='false' >"."不合格"."</td>";                
                } else {
                    echo "<td align='center'>"."<input type=\".text.\" name=\".ans.\" maxlength=\".20.\">"."</td>";                
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
                <input type="submit" name="button" value="送出">
            </td>
        </tr>
    </table>
    </form>
</body>
</html>