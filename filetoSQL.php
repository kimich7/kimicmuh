<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");    
    //工程編號
    $fileNo=$_POST["mmtsysNo"];
    //系同細節編號
    $sdid=$_POST["sdid"];
    //上傳(保養)人員id
    $fileDownloadUserID=$_POST["uploadempid"];    
    //上傳日期
    $fileDownloadDate=$_POST["update"];
    //保養日期
    $fileInsertDate=$_POST["insertdate"];
    //設備地點
    $eqlocation=$_POST["location"];
    //保養區分
    $fileCycle=$_POST["b"];
    
    // $fileCycle_Array=$_POST["b"];
    // $fileCycle= implode(",", $fileCycle_Array);
    
    //檔案
    //$filePath=$_POST["mmtfile"];
    //備註
    $remark=$_POST["remark"];
    $remark=str_replace(chr(13).chr(10), "<br />",$remark);
    //狀態
    $status='W';

    
    
    if($_FILES['mmtfile']['error']>0){
        $insertStr="INSERT INTO FA.MMA_getFile(fileNo,sdid,fileDownloadUserID,fileDownloadDate,fileInsertDate,eqlocation,fileCycle,remark,status)VALUES('$fileNo',$sdid,'$fileDownloadUserID','$fileDownloadDate','$fileInsertDate','$eqlocation','$fileCycle','$remark','$status')";
    }else{
        move_uploaded_file($_FILES['mmtfile']['tmp_name'], 'mmt_uploadfile/'.$_FILES['mmtfile']['name']);
        $filePath='mmt_uploadfile/'.$_FILES['mmtfile']['name'];
        $insertStr="INSERT INTO FA.MMA_getFile(fileNo,sdid,fileDownloadUserID,fileDownloadDate,fileInsertDate,eqlocation,fileCycle,filePath,remark,status)VALUES('$fileNo',$sdid,'$fileDownloadUserID','$fileDownloadDate','$fileInsertDate','$eqlocation','$fileCycle','$filePath','$remark','$status')";        
        //$insert_mmtUPloadfile=$pdo->exec("INSERT INTO FA.MMA_getFile(fileNo,sdid,fileDownloadUserID,fileDownloadDate,fileInsertDate,eqlocation,fileCycle,filePath,remark,status)VALUES('$fileNo',$sdid,'$fileDownloadUserID','$fileDownloadDate','$fileInsertDate','$eqlocation','$fileCycle','$filePath','$remark','$status')"); 
    }   
    $insert_mmtUPloadfile=$pdo->exec($insertStr);
    
     $pdo=null;
     header("Location: mmt_list_b_other.php");

?>