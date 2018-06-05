<?php
    function sql_database($col,$tbl,$when,$wheans){        
        include("CMUHconndata.php");
        $view_select = "SELECT $col FROM $tbl WHERE $when = '$wheans' ";
        $view_query=$pdo->query($view_select)->fetch(PDO::FETCH_ASSOC);
        $view=$view_query["$col"];
        $pdo=null;
        return $view;
    }

    function updata_select($tbl,$Mid){
        include("CMUHconndata.php");

        $updata="SELECT * FROM $tbl WHERE recordID = $Mid";
        $updata_query=$pdo->query($updata)->fetchAll();
        $pdo=null;
        return $updata_query;
    } 

    function updata_num($tbl,$Mid){
        include("CMUHconndata.php");
        $updata="SELECT COUNT(equipCheckID) FROM $tbl WHERE recordID = $Mid";
        $updata_qt=Current($pdo->query($updata)->fetch());
        $pdo=null;
        return $updata_qt;
    }

    function sys_search($tbl){
        include("CMUHconndata.php");
        
        $search="SELECT * FROM $tbl WHERE check_number IS NULL or check_manager IS NULL";
        $query=$pdo->query($search)->fetchAll();
        $pdo=null;
        return $query;
    }

    function database($colID,$colName,$query){        
    include("SQL_Database.php");
    $dataArray=array();
    $dataArray1=array();
    foreach ($query as $datainfo) {
       $dataArray["$colID"] = $datainfo["$colID"];
       $dataArray["$colName"]=$datainfo["$colName"];
       $dataArray2=array_push($dataArray1,$dataArray);
    }
    return json_encode($dataArray1,JSON_UNESCAPED_UNICODE);
    }

    function num($str){
        include("CMUHconndata.php");
        $num="$str";
        $num_qt=Current($pdo->query($num)->fetch());
        $pdo=null;
        return $num_qt;
    }
    function item($str){
        include("CMUHconndata.php");
        $item="$str";
        $item_qt=$pdo->query($item)->fetchAll();
        $pdo=null;
        return $item_qt;
    }

    function sysTable($sysID){
        $table=array();
        switch ($sysID) {
            case '1':
                $systemDetail='FA.Water_System_Record_Detail';
                $systemMaster='FA.Water_System_Record_Master';
                $equipTable='FA.Equipment_Check';
                break;
            case '2':
                $systemDetail='FA.Air_System_Record_Detail';
                $systemMaster='FA.Air_System_Record_Master';
                $equipTable='FA.Equipment_Check';
                break;
            case '3':
                $systemDetail='FA.AirCond_System_Record_Detail';
                $systemMaster='FA..AirCond_System_Record_Master';
                $equipTable='FA.Equipment_Check';
                break;
            case '4':
                $systemDetail='FA.HL_Vol_System_Record_Detail';
                $systemMaster='FA.HL_Vol_System_Record_Master';
                $equipTable='FA.Equipment_Check_elec';
                break;
        }
        $table['master']=$systemMaster;
        $table['detail']=$systemDetail;
        $table['equip']=$equipTable;
        return $table;
    }

    function equipCheckSearch($sys_no,$equip_no){
        switch ($sys_no) {
            case '4':
                if (empty($equip_no)) {
                    $sql_equip_check = "SELECT equipCheckID,ref  FROM FA.Equipment_Check_elec WHERE floorID='$floorID'AND b_number='$build_no' AND sysID='$sys_no'";
                } else {
                    $sql_equip_check = "SELECT equipCheckID,ref  FROM FA.Equipment_Check_elec WHERE floorID='$floorID'AND zoneNo='$equip_no'AND b_number='$build_no' AND sysID='$sys_no'";
                }
                break;            
            default:
                if (empty($equip_no)) {
                    $sql_equip_check = "SELECT equipCheckID,ref  FROM FA.Equipment_Check WHERE floorID='$floorID'AND b_number='$build_no' AND sysID='$sys_no'";
                } else {
                    $sql_equip_check = "SELECT equipCheckID,ref  FROM FA.Equipment_Check WHERE floorID='$floorID'AND equipID='$equip_no'AND b_number='$build_no' AND sysID='$sys_no'";
                }
                break;
        }
        return $sql_equip_check;
    }
?>

