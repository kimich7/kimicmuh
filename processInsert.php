<?php
include("php/CMUHconndata.php");
include("php/fun.php");
//include("class.phpmailer.php");
// if(!isset($_SESSION['staff_no'])){
//     header('location:login.php');
// }
//get data
$pmcid = $_POST["id"];
$related_id = $_POST["rid"];
$status = $_POST["status"];
$content = $_POST["editor"];
//$emailstatus = $_POST["email"];
$e_number = $_POST["staffno"];
$c=$_POST["choice"];//判斷回哪也頁面的依據
$id = date('YmdHis');

//createdUser
$selectstaff = "select s.e_number,s.cname,d.title from FA.pmc d,FA.Employee s where d.id='".$pmcid."' and d.e_number=s.e_number";
$staffquery = $pdo -> query($selectstaff);
while ($row=$staffquery->fetch()) {
    $staffno=$row['e_number'];
    $name=$row['cname'];
    //$email=$row['email'];
    //$dept=$row['dept'];
    $title=$row['title'];
}

if($e_number == ""){
    header('location:login.php');
}else{
    if($status == 'I'){ //create new process
        $createdOn = date('Y-m-d H:i:s');
        $insertprocess = "insert FA.process(id,pmc_id,related_id,content,createdOn,createdUser,status) values('".$id."','".$pmcid."','".$related_id."','".$content."','".$createdOn."','".$e_number."','Y')";
        $insertquery = $pdo -> prepare($insertprocess);
        $insertquery -> execute();
        $pdo =null;
    }elseif($status == 'U'){ //update process
        $updatedOn = date('Y-m-d H:i:s');
        $updateprocess = "update FA.process set content='".$content."',updatedOn='".$updatedOn."',updatedUser='".$e_number."' where id='".$related_id."'";
        $updatequery = $pdo -> prepare($updateprocess);
        $updatequery -> execute();
        $pdo =null;
    }else{ //delete process
        $deletedOn = date('Y-m-d H:i:s');
        $deleteprocess = "update FA.process set deletedOn='".$deletedOn."',deletedUser='".$e_number."' where id='".$related_id."'";
        $deletequery = $pdo -> prepare($deleteprocess);
        $deletequery -> execute();
        $pdo =null;
    }

    $emailarr = array();

    //email
    // if($emailstatus == 'Y' and $status == 'I' and $related_id == ''){ //通知發案者及其單位主管
    //     $emailarr[] = array(
    //         'staff_no' => $staffno,
    //         'name' => $name,
    //         'email' => $email
    //     );

    //     //managers
    //     $selectmanager = "select staff_no,name,email from staff where dept='".$dept."' and (permissions='5' or permissions='4') and status='Y'";
    //     $managerquery = $GLOBALS["link"] -> prepare($selectmanager);
    //     $managerquery -> execute();
    //     $managerquery -> bind_result($sstaff_no,$sname,$semail);
    //     while($managerquery -> fetch()){
    //         $managerarr = array(
    //             'staff_no' => $sstaff_no,
    //             'name' => $sname,
    //             'email' => $semail
    //         );

    //         array_push($emailarr,$managerarr);
    //     }
    //     $managerquery -> close();
    //     $emailnum = count($emailarr);
    //     for($e = 0;$e < $emailnum;$e++){
    //         //==============(寄發通知信件)=================//
    //         $name = $emailarr[$e]["name"];
    //         $email_addr = $emailarr[$e]["email"];
    //         $s_no = $emailarr[$e]["staff_no"];
    //         $subject = '工作需求通知信';
                
    //         $content = '<table border="0" width="400" cellpadding="0" cellspacing="0">
    //         <tr>
    //         <td width="400" align="left" style="padding:5px;">
    //         <h2 style="font-size:14px; font-family:'."'新細明體'".'; color:#84998e; letter-spacing:1px;">信件內容:</h2>
    //         <p style="font-size:14px; font-family:'."'新細明體'".'; color:#333333; letter-spacing:1px; line-height:21px;">
    //         '.$name.' 您好:
    //         <br>
    //         處理回覆如下，請點選連結查看詳細資訊
    //         <br>
    //         <a href="http://www.globalnetauto.url.tw/gna_helper/demand.php?id='.$demand_id.'&sno='.$s_no.'">'.$title.'</a>
    //         <br>
    //         </p>
    //         </td>
    //         </tr>
    //         </table>';
    //         ////寄件人信箱 會顯示
    //         $mail_from = 'service@globalnetauto.com.tw';
                
                
    //         $mail = new PHPMailer(true);
    //         $mail->Host = "sp22.g-dns.com"; //請填localhost即可
    //         $mail->Port = 465; //郵件伺服
    //         $mail->CharSet = "utf-8";// 信件內容的編碼方式 
    //         $mail->Encoding = "base64";// 信件處理的編碼方式
    //         $mail->Username = $mail_from;// SMTP 驗證的使用者資訊
    //         $mail->Password = "28328809!";  //在cpanel新增mail帳號時設定的密碼，請小心是否有空格，空格也算一碼。  
    //         $mail->From = $mail_from; //需要與上述的使用者資訊相同mail
    //         $mail->FromName = "Global Net Automotive"; //此顯示寄件者名稱
    //         $mail->Subject = $subject; //信件主旨
    //         //=======(信件內容)=======//
    //         $mailBody = $content;
                        
    //         //=======(信件內容)=======//
    //         $mail->Body = $mailBody;   //信件內容 
    //         $mail->IsHTML(true);
    //         $mail->AddAddress($email_addr, $name);//此為收件者的電子信箱及顯示名稱
    //         //$mail->AddAttachment("images/VAQ2_logo.png");//附件
    //         // 顯示訊息
    //         if(!$mail->Send()) {     
    //             //$data = "Mail error: " . $mail->ErrorInfo; //echo來檢查用的
                    
    //         }else {
                    
    //         }
    //     }
    // }elseif($emailstatus == 'Y' and $status == 'I' and $related_id != ''){ //通知處理者
    //     //process staff info
    //     $selectstaffinfo = "select staff_no,name,email from process p,staff s where p.id='".$related_id."' and p.createdUser=s.staff_no";
    //     $infoquery = $GLOBALS["link"] -> prepare($selectstaffinfo);
    //     $infoquery -> execute();
    //     $infoquery -> bind_result($pstaff_no,$pname,$pemail);
    //     $infoquery -> fetch();
    //     $infoquery -> close();

    //     //==============(寄發通知信件)=================//
    //     $name = $pname;
    //     $email_addr = $pemail;
    //     $ps_no = $pstaff_no;
    //     $subject = '工作需求通知信';
                
    //     $content = '<table border="0" width="400" cellpadding="0" cellspacing="0">
    //     <tr>
    //     <td width="400" align="left" style="padding:5px;">
    //     <h2 style="font-size:14px; font-family:'."'新細明體'".'; color:#84998e; letter-spacing:1px;">信件內容:</h2>
    //     <p style="font-size:14px; font-family:'."'新細明體'".'; color:#333333; letter-spacing:1px; line-height:21px;">
    //     '.$name.' 您好:
    //     <br>
    //     指派任務回覆如下，請點選連結查看詳細資訊
    //     <br>
    //     <a href="http://www.globalnetauto.url.tw/gna_helper/demand.php?id='.$demand_id.'&sno='.$ps_no.'">'.$title.'</a>
    //     <br>
    //     </p>
    //     </td>
    //     </tr>
    //     </table>';
    //     ////寄件人信箱 會顯示
    //     $mail_from = 'service@globalnetauto.com.tw';
                
                
    //     $mail = new PHPMailer(true);
    //     $mail->Host = "sp22.g-dns.com"; //請填localhost即可
    //     $mail->Port = 465; //郵件伺服
    //     $mail->CharSet = "utf-8";// 信件內容的編碼方式 
    //     $mail->Encoding = "base64";// 信件處理的編碼方式
    //     $mail->Username = $mail_from;// SMTP 驗證的使用者資訊
    //     $mail->Password = "28328809!";  //在cpanel新增mail帳號時設定的密碼，請小心是否有空格，空格也算一碼。  
    //     $mail->From = $mail_from; //需要與上述的使用者資訊相同mail
    //     $mail->FromName = "Global Net Automotive"; //此顯示寄件者名稱
    //     $mail->Subject = $subject; //信件主旨
    //     //=======(信件內容)=======//
    //     $mailBody = $content;
                        
    //     //=======(信件內容)=======//
    //     $mail->Body = $mailBody;   //信件內容 
    //     $mail->IsHTML(true);
    //     $mail->AddAddress($email_addr, $name);//此為收件者的電子信箱及顯示名稱
    //     //$mail->AddAttachment("images/VAQ2_logo.png");//附件
    //     // 顯示訊息
    //     if(!$mail->Send()) {     
    //         //$data = "Mail error: " . $mail->ErrorInfo; //echo來檢查用的
                    
    //     }else{
                    
    //     }
    // }else{

    // }
    $_SESSION["msg"] = "回覆建立完成";
}
switch ($c) {
    case 'check':
        header('Location:pmcheck.php?id='.$pmcid);
        break;
    case 'detail':
        header('Location:pmcDetail.php?id='.$pmcid);
        break;
    case 'edit':
        header('Location:pmcEdit.php?id='.$pmcid);
        break;
    default:
        header('Location:pmc.php?filter=no');
        break;
}

?>