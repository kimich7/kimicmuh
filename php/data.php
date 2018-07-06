<?php
    include("fun.php");
    include("SQL_Database.php");
    $colID = $_REQUEST['colID'];
    $colName = $_REQUEST['colName'];
    switch ($colID) {
        case 'b_number':
            $query=$query_build;
            break;
        case 'sysID':
            $query=$query_system;
            break;
        case 'shiftID':
            $query=$query_shift;
            break;
        case 'c_number':
            $query=$query_courtyard;
            break;        
    }     
    $data=database($colID,$colName,$query);
    echo $data;
?>