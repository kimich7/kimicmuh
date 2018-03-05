<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <?php
        include("CMUHconndata.php");
        //資料帶入
        $buildNo=$_POST["build"];
        $sysNo=$_POST["system"];
        $equipNo=$_POST["equipment"];
        $shiftNo=$_POST["class"];
        //SQL Binding
        $sql_build="SELECT B_name FROM FA.Building WHERE b_number ='$buildNo' ";
        $building = $pdo->query($sql_build)->fetchall();
        foreach ($building as $build_value) {
            $build_value['B_name'];
            }
        $build=$build_value['B_name'];        
        
        $sql_sys = "SELECT sysName FROM FA.Equipment_System_Group WHERE sysID='$sysNo'";
        $sys = $pdo->query($sql_sys)->fetchall();
        foreach ($sys as $sys_value) {
            $sys_value['sysName'];
            }
        $system=$sys_value['sysName'];   
        
        $sql_equip="SELECT equipName FROM FA.Equipment_System WHERE equipID='$equipNo'";
        $equip= $pdo->query($sql_equip)->fetchall();
        foreach ($equip as $equip_value) {
            $equip_value['equipName'];
            }
        $equipment=$equip_value['equipName']; 

        $sql_shift ="SELECT shiftName FROM FA.Shift_Table WHERE shiftID='$shiftNo'";
        $shift=$pdo->query($sql_shift)->fetchall();
        foreach ($shift as $shift_value) {
            $shift_value['shiftName'];
            }
            $class=$shift_value['shiftName']; 
        ?>
       <!--  <table>
            <tr>
                <td>

                </td>
            </tr>
            <tr>
                <td>
 -->
</body>
</html>