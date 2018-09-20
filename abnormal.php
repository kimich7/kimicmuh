<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");    

    $errdate=$_POST["errordate"];//日期
    $errloction =$_POST["errortextlocation"];//地點
    $erremp=$_POST["findemp"];//發現人
    $errtitle=$_POST["errortexttitle"];//主旨
    $errtext=$_POST["errortext"];//內容描述
    
    if($_FILES['uploadfile']['error']>0){        
        $insertStr="INSERT INTO FA.Abnormal_Notification_System_Master(case_title,case_location,case_time,case_userID,case_description)VALUES('$errtitle','$errloction','$errdate','$erremp','$errtext')";
    }else{
        move_uploaded_file($_FILES['uploadfile']['tmp_name'], 'abnormalPhoto/'.$_FILES['uploadfile']['name']);
        $location='abnormalPhoto/'.$_FILES['uploadfile']['name'];
        $insertStr="INSERT INTO FA.Abnormal_Notification_System_Master(case_title,case_location,case_time,case_userID,case_description,case_url)VALUES('$errtitle','$errloction','$errdate','$erremp','$errtext','$location')";        
    }
    $insert_abnormal_master=$pdo->exec($insertStr);
     $pdo=null;
     header("Location: index.html");



?>