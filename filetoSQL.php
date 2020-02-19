<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");    
    //工程編號
    $fileNo=$_POST["mmtsysNo"];
    //系同細節編號
    $sdid=(int)$_POST["sdid"];
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
    switch ($sdid) {
        case 1:
            header("Location: mmt_list_filepage.php?sdid=1");
            break;
        case 2:
            header("Location: mmt_list_filepage.php?sdid=2");
            break;
        case 3:
            header("Location: mmt_list_filepage.php?sdid=3");
            break;
        case 4:
            header("Location: mmt_list_filepage.php?sdid=4");
            break;
        case 5:
            header("Location: mmt_list_filepage.php?sdid=5");
            break;
        case 6:
            header("Location: mmt_list_filepage.php?sdid=6");
            break;
        case 7:
            header("Location: mmt_list_filepage.php?sdid=7");
            break;
        case 8:
            header("Location: mmt_list_filepage.php?sdid=8");
            break;
        case 9:
            header("Location: mmt_list_filepage.php?sdid=9");
            break;
        case 10:
            header("Location: mmt_list_filepage.php?sdid=10");
            break;
        case 11:
            header("Location: mmt_list_filepage.php?sdid=11");
            break;
        case 12:
            header("Location: mmt_list_filepage.php?sdid=12");
            break;
        case 13:
            header("Location: mmt_list_filepage.php?sdid=13");
            break;
        
    }
     //header("Location: mmt_list_b_other.php");

?>