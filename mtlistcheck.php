<?php
    include("php/CMUHconndata.php");
    include("php/fun.php");
    session_start();
    $checkuser=$_SESSION["login_member"];
    $checkuserID=sql_database('e_number','FA.Employee','cname',$checkuser);
    
    //20190712修改(未完成)
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
    //20190712修改(未完成)


    $str_member="SELECT * FROM FA.Employee WHERE e_number='$checkuserID'";
    $member=$pdo->query($str_member)->fetch();
    if (isset($_POST["action"])&&($_POST["action"]=="check")) {
        $total_num=$_POST["total_num"];
        for ($i=0; $i<$total_num ; $i++) { 
            $j=$i+1000;
            $k=$i+2000;                    
            $a=$i;
            if (isset($_POST["$j"])) {
                $memberCheck=1;
            } else {
                $memberCheck=0;
            }
            if (isset($_POST["$a"])) {
                $managerCheck=1;
            } else {
                $managerCheck=0;
            }
            if (isset($_POST["$k"])) {
                $rdID=$_POST["$k"];
            }else{
                break;
            }
            if ($member['rank']<3) {
                $sql="UPDATE FA.Water_System_Record_Master SET  check_manager=:check_manager,managerID=:managerID WHERE recordID=:ID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':check_manager',$managerCheck,PDO::PARAM_STR);
                $stmt->bindParam(':managerID',$checkuserID,PDO::PARAM_STR);
            } else {
                $sql="UPDATE FA.Water_System_Record_Master SET  check_number=:check_number,r_member=:r_member WHERE recordID=:ID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':check_number',$memberCheck,PDO::PARAM_STR);
                $stmt->bindParam(':r_member',$checkuserID,PDO::PARAM_STR);
            }
            $stmt->bindParam(':ID',$rdID,PDO::PARAM_INT);
            $stmt->execute();
            $j=$i+1000;
            $k=$i+2000;     
        }
        $pdo=null;
        header("Location:mtlistcheck.php");
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
    <title>設備保養表單未簽核清單</title>
</head>

<body>
    <!-- header網頁標題 -->
    <header>
       <div id="header"></div>
    </header>
    <form action="" method="post" name="checklist">
    <!-- 未簽核清單 -->
    <section class="container-fluid">
        <h1 class="text-center">設備保養表單未簽核清單</h1>
        <div class="list-group mx-5 my-5">
        <?php
        $pageRow_record=10;//每頁的筆數
        $page_num=1;//預設的頁數
        //更新$page_num        
        if (isset($_GET['page'])) {
            $page_num=$_GET['page'];
        }
        $startRow_record=($page_num-1)*$pageRow_record;
        if ($member['rank']<3) {
            //所有的資料 (check_manager IS NULL or check_manager=0) and
            $sqlstr_total="SELECT * FROM FA.Water_System_Record_Master WHERE (check_number=1) and (check_manager IS NULL or check_manager=0)";
            //篩選後給每頁的筆數
            $sqlstr_page="SELECT * FROM FA.Water_System_Record_Master WHERE (check_number=1) and (check_manager IS NULL or check_manager=0) ORDER BY recordID ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
            //總資料數量
            $totalstr_num="SELECT COUNT(recordID) FROM FA.Water_System_Record_Master WHERE (check_number=1) and (check_manager IS NULL or check_manager=0)";
        } else {
            //所有的資料
            $sqlstr_total="SELECT * FROM FA.Water_System_Record_Master WHERE check_number IS NULL or check_number=0 or check_manager IS NULL or check_manager=0";
            //篩選後給每頁的筆數
            $sqlstr_page="SELECT * FROM FA.Water_System_Record_Master WHERE check_number IS NULL or check_number=0 or check_manager IS NULL or check_manager=0 ORDER BY recordID ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
            //總資料數量
            $totalstr_num="SELECT COUNT(recordID) FROM FA.Water_System_Record_Master WHERE check_number IS NULL or check_number=0 or check_manager IS NULL or check_manager=0";
        }
        $sql_page=$pdo->query($sqlstr_page);
        $sql_total=$pdo->query($sqlstr_total);
        $total_num=CURRENT($pdo->query($totalstr_num)->fetch());
        
        //本業開始的筆數
        $i=0;
        $j=$i+1000;
        $k=$i+2000;
        $a=0;
        echo '<table border="1" align="center" width="80%">';
        echo '<thead align="center">';
        echo '<th>項  目</th>';
        echo '<th>主  管</th>';
        echo '<th>檢查人</th>';
        echo '</thead>';
        echo '<tbody>';
        $mgcheck="mgcheck";
        $mbcheck="mbcheck";
        while ($data_page = $sql_page->fetch()) {
            $a=$i;
            echo '<tr>';
            $buildName=sql_database('B_name','FA.Building','b_number',$data_page['b_number']);
            $sysName=sql_database('sysName','FA.Equipment_System_Group','sysID',$data_page['sysID']);
                echo "<td width='80%'><a href='mtchecktable.php?id=\"".$data_page['recordID']."\"&build=\"".$data_page['b_number']."\"&r_date=\"".$data_page['rDate']."\"&member=\"".$data_page['r_member']."\"&manage=\"".$data_page['managerID']."\"&checkMember=\"".$data_page['check_number']."\"&checkManager=\"".$data_page['check_manager']."\"&sysID=\"".$data_page['sysID']."\"' class=\".list-group-item list-group-item-action.\">".$buildName."-".$sysName."-".$data_page['rDate'].'</a></td>';
                echo '<td width="10%" align="center">';
                if ($data_page['check_manager']==1) {
                    echo "<input type='checkbox' class='managerCheck' name=\"".$a."\" value=\"".$mgcheck."\" checked disabled>";
                } else {
                    echo "<input type='checkbox' class='managerCheck' name=\"".$a."\" value=\"".$mgcheck."\" disabled>";
                }   
                echo '</td>';
                echo '<td width="10%" align="center">';
                if ($data_page['check_number']==1) {
                    echo "<input type='checkbox' class='employeeCheck' name=\"".$j."\" value=\"".$mbcheck."\" checked disabled>";
                } else {
                    echo "<input type='checkbox' class='employeeCheck' name=\"".$j."\" value=\"".$mbcheck."\" disabled>";
                }  
                echo '</td>';
                $q=$data_page['recordID'];
                echo "<input type='hidden' name=\"".$k."\" value=\"".$q."\">";
            $i++;
            $j++;
            $k++;
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        
        //計算總頁數
        $total_page=ceil($total_num/$pageRow_record);
        echo '<table border="0" align="center">';    
            echo '<tr>';
                echo '<td><h5>'.'未簽核清單共計'.$total_num.'筆(共'.$total_page.'頁)'.'</h5></td>';
            echo '</tr>';
        echo '</table>';
        echo '<table border="0" align="center">';
            echo '<tr>';
                if ($page_num>1) {
                    $prev=$page_num-1;
                    echo '<td><a href="mtlistcheck.php?page=1">'."[第一頁]".'</a></td>';
                    echo "<td><a href=\"mtlistcheck.php?page={$prev}\">"."[<<<上一頁]".'</a></td>';
                }
                if ($page_num<$total_page) {
                    $next=$page_num+1;
                    echo "<td>"."<a href=\"mtlistcheck.php?page={$next}\">"."[下一頁>>>]".'</a></td>';
                    echo "<td><a href=\"mtlistcheck.php?page=$total_page\">".'[最末頁]'.'</a></td>';
                }
            echo '</tr>';
        echo '</table>';
        echo '<nav aria-label="Page navigation example" >';
                echo '<ul class="pagination">';
                        for ($i=1; $i <= $total_page; $i++) {
                            if ($i==$page_num) {
                                echo "<li class=\"page-item\"><span class='page-link text-danger' href=#><b>{$i}</b></span></li>";
                            } else {
                                echo "<li class=\"page-item\"><a class='page-link' href=\"mtlistcheck.php?page={$i}\">{$i}</a></li>";
                            }
                        }
                echo '</ul>';
        echo '</nav>';
        ?>
        </div>
        <input type="hidden" name="total_num" value="<?= $total_num?>">;
        <input type="hidden" name="action" value="check">
        <!-- 送出鈕 -->
        <div class="d-flex justify-content-end">
            <button class="my-3 px-3 py-1 btn-outline-info text-dark" type="submit">送出</button>
        </div>
    </section>
    </form>
    <!-- footer網頁尾頁 -->
    <footer>
        <div id="footer"></div>
    </footer>
</body>
</html>