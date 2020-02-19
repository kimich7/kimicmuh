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
    
    //該表單所點檢的項目
    $item=item("SELECT equipCheckID,ref,answerMode FROM FA.Equipment_Check WHERE b_number='$buildNo' and sysID=$sysNo ORDER BY floorID");
    //該表單所點檢項目的數量
    $num=num("SELECT COUNT(equipCheckID) FROM FA.Equipment_Check WHERE b_number='$buildNo' and sysID=$sysNo");
    $checkName=array();//用來放equipCheckName的陣列
    //早中晚班資料的撈取
    //$ans1=item("SELECT recordDetailID,equipCheckID,checkResult,r_member FROM $systemTable WHERE recordID=$MasterID AND shiftID=1");
    $ans1=item("SELECT B.recordDetailID,B.checkResult,B.r_member,B.remark,A.equipCheckID,A.ref,A.answerMode FROM FA.Equipment_Check AS A LEFT JOIN ( SELECT recordDetailID,equipCheckID,checkResult,r_member,shiftID,remark FROM $systemTable WHERE recordID=$MasterID AND shiftID=1)AS B ON A.equipCheckID = B.equipCheckID WHERE A.b_number='$buildNo' and A.sysID=$sysNo ORDER BY A.floorID,A.equipCheckID");
    $ans2=item("SELECT B.recordDetailID,B.checkResult,B.r_member,B.remark,A.equipCheckID,A.ref,A.answerMode FROM FA.Equipment_Check AS A LEFT JOIN ( SELECT recordDetailID,equipCheckID,checkResult,r_member,shiftID,remark FROM $systemTable WHERE recordID=$MasterID AND shiftID=2)AS B ON A.equipCheckID = B.equipCheckID WHERE A.b_number='$buildNo' and A.sysID=$sysNo ORDER BY A.floorID,A.equipCheckID");
    $ans3=item("SELECT B.recordDetailID,B.checkResult,B.r_member,B.remark,A.equipCheckID,A.ref,A.answerMode FROM FA.Equipment_Check AS A LEFT JOIN ( SELECT recordDetailID,equipCheckID,checkResult,r_member,shiftID,remark FROM $systemTable WHERE recordID=$MasterID AND shiftID=3)AS B ON A.equipCheckID = B.equipCheckID WHERE A.b_number='$buildNo' and A.sysID=$sysNo ORDER BY A.floorID,A.equipCheckID");
    $user_1No=$ans1[0]["r_member"];
    $user_2No=$ans2[0]["r_member"];
    $user_3No=$ans3[0]["r_member"];
    //備註內容整合
    $remark='';
    //早班備註
    if (isset($ans1[0]["remark"]) && $ans1[0]["remark"]!='') {
        $remark.='早班：'.$ans1[0]["remark"].'。'.chr(13);
    } else {
        $remark='';
    }
    //中班備註
    if (isset($ans2[0]["remark"]) && $ans2[0]["remark"]!='') {
        $remark.='中班：'.$ans2[0]["remark"].'。'.chr(13);
    } else {
        $remark.='';
    }
    //晚班備註
    if (isset($ans3[0]["remark"]) && $ans3[0]["remark"]!='') {
        $remark.='晚班：'.$ans3[0]["remark"].'。'.chr(13);
    } else {
        $remark.='';
    }    

    //巡檢人員
    if (!isset($user_1No)or $user_1No=='' ) {
        $user_1="該班無抄表紀錄";
    } else {
        $user_1=sql_database('cname','FA.Employee','e_number',$ans1[0]['r_member']);
    }
    if (!isset($user_2No)or $user_2No=='' ) {
        $user_2="該班無抄表紀錄";
    } else {
        $user_2=sql_database('cname','FA.Employee','e_number',$ans2[0]['r_member']);
    }
    if (!isset($user_3No)or $user_3No=='' ) {
        $user_3="該班無抄表紀錄";
    } else {
        $user_3=sql_database('cname','FA.Employee','e_number',$ans3[0]['r_member']);
    }

    //Update to SQL
    if (isset($_POST["action"])&&($_POST["action"]=="update")) {
        $ans_num=$_POST["num"];
        $selectNum=0;
        for ($selectNum=0; $selectNum < 3; $i++) { 
            for ($i=0; $i  <$ans_num ; $i++) {
                switch ($selectNum) {
                case 0:
                    $q=200+$i;
                    if (isset($_POST["$q"])) {
                        $rdID=$_POST["$q"];                        
                        $an=$i;
                        $type=400+$i;
                        $Module=$_POST["$type"];
                        if ($Module=='plural') {
                            $qu=$_POST["b"];
                            $ans=implode(",", $qu) ;           
                            // if ($ans=='1,1') {
                            //     $ans= '1';
                            // }elseif($ans=='2,2'){
                            //     $ans= '2';
                            // }else{
                            //     $ans= '1,2';
                            // }                
                        }elseif ($Module=='plural_1') {
                            $qc=$_POST["c"];
                            $ans=implode(",", $qc);
                            // if ($ans=='1,1') {
                            //     $ans= '1';
                            // }elseif($ans=='2,2'){
                            //     $ans= '2';
                            // }else{
                            //     $ans= '1,2';
                            // }
                        }else{
                            $ans=$_POST["$an"];
                        }
                    }
                    break;
                case 1:
                    
                    $q2=$i+1200+$i+($ans_num);
                    if (isset($_POST["$q2"])) {
                        $rdID=$_POST["$q2"];
                        $an2=$i+1000;
                        $type=$i+1400;
                        $Module=$_POST["$type"];
                        if ($Module=='plural') {
                            $qu=$_POST["d"];
                            $ans=implode(",", $qu) ;           
                            // if ($ans=='1,1') {
                            //     $ans= '1';
                            // }elseif($ans=='2,2'){
                            //     $ans= '2';
                            // }else{
                            //     $ans= '1,2';
                            // }                
                        }elseif ($Module=='plural_1') {
                            $qc=$_POST["e"];
                            $ans=implode(",", $qc);
                            // if ($ans=='1,1') {
                            //     $ans= '1';
                            // }elseif($ans=='2,2'){
                            //     $ans= '2';
                            // }else{
                            //     $ans= '1,2';
                            // }
                        }else{
                            $ans=$_POST["$an2"];
                        }                    
                }
                    break;
                case 2:
                    $an3=$i+2000;//晚班抄錶結果
                    $q3=$an3+200;//早班回傳RecordDetail ID的Name
                    $type3=$an3+400;//早班上傳參考值格式的Name

                    $q3=$i+2200;
                    if (isset($_POST["$q3"])) {
                        $rdID=$_POST["$q3"];
                        $an3=$i+2000;
                        $type=$i+2400;
                        $Module=$_POST["$type"];
                        if ($Module=='plural') {
                            $qu=$_POST["f"];
                            $ans=implode(",", $qu) ;           
                            // if ($ans=='1,1') {
                            //     $ans= '1';
                            // }elseif($ans=='2,2'){
                            //     $ans= '2';
                            // }else{
                            //     $ans= '1,2';
                            // }                
                        }elseif ($Module=='plural_1') {
                            $qc=$_POST["g"];
                            $ans=implode(",", $qc);
                            // if ($ans=='1,1') {
                            //     $ans= '1';
                            // }elseif($ans=='2,2'){
                            //     $ans= '2';
                            // }else{
                            //     $ans= '1,2';
                            // }
                        }else{
                            $ans=$_POST["$an3"];
                        }                          
                    }
                    break;  
                }
                $sql="UPDATE $systemTable SET remark=:remark , checkResult=:checkResult WHERE recordDetailID=:ID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':remark',$_POST["remark"],PDO::PARAM_STR);
                $stmt->bindParam(':checkResult',$ans,PDO::PARAM_STR);
                $stmt->bindParam(':ID',$rdID,PDO::PARAM_INT);
                $stmt->execute();                    
            }
        $selectNum++;
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
                <table class="table">
                    <thead>
                        <th>檢查項目</th>
                        <th>參考值</th>
                        <th>早班結果</th>
                        <th>中班結果</th>
                        <th>晚班結果</th>
                    </thead>
                    <tbody class="text-primary">
                <?php
                    for ($a=0; $a < $num; $a++) {    //拿該表單所點檢項目的數量做迴圈用num
                        $checkName[$a]=sql_database('equipCheckName',$equipTable,'equipCheckID',$ans1[$a]["equipCheckID"]);//找出對應equipCheckID的equipCheckName的名稱                        
                    //問號結尾
                    echo '<tr>';
                        //檢查項目<!--檢查項目-->
                        echo '<td><h10>'.$checkName[$a].'</h10></td>';
                        //參考值<!--參考值-->
                        echo '<td><h10>'.$ans1[$a]["ref"].'</h10></td>';
                            $an=$a;//早班抄表結果
                            $q=$a+200;//早班回傳RecordDetail ID的Name
                            $type=$a+400;//早班上傳參考值格式的Name

                            $an2=$a+1000;//中班抄錶結果
                            $q2=$an2+200;//早班回傳RecordDetail ID的Name
                            $type2=$an2+400;//早班上傳參考值格式的Name

                            $an3=$a+2000;//晚班抄錶結果
                            $q3=$an3+200;//早班回傳RecordDetail ID的Name
                            $type3=$an3+400;//早班上傳參考值格式的Name
                        //早班結果    
                            $answerMode=$ans1[$a]["answerMode"];
                            if (is_null($ans1[$a]["recordDetailID"])) {                                    
                                echo "<td><input type=\"text\" name=\"$an\" maxlength=\"20\" value=\"無該筆紀錄，此紀錄無法修改\" DISABLED></td>";                                       
                            }else{
                                switch ($answerMode) {
                                        case 'choiceTF':
                                            echo '<td>';
                                                if( $ans1[$a]["checkResult"]=="true"){
                                                    echo "<input type='radio' name=\"".$an."\" value='true' checked >合格";
                                                    echo "<input type='radio' name=\"".$an."\" value='false'>不合格";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an."\" value='true' >合格";
                                                    echo "<input type='radio' name=\"".$an."\" value='false' checked >不合格";
                                                }                                
                                            echo '</td>';
                                            break;
                                        case 'choiceHA':
                                            echo '<td>';
                                                if( $ans1[$a]["checkResult"]=="handle"){
                                                    echo "<input type='radio' name=\"".$an."\" value='handle' checked>手動";
                                                    echo "<input type='radio' name=\"".$an."\" value='auto'>自動";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an."\" value='handle'>手動";
                                                    echo "<input type='radio' name=\"".$an."\" value='auto' checked>自動";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'choiceFN':
                                            echo '<td>';
                                                if( $ans1[$a]["checkResult"]=="OFF"){
                                                    echo "<input type='radio' name=\"".$an."\" value='OFF' checked>OFF";
                                                    echo "<input type='radio' name=\"".$an."\" value='ON'>ON";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an."\" value='OFF'>OFF";
                                                    echo "<input type='radio' name=\"".$an."\" value='ON' checked>ON";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'choiceRL':
                                            echo '<td>';
                                                if( $ans1[$a]["checkResult"]=="remote"){
                                                    echo "<input type='radio' name=\"".$an."\" value='remote' checked>遠端";
                                                    echo "<input type='radio' name=\"".$an."\" value='local'>本地";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an."\" value='remote'>遠端";
                                                    echo "<input type='radio' name=\"".$an."\" value='local' checked>本地";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'choiceS12':
                                            echo '<td>';
                                                if( $ans1[$a]["checkResult"]=="S1"){
                                                    echo "<input type='radio' name=\"".$an."\" value='S1' checked>S1";
                                                    echo "<input type='radio' name=\"".$an."\" value='S2'>S2";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an."\" value='S1'>S1";
                                                    echo "<input type='radio' name=\"".$an."\" value='S2' checked>S2";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'choiceRG':
                                            echo '<td>';
                                                if( $ans1[$a]["checkResult"]=="red"){
                                                    echo "<input type='radio' name=\"".$an."\" value='red' checked>紅";
                                                    echo "<input type='radio' name=\"".$an."\" value='green'>綠";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an."\" value='red'>紅";
                                                    echo "<input type='radio' name=\"".$an."\" value='green' checked>綠";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'plural':
                                            echo '<td>';
                                            if( $ans1[$a]["checkResult"]=="1"){ ?>
                                                <input type='checkbox' name="b[]" value='1' checked>1&nbsp&nbsp
                                                <input type='checkbox' name="b[]" value='2'>2
                                            <?php } elseif($ans1[$a]["checkResult"]=="2") { ?>
                                                <input type='checkbox' name="b[]" value='1'>1&nbsp&nbsp
                                                <input type='checkbox' name="b[]" value='2' checked>2
                                            <?php } elseif($ans1[$a]["checkResult"]=="1,2") { ?>
                                                <input type='checkbox' name="b[]" value='1' checked>1&nbsp&nbsp
                                                <input type='checkbox' name="b[]" value='2' checked>2
                                            <?php } else { ?>
                                                <input type='checkbox' name="b[]" value='1'>1&nbsp&nbsp
                                                <input type='checkbox' name="b[]" value='2'>2                                
                                            <?php }                            
                                            echo '</td>';
                                            break;
                                        case 'plural_1':
                                            echo '<td>';
                                            if( $ans1[$a]["checkResult"]=="1"){ ?>
                                                <input type='checkbox' name="c[]" value='1' checked>1&nbsp&nbsp
                                                <input type='checkbox' name="c[]" value='2'>2
                                            <?php } elseif($ans1[$a]["checkResult"]=="2") { ?>
                                                <input type='checkbox' name="c[]" value='1'>1&nbsp&nbsp
                                                <input type='checkbox' name="c[]" value='2' checked>2
                                            <?php } elseif($ans1[$a]["checkResult"]=="1,2") { ?>
                                                <input type='checkbox' name="c[]" value='1' checked>1&nbsp&nbsp
                                                <input type='checkbox' name="c[]" value='2' checked>2
                                            <?php } else { ?>
                                                <input type='checkbox' name="c[]" value='1'>1&nbsp&nbsp
                                                <input type='checkbox' name="c[]" value='2'>2                                
                                            <?php }                            
                                            echo '</td>';
                                            break;
                                        default:
                                            echo '<td>'."<input type='text' name=\"".$an."\" maxlength='20' value=\"".$ans1[$a]["checkResult"]."\"></td>";
                                            break;
                                    }
                                echo "<input type='hidden' name=\"".$q."\" value=\"".$ans1[$a]["recordDetailID"]."\">";
                                echo "<input type='hidden' name=\"".$type."\" value=\"".$answerMode."\">";
                            }
                        
                        //中班結果<!-- 中班結果 -->
                            if (is_null($ans2[$a]["recordDetailID"])) {
                                echo "<td><input type=\"text\" name=\"$an2\" maxlength=\"20\" value=\"無該筆紀錄，此紀錄無法修改\" DISABLED></td>";                                          
                            }else{
                                switch ($answerMode) {
                                        case 'choiceTF':
                                            echo '<td>';
                                                if( $ans2[$a]["checkResult"]=="true"){
                                                    echo "<input type='radio' name=\"".$an2."\" value='true' checked >合格";
                                                    echo "<input type='radio' name=\"".$an2."\" value='false'>不合格";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an2."\" value='true' >合格";
                                                    echo "<input type='radio' name=\"".$an2."\" value='false' checked >不合格";
                                                }                                
                                            echo '</td>';
                                            break;
                                        case 'choiceHA':
                                            echo '<td>';
                                                if( $ans2[$a]["checkResult"]=="handle"){
                                                    echo "<input type='radio' name=\"".$an2."\" value='handle' checked>手動";
                                                    echo "<input type='radio' name=\"".$an2."\" value='auto'>自動";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an2."\" value='handle'>手動";
                                                    echo "<input type='radio' name=\"".$an2."\" value='auto' checked>自動";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'choiceFN':
                                            echo '<td>';
                                                if( $ans2[$a]["checkResult"]=="OFF"){
                                                    echo "<input type='radio' name=\"".$an2."\" value='OFF' checked>OFF";
                                                    echo "<input type='radio' name=\"".$an2."\" value='ON'>ON";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an2."\" value='OFF'>OFF";
                                                    echo "<input type='radio' name=\"".$an2."\" value='ON' checked>ON";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'choiceRL':
                                            echo '<td>';
                                                if( $ans2[$a]["checkResult"]=="remote"){
                                                    echo "<input type='radio' name=\"".$an2."\" value='remote' checked>遠端";
                                                    echo "<input type='radio' name=\"".$an2."\" value='local'>本地";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an2."\" value='remote'>遠端";
                                                    echo "<input type='radio' name=\"".$an2."\" value='local' checked>本地";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'choiceS12':
                                            echo '<td>';
                                                if( $ans2[$a]["checkResult"]=="S1"){
                                                    echo "<input type='radio' name=\"".$an2."\" value='S1' checked>S1";
                                                    echo "<input type='radio' name=\"".$an2."\" value='S2'>S2";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an2."\" value='S1'>S1";
                                                    echo "<input type='radio' name=\"".$an2."\" value='S2' checked>S2";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'choiceRG':
                                            echo '<td>';
                                                if( $ans2[$a]["checkResult"]=="red"){
                                                    echo "<input type='radio' name=\"".$an2."\" value='red' checked>紅";
                                                    echo "<input type='radio' name=\"".$an2."\" value='green'>綠";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an2."\" value='red'>紅";
                                                    echo "<input type='radio' name=\"".$an2."\" value='green' checked>綠";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'plural':
                                            echo '<td>';
                                            if( $ans2[$a]["checkResult"]=="1"){ ?>
                                                <input type='checkbox' name="d[]" value='1' checked>1&nbsp&nbsp
                                                <input type='checkbox' name="d[]" value='2'>2
                                            <?php } elseif($ans2[$a]["checkResult"]=="2") { ?>
                                                <input type='checkbox' name="d[]" value='1'>1&nbsp&nbsp
                                                <input type='checkbox' name="d[]" value='2' checked>2
                                            <?php } elseif($ans2[$a]["checkResult"]=="1,2") { ?>
                                                <input type='checkbox' name="d[]" value='1' checked>1&nbsp&nbsp
                                                <input type='checkbox' name="d[]" value='2' checked>2
                                            <?php } else { ?>
                                                <input type='checkbox' name="d[]" value='1'>1&nbsp&nbsp
                                                <input type='checkbox' name="d[]" value='2'>2                                
                                            <?php }                            
                                            echo '</td>';
                                            break;
                                        case 'plural_1':
                                            echo '<td>';
                                            if( $ans2[$a]["checkResult"]=="1"){ ?>
                                                <input type='checkbox' name="e[]" value='1' checked>1&nbsp&nbsp
                                                <input type='checkbox' name="e[]" value='2'>2
                                            <?php } elseif($ans2[$a]["checkResult"]=="2") { ?>
                                                <input type='checkbox' name="e[]" value='1'>1&nbsp&nbsp
                                                <input type='checkbox' name="e[]" value='2' checked>2
                                            <?php } elseif($ans2[$a]["checkResult"]=="1,2") { ?>
                                                <input type='checkbox' name="e[]" value='1' checked>1&nbsp&nbsp
                                                <input type='checkbox' name="e[]" value='2' checked>2
                                            <?php } else { ?>
                                                <input type='checkbox' name="e[]" value='1'>1&nbsp&nbsp
                                                <input type='checkbox' name="e[]" value='2'>2                                
                                            <?php }                            
                                            echo '</td>';
                                            break;
                                        default:
                                            echo '<td>'."<input type='text' name=\"".$an2."\" maxlength='20' value=\"".$ans2[$a]["checkResult"]."\"></td>";
                                            break;
                                    }
                                echo "<input type='hidden' name=\"".$q2."\" value=\"".$ans2[$a]["recordDetailID"]."\">";
                                echo "<input type='hidden' name=\"".$type2."\" value=\"".$answerMode."\">";
                            }        
                        //晚班結果<!-- 晚班結果 -->
                            if (is_null($ans3[$a]["recordDetailID"])) {
                                echo "<td><input type=\"text\" name=\"$an3\" maxlength=\"20\" value=\"無該筆紀錄，此紀錄無法修改\" Disabled></td>";                               
                            }else{
                                switch ($answerMode) {
                                    case 'choiceTF':
                                        echo '<td>';
                                            if( $ans3[$a]["checkResult"]=="true"){
                                                echo "<input type='radio' name=\"".$an3."\" value='true' checked >合格";
                                                echo "<input type='radio' name=\"".$an3."\" value='false'>不合格";
                                            } else {
                                                echo "<input type='radio' name=\"".$an3."\" value='true' >合格";
                                                echo "<input type='radio' name=\"".$an3."\" value='false' checked >不合格";
                                            }                                
                                        echo '</td>';
                                        break;
                                    case 'choiceHA':
                                        echo '<td>';
                                            if( $ans3[$a]["checkResult"]=="handle"){
                                                echo "<input type='radio' name=\"".$an3."\" value='handle' checked>手動";
                                                echo "<input type='radio' name=\"".$an3."\" value='auto'>自動";
                                            } else {
                                                echo "<input type='radio' name=\"".$an3."\" value='handle'>手動";
                                                echo "<input type='radio' name=\"".$an3."\" value='auto' checked>自動";
                                            }
                                        echo '</td>';
                                        break;
                                    case 'choiceFN':
                                        echo '<td>';
                                            if( $ans3[$a]["checkResult"]=="OFF"){
                                                echo "<input type='radio' name=\"".$an3."\" value='OFF' checked>OFF";
                                                echo "<input type='radio' name=\"".$an3."\" value='ON'>ON";
                                            } else {
                                                echo "<input type='radio' name=\"".$an3."\" value='OFF'>OFF";
                                                echo "<input type='radio' name=\"".$an3."\" value='ON' checked>ON";
                                            }
                                        echo '</td>';
                                        break;
                                    case 'choiceRL':
                                        echo '<td>';
                                            if( $ans3[$a]["checkResult"]=="remote"){
                                                echo "<input type='radio' name=\"".$an3."\" value='remote' checked>遠端";
                                                echo "<input type='radio' name=\"".$an3."\" value='local'>本地";
                                            } else {
                                                echo "<input type='radio' name=\"".$an3."\" value='remote'>遠端";
                                                echo "<input type='radio' name=\"".$an3."\" value='local' checked>本地";
                                            }
                                        echo '</td>';
                                        break;
                                    case 'choiceS12':
                                        echo '<td>';
                                            if( $ans3[$a]["checkResult"]=="S1"){
                                                echo "<input type='radio' name=\"".$an3."\" value='S1' checked>S1";
                                                echo "<input type='radio' name=\"".$an3."\" value='S2'>S2";
                                            } else {
                                                echo "<input type='radio' name=\"".$an3."\" value='S1'>S1";
                                                echo "<input type='radio' name=\"".$an3."\" value='S2' checked>S2";
                                            }
                                        echo '</td>';
                                        break;
                                    case 'choiceRG':
                                        echo '<td>';
                                            if( $ans3[$a]["checkResult"]=="red"){
                                                echo "<input type='radio' name=\"".$an3."\" value='red' checked>紅";
                                                echo "<input type='radio' name=\"".$an3."\" value='green'>綠";
                                            } else {
                                                echo "<input type='radio' name=\"".$an3."\" value='red'>紅";
                                                echo "<input type='radio' name=\"".$an3."\" value='green' checked>綠";
                                            }
                                        echo '</td>';
                                        break;
                                    case 'plural':
                                        echo '<td>';
                                        if( $ans3[$a]["checkResult"]=="1"){ ?>
                                            <input type='checkbox' name="f[]" value='1' checked>1&nbsp&nbsp
                                            <input type='checkbox' name="f[]" value='2'>2
                                        <?php } elseif($ans3[$a]["checkResult"]=="2") { ?>
                                            <input type='checkbox' name="f[]" value='1'>1&nbsp&nbsp
                                            <input type='checkbox' name="f[]" value='2' checked>2
                                        <?php } elseif($ans3[$a]["checkResult"]=="1,2") { ?>
                                            <input type='checkbox' name="f[]" value='1' checked>1&nbsp&nbsp
                                            <input type='checkbox' name="f[]" value='2' checked>2
                                        <?php } else { ?>
                                            <input type='checkbox' name="f[]" value='1'>1&nbsp&nbsp
                                            <input type='checkbox' name="f[]" value='2'>2                                
                                        <?php }                            
                                        echo '</td>';
                                        break;
                                    case 'plural_1':
                                        echo '<td>';
                                        if( $ans3[$a]["checkResult"]=="1"){ ?>
                                            <input type='checkbox' name="g[]" value='1' checked>1&nbsp&nbsp
                                            <input type='checkbox' name="g[]" value='2'>2
                                        <?php } elseif($ans3[$a]["checkResult"]=="2") { ?>
                                            <input type='checkbox' name="g[]" value='1'>1&nbsp&nbsp
                                            <input type='checkbox' name="g[]" value='2' checked>2
                                        <?php } elseif($ans3[$a]["checkResult"]=="1,2") { ?>
                                            <input type='checkbox' name="g[]" value='1' checked>1&nbsp&nbsp
                                            <input type='checkbox' name="g[]" value='2' checked>2
                                        <?php } else { ?>
                                            <input type='checkbox' name="g[]" value='1'>1&nbsp&nbsp
                                            <input type='checkbox' name="g[]" value='2'>2                                
                                        <?php }                            
                                        echo '</td>';
                                        break;
                                    default:
                                        echo '<td>'."<input type='text' name=\"".$an3."\" maxlength='20' value=\"".$ans3[$a]["checkResult"]."\"></td>";
                                        break;
                                }                                                            
                            echo "<input type='hidden' name=\"".$q3."\" value=\"".$ans3[$a]["recordDetailID"]."\">";
                            echo "<input type='hidden' name=\"".$type3."\" value=\"".$answerMode."\">";
                            }
                        echo '</tr>';
                    }                            
                        echo '</tbody>';
                        echo '<tfoot class="text-primary">';
                            echo '<td>巡檢人員</td>';
                            echo '<td></td>';
                            echo '<td id="tfresult1">'.$user_1.'</td>';
                            echo '<td id="tfresult2">'.$user_2.'</td>';
                            echo '<td id="tfresult3">'.$user_3.'</td>';
                        echo '</tfoot>';                    
                echo '</table>';
            echo '</div>';
            echo "<input type='hidden' name='num' value=\"".$num."\">";
            ?>     
            <!-- 備註欄 -->
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">備註：</span>
                </div>                
                <textarea class="form-control" name="remark" rows="5" aria-label="With textarea" ><?= $remark ?></textarea>
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