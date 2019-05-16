<?php
include("php/CMUHconndata.php");
include("php/fun.php");
//總資料
$pmcstr="SELECT * FROM FA.pmc ";
$pmcQuery=$pdo->query($pmcstr);
$pmcAll=array();
$pmcnumstr="SELECT Count(id)FROM FA.pmc  ";
$pmcnum=Current($pdo->query($pmcnumstr)->fetch());
while ($row=$pmcQuery->fetch()) {
    $pmc[]=array(
        'id'=>$row["id"],//案件單號
        'e_number'=>$row["e_number"],//(承辦人/指派人員)id
        'category'=>$row["category"],//案件類型
        'title'=>$row["title"],//案件名稱(標題)
        'createdOn'=>$row["createdOn"],//交派日期
        'process'=>$row["process"],//流程進度日期及金額
        'content'=>$row["content"],//工程進度/異常說明
        'contract'=>$row["contract"],//發包日期.決包廠商.金額
        'building'=>$row["building"],//案件大樓
        'endon'=>$row["endon"],//結束日期
        'status'=>$row["status"]//狀態(W/P/H/F/D 尚未受理/處理中/部分完成/完成/作廢)
    );
}
//

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
    <div class="panel-heading">
            <h4>&nbsp&nbsp&nbsp&nbsp新增專案：<a class="btn btn-primary" href="pmcCreate.php" class="text-dark">新增</a></h4>    
    <form method="get" name="selectform" action="pmc.php">

    </form>
    </div>
    <table class="table table-striped table-bordered table-hover">
        <thead  class="thead-light">
            <tr>
            <th scope="col">單號</th>
            <th scope="col">承辦人</th>
            <th scope="col">工程名稱</th>
            <th scope="col">交辦日期</th>
            <th scope="col">棟別</th>
            <th scope="col">狀態</th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        <?php for ($i=0; $i <$pmcnum ; $i++) { 
            # code...
        }?>
        </tbody>

</table>

</body>
</html>