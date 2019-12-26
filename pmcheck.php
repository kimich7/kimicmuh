<?php
include("php/CMUHconndata.php");
include("php/fun.php");
session_start();
if(isset($_GET["id"]) and $_GET["id"] != ''){
    $id = $_GET["id"];
}
$userID=$_SESSION["login_number"];//登錄人員ID
$username=$_SESSION["login_member"] ;//登錄人員名稱
$str="SELECT sid FROM FA.securityemp as e Left join FA.securityKind as k on e.sid=k.id WHERE e.e_number = '$userID' and k.id=34 ";
@$logLevel=item($str);
if ($logLevel[0]['sid']!='34') {
    echo '<h3>您並非主管到此頁面，稍後系統將回到首頁</h3>';
    header("Refresh:3;url=pmc.php?filter=no");
}

$detail="SELECT d.id,d.e_number,d.category,d.title,d.createdOn,d.process,d.content,d.contract,d.building,d.endon,d.status,s.cname from FA.pmc as d LEFT JOIN FA.Employee as s ON d.e_number=s.e_number where d.id='$id'";
$pmcdetailstr=$pdo->query($detail);
while ($row=$pmcdetailstr -> fetch()) {
    $id=$row['id'];
    $pmcemp=$row['e_number'];
    $category=$row['category'];
    $title=$row['title'];
    $createdOn=$row['createdOn'];
    $process=$row['process'];
    $content=$row['content'];
    $contract=$row['contract'];
    $building=$row['building'];
    $endon=$row['endon'];
    $status=$row['status'];
    $cname=$row['cname'];
}
$title=str_replace(chr(13).chr(10), "<br/>", $title);
$process=str_replace(chr(13).chr(10), "<br/>", $process);
$content=str_replace(chr(13).chr(10), "<br/>", $content);
$contract=str_replace(chr(13).chr(10), "<br/>", $contract);
$building=str_replace(chr(13).chr(10), "<br/>", $building);
$selectprocess = "select p.id,p.content,p.createdOn,p.createdUser,p.updatedOn,p.updatedUser,p.deletedOn,p.deletedUser,p.status,s.cname from FA.process as p LEFT JOIN FA.Employee as s ON p.createdUser=s.e_number where p.pmc_id='".$id."' and p.related_id='' and p.status='Y' order by p.createdOn DESC";
$processquery = $pdo-> query($selectprocess);
    if ($pmcemp==$userID) {
        $editor=1;
    } else {
        $editor=0;
    }
                            
$pro_arr=array();
while($prorow=$processquery -> fetch()){
        $pid = $prorow['id'];
        $pcontent = $prorow['content'];
        $pcreatedOn = $prorow['createdOn'];
        $pcreatedUser = $prorow['createdUser'];
        $pupdatedOn = $prorow['updatedOn'];
        $pupdatedUser = $prorow['updatedUser'];
        $pdeletedOn = $prorow['deletedOn'];
        $pdeletedUser = $prorow['deletedUser'];
        $pstatus = $prorow['status'];
        $pcname = $prorow['cname'];
    $pro_arr[] = array(
        'id' => $pid,
        'content' => $pcontent,
        'createdOn' => $pcreatedOn,
        'createdUser' => $pcreatedUser,
        'updatedOn' => $pupdatedOn,
        'updatedUser' => $pupdatedUser,
        'deletedOn' => $pdeletedOn,
        'deletedUser' => $pdeletedUser,
        'status' => $pstatus,
        'cname' => $pcname
    );
};
$pro_num = count($pro_arr);
$GLOBALS["x"] = 1;
$str=$pdo;
//判斷是否有子層
function child_check($id,$pid){
    $selectchild = "select p.id,p.content,p.createdOn,p.createdUser,p.updatedOn,p.updatedUser,p.deletedOn,p.deletedUser,p.status,s.cname from FA.process as p LEFT JOIN FA.Employee as s ON p.createdUser=s.e_number where p.pmc_id='".$id."' and p.related_id='".$pid."' and p.status='Y' order by p.createdOn DESC";
    $childquery = $GLOBALS["str"] -> query($selectchild);
    $numstr="SELECT Count(*) from FA.process as p LEFT JOIN FA.Employee as s ON p.createdUser=s.e_number where p.pmc_id='".$id."' and p.related_id='".$pid."' and p.status='Y'";
    $childnum=Current($GLOBALS["str"]->query($numstr)->fetch());
    while($chirow=$childquery -> fetch()){
        $cid=$chirow['id'];
        $ccontent=$chirow['content'];
        $ccreatedOn=$chirow['createdOn'];
        $ccreatedUser=$chirow['createdUser'];
        $cupdatedOn=$chirow['updatedOn'];
        $cupdatedUser=$chirow['updatedUser'];
        $cdeletedOn=$chirow['deletedOn'];
        $cdeletedUser=$chirow['deletedUser'];
        $cstatus=$chirow['status'];
        $csname=$chirow['cname'];
        $charr[] = array(
            'id' => $cid,
            'content' => $ccontent,
            'createdOn' => $ccreatedOn,
            'createdUser' => $ccreatedUser,
            'updatedOn' => $cupdatedOn,
            'updatedUser' => $cupdatedUser,
            'deletedOn' => $cdeletedOn,
            'deletedUser' => $cdeletedUser,
            'status' => $cstatus,
            'name' => $csname
        );        
    }
    if($childnum > 0){ //有相關回覆
        $GLOBALS["x"] += 1;
        $x = (100 - $GLOBALS["x"])*1;
        foreach ($charr as $key => $value) {
            $contenthtml = '<div class="process_box" style="margin-top:-20px; width:'.$x.'%; float:right;">
                <ul class="process_content">'.$value["content"].'</ul>
                <ul class="process_info"><li class="process_li">'.$value["name"];
                if($value["status"] == 'Y' and $value["updatedUser"] == ''){
                    $contenthtml.='(建立時間: '.$value["createdOn"].')';
                }elseif($value["status"] == 'Y' and $value["updatedUser"] != ''){
                    $contenthtml.='(更新時間: '.$value["updatedOn"].')';
                }
        $contenthtml.='</li>';
                    //if($_SESSION["dept"] == $GLOBALS["dept_id"] or in_array($_SESSION["dept"],$GLOBALS["deptidarr"])){
                       $contenthtml.='<li class="process_li">
                       <a onclick="process_info('.$id.','.$value["id"].','."'".'I'."'".')" class="btn btn-primary btn-sm" style="margin:0;">回覆</a>
                       </li>';
                    //}
                    if($_SESSION["login_number"] == $ccreatedUser){
                       $contenthtml.='<li class="process_li">
                       <a onclick="process_info('.$id.','.$value["id"].','."'".'U'."'".')" class="btn btn-info btn-sm" style="margin:0;">修改</a>
                       </li>';
                    }
                    $contenthtml.='</ul>
                </div>';

        echo $contenthtml;
        echo child_check($id,$value["id"]); //繼續往下找
        }
    }
}
if (isset($_POST["action"])&&($_POST["action"]=="Check")) {
    $id=$_POST["demandid"];//pmcid工程單號
    $pmcCheck=$_POST['pmcCheck'];//是否勾選審核欄位
    $Status=$_POST['status'];//現狀狀態如何

    if ($pmcCheck=='F') {
        $sans='F';
    } else {
        if ($Status=='F' or $Status=='M') {
            $sans='M';
        } else {
            $sans=$Status;
        }
        
    }
    
    $datetime = date("Y-m-d H:i:s");
    $datetime1=$datetime.'.000';
    $compare=$_POST["compare"];    
    (string)$pid=date("YmdHis");

    $MasterStr="UPDATE FA.pmc SET status=:status WHERE id=:id";
    $stmtM = $pdo->prepare($MasterStr);
    $stmtM->bindParam(':status',$sans,PDO::PARAM_STR);
    $stmtM->bindParam(':id',$id,PDO::PARAM_STR);
    $stmtM->execute();    
    $pdo=null;
    
    header("Location:pmc.php?filter=no");
}



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
    <title>工程專案內容</title>
    <!--牧宏引進的-->
    <!-- 連結自己的CSS -->
    
    <link href="css/menu.css" rel="stylesheet">
    <link href="css/mail.css" rel="stylesheet">
    <link href="css/reset.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <!-- SB Admin -->
    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- SB Admin -->
    <style>
        body {
            background-color: #f8f8f8;
        }
        /*
        #right_area {
            width: 100%;
            display: block;
            position: absolute;
            left: 0;
            top: 0;
            padding: 50px 20px 10px 100px;
            margin: 0;
            overflow: hidden;
            -webkit-box-sizing: border-box;
            -ms-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        */
        #right_area {
            padding: 50px 30px 10px 50px;
        }
        .content_box {
            clear:both;
            width: 95%;  
            height:auto;  
            display: block;
            position: relative;
            float: left;
            margin: 0 0 20px 0;
            padding: 10px;
            overflow: hidden;
            border: 2px solid #A9A9A9;
            border-radius: 5px;
            -webkit-box-sizing: border-box;
            -ms-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            font-family: '微軟正黑體';
            background-color :#F0FFFF;
            /* background-color :#BBFFEE; */
        }
        .attachment_box {
            width: 45%;
            display: block;
            position: relative;
            float: left;
            margin: 0 0 20px 10px;
            padding: 10px;
            overflow: hidden;
            border: 1px solid #eee;
            border-radius: 5px;
            -webkit-box-sizing: border-box;
            -ms-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .attachment_box a {
            font-size: 14px;
            font-family: '微軟正黑體';
            letter-spacing: 1px;
            line-height: 18px;
            text-decoration: none;
        }
        .attachment_box a:hover {
            font-weight: bold;
        }
        .info_box {
            width: 25%;
            display: block;
            position: relative;;
            float: left;
            margin: 0 10px 10px 0;
            padding: 10px;
            overflow: hidden;
            border: 1px solid #eee;
            border-radius: 5px;
            -webkit-box-sizing: border-box;
            -ms-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .info_name {
            position: relative;
            margin: 5px 0 0 0;
        }
        .infoname {
            width:15%;
            display: block;
            position: relative;
            float: left;
            margin: 5px 10px 0 0;
            padding: 5px;
            overflow: hidden;
            border: 1px solid #eee;
            border-radius: 5px;
            -webkit-box-sizing: border-box;
            -ms-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .demand_box {
            width: 100%;
            display: block;
            position: relative;;
            float: left;
            padding: 10px;
            overflow: hidden;
            border: 1px solid #eee;
            border-radius: 5px;
            font-size:14px;
            font-family: '微軟正黑體';
            letter-spacing: 1px;
            -webkit-box-sizing: border-box;
            -ms-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        /* process */
        .process_box {
            clear:both;
            width: 100%;
            display: block;
            position: relative;
            padding: 10px;
            margin:0 0 20px 0;
            font-size: 14px;
            font-family: '微軟正黑體';
            letter-spacing: 1px;
            line-height: 21px;
            border: 1px solid #000000;
            border-radius: 5px;
            -webkit-box-sizing: border-box;
            -ms-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            background-color: #f5f5f5;
            /* background-color: #fff; */
        }
        .process_box:nth-child(odd) {
            background-color:#f5f5f5;
            /* background-color:#fcfcfc */
        }
        .process_box ul.process_content {
            clear: both;
            width: 100%;
            display: block;
            padding: 0 5px 10px 5px;
            border-bottom: 1px solid #eee;
            overflow: hidden;
            -webkit-box-sizing: border-box;
            -ms-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .process_box ul.process_info {
            clear: both;
            width: 100%;
            display: block;
            padding: 10px 5px 0 5px;
            overflow: hidden;
            font-size: 12px;
            -webkit-box-sizing: border-box;
            -ms-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .process_box ul.process_info li {
            position:relative;
            float: right;
            margin: 0 0 0 5px;
        }
        .process_box ul.process_info li:first-child {
            float: left;
            margin: 0;
        }

        /* CK-edit */
        .error_msg {
            font-size:14px;
            font-family: '微軟正黑體';
            letter-spacing: 1px;
            color: red;
        }

        #ckbox {
            width: 50%;
            display: block;
            position: fixed;
            top: -500px;
            padding: 10px;
            overflow: hidden;
            opacity: 0;
            z-index: 1002;
            border: 1px solid #eee;
            border-radius: 5px;
            background-color: #fff;
            -webkit-box-sizing: border-box;
            -ms-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        #assigncheck {
            width: 50%;
            display: block;
            position: absolute;
            top: 0px;
            left: 0px;
            overflow: hidden;
            opacity: 0;
            z-index: 1002;
        }

        /* keybox */
        .keybox {
            width:25%;
            display: block;
            position: relative;
            float: left;
            margin: 5px 10px 0 0;
            padding: 5px;
            overflow: hidden;
            border: 1px solid #eee;
            border-radius: 5px;
            background-color: #f7f7f7;
            -webkit-box-sizing: border-box;
            -ms-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .keybox ul {
            width: calc(100% - 40px);
            margin: 0;
            float: left;
        }

    </style>
</head>
<body >
     <!-- header網頁標題 -->
    <header>
        <nav class="navbar navbar-expand-sm navbar-light bg-light">
            <a class="navbar-brand" href="index.html">
                <img src="./jpg/logo.png" alt="">
            </a>
        </nav>
    </header>
    
    <!--回覆視窗-->
    <div id="ckbox">
        <form method="post" name="process_form">
            <input type="hidden" name="id" value="">
            <input type="hidden" name="rid" value="">
            <input type="hidden" name="status" value="">
            <input type="hidden" name="choice" value="check">
            <input type="hidden" name="staffno" value="<?php echo $_SESSION["login_number"]; ?>">
            <div class="form-group">
                <label>回覆內容</label>
                <textarea name="editor" id="editor" class="form-control" rows="12"></textarea>
            </div>            
            <div class="form-group" style="border-top:1px solid #eee;">
                <a class="btn btn-primary" id="submit">送出</a>
                <a class="btn btn-warning" id="cancel" onclick="cancel_process()">取消</a>
            </div>
        </form>
    </div>
    <!--回覆視窗end-->     
    
    <?php
        include_once "ckeditor/ckeditor.php";
        $CKEditor = new CKEditor();
        $CKEditor->basePath = 'ckeditor/';
        $CKEditor->replace("editor");
    ?>
    
    <div id="right_area">
        <div class="row">    
            <div class="col-lg-12">            
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <span style="font-weight:bold;"><font color="red">工程單號 [<?php echo $id; ?>] </font></span><a class="btn btn-success btn-sm" style="margin: 0 0 0 10px;" href="pmc.php?filter=no">返回列表</a>
                    </div>                    
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-7">
                            <form action="" method="post" name="pmcEdit">
                                <input type="hidden" name="demandid" value="<?php echo $id; ?>">
                                <!--工程類型category-->
                                <div class="form-group">
                                <label><span style="font-weight:bold;">工程類型：</span></label>
                                    <div class="content_box"><?php echo $category; ?></div>
                                </div>
                                <!--工程名稱title-->
                                <div class="form-group">
                                <label><span style="font-weight:bold;">工程名稱：</span></label>
                                <div class="content_box"><?php echo $title; ?></div>
                                </div>
                                <!--工程流程進度及金額process-->
                                <div class="form-group">
                                <label><span style="font-weight:bold;">工程流程進度及金額：</span></label>
                                <div class="content_box"><?php echo $process; ?></div>                                
                                </div>
                                
                                <!--工程進度/異常說明content-->
                                <input type="hidden" name="compare" value="<?php echo $content; ?>">
                                <div class="form-group">
                                <label><span style="font-weight:bold;">工程進度/異常說明：</span></label>
                                <div class="content_box"><?php echo $content; ?></div>
                                </div>
                                
                                <!--日期、決包廠商、金額contract-->
                                <div class="form-group">
                                <label><span style="font-weight:bold;">日期、決包廠商、金額：</span></label>
                                <div class="content_box"><?php echo $contract; ?></div>
                                </div>
                                
                                <!--工程範圍(棟別)building-->
                                <div class="form-group">
                                <label><span style="font-weight:bold;">工程範圍(位置)：</span></label>
                                <div class="content_box"><?= $building ?></div>
                                </div>
                                
                                <!--承辦人-->                            
                                <div class="form-group" style="clear:both;">
                                <label><span style="font-weight:bold;">承辦人：</span></label>
                                <div class="content_box"><?php echo $cname; ?></div>
                                </div>
                                
                                <!--交辦日期-->
                                <div class="form-group">
                                <label><span style="font-weight:bold;">交辦日期：</span></label>
                                <div class="content_box"><?php echo $createdOn; ?></div>
                                </div> 
                                <input type="hidden" name="action" value="Check">
                                <input type="hidden" name="status" value="<?= $status ?>">
                                
                                <!-- 審核確認 --> 
                                <div class="form-group">   
                                <?php if ($status=='W'or $status=='' or $status==null or $logLevel[0]['sid']!='34') {?>
                                    <label><span style="font-weight:bold;" title="非主管身分或案件尚未開始進行無法審核">工程專案主管：<?= $username?>&nbsp&nbsp&nbsp<input type="checkbox" name="pmcCheck" value='F' disabled>審核</span></label>
                                <?PHP } ?>
                                <?php if ($status =='M') {?>
                                    <label><span style="font-weight:bold;">工程專案主管：<?= $username?>&nbsp&nbsp&nbsp<input type="checkbox" name="pmcCheck" value='F'>審核</span></label>
                                <?PHP } ?>
                                <?php if ($status =='F') {?>
                                    <label><span style="font-weight:bold;">工程專案主管：<?= $username?>&nbsp&nbsp&nbsp<input type="checkbox" name="pmcCheck" value='F' checked>審核</span></label>
                                <?PHP } ?>
                                </div>
                                <!-- 送出鈕 -->
                                <!-- <div class="form-group">  -->
                                    <div class="d-flex justify-content-end">
                                    <?php if ($logLevel[0]['sid']=='34'&& ($status =='M' or $status =='F')) {?>
                                        <button type="submit"
                                            class="btn btn-primary" formtarget="_self">送出</button>&nbsp&nbsp&nbsp
                                    <?php } else {?>
                                        <span data-toggle="tooltip" data-placement="bottom" title="非主管身分或案件尚未開始進行無法審核">
                                        <button style="pointer-events: none;" type="submit"
                                            class="btn btn-primary" formtarget="_self" disabled>送出</button>&nbsp&nbsp&nbsp
                                        </span>
                                    <?php }?>
                                    <input type="button" value="返回離開" class="btn btn-primary" onclick="location.href='pmc.php?filter=no'">
                                    </div>
                                <!-- </div> -->
                            </form>
                            </div>
                        
                            <!-- 進度回報 -->                           
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label><span style="font-weight:bold; float:left">工程內容履歷/主管意見或回覆：</span></label>
                                    <div style="float:right; position:relative;top:-15px"><a onclick="process_info(<?php echo $id; ?>,'','I')"   class="btn btn-success btn-sm">新增回覆</a></div>
                                </div>
                                
                                <?php for($i = 0; $i < $pro_num; $i++){ ?>
                                <div class="process_box">
                                    <ul class="process_content">
                                    <?php echo $pro_arr[$i]["content"]; ?>
                                    </ul>
                                    <ul class="process_info">
                                        <li class="process_li">
                                        <?php echo $pro_arr[$i]["cname"]; ?>
                                        <?php if($pro_arr[$i]["status"] == 'Y' and $pro_arr[$i]["updatedUser"] == ''){ ?>
                                        (建立時間: <?php echo $pro_arr[$i]["createdOn"]; ?>)
                                        <?php }elseif($pro_arr[$i]["status"] == 'Y' and $pro_arr[$i]["updatedUser"] != ''){ ?>
                                        (更新時間: <?php echo $pro_arr[$i]["updatedOn"]; ?>)
                                        <?php } ?>
                                        </li>
                                        <li class="process_li">
                                        <a onclick="process_info(<?php echo $id; ?>,<?php echo $pro_arr[$i]["id"]; ?>,'I')" class="btn btn-primary btn-sm" style="margin:0;">回覆</a>
                                        </li>
                                        <?php if($_SESSION["login_number"] == $pro_arr[$i]["createdUser"]){ ?>
                                        <li class="process_li">
                                        <a onclick="process_info(<?php echo $id; ?>,<?php echo $pro_arr[$i]['id']; ?>,'U')" class="btn btn-info btn-sm" style="margin:0;">修改</a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <?php echo child_check($id,$pro_arr[$i]["id"]); ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<!-- SB Admin -->
    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
<!-- SB Admin -->
<script>
//delete assign
function delete_assign(obj){
    var returnconfirm=confirm('確定要刪除此指派人員嗎?');
    if(returnconfirm == true){
        var ID = obj.name;
        var demandid = $('input[name=demandid]').val();
        document.getElementById(ID).remove();

        //console.log(ID);
        //console.log(demandid);
        $.ajax({
            url:'deleteAssign.php',//url:'要傳送到的程式'
            cache: false,
            type:'post',
            fileElementId:'file',
            data:{"ID":ID,"demandid":demandid},//{'傳過去的質的名稱':所選擇的質}
            //dataType:'json',//傳送方式
            beforeSend :function(){},//function裡可放一張Loading的gif圖檔
            success:function(response){
                //do nothing
            },
            error:function(){
                alert("錯誤");
            }
        });
    }
}

//delete key
function deletekey(id,key){
    var returnconfirm=confirm('確定要刪除此關鍵字嗎?');
    if(returnconfirm == true){
        document.getElementById(key).remove();

        $.ajax({
            url:'deleteKey.php',//url:'要傳送到的程式'
            cache: false,
            type:'post',
            fileElementId:'file',
            data:{"id":id},//{'傳過去的質的名稱':所選擇的質}
            //dataType:'json',//傳送方式
            beforeSend :function(){},//function裡可放一張Loading的gif圖檔
            success:function(response){
                //do nothing
            },
            error:function(){
                alert("錯誤");
            }
        });
    }
}

//insert / update process
function process_info(id,rid,status){
    var ID = id,
        RelatedID = rid,
        Status = status,
        winwidth = $(window).width(),
        winheight = $(window).height(),
        staffno = $('input[name=staffno]').val();

    $('input[name=id]').val(ID);
    $('input[name=rid]').val(RelatedID);
    $('input[name=status]').val(Status);
    $('.error_msg').remove();

    if(staffno == ''){
    	window.location = "login.php";
    }
    else{
	    $('body').append('<div id="mask_box"></div>');
	    $('#mask_box').css({
	        position:'fixed',
	        left:0,
	        top:0,
	        width:winwidth,
	        height:winheight,
	        backgroundColor:'#000',
	        opacity:0.8,
	        zIndex:1001
	    });

	    //update content
	    if(Status == 'U'){
	        $.ajax({
	            url:'ajax_process.php',//url:'要傳送到的程式'
	            cache: false,
	            type:'post',
	            fileElementId:'file',
	            data:{"ID":RelatedID},//{'傳過去的質的名稱':所選擇的質}
	            //dataType:'json',//傳送方式
	            beforeSend :function(){},//function裡可放一張Loading的gif圖檔
	            success:function(response){
	                CKEDITOR.instances['editor'].setData(response);
	            },
	            error:function(){
	                alert("錯誤");
	            }
	        });
	    }

	    $('#ckbox').css({
	        left: winwidth / 4,
	        top:0
	    }).animate({
	        top: '100px',
	        opacity: 1
	    }, 500);

	    //submit
	    $('#submit').click(function(){
	        $('.error_msg').remove();
	        var submit = true;
	        var data = CKEDITOR.instances['editor'].getData();

	        if(data == ''){
	            $('#cke_editor').addClass('error_input');
	            $('#cke_editor').parent().append('<span class="error_msg">請輸入回覆內容</span>');
	            submit = false;
	        }
	        if(submit == true){          
	            $('form[name=process_form]').attr('action','processInsert.php').submit();
	        }else{
	            return false;
	        }
	    });
	}
}

//cancel process
function cancel_process(){
    $('input[name=id]').val('');
    $('input[name=rid]').val('');
    CKEDITOR.instances['editor'].setData('');
    $('#mask_box').remove();
    $('#ckbox').css({
        top:'-500px',
        opacity:0
    });
}

//cancel assign
function cancel_assign(){
    $('#mask_box, #assigncheck').remove();
}

//assign member
function assign_member($id,$staffid){
    var winwidth = $(window).width(),
        winheight = $(window).height();

    $('body').append('<div id="mask_box"></div>');
    $('#mask_box').css({
        position:'fixed',
        left:0,
        top:0,
        width:winwidth,
        height:winheight,
        backgroundColor:'#000',
        opacity:0.8,
        zIndex:1001
    });

    $.ajax({
        url:'ajax_assign.php',//url:'要傳送到的程式'
        cache: false,
        type:'post',
        fileElementId:'file',
        data:{"id":$id,"sid":$staffid},//{'傳過去的質的名稱':所選擇的質}
        dataType:'json',//傳送方式
        beforeSend :function(){},//function裡可放一張Loading的gif圖檔
        success:function(data){
            $('body').append(data["content"]);
            $('#assigncheck').css({
                left: winwidth / 4,
                top:0
            }).animate({
                top: '100px',
                opacity: 1
            }, 500);
        },
        error:function(){
            alert("錯誤");
        }
    });
}

//delete assign
function deleteassign(obj){
    var ID = obj.name;
    $('#assign_members').find('#'+ID).remove();
    $('input[name=staff]').each(function(){
        if($(this).val() == ID){
            $(this).prop('checked',false);
        }
    });
}

//update assign
function updateassign(){
    var assignids = '',
        assignlength = $('.infoname').length;
    //console.log(assignlength);
    if(assignlength < 1){
        alert('請選擇要指派的同仁!');
    }else{
        for(var i = 0; i < assignlength; i++){
            if(i == (assignlength - 1)){
                assignids = assignids+$('.infoname').eq(i).attr('id');
            }
            else{
               assignids = assignids+$('.infoname').eq(i).attr('id')+','; 
            }
        }
        $('input[name=staffnos]').val(assignids);
        $('form[name=assign_form]').attr('action','assign_update.php').submit();
    }
    //console.log(assignids);
}

//completed_report
function completed_report($id,$staffid){
     var returnconfirm=confirm('確定完成嗎?');
    if(returnconfirm == true){
        $.ajax({
            url:'ajax_completed.php',//url:'要傳送到的程式'
            cache: false,
            type:'post',
            fileElementId:'file',
            data:{"id":$id,"sid":$staffid},//{'傳過去的質的名稱':所選擇的質}
            //dataType:'json',//傳送方式
            beforeSend :function(){},//function裡可放一張Loading的gif圖檔
            success:function(response){
                alert('完成通知!');
                location.reload();
            },
            error:function(){
                alert("錯誤");
            }
        });
    }
}

//addkey
function addkey(id){
    var key = $('input[name=key]').val();

    if(key == ''){
        alert('請輸入關鍵字!');
        return false;
    }else{
        $.ajax({
            url:'ajax_addkey.php',//url:'要傳送到的程式'
            cache: false,
            type:'post',
            fileElementId:'file',
            data:{"id":id,"key":key},//{'傳過去的質的名稱':所選擇的質}
            dataType:'json',//傳送方式
            beforeSend :function(){},//function裡可放一張Loading的gif圖檔
            success:function(data){
                if(data["status"] == 'Y'){
                    $('#keys').append('<div class="keybox" id="'+key+'"><ul>'+key+'</ul><a onclick='+"deletekey('"+data["id"]+"','"+key+"')"+' class="btn btn-danger btn-xs" style="margin: 0; padding: 0px; width:30px; float:right;">X</a></div>');

                    $('input[name=key]').val('');
                }
                if(data["status"] == 'N'){
                    alert('關鍵字已存在!');
                }
            },
            error:function(){
                alert("錯誤");
            }
        });
    }
}

//jQuery
$(function(){
    //update assign checkbox
    $(document).on('click','input[name=staff]',function(){
        var staffid = $(this).val();
            staffname = $(this).attr('sname');

        if($(this).prop('checked')){ //要指派
            $(this).prop('checked',true);
            //console.log(staffid+'=Y'+staffname);
            $('#assign_members').append('<div class="infoname" id="'+staffid+'">'+staffname+' <a onclick="deleteassign(this)" class="btn btn-danger btn-xs" style="margin: 0; padding: 0px; width:30px; float:right;" name="'+staffid+'">X</a></div>');
        }else{ //取銷指派
            $(this).prop('checked',false);
            //console.log(staffid+'=N');
            $('#assign_members').find('#'+staffid).remove();
        }
    });
});
//------20191226自動增長的TextArea
    function autogrow(textarea) {
        var adjustedHeight = textarea.clientHeight;

        adjustedHeight = Math.max(textarea.scrollHeight, adjustedHeight);
        if (adjustedHeight > textarea.clientHeight) {
            textarea.style.height = adjustedHeight + 'px';
        }

    }
    //------動增長的TextArea結束
</script>
<?php
$_SESSION["msg"] = "";
if($_SESSION["msg"] != ""){
    /*
    echo "<script>$(function(){ $('#message_box').html('".$_SESSION["msg"]."');
            $('#message_box').show(400, function(){
                $(this).delay(3000).fadeOut(800);
            }); })</script>";
    */
    echo "<script>$(function(){ alert('".$_SESSION["msg"]."') })</script>";
}


?>
</body>
</html>