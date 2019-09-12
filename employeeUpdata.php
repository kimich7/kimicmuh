<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    if (isset($_POST["action"])&&($_POST["action"]=="updata")) {
        $sqldelete="UPDATE FA.Employee SET e_number=:e_number, cname=:cname, passcard=:passcard, title=:title, rank=:rank  WHERE e_number=:cID";
        $stmt = $pdo->prepare($sqldelete);
        if (isset($_POST['security'])&&$_POST['security']!='') {
                $level=$_POST['security'];                
                $num=count($level);
                $emp=$_POST["cID"];
                $leveldeletestr="DELETE FA.securityemp WHERE e_number='$emp'" ;
                $leveldelete=$pdo->exec($leveldeletestr);
                for ($i=0; $i < $num; $i++) {
                    $levelint[$i]=(int)$level[$i];
                    $levelstr="INSERT INTO FA.securityemp(e_number,sid)VALUES('$emp',$levelint[$i])";
                    $leveldata=$pdo->exec($levelstr);
                }
            }          
        $stmt->bindParam(':e_number',$_POST["uid"],PDO::PARAM_STR);
        $stmt->bindParam(':cname',$_POST["uname"],PDO::PARAM_STR);
        $stmt->bindParam(':passcard',$_POST["upw"],PDO::PARAM_STR);
        $stmt->bindParam(':title',$_POST["utitle"],PDO::PARAM_STR);
        $stmt->bindParam(':rank',$_POST["urank"],PDO::PARAM_INT);
        $stmt->bindParam(':cID',$_POST["cID"],PDO::PARAM_STR);      
        $stmt->execute();
        $pdo=null;
        header("Location: employee.php");
    }
    $id=$_GET["id"];
    $sqlselect="SELECT e_number,cname,passcard,title,rank FROM FA.Employee WHERE e_number='$id'";
    $result = $pdo->query($sqlselect)->fetch();
    
    //全部的類別權限
    $securitystr="SELECT * FROM FA.securityKind";
    $security=$pdo->query($securitystr);
    while ($row = $security->fetch()) {
        $level[]=array(
            'id'=>$row['id'],
            'sName'=>$row['sName']
        );
    }
    $levelnum=Count($level);
    
    //人員的權限有哪些
    $securityempstr="SELECT a.* FROM FA.securityKind as a LEFT JOIN FA.securityemp AS b ON a.id=b.sid WHERE b.e_number = '$id'";
    $securityemp=$pdo->query($securityempstr);
    while ($row = $securityemp->fetch()) {
        $levelemp[]=array(
            'id'=>$row['id'],
            'sName'=>$row['sName']
        );
    }
    
    if (isset($levelemp)&&$levelemp!='') {
            //交集
        foreach ($level as $value){
            foreach ($levelemp as $val){
                if($value==$val){
                $intersect[]=$value;
                }
            }
        }
        $pinum=count($intersect);
        
        //差集
        if ($pinum>0) {
            foreach($level as $k=>$v){
                if(in_array($v, $levelemp)){
                    unset($level[$k]);
                } 
            }
            $level=array_values($level); 
            $levelnun=count($level);
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
    <h2 align="center">工務所人員管理系統-更改資料</h2>
    <form action="" method="post" name="formUpd" id="formUpd">
        <?php
        echo '<table border="1" align="center" cellpadding="6" width="40%">';
            echo '<tr align="center">';
                echo '<th>欄位</th><th>資料</th>';
            echo '</tr>';
            echo '<tr>';
                echo "<td align='center'>員工編號</td><td align='center'><input type='text' name='uid' id='uid' Value=\"".$result["e_number"]."\"></td>";                                                                       
            echo '</tr>';
            echo '<tr>';
                echo "<td align='center'>員工姓名</td><td align='center'><input type='text' name='uname' id='uname' Value=\"".$result["cname"]."\"></td>";                    
            echo '</tr>';
            echo '<tr>';
                echo "<td align='center'>密碼</td><td align='center'><input type='text' name='upw' id='upw' Value=\"".$result["passcard"]."\"></td>";
            echo '</tr>';
            echo '<tr>';
                echo "<td align='center'>職稱</td><td align='center'><input type='text' name='utitle' id='utitle' Value=\"".$result["title"]."\"></td>";
            echo '</tr>';
            echo '<tr>';
                switch ($result["rank"]) {
                    case '1':
                        echo '<td align="center">權限</td><td align="center"><input type="radio" name="urank" id="urank" Value="1" checked>系統管理員';
                        echo '<input type="radio" name="urank" id="urank" Value="2">組長/主任';
                        echo '<input type="radio" name="urank" id="urank" Value="3">作業者</td>';
                        break;
                    case '2':
                        echo '<td align="center">權限</td><td align="center"><input type="radio" name="urank" id="urank" Value="1">系統管理員';
                        echo '<input type="radio" name="urank" id="urank" Value="2" checked>組長/主任';
                        echo '<input type="radio" name="urank" id="urank" Value="3">作業者</td>';
                        break;                    
                    default:
                        echo '<td align="center">權限</td><td align="center"><input type="radio" name="urank" id="urank" Value="1">系統管理員';
                        echo '<input type="radio" name="urank" id="urank" Value="2">組長/主任';
                        echo '<input type="radio" name="urank" id="urank" Value="3" checked>作業者</td>';
                        break;
                }
            echo '</tr>';
            
            echo '<tr>';
            echo '<td align="center">權限</td><td align="center">';
            echo '<select name="security[]" id="security" style="width:auto;" size=10 multiple>';
            if (isset($levelemp)&&$levelemp!='') {
                for ($i=0; $i < $pinum; $i++) {                    
                        //echo "<input type='checkbox' name='security[]' id='security' value=\"".$intersect[$i]['id']."\" checked>".$intersect[$i]['sName'];
                        echo "<option value=\"".$intersect[$i]['id']."\" selected>".$intersect[$i]['sName']."</option>";
                    } 
                for ($i=0; $i < $levelnun; $i++) { 
                    //echo "<input type='checkbox' name='security[]' id='security' value=\"".$level[$i]['id']."\">".$level[$i]['sName'];
                    echo "<option value=\"".$level[$i]['id']."\">".$level[$i]['sName']."</option>";
                }
            }else{
               for ($i=0; $i < $levelnum; $i++) { 
                   //echo "<input type='checkbox' name='security[]' id='security' value=\"".$level[$i]['id']."\">".$level[$i]['sName'];
                   echo "<option value=\"".$level[$i]['id']."\">".$level[$i]['sName']."</option>";
               }                 
            }                
            echo '</tr>';            
            echo '<tr>';
                echo '<td align="center" colspan="2">';
                echo '<input name="action" type="hidden" value="updata">';
                echo "<input name='cID' type='hidden' value=\"".$result["e_number"]."\">";
                echo '<input type="submit" name="button" id="btnUpdata" value="修改資料">';
                echo '<input type="reset" name="button2" value="重新填寫">';
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