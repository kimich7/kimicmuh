<?php
include("php/CMUHconndata.php");
include("php/fun.php");
//登入者
session_start();
$checkuser=$_SESSION["login_member"];
$checkuserID=sql_database('e_number','FA.Employee','cname',$checkuser);
$str_member="SELECT * FROM FA.Employee WHERE e_number='$checkuserID'";
$member=$pdo->query($str_member)->fetch();
//總資料
//$ammtmstr="SELECT * FROM FA.MMT_AtableM ";
$ammtmstr="SELECT id,bid,fid,eid,rdate,datekind,tid,macNo,remark,emp,cemp,status FROM FA.MMT_AtableM ";
$ammtmQuery=$pdo->query($ammtmstr);
$ammtmAll=array();
$ammtmnumstr="SELECT Count(id)FROM FA.MMT_AtableM ";
$ammtmnum=Current($pdo->query($ammtmnumstr)->fetch());//數量

while ($row=$ammtmQuery->fetch()) {
    $ammtm[]=array(
        'id'=>$row["id"],//案件單號
        'tableNo'=>$row["tid"],//報表編號
        'macNo'=>$row["macNo"],//設備機台編號
        'bid'=>$row["bid"],//棟別id
        'fid'=>$row["fid"],//樓層id
        'eid'=>$row["eid"],//設備編號
        'rdate'=>$row["rdate"],//紀錄日期
        'datekind'=>$row["datekind"],//保養週期
        'remark'=>$row["remark"],//備註
        'emp'=>$row["emp"],//保養者員工編號
        'cemp'=>$row["cemp"],//工務室審核員工編號
        'status'=>$row["status"]//狀態(W/F/D 未審核/完成/作廢)
    );    
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
    <title>工程專案清單</title>
</head>
<body>
     <!-- header網頁標題 -->
    <header>
        <nav class="navbar navbar-expand-sm navbar-light bg-light">
            <a class="navbar-brand" href="index.html">
                <img src="./jpg/logo.png" alt="">
            </a>
        </nav>
    </header>
    <form action="mmtCreate_a_choice.php" method="post" name="mmtca">
        <div class="panel-heading">
            <input type="hidden" name="mmtsysa" value='A'>
                <h4>&nbsp&nbsp&nbsp&nbsp新增保養：<button type='submit' name="mmtsysabtn" class="btn btn-primary" >新增</button></h4> 
                <!-- <h4>&nbsp&nbsp&nbsp&nbsp新增保養：<a class="btn btn-primary" href="mmtCreate_a_choice.php" class="text-dark">新增</a></h4>    -->
        
        </div>
    </form>

    <table class="table table-striped table-bordered table-hover col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12 table-sm"><!--表格樣式：條紋行、帶框表格、可滑入行-->
        <thead  class="thead-light">
            <tr align="center">
            <th scope="col" width="15%">單號</th>
            <th scope="col" width="40%">標單名稱</th>
            <th scope="col" width="10%">保養日期</th>
            <th scope="col" width="8%">保養人</th>
            <th scope="col" width="8%">工務室</th>
            <th scope="col" width="8%">狀態</th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        <?php
            for ($i=0; $i <$ammtmnum ; $i++) {
                //顯示資料的轉換 
                $ammtmemp=sql_database('cname','FA.Employee','e_number',$ammtm[$i]['emp']);//員編轉人名
                $ammtmcemp=sql_database('cname','FA.Employee','e_number',$ammtm[$i]['cemp']);//審核員編轉人名
                $ammtmtabletitle=sql_database('tableName','FA.MMT_KIND','id',$ammtm[$i]['tableNo']);//表單編號轉名稱
                switch ($ammtm[$i]['status']) {//(W/F/D 未審核/完成/作廢)}
                    case 'W':
                        $ammtmstatus='未審核';
                        break;
                    case NULL:
                        $ammtmstatus='未審核';
                        break;
                    case '':
                        $ammtmstatus='未審核';
                        break;                
                    case 'F':
                        $ammtmstatus='審核完成';
                        break;
            }
            $macNo=$ammtm[$i]['macNo'];
        ?>
            <tr align="center">
                <th scope="row"><?= $ammtm[$i]['id']?></th><!--表單編號-->                
                <td><a href="mmtDetail_a.php?id=<?= $ammtm[$i]['id']?>"><?= $ammtmtabletitle.'('.$macNo.')' ?></a></td><!--表單名稱-->
                <td><?= $ammtm[$i]['rdate']?></td><!--保養日期-->  
                <td><?= $ammtmemp?></td><!--保養者-->                
                <td><?= $ammtmcemp?></td><!--審核者-->                
                <td><?= $ammtmstatus?></td><!--狀態-->
                <td><a href="mmtEdit_a.php?id=<?= $ammtm[$i]['id']?>">內容編輯與修改</a></td>
            </tr>
        <?php }?>
        </tbody>

</table>

</body>
</html>