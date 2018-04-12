<!-- 連接SQL資料庫 -->
<?PHP
	include("SQL_Database.php");
?>
<!-- PHP表單登入功能 -->
<?php
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $login_success=false;
    for ($i=0; $i < sizeof($query_employee) ; $i++) 
    {
        $user_check = $query_employee[$i]['ename'];
        $password_check = $query_employee[$i]['passcard'];
            if ($username == $user_check && $password == $password_check)
                {
                    $login_success=true;
                    break;
                }
    }
    if($login_success==true){
        echo "歡迎登入".$user_check;
    }else{
        echo "登入失敗";
    }
        
?>

 
 
 
