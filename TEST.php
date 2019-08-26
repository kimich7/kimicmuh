<?php 
include("php/CMUHconndata.php");
include("php/fun.php");
$date='WHERE empCheckdate = \'2019-08-23\' and check_number =1';

$str="SELECT * FROM FA.Water_System_Record_Master $date and check_manager is null";
$query=$pdo->query($str);
while ($show = $query->fetch()) {
    echo $show['b_number'].'</br>';
    echo $show['recordID'].'</br>';
}


?>

