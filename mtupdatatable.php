<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    
    //叫出資料
    $MasterID=$_GET["id"];
    $buildNo = $_GET["build"];
    $sysNo= $_GET["sys"];
    $rDate=$_GET["r_date"];
    $systemTable='FA.Water_System_Record_Detail';
    $equipTable='FA.Equipment_Check';
           
    $bname=sql_database('B_name','FA.Building','b_number',$buildNo);
    $sysname=sql_database('sysName','FA.Equipment_System_Group','sysID',$sysNo);
    $updata_qt=updata_num($systemTable,$MasterID);//迴圈數量
    $updatainfo=updata_select($systemTable,$MasterID);//我要的東西
    
    if (isset($_POST["action"])&&($_POST["action"]=="update")) {        
        for ($i=0; $i  <$updata_qt ; $i++) {
            $q=200+$i;
            $an=$i;
            if (isset($_POST["$an"])) {
                $ans=$_POST["$an"];
            } else {
                $ans=0;

            }
            if (isset($_POST["$q"])) {
                $rdID=$_POST["$q"];
            }else{
                break;
            }
            //$rdID=$_POST["$q"];
            //$ans=$_POST["$an"];
            $sql="UPDATE $systemTable SET remark=:remark , checkResult=:checkResult WHERE recordDetailID=:ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':remark',$_POST["remark"],PDO::PARAM_STR);
            $stmt->bindParam(':checkResult',$ans,PDO::PARAM_STR);
            $stmt->bindParam(':ID',$rdID,PDO::PARAM_INT);
            $stmt->execute();      
        }
        $pdo=null;
        header("Location: mtupdata.html");    
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
    <title>設備保養修改表單</title>
</head>

<body class="table_bg">
    <div class="container border border-info mt-5">
        <form action="" method="post" name="up">
            <h2 class="text-center font-weight-bold">中國醫藥大學附設醫院-<?= $bname ?>--<?= $sysname ?></h2>
            <!-- 班別/檢查者/日期欄 -->
            <div class="row my-3">
                <div class="col text-left">
                <p class="d-inline font-weight-bold">檢查者：</p>
                <p class="d-inline text-primary" name="reMumber"><?= '' ?></p>
                </div>
                <div class="col text-right">
                <p class="d-inline font-weight-bold">檢查日期：</p>
                <p class="d-inline text-primary"><?= $rDate ?></p>
                </div>
            </div>
            <div class="d-flex justify-content-end">
            <button class="btn btn-primary my-2 mr-2 d-none" id="tfbtn1">早班結果</button>
            <button class="btn btn-primary my-2 mr-2 d-none" id="tfbtn2">中班結果</button>
            <button class="btn btn-primary my-2 mr-2 d-none" id="tfbtn3">晚班結果</button>
            </div>
            <!-- 表格主體 -->
            <div class="row container">
                <!-- 檢查項目/參考值 -->
                <table class="table col-xl-4 col-lg-4 col-md-8 col-sm-12 col-12">
                    <thead>
                        <th>檢查項目</th>
                        <th>參考值</th>
                    </thead>
                    <tbody class="text-primary">
                    <?php 
                        $item=item("SELECT equipCheckID,ref FROM FA.Equipment_Check WHERE b_number='$buildNo' and sysID=$sysNo");
                        // $item=item("SELECT DISTINCT equipCheckID,ref FROM $systemTable WHERE recordID=$MasterID");
                       
                        // $num=num("SELECT COUNT(equipCheckID) FROM $systemTable WHERE recordID=$MasterID");
                        $num=num("SELECT COUNT(equipCheckID) FROM FA.Equipment_Check WHERE b_number='$buildNo' and sysID=$sysNo");
                        $checkName=array();
                        for ($a=0; $a < $num; $a++) {                            
                        $checkName[$a]=sql_database('equipCheckName',$equipTable,'equipCheckID',$item[$a]["equipCheckID"]);//找出對應equipCheckID的equipCheckName的名稱                        
                    //問號結尾
                    echo '<tr>';
                        //檢查項目<!--檢查項目-->
                        echo '<td>'.$checkName[$a].'</td>';
                        //參考值<!--參考值-->
                        echo '<td>'.$item[$a]["ref"].'</td>';
                    echo '</tr>';
                    //php開頭                           
                        }
                    echo '</tbody>';
                    echo '<tfoot class="text-primary">';
                        echo '<td>巡檢人員</td>';
                    echo '</tfoot>';
                echo '</table>';
                 
                //早班結果<!-- 早班結果 -->
                
                echo '<table id="tfresult1" class="table col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">';
                    echo '<thead>';
                        echo '<th>早班結果</th>';
                    echo '</thead>';
                    echo '<tbody class="text-primary">';    
                        $ans1=item("SELECT recordDetailID,equipCheckID,checkResult,r_member FROM $systemTable WHERE recordID=$MasterID AND shiftID=1");
                        $user_1=sql_database('cname','FA.Employee','e_number',$ans1[0]['r_member']);
                        for ($a=0; $a < $num; $a++) {
                            $q=$a+200;
                            $an=$a;//結果答案
                            for ($i=0; $i < $num ;) { 
                                if ($item[$a]["equipCheckID"] == $ans1[$i]["equipCheckID"]) {
                                    if ($item[$a]["ref"]=="V/X") {
                        echo '<tr>';
                            echo '<td>';
                            if($ans1[$i]["checkResult"]==1){
                                echo "<input type='radio' name=\"".$an."\" value=1 checked>合格";
                                echo "<input type='radio' name=\"".$an."\" value=0>不合格";
                            }else{
                                echo "<input type='radio' name=\"".$an."\" value=1>合格";
                                echo "<input type='radio' name=\"".$an."\" value=0 checked>不合格";
                            } 
                            echo '</td>'; 
                        echo '</tr>';
                                    } else {
                        echo '<tr>';
                            echo '<td>'."<input type='text' name=\"".$an."\" maxlength='20' value=\"".$ans1[$i]["checkResult"]."\"></td>";
                        echo '</tr>';
                                    } 
                                    echo "<input type='hidden' name=\"".$q."\" value=\"".$ans1[$i]["recordDetailID"]."\">";
                                break;                                            
                                } else {
                                    $i++;                                               
                                }
                            }
                        } 
                    echo '</tbody>';
                    echo '<tfoot class="text-primary">';
                        echo '<td>'.$user_1.'</td>';
                    echo '</tfoot>';
                echo '</table>';
                
                //中班結果<!-- 中班結果 -->
                echo '<table id="tfresult2" class="table col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">';
                    echo '<thead>';
                        echo '<th>中班結果</th>';
                    echo '</thead>';
                    echo '<tbody class="text-primary">';
                        $ans2=item("SELECT recordDetailID,equipCheckID,checkResult,r_member FROM $systemTable WHERE recordID=$MasterID AND shiftID=2");
                            for ($a=0; $a < $num; $a++) {
                                $an=$a+($num);
                                $q=$a+200+($num);
                                if (!isset($ans2[$a])) {
                                    echo '<tr>';
                                        echo "<td><input type=\"text\" name=\"$an\" maxlength=\"20\" value=\"未填寫\"></td>";
                                    echo '</tr>';
                                    $user_2='無人填寫';   
                                }else{
                                    $user_2=sql_database('cname','FA.Employee','e_number',$ans2[0]['r_member']);
                                    for ($i=0; $i < $num ;) {
                                        if ($item[$a]["equipCheckID"] == $ans2[$i]["equipCheckID"]) {
                                            if ($item[$a]["ref"]=="V/X") {
                            echo '<tr>';
                                echo '<td>';
                                if($ans2[$i]["checkResult"]==1){
                                    echo "<input type='radio' name=\"".$an."\" value=1 checked>合格";
                                    echo "<input type='radio' name=\"".$an."\" value=0>不合格";
                                }else{
                                    echo "<input type='radio' name=\"".$an."\" value=1>合格";
                                    echo "<input type='radio' name=\"".$an."\" value=0 checked>不合格";
                                }    
                                echo '</td>';
                           echo '</tr>';
                                            } else {
                            echo '<tr>';
                                echo '<td>'."<input type='text' name=\"".$an."\" maxlength='20' value=\"".$ans2[$i]["checkResult"]."\">".'</td>';
                            echo '</tr>';
                                            }
                                    echo "<input type='hidden' name=\"".$q."\" value=\"".$ans2[$i]["recordDetailID"]."\">";
                                        break;                                            
                                        } else {
                                    $i++;                                               
                                        }
                                    }
                                }
                            }
                    echo '</tbody>';
                    echo '<tfoot class="text-primary">';
                        echo '<td>'.$user_2.'</td>';
                    echo '</tfoot>';
                echo '</table>';
                 //晚班結果<!-- 晚班結果 -->    
                echo '<table id="tfresult3" class="table col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">';
                    echo '<thead>';
                        echo '<th>晚班結果</th>';
                    echo '</thead>';
                    echo '<tbody class="text-primary">';
                        $ans3=item("SELECT recordDetailID,equipCheckID,checkResult,r_member FROM $systemTable WHERE recordID=$MasterID AND shiftID=3");
                        for ($a=0; $a < $num; $a++) {
                            $an=$a+(($num)+($num));
                            $q=$a+200+(($num)+($num));
                            if (!isset($ans3[$a])) {
                                echo '<tr>';
                                    echo "<td><input type=\"text\" name=\"$an\" maxlength=\"20\" value=\"未填寫\"></td>";
                                echo '</tr>';
                                $user_3='無人填寫';   
                            }else{
                                $user_3=sql_database('cname','FA.Employee','e_number',$ans3[0]['r_member']);                                
                                for ($i=0; $i < $num ;) { 
                                    if ($item[$a]["equipCheckID"] == $ans3[$i]["equipCheckID"]) {
                                        if ($item[$a]["ref"]=="V/X") {
                            echo '<tr>';
                                echo '<td>';
                                if($ans3[$i]["checkResult"]==1){
                                    echo "<input type='radio' name=\"".$an."\" value=1 checked>合格";
                                    echo "<input type='radio' name=\"".$an."\" value=0>不合格";
                                }else{
                                    echo "<input type='radio' name=\"".$an."\" value=1>合格";
                                    echo "<input type='radio' name=\"".$an."\" value=0 checked>不合格";
                                }    
                                echo '</td>';
                           echo '</tr>';
                                        } else {
                        echo '<tr>';
                            echo '<td>'."<input type='text' name=\"".$an."\" maxlength='20' value=\"".$ans3[$i]["checkResult"]."\">".'</td>';
                        echo '</tr>';
                                        }
                            echo "<input type='hidden' name=\"".$q."\" value=\"".$ans3[$i]["recordDetailID"]."\">";
                                        break;                                            
                                    } else {
                                        $i++;                                               
                                    }
                                }
                            }
                        }    
                    
                    echo '</tbody>';
                    echo '<tfoot class="text-primary">';
                        echo '<td>'.$user_3.'</td>';
                    echo '</tfoot>';
                echo '</table>';
            echo '</div>';
            ?>     
            <!-- 備註欄 -->
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">備註：</span>
                </div>
                <textarea class="form-control" name="remark" aria-label="With textarea"><?= $updatainfo[0]["remark"] ?></textarea>
            </div>
            <!-- 傳送值到資料庫中 -->
            <input type="hidden" name="action" value="update">
            <!-- 送出鈕 -->
            <div class="d-flex justify-content-end">
                <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit">送出</button>
            </div>
        </form>
    </div>
</body>

</html>