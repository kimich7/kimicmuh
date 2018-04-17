<!-- 連接SQL資料庫 -->
<?PHP
    include("CMUHconndata.php");
    include("fun.php");
?>
<!-- PHP表單登入功能 -->
<?php
session_start();
$login_success=false;
if (isset($_POST['username']) && isset($_POST['password'])) {
    $userID = trim($_POST['username']);
    $password = trim($_POST['password']);
    $login_success=false;
    $log_str="SELECT e_number,passcard,cname FROM FA.Employee WHERE e_number = '$userID' AND passcard = '$password'";
    $log_query=$pdo->query($log_str)->fetchAll();
    $username=sql_database('cname','FA.Employee','e_number',$userID);
    if (count($log_query)==0) {
        echo "登入失敗";
    } else {
        $_SESSION["loginMember"] = $username;
        $_SESSION["loginPassword"] = $password;
        $login_success=true;
        echo "歡迎登入".$username;
    }
}        
?>

 
 
 
