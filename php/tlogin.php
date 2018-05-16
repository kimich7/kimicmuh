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
    $log_str="SELECT e_number,passcard,cname,rank FROM FA.Employee WHERE e_number = '$userID' AND passcard = '$password'";
    $log_query=$pdo->query($log_str)->fetchAll();
    $username=sql_database('cname','FA.Employee','e_number',$userID);
    $loginAutho=sql_database('rank','FA.Employee','e_number',$userID);
    if (count($log_query)==0) {
        $_SESSION["login_success"] = $login_success;
        $_SESSION["login_member"] = $username;
        $_SESSION["login_authority"] = $loginAutho;
        header('Location:../login.html'); 
    } else {
        $_SESSION["login_member"] = $username;
        $_SESSION["login_password"] = $password;     
        $login_success=true;
        $_SESSION["login_success"] = $login_success;
        $_SESSION["login_authority"] = $loginAutho;
        header('Location:../index.html'); 
    }
};
   
?>

 
 
 
