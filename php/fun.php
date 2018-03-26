<?php
    function sql_database($col,$tbl,$when,$wheans){        
        include("CMUHconndata.php");
        $view_select = "SELECT $col FROM $tbl WHERE $when = '$wheans' ";
        $view_query=$pdo->query($view_select)->fetch(PDO::FETCH_ASSOC);
        $view=$view_query["$col"];
        $pdo=null;
        return $view;
    }

    function updata_select($tbl,$date,$equip,$Mid){
        include("CMUHconndata.php");
        //$updata_view=array();
        $updata="SELECT recordDetailID,equipCheckID,ref,r_member,remark,checkResult FROM $tbl WHERE rDate='$date' AND equipID=$equip AND recordID=$Mid";
        $updata_query=$pdo->query($updata)->fetchAll();
        //$updata_query=$pdo->query($updata);
        $pdo=null;
        // foreach ($updata_query as $updatainfo) {
        //     ECHO $updatainfo["equipCheckID"].'</br>';
        // }
        //$updata_view = $updata_query;
        return $updata_query;
    } 

    function updata_num($tbl,$date,$equip,$Mid){
        include("CMUHconndata.php");
        $updata="SELECT COUNT(equipCheckID) FROM $tbl WHERE rDate='$date' AND equipID=$equip AND recordID=$Mid";
        $updata_qt=Current($pdo->query($updata)->fetch());
        $pdo=null;
        return $updata_qt;
    }

    function sys_search($tbl){
        include("CMUHconndata.php");
        $search="SELECT b_number,rDate FROM $tbl WHERE check_number = NULL or check_manager IS NULL";
        $query=$pdo->query($search)->fetchAll();
        $pdo=null;
        return $query;
    }
?>

