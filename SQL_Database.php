<?php
include("CMUHconndata.php");
    //select building
    $sql_build="SELECT b_number,B_name FROM FA.Building";
    $query_build=$pdo->query($sql_build)->fetchAll();

    //select System
    $sql_system ="SELECT sysID,sysName FROM FA.Equipment_System_Group";
    $query_system = $pdo->query($sql_system)->fetchAll();

    //select equipment
    $sql_equipment="SELECT equipID,equipName FROM FA.Equipment_System";
    $query_equipment =$pdo->query($sql_equipment)->fetchAll();

    //select shift
    $sql_shift = "SELECT shiftID,shiftName FROM FA.Shift_Table";
    $query_shift=$pdo->query($sql_shift)->fetchAll();

    // 設定連結SQL資料庫內的Employee表格並取ename,passwoed兩個欄位的資料
    $sql_employee = "SELECT ename,passcard FROM FA.Employee";
    $query_employee=$pdo->query($sql_employee)->fetchAll();
    $pdo=null;
?>