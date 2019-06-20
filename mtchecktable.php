<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    session_start();
    $checkuser=$_SESSION["login_member"];
    $checkuserID=sql_database('e_number','FA.Employee','cname',$checkuser);
    //叫出資料
    $MasterID=$_GET["id"];
    $MasterID=str_replace("\"", "", $MasterID);    
    $buildNo = $_GET["build"];
    $buildNo=str_replace("\"", "", $buildNo);
    $sysNo= $_GET["sysID"];
    $sysNo=str_replace("\"", "", $sysNo);
    $rDate=$_GET["r_date"];
    $rDate=str_replace("\"", "", $rDate);
    $sysMaster='FA.Water_System_Record_Master';
    $systemTable='FA.Water_System_Record_Detail';
    $equipTable='FA.Equipment_Check';

    $userRank=rank($checkuserID);//登入者的等級
    if ($userRank<3) {//主管登錄
        $tatus=rankStatus($MasterID);
            $eeID=$tatus['employeeID'];
        $eeName=sql_database('cname','FA.Employee','e_number',$eeID);        
    } else {//檢查者登錄
        $managerID = '';        
    }
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
    $ans1=item("SELECT B.recordDetailID,B.checkResult,B.r_member FROM FA.Equipment_Check AS A LEFT JOIN ( SELECT recordDetailID,equipCheckID,checkResult,r_member,shiftID FROM $systemTable WHERE recordID=$MasterID AND shiftID=1)AS B ON A.equipCheckID = B.equipCheckID WHERE A.b_number='$buildNo' and A.sysID=$sysNo ORDER BY A.floorID");
    $ans2=item("SELECT B.recordDetailID,B.checkResult,B.r_member FROM FA.Equipment_Check AS A LEFT JOIN ( SELECT recordDetailID,equipCheckID,checkResult,r_member,shiftID FROM $systemTable WHERE recordID=$MasterID AND shiftID=2)AS B ON A.equipCheckID = B.equipCheckID WHERE A.b_number='$buildNo' and A.sysID=$sysNo ORDER BY A.floorID");
    $ans3=item("SELECT B.recordDetailID,B.checkResult,B.r_member FROM FA.Equipment_Check AS A LEFT JOIN ( SELECT recordDetailID,equipCheckID,checkResult,r_member,shiftID FROM $systemTable WHERE recordID=$MasterID AND shiftID=3)AS B ON A.equipCheckID = B.equipCheckID WHERE A.b_number='$buildNo' and A.sysID=$sysNo ORDER BY A.floorID");


    if (isset($_POST["action"])&&($_POST["action"]=="update")) {
        $recordID=$_POST['MasterID'];        
        if ($userRank<3) {//主管登錄
            if (isset($_POST["mgrCheck"])) {
                $check_manager=1;           
            }
            $sql="UPDATE $sysMaster SET managerID=:managerID , check_manager=:check_manager WHERE recordID=:ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':managerID',$checkuserID,PDO::PARAM_STR);
            $stmt->bindParam(':check_manager',$check_manager,PDO::PARAM_STR);
        } else {//檢查者登錄
            if (isset($_POST["eeCheck"])) {
                $check_employee=1;           
            }
            $sql="UPDATE $sysMaster SET r_member=:r_member , check_number=:check_number WHERE recordID=:ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':r_member',$checkuserID,PDO::PARAM_STR);
            $stmt->bindParam(':check_number',$check_employee,PDO::PARAM_STR);
        }
            $stmt->bindParam(':ID',$recordID,PDO::PARAM_INT);
            $stmt->execute();      
        $pdo=null;
        header("Location: mtlistcheck.php");    
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
                <div class="col text-right">
                <p class="d-inline font-weight-bold">巡檢日期：</p>
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
                        for ($a=0; $a < $num; $a++) {    //拿該表單所點檢項目的數量做迴圈用num                    
                        $checkName[$a]=sql_database('equipCheckName',$equipTable,'equipCheckID',$item[$a]["equipCheckID"]);//找出對應equipCheckID的equipCheckName的名稱                        
                    //問號結尾
                    echo '<tr>';
                        //檢查項目<!--檢查項目-->
                        echo '<td><h10>'.$checkName[$a].'</h10></td>';
                        //參考值<!--參考值-->
                        echo '<td><h10>'.$item[$a]["ref"].'</h10></td>';
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
                    $user_1=""; 
                        for ($a=0; $a < $num; $a++) {
                            $q=$a+200;
                            $an=$a;//結果答案
                            $type=$a+400;
                            $answerMode=$item[$a]["answerMode"];
                            if (is_null($ans1[$a]["recordDetailID"])) {
                                echo '<tr>';
                                    echo "<td><input type=\"text\" name=\"$an\" maxlength=\"20\" value=\"無該筆紀錄，此紀錄無法修改\" DISABLED></td>";
                                echo '</tr>';
                            }else{
                                if ($user_1=="") {
                                    $user_1=sql_database('cname','FA.Employee','e_number',$ans1[$a]['r_member']);
                                }
                                echo '<tr>';
                                    switch ($answerMode) {
                                        case 'choiceTF':
                                            echo '<td>';
                                                if( $ans1[$a]["checkResult"]=="true"){
                                                    echo "<input type='radio' name=\"".$an."\" value='true' checked DISABLED>合格";
                                                    echo "<input type='radio' name=\"".$an."\" value='false'DISABLED>不合格";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an."\" value='true' DISABLED>合格";
                                                    echo "<input type='radio' name=\"".$an."\" value='false' checked DISABLED>不合格";
                                                }                                
                                            echo '</td>';
                                            break;
                                        case 'choiceHA':
                                            echo '<td>';
                                                if( $ans1[$a]["checkResult"]=="handle"){
                                                    echo "<input type='radio' name=\"".$an."\" value='handle' checked DISABLED>手動";
                                                    echo "<input type='radio' name=\"".$an."\" value='auto' DISABLED>自動";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an."\" value='handle' DISABLED>手動";
                                                    echo "<input type='radio' name=\"".$an."\" value='auto' checked DISABLED>自動";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'choiceFN':
                                            echo '<td>';
                                                if( $ans1[$a]["checkResult"]=="OFF"){
                                                    echo "<input type='radio' name=\"".$an."\" value='OFF' checked DISABLED>OFF";
                                                    echo "<input type='radio' name=\"".$an."\" value='ON' DISABLED>ON";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an."\" value='OFF' DISABLED>OFF";
                                                    echo "<input type='radio' name=\"".$an."\" value='ON' checked DISABLED>ON";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'choiceRL':
                                            echo '<td>';
                                                if( $ans1[$a]["checkResult"]=="remote"){
                                                    echo "<input type='radio' name=\"".$an."\" value='remote' checked DISABLED>遠端";
                                                    echo "<input type='radio' name=\"".$an."\" value='local' DISABLED>本地";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an."\" value='remote' DISABLED>遠端";
                                                    echo "<input type='radio' name=\"".$an."\" value='local' checked DISABLED>本地";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'choiceS12':
                                            echo '<td>';
                                                if( $ans1[$a]["checkResult"]=="S1"){
                                                    echo "<input type='radio' name=\"".$an."\" value='S1' checked DISABLED>S1";
                                                    echo "<input type='radio' name=\"".$an."\" value='S2' DISABLED>S2";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an."\" value='S1' DISABLED>S1";
                                                    echo "<input type='radio' name=\"".$an."\" value='S2' DISABLED checked>S2";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'choiceRG':
                                            echo '<td>';
                                                if( $ans1[$a]["checkResult"]=="red"){
                                                    echo "<input type='radio' name=\"".$an."\" value='red' DISABLED checked>紅";
                                                    echo "<input type='radio' name=\"".$an."\" value='green' DISABLED>綠";
                                                } else {
                                                    echo "<input type='radio' name=\"".$an."\" value='red' DISABLED>紅";
                                                    echo "<input type='radio' name=\"".$an."\" value='green' checked DISABLED>綠";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'plural':
                                            echo '<td>';
                                            if( $ans1[$a]["checkResult"]=="1"){ ?>
                                                <input type='checkbox' name="b[]" value='1' checked DISABLED>1&nbsp&nbsp
                                                <input type='checkbox' name="b[]" value='2' DISABLED>2
                                            <?php } elseif($ans1[$a]["checkResult"]=="2") { ?>
                                                <input type='checkbox' name="b[]" value='1' DISABLED>1&nbsp&nbsp
                                                <input type='checkbox' name="b[]" value='2' checked DISABLED>2
                                            <?php } elseif($ans1[$a]["checkResult"]=="1,2") { ?>
                                                <input type='checkbox' name="b[]" value='1' checked DISABLED>1&nbsp&nbsp
                                                <input type='checkbox' name="b[]" value='2' checked DISABLED>2
                                            <?php } else { ?>
                                                <input type='checkbox' name="b[]" value='1' DISABLED>1&nbsp&nbsp
                                                <input type='checkbox' name="b[]" value='2' DISABLED>2                                
                                            <?php }                            
                                            echo '</td>';
                                            break;
                                        case 'plural_1':
                                            echo '<td>';
                                            if( $ans1[$a]["checkResult"]=="1"){ ?>
                                                <input type='checkbox' name="c[]" value='1' checked DISABLED>1&nbsp&nbsp
                                                <input type='checkbox' name="c[]" value='2' DISABLED>2
                                            <?php } elseif($ans1[$a]["checkResult"]=="2") { ?>
                                                <input type='checkbox' name="c[]" value='1' DISABLED>1&nbsp&nbsp
                                                <input type='checkbox' name="c[]" value='2' checked DISABLED>2
                                            <?php } elseif($ans1[$a]["checkResult"]=="1,2") { ?>
                                                <input type='checkbox' name="c[]" value='1' checked DISABLED>1&nbsp&nbsp
                                                <input type='checkbox' name="c[]" value='2' checked DISABLED>2
                                            <?php } else { ?>
                                                <input type='checkbox' name="c[]" value='1' DISABLED>1&nbsp&nbsp
                                                <input type='checkbox' name="c[]" value='2' DISABLED>2                                
                                            <?php }                            
                                            echo '</td>';
                                            break;
                                        default:
                                            echo '<td>'."<input type='text' name=\"".$an."\" maxlength='20' value=\"".$ans1[$a]["checkResult"]."\" DISABLED></td>";
                                            break;
                                        }
                                echo '</tr>';         
                                echo "<input type='hidden' name=\"".$q."\" value=\"".$ans1[$a]["recordDetailID"]."\">";
                                //echo "<input type='hidden' name=\"".$type."\" value=\"".$answerMode."\">";
                            }
                        } 
                    echo '</tbody>';
                    echo '<tfoot class="text-primary">';
                    if ($user_1=="") {
                        $user_1="該班無抄表紀錄";
                    }
                        echo '<td>'.$user_1.'</td>';
                    echo '</tfoot>';
                echo '</table>';
                
                //中班結果<!-- 中班結果 -->
                echo '<table id="tfresult2" class="table col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">';
                    echo '<thead>';
                        echo '<th>中班結果</th>';
                    echo '</thead>';
                    echo '<tbody class="text-primary">';
                        $user_2="";
                            for ($a=0; $a < $num; $a++) {
                                $an=$a+($num);
                                $q=$a+200+($num);
                                $type=$a+400+($num);
                                if (is_null($ans2[$a]["recordDetailID"])) {
                                    echo '<tr>';
                                        echo "<td><input type=\"text\" name=\"$an\" maxlength=\"20\" value=\"無該筆紀錄，此紀錄無法修改\" DISABLED></td>";
                                    echo '</tr>';
                                }else{
                                    if ($user_2=="") {
                                        $user_2=sql_database('cname','FA.Employee','e_number',$ans2[$a]['r_member']);
                                    }
                                    $answerMode=$item[$a]["answerMode"];
                                    echo '<tr>';
                                        switch ($answerMode) {
                                            case 'choiceTF':
                                                echo '<td>';
                                                    if( $ans2[$a]["checkResult"]=="true"){
                                                        echo "<input type='radio' name=\"".$an."\" value='true' checked DISABLED>合格";
                                                        echo "<input type='radio' name=\"".$an."\" value='false' DISABLED>不合格";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$an."\" value='true' DISABLED>合格";
                                                        echo "<input type='radio' name=\"".$an."\" value='false' checked DISABLED>不合格";
                                                    }                                
                                                echo '</td>';
                                                break;
                                            case 'choiceHA':
                                                echo '<td>';
                                                    if( $ans2[$a]["checkResult"]=="handle"){
                                                        echo "<input type='radio' name=\"".$an."\" value='handle' checked DISABLED>手動";
                                                        echo "<input type='radio' name=\"".$an."\" value='auto' DISABLED>自動";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$an."\" value='handle' DISABLED>手動";
                                                        echo "<input type='radio' name=\"".$an."\" value='auto' checked DISABLED>自動";
                                                    }
                                                echo '</td>';
                                                break;
                                            case 'choiceFN':
                                                echo '<td>';
                                                    if( $ans2[$a]["checkResult"]=="OFF"){
                                                        echo "<input type='radio' name=\"".$an."\" value='OFF' checked DISABLED>OFF";
                                                        echo "<input type='radio' name=\"".$an."\" value='ON' DISABLED>ON";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$an."\" value='OFF' DISABLED>OFF";
                                                        echo "<input type='radio' name=\"".$an."\" value='ON' checked DISABLED>ON";
                                                    }
                                                echo '</td>';
                                                break;
                                            case 'choiceRL':
                                                echo '<td>';
                                                    if( $ans2[$a]["checkResult"]=="remote"){
                                                        echo "<input type='radio' name=\"".$an."\" value='remote' checked DISABLED>遠端";
                                                        echo "<input type='radio' name=\"".$an."\" value='local' DISABLED>本地";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$an."\" value='remote' DISABLED>遠端";
                                                        echo "<input type='radio' name=\"".$an."\" value='local' checked DISABLED>本地";
                                                    }
                                                echo '</td>';
                                                break;
                                            case 'choiceS12':
                                                echo '<td>';
                                                    if( $ans2[$a]["checkResult"]=="S1"){
                                                        echo "<input type='radio' name=\"".$an."\" value='S1' checked DISABLED>S1";
                                                        echo "<input type='radio' name=\"".$an."\" value='S2' DISABLED>S2";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$an."\" value='S1' DISABLED>S1";
                                                        echo "<input type='radio' name=\"".$an."\" value='S2' checked DISABLED>S2";
                                                    }
                                                echo '</td>';
                                                break;
                                            case 'choiceRG':
                                                echo '<td>';
                                                    if( $ans2[$a]["checkResult"]=="red"){
                                                        echo "<input type='radio' name=\"".$an."\" value='red' checked DISABLED>紅";
                                                        echo "<input type='radio' name=\"".$an."\" value='green' DISABLED>綠";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$an."\" value='red' DISABLED>紅";
                                                        echo "<input type='radio' name=\"".$an."\" value='green' checked DISABLED>綠";
                                                    }
                                                echo '</td>';
                                                break;
                                            case 'plural':
                                                echo '<td>';
                                                if( $ans2[$a]["checkResult"]=="1"){ ?>
                                                    <input type='checkbox' name="d[]" value='1' checked DISABLED>1&nbsp&nbsp
                                                    <input type='checkbox' name="d[]" value='2' DISABLED>2
                                                <?php } elseif($ans2[$a]["checkResult"]=="2") { ?>
                                                    <input type='checkbox' name="d[]" value='1' DISABLED>1&nbsp&nbsp
                                                    <input type='checkbox' name="d[]" value='2' checked DISABLED>2
                                                <?php } elseif($ans2[$a]["checkResult"]=="1,2") { ?>
                                                    <input type='checkbox' name="d[]" value='1' checked DISABLED>1&nbsp&nbsp
                                                    <input type='checkbox' name="d[]" value='2' checked DISABLED>2
                                                <?php } else { ?>
                                                    <input type='checkbox' name="d[]" value='1' DISABLED>1&nbsp&nbsp
                                                    <input type='checkbox' name="d[]" value='2' DISABLED>2                                
                                                <?php }                            
                                                echo '</td>';
                                                break;
                                            case 'plural_1':
                                                echo '<td>';
                                                if( $ans2[$a]["checkResult"]=="1"){ ?>
                                                    <input type='checkbox' name="e[]" value='1' checked DISABLED>1&nbsp&nbsp
                                                    <input type='checkbox' name="e[]" value='2' DISABLED>2
                                                <?php } elseif($ans2[$a]["checkResult"]=="2") { ?>
                                                    <input type='checkbox' name="e[]" value='1' DISABLED>1&nbsp&nbsp
                                                    <input type='checkbox' name="e[]" value='2' checked DISABLED>2
                                                <?php } elseif($ans2[$a]["checkResult"]=="1,2") { ?>
                                                    <input type='checkbox' name="e[]" value='1' checked DISABLED>1&nbsp&nbsp
                                                    <input type='checkbox' name="e[]" value='2' checked DISABLED>2
                                                <?php } else { ?>
                                                    <input type='checkbox' name="e[]" value='1' DISABLED>1&nbsp&nbsp
                                                    <input type='checkbox' name="e[]" value='2' DISABLED>2                                
                                                <?php }                            
                                                echo '</td>';
                                                break;
                                            default:
                                                echo '<td>'."<input type='text' name=\"".$an."\" maxlength='20' value=\"".$ans2[$a]["checkResult"]."\" DISABLED></td>";
                                                break;
                                            }
                                    echo '</tr>';         
                                    echo "<input type='hidden' name=\"".$q."\" value=\"".$ans2[$a]["recordDetailID"]."\">";
                                    //echo "<input type='hidden' name=\"".$type."\" value=\"".$answerMode."\">";
                                }
                            }
                    echo '</tbody>';
                    echo '<tfoot class="text-primary">';
                    if ($user_2=="") {
                        $user_2="該班無抄表紀錄";
                    }
                        echo '<td>'.$user_2.'</td>';
                    echo '</tfoot>';
                echo '</table>';
                 //晚班結果<!-- 晚班結果 -->    
                echo '<table id="tfresult3" class="table col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">';
                    echo '<thead>';
                        echo '<th>晚班結果</th>';
                    echo '</thead>';
                    echo '<tbody class="text-primary">';
                        for ($a=0; $a < $num; $a++) {
                            $an=$a+(($num)+($num));
                            $q=$a+200+(($num)+($num));
                            $type=$a+400+(($num)+($num));
                            $user_3='';
                            if (is_null($ans3[$a]["recordDetailID"])) {
                                echo '<tr>';
                                    echo "<td><input type=\"text\" name=\"$an\" maxlength=\"20\" value=\"無該筆紀錄，此紀錄無法修改\" Disabled></td>";
                                echo '</tr>';  
                            }else{
                                if ($user_3=='此時段無抄表紀錄'or $user_3=='') {
                                    $user_3=sql_database('cname','FA.Employee','e_number',$ans3[0]['r_member']);
                                }
                                $answerMode=$item[$a]["answerMode"];
                                    echo '<tr>';
                                        switch ($answerMode) {
                                            case 'choiceTF':
                                                echo '<td>';
                                                    if( $ans3[$a]["checkResult"]=="true"){
                                                        echo "<input type='radio' name=\"".$an."\" value='true' checked Disabled>合格";
                                                        echo "<input type='radio' name=\"".$an."\" value='false' Disabled>不合格";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$an."\" value='true' Disabled>合格";
                                                        echo "<input type='radio' name=\"".$an."\" value='false' checked Disabled>不合格";
                                                    }                                
                                                echo '</td>';
                                                break;
                                            case 'choiceHA':
                                                echo '<td>';
                                                    if( $ans3[$a]["checkResult"]=="handle"){
                                                        echo "<input type='radio' name=\"".$an."\" value='handle' checked Disabled>手動";
                                                        echo "<input type='radio' name=\"".$an."\" value='auto' Disabled>自動";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$an."\" value='handle' Disabled>手動";
                                                        echo "<input type='radio' name=\"".$an."\" value='auto' checked Disabled>自動";
                                                    }
                                                echo '</td>';
                                                break;
                                            case 'choiceFN':
                                                echo '<td>';
                                                    if( $ans3[$a]["checkResult"]=="OFF"){
                                                        echo "<input type='radio' name=\"".$an."\" value='OFF' checked Disabled>OFF";
                                                        echo "<input type='radio' name=\"".$an."\" value='ON' Disabled>ON";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$an."\" value='OFF' Disabled>OFF";
                                                        echo "<input type='radio' name=\"".$an."\" value='ON' checked Disabled>ON";
                                                    }
                                                echo '</td>';
                                                break;
                                            case 'choiceRL':
                                                echo '<td>';
                                                    if( $ans3[$a]["checkResult"]=="remote"){
                                                        echo "<input type='radio' name=\"".$an."\" value='remote' checked Disabled>遠端";
                                                        echo "<input type='radio' name=\"".$an."\" value='local' Disabled>本地";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$an."\" value='remote' Disabled>遠端";
                                                        echo "<input type='radio' name=\"".$an."\" value='local' checked Disabled>本地";
                                                    }
                                                echo '</td>';
                                                break;
                                            case 'choiceS12':
                                                echo '<td>';
                                                    if( $ans3[$a]["checkResult"]=="S1"){
                                                        echo "<input type='radio' name=\"".$an."\" value='S1' checked Disabled>S1";
                                                        echo "<input type='radio' name=\"".$an."\" value='S2' Disabled>S2";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$an."\" value='S1' Disabled>S1";
                                                        echo "<input type='radio' name=\"".$an."\" value='S2' checked Disabled>S2";
                                                    }
                                                echo '</td>';
                                                break;
                                            case 'choiceRG':
                                                echo '<td>';
                                                    if( $ans3[$a]["checkResult"]=="red"){
                                                        echo "<input type='radio' name=\"".$an."\" value='red' checked Disabled>紅";
                                                        echo "<input type='radio' name=\"".$an."\" value='green' Disabled>綠";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$an."\" value='red' Disabled>紅";
                                                        echo "<input type='radio' name=\"".$an."\" value='green' checked Disabled>綠";
                                                    }
                                                echo '</td>';
                                                break;
                                            case 'plural':
                                                echo '<td>';
                                                if( $ans3[$a]["checkResult"]=="1"){ ?>
                                                    <input type='checkbox' name="f[]" value='1' checked Disabled>1&nbsp&nbsp
                                                    <input type='checkbox' name="f[]" value='2' Disabled>2
                                                <?php } elseif($ans3[$a]["checkResult"]=="2") { ?>
                                                    <input type='checkbox' name="f[]" value='1' Disabled>1&nbsp&nbsp
                                                    <input type='checkbox' name="f[]" value='2' checked Disabled>2
                                                <?php } elseif($ans3[$a]["checkResult"]=="1,2") { ?>
                                                    <input type='checkbox' name="f[]" value='1' checked Disabled>1&nbsp&nbsp
                                                    <input type='checkbox' name="f[]" value='2' checked Disabled>2
                                                <?php } else { ?>
                                                    <input type='checkbox' name="f[]" value='1' Disabled>1&nbsp&nbsp
                                                    <input type='checkbox' name="f[]" value='2' Disabled>2                                
                                                <?php }                            
                                                echo '</td>';
                                                break;
                                            case 'plural_1':
                                                echo '<td>';
                                                if( $ans3[$a]["checkResult"]=="1"){ ?>
                                                    <input type='checkbox' name="g[]" value='1' checked Disabled>1&nbsp&nbsp
                                                    <input type='checkbox' name="g[]" value='2' Disabled>2
                                                <?php } elseif($ans3[$a]["checkResult"]=="2") { ?>
                                                    <input type='checkbox' name="g[]" value='1' Disabled>1&nbsp&nbsp
                                                    <input type='checkbox' name="g[]" value='2' checked Disabled>2
                                                <?php } elseif($ans3[$a]["checkResult"]=="1,2") { ?>
                                                    <input type='checkbox' name="g[]" value='1' checked Disabled>1&nbsp&nbsp
                                                    <input type='checkbox' name="g[]" value='2' checked Disabled>2
                                                <?php } else { ?>
                                                    <input type='checkbox' name="g[]" value='1' Disabled>1&nbsp&nbsp
                                                    <input type='checkbox' name="g[]" value='2' Disabled>2                                
                                                <?php }                            
                                                echo '</td>';
                                                break;
                                            default:
                                                echo '<td>'."<input type='text' name=\"".$an."\" maxlength='20' value=\"".$ans3[$a]["checkResult"]."\" Disabled></td>";
                                                break;
                                            }
                                    echo '</tr>';                        
                             echo "<input type='hidden' name=\"".$q."\" value=\"".$ans3[$a]["recordDetailID"]."\">";
                             //echo "<input type='hidden' name=\"".$type."\" value=\"".$answerMode."\">";
                            }
                        }                    
                    echo '</tbody>';
                    echo '<tfoot class="text-primary">';
                    if ($user_3=="") {
                        $user_3="該班無抄表紀錄";
                    }
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
                <textarea class="form-control" name="remark" aria-label="With textarea" ><?= $updatainfo[0]["remark"] ?></textarea>
            </div>
            <input type='hidden' name='MasterID' value='<?= $MasterID?>'>
            <?php
                if ($userRank<3) {//主管等級
                echo '<div class="row my-3">';
                    echo '<div class="col text-left">';
                    echo '<p class="d-inline font-weight-bold">主管：</p>';
                    echo '<p class="d-inline text-primary">'.$checkuser.'&nbsp&nbsp;</p>';
                    echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="mgrCheck" value="mgcheck">主管確認</p>';
                    echo '</div>';
                    echo '<div class="col text-right">';
                    echo '<p class="d-inline font-weight-bold">檢查者：</p>';
                    echo '<p class="d-inline text-primary" name="reMumber">'.$eeName.'&nbsp&nbsp</p>';
                    echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="eeCheck" value="mbcheck" checked disabled >檢查人確認</p>';
                    echo '</div>';
                echo '</div>' ;
                } else {//檢查者登錄
                echo '<div class="row my-3">';
                    echo '<div class="col text-left">';
                    echo '<p class="d-inline font-weight-bold">主管：</p>';
                    echo '<p class="d-inline text-primary">'.$managerID.'</p>';
                    echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="mgrCheck" value="mgcheck" disabled>主管確認</p>';
                    echo '</div>';
                    echo '<div class="col text-right">';
                    echo '<p class="d-inline font-weight-bold">檢查者：</p>';
                    echo '<p class="d-inline text-primary" name="reMumber">'.$checkuser.'</p>';
                    echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="eeCheck" value="mbcheck">檢查人確認</p>';
                    echo '</div>';
                echo '</div>' ;  
                }                
            ?>
            
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