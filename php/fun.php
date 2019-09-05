<?php
    function sql_database($col,$tbl,$when,$wheans){//對照欄位的名稱，通常用在ID找名稱        
        include("CMUHconndata.php");
        $view_select = "SELECT $col FROM $tbl WHERE $when = '$wheans' ";
        $view_query=$pdo->query($view_select)->fetch(PDO::FETCH_ASSOC);
        $view=$view_query["$col"];
        $pdo=null;
        return $view;
    }

    FUNCTION checkName($checkID){
        include("CMUHconndata.php");
        if (is_null($checkID)) {
            return $checkUser='';
        } else {
            return $checkUser=sql_database('cname','FA.Employee','e_number',$checkID);
        }
    }

    FUNCTION checkNamelike($keyword){
        include("CMUHconndata.php");
        $view_select = "SELECT e_number FROM FA.Employee WHERE cname like '%$keyword%' ";
        $view_query=$pdo->query($view_select);
        while ($name = $view_query->fetch()) {
            $view=array($name["e_number"]);
        };        
        $pdo=null;
        return $view;
    }

    function sql_database_int($col,$tbl,$when,$wheans){//對照欄位的名稱，通常用在ID找名稱        
        include("CMUHconndata.php");
        $view_select = "SELECT $col FROM $tbl WHERE $when = $wheans ";
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

    function rank($userID){
        include("CMUHconndata.php");
        $str="SELECT rank FROM FA.Employee WHERE e_number = '$userID'";
        $str_query=$pdo->query($str)->fetch();
        return $rank=$str_query['rank'];
    }
    function rankStatus($recordID){
        include("CMUHconndata.php");
        $status=array();
        $strStatus="SELECT r_member,managerID,check_number,check_manager FROM FA.Water_System_Record_Master WHERE recordID=$recordID";
        $strStatus_query=$pdo->query($strStatus)->fetch();
        $status['mgrCheck']=$strStatus_query['check_manager'];
        $status['eeCheck']=$strStatus_query['check_number'];
        $status['employeeID']=$strStatus_query['r_member'];
        $status['managerID']=$strStatus_query['managerID'];
        return $status;

    }

    //確認抄表系統該樓層今天抄過表沒
    function tableCheck($rDate,$floor,$shiftNo){
        include("CMUHconndata.php");
        if (isset($shiftNo) AND $shiftNo!="") {
            $str="SELECT COUNT(recordDetailID) FROM FA.Water_System_Record_Detail where rDate='$rDate' AND floorID='$floor' AND shiftID=$shiftNo";
        } else {
            $str="SELECT COUNT(recordDetailID) FROM FA.Water_System_Record_Detail where rDate='$rDate' AND floorID='$floor'";
        }
        $strnum=Current($pdo->query($str)->fetch());
        return $strnum;
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
    function cookieout(){
        setcookie("buildID",'');// mtwatertable.php
        setcookie("buildName",'');// mtwatertable.php
        setcookie("floorID",'');// mtwatertable.php
        setcookie("floorName",'');// mtwatertable.php
        setcookie("date",'');// php/data_class.php
        setcookie("shift",'');// php/data_class.php
        setcookie("courtyard",'');// php/data_class.php
        setcookie("className",'');// php/data_class.php
        setcookie("courtyardName",'');// php/data_class.php
    }
    
    //差集
    function a_array_unique($array){
        $out = array();
        foreach ($array as $key=>$value) {
            if (!in_array($value, $out)){
                $out[$key] = $value;
            }
        }
        $out = array_values($out);
        return $out;
    }

    
?>

