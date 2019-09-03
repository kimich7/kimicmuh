<?php 
include("php/CMUHconndata.php");
include("php/fun.php");
include("page_searchfilter.php");
session_start();
$securitystr="SELECT * FROM FA.securityKind";
$security=$pdo->query($securitystr);
if (isset($_POST["action"])&&($_POST["action"]=="add")) {
    $anser=$_POST['security'];
    $num=count($anser);
    echo $num;
    var_dump($anser);
    echo $anser[0];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="" method="post"> 
    <table>
        <tr>
            <td align="center">權限</td><td align="center">
            <select name="security[]" id="security" multiple>
            <?PHP
            echo '<option value="">請選擇權限</option>';
            while ($row = $security->fetch()) {
                $test[]=array(
                'id'=>$row['id'],
                'name'=>$row['sName']
                );                
            }
            @$num=count($test);
            for ($i=0; $i < $num; $i++) { 
                echo "<option value=\"".$test[$i]['id']."\">".$test[$i]['name']."</option>";
            }
            ?>
        </tr>
    </table>
    <input name="action" type="hidden" value="add">
<input type='submit'>送出
</form>
</body>
</html>

    


