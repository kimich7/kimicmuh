<?php 
include("php/CMUHconndata.php");
include("php/fun.php");
include("page_searchfilter.php");
    session_start();
    $checkuser=$_SESSION["login_member"];
    $checkuserID=sql_database('e_number','FA.Employee','cname',$checkuser);
    $str_member="SELECT * FROM FA.Employee WHERE e_number='$checkuserID'";
    $member=$pdo->query($str_member)->fetch();
$date='WHERE empCheckdate = \'2019-08-23\' and check_number =1';

$str="SELECT * FROM FA.Water_System_Record_Master $date and check_manager is null";
$query=$pdo->query($str);
while ($show = $query->fetch()) {
    echo $show['b_number'].'</br>';
    echo $show['recordID'].'</br>';
}
$pageRow_record=10;//每頁的筆數
$page_num=1;//預設的頁數
//更新$page_num        
if (isset($_GET['page'])) {
    $page_num=$_GET['page'];
}
@$startRow_record=($page_num-1)*$pageRow_record;

$condition="WHERE (e.e_number='$checkuserID') and(";// and (check_number=1) and (check_manager IS NULL or check_manager=0)';
//所有的資料 (check_manager IS NULL or check_manager=0) and
$sqlstr_total="SELECT a.* FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID LEFT JOIN FA.CheckStatus as p ON a.status=p.id left join FA.securityKind as k ON a.sysID=k.sysID left join FA.securityemp as e ON k.id=e.sid $condition b.cname like '%未%' or c.B_name like '%未%' or s.sysName like '%未%' or a.rDate like '%未%' or p.checkName like '%未%')";
//篩選後給每頁的筆數
$sqlstr_page="SELECT a.* FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID LEFT JOIN FA.CheckStatus as p ON a.status=p.id left join FA.securityKind as k ON a.sysID=k.sysID left join FA.securityemp as e ON k.id=e.sid $condition b.cname like '%未%' or c.B_name like '%未%' or s.sysName like '%未%' or a.rDate like '%未%' or p.checkName like '%未%') ORDER BY recordID ASC OFFSET $startRow_record ROWS FETCH NEXT $pageRow_record ROWS ONLY";
//總資料數量
$totalstr_num="SELECT COUNT(recordID) FROM FA.Water_System_Record_Master as a LEFT JOIN FA.Employee as b on a.r_member=b.e_number or a.managerID=b.e_number left join FA.Building as c on a.b_number=c.b_number left join FA.Equipment_System_Group as s on a.sysID=s.sysID LEFT JOIN FA.CheckStatus as p ON a.status=p.id left join FA.securityKind as k ON a.sysID=k.sysID left join FA.securityemp as e ON k.id=e.sid $condition b.cname like '%未%' or c.B_name like '%未%' or s.sysName like '%未%' or a.rDate like '%未%' or p.checkName like '%未%')";

$sql_page=$pdo->query($sqlstr_page);
            $sql_total=$pdo->query($sqlstr_total);
            $total_num=CURRENT($pdo->query($totalstr_num)->fetch());
            
            //本業開始的筆數
            $i=0;
            $j=$i+1000;
            $k=$i+2000;
            $a=0;
            echo '<table id="searchlist" class="display tablesorter table table-striped table-bordered table-hover col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12 table-sm order-table" align="center" width="80%">';
            echo '<thead class="thead-light" >';
            echo '<tr align="center">';
            echo '<th class="th-sm">項  目</th>';
            echo '<th class="th-sm">主  管</th>';
            echo '<th class="th-sm">檢查人</th>';
            echo '</tr>';
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
                        echo "<input type='checkbox'  name=\"".$a."\" value=\"".$mgcheck."\" checked disabled>";
                    } else {
                        echo "<input type='checkbox' class='managerCheck' name=\"".$a."\" value=\"".$mgcheck."\" disabled>";
                    }   
                    echo '</td>';
                    echo '<td width="10%" align="center">';
                    if ($data_page['check_number']==1) {
                        echo "<input type='checkbox'  class='employeeCheck' name=\"".$j."\" value=\"".$mbcheck."\" checked disabled>";
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
                        @$prev=$page_num-1;
                        echo "<td><a href='mtlistfilter.php?page=1\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".未."\"&key=next_page'>"."[第一頁]".'</a></td>';
                        echo "<td><a href='mtlistfilter.php?page={$prev}\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".未."\"&key=next_page'>"."[<<<上一頁]".'</a></td>';
                        
                    }
                    if ($page_num<$total_page) {
                        @$next=$page_num+1;
                        echo "<td>"."<a href='mtlistfilter.php?page={$next}\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".未."\"&key=next_page'>"."[下一頁>>>]".'</a></td>';
                        echo "<td><a href='mtlistfilter.php?page=$total_page\"&startdate=\"".$start_date."\"&enddate=\"".$end_date."\"&keyword=\"".未."\"&key=next_page'>".'[最末頁]'.'</a></td>';
                    }
                echo '</tr>';
            echo '</table>';
            
            //分頁按鈕一次七頁
            $phpfile = 'mtlistfilter.php';
            $page= isset($_GET['page'])?$_GET['page']:1;        
            $getpageinfo = page($page,$total_num,$phpfile,$start_date,$end_date,未,'next_page');

            echo '<div align="center">'; 
                echo @$getpageinfo['pagecode'];//顯示分頁的html語法
            echo '</div>';
            //分頁按鈕end
?>

