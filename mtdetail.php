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

    // $userRank=rank($checkuserID);//登入者的等級
    // if ($userRank<3) {//主管登錄
    //     $tatus=rankStatus($MasterID);
    //         $eeID=$tatus['employeeID'];
    //     $eeName=sql_database('cname','FA.Employee','e_number',$eeID);        
    // } else {//檢查者登錄
    //     $managerID = '';        
    // }

    //20190703新增-判斷權限分類
    
    $securityNoStr="SELECT e.sid,e.e_number,k.sysID FROM FA.securityemp as e LEFT JOIN FA.securityKind as k on e.sid=k.id  WHERE e.e_number='$checkuserID' AND k.sysID='$sysNo'";
    $securityNo=$pdo->Query($securityNoStr)->fetch();
    if (isset($securityNo) and $securityNo!='') {
        $sNumber=$securityNo['sid'];//權限區域
        if ($sNumber>4 and $sNumber<9) {
            $checksum=1;//可簽核-身分主管
        } else {
            $checksum=2;//可簽核-檢查者
        }        
    } else {
        $checksum=3;//只能看
    }
    $status=rankStatus($MasterID);
    @$checkmanID=$status['employeeID'];//確認是否有檢查者
    @$managerID=$status['managerID'];//確認是否有主管檢查者
    @$echeck=$status['eeCheck'];//確認是否做檢查了
    @$mcheck=$status['mgrCheck'];//確認是否做檢查了
    
    if (!isset($echeck) or $echeck==0 ) {
        $checkmanID='尚未檢查';
        $echeck=0;
    }else{
        $echeck=1;
    }
    if (!isset($mcheck) or $mcheck==0 ) {
        $managerID='主管尚未核准';
        $mcheck=0;
    }else{
        $mcheck=1;
    }    
    //權限分類功能結束

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
    $user_1No=$ans1[0]["r_member"];
    $user_2No=$ans2[0]["r_member"];
    $user_3No=$ans3[0]["r_member"];
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
    
    // if (isset($_POST["action"])&&($_POST["action"]=="update")) {        
    //     $recordID=$_POST['MasterID'];
    //     $checksum=$_POST['checksum'];
    //     switch ($checksum) {
    //         case '1':
    //             if ($_POST["mgrCheck"]=='mgcheck') {
    //                 $check_manager=1;                    
    //             }else{
    //                 $check_manager=0;
    //                 //$checkuserID=null;                    
    //             }
    //             $sql="UPDATE $sysMaster SET managerID=:managerID , check_manager=:check_manager WHERE recordID=:ID";
    //             $stmt = $pdo->prepare($sql);
    //             $stmt->bindParam(':managerID',$checkuserID,PDO::PARAM_STR);
    //             $stmt->bindParam(':check_manager',$check_manager,PDO::PARAM_STR);
    //             break;
    //         case '2':
    //             if ($_POST["eeCheck"]=='mbcheck') {
    //                 $check_employee=1;
    //             } else {
    //                 $check_employee=0;
    //                 //$checkuserID=null;
    //             }      
    //             $sql="UPDATE $sysMaster SET r_member=:r_member , check_number=:check_number WHERE recordID=:ID";
    //             $stmt = $pdo->prepare($sql);
    //             $stmt->bindParam(':r_member',$checkuserID,PDO::PARAM_STR);
    //             $stmt->bindParam(':check_number',$check_employee,PDO::PARAM_STR);          
    //             break;
    //         case '3':
    //             header("Location: mtlistcheck.php");
    //             break;
    //     }        
    //     $stmt->bindParam(':ID',$recordID,PDO::PARAM_INT);
    //     $stmt->execute();      
    //     $pdo=null;
    //     header("Location: mtlistcheck.php");    
    // }
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
                        $checkName[$a]=sql_database('equipCheckName',$equipTable,'equipCheckID',$item[$a]["equipCheckID"]);//找出對應equipCheckID的equipCheckName的名稱                        
                    //問號結尾
                        echo '<tr>';
                            //檢查項目<!--檢查項目-->
                            echo '<td><h10>'.$checkName[$a].'</h10></td>';
                            //參考值<!--參考值-->
                            echo '<td><h10>'.$item[$a]["ref"].'</h10></td>';
                            $q=$a+200;
                            $an=$a;//結果答案
                            //$type=$a+400;
                            $answerMode=$item[$a]["answerMode"];
                            if (is_null($ans1[$a]["recordDetailID"])) {                               
                                echo "<td><input type=\"text\" name=\"$an\" maxlength=\"20\" value=\"無該筆紀錄，此紀錄無法修改\" DISABLED></td>";
                            }else{                                                              
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
                                echo "<input type='hidden' name=\"".$q."\" value=\"".$ans1[$a]["recordDetailID"]."\">";
                            }
                            //中班
                            $b=$a+($num);//中班答案欄位的名稱
                            $q2=$a+200+($num);
                            //$type=$a+400+($num);
                            if (is_null($ans2[$a]["recordDetailID"])) {
                                    echo "<td><input type=\"text\" name=\"$b\" maxlength=\"20\" value=\"無該筆紀錄，此紀錄無法修改\" DISABLED></td>";
                            }else{
                                $answerMode=$item[$a]["answerMode"];
                                    switch ($answerMode) {
                                        case 'choiceTF':
                                            echo '<td>';
                                                if( $ans2[$a]["checkResult"]=="true"){
                                                    echo "<input type='radio' name=\"".$b."\" value='true' checked DISABLED>合格";
                                                    echo "<input type='radio' name=\"".$b."\" value='false' DISABLED>不合格";
                                                } else {
                                                    echo "<input type='radio' name=\"".$b."\" value='true' DISABLED>合格";
                                                    echo "<input type='radio' name=\"".$b."\" value='false' checked DISABLED>不合格";
                                                }                                
                                            echo '</td>';
                                            break;
                                        case 'choiceHA':
                                            echo '<td>';
                                                if( $ans2[$a]["checkResult"]=="handle"){
                                                    echo "<input type='radio' name=\"".$b."\" value='handle' checked DISABLED>手動";
                                                    echo "<input type='radio' name=\"".$b."\" value='auto' DISABLED>自動";
                                                } else {
                                                    echo "<input type='radio' name=\"".$b."\" value='handle' DISABLED>手動";
                                                    echo "<input type='radio' name=\"".$b."\" value='auto' checked DISABLED>自動";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'choiceFN':
                                            echo '<td>';
                                                if( $ans2[$a]["checkResult"]=="OFF"){
                                                    echo "<input type='radio' name=\"".$b."\" value='OFF' checked DISABLED>OFF";
                                                    echo "<input type='radio' name=\"".$b."\" value='ON' DISABLED>ON";
                                                } else {
                                                    echo "<input type='radio' name=\"".$b."\" value='OFF' DISABLED>OFF";
                                                    echo "<input type='radio' name=\"".$b."\" value='ON' checked DISABLED>ON";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'choiceRL':
                                            echo '<td>';
                                                if( $ans2[$a]["checkResult"]=="remote"){
                                                    echo "<input type='radio' name=\"".$b."\" value='remote' checked DISABLED>遠端";
                                                    echo "<input type='radio' name=\"".$b."\" value='local' DISABLED>本地";
                                                } else {
                                                    echo "<input type='radio' name=\"".$b."\" value='remote' DISABLED>遠端";
                                                    echo "<input type='radio' name=\"".$b."\" value='local' checked DISABLED>本地";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'choiceS12':
                                            echo '<td>';
                                                if( $ans2[$a]["checkResult"]=="S1"){
                                                    echo "<input type='radio' name=\"".$b."\" value='S1' checked DISABLED>S1";
                                                    echo "<input type='radio' name=\"".$b."\" value='S2' DISABLED>S2";
                                                } else {
                                                    echo "<input type='radio' name=\"".$b."\" value='S1' DISABLED>S1";
                                                    echo "<input type='radio' name=\"".$b."\" value='S2' checked DISABLED>S2";
                                                }
                                            echo '</td>';
                                            break;
                                        case 'choiceRG':
                                            echo '<td>';
                                                if( $ans2[$a]["checkResult"]=="red"){
                                                    echo "<input type='radio' name=\"".$b."\" value='red' checked DISABLED>紅";
                                                    echo "<input type='radio' name=\"".$b."\" value='green' DISABLED>綠";
                                                } else {
                                                    echo "<input type='radio' name=\"".$b."\" value='red' DISABLED>紅";
                                                    echo "<input type='radio' name=\"".$b."\" value='green' checked DISABLED>綠";
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
                                            echo '<td>'."<input type='text' name=\"".$b."\" maxlength='20' value=\"".$ans2[$a]["checkResult"]."\" DISABLED></td>";
                                            break;
                                        }
                                echo "<input type='hidden' name=\"".$q2."\" value=\"".$ans2[$a]["recordDetailID"]."\">";
                            }
                            //晚班
                            $c=$a+(($num)+($num));
                            $q3=$a+200+(($num)+($num));
                            //$type=$a+400+(($num)+($num));
                            if (is_null($ans3[$a]["recordDetailID"])) {
                                    echo "<td><input type=\"text\" name=\"$c\" maxlength=\"20\" value=\"無該筆紀錄，此紀錄無法修改\" Disabled></td>";
                            }else{
                                $answerMode=$item[$a]["answerMode"];
                                        switch ($answerMode) {
                                            case 'choiceTF':
                                                echo '<td>';
                                                    if( $ans3[$a]["checkResult"]=="true"){
                                                        echo "<input type='radio' name=\"".$c."\" value='true' checked Disabled>合格";
                                                        echo "<input type='radio' name=\"".$c."\" value='false' Disabled>不合格";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$c."\" value='true' Disabled>合格";
                                                        echo "<input type='radio' name=\"".$c."\" value='false' checked Disabled>不合格";
                                                    }                                
                                                echo '</td>';
                                                break;
                                            case 'choiceHA':
                                                echo '<td>';
                                                    if( $ans3[$a]["checkResult"]=="handle"){
                                                        echo "<input type='radio' name=\"".$c."\" value='handle' checked Disabled>手動";
                                                        echo "<input type='radio' name=\"".$c."\" value='auto' Disabled>自動";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$c."\" value='handle' Disabled>手動";
                                                        echo "<input type='radio' name=\"".$c."\" value='auto' checked Disabled>自動";
                                                    }
                                                echo '</td>';
                                                break;
                                            case 'choiceFN':
                                                echo '<td>';
                                                    if( $ans3[$a]["checkResult"]=="OFF"){
                                                        echo "<input type='radio' name=\"".$c."\" value='OFF' checked Disabled>OFF";
                                                        echo "<input type='radio' name=\"".$c."\" value='ON' Disabled>ON";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$c."\" value='OFF' Disabled>OFF";
                                                        echo "<input type='radio' name=\"".$c."\" value='ON' checked Disabled>ON";
                                                    }
                                                echo '</td>';
                                                break;
                                            case 'choiceRL':
                                                echo '<td>';
                                                    if( $ans3[$a]["checkResult"]=="remote"){
                                                        echo "<input type='radio' name=\"".$c."\" value='remote' checked Disabled>遠端";
                                                        echo "<input type='radio' name=\"".$c."\" value='local' Disabled>本地";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$c."\" value='remote' Disabled>遠端";
                                                        echo "<input type='radio' name=\"".$c."\" value='local' checked Disabled>本地";
                                                    }
                                                echo '</td>';
                                                break;
                                            case 'choiceS12':
                                                echo '<td>';
                                                    if( $ans3[$a]["checkResult"]=="S1"){
                                                        echo "<input type='radio' name=\"".$c."\" value='S1' checked Disabled>S1";
                                                        echo "<input type='radio' name=\"".$c."\" value='S2' Disabled>S2";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$c."\" value='S1' Disabled>S1";
                                                        echo "<input type='radio' name=\"".$c."\" value='S2' checked Disabled>S2";
                                                    }
                                                echo '</td>';
                                                break;
                                            case 'choiceRG':
                                                echo '<td>';
                                                    if( $ans3[$a]["checkResult"]=="red"){
                                                        echo "<input type='radio' name=\"".$c."\" value='red' checked Disabled>紅";
                                                        echo "<input type='radio' name=\"".$c."\" value='green' Disabled>綠";
                                                    } else {
                                                        echo "<input type='radio' name=\"".$c."\" value='red' Disabled>紅";
                                                        echo "<input type='radio' name=\"".$c."\" value='green' checked Disabled>綠";
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
                                                echo '<td>'."<input type='text' name=\"".$c."\" maxlength='20' value=\"".$ans3[$a]["checkResult"]."\" Disabled></td>";
                                                break;
                                            }          
                             echo "<input type='hidden' name=\"".$q3."\" value=\"".$ans3[$a]["recordDetailID"]."\">";
                            }
                        echo '</tr>';
                    //php開頭                           
                        }                        
                    echo '</tbody>';
                    echo '<tfoot class="text-primary">';
                        echo '<td>巡檢人員</td>';
                        echo '<td></td>';
                        echo '<td>'.$user_1.'</td>';
                        echo '<td>'.$user_2.'</td>';
                        echo '<td>'.$user_3.'</td>';
                    echo '</tfoot>';                    
                echo '</table>'; 
                // 
            echo '</div>';
            ?> 
                 
            <!-- 備註欄 -->
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">備註：</span>
                </div>
                <textarea class="form-control" name="remark" aria-label="With textarea" DISABLED><?= $updatainfo[0]["remark"] ?></textarea>
            </div>
            <input type='hidden' name='MasterID' value='<?= $MasterID?>'>
            <input type='hidden' name='checksum' value='<?= $checksum?>'>
            <?php
                switch ($checksum) {
                    case '1':
                        echo '<div class="row my-3">';
                            echo '<div class="col text-left">';
                            echo '<p class="d-inline font-weight-bold">主管：</p>';
                            echo '<p class="d-inline text-primary">'.$checkuser.'&nbsp&nbsp&nbsp</p>';
                            if ($mcheck==1) {
                                echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="mgrCheck" value="mgcheck" checked>主管確認</p>';
                            echo '</div>';
                            } else {
                                echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="mgrCheck" value="mgcheck">主管確認</p>';
                            echo '</div>';
                            }                            
                            echo '<div class="col text-right">';
                            echo '<p class="d-inline font-weight-bold">檢查者：</p>';
                            echo '<p class="d-inline text-primary" name="reMumber">'.$checkmanID.'&nbsp&nbsp&nbsp</p>';
                            echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="eeCheck" value="mbcheck" checked disabled >檢查人確認</p>';
                            echo '</div>';
                        echo '</div>' ;
                        break;
                    
                    case '2':
                        echo '<div class="row my-3">';
                            echo '<div class="col text-left">';
                            echo '<p class="d-inline font-weight-bold">主管：</p>';
                            echo '<p class="d-inline text-primary">'.$managerID.'&nbsp&nbsp&nbsp</p>';
                            echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="mgrCheck" value="mgcheck" disabled>主管確認</p>';
                            echo '</div>';
                            echo '<div class="col text-right">';
                            echo '<p class="d-inline font-weight-bold">檢查者：</p>';
                            echo '<p class="d-inline text-primary" name="reMumber">'.$checkuser.'&nbsp&nbsp&nbsp</p>';
                            if ($echeck==1) {
                                echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="eeCheck" value="mbcheck" checked>檢查人確認</p>';
                                echo '</div>';
                            } else {
                                echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="eeCheck" value="mbcheck" >檢查人確認</p>';
                                echo '</div>';
                            }
                        echo '</div>' ;
                        break;
                    
                    case '3':
                        echo '<div class="row my-3">';
                            echo '<div class="col text-left">';
                            echo '<p class="d-inline font-weight-bold">主管：</p>';
                            echo '<p class="d-inline text-primary">'.$managerID.'&nbsp&nbsp&nbsp</p>';
                            if ($mcheck==1) {
                                echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="mgrCheck" value="mgcheck" disabled checked>主管確認</p>';
                                echo '</div>';                                
                            } else {
                                echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="mgrCheck" value="mgcheck" disabled >主管確認</p>';
                                echo '</div>';
                            }                             
                            echo '<div class="col text-right">';
                            echo '<p class="d-inline font-weight-bold">檢查者：</p>';
                            echo '<p class="d-inline text-primary" name="reMumber">'.$checkmanID.'&nbsp&nbsp&nbsp</p>';
                            if ($echeck==1) {
                                echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="eeCheck" value="mbcheck" disabled checked>檢查人確認</p>';
                            echo '</div>';
                            } else {
                                echo '<p class="d-inline font-weight-bold" name="reMumber"><input type="checkbox" name="eeCheck" value="mbcheck" disabled>檢查人確認</p>';
                                echo '</div>';
                            }                            
                        echo '</div>' ;
                        break;
                }
            ?>
            
            <!-- 傳送值到資料庫中 -->
            <input type="hidden" name="action" value="update">
            <!-- 送出鈕 -->
            <div class="d-flex justify-content-end">
                <a href='mtlistsearch.php' type='button' class="my-3 px-3 py-1 btn-outline-info text-dark">離開</button>
            </div>
        </form>
    </div>
</body>

</html>