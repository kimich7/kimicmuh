<?php
    include("CMUHconndata.php");
    include("fun.php");
    session_start();
    $message="";
    $userName=$_SESSION["login_member"];//登錄者的名稱
    $orig_passwd=sql_database('passcard','FA.Employee','cname',$userName);//原本的密碼

    if (empty($_GET["old_passwd"]) or empty($_GET["new_passwd"]) or empty($_GET["check_passwd"]) ) {//輸入舊的密碼
       $message='有一個或部分欄未填寫，請補齊';
    }
    
    if (!empty($_GET["old_passwd"])) {//輸入舊的密碼
       $old_passwd=$_GET["old_passwd"];
    }else{
        $old_passwd="";
    }
    
    if (!empty($_GET["new_passwd"])) {//輸入新的密碼
        $new_passwd=$_GET["new_passwd"];
    }else{
        $new_passwd="";
    }
    
    if (!empty($_GET["check_passwd"])) {//輸入確認密碼
        $check_passwd=$_GET["check_passwd"];
    }else{
        $check_passwd="";
    }   
      

    if ($old_passwd!="" and $new_passwd!="" and $check_passwd!="" ) {
        if ($old_passwd!=$orig_passwd) {
            $message='舊密碼輸入錯誤請重新確認';
        }

        if ($check_passwd!=$new_passwd) {
            $message='確認密碼與新密碼不同請重新確認';
        }

        if ($old_passwd==$orig_passwd and $check_passwd==$new_passwd) {            
            $newpd=$check_passwd;
            $str="UPDATE FA.Employee SET passcard=:passcard WHERE cname=:userName";
            $stmt = $pdo->prepare($str);
            $stmt->bindParam(':passcard',$newpd,PDO::PARAM_STR);
            $stmt->bindParam(':userName',$userName,PDO::PARAM_STR);
            $stmt->execute();
            $message='密碼更換成功，下次請用新密碼登錄';
        }
    }
    echo json_encode($message);
?>