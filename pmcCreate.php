<?php
include("php/CMUHconndata.php");
include("php/fun.php");
//取得人員名單
$stremp="SELECT e_number,cname FROM FA.Employee";
$queryemp=$pdo->query($stremp);
$empnumstr="SELECT COUNT(e_number) FROM FA.Employee";
$empnum=Current($pdo->query($empnumstr)->fetch());
while ($row=$queryemp->fetch()) {
    $emp[]=array(
        'id'=>$row['e_number'],
        'cname'=>$row['cname']
    );
}
//取得工程類別種類
$strpmcty="SELECT id,typename FROM FA.pmctype";
$querypmcty=$pdo->query($strpmcty);
$pmctynumstr="SELECT COUNT(id) FROM FA.pmctype";
$pmctynum=Current($pdo->query($pmctynumstr)->fetch());
while ($row=$querypmcty->fetch()) {
    $pmcty[]=array(
        'id'=>$row['id'],
        'typename'=>$row['typename']
    );
}

if (isset($_POST["pmcaction"])&&($_POST["pmcaction"]=="add")) {
    $rTime= date('Y-m-d H:i:s');//日期
    //$id=$_POST["pmcid"];//id
    $id=$_POST['pmchidden'];
    $empid=$_POST["pmccaseofficer"];//empoly
    $pmctype=$_POST["pmctype"];//pmctypeid
    $pmctypname=sql_database('typename','FA.pmctype','id',$pmctype);//pmctypename
    $title=$_POST["pmcname"];//工程名稱
    $pmcfield=$_POST["pmczone"];//地點
    //echo '我是id'.$id;
    $insertstr="INSERT INTO FA.pmc(id,e_number,category,title,createdOn,building,status) VALUES ('$id','$empid','$pmctypname','$title','$rTime','$pmcfield','W') ";
    $insertquery =$pdo->exec($insertstr);
    $pdo=null;
    
    header("Location: pmc.php");

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
    <form action="" method="POST" name="formpmc">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-10 col-sm-10 col-12 back container w-50 my-4 rounded">
                    <div id="tablepanel" class="choice_table rounded text-white">                        
                        <div id="insert" class="choice_table rounded text-white">
                            <!-- 副標題 -->
                            <h2 class="text-center mb-4">新增工程</h2>
                            <!-- 單號 -->
                            <P>工程編號: </P>
                            <Input class="form-control mb-3" type='text' name="pmcid" id="pmcid" Disabled>
                            <!--工程名稱 -->
                            <P>工程名稱: </P>
                            <textarea class="form-control" rows="5" name="pmcname" id="pmcname" required></textarea>                              
                            <!-- 工程類型 -->
                            <P>工程類型: </P>
                            <select class="form-control mb-3" type='text' name="pmctype" id="pmctype">
                                <option selected>請選擇工程類型</option>
                                <?php for ($i=0; $i < $pmctynum; $i++) { ?>
                                    <option value='<?php echo $pmcty[$i]["id"];?>'><?php echo $pmcty[$i]["typename"];?></option>
                                <?php };?>
                            </select>
                            <!-- 承辦人 -->
                            <p>指派承辦人：</p>
                            <select class="form-control mb-3" name="pmccaseofficer" id="pmccaseofficer">
                                <option selected>請選擇人員</option>
                                <?php for ($i=0; $i < $empnum; $i++) { ?>
                                    <option value='<?php echo $emp[$i]["id"];?>'><?php echo $emp[$i]["cname"];?></option>
                                <?php };?>
                            </select>
                            <!-- 選擇區域 -->
                            <p>工程範圍/區域：</p>
                            <input class="form-control mb-3" type='text' name="pmczone" id="pmczone" value='請輸入工程範圍/區域。ex.BCG棟或全院......等'>
                            <input type="hidden" name="pmcaction" value="add">
                            <input type="hidden" name="pmchidden">
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
    //$("#pmcid").html(id);
    document.formpmc.pmcid.value = id;
    document.formpmc.pmchidden.value = id;

} 
</script> 
</html>