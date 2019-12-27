<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    if (isset($_POST["action"])&&($_POST["action"]=="delete")) {
        $emp=$_POST["cID"];
        $sqldelete="DELETE FROM FA.Employee WHERE e_number=:ID";
        $stmt = $pdo->prepare($sqldelete);        
        $deletestr="SELECT COUNT(e_number) FROM FA.securityemp WHERE e_number='$emp'";
        $delete=Current($pdo->query($deletestr)->fetch());
        if ($delete!=0) {
            $emp=$_POST["cID"];
            $deleteempstr="DELETE FA.securityemp WHERE e_number='$emp'";
            $deleteemp=$pdo->exec($deleteempstr);
        }
        $stmt->bindParam(':ID',$_POST["cID"],PDO::PARAM_STR);       
        $stmt->execute();
        $pdo=null;
        header("Location: employee.php?filter=no");
    }
    $id=$_GET["id"];
    $sqlselect="SELECT e_number,cname,passcard,title,rank FROM FA.Employee WHERE e_number='$id'";
    $result = $pdo->query($sqlselect)->fetch();    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- 連結外部的CSS -->
    <!-- 新設 -->
    <link href="./css/jquery-ui.min.css" rel="stylesheet">
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
    <!-- 新設 -->
    <script src="./js/jquery-ui.min.js"></script>
    <title>工務所人員管理系統</title>
</head>

<body>
<!-- header網頁標題 -->
    <header>
        <div id="header"></div>
    </header>
    <!-- 表格內容 -->
    <h2 align="center">工務所人員管理系統-刪除資料</h2>
    <form action="" method="post" name="formDel" id="formDel">
        <?php
        echo '<table border="1" align="center" cellpadding="6">';
            echo '<tr align="center">';
                echo '<th>欄位</th><th>資料</th>';
            echo '</tr>';
            echo '<tr>';
                echo '<td align="center">員工編號</td><td align="center">'.$result["e_number"].'</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td align="center">員工姓名</td><td align="center">'.$result["cname"].'</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td align="center">職稱</td><td align="center">'.$result["title"].'</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<td align="center" colspan="2">';
                echo "<input name='cID' type='hidden' value=\"".$result["e_number"]."\">";
                echo '<input name="action" type="hidden" value="delete">';
                echo '<input type="submit" name="button" id="button" value="確定要刪除此筆資料嗎?">';
                echo '</td>';
            echo '</tr>';
        echo '</table>'
        ?>
    </form>
<!-- footer網頁尾頁 -->
    <footer>
        <div id="footer"></div>
    </footer>
</body>
</html>