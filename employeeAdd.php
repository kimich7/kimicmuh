<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    if (isset($_POST["action"])&&($_POST["action"]=="add")) {
        if (empty($_POST["aid"])) {
            echo "<script>";
            echo "alert('員工編號不可為空白')";
            echo "</script>";
            //header("Location: employeeAdd.php");
        } else {
            $id=$_POST["aid"];
            if (empty($_POST["cname"])) {
                $name=null;
            }else{
                $name=$_POST["cname"];
            }
            if (empty($_POST["cnpw"])) {
                $password=$id;
            }else{
                $password=$_POST["cnpw"];
            }
            if (empty($_POST["ctitle"])) {
                $title=null;
            }else{
                $title=$_POST["ctitle"];
            }
            if (empty($_POST["crank"])) {
                $rank=3;
            }else{
                $rank=(int)$_POST["crank"];
            }
            $insertstr="INSERT INTO FA.Employee(e_number,cname,passcard,title,rank)VALUES('$id','$name','$password','$title',$rank)";
            $insertdata=$pdo->exec($insertstr);
            $pdo=null;
            header("Location: employee.php");
        }
        

    }

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
    <h2 align="center">工務所人員管理系統-新增人員</h2>
    <p align="center"><a href="employee.php">回主畫面</a></p>
    <form action="" method="post" name="formAdd" id="formAdd">
    <?php
    echo '<table border="3" align="center" cellpadding="6" width="25%">';
        echo '<tr align="center">';
            echo '<th>欄位</th><th>資料</th>';
        echo '</tr>';
        echo '<tr>';
            echo '<td align="center">員工編號</td><td align="center"><input type="text" name="aid" id="aid"></td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td align="center">員工姓名</td><td align="center"><input type="text" name="cname" id="cname"></td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td align="center">密碼</td><td align="center"><input type="password" name="cnpw" id="cnpw"></td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td align="center">職稱</td><td align="center"><input type="text" name="ctitle" id="ctitle"></td>';
        echo '</tr>';
        echo '<tr>';
            echo '<td align="center">權限</td><td align="center"><input type="radio" name="crank" id="crank" Value="1">系統管理員';
            echo '<input type="radio" name="crank" id="crank" Value="2">組長/主任';
            echo '<input type="radio" name="crank" id="crank" Value="3">作業者</td>';
        echo '</tr>';
        echo '<tr>';
                echo '<td align="center" colspan="2">';
                echo '<input name="action" type="hidden" value="add">';
                echo '<input type="submit" name="button" id="btnAdd" value="新增資料">';
                echo '<input type="reset" name="button2" value="重新填寫">';
                echo '</td>';
        echo '</tr>';
    echo '</table><p></p>';
    ?>
    </form>
    
<!-- footer網頁尾頁 -->
    <footer>
        <div id="footer"></div>
    </footer>
</body>
</html>