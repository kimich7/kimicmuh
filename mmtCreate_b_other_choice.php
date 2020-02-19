<?php
include("php/CMUHconndata.php");
include("php/fun.php");
session_start();
$userID=$_SESSION["login_number"];//登錄人員ID
$username=$_SESSION["login_member"] ;//登錄人員名稱
$mmtsysNo=$_POST["mmtsysa"];
$mmtsysName=sql_database('sName','FA.MMT_sysDetail','id',$mmtsysNo);
$sysID=sql_database('sid','FA.MMT_sysDetail','id',$mmtsysNo);
$datetime=time_rota();
$fileNo=$sysID.$datetime;
$update=date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- 連結外部的CSS -->
    <link rel="stylesheet" href="./css/bootstrap.css">
    <link href="./node_modules/fontawesome-free-5.0.6/web-fonts-with-css/css/fontawesome-all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./node_modules/normalize.css/normalize.css">
    <!-- 連結自己的CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <!-- 連結外部的JS -->
    <script src="./node_modules/jquery/dist/jquery.min.js"></script>
    <script src="./node_modules/popper.js/dist/umd/popper.min.js"></script>
    <script src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- 連結自己的JS -->
    <script src="./js/main.js"></script>    
    <title>工程專案新增</title>
</head>
<body onload="time_rota()">
     <!-- header網頁標題 -->
    <header>
        <nav class="navbar navbar-expand-sm navbar-light bg-light">
            <a class="navbar-brand" href="index.html">
                <img src="./jpg/logo.png" alt="">
            </a>
        </nav>
    </header>
    <form action="filetoSQL.php" method="POST" name="formmmtchoicea" enctype="multipart/form-data">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-10 col-sm-10 col-12 back container w-50 my-4 rounded">
                    <div id="tablepanel" class="choice_table rounded text-white">                        
                        <div class="choice_table rounded text-white">
                            <!-- 副標題 -->
                            <h2 class="text-center mb-4">檔案上傳</BR><?= $mmtsysName ?></h2>
                            <!-- 單號 -->                            
                            <P>上傳單號: </P>
                            <Input class="form-control mb-3" type='text' name="mmtsysNo_1" value='<?= $fileNo?>' Disabled>
                            <input type='hidden' name='mmtsysNo' value='<?= $fileNo?>'>
                            <!--上傳(保養)人員 -->
                            <P>上傳(保養)人員: </P>                                 
                            <Input class="form-control mb-3" type='text' name="uploademp" value='<?= $username?>' Disabled>
                            <input type='hidden' name='uploadempid' value='<?= $userID?>'>
                            <input type='hidden' name='sdid' value=1>
                            <input type='hidden' name='sid' value='<?= $mmtsysNo; ?>'>
                            <!-- 上傳日期 -->
                            <P>上傳日期: </P>
                            <Input class="form-control mb-3" type='text' name="update_1" value='<?= $update;?>' Disabled>
                            <input type='hidden' name='update' value='<?= $update?>'>
                            <!-- 保養日期 -->
                            <P>保養日期: </P>
                            <input class="form-control mb-3" type="date" name="insertdate" required>
                            <!-- 設備地點 -->
                            <P>設備地點: </P>
                            <Input class="form-control mb-3" type='text' name="location" placeholder="EX:A棟5樓或全院區...等等"/>
                            <!--保養區分 -->
                            <p>
                                保養區分:&nbsp&nbsp</BR>
                                <h3>
                                    <input type="radio" name="b" value='HM'>&nbsp半月&nbsp&nbsp
                                    <input type="radio" name="b" value='M'>&nbsp月&nbsp&nbsp
                                    <input type="radio" name="b" value='S'>&nbsp季&nbsp&nbsp
                                    <input type="radio" name="b" value='HY'>&nbsp半年&nbsp&nbsp
                                    <input type="radio" name="b" value='Y'>&nbsp年
                                    <!-- <input type='checkbox' name="b[]" value='HM'>&nbsp半月&nbsp&nbsp
                                    <input type='checkbox' name="b[]" value='M'>&nbsp月&nbsp&nbsp
                                    <input type='checkbox' name="b[]" value='S'>&nbsp季&nbsp&nbsp
                                    <input type='checkbox' name="b[]" value='HY'>&nbsp半年&nbsp&nbsp
                                    <input type='checkbox' name="b[]" value='Y'>&nbsp年 -->
                                </h3>
                            </p>                           
                            <!-- 檔案上傳 -->
                            <P>選擇上傳檔案: </P>
                            <div class="form-group">
                                <input type="file" class="form-control-file" name="mmtfile" multiple="multiple" />
                            </div>
                            <!-- 備註 -->
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">備註：</span>
                                </div>
                                <textarea class="form-control" name="remark"  rows="5" aria-label="With textarea"></textarea>
                            </div>
                            
                            <!-- 送出鍵 -->
                            <div class="d-flex justify-content-end">                                
                                <span class="billBoard3" tabindex="0" data-toggle="tooltip" data-placement="bottom"
                                    title="請登入相應權限帳號已解鎖">
                                    <button style="pointer-events: none;" type="submit"
                                        class="btn btn-primary mt-4 autho3" formtarget="_self" disabled>送出</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
</body>

<!--JS-->
<script language="JavaScript"> 
function time_rota()  //日期 
{ 
    var now = new Date(); 
    var y = now.getFullYear(); 
    var m = (now.getMonth() + 1>9) ? now.getMonth() + 1 : "0"+(now.getMonth() + 1); 
    var d = (now.getDate()>9)  ? now.getDate()  : "0"+now.getDate(); 
    var h = (now.getHours()>9)  ? now.getHours()  : "0"+now.getHours();
    var mm = (now.getMinutes()>9)  ? now.getMinutes()  : "0"+now.getMinutes();
    var s = (now.getSeconds()>9)  ? now.getSeconds()  : "0"+now.getSeconds();
    var id=y+m+d+h+mm+s; 
    
    return id;
    //$("#pmcid").html(id);
    //document.formpmc.pmcid.value = id;
    //document.formpmc.pmchidden.value = id;

} 
</script> 
</html>