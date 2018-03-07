 <!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>Document</title>
 </head>
 <body>
      <!-- 連接SQL資料庫 -->
 <?PHP
		include("SQL_Database.php");
    ?>

    <?php
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $login_success=false;
        for ($a=0; $a < sizeof($query_employee) ; $a++) 
        {
            $user_check = $query_employee[$a]['ename'];
            $password_check = $query_employee[$a]['passcard'];
                if ($username == $user_check && $password == $password_check)
                    {
                        $login_success=true;
                        break;
                    }
        }
        if($login_success==true){
            echo "歡迎登入".$user_check. $password_check;
        }else{
            echo "登入失敗";
        }
        
    ?>
 </body>
 </html>
 
 
 
