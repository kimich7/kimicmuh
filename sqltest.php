<?php 
include("php/CMUHconndata.php");
include("php/fun.php");

$ansStr="SELECT e.sid,e.e_number,p.cname,k.sysID FROM FA.securityemp as e LEFT JOIN FA.securityKind as k on e.sid=k.id LEFT JOIN FA.Employee as p on e.e_number=p.e_number  WHERE e.e_number='A11141'";
$ans=$pdo->Query($ansStr)->fetch();

echo $ans['e_number'].'<hr>';


if (isset($ans) and $ans!='') {
    echo '我正常';
} else {
    echo '我不正常';
}

$status=rankStatus(296);
if (isset($status) and $status!='') {
    echo '我正常';
} else {
    echo '我不正常';
}

print_r($status);
?>