<?php
include("php/CMUHconndata.php");
include("php/fun.php");
$mmtsysNo=$_POST["mmtsysa"];
$mmtsysName=sql_database('sName','FA.MMT_sys','id',$mmtsysNo);

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
    <form action="mmtCreate_e.php" method="get" name="formmmtchoiceb">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-10 col-sm-10 col-12 back container w-50 my-4 rounded">
                    <div id="tablepanel" class="choice_table rounded text-white">                        
                        <div class="choice_table rounded text-white">
                            <!-- 副標題 -->
                            <h2 class="text-center mb-4"><?= $mmtsysName ?></br>分電盤保養表單</h2>
                            <!-- 單號 -->
                            <P>保養系統: </P>                            
                            <select class="form-control mb-3 mmtsysa" name="mmtsysa" id="mmtsysa" >
                            <option value='<?= $mmtsysNo ?>' selected><?= $mmtsysNo ?>-<?= $mmtsysName ?></option>
                            </select>
                            <!--工程名稱 -->
                            <P>保養設備大樓: </P>                                 
                            <select class="form-control mb-3" name="mmtbuilda" id="mmtbuilda" required>
                                <!--f1-->
                                <option selected>請選擇大樓</option> 
                            </select>
                            <P>保養設備樓層: </P>
                            <select class="form-control mb-3" name="mmtfloora" id="mmtfloora" required>
                                <!--f1-->
                                <option selected>請選擇樓層</option>
                                </select>
                            <!-- 送出鍵 -->
                            <div class="d-flex justify-content-end">                                
                                <span class="billBoardpowerL1 billBoardpowerL2 billBoardpowerL3" tabindex="0" data-toggle="tooltip" data-placement="bottom"
                                    title="請登入相應權限帳號已解鎖">
                                    <button style="pointer-events: none;" type="submit"
                                        class="btn btn-primary mt-4 powerL1 powerL2 powerL3" formtarget="_self" disabled>送出</button>
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
//20191127
    //載入建築表養的大樓資訊
    $.getJSON("php/mmtsys_b_data.php", {
        sysID:'E'
    }, function (data) {
        var html = '<option value="" selected> 請選擇大樓 </option>';
        for (let i = 0; i < data.length; i++) {
            html += "<option value=\"" + data[i]["id"] + "\">" + data[i]["bName"] + "</option>";
            $("#mmtbuildb").html(html);
        }
    });




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
    //$("#pmcid").html(id);
    document.formpmc.pmcid.value = id;
    document.formpmc.pmchidden.value = id;

} 
</script> 
</html>